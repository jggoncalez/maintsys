# 📂 Estrutura Visual — Organização MaintSys

```
obsidian-vault/
│
├── 🌟 START-HERE.md ..................... ← COMECE AQUI!
│
├── 📖 _Documentação/
│   ├── README.md ........................ Visão geral do projeto
│   ├── DIAGRAMAS.md ..................... Índice de fluxogramas
│   ├── ESTRUCTURA.md .................... Estrutura docs original
│   ├── RESUMO.md ........................ O que foi criado
│   │
│   ├── 01-Requisitos.md ................. Contexto & objetivos
│   ├── 02-Arquitetura.md ................ Design & padrões
│   ├── 03-Banco-de-Dados.md ............. Schema SQL completo
│   ├── 04-Filament-Resources.md ......... CRUD resources
│   ├── 05-Dashboard.md .................. Widgets & gráficos
│   ├── 06-Permissões.md ................. Spatie RBAC
│   ├── 07-Checklist.md .................. Implementação 7 fases
│   ├── 08-Diagrama-ER.md ................ Mermaid ER diagram
│   └── Arquitetura-Tecnica.md ........... Stack em camadas
│
├── 📊 _Fluxogramas/
│   ├── Fluxo-Autenticacao.md ............ 🔐 Login Process
│   │   ├─ Middleware Stack
│   │   ├─ Session Creation
│   │   └─ Dashboard Load
│   │
│   ├── Fluxo-Ordem-Servico.md ........... 📋 O.S. Lifecycle
│   │   ├─ Create by Gerente
│   │   ├─ Start by Técnico
│   │   ├─ Register Logs
│   │   └─ Complete with Notes
│   │
│   ├── Fluxo-Status-Alert.md ............ 🚨 Status Alerts
│   │   ├─ Machine Status Change
│   │   ├─ Boot Hook Trigger
│   │   ├─ DB Alert Creation
│   │   ├─ Notification Send
│   │   └─ Dashboard Update
│   │
│   ├── Fluxo-Permissoes.md .............. 🛡️ Authorization
│   │   ├─ Request → Middleware
│   │   ├─ Spatie Load
│   │   ├─ Role Check
│   │   ├─ Policy Verify
│   │   └─ Access Granted/Denied
│   │
│   └── Fluxo-MQTT.md .................... 📡 IoT/ESP-32
│       ├─ Sensor Reading
│       ├─ MQTT Publish
│       ├─ Listener Receive
│       ├─ Anomaly Detect
│       ├─ Status Update
│       └─ Real-time Dashboard
│
├── 🎨 _Canvas/ (Interactive Diagrams)
│   ├── MaintSys-Overview.canvas ......... Full project map
│   ├── Fluxo-Autenticacao.canvas ........ Login flow visual
│   ├── Fluxo-Ordem-Servico.canvas ....... O.S. lifecycle visual
│   ├── Schema-Banco-Dados.canvas ........ DB schema with FK
│   ├── RBAC-Permissoes.canvas ........... Roles & Permissions
│   ├── Fluxo-MQTT.canvas ................ IoT flow visual
│   └── Estrutura.canvas ................. Overall structure
│
└── 🔍 _Referência/
    ├── INDEX.md ......................... Busca por tópico/keyword
    │   ├─ Por palavra-chave
    │   ├─ Por role/perfil
    │   ├─ Por tópico (A-Z)
    │   ├─ Por tipo conteúdo
    │   ├─ Por fase projeto
    │   ├─ Por estágio aprendizado
    │   └─ Dúvidas comuns
    │
    └── Quick-Reference.md ............... Code snippets
        ├─ Migrations (6 prontas)
        ├─ Models (3 completos)
        ├─ Resources (Filament)
        ├─ Policies
        ├─ Widgets
        ├─ Seeders com Roles
        ├─ Tests
        ├─ MQTT Listener
        ├─ Routes & .env
        └─ Validation Rules
```

---

## 🎯 Guia de Navegação

### Por Tarefa

#### "Quero Entender o Projeto"
```
START-HERE.md
    ↓
_Documentação/README.md
    ↓
_Documentação/01-Requisitos.md
    ↓
_Canvas/MaintSys-Overview.canvas (ver visualmente)
```

#### "Quero Implementar"
```
_Documentação/07-Checklist.md (roadmap)
    ↓
_Referência/Quick-Reference.md (copy code)
    ↓
_Fluxogramas/Fluxo-* (consultar lógica)
    ↓
_Canvas/Fluxo-*.canvas (ver visualmente)
```

#### "Qual é o Fluxo de...?"
```
_Referência/INDEX.md (busque por tópico)
    ↓
_Fluxogramas/Fluxo-X.md (leia texto + Mermaid)
    ↓
_Canvas/Fluxo-X.canvas (veja interativo)
```

#### "Como Código?"
```
_Referência/Quick-Reference.md
    ↓
Copie trecho
    ↓
Adapte para seu projeto
```

#### "Qual é o Schema BD?"
```
_Documentação/03-Banco-de-Dados.md (SQL)
    ↓
_Documentação/08-Diagrama-ER.md (Mermaid ER)
    ↓
_Canvas/Schema-Banco-Dados.canvas (visual)
```

---

## 📊 Tipos de Conteúdo

### 📖 Documentação Textual (13 arquivos em _Documentação/)
- Explicações em português
- SQL schemas
- Tabelas estruturadas
- Checklists
- Diagramas Mermaid inline

### 📊 Fluxogramas (5 arquivos em _Fluxogramas/)
- Flowcharts Mermaid detalhados
- Sequência de operações
- Decisões e branches
- Estado machines
- Explicações em português

### 🎨 Canvas Visuais (7 arquivos em _Canvas/)
- Diagramas interativos
- Nodes clicáveis
- Zoom/Pan
- Cores por categoria
- Links entre elementos
- **Abra no Obsidian Graph View**

### 💻 Code Reference (Quick-Reference.md)
- Migrations prontas
- Models com relacionamentos
- Resources Filament
- Policies
- Tests
- **Copy-paste ready**

### 🔍 Índices (INDEX.md)
- Busca por palavra-chave
- Busca por role/perfil
- Busca por tópico A-Z
- Busca por tipo conteúdo
- Busca por fase projeto

---

## ✨ Cores & Convenções

### Cores Emoji
- 📖 = Documentação
- 📊 = Fluxogramas/Diagramas
- 🎨 = Canvas Visual
- 🔍 = Referência/Índices
- 💻 = Code

### Cores Pastas
- `_Documentação/` — Especificação principal
- `_Fluxogramas/` — Flowcharts Mermaid
- `_Canvas/` — Diagramas interativos
- `_Referência/` — Quick lookup

### Convenção Nomes
- `XX-Nome.md` — Números indicam leitura sequencial
- `Fluxo-*.md` — Flowcharts de processos
- `*.canvas` — Diagramas visuais interativos

---

## 📱 Como Abrir

### File Type: .md
```
Clique normal no Obsidian
Renderiza Mermaid inline
Navega links com Ctrl+Click
```

### File Type: .canvas
```
Clique normal no Obsidian
Abre Graph View interativa
Zoom/Pan com mouse
Clica em nodes para expandir
```

---

## 🔗 Links Úteis

### Na Raiz
```
START-HERE.md ← Ponto de partida Visual
```

### Em Cada Pasta
```
README.md em _Documentação/ ← Overview
INDEX.md em _Referência/ ← Busca rápida
```

### Entre Arquivos
```
Links automáticos com [[arquivo]]
Funciona entre pastas: [[_Documentação/README]]
```

---

## 📈 Navegação em 3 Cliques

### Cenário 1: "Qual é o fluxo de login?"
```
1. Clique em: _Referência/INDEX.md
2. Procure: "Autenticação"
3. Clique em: Fluxo-Autenticacao.md ou .canvas
```

### Cenário 2: "Como implemento?"
```
1. Clique em: START-HERE.md
2. Siga instruções
3. Abra: _Documentação/07-Checklist.md
```

### Cenário 3: "Qual é o schema BD?"
```
1. Clique em: _Documentação/03-Banco-de-Dados.md
2. Leia schema SQL
3. Veja em: _Canvas/Schema-Banco-Dados.canvas
```

---

## 🎁 Quick Access (Arquivos Frequentes)

| Necessidade | Arquivo | Tipo |
|---|---|---|
| Comece aqui | `START-HERE.md` | 📝 |
| Veja mapa visual | `_Canvas/MaintSys-Overview.canvas` | 🎨 |
| Implemente | `_Documentação/07-Checklist.md` | 📋 |
| Copie código | `_Referência/Quick-Reference.md` | 💻 |
| Busque tópico | `_Referência/INDEX.md` | 🔍 |
| Qual fluxo? | `_Fluxogramas/Fluxo-*.md` | 📊 |

---

## 📊 Estatísticas

```
Total de Arquivos:     30
├─ Markdown:           20
├─ Canvas:             7
├─ Folders:            4
└─ Root:               1

Linhas de Conteúdo:    5500+
Diagramas Mermaid:     30+
Canvas Interativos:    7
Links Cruzados:        100+
Code Snippets:         15+
```

---

## 🚀 TL;DR (Very Quick Start)

```
1. Abra: START-HERE.md
2. Explore: _Canvas/MaintSys-Overview.canvas
3. Implemente: _Documentação/07-Checklist.md
4. Copie: _Referência/Quick-Reference.md
5. Consulte: _Fluxogramas/ quando necessário
```

---

*Estrutura Visual — MaintSys Documentation*
*v1.0 — 2026-04-03*

**Próximo passo: Abra `START-HERE.md` →**
