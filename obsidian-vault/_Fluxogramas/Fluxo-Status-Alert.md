# 🚨 Fluxo de Alertas de Status

## 🔴 Fluxo: Mudança de Status da Máquina

```mermaid
flowchart TD
    A["👤 Gerente acessa detalhe da Machine"] --> B["Clica em status (Select)"]
    B --> C["Muda de 'operational' → 'critical'"]
    C --> D["Clica Save"]
    D --> E["MachineController@update"]
    E --> F["Machine::update(['status' => 'critical'])"]
    F --> G["Boot Hook dispara"]
    G --> H["Machine::updating()"]
    H --> I{"isDirty('status')?"}
    I -->|Não| J["Salva normalmente"]
    I -->|Sim| K["Get previous_status"]
    K --> L["Get new_status"]
    L --> M["StatusAlert::create()"]
    M --> N["Salva: previous_status, new_status"]
    N --> O["message = 'Machine X mudou...'"]
    O --> P["is_read = false"]
    P --> Q["triggered_at = now()"]
    Q --> R["✅ StatusAlert criada"]
    R --> S["Notification::make()"]
    S --> T["when(new_status === 'critical')"]
    T --> U["->danger()"]
    U --> V["->sendToDatabase(User::all())"]
    V --> W["✅ Notificação enviada"]
    W --> X["CriticalAlertsWidget atualiza"]
    X --> Y["Alerta aparece no Dashboard"]

    style C fill:#e74c3c
    style R fill:#27ae60
    style Y fill:#f39c12
```

---

## 📱 Fluxo: Alerta Aparece no Dashboard

```mermaid
flowchart TD
    A["StatusAlert criada no BD"] --> B["CriticalAlertsWidget recarrega"]
    B --> C["Query: StatusAlert::where('is_read', false)"]
    C --> D["Pega últimas 10"]
    D --> E["Renderiza em tabela"]
    E --> F["Cores por new_status"]
    F --> G{new_status?}
    G -->|critical| H["🔴 Vermelho"]
    G -->|maintenance| I["🟡 Amarelo"]
    G -->|operational| J["🟢 Verde"]
    H --> K["Exibe na Dashboard"]
    I --> K
    J --> K
    K --> L["Widget mostra:"]
    L --> M["machine | message | new_status | triggered_at"]
    M --> N["✅ Alerta visível para gerente"]

    style K fill:#27ae60
    style H fill:#e74c3c
    style I fill:#f39c12
    style J fill:#27ae60
```

---

## ✅ Fluxo: Marcar Alerta como Lido

```mermaid
flowchart TD
    A["👤 Gerente vê alerta no Dashboard"] --> B["Clica botão ✓ 'Marcar Lido'"]
    B --> C["Livewire Toggle Action"]
    C --> D{Autorizado?}
    D -->|Não| E["🚫 403"]
    D -->|Sim| F["StatusAlertController@update"]
    F --> G["StatusAlert::update(['is_read' => true])"]
    G --> H["✅ Alert marcado como lido"]
    H --> I["Livewire refresh"]
    I --> J["Widget atualiza"]
    J --> K["Alerta desaparece da lista"]
    K --> L["Fade-out animation"]

    style B fill:#f39c12
    style H fill:#27ae60
    style K fill:#27ae60
```

---

## 🔄 Fluxo Completo: Machine Status Change → Alert → Dashboard

```mermaid
flowchart LR
    A["Gerente muda status"] -->|1| B["Machine::update()"]
    B -->|2| C["Boot Hook"]
    C -->|3| D["StatusAlert::create()"]
    D -->|4| E["Notification enviada"]
    E -->|5| F["DB notificações"]
    F -->|6| G["CriticalAlertsWidget query"]
    G -->|7| H["Renderiza alerta"]
    H -->|8| I["Dashboard atualiza"]
    I -->|9| J["✅ Gerente vê alerta"]
    J -->|10| K["Clica Marcar Lido"]
    K -->|11| L["StatusAlert->update()"]
    L -->|12| M["is_read = true"]
    M -->|13| N["Alerta sai da lista"]

    style A fill:#4a90e2
    style J fill:#27ae60
    style N fill:#27ae60
```

---

## 📊 Timeline: Status Transitions

```mermaid
timeline
    title Máquina SN-2024-001 - Timeline de Status

    section Janeiro
    Jan 1: operational
    Jan 15: operational

    section Fevereiro
    Feb 1: maintenance (Revisão)
    Feb 10: operational
    Feb 28: operational

    section Março
    Mar 5: critical (Alarme vibrações)
    Mar 6: maintenance (Técnico atribui)
    Mar 10: operational (Correto)
    Mar 20: critical (Motor falha)
    Mar 22: offline (Pendente peça)
```

---

## 🎯 Matrix: Quando Alertas São Criados

| De | Para | Alerta | Cor | Notificação |
|----|------|--------|-----|-------------|
| operational | maintenance | ✅ | Amarelo | ✅ |
| operational | critical | ✅ | Vermelho | ✅✅ (urgente) |
| operational | offline | ✅ | Cinza | ✅ |
| maintenance | operational | ✅ | Verde | ✅ |
| maintenance | critical | ✅ | Vermelho | ✅✅ |
| critical | operational | ✅ | Verde | ✅ |
| critical → critical | ❌ | — | — | ❌ (sem mudança) |

---

*[[DIAGRAMAS]] | [[_Fluxogramas/Fluxo-Ordem-Servico]] | [[_Fluxogramas/Fluxo-MQTT]]*
