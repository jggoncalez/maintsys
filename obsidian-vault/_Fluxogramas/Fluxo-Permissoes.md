# 🛡️ Fluxo de Autorização e Permissões

## 🔐 Middleware Stack: Request → Authorization

```mermaid
flowchart TD
    A["HTTP Request"] --> B["HTTP Kernel Middleware"]
    B --> C["auth:sanctum"]
    C --> D{Session válida?}
    D -->|Não| E["❌ Redirect /admin/login"]
    D -->|Sim| F["Carrega User Model"]
    F --> G["Spatie Load Roles"]
    G --> H["Spatie Load Permissions"]
    H --> I["Request Pass"]
    I --> J["Route Handler"]
    J --> K["Filament Resource"]
    K --> L["canCreate/Edit/Delete?"]
    L -->|Não| M["Form campos desabilitados"]
    L -->|Sim| N["Policy::authorize()"]
    N --> O{HasRole?}
    O -->|Não| P["🚫 403 Forbidden"]
    O -->|Sim| Q["✅ Ação liberada"]
    Q --> R["Controller executa"]
    R --> S["Model::create/update/delete"]
    S --> T["✅ Salvo BD"]

    style E fill:#e74c3c
    style P fill:#e74c3c
    style T fill:#27ae60
```

---

## 👤 Role Hierarchy

```mermaid
flowchart TD
    A["Super Admin<br/>(Full Access)"] -.->|Inclui permissões de| B["Admin<br/>CRUD Everything"]
    B -.->|Inclui permissões de| C["Gerente<br/>Gestão Operacional"]
    C -.->|Inclui permissões de| D["Tecnico<br/>Execução"]
    D -.->|Inclui permissões de| E["Operador<br/>View Only"]

    style A fill:#c0392b
    style B fill:#e74c3c
    style C fill:#f39c12
    style D fill:#f1c40f
    style E fill:#27ae60
```

---

## 📊 Fluxo: Verificação de Permissão em Resource

```mermaid
flowchart TD
    A["Usuário clica 'Create'"] --> B["Filament chama canCreate()"]
    B --> C{Static method}
    C --> D["return auth()->user()?->hasRole(...)"]
    D --> E{Has Role?}
    E -->|Não| F["❌ Button disabled"]
    E -->|Sim| G["✅ Button enabled"]
    G --> H["User clica 'Submit'"]
    H --> I["Form submission"]
    I --> J["Authorize Middleware"]
    J --> K["MachinePolicy::create()"]
    K --> L{Policy says OK?}
    L -->|Não| M["🚫 403"]
    L -->|Sim| N["MachineController@store"]
    N --> O["Machine::create()"]
    O --> P["✅ Salvo"]

    style F fill:#f39c12
    style G fill:#27ae60
    style P fill:#27ae60
    style M fill:#e74c3c
```

---

## 🎭 Matrix: Roles vs Resources

```mermaid
graph TB
    subgraph admin["👑 ADMIN"]
        A1["✅ Machines CRUD"]
        A2["✅ ServiceOrders CRUD"]
        A3["✅ MaintenanceLogs CRUD"]
        A4["✅ StatusAlerts CRUD"]
        A5["✅ Users CRUD"]
    end

    subgraph gerente["📊 GERENTE"]
        G1["✅ Machines RU"]
        G2["✅ ServiceOrders CRUD"]
        G3["✅ MaintenanceLogs RU"]
        G4["✅ StatusAlerts RU"]
        G5["❌ Users -"]
    end

    subgraph tecnico["🔧 TECNICO"]
        T1["✅ Machines R"]
        T2["✅ ServiceOrders RU*"]
        T3["✅ MaintenanceLogs CR"]
        T4["✅ StatusAlerts R"]
        T5["❌ Users -"]
    end

    subgraph operador["👁️ OPERADOR"]
        O1["✅ Machines R"]
        O2["✅ ServiceOrders R"]
        O3["✅ MaintenanceLogs R"]
        O4["✅ StatusAlerts R"]
        O5["❌ Users -"]
    end

    style admin fill:#e74c3c
    style gerente fill:#f39c12
    style tecnico fill:#f1c40f
    style operador fill:#27ae60
```

**Legenda:** C=Create, R=Read, U=Update, D=Delete, `-`=Sem acesso, `*`=Restrições

---

## 🎯 Fluxo: Técnico Só Edita Próprias O.S.

```mermaid
flowchart TD
    A["👤 Técnico 1 acessa O.S."] --> B["O.S. atribuída a Técnico 2"]
    B --> C["Clica 'Edit'"]
    C --> D["ServiceOrderPolicy::update()"]
    D --> E{"auth()->id() === O.S.->technician_id?"}
    E -->|Não| F["🚫 403 Forbidden"]
    E -->|Sim| G["✅ Editor liberado"]
    G --> H["Só pode mudar status"]
    H --> I["Não pode mudar technician_id"]
    I --> J["✅ Salva"]

    style F fill:#e74c3c
    style J fill:#27ae60
```

---

## 📱 Fluxo: Proteger Ação em Tabela

```mermaid
flowchart TD
    A["Tabela ServiceOrders"] --> B["Colunas com Actions"]
    B --> C["Edit, Delete, etc"]
    C --> D["Filament renderiza ações"]
    D --> E{"canEdit()?"}
    E -->|Não| F["Ícone Edit desabilitado"]
    E -->|Sim| G{canDelete()?}
    G -->|Não| H["Delete hidden"]
    G -->|Sim| I["Ambos disponíveis"]
    I --> J["User clica Edit"]
    J --> K["Modal abre"]
    K --> L["Policy valida de novo"]
    L --> M{Autorizado?}
    M -->|Não| N["Modal error"]
    M -->|Sim| O["Campos editáveis"]

    style F fill:#f39c12
    style H fill:#f39c12
    style O fill:#27ae60
```

---

## 🔄 Fluxo: Gerente Muda Status de Machine

```mermaid
flowchart TD
    A["👤 Gerente vê Machine"] --> B["Status = 'operational'"]
    B --> C["Clica Status Select"]
    C --> D["Muda para 'critical'"]
    D --> E["Submit"]
    E --> F["MachinePolicy::update()"]
    F --> G{"auth()->user()->hasRole(['admin', 'gerente'])?"}
    G -->|Não| H["🚫 403"]
    G -->|Sim| I["Machine::update()"]
    I --> J["Boot Hook dispara"]
    J --> K["StatusAlert::create()"]
    K --> L["✅ Alerta enviado"]
    L --> M["CriticalAlertsWidget atualiza"]
    M --> N["Dashboard mostra alerta"]

    style H fill:#e74c3c
    style N fill:#e74c3c
```

---

## 🧪 Test Checklist: Autorização

```mermaid
graph TD
    T1["Login como Admin<br/>✅ Pode criar Máquina"]
    T2["Login como Gerente<br/>✅ Pode criar Máquina"]
    T3["Login como Técnico<br/>❌ Não pode criar Máquina"]
    T4["Login como Operador<br/>❌ Não pode criar nada"]

    T5["Técnico vê O.S. de outro<br/>✅ Pode visualizar"]
    T6["Técnico tenta editar O.S. alheio<br/>❌ 403 Forbidden"]
    T7["Técnico edita própria O.S.<br/>✅ Salva"]

    T8["Operador vê Dashboard<br/>✅ Dados carregam"]
    T9["Operador tenta editar Máquina<br/>❌ Botão desabilitado"]

    T10["Admin vê Users<br/>✅ Listagem"]
    T11["Gerente acessa Users<br/>❌ 403 Forbidden"]

    style T1 fill:#27ae60
    style T3 fill:#e74c3c
    style T6 fill:#e74c3c
    style T11 fill:#e74c3c
```

---

## 📋 Gates vs Policies

| Situação | Usar | Exemplo |
|----------|------|---------|
| Ação global | **Gate** | `Gate::allows('admin')` |
| Recurso específico | **Policy** | `$policy->update($user, $model)` |
| Check simples | **Gate** | `can('view_dashboard')` |
| Lógica complexa | **Policy** | Policy methods |

```php
// Gate (global)
Gate::define('admin-only', function (User $user) {
    return $user->hasRole('admin');
});

// Policy (model-specific)
class MachinePolicy {
    public function update(User $user, Machine $machine) {
        return $user->hasAnyRole(['admin', 'gerente']);
    }
}

// Usage
if (Gate::allows('admin-only')) { }
if ($user->can('update', $machine)) { }
```

---

*[[DIAGRAMAS]] | [[_Fluxogramas/Fluxo-MQTT]] | [[Arquitetura-Tecnica]]*
