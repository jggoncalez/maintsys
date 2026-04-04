# 🏭 MaintSys — Sistema de Manutenção Industrial 4.0

> Documentação completa estruturada e organizada

---

## 📁 Estrutura de Pastas

```
obsidian-vault/
│
├── 📖 _Documentação/          ← Especificação & Guias
│   ├── README.md              ← Comece aqui
│   ├── LEVANTAMENTO-REQUISITOS.md ← Requisitos detalhados
│   ├── METODOLOGIA-DESENVOLVIMENTO.md ← Metodologia de dev
│   ├── DIAGRAMAS.md           ← Índice de fluxogramas
│   │
│   ├── 01-Requisitos.md
│   ├── 02-Arquitetura.md
│   ├── 03-Banco-de-Dados.md
│   ├── 04-Filament-Resources.md
│   ├── 05-Dashboard.md
│   ├── 06-Permissões.md
│   ├── 07-Checklist.md
│   ├── 10-Diagrama-Classes-Mermaid.md
│   ├── 11-Diagrama-ER-Mermaid.md
│   ├── Arquitetura-Tecnica.md
│   └── (...)
│
├── 📊 _Fluxogramas/           ← Flowcharts Mermaid
│   ├── Fluxo-Autenticacao.md  ← Login process
│   ├── Fluxo-Ordem-Servico.md ← O.S. lifecycle
│   ├── Fluxo-Status-Alert.md  ← Status alerts flow
│   ├── Fluxo-Permissoes.md    ← Authorization flow
│   └── Fluxo-MQTT.md          ← IoT/ESP-32 flow
│
├── 🎨 _Canvas/                ← Diagramas Visuais
│   ├── MaintSys-Overview.canvas    ← Mapa geral
│   ├── Fluxo-Autenticacao.canvas   ← Login visual
│   ├── Fluxo-Ordem-Servico.canvas  ← O.S. visual
│   ├── Schema-Banco-Dados.canvas   ← BD schema visual
│   ├── RBAC-Permissoes.canvas      ← Permissions matrix
│   ├── Fluxo-MQTT.canvas           ← IoT flow visual
│   └── Estrutura.canvas            ← Overall structure
│
└── 🔍 _Referência/            ← Índices & Quick Reference
    ├── INDEX.md               ← Busca por tópico
    └── Quick-Reference.md     ← Code snippets
```

---

## 🚀 Começar (em ordem)

### 1️⃣ **Compreender o Projeto**
```
_Documentação/README.md                 ← Leia primeiro
↓
_Documentação/LEVANTAMENTO-REQUISITOS.md ← Entenda requisitos
↓
_Documentação/02-Arquitetura.md         ← Entenda design
```

### 1️⃣b **Entender Metodologia**
```
_Documentação/METODOLOGIA-DESENVOLVIMENTO.md ← Workflow & padrões
↓
Siga os padrões durante implementação
```

### 2️⃣ **Ver Diagramas Visuais**
```
_Canvas/MaintSys-Overview.canvas  ← Mapa geral (abra no Obsidian)
↓
_Fluxogramas/                     ← Leia flowcharts
↓
_Canvas/                          ← Veja versões visuais
```

### 3️⃣ **Implementar**
```
_Documentação/07-Checklist.md     ← Siga passo-a-passo
↓
_Referência/Quick-Reference.md    ← Use code snippets
↓
_Fluxogramas/                     ← Consulte quando necessário
```

---

## 🎯 Navegação Rápida

### Por Tópico
- **Autenticação** → `_Fluxogramas/Fluxo-Autenticacao.md` + `_Canvas/Fluxo-Autenticacao.canvas`
- **Ordens de Serviço** → `_Fluxogramas/Fluxo-Ordem-Servico.md` + `_Canvas/Fluxo-Ordem-Servico.canvas`
- **Permissões** → `_Documentação/06-Permissões.md` + `_Canvas/RBAC-Permissoes.canvas`
- **Banco de Dados** → `_Documentação/03-Banco-de-Dados.md` + `_Canvas/Schema-Banco-Dados.canvas`
- **Dashboard** → `_Documentação/05-Dashboard.md`
- **IoT/MQTT** → `_Fluxogramas/Fluxo-MQTT.md` + `_Canvas/Fluxo-MQTT.canvas`

### Por Tipo
- **Especificação** → `_Documentação/`
- **Flowcharts** → `_Fluxogramas/`
- **Diagramas Visuais** → `_Canvas/`
- **Code & Reference** → `_Referência/`

---

## 📊 Conteúdo Disponível

### 📖 16 Arquivos de Documentação
- ✅ Levantamento de Requisitos (completo)
- ✅ Metodologia de Desenvolvimento (padrões + workflows)
- ✅ Especificação de Requisitos (RF + RNF)
- ✅ Arquitetura e design patterns
- ✅ Schema SQL completo
- ✅ Filament resources completos
- ✅ Dashboard com widgets
- ✅ RBAC com 4 roles
- ✅ Checklist de implementação
- ✅ Diagrama ER

### 📊 5 Fluxogramas Mermaid
- ✅ Login & Autenticação
- ✅ Ordem de Serviço (lifecycle)
- ✅ Alertas de Status
- ✅ MQTT & IoT
- ✅ Permissões & Autorização

### 🎨 7 Canvas Visuais
- ✅ Mapa geral do projeto
- ✅ Login flowchart
- ✅ O.S. lifecycle
- ✅ Database schema
- ✅ RBAC matrix
- ✅ MQTT flow
- ✅ Overall structure

### 💻 Code Reference
- ✅ 15+ code snippets prontos
- ✅ Migrations, Models, Resources
- ✅ Policies, Widgets, Commands
- ✅ Tests, .env, validation

---

## 🎨 Como Usar

### 1. Abra Canvas no Obsidian
```
Clique em qualquer arquivo .canvas em _Canvas/
Verá diagrama visual interativo com nodes clicáveis
```

### 2. Consulte Fluxogramas
```
Abra _Fluxogramas/*.md
Veja flowcharts em Mermaid renderizados
```

### 3. Busque Rápido
```
Use _Referência/INDEX.md para buscar por tópico
Use _Referência/Quick-Reference.md para code snippets
```

---

## ✅ Status Documentação

```
✅ Requisitos Funcionais ............ 100%
✅ Arquitetura ..................... 100%
✅ Banco de Dados .................. 100%
✅ Filament Resources .............. 100%
✅ Dashboard ....................... 100%
✅ Permissões (RBAC) ............... 100%
✅ Fluxos de Negócio ............... 100%
✅ Code Snippets ................... 100%
✅ Checklist ....................... 100%
✅ Canvas Visuais .................. 100%
```

---

## 📞 Dúvidas Comuns

| Pergunta | Resposta |
|----------|----------|
| Por onde começo? | `_Documentação/README.md` |
| Quero ver diagrama visual | `_Canvas/MaintSys-Overview.canvas` |
| Como implemento? | `_Documentação/07-Checklist.md` |
| Preciso de código? | `_Referência/Quick-Reference.md` |
| Qual é o fluxo de login? | `_Fluxogramas/Fluxo-Autenticacao.md` ou `_Canvas/Fluxo-Autenticacao.canvas` |
| Como funcionam permissões? | `_Documentação/06-Permissões.md` + `_Canvas/RBAC-Permissoes.canvas` |
| Qual é o schema BD? | `_Documentação/03-Banco-de-Dados.md` + `_Canvas/Schema-Banco-Dados.canvas` |
| Quero procurar por tópico | `_Referência/INDEX.md` |

---

## 🗂️ Estrutura Resumida (ASCII Art)

```
MaintSys Documentation
│
├─ 📖 Documentação (14 arquivos)
│  ├─ README + Resumo
│  ├─ Especificação (01-08)
│  ├─ Arquitetura Técnica
│  └─ Índices (DIAGRAMAS, ESTRUTURA)
│
├─ 📊 Fluxogramas (5 Mermaid)
│  ├─ Autenticação
│  ├─ Ordem de Serviço
│  ├─ Status Alerts
│  ├─ Permissões
│  └─ MQTT/IoT
│
├─ 🎨 Canvas Interativos (7 visuais)
│  ├─ Mapa geral
│  ├─ Login
│  ├─ O.S. Lifecycle
│  ├─ DB Schema
│  ├─ RBAC
│  ├─ MQTT
│  └─ Estrutura
│
└─ 🔍 Referência (2 arquivos)
   ├─ INDEX (busca por tópico)
   └─ Quick-Reference (code)
```

---

## 🎁 Próximas Ações

1. ✅ Abra `_Documentação/README.md`
2. ✅ Explore `_Canvas/MaintSys-Overview.canvas`
3. ✅ Consulte `_Documentação/07-Checklist.md`
4. ✅ Use `_Referência/Quick-Reference.md` para código
5. ✅ Implemente seguindo os fluxos em `_Fluxogramas/`

---

*Documentação MaintSys - Organizada & Pronta para Usar*
*v1.0 — 2026-04-03*

---

**Comece em `_Documentação/README.md` →**
