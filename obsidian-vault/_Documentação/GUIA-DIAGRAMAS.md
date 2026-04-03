# 📚 Guia: Diagramas de Classes e ER

## 🎯 Visão Geral

Este guia liga dois documentos essenciais que representam a arquitetura de dados e estrutura do MaintSys:

1. **Diagrama-Classes.md** — Visão orientada a objetos de todos os Models
2. **08-Diagrama-ER.md** — Visão de banco de dados relacional
3. **Diagrama-Classes.canvas** — Visualização interativa

---

## 📊 Estrutura Geral

```
┌─────────────────────────────────────────┐
│        LAYER: Presentation              │
│  (Filament Resources + Widgets)         │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│    LAYER: Application/Controllers       │
│  (Request Handling - Filament)          │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│    LAYER: Domain/Models (THIS DIAGRAM)  │
│  - User, Machine, ServiceOrder, etc.    │
│  - Relationships & Methods              │
│  - Business Logic                       │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│    LAYER: Infrastructure/Database       │
│  - Migrations, Seeders                  │
│  - Schema (ER Diagram)                  │
│  - Indexes, Constraints                 │
└─────────────────────────────────────────┘
```

---

## 🗺️ Mapeamento: Código ↔ Banco de Dados

### User Model → users Table
```
Código (Eloquent Model)
├── hasMany(ServiceOrder) ──────→ users.id ← service_orders.technician_id
├── hasMany(ServiceOrder) ──────→ users.id ← service_orders.created_by
├── hasMany(MaintenanceLog) ────→ users.id ← maintenance_logs.user_id
├── belongsToMany(Role) ────────→ N:N via role_user

BD (SQL Schema)
├── TABLE users (PK: id)
├── TABLE service_orders (FK: technician_id, created_by)
├── TABLE maintenance_logs (FK: user_id)
└── TABLE role_user (pivot)
```

### Machine Model → machines Table
```
Código
├── hasMany(ServiceOrder)
├── hasMany(MaintenanceLog)
├── hasMany(MachineReading)
├── hasMany(StatusAlert)
├── scopeOperational(), scopeCritical(), etc

BD
├── TABLE machines (PK: id, UQ: serial_number, INDEX: status, location)
├── TABLE service_orders (FK: machine_id ON DELETE CASCADE)
├── TABLE maintenance_logs (FK: machine_id)
├── TABLE machine_readings (FK: machine_id)
└── TABLE status_alerts (FK: machine_id)
```

### ServiceOrder Model → service_orders Table
```
Código
├── belongsTo(Machine)
├── belongsTo(User, 'technician_id')
├── belongsTo(User, 'created_by')
├── hasMany(MaintenanceLog)
├── isOpen(), isCritical(), start(), complete()

BD
├── TABLE service_orders
│   ├── PK: id
│   ├── FK: machine_id (CASCADE)
│   ├── FK: technician_id (RESTRICT)
│   ├── FK: created_by (RESTRICT)
│   ├── INDEX: machine_id, technician_id, status
│   └── ENUM columns: type, priority, status
```

---

## 🔗 Relacionamentos (Multiplicity)

### One-to-Many (1:N)

| Origem | → | Destino | SQL FK |
|--------|---|---------|--------|
| User | → N | ServiceOrder | technician_id |
| User | → N | ServiceOrder | created_by |
| User | → N | MaintenanceLog | user_id |
| Machine | → N | ServiceOrder | machine_id |
| Machine | → N | MaintenanceLog | machine_id |
| Machine | → N | MachineReading | machine_id |
| Machine | → N | StatusAlert | machine_id |
| ServiceOrder | → N | MaintenanceLog | service_order_id |

### Many-to-Many (N:N)

| Origem | ↔ | Destino | Pivot Table |
|--------|---|---------|-------------|
| User | ↔ N | Role | role_user |
| Role | ↔ N | Permission | role_has_permissions |
| User | ↔ N | Permission | model_has_permissions |

---

## 📋 Enumerations (Tipos Fixos)

### Machine.status
```
'operational'  - Funcionando normalmente
'maintenance'  - Em manutenção
'critical'     - Problema grave
'offline'      - Desligada
```

### ServiceOrder.type
```
'preventive'   - Manutenção preventiva
'corrective'   - Manutenção corretiva
```

### ServiceOrder.priority
```
'low'          - Prioridade baixa
'medium'       - Prioridade média
'high'         - Prioridade alta
'critical'     - Prioridade crítica
```

### ServiceOrder.status
```
'open'         - Aberta (aguardando)
'in_progress'  - Em progresso
'completed'    - Concluída
'cancelled'    - Cancelada
```

---

## 🎨 Visualização de Dados

### Diagrama de Classes (UML)
**Arquivo:** [[Diagrama-Classes.md]]
**Canvas:** [[Diagrama-Classes.canvas]]

Mostra:
- Todas as classes (Models)
- Atributos e tipos
- Métodos e comportamentos
- Relacionamentos com multiplicidade
- Enumerações

**Use this when:**
- Você quer entender a estrutura orientada a objetos
- Você vai implementar os Models
- Você precisa refletir sobre design

### Diagrama ER
**Arquivo:** [[08-Diagrama-ER.md]]

Mostra:
- Tabelas e colunas
- Foreign keys e constraints
- Índices para performance
- Relacionamentos normalizados

**Use this when:**
- Você está trabalhando com migrações
- Você precisa otimizar queries
- Você quer entender o banco de dados relacional

### Canvas Visual
**Arquivo:** [[Diagrama-Classes.canvas]]

Mostra:
- Visão interativa dos Models
- Relacionamentos graficamente
- Pode expandir nodes
- Organizado por layout

**Use this when:**
- Você prefere visualização gráfica
- Você está apresentando para stakeholders
- Você quer explorar interativamente

---

## 🔑 Constraints Importantes

### Foreign Keys
```
machine_id → machines(id) CASCADE
   ↳ Quando máquina deletada, O.S. também

technician_id → users(id) RESTRICT
   ↳ Não pode deletar user com O.S. ativa

user_id → users(id) RESTRICT
   ↳ Preserva auditoria (não deleta logs)

service_order_id → service_orders(id) SET NULL
   ↳ Mantém log mesmo se O.S. deletada
```

### Unique Constraints
```
serial_number (UNIQUE)
   ↳ Não pode ter 2 máquinas com mesmo SN

email (UNIQUE)
   ↳ Cada user tem email único
```

---

## 📈 Performance Indexes

### Mais Usadas (Prioridade ALTA)
```
machines.status        - Scopes operational, critical
service_orders.status  - Queries dashboard
status_alerts.is_read  - Widget de alertas não-lidos
```

### Moderadamente Usadas
```
machines.serial_number - Busca por SN
maintenance_logs.defect_type - Análise de padrões
service_orders.technician_id - Filtros por técnico
```

---

## 🧪 Queries de Teste

Após implementar, rode estas queries para validar:

```sql
-- Verificar relacionamentos
SELECT
  COUNT(DISTINCT u.id) as users,
  COUNT(DISTINCT m.id) as machines,
  COUNT(DISTINCT so.id) as service_orders,
  COUNT(DISTINCT ml.id) as maintenance_logs
FROM users u
CROSS JOIN machines m
LEFT JOIN service_orders so ON m.id = so.machine_id
LEFT JOIN maintenance_logs ml ON so.id = ml.service_order_id;

-- Verificar integridade referencial
SELECT 'missing machine' as issue
FROM service_orders so
WHERE NOT EXISTS (SELECT 1 FROM machines m WHERE m.id = so.machine_id)
UNION ALL
SELECT 'missing technician'
FROM service_orders so
WHERE technician_id IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM users u WHERE u.id = so.technician_id);
```

---

## 🎯 Fluxo de Leitura Recomendado

### Se você é iniciante:
```
1. Leia: Diagrama-Classes.md (Visão geral)
2. Explore: [[Diagrama-Classes.canvas]] (Visual interativa)
3. Estude: 08-Diagrama-ER.md (Banco de dados)
4. Implemente: Models + Migrations
```

### Se você é desenvolvedor:
```
1. Abra: Diagrama-Classes.canvas
2. Referencie: Diagrama-Classes.md (métodos/atributos)
3. Consulte: 08-Diagrama-ER.md (queries/índices)
4. Code: Migrations + Models + Resources
```

### Se você é DBA/Infra:
```
1. Estude: 08-Diagrama-ER.md (constraints)
2. Planeje: Indexes (performance)
3. Configure: Backups (maintenance_logs)
4. Monitor: Queries (slow log)
```

---

## 🔄 Exemplo de Implementação

### Passo 1: Ler Diagrama de Classes
Veja que `Machine` tem `hasMany(StatusAlert)` e um boot hook que cria alert

### Passo 2: Ler Diagrama ER
Veja a tabela `status_alerts` com FK para `machines` e estrutura

### Passo 3: Implementar Migration
```php
Schema::create('status_alerts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
    $table->foreignId('triggered_by')->nullable()->constrained('users')->setOnDelete('set null');
    $table->string('previous_status');
    $table->string('new_status');
    $table->text('message');
    $table->boolean('is_read')->default(false);
    $table->timestamp('triggered_at');
    $table->timestamps();

    $table->index('machine_id');
    $table->index('is_read');
});
```

### Passo 4: Implementar Model
```php
class StatusAlert extends Model {
    public function machine() {
        return $this->belongsTo(Machine::class);
    }
}
```

### Passo 5: Implementar Boot Hook (conforme Diagrama)
```php
// In Machine model
protected static function boot() {
    parent::boot();
    static::updating(function ($machine) {
        if ($machine->isDirty('status')) {
            StatusAlert::create([...]);
        }
    });
}
```

---

## 📚 Documentos Relacionados

- [[Diagrama-Classes.md]] — UML completo
- [[08-Diagrama-ER.md]] — Banco de dados
- [[Diagrama-Classes.canvas]] — Visual interativa
- [[LEVANTAMENTO-REQUISITOS]] — Contexto
- [[METODOLOGIA-DESENVOLVIMENTO]] — Implementação
- [[_Referência/Quick-Reference]] — Code snippets

---

*Guia de Diagramas — MaintSys v1.0*
*Integra visão orientada a objetos com modelo relacional*
*2026-04-03*
