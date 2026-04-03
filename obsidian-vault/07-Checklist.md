# 07 - Checklist de Implementação

## 🎯 Execução Ordenada

Seguir a ordem abaixo para garantir que cada etapa funciona antes de prosseguir.

---

## ✅ Fase 1: Banco de Dados

- [ ] **Criar migração: CreateUsersTable** (estender avec Spatie)
- [ ] **Criar migração: CreateMachinesTable**
  - [ ] enum status
  - [ ] índices em serial_number, status, location

- [ ] **Criar migração: CreateServiceOrdersTable**
  - [ ] enums type, priority, status
  - [ ] FKs para machines, users
  - [ ] índices

- [ ] **Criar migração: CreateMaintenanceLogsTable**
  - [ ] FK para machines, service_orders, users
  - [ ] índices em machine_id, defect_type, logged_at

- [ ] **Criar migração: CreateMachineReadingsTable**
  - [ ] decimal(8,2) para value
  - [ ] índices em read_at

- [ ] **Criar migração: CreateStatusAlertsTable**
  - [ ] boolean para is_read
  - [ ] FK para triggered_by

- [ ] **Rodar migrations**
  ```bash
  php artisan migrate:fresh
  ```

---

## ✅ Fase 2: Models e Relacionamentos

- [ ] **User Model** (estender com Spatie)
  ```php
  use Spatie\Permission\Traits\HasRoles;
  ```

- [ ] **Machine Model**
  - [ ] Adicionar scopes: operational, inMaintenance, critical, offline
  - [ ] Implementar boot() com StatusAlert + Notification
  - [ ] Relations: hasMany(ServiceOrder, MaintenanceLog, MachineReading, StatusAlert)

- [ ] **ServiceOrder Model**
  - [ ] Methods: isOpen(), isCritical(), complete(), start()
  - [ ] Relations: belongsTo Machine, belongsTo User (technician_id), belongsTo User (created_by)
  - [ ] hasMany MaintenanceLog

- [ ] **MaintenanceLog Model**
  - [ ] Relations: belongsTo Machine, ServiceOrder, User

- [ ] **MachineReading Model**
  - [ ] Relations: belongsTo Machine

- [ ] **StatusAlert Model**
  - [ ] Relations: belongsTo Machine, belongsTo User (triggered_by)

- [ ] **Testar relacionamentos**
  ```bash
  php artisan tinker
  Machine::first()->serviceOrders()->count()
  ```

---

## ✅ Fase 3: Filament Resources

### MachineResource
- [ ] Criar resource base
- [ ] Adicionar colunas: serial_number, name, model, location, status (badge), installed_at, last_reading_at
- [ ] Adicionar form fields: serial_number, name, model, location, installed_at (DatePicker), status (Select), description, image (FileUpload)
- [ ] Adicionar filtros: por status, por location, DateRange
- [ ] Criar Relation Managers:
  - [ ] ServiceOrdersRelationManager
  - [ ] MaintenanceLogsRelationManager
  - [ ] MachineReadingsRelationManager
  - [ ] StatusAlertsRelationManager
- [ ] Testar no browser

### ServiceOrderResource
- [ ] Criar resource base
- [ ] Colunas: title, machine, type (badge), priority (badge), status (badge), technician, created_at
- [ ] Form fields: title, description, machine_id (searchable), type, priority, technician_id (filtrado por tecnico role), status, resolution_notes (visível se status=completed)
- [ ] Filtros: status, type, priority, machine, DateRange
- [ ] Ações: "Iniciar O.S." (livewire), "Concluir O.S." (com modal)
- [ ] Testar fluxo completo

### MaintenanceLogResource
- [ ] Criar resource
- [ ] Colunas: machine, action, defect_type, user, logged_at
- [ ] Form fields: machine_id, service_order_id (dependente), action, description, defect_type, logged_at
- [ ] Filtros: machine, defect_type, DateRange
- [ ] Testar

### UserResource
- [ ] Criar resource
- [ ] Colunas: name, email, roles (badges), created_at
- [ ] Form fields: name, email, password (obrigatório apenas na criação), roles (MultiSelect Spatie)
- [ ] Validações: email único, password >8
- [ ] Proteção: apenas admin

### StatusAlertResource
- [ ] Criar resource
- [ ] Colunas: machine, message, previous_status, new_status, is_read (toggle), triggered_at
- [ ] Filtros: machine, is_read, new_status
- [ ] Ação: toggle is_read

---

## ✅ Fase 4: Políticas de Acesso (Spatie)

- [ ] Instalar Spatie Permission
  ```bash
  composer require spatie/laravel-permission
  composer require filament/spatie-laravel-permission-plugin
  php artisan migrate
  ```

- [ ] Criar 4 roles: admin, gerente, tecnico, operador
  ```bash
  php artisan make:seeder RoleAndPermissionSeeder
  ```

- [ ] Criar Policies:
  - [ ] MachinePolicy
  - [ ] ServiceOrderPolicy
  - [ ] MaintenanceLogPolicy
  - [ ] StatusAlertPolicy
  - [ ] UserPolicy

- [ ] Proteger cada Resource com canCreate/Edit/Delete

- [ ] Testar autorização com diferentes roles

---

## ✅ Fase 5: Dashboard e Widgets

- [ ] **StatsOverviewWidget**
  - [ ] 4 cards: total, operacionais, manutenção, crítica
  - [ ] Links para filtros

- [ ] **RecentServiceOrdersWidget**
  - [ ] Tabela com últimas 5 O.S. abertas
  - [ ] Colunas: machine, title, type, priority, technician, created_at

- [ ] **CriticalAlertsWidget**
  - [ ] Tabela com últimos alertas não lidos
  - [ ] Ação: marcar como lido (toggle)

- [ ] **MaintenanceLogWidget**
  - [ ] Tabela com últimas 5 intervenções

- [ ] Registrar no PanelProvider

- [ ] Testar dashboard com dados variados

---

## ✅ Fase 6: Seeds de Teste

- [ ] **Criar DatabaseSeeder completo**
  - [ ] 1 admin: admin@maintsys.com
  - [ ] 1 gerente: gerente@maintsys.com
  - [ ] 3 técnicos

- [ ] **10 Machines** com:
  - [ ] serial_numbers únicos (SN-2024-001, etc)
  - [ ] Locations variadas
  - [ ] Status variados (mix de operational, maintenance, critical)

- [ ] **20 ServiceOrders**
  - [ ] Mix de preventive/corrective
  - [ ] Status variados (open, in_progress, completed, cancelled)
  - [ ] Prioridades variadas

- [ ] **30 MaintenanceLogs**
  - [ ] Distribuídas entre máquinas
  - [ ] Diferentes actions (Troca de correia, lubrificação, etc)

- [ ] **15 StatusAlerts**
  - [ ] Alguns com is_read=true, outros false

- [ ] **20 MachineReadings**
  - [ ] Variados sensor_keys (temperature, vibration, rpm)

- [ ] Rodar seed:
  ```bash
  php artisan migrate:fresh --seed
  ```

- [ ] Verificar dados no banco:
  ```bash
  php artisan tinker
  Machine::count() # deve ser 10
  ServiceOrder::count() # deve ser 20
  ```

---

## ✅ Fase 7: Configuração Final do Painel

- [ ] **PanelProvider**
  - [ ] Brand name: "MaintSys"
  - [ ] Dark mode: habilitado por default
  - [ ] Ícone: wrench ou gear
  - [ ] Logo (se houver)

- [ ] **Locação**
  - [ ] pt_BR em app/config/localization ou filament.php

- [ ] **Navigation**
  - [ ] Grupo "Equipamentos": Machines
  - [ ] Grupo "Ordens de Serviço": ServiceOrders, MaintenanceLogs
  - [ ] Grupo "Alertas": StatusAlerts
  - [ ] Grupo "Administração": Users, (Roles plugin)
  - [ ] Dashboard como homepage

- [ ] **Auth**
  - [ ] Login customizado (opcional)
  - [ ] Logout funcionando

- [ ] Testar navegação completa

---

## ✅ Testes Funcionais Finais

### Teste 1: Fluxo Completo de O.S.
- [ ] Gerente cria nova O.S. preventiva
- [ ] Atribui a um técnico
- [ ] Técnico vê em seu painel
- [ ] Técnico clica "Iniciar O.S."
- [ ] Status muda para in_progress
- [ ] Técnico cria MaintenanceLog
- [ ] Técnico clica "Concluir"
- [ ] Adiciona resolution_notes
- [ ] Status muda para completed
- [ ] O.S. sai da lista "Recentes"

### Teste 2: Alerta de Status
- [ ] Gerente muda status da Machine para "critical"
- [ ] StatusAlert é criado automaticamente
- [ ] Notificação aparece
- [ ] Dashboard mostra em "Critical Alerts"
- [ ] Gerente marca como lido
- [ ] Alerta desaparece da lista

### Teste 3: Permissões
- [ ] Logar como técnico
  - [ ] Não pode criar Máquinas
  - [ ] Pode ver Máquinas
  - [ ] Pode ver/criar/editar suas O.S.
  - [ ] Pode criar MaintenanceLogs

- [ ] Logar como operador
  - [ ] Pode ver tudo (read-only)
  - [ ] Não pode editar nada

- [ ] Logar como Admin
  - [ ] Acesso total

### Teste 4: Dashboard
- [ ] Dados corretos nos 4 cards (total, operacionais, manutenção, crítica)
- [ ] Últimas 5 O.S. abertas carregam
- [ ] Últimos alertas não lidos aparecem
- [ ] Últimas 5 intervênções mostram

---

## 📋 Verificações Pós-Implementação

```bash
# Rodar migrations sem erros
php artisan migrate:fresh --seed

# Verificar modelos
php artisan make:model --help

# Verificar banço
mysql -u root -p maintsys < /dev/null

# Rodar servidor
php artisan serve

# Acessar
http://localhost:8000/admin
```

---

## 🚀 Deploy

- [ ] Environment variables (.env)
- [ ] Database pronta (PostgreSQL ou MySQL)
- [ ] Storage configurado
- [ ] Cache limpo antes de deploy
- [ ] Testes unitários rodando
- [ ] Documentação atualizada

---

*[[README]] | [[06-Permissões]] | [[08-Diagrama-ER]]*
