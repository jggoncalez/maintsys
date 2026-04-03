# 🔧 Fluxo de Ordem de Serviço (Manutenção)

## 🎯 Fluxo Completo de O.S.

```mermaid
flowchart TD
    A["📋 Gerente cria nova O.S."] --> B["Preenche formulário"]
    B --> C["machine_id, type, priority, technician_id"]
    C --> D["✅ Submit formulário"]
    D --> E["ServiceOrderController@create"]
    E --> F["Validação (title, description obrigatórios)"]
    F --> G{Dados válidos?}
    G -->|Não| H["❌ Erro de validação"]
    H --> B
    G -->|Sim| I["ServiceOrder::create()"]
    I --> J["Status = 'open'"]
    J --> K["created_by = auth()->id()"]
    K --> L["✅ O.S. salva no BD"]
    L --> M["Notificação para técnico"]
    M --> N["📧 Email + Filament Notification"]
    N --> O["✅ Redirect para ServiceOrderResource"]

    style A fill:#3498db
    style L fill:#27ae60
    style H fill:#e74c3c
    style M fill:#f39c12
```

---

## ⏱️ Fluxo: Técnico Inicia O.S.

```mermaid
flowchart TD
    A["👤 Técnico vê O.S. em aberto"] --> B["Tabela de ServiceOrders"]
    B --> C["Status = 'open'"]
    C --> D["Clica botão 'Iniciar O.S.'"]
    D --> E["Livewire Action dispara"]
    E --> F{Autorização OK?}
    F -->|Não| G["🚫 403 Forbidden"]
    F -->|Sim| H["ServiceOrderController@start"]
    H --> I["ServiceOrder::start()"]
    I --> J["status = 'in_progress'"]
    J --> K["started_at = now()"]
    K --> L["$serviceOrder->save()"]
    L --> M["✅ O.S. iniciada"]
    M --> N["Notificação ao gerente"]
    N --> O["Dashboard atualiza"]
    O --> P["O.S. sai de 'Abertas'"]
    P --> Q["Aparece em 'Em Progresso'"]

    style D fill:#f39c12
    style M fill:#27ae60
    style G fill:#e74c3c
```

---

## 📝 Fluxo: Criar Maintenance Log

```mermaid
flowchart TD
    A["👤 Técnico realiza manutenção"] --> B["Acessa machine details"]
    B --> C["Clica 'Adicionar Maintenance Log'"]
    C --> D["Abre formulário"]
    D --> E["Preenche: action, description, defect_type"]
    E --> F["Seleciona service_order (opcional)"]
    F --> G["logged_at (padrão: now)"]
    G --> H["Submit"]
    H --> I["MaintenanceLogController@create"]
    I --> J["Validação"]
    J --> K{machine_id existe?}
    K -->|Não| L["❌ Erro: Machine not found"]
    K -->|Sim| M{"service_order_id válida?"}
    M -->|Inválida| N["❌ Erro: Invalid O.S."]
    M -->|Válida| O["MaintenanceLog::create()"]
    O --> P["Salva action, defect_type, etc"]
    P --> Q["✅ Log criado"]
    Q --> R["Atualiza 'last_reading_at' da Machine"]
    R --> S["Refresh tabela de logs"]
    S --> T["Log aparece em histórico"]

    style A fill:#9b59b6
    style Q fill:#27ae60
    style L fill:#e74c3c
```

---

## ✅ Fluxo: Concluir O.S.

```mermaid
flowchart TD
    A["👤 Técnico conclui manutenção"] --> B["O.S. em 'in_progress'"]
    B --> C["Clica 'Concluir O.S.'"]
    C --> D["Modal com Textarea"]
    D --> E["Digita resolution_notes"]
    E --> F["Clica 'Confirmar'"]
    F --> G["ServiceOrderController@complete"]
    G --> H["Livewire valida"]
    H --> I{resolution_notes não vazio?}
    I -->|Vazio| J["❌ Erro: Notas obrigatórias"]
    I -->|OK| K["ServiceOrder::complete()"]
    K --> L["status = 'completed'"]
    L --> M["completed_at = now()"]
    M --> N["resolution_notes = texto"]
    N --> O["$serviceOrder->save()"]
    O --> P["✅ O.S. concluída"]
    P --> Q["Notificação ao gerente"]
    Q --> R["MaintenanceLogWidget atualiza"]
    R --> S["O.S. sai de 'Recentes'"]

    style C fill:#f39c12
    style P fill:#27ae60
    style J fill:#e74c3c
```

---

## 🔄 Fluxo Alternativo: Técnico Reabre O.S.

```mermaid
flowchart TD
    A["O.S. em 'completed'"] --> B["Técnico finds problema"]
    B --> C["Clica 'Reabrir O.S.'"]
    C --> D["Modal de confirmação"]
    D --> E["Confirma reabertura"]
    E --> F["ServiceOrder::update()"]
    F --> G["status = 'open'"]
    G --> H["completed_at = null"]
    H --> I["✅ O.S. reabierta"]
    I --> J["Volta para fila de abertas"]

    style I fill:#27ae60
```

---

## 📊 Status Diagrama (State Machine)

```mermaid
stateDiagram-v2
    [*] --> open: Gerente cria
    open --> in_progress: Técnico clica "Iniciar"
    in_progress --> completed: Técnico clica "Concluir"
    completed --> open: Técnico clica "Reabrir"
    open --> cancelled: Gerente cancela
    in_progress --> cancelled: Gerente cancela
    completed --> [*]
    cancelled --> [*]

    note right of open
        Aguardando técnico
    end note

    note right of in_progress
        Técnico trabalhando
    end note

    note right of completed
        Finalizada com notas
    end note

    note right of cancelled
        Descartada/Cancelada
    end note
```

---

*[[DIAGRAMAS]] | [[_Fluxogramas/Fluxo-Autenticacao]] | [[_Fluxogramas/Fluxo-Status-Alert]]*
