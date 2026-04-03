# 08 - Diagrama de Entidade-Relacionamento

## 📊 ER Diagram (Mermaid)

```mermaid
erDiagram
    USERS ||--o{ SERVICE_ORDERS : "technician"
    USERS ||--o{ SERVICE_ORDERS : "created_by"
    USERS ||--o{ MAINTENANCE_LOGS : "logs"
    USERS ||--o{ STATUS_ALERTS : "triggered_by"
    USERS ||--o{ ROLES : "has"

    MACHINES ||--o{ SERVICE_ORDERS : "has"
    MACHINES ||--o{ MAINTENANCE_LOGS : "has"
    MACHINES ||--o{ MACHINE_READINGS : "has"
    MACHINES ||--o{ STATUS_ALERTS : "has"

    SERVICE_ORDERS ||--o{ MAINTENANCE_LOGS : "has"

    PERMISSIONS ||--o{ ROLES : "assigned_to"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        timestamp created_at
        timestamp updated_at
    }

    MACHINES {
        bigint id PK
        string serial_number UK
        string name
        string model
        string location
        enum status "operational,maintenance,critical,offline"
        date installed_at
        text description "nullable"
        string image "nullable"
        timestamp last_reading_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    SERVICE_ORDERS {
        bigint id PK
        bigint machine_id FK
        bigint technician_id FK
        bigint created_by FK
        string title
        text description
        enum type "preventive,corrective"
        enum priority "low,medium,high,critical"
        enum status "open,in_progress,completed,cancelled"
        timestamp started_at "nullable"
        timestamp completed_at "nullable"
        text resolution_notes "nullable"
        timestamp created_at
        timestamp updated_at
    }

    MAINTENANCE_LOGS {
        bigint id PK
        bigint machine_id FK
        bigint service_order_id FK "nullable"
        bigint user_id FK
        string action
        text description
        string defect_type "nullable"
        timestamp logged_at
        timestamp created_at
        timestamp updated_at
    }

    MACHINE_READINGS {
        bigint id PK
        bigint machine_id FK
        string sensor_key
        decimal value "8,2"
        string unit
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }

    STATUS_ALERTS {
        bigint id PK
        bigint machine_id FK
        bigint triggered_by FK "nullable"
        string previous_status
        string new_status
        text message
        boolean is_read "default:false"
        timestamp triggered_at
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        bigint id PK
        string name UK
        string guard_name
        timestamp created_at
        timestamp updated_at
    }

    PERMISSIONS {
        bigint id PK
        string name UK
        string guard_name
        timestamp created_at
        timestamp updated_at
    }
```

---

## 🔄 Relacionamentos Detalhados

### Users → ServiceOrders (duplo)
- **technician_id**: Um usuário pode ter múltiplas O.S. como técnico atribuído
- **created_by**: Um usuário pode criar múltiplas O.S.

**Query exemplo:**
```php
$user->serviceOrders; // O.S. onde o user é technician_id
$user->createdServiceOrders; // O.S. criadas pelo user
```

---

### Machines → ServiceOrders
Uma máquina pode ter múltiplas ordens de serviço

**Query exemplo:**
```php
$machine->serviceOrders; // All O.S. para essa máquina
$machine->serviceOrders()->where('status', 'completed')->count();
```

---

### Machines → MaintenanceLogs
Uma máquina pode ter múltiplos logs de intervenção

**Query exemplo:**
```php
$machine->maintenanceLogs; // All logs
$machine->maintenanceLogs()->where('defect_type', 'corrosion')->get();
```

---

### ServiceOrders → MaintenanceLogs
Uma O.S. pode ter múltiplos logs (uma intervenção pode gerar vários registros)

**Query exemplo:**
```php
$serviceOrder->maintenanceLogs; // Todos os logs dessa O.S.
```

---

### Machines → MachineReadings
Leitura de sensores de uma máquina (futuro: dados de ESP-32)

**Query exemplo:**
```php
$machine->readings()->where('sensor_key', 'temperature')->latest()->first();
```

---

### Machines → StatusAlerts
Histórico de mudanças de status de uma máquina

**Query exemplo:**
```php
$machine->alerts()->where('is_read', false)->count(); // Alertas não lidos
```

---

## 📋 Principais Índices (Performance)

```sql
-- machines
ALTER TABLE machines ADD INDEX idx_status (status);
ALTER TABLE machines ADD INDEX idx_location (location);
ALTER TABLE machines ADD FULLTEXT INDEX ft_name (name);

-- service_orders
ALTER TABLE service_orders ADD INDEX idx_machine_id (machine_id);
ALTER TABLE service_orders ADD INDEX idx_technician_id (technician_id);
ALTER TABLE service_orders ADD INDEX idx_status (status);
ALTER TABLE service_orders ADD INDEX idx_type (type);

-- maintenance_logs
ALTER TABLE maintenance_logs ADD INDEX idx_machine_id (machine_id);
ALTER TABLE maintenance_logs ADD INDEX idx_defect_type (defect_type);
ALTER TABLE maintenance_logs ADD INDEX idx_logged_at (logged_at);

-- machine_readings
ALTER TABLE machine_readings ADD INDEX idx_machine_id (machine_id);
ALTER TABLE machine_readings ADD INDEX idx_read_at (read_at);

-- status_alerts
ALTER TABLE status_alerts ADD INDEX idx_machine_id (machine_id);
ALTER TABLE status_alerts ADD INDEX idx_is_read (is_read);
ALTER TABLE status_alerts ADD INDEX idx_triggered_at (triggered_at);
```

---

## 🔐 Constraints e Integridade

### Cascade DELETE
- `machines` → `service_orders` (ON DELETE CASCADE)
- `machines` → `maintenance_logs` (ON DELETE CASCADE)
- `machines` → `machine_readings` (ON DELETE CASCADE)
- `machines` → `status_alerts` (ON DELETE CASCADE)

### SET NULL
- `service_orders.service_order_id` → null se O.S. deletada
- `status_alerts.triggered_by` → null se usuário deletado

### RESTRICT
- Não permite deletar um User que tenha service_orders ou logs associados

---

## 📊 Queries Comuns

### Máquinas em Estado Crítico com O.S. Abertas

```php
$criticalWithOpenOrders = Machine::where('status', 'critical')
    ->whereHas('serviceOrders', function ($query) {
        $query->where('status', '!=', 'completed');
    })
    ->get();
```

---

### Histórico Completo de Uma Máquina

```php
$history = Machine::find($machineId)
    ->maintenanceLogs()
    ->with('user', 'serviceOrder')
    ->orderBy('logged_at', 'DESC')
    ->get();
```

---

### Técnico com Mais O.S. Completadas

```php
$topTechnician = User::withCount('serviceOrders')
    ->where('serviceOrders.status', 'completed')
    ->orderBy('service_orders_count', 'DESC')
    ->first();
```

---

### Defetos Recorrentes

```php
$recurringDefects = MaintenanceLog::where('defect_type', '!=', null)
    ->groupBy('defect_type')
    ->selectRaw('defect_type, COUNT(*) as count')
    ->orderBy('count', 'DESC')
    ->get();
```

---

*[[README]] | [[07-Checklist]]*
