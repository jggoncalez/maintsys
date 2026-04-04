# 🗺️ ÍNDICE VISUAL - Busque o Que Você Precisa

```
╔════════════════════════════════════════════════════════════════════════════╗
║                                                                            ║
║                    MAINTSYS — DOCUMENTATION INDEX                         ║
║                Sistema de Manutenção Industrial 4.0                        ║
║                                                                            ║
╚════════════════════════════════════════════════════════════════════════════╝
```

---

## 🎨 Canvas Visuais Interativos (5 arquivos)

Abra diretamente no Obsidian para ver diagramas visuais:

- **[[MaintSys-Overview.canvas]]** — Mapa geral do projeto com relações
- **[[Fluxo-Autenticacao.canvas]]** — Login flowchart visual
- **[[Fluxo-Ordem-Servico.canvas]]** — O.S. lifecycle visual
- **[[Schema-Banco-Dados.canvas]]** — Database schema com FK
- **[[RBAC-Permissoes.canvas]]** — Roles & permissions visual
- **[[Fluxo-MQTT.canvas]]** — IoT/ESP-32 flow visual

---

## 🔍 Procure por Palavra-Chave

### 🔐 Autenticação & Login
- [[_Fluxogramas/Fluxo-Autenticacao]] — Login flowchart completo
- [[_Fluxogramas/Fluxo-Permissoes]] — Middleware + authorization
- [[_Documentação/06-Permissões]] — Spatie setup
- [[Quick-Reference]] — Code snippets

### 📋 Ordens de Serviço (O.S.)
- [[_Fluxogramas/Fluxo-Ordem-Servico]] — Fluxo completo de O.S.
- [[_Documentação/04-Filament-Resources]] — ServiceOrderResource
- [[_Documentação/03-Banco-de-Dados]] — Tabela service_orders
- [[_Documentação/07-Checklist]] — Implementação passo-a-passo

### 🚨 Alertas & Notificações
- [[_Fluxogramas/Fluxo-Status-Alert]] — Flowchart de alertas
- [[_Documentação/05-Dashboard]] — CriticalAlertsWidget
- [[_Documentação/03-Banco-de-Dados]] — Tabela status_alerts
- [[Arquitetura-Tecnica]] — Notification system

### 📡 IoT & MQTT (Futuro)
- [[_Fluxogramas/Fluxo-MQTT]] — ESP-32 → MQTT → Laravel
- [[Quick-Reference]] — MQTT Listener command
- [[_Documentação/03-Banco-de-Dados]] — Tabela machine_readings
- [[Arquitetura-Tecnica]] — Integration points

### 🛡️ Permissões & Segurança
- [[_Fluxogramas/Fluxo-Permissoes]] — Authorization flowchart
- [[_Documentação/06-Permissões]] — RBAC com 4 roles
- [[_Documentação/04-Filament-Resources]] — canCreate/Edit/Delete
- [[Quick-Reference]] — Policies code

### 🗄️ Banco de Dados
- [[_Documentação/03-Banco-de-Dados]] — Schema SQL completo
- [[11-Diagrama-ER-Mermaid]] — Mermaid ER diagram
- [[Quick-Reference]] — Migrations ready

### 🎨 Interface & Dashboard
- [[_Documentação/05-Dashboard]] — Widgets design
- [[_Documentação/04-Filament-Resources]] — All Resources
- [[Quick-Reference]] — Base Resource code
- [[_Documentação/02-Arquitetura]] — UI patterns

### 🏗️ Arquitetura & Design
- [[_Documentação/02-Arquitetura]] — System design
- [[Arquitetura-Tecnica]] — Stack em camadas
- [[11-Diagrama-ER-Mermaid]] — Relações entre tabelas
- [[_Documentação/DIAGRAMAS]] — Todos os diagramas

### ✅ Implementação & Checklist
- [[_Documentação/07-Checklist]] — 7 fases com 100+ tasks
- [[Quick-Reference]] — Code ready to copy
- [[RESUMO]] — O que foi criado

---

## 📚 Procure por Role/Perfil

### 👑 **Admin**
1. Leia: [[_Documentação/01-Requisitos]]
2. Revise: [[_Documentação/02-Arquitetura]]
3. Estude: [[Arquitetura-Tecnica]]
4. Implemente: [[_Documentação/07-Checklist]]

### 📊 **Gerente**
1. Entenda: [[_Fluxogramas/Fluxo-Ordem-Servico]]
2. Saiba: [[_Fluxogramas/Fluxo-Status-Alert]]
3. Acesse: [[_Documentação/05-Dashboard]]
4. Use: [[_Documentação/04-Filament-Resources]]

### 🔧 **Técnico**
1. Aprenda: [[_Fluxogramas/Fluxo-Ordem-Servico]]
2. Consulte: [[_Documentação/04-Filament-Resources]]
3. Registre: [[_Fluxogramas/Fluxo-MQTT]] (futuro)

### 👨‍💻 **Developer**
1. Copie: [[Quick-Reference]]
2. Siga: [[_Documentação/07-Checklist]]
3. Consulte: [[Fluxo-*]] conforme necessário
4. Teste: [[_Fluxogramas/Fluxo-Permissoes]]

### 🧪 **QA/Tester**
1. Leia: [[_Documentação/DIAGRAMAS]]
2. Estude: [[_Fluxogramas/Fluxo-Permissoes]] (matrix)
3. Siga: [[_Documentação/07-Checklist]] (testes)
4. Valide: cada fluxo

---

## 🎯 Procure por Tópico

### A
- **Arquitetura** — [[Arquitetura-Tecnica]], [[_Documentação/02-Arquitetura]]
- **Autenticação** — [[_Fluxogramas/Fluxo-Autenticacao]], [[_Documentação/06-Permissões]]
- **Alertas** — [[_Fluxogramas/Fluxo-Status-Alert]], [[_Documentação/05-Dashboard]]

### B
- **Banco de dados** — [[_Documentação/03-Banco-de-Dados]], [[11-Diagrama-ER-Mermaid]]
- **Boot hooks** — [[_Documentação/02-Arquitetura]], [[Quick-Reference]]

### C
- **Checklist** — [[_Documentação/07-Checklist]]
- **Code** — [[Quick-Reference]]
- **CRUD** — [[_Documentação/04-Filament-Resources]], [[Quick-Reference]]

### D
- **Dashboard** — [[_Documentação/05-Dashboard]], [[_Documentação/04-Filament-Resources]]
- **Diagramas** — [[_Documentação/DIAGRAMAS]], [[11-Diagrama-ER-Mermaid]]

### E
- **ER Diagram** — [[11-Diagrama-ER-Mermaid]]
- **Estrutura** — [[ESTRUTURA]]

### F
- **Filament** — [[_Documentação/04-Filament-Resources]], [[_Documentação/05-Dashboard]]
- **Fluxos** — [[_Documentação/DIAGRAMAS]], [[Fluxo-*]]

### M
- **Machines** — [[_Documentação/04-Filament-Resources]], [[_Documentação/03-Banco-de-Dados]]
- **Models** — [[_Documentação/03-Banco-de-Dados]], [[Quick-Reference]]
- **MQTT** — [[_Fluxogramas/Fluxo-MQTT]], [[Quick-Reference]]

### O
- **Ordens de Serviço** — [[_Fluxogramas/Fluxo-Ordem-Servico]], [[_Documentação/04-Filament-Resources]]

### P
- **Permissões** — [[_Documentação/06-Permissões]], [[_Fluxogramas/Fluxo-Permissoes]]
- **Policies** — [[_Documentação/06-Permissões]], [[Quick-Reference]]

### Q
- **Quick Reference** — [[Quick-Reference]]

### R
- **Requisitos** — [[_Documentação/01-Requisitos]]
- **Resources** — [[_Documentação/04-Filament-Resources]]

### S
- **Segurança** — [[_Fluxogramas/Fluxo-Permissoes]], [[_Documentação/06-Permissões]]
- **Spatie** — [[_Documentação/06-Permissões]], [[Quick-Reference]]

### T
- **Testes** — [[_Documentação/07-Checklist]], [[Quick-Reference]]
- **Técnicos** — [[_Fluxogramas/Fluxo-Ordem-Servico]], [[_Documentação/04-Filament-Resources]]

### W
- **Widgets** — [[_Documentação/05-Dashboard]], [[Quick-Reference]]

---

## 📊 Procure por Tipo de Conteúdo

### 📖 Documentação Textual
- [[_Documentação/README]] — Visão geral
- [[_Documentação/01-Requisitos]] — Especificação
- [[_Documentação/02-Arquitetura]] — Design
- [[_Documentação/03-Banco-de-Dados]] — Schema SQL
- [[_Documentação/06-Permissões]] — RBAC explicado

### 📊 Diagramas & Flowcharts
- [[_Canvas/MaintSys-Overview]] — Mapa visual
- [[_Documentação/DIAGRAMAS]] — Índice de fluxos
- [[11-Diagrama-ER-Mermaid]] — ER diagram
- [[Fluxo-*]] — Flowcharts específicos
- [[Arquitetura-Tecnica]] — Architecture diagrams

### 💻 Código & Snippets
- [[Quick-Reference]] — Code ready
- [[_Documentação/04-Filament-Resources]] — Exemplos
- [[_Documentação/07-Checklist]] — Artisan commands

### ✅ Checklists & Tasks
- [[_Documentação/07-Checklist]] — Implementação
- [[RESUMO]] — O que foi feito
- [[_Fluxogramas/Fluxo-Permissoes]] — Test matrix

### 📋 Tabelas & Matrizes
- [[_Documentação/06-Permissões]] — RBAC matrix
- [[_Fluxogramas/Fluxo-Permissoes]] — Routes matrix
- [[_Documentação/03-Banco-de-Dados]] — Schema docs
- [[_Documentação/07-Checklist]] — Task lists

---

## 🚀 Procure por Fase de Projeto

### 📚 **Fase 1: Entendimento**
- [[_Documentação/README]] ← Comece aqui
- [[_Canvas/MaintSys-Overview]] ← Mapa visual
- [[_Documentação/01-Requisitos]] ← O quê
- [[_Documentação/02-Arquitetura]] ← Como

### 🗄️ **Fase 2: Banco de Dados**
- [[_Documentação/03-Banco-de-Dados]] ← Migrations
- [[11-Diagrama-ER-Mermaid]] ← Relações
- [[Quick-Reference]] ← Code
- [[_Documentação/07-Checklist]] ← Tarefas

### 🎨 **Fase 3: Filament**
- [[_Documentação/04-Filament-Resources]] ← Resources
- [[_Documentação/05-Dashboard]] ← Widgets
- [[Quick-Reference]] ← Code samples
- [[_Documentação/07-Checklist]] ← Tasks

### 🛡️ **Fase 4: Permissões**
- [[_Documentação/06-Permissões]] ← Spatie setup
- [[_Fluxogramas/Fluxo-Permissoes]] ← Flowchart
- [[Quick-Reference]] ← Policies
- [[_Documentação/07-Checklist]] ← Implementation

### 📡 **Fase 5: MQTT (Futuro)**
- [[_Fluxogramas/Fluxo-MQTT]] ← Architecture
- [[Quick-Reference]] ← Listener
- [[_Documentação/03-Banco-de-Dados]] ← Readings
- [[_Documentação/07-Checklist]] ← Setup

### 🧪 **Fase 6: Testes**
- [[_Documentação/07-Checklist]] ← Test cases
- [[_Fluxogramas/Fluxo-Permissoes]] ← Test matrix
- [[Quick-Reference]] ← Test code

---

## 🎓 Procure por Estágio de Aprendizado

### 🟢 **Iniciante: Quero entender o projeto**
```
1. [[_Documentação/README]] — Overview
2. [[_Canvas/MaintSys-Overview]] — Mapa visual
3. [[_Documentação/01-Requisitos]] — O que será feito
4. [[_Documentação/DIAGRAMAS]] — Ver fluxos
```

### 🟡 **Intermediário: Quero implementar**
```
1. [[_Documentação/07-Checklist]] — Roadmap
2. [[Quick-Reference]] — Code
3. [[_Fluxogramas/Fluxo-Ordem-Servico]] — Lógica
4. [[_Documentação/04-Filament-Resources]] — UI
```

### 🔴 **Avançado: Debugar & Otimizar**
```
1. [[Arquitetura-Tecnica]] — System design
2. [[_Fluxogramas/Fluxo-Permissoes]] — Segurança
3. [[11-Diagrama-ER-Mermaid]] — Queries
4. [[_Fluxogramas/Fluxo-MQTT]] — Integrações
```

---

## ❓ Procure por Dúvida Comum

### "Por onde começo?"
→ [[_Canvas/MaintSys-Overview]]

### "Como funciona?"
→ [[_Documentação/DIAGRAMAS]] + [[_Fluxogramas/Fluxo-Ordem-Servico]]

### "Como implemento?"
→ [[_Documentação/07-Checklist]] + [[Quick-Reference]]

### "Como faço autorização?"
→ [[_Fluxogramas/Fluxo-Permissoes]] + [[_Documentação/06-Permissões]]

### "Qual é o schema BD?"
→ [[_Documentação/03-Banco-de-Dados]] + [[11-Diagrama-ER-Mermaid]]

### "Como makeAssets Filament?"
→ [[_Documentação/04-Filament-Resources]] + [[Quick-Reference]]

### "Como configure permissões?"
→ [[_Documentação/06-Permissões]] + [[Quick-Reference]]

### "Como configure MQTT?"
→ [[_Fluxogramas/Fluxo-MQTT]] + [[Quick-Reference]]

### "Qual é a arquitetura?"
→ [[Arquitetura-Tecnica]] + [[_Documentação/02-Arquitetura]]

### "Quais são os testes?"
→ [[_Documentação/07-Checklist]] + [[Quick-Reference]]

---

## 🎁 Quick Links

| Preciso de... | Leia... |
|---------------|---------|
| Visão geral | [[_Canvas/MaintSys-Overview]] |
| Começar | [[_Documentação/README]] |
| Entender | [[_Documentação/01-Requisitos]] |
| Codar | [[Quick-Reference]] |
| Planejar | [[_Documentação/07-Checklist]] |
| Debugar | [[_Fluxogramas/Fluxo-Permissoes]] |
| Diagramas | [[_Documentação/DIAGRAMAS]] |

---

## 📞 Busca Rápida (Ctrl+F)

```
No seu Obsidian, use:
- Ctrl+F dentro de um arquivo
- Ctrl+Shift+F para buscar em todos os arquivos

Palavras-chave úteis:
- "machine"
- "service_order"
- "permission"
- "mqtt"
- "widget"
- "migration"
- "policy"
- "resource"
```

---

## 🎯 Mapa Hierárquico Completo

```
📖 README (start here)
│
├─ 🎨 CANVAS (visual map)
│  └─ 📊 DIAGRAMAS (all flowcharts)
│     ├─ Fluxo-Autenticacao
│     ├─ Fluxo-Ordem-Servico
│     ├─ Fluxo-Status-Alert
│     ├─ Fluxo-MQTT
│     └─ Fluxo-Permissoes
│
├─ 📋 01-Requisitos
├─ 🏗️ 02-Arquitetura
├─ 🗄️ 03-Banco-de-Dados → 11-Diagrama-ER-Mermaid
├─ 🎨 04-Filament-Resources
├─ 📊 05-Dashboard
├─ 🛡️ 06-Permissões
├─ ✅ 07-Checklist
│
├─ 🏢 Arquitetura-Tecnica
│
├─ 💻 Quick-Reference (code snippets)
│
├─ 📰 RESUMO (what was created)
├─ 🗂️ ESTRUTURA (documentation structure)
│
└─ 🔍 INDEX (este arquivo)
```

---

## ✨ Dicas de Navegação

### 💡 Use Breadcrumbs
Cada arquivo começa com um breadcrumb no final:
```
*[[_Documentação/README]] | [[_Documentação/02-Arquitetura]] | [[_Documentação/03-Banco-de-Dados]]*
```

### 💡 Use Graph View
No Obsidian: `Graph View` mostra todas as conexões

### 💡 Busque Rapidamente
- Ctrl+P: Palette (procure arquivo)
- Ctrl+Shift+F: global search
- Ctrl+F: search dentro do arquivo

### 💡 Use Tags
Procure por tags como: #diagram, #code, #checklist

---

*🗺️ Índice Visual — Documentação MaintSys*
*v1.0 — 2026-04-03*

---

**Não sabe por onde começar? Visite [[_Canvas/MaintSys-Overview]]! ↓**

```
    ▼ START HERE ▼

    [[_Canvas/MaintSys-Overview]]

    ▲ CLICK ABOVE ▲
```
