# 🗺️ Mapa de Rotas Filament v5 - MaintSys

## URL Map para Admin Panel

### 🏗️ MÁQUINAS
```
GET    /admin/machines                    → ListMachines (Tabela com todos os registros)
GET    /admin/machines/create             → CreateMachine (Formulário vazio)
POST   /admin/machines                    → CreateMachine (Salvar novo registro)
GET    /admin/machines/{machine}          → ViewMachine (Visualizar - Read Only)
GET    /admin/machines/{machine}/edit     → EditMachine (Formulário preenchido)
PATCH  /admin/machines/{machine}          → EditMachine (Salvar edição)
DELETE /admin/machines/{machine}          → MachineResource (Delete - Admin only)
```

**Actions na Tabela:**
- Visualizar → `/admin/machines/{machine}`
- Editar → `/admin/machines/{machine}/edit`
- Marcar Crítica → Atualiza em tempo real
- Marcar Operacional → Atualiza em tempo real

---

### 🔧 ORDENS DE SERVIÇO
```
GET    /admin/service-orders              → ListServiceOrders
GET    /admin/service-orders/create       → CreateServiceOrder
POST   /admin/service-orders              → CreateServiceOrder
GET    /admin/service-orders/{order}      → ViewServiceOrder (Read Only)
GET    /admin/service-orders/{order}/edit → EditServiceOrder
PATCH  /admin/service-orders/{order}      → EditServiceOrder
DELETE /admin/service-orders/{order}      → ServiceOrderResource (Delete - Admin only)
```

**Actions na View Page:**
- START (Se status = open) → `$order->start()`
- COMPLETE (Se status = in_progress) → `$order->complete($notes)`

---

### 📝 LOGS DE MANUTENÇÃO
```
GET    /admin/maintenance-logs                  → ListMaintenanceLogs
GET    /admin/maintenance-logs/create           → CreateMaintenanceLog
POST   /admin/maintenance-logs                  → CreateMaintenanceLog
GET    /admin/maintenance-logs/{log}            → ViewMaintenanceLog (Read Only)
GET    /admin/maintenance-logs/{log}/edit       → EditMaintenanceLog
PATCH  /admin/maintenance-logs/{log}            → EditMaintenanceLog
DELETE /admin/maintenance-logs/{log}            → MaintenanceLogResource
```

---

### 🚨 ALERTAS DE STATUS
```
GET    /admin/status-alerts           → ListStatusAlerts
GET    /admin/status-alerts/{alert}   → ViewStatusAlert (Read Only)
```

**Actions na View Page:**
- Toggle Read Status → Atualiza `is_read` em tempo real

*Nota: Sem Create/Edit porque são criados automaticamente*

---

### 👥 USUÁRIOS (Admin Only)
```
GET    /admin/users                   → ListUsers
GET    /admin/users/create            → CreateUser
POST   /admin/users                   → CreateUser
GET    /admin/users/{user}            → ViewUser (Read Only)
GET    /admin/users/{user}/edit       → EditUser
PATCH  /admin/users/{user}            → EditUser
DELETE /admin/users/{user}            → UserResource (Delete)
```

---

## 🔐 Permissões por Rota

### MachineResource
| Rota | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| GET /machines | ✅ | ✅ | ❌ |
| POST /machines | ✅ | ✅ | ❌ |
| GET /machines/{id} | ✅ | ✅ | ❌ |
| PATCH /machines/{id} | ✅ | ✅ | ❌ |
| DELETE /machines/{id} | ✅ | ❌ | ❌ |

### ServiceOrderResource
| Rota | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| GET /service-orders | ✅ | ✅ | ✅ |
| POST /service-orders | ✅ | ✅ | ✅ |
| GET /service-orders/{id} | ✅ | ✅ | ✅ |
| PATCH /service-orders/{id} | ✅ | ✅ | ❌ |
| DELETE /service-orders/{id} | ✅ | ❌ | ❌ |

### MaintenanceLogResource
| Rota | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| GET /maintenance-logs | ✅ | ✅ | ✅ |
| POST /maintenance-logs | ✅ | ✅ | ✅ |
| GET /maintenance-logs/{id} | ✅ | ✅ | ✅ |
| PATCH /maintenance-logs/{id} | ✅ | ✅ | ❌ |
| DELETE /maintenance-logs/{id} | ✅ | ✅ | ❌ |

### StatusAlertResource
| Rota | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| GET /status-alerts | ✅ | ✅ | ✅ |
| GET /status-alerts/{id} | ✅ | ✅ | ✅ |
| *No create/edit* | - | - | - |

### UserResource
| Rota | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| GET /users | ✅ | ❌ | ❌ |
| POST /users | ✅ | ❌ | ❌ |
| GET /users/{id} | ✅ | ❌ | ❌ |
| PATCH /users/{id} | ✅ | ❌ | ❌ |
| DELETE /users/{id} | ✅ | ❌ | ❌ |

---

## 📱 Navegação entre Páginas

```
┌─────────────────┐
│  ListPage       │  (Tabela de todos)
│ [Create Button] │
└────────┬────────┘
         │
         ├──→ [Criar] ──→ CreatePage ──→ (Salva) ──→ ListPage
         │
         └──→ [Visualizar] ──→ ViewPage (Read-Only)
                                   │
                                   └──→ [Editar] ──→ EditPage ──→ (Salva) ──→ ListPage
                                   │
                                   └──→ [Deletar] ──→ (Confirmação) ──→ ListPage
```

---

## 🎯 Query Parameters

### ListPage
```
?search=valor              # Busca em campos searchable
?sort=-created_at         # Ordenação (- para descendente)
?per_page=50              # Itens por página (padrão 10)
?page=2                   # Número da página
?tableFilters[...]=value  # Filtros aplicados
```

### Example
```
/admin/machines?search=MACH-001&sort=-created_at&per_page=25&page=1
```

---

## 🚀 Exemplo de Fluxo Completo

### Criando uma nova Máquina

1. **Acessar lista**
   ```
   GET /admin/machines
   ```

2. **Clicar em "Adicionar Máquina"**
   ```
   GET /admin/machines/create
   ```

3. **Preencher formulário e salvar**
   ```
   POST /admin/machines
   Data: {serial_number, name, model, location, ...}
   ```

4. **Redireciona para lista**
   ```
   GET /admin/machines (com flash message de sucesso)
   ```

5. **Clicar no registro para visualizar**
   ```
   GET /admin/machines/{new_machine_id}
   ```

6. **Clicar em Editar**
   ```
   GET /admin/machines/{new_machine_id}/edit
   ```

7. **Modificar e salvar**
   ```
   PATCH /admin/machines/{new_machine_id}
   ```

8. **Redireciona para lista**
   ```
   GET /admin/machines (com flash message de sucesso)
   ```

---

## 💾 Urls Diretas (Bookmarks)

```
Machine Management
  /admin/machines

Order Management
  /admin/service-orders

Maintenance
  /admin/maintenance-logs

Alerts
  /admin/status-alerts

Users (Admin)
  /admin/users
```
