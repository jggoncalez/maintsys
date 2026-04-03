# 03 - Banco de Dados

## 📊 Schema de Dados

### Tabela: `users` (Laravel padrão + Spatie)
Adicionar suporte a roles do Spatie:

```php
Schema::table('users', function (Blueprint $table) {
    // Spatie irá adicionar via plugin
    // Colunas existentes: id, name, email, password, timestamps
});
```

---

### Tabela: `machines`
Máquinas industriais a serem monitoradas

```sql
CREATE TABLE machines (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    serial_number VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    model VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM('operational','maintenance','critical','offline') DEFAULT 'operational',
    installed_at DATE NOT NULL,
    description TEXT NULLABLE,
    image VARCHAR(255) NULLABLE,
    last_reading_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_location (location),
    INDEX idx_serial_number (serial_number)
);
```

**Scopes:**
- `scopeOperational()` - WHERE status = 'operational'
- `scopeInMaintenance()` - WHERE status = 'maintenance'
- `scopeCritical()` - WHERE status = 'critical'
- `scopeOffline()` - WHERE status = 'offline'

**Boot Hook:** Ao atualizar `status`, criar StatusAlert + notificação

---

### Tabela: `service_orders`
Ordens de serviço (preventiva/corretiva)

```sql
CREATE TABLE service_orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    machine_id BIGINT UNSIGNED NOT NULL,
    technician_id BIGINT UNSIGNED NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('preventive','corrective') NOT NULL,
    priority ENUM('low','medium','high','critical') DEFAULT 'medium',
    status ENUM('open','in_progress','completed','cancelled') DEFAULT 'open',
    started_at TIMESTAMP NULLABLE,
    completed_at TIMESTAMP NULLABLE,
    resolution_notes TEXT NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,

    INDEX idx_machine_id (machine_id),
    INDEX idx_technician_id (technician_id),
    INDEX idx_status (status),
    INDEX idx_type (type)
);
```

**Métodos:**
- `isOpen()` - status === 'open'
- `isCritical()` - priority === 'critical'
- `complete(string $notes)` - preenche completed_at e resolution_notes
- `start()` - muda status para in_progress e preenche started_at

---

### Tabela: `maintenance_logs`
Histórico de todas as intervenções

```sql
CREATE TABLE maintenance_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    machine_id BIGINT UNSIGNED NOT NULL,
    service_order_id BIGINT UNSIGNED NULLABLE,
    user_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    defect_type VARCHAR(100) NULLABLE,
    logged_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,
    FOREIGN KEY (service_order_id) REFERENCES service_orders(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,

    INDEX idx_machine_id (machine_id),
    INDEX idx_defect_type (defect_type),
    INDEX idx_logged_at (logged_at)
);
```

**Exemplos de `action`:**
- "Troca de correia"
- "Lubrificação"
- "Substituição de parafuso"
- "Reparo de motor"

---

### Tabela: `machine_readings`
Leituras de sensores (preparação para IoT)

```sql
CREATE TABLE machine_readings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    machine_id BIGINT UNSIGNED NOT NULL,
    sensor_key VARCHAR(50) NOT NULL,
    value DECIMAL(8, 2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    read_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,

    INDEX idx_machine_id (machine_id),
    INDEX idx_sensor_key (sensor_key),
    INDEX idx_read_at (read_at)
);
```

**Exemplos de sensor_key:**
- `temperature` (unit: °C)
- `vibration` (unit: mm/s)
- `rpm` (unit: RPM)
- `pressure` (unit: bar)

---

### Tabela: `status_alerts`
Histórico de alertas de mudança de status

```sql
CREATE TABLE status_alerts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    machine_id BIGINT UNSIGNED NOT NULL,
    triggered_by BIGINT UNSIGNED NULLABLE,
    previous_status VARCHAR(50) NOT NULL,
    new_status VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    triggered_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,
    FOREIGN KEY (triggered_by) REFERENCES users(id) ON DELETE SET NULL,

    INDEX idx_machine_id (machine_id),
    INDEX idx_is_read (is_read),
    INDEX idx_triggered_at (triggered_at)
);
```

---

## 📈 Relacionamentos (Eloquent)

### Machine
```php
hasMany(ServiceOrder)
hasMany(MaintenanceLog)
hasMany(MachineReading)
hasMany(StatusAlert)
```

### ServiceOrder
```php
belongsTo(Machine)
belongsTo(User, 'technician_id')
belongsTo(User, 'created_by')
hasMany(MaintenanceLog)
```

### MaintenanceLog
```php
belongsTo(Machine)
belongsTo(ServiceOrder)
belongsTo(User)
```

### MachineReading
```php
belongsTo(Machine)
```

### StatusAlert
```php
belongsTo(Machine)
belongsTo(User, 'triggered_by')
```

### User (Spatie)
```php
hasMany(ServiceOrder, 'technician_id')
hasMany(ServiceOrder, 'created_by')
hasMany(MaintenanceLog)
// Spatie adiciona: roles(), permissions()
```

---

## 🔧 Estratégia de Índices e Performance

- **machines**: serial_number (unique), status, location
- **service_orders**: machine_id, technician_id, status, type
- **maintenance_logs**: machine_id, defect_type, logged_at
- **machine_readings**: machine_id, sensor_key, read_at
- **status_alerts**: machine_id, is_read, triggered_at

**Dica:** Criar índices compostos para queries habituais:
- `(machine_id, status)` em machines
- `(technician_id, status)` em service_orders

---

*[[_Documentação/README]] | [[_Documentação/02-Arquitetura]] | [[_Documentação/04-Filament-Resources]]*
