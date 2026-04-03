# 05 - Dashboard

## 📊 Página Inicial (Dashboard)

Deve ser a página de default ao fazer login. Contém widgets com visualização rápida do estado do sistema.

---

## 🎯 StatsOverviewWidget

**Tipo:** Card widgets (4 cards em linha)

### Card 1: Total de Máquinas
```
Ícone: ⚙️ (gear)
Valor: count(Machine::all())
Cor: blue
Ação: link para MachineResource
```

### Card 2: Máquinas Operacionais
```
Ícone: ✅ (check)
Valor: count(Machine::where('status', 'operational'))
Cor: green (success)
Ação: link para MachineResource com filtro status=operational
```

### Card 3: Em Manutenção
```
Ícone: 🔧 (wrench)
Valor: count(Machine::where('status', 'maintenance'))
Cor: yellow (warning)
Ação: link para MachineResource com filtro status=maintenance
```

### Card 4: Estado Crítico
```
Ícone: 🚨 (alert)
Valor: count(Machine::where('status', 'critical'))
Cor: red (danger)
Ação: link para MachineResource com filtro status=critical
```

**Atualização:** Refresh a cada 30s (WebSocket ou polling)

---

## 📋 RecentServiceOrdersWidget

**Tipo:** Table Widget

### Configuração
```
Modelo: ServiceOrder
Paginação: 5 primeiras
Ordenação: created_at DESC
Query: ServiceOrder::where('status', '!=', 'completed')->latest()
```

### Colunas
| Campo | Tipo | Renderização |
|-------|------|--------------|
| machine | relation | Link para máquina |
| title | string | Texto truncado (max 40 chars) |
| type | enum | Badge (preventive=info, corrective=warning) |
| priority | enum | Badge (low=gray, medium=blue, high=orange, critical=red) |
| technician | relation | Nome do técnico |
| created_at | timestamp | "há X horas" ou data |

### Ações
- 👁️ **View** - abrir detalhes da O.S.
- **Status Badge Clicável** - filtrar por status na lista principal

---

## 🚨 CriticalAlertsWidget

**Tipo:** Table Widget com ação interativa

### Configuração
```
Modelo: StatusAlert
Paginação: últimos 10 alertas não lidos
Query: StatusAlert::where('is_read', false)->latest('triggered_at')->take(10)
```

### Colunas
| Campo | Tipo | Renderização |
|-------|------|--------------|
| machine | relation | Link para máquina |
| message | string | Texto completo |
| new_status | string | Badge (critical=red, maintenance=yellow, operational=green) |
| triggered_at | timestamp | "há X minutos" |

### Ação em Linha
- ✅ **"Marcar como lido"** (toggle)
  - Updates `is_read = true`
  - Animation fade-out da linha
  - Livewire refresh instantâneo

### Estilo
- Se `new_status = 'critical'`: destaque vermelho
- Se `new_status = 'maintenance'`: destaque amarelo
- Fundo levemente colorido por status

---

## 🔧 MaintenanceLogWidget

**Tipo:** Table Widget

### Configuração
```
Modelo: MaintenanceLog
Paginação: 5 mais recentes
Ordenação: logged_at DESC
Query: MaintenanceLog::latest('logged_at')->take(5)
```

### Colunas
| Campo | Tipo | Renderização |
|-------|------|--------------|
| machine | relation | Link para máquina |
| action | string | Texto (ex: "Troca de correia") |
| defect_type | string | Texto ou badge |
| user | relation | Nome do técnico |
| logged_at | timestamp | Data/hora formatada |

### Ações
- 👁️ **View** - abrir transição completa do log

---

## 📱 Layout da Dashboard

```
┌─────────────────────────────────────────────────────────┐
│              FILAMENT NAVIGATION TOP BAR               │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                    PAGE TITLE                           │
│                    Dashboard                            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│    StatsOverviewWidget (4 cards em linha)               │
│  [ Total ]  [ Operacionais ]  [ Manutenção ]  [ Crítica]│
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  RecentServiceOrdersWidget          CriticalAlertsWidget│
│  (Últimas 5 O.S. abertas)       (Últimos alertas)      │
│  ┌─────────────────┐         ┌──────────────────┐     │
│  │ title | type    │         │ machine | message│     │
│  │ ...   | ...     │         │ ...     | ...    │     │
│  └─────────────────┘         └──────────────────┘     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  MaintenanceLogWidget (últimas 5 intervenções)          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ machine | action | defect | user | logged_at    │  │
│  │ ...     | ...    | ...    | ...  | ...          │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## 🔧 Implementação (Filament)

### Registrar no PanelProvider

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->homeUrl('/admin')
        ->brandName('MaintSys')
        ->widgets([
            StatsOverviewWidget::class,
            RecentServiceOrdersWidget::class,
            CriticalAlertsWidget::class,
            MaintenanceLogWidget::class,
        ]);
}
```

---

## 🎨 Tema e Idioma

- **Locale:** pt_BR
- **Dark Mode:** Habilitado por padrão
- **Ícone do painel:** Wrench (🔧) ou Gear (⚙️)

---

*[[README]] | [[04-Filament-Resources]] | [[06-Permissões]]*
