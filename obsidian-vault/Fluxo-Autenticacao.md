# 🎨 Fluxo de Autenticação e Login

## 🔐 Diagrama: Autenticação Filament + Spatie

```mermaid
flowchart TD
    A["🌐 Usuário acessa /admin"] --> B{Autenticado?}
    B -->|Não| C["📝 Filament Auth Page"]
    C --> D["Insere email + password"]
    D --> E["POST /auth/login"]
    E --> F{Credenciais válidas?}
    F -->|Não| G["❌ Erro: Invalid credentials"]
    G --> C
    F -->|Sim| H["✅ Laravel Auth valida"]
    H --> I["Carrega User Model"]
    I --> J["Spatie carrega roles + permissions"]
    J --> K{Usuário ativo?}
    K -->|Não| L["⛔ Account disabled"]
    K -->|Sim| M["🔓 Session criada"]
    M --> N["redirect /admin"]
    B -->|Sim| O["Renderiza PanelProvider"]
    O --> P["Carrega Widgets + Navigation"]
    P --> Q{Tem permissão?}
    Q -->|Não| R["🚫 403 Forbidden"]
    Q -->|Sim| S["✅ Dashboard Carregada"]
    S --> T["📊 StatsOverviewWidget"]
    T --> U["4 Cards com dados"]
    U --> V["Widgets renderem"]
    V --> W["Página pronta"]

    style A fill:#4a90e2
    style G fill:#e74c3c
    style M fill:#27ae60
    style S fill:#27ae60
    style R fill:#e74c3c
```

---

## 🔄 Fluxo de Sessão

```mermaid
flowchart LR
    A["Middleware: auth:sanctum"] --> B["Verifica session cookie"]
    B --> C{User existe?}
    C -->|Não| D["❌ Redirect /admin/login"]
    C -->|Sim| E["Carrega User + roles"]
    E --> F["Spatie::hasRole()"]
    F --> G["✅ Autorizado"]
    G --> H["Request continua"]

    style D fill:#e74c3c
    style G fill:#27ae60
```

---

## 📋 Verificação de Permissões

```mermaid
flowchart TD
    A["Usuário clica em ação no Filament"] --> B["Filament chama canCreate/Edit/Delete"]
    B --> C["Gate ou Policy verifica"]
    C --> D{Auth::user()->hasRole()?}
    D -->|false| E["🚫 Ação desabilitada"]
    D -->|true| F["Spatie::hasPermissionTo()"]
    F --> G{Tem permissão?}
    G -->|false| H["403 Unauthorized"]
    G -->|true| I["✅ Executa controller"]
    I --> J["Model salvo ou deletado"]

    style E fill:#f39c12
    style H fill:#e74c3c
    style I fill:#27ae60
```

---

## 📊 Matrix de Acesso por Rota

| Rota | Admin | Gerente | Tecnico | Operador |
|------|:-----:|:-------:|:-------:|:--------:|
| `/admin` | ✅ | ✅ | ✅ | ✅ |
| `/admin/machines` (view) | ✅ | ✅ | ✅ | ✅ |
| `/admin/machines/create` | ✅ | ✅ | ❌ | ❌ |
| `/admin/machines/edit` | ✅ | ✅ | ❌ | ❌ |
| `/admin/service-orders` | ✅ | ✅ | ✅ | ✅ |
| `/admin/service-orders/create` | ✅ | ✅ | ✅ | ❌ |
| `/admin/users` | ✅ | ❌ | ❌ | ❌ |

---

*[[DIAGRAMAS]] | [[Fluxo-Ordem-Serviço]]*
