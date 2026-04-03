# 04 - Filament Resources

## 📋 MachineResource

### Coluna Tabela
| Campo | Tipo | Renderização |
|-------|------|--------------|
| serial_number | string | Texto |
| name | string | Texto |
| model | string | Texto |
| location | string | Texto |
| status | enum | Badge (operational=success, maintenance=warning, critical=danger, offline=gray) |
| installed_at | date | Data formatada |
| last_reading_at | timestamp | Data/hora ou "–" |

### Formulário
```
Input Fields:
- serial_number (Text, obrigatório, único)
- name (Text, obrigatório)
- model (Text, obrigatório)
- location (Text, obrigatório)
- installed_at (DatePicker, obrigatório)
- status (Select: operational, maintenance, critical, offline)
- description (Textarea, opcional)
- image (FileUpload, disco: 'public', pasta: 'machines')

Abas:
- Geral (campos acima)
- Ordens de Serviço (RelationManager)
- Manutenção (RelationManager)
- Leituras (RelationManager)
- Alertas (RelationManager)
```

### Filtros
- Por status
- Por location
- DateRange em installed_at

### Ações em Linha (Bulk Actions)
- 🔴 "Marcar como Crítica" - muda status para 'critical'
- ✅ "Marcar como Operacional" - muda status para 'operational'
- ⚠️ "Marcar em Manutenção" - muda status para 'maintenance'

---

## 📋 ServiceOrderResource

### Coluna Tabela
| Campo | Tipo | Renderização |
|-------|------|--------------|
| title | string | Texto |
| machine | relation | nome da máquina (link) |
| type | enum | Badge (preventive=info, corrective=warning) |
| priority | enum | Badge colorido (low=gray, medium=blue, high=orange, critical=red) |
| status | enum | Badge (open=info, in_progress=warning, completed=success, cancelled=gray) |
| technician | relation | nome do técnico |
| created_at | timestamp | Data formatada |

### Formulário
```
Input Fields:
- title (Text, obrigatório)
- description (Textarea, obrigatório)
- machine_id (Select searchable, obrigatório)
- type (Select: preventive/corrective, obrigatório)
- priority (Select: low/medium/high/critical)
- technician_id (Select filtrado por role 'tecnico', obrigatório)
- status (Select: open/in_progress/completed/cancelled)
- resolution_notes (Textarea, VISÍVEL APENAS SE status='completed')

Validações:
- Se status='completed', resolution_notes obrigatório
```

### Filtros
- Por status
- Por type
- Por priority
- Por machine (Select)
- DateRange em created_at

### Ações em Linha
- **"Iniciar O.S."** (livewire action)
  - Abre modal de confirmação
  - Executa: `$serviceOrder->start()` (status='in_progress', started_at=now)
  - Refresh tabela

- **"Concluir O.S."** (livewire action)
  - Abre modal com Textarea para resolution_notes
  - Executa: `$serviceOrder->complete($notes)` (status='completed', completed_at=now)
  - Atualiza resolution_notes
  - Refresh tabela

- **"Reabrir"** (se status='completed')
  - Muda de volta para 'open'

---

## 📋 MaintenanceLogResource

### Coluna Tabela
| Campo | Tipo | Renderização |
|-------|------|--------------|
| machine | relation | nome da máquina (link) |
| action | string | Texto (ex: "Troca de correia") |
| defect_type | string | Texto ou badge com cor |
| user | relation | nome do técnico |
| logged_at | timestamp | Data/hora formatada |

### Formulário
```
Input Fields:
- machine_id (Select, obrigatório)
- service_order_id (Select **dependente**: filtrado pela machine selecionada, opcional)
- action (Text, obrigatório — ex: "Lubrificação")
- description (Textarea, obrigatório)
- defect_type (Select ou Text livre, opcional)
- logged_at (DateTimePicker, obrigatório, padrão: now)
```

### Filtros
- Por machine (Select)
- Por defect_type (Select com valores únicos)
- DateRange em logged_at

### Relatório
Este resource serve como **"Log completo de intervenções"** — requisito de auditoria. Deve ser acessível apenas por admin e gerente.

---

## 📋 UserResource

### Coluna Tabela
| Campo | Tipo | Renderização |
|-------|------|--------------|
| name | string | Texto |
| email | email | Email link |
| roles | relation | Badges com cores (admin=red, gerente=blue, tecnico=orange, operador=gray) |
| created_at | timestamp | Data formatada |

### Formulário
```
Input Fields:
- name (Text, obrigatório)
- email (Email, obrigatório, único)
- password (Password)
  - Obrigatório apenas na CRIAÇÃO (canCreate())
  - Na edição, deixar vazio para não alterar
  - Validation: min:8 se preenchido

- roles (MultiSelect com Spatie, obrigatório)
  - Opções: admin, gerente, tecnico, operador
  - Um user pode ter múltiplas roles

Validações:
- email único no banco
- password >8 caracteres
- pelo menos uma role
```

### Permissões
- Apenas **admin** pode CRUD usuários
- Cada role pode ver suas informações (read)

---

## 📋 StatusAlertResource

### Coluna Tabela
| Campo | Tipo | Renderização |
|-------|------|--------------|
| machine | relation | nome da máquina |
| message | string | Texto descritivo |
| previous_status | string | Texto |
| new_status | string | Badge colorida |
| is_read | boolean | Toggle (com ícone✓/✗) |
| triggered_at | timestamp | Data/hora |

### Filtros
- Por machine (Select)
- Por is_read (Toggle/Checkbox)
- Por new_status (Select)

### Ações
- **Toggle "Marcar como lido"** — clica direto na linha
  - Muda is_read true/false
  - Refresh instantâneo

---

## 🔗 Relation Managers

### MachineResource > ServiceOrdersRelationManager
Mostra todas as O.S. da máquina

```
Colunas: title, type, priority, status, technician, created_at
Ações: view, edit, delete
Buttons: create
```

### MachineResource > MaintenanceLogsRelationManager
Histórico de intervenções

```
Colunas: action, defect_type, user, logged_at
Ações: view, edit, delete
Buttons: create
```

### MachineResource > MachineReadingsRelationManager
Leituras de sensores

```
Colunas: sensor_key, value, unit, read_at
Ações: view, delete
Buttons: create (para admin apenas, ou desabilitar)
```

### MachineResource > StatusAlertsRelationManager
Alertas da máquina

```
Colunas: message, previous_status, new_status, is_read, triggered_at
Ações: view
Buttons: none (apenas visualização)
```

---

## 🛡️ Proteção com Policies

### MachineResource
```php
canCreate() → admin, gerente
canEdit()   → admin, gerente
canDelete() → admin
canView()   → admin, gerente, tecnico, operador
```

### ServiceOrderResource
```php
canCreate() → admin, gerente, tecnico
canEdit()   → admin, gerente, + tecnico da O.S.
canDelete() → admin
canView()   → todos
```

### MaintenanceLogResource
```php
canCreate() → admin, gerente, tecnico
canEdit()   → admin, gerente
canDelete() → admin
canView()   → todos
```

### UserResource
```php
canCreate() → admin
canEdit()   → admin
canDelete() → admin
canView()   → admin (pode listar), todos (podem ver própil)
```

### StatusAlertResource
```php
canCreate() → sistema (automático)
canEdit()   → admin, gerente (marcar como lido)
canDelete() → admin
canView()   → todos
```

---

*[[_Documentação/README]] | [[_Documentação/03-Banco-de-Dados]] | [[_Documentação/05-Dashboard]]*
