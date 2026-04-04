# 📐 Diagrama de Classes — Mermaid

## 🎨 UML Class Diagram (Mermaid)

```mermaid
classDiagram
    class User {
        -bigint id (PK)
        -string name
        -string email (UK)
        -string password
        -timestamp email_verified_at
        -timestamp created_at
        -timestamp updated_at
        --
        +hasMany(ServiceOrder) technician
        +hasMany(ServiceOrder) creator
        +hasMany(MaintenanceLog)
        +hasMany(StatusAlert)
        +belongsToMany(Role)
        +belongsToMany(Permission)
        --
        +hasRole(string) boolean
        +hasPermissionTo(string) boolean
        +can(string, Model) boolean
    }

    class Role {
        <<Spatie>>
        -bigint id
        -string name
        -string guard_name
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsToMany(User)
        +belongsToMany(Permission)
        +givePermissionTo(Permission)
    }

    class Permission {
        <<Spatie>>
        -bigint id
        -string name
        -string guard_name
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsToMany(Role)
        +belongsToMany(User)
    }
    class Status {
        <<enum>>
        operational
        maintenance
        critical
        offline
    }

    class OSType {
        <<enum>>
        preventive
        corrective
    }

    class Priority {
        <<enum>>
        low
        medium
        high
        critical
    }

    class OSStatus {
        <<enum>>
        open
        in_progress
        completed
        cancelled
    }
    class Machine {
        -bigint id (PK)
        -string serial_number (UK)
        -string name
        -string model
        -string location
        -Status status
        -date installed_at
        -text description
        -string image
        -timestamp last_reading_at
        -timestamp created_at
        -timestamp updated_at
        --
        +hasMany(ServiceOrder)
        +hasMany(MaintenanceLog)
        +hasMany(MachineReading)
        +hasMany(StatusAlert)
        --
        +scopeOperational() Builder
        +scopeInMaintenance() Builder
        +scopeCritical() Builder
        +scopeOffline() Builder
        +boot()* static
    }

    class ServiceOrder {
        -bigint id (PK)
        -bigint machine_id (FK)
        -bigint technician_id (FK)
        -bigint created_by (FK)
        -string title
        -text description
        -OSType type
        -Priority priority
        -OSStatus status
        -timestamp started_at
        -timestamp completed_at
        -text resolution_notes
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsTo(Machine)
        +belongsTo(User, 'technician_id')
        +belongsTo(User, 'created_by')
        +hasMany(MaintenanceLog)
        --
        +isOpen() boolean
        +isCritical() boolean
        +start() void
        +complete(notes) void
    }

    class MaintenanceLog {
        -bigint id (PK)
        -bigint machine_id (FK)
        -bigint service_order_id (FK)
        -bigint user_id (FK)
        -string action
        -text description
        -string defect_type
        -timestamp logged_at
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsTo(Machine)
        +belongsTo(ServiceOrder)
        +belongsTo(User)
    }

    class MachineReading {
        -bigint id (PK)
        -bigint machine_id (FK)
        -string sensor_key
        -decimal(8,2) value
        -string unit
        -timestamp read_at
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsTo(Machine)
    }

    class StatusAlert {
        -bigint id (PK)
        -bigint machine_id (FK)
        -bigint triggered_by (FK)
        -string previous_status
        -string new_status
        -text message
        -boolean is_read
        -timestamp triggered_at
        -timestamp created_at
        -timestamp updated_at
        --
        +belongsTo(Machine)
        +belongsTo(User, 'triggered_by')
    }
    User "1" --> "N" ServiceOrder : technician_id
    User "1" --> "N" ServiceOrder : created_by
    User "1" --> "N" MaintenanceLog : user_id
    User "1" --> "N" StatusAlert : triggered_by

    User "N" --> "N" Role : belongsToMany
    Role "N" --> "N" Permission : belongsToMany
    User "N" --> "N" Permission : can_through_role

    Machine "1" --> "N" ServiceOrder : machine_id
    Machine "1" --> "N" MaintenanceLog : machine_id
    Machine "1" --> "N" MachineReading : machine_id
    Machine "1" --> "N" StatusAlert : machine_id

    ServiceOrder "1" --> "N" MaintenanceLog : service_order_id

    Machine "1" --> "1" Status : uses
    ServiceOrder "1" --> "1" OSType : uses
    ServiceOrder "1" --> "1" Priority : uses
    ServiceOrder "1" --> "1" OSStatus : uses
```

---

## 📋 Legenda

| Símbolo | Significado |
|---------|-----------|
| `-` | Private attribute/method |
| `+` | Public method |
| `FK` | Foreign Key (Banco de dados) |
| `PK` | Primary Key |
| `UK` | Unique Key |
| `*` | Static method (boot) |
| `N` | Um para muitos |
| `<<enum>>` | Enumeration |
| `<<Spatie>>` | From Spatie package |

---

## 🔗 Relacionamentos Explicados

### 1:N (One-to-Many)
```
User (1) ──→ (N) ServiceOrder
   ↳ Um user pode ter múltiplas O.S. como técnico
```

### N:N (Many-to-Many)
```
User (N) ↔ (N) Role
   ↳ Um user pode ter múltiplos roles
   ↳ Um role pode ter múltiplos users
   ↳ Pivot table: role_user
```

### Inheritance/Polymorphism
```
Permission ← Role
Permission ← User (direct assignment)
   ↳ Spatie allows both role-based and direct permissions
```

---

## 🎯 Padrões de Design

### Model Relationships
```php
// Eloquent relationships (BelongsTo, HasMany, BelongsToMany)
// Todas as relações mapeadas no diagrama acima
```

### Boot Hooks
```php
// Machine::boot() cria StatusAlert automaticamente
protected static function boot() {
    parent::boot();
    static::updating(function ($machine) {
        if ($machine->isDirty('status')) {
            // Create StatusAlert
        }
    });
}
```

### Scopes
```php
// Query builder shortcuts
Machine::operational() // WHERE status = 'operational'
Machine::critical()    // WHERE status = 'critical'
```

### Methods
```php
ServiceOrder::start()      // Mark as in_progress
ServiceOrder::complete()   // Mark as completed
User::hasRole()           // Check Spatie role
```

---

## 📊 Multiplicidade das Relações

| Source       | →   | Target                 | Relation Type | SQL FK                        | Delete   |
| ------------ | --- | ---------------------- | ------------- | ----------------------------- | -------- |
| User         | 1:N | ServiceOrder (tech)    | HasMany       | technician_id                 | RESTRICT |
| User         | 1:N | ServiceOrder (creator) | HasMany       | created_by                    | RESTRICT |
| User         | 1:N | MaintenanceLog         | HasMany       | user_id                       | RESTRICT |
| User         | 1:N | StatusAlert            | HasMany       | triggered_by                  | SET NULL |
| Machine      | 1:N | ServiceOrder           | HasMany       | machine_id                    | CASCADE  |
| Machine      | 1:N | MaintenanceLog         | HasMany       | machine_id                    | CASCADE  |
| Machine      | 1:N | MachineReading         | HasMany       | machine_id                    | CASCADE  |
| Machine      | 1:N | StatusAlert            | HasMany       | machine_id                    | CASCADE  |
| ServiceOrder | 1:N | MaintenanceLog         | HasMany       | service_order_id              | SET NULL |
| User         | N:N | Role                   | BelongsToMany | (pivot: role_user)            | Detach   |
| Role         | N:N | Permission             | BelongsToMany | (pivot: role_has_permissions) | Detach   |

---

*Diagrama de Classes (UML) — MaintSys v1.0*
*Mermaid Class Diagram com todas as relações Eloquent*
*2026-04-03*
