# 02 - Arquitetura do Sistema

## 🏗️ Visão Geral

```
┌─────────────────────────────────────────────┐
│          FILAMENT DASHBOARD (UI)            │
│  - Machines | ServiceOrders | Alerts | etc  │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│       LARAVEL 11 (Controllers/Actions)      │
│  - Resources, Relation Managers, Widgets    │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│         ELOQUENT MODELS + TRAITS            │
│  - Scopes, Boot hooks, Relationships       │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│    DATABASE (MySQL) + Migrations            │
│  - 6 tabelas principais + índices           │
└─────────────────────────────────────────────┘
```

---

## 📦 Estrutura de Diretórios

```
app/
├── Models/
│   ├── Machine.php
│   ├── ServiceOrder.php
│   ├── MaintenanceLog.php
│   ├── MachineReading.php
│   └── StatusAlert.php
├── Filament/
│   └── Resources/
│       ├── MachineResource.php
│       ├── UserResource.php
│       ├── ServiceOrderResource.php
│       ├── MaintenanceLogResource.php
│       └── StatusAlertResource.php
├── Filament/Widgets/
│   ├── StatsOverviewWidget.php
│   ├── RecentServiceOrdersWidget.php
│   ├── CriticalAlertsWidget.php
│   └── MaintenanceLogWidget.php
└── Policies/
    ├── MachinePolicy.php
    ├── ServiceOrderPolicy.php
    └── StatusAlertPolicy.php

database/
├── migrations/
│   ├── 2024_01_01_000000_create_machines_table.php
│   ├── 2024_01_01_000001_create_service_orders_table.php
│   ├── 2024_01_01_000002_create_maintenance_logs_table.php
│   ├── 2024_01_01_000003_create_machine_readings_table.php
│   └── 2024_01_01_000004_create_status_alerts_table.php
└── seeders/
    ├── UserSeeder.php
    ├── MachineSeeder.php
    ├── ServiceOrderSeeder.php
    ├── MaintenanceLogSeeder.php
    ├── MachineReadingSeeder.php
    └── StatusAlertSeeder.php
```

---

## 🔄 Fluxos Principais

### Fluxo 1: Criação de Ordem de Serviço

```
Gerente cria O.S. (preventiva/corretiva)
    ↓
Atribui a um técnico
    ↓
Técnico vê O.S. pendente
    ↓
Clica "Iniciar O.S." (status → in_progress, started_at)
    ↓
Registra MaintenanceLog (ação, defeito, descrição)
    ↓
Clica "Concluir O.S." + adiciona resolution_notes
    ↓
Sistema cria StatusAlert se houver mudança de status da máquina
```

### Fluxo 2: Alerta de Status

```
Gerente muda status da Machine (ex: operational → critical)
    ↓
Boot hook dispara:
  - Cria StatusAlert (previous_status, new_status, message)
  - Envia notificação Filament para todos os users
    ↓
Dashboard mostra "Critical Alerts" com is_read = false
    ↓
Gerente clica "Marcar como lido"
    ↓
is_read muda para true, alerta sai da lista
```

---

## 🛡️ Padrões de Segurança

### RBAC (Role-Based Access Control)

| Role | Máquinas | O.S. | Logs | Alertas | Usuários |
|------|----------|------|------|---------|----------|
| **admin** | CRUD | CRUD | CRUD | CRUD | CRUD |
| **gerente** | R,U | CRUD | R | R | R |
| **tecnico** | R | R,U (próprias) | C | R | R |
| **operador** | R | R | R | R | R |

- `C` = Create, `R` = Read, `U` = Update, `D` = Delete

### Autorização em Models

Usar **Laravel Policies**:
- `MachinePolicy::update()` - apenas admin/gerente
- `ServiceOrderPolicy::create()` - gerente + tecnico (com restrições)
- `StatusAlertPolicy::update()` - apenas admin/gerente para marcar como lido

---

## 🚦 Integrações Futuras

- **MQTT**: Broker MQTT para receber dados de sensores ESP-32
- **WebSockets**: Notificações em tempo real com Laravel Echo
- **Webhooks**: Integração com sistemas ERP

---

*[[_Documentação/README]] | [[_Documentação/01-Requisitos]] | [[_Documentação/03-Banco-de-Dados]]*
