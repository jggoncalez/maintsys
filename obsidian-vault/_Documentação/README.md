# 🏭 MaintSys - Sistema de Manutenção Industrial 4.0

> Sistema de gestão de manutenção de máquinas industriais com **Laravel 11 + Filament v3**, desenvolvido como plataforma central para técnicos registrarem ordens de serviço e gerentes acompanharem a saúde dos equipamentos em tempo real.

---

## 🎨 Comece Aqui

### 🗺️ **Mapa Visual & Canvas**
- [[CANVAS]] — Mapa visual interativo de todo o projeto
- [[_Documentação/DIAGRAMAS]] — Índice de todos os flowcharts e diagramas

### 📚 **Documentação Completa**
- [[_Documentação/ESTRUTURA]] — Estrutura de documentação e navegação

---

## 📋 Documentação Detalhada

### 1️⃣ **Especificação & Arquitetura**
| Documento | Conteúdo |
|-----------|----------|
| [[_Documentação/01-Requisitos]] | RF, RNF, contexto do projeto |
| [[_Documentação/02-Arquitetura]] | Stack, fluxos, RBAC pattern |
| [[_Documentação/Arquitetura-Tecnica]] | Camadas, componentes, integrações |

### 2️⃣ **Banco de Dados**
| Documento | Conteúdo |
|-----------|----------|
| [[_Documentação/03-Banco-de-Dados]] | Schema SQL, migrations, índices |
| [[_Documentação/08-Diagrama-ER]] | Mermaid ER, relacionamentos, queries |

### 3️⃣ **Filament & UI**
| Documento | Conteúdo |
|-----------|----------|
| [[_Documentação/04-Filament-Resources]] | Resources, forms, relation managers |
| [[_Documentação/05-Dashboard]] | Widgets, stats, real-time updates |

### 4️⃣ **Segurança & Acesso**
| Documento | Conteúdo |
|-----------|----------|
| [[_Documentação/06-Permissões]] | Spatie RBAC, 4 roles, policies |
| [[Fluxo-Permissoes]] | Flowchart autorização completo |

### 5️⃣ **Fluxos de Negócio**
| Flowchart | Descrição |
|-----------|-----------|
| [[Fluxo-Autenticacao]] | Login → Session → Dashboard |
| [[Fluxo-Ordem-Servico]] | Criar → Iniciar → Log → Concluir |
| [[Fluxo-Status-Alert]] | Mudança status → Alert → Dashboard |
| [[Fluxo-MQTT]] | ESP-32 → MQTT → Laravel → Real-time |

### 6️⃣ **Execução**
| Documento | Conteúdo |
|-----------|----------|
| [[_Documentação/07-Checklist]] | 7 fases, 100+ tarefas, testes |

---

## 🎯 Guia Rápido de Acesso

### 👤 Você é **Arquiteto**?
```
Leia: [[_Documentação/02-Arquitetura]] → [[_Documentação/Arquitetura-Tecnica]] → [[_Documentação/08-Diagrama-ER]]
```

### 👨‍💻 Você é **Desenvolvedor**?
```
Comece: [[_Documentação/07-Checklist]] → [[_Documentação/03-Banco-de-Dados]] → [[_Documentação/04-Filament-Resources]]
Consulte: [[Fluxo-Ordem-Servico]] (lógica) + [[Fluxo-Permissoes]] (segurança)
```

### 🧪 Você é **QA/Tester**?
```
Estude: [[_Documentação/DIAGRAMAS]] → [[Fluxo-Permissoes]] (matrix) → [[_Documentação/07-Checklist]] (testes)
```

### 🤔 Você é **PM/Product**?
```
Entenda: [[_Documentação/01-Requisitos]] → [[CANVAS]] → [[_Documentação/DIAGRAMAS]]
```

---

## 🎨 Diagramas Disponíveis

### Tipos de Diagrama

```
✅ Flowcharts (18+)      — Processos e fluxos de negócio
✅ Diagrama ER           — Banco de dados relacional
✅ State Machines (2+)   — Transições de estado
✅ Sequence Diagrams     — Interações entre componentes
✅ Architecture Diagrams — Stack em camadas
✅ Matrices              — RBAC, permissões, checklist
```

### Tópicos Cobertos

```
🔐 Autenticação & Autorização
📋 Ordem de Serviço (O.S.)
🚨 Alertas de Status
📡 MQTT & IoT (ESP-32)
🛡️ Permissões Spatie
🏗️ Arquitetura Técnica
💾 Banco de Dados
🎨 Dashboard & Widgets
```

---

## 📊 Status da Documentação

| Seção | Status | % |
|-------|--------|---|
| Requisitos | ✅ Completo | 100% |
| Arquitetura | ✅ Completo | 100% |
| Banco de Dados | ✅ Completo | 100% |
| Resources Filament | ✅ Completo | 100% |
| Dashboard | ✅ Completo | 100% |
| Permissões & RBAC | ✅ Completo | 100% |
| Fluxos Visuais | ✅ Completo | 100% |
| Checklist | ✅ Completo | 100% |

---

## 📦 Arquivos Criados

```
vault/
├── README.md ........................... Este arquivo
├── CANVAS.md ........................... 🎨 Mapa visual interativo
├── DIAGRAMAS.md ........................ 📊 Índice de flowcharts
├── ESTRUTURA.md ........................ 🗂️ Estrutura de docs
│
├── 01-Requisitos.md .................... 📋 Contexto & objetivos
├── 02-Arquitetura.md ................... 🏗️ Design & padrões
├── 03-Banco-de-Dados.md ................ 🗄️ Schema SQL complete
├── 04-Filament-Resources.md ............ 🎨 CRUD resources
├── 05-Dashboard.md ..................... 📊 Widgets & visualizações
├── 06-Permissões.md .................... 🛡️ Spatie RBAC
├── 07-Checklist.md ..................... ✅ Implementação step-by-step
├── 08-Diagrama-ER.md ................... 📈 Mermaid ER diagram
│
├── Arquitetura-Tecnica.md .............. 🏢 Stack & layers
├── Fluxo-Autenticacao.md ............... 🔐 Login & session
├── Fluxo-Ordem-Servico.md .............. 📋 O.S. completo
├── Fluxo-Status-Alert.md ............... 🚨 Alertas & notificações
├── Fluxo-MQTT.md ....................... 📡 IoT & ESP-32
└── Fluxo-Permissoes.md ................. 🛡️ Authorization flow
```

**Total:** 16 arquivos | 3000+ linhas | 30+ diagramas Mermaid

---

## 🚀 Implementação Recomendada

### Ordem de Execução

```
1. Estude: [[_Documentação/01-Requisitos]] + [[_Documentação/02-Arquitetura]]
2. Prepare: [[_Documentação/03-Banco-de-Dados]] (criar migrations)
3. Modele: [[_Documentação/08-Diagrama-ER]] (validar schema)
4. Buildar: [[_Documentação/04-Filament-Resources]] (CRUD)
5. Proteja: [[_Documentação/06-Permissões]] (RBAC)
6. Visualize: [[_Documentação/05-Dashboard]] (widgets)
7. Teste: [[_Documentação/07-Checklist]] (7 fases)
8. Implante: [[_Documentação/Arquitetura-Tecnica]] (deployment)
```

### Follow the Flowcharts

```
✅ Login: [[Fluxo-Autenticacao]]
✅ O.S.: [[Fluxo-Ordem-Servico]]
✅ Alertas: [[Fluxo-Status-Alert]]
✅ Perms: [[Fluxo-Permissoes]]
🔜 MQTT: [[Fluxo-MQTT]] (futuro)
```

---

## 👥 Usuários Padrão (Seed)

```
Admin:    admin@maintsys.com     (role: admin)
Gerente:  gerente@maintsys.com   (role: gerente)
Tecnico:  tecnico@maintsys.com   (role: tecnico)
Operador: operador@maintsys.com  (role: operador)

Senha (todos): password
```

---

## 🛠️ Stack Tecnológico

```
Backend:     Laravel 11    | PHP 8.3+
Frontend:    Filament v3   | Livewire
Database:    MySQL 8.0+    | Eloquent ORM
Auth:        Spatie Permission
Real-time:   Laravel Echo  | Websockets (futuro)
IoT:         MQTT Broker   | ESP-32 (futuro)
Deploy:      Docker        | Kubernetes (prod)
```

---

## 📞 Links & Referências

### Internos (Obsidian)
- [[CANVAS]] — Mapa visual
- [[_Documentação/DIAGRAMAS]] — Todos os flowcharts
- [[Fluxo-Ordem-Servico]] — Lógica central
- [[Fluxo-Permissoes]] — Segurança

### Externos
- [Filament Docs](https://filamentphp.com)
- [Laravel 11](https://laravel.com)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Eloquent ORM](https://laravel.com/docs/eloquent)

---

## ✨ Destaques desta Documentação

- ✅ **Cobertura 100%** — Todos os requisitos documentados
- ✅ **30+ Diagramas** — Mermaid flowcharts interativos
- ✅ **Relações cruzadas** — Links internos para navegação fluida
- ✅ **Pronto para código** — Copy-paste ready specifications
- ✅ **Checklists** — 100+ tarefas organizadas
- ✅ **Exemplos práticos** — Queries, código, configurações
- ✅ **Canvas visual** — Guia mapa do projeto
- ✅ **Mobile-friendly** — Funciona em desktop e mobile

---

## 🎓 Como Usar Esta Documentação

### 📖 Leitura Linear
Para entender o projeto do zero:
```
README → CANVAS → 01-Requisitos → 02-Arquitetura → ...
```

### 🔍 Consulta Rápida
Para referência em uma tarefa específica:
```
[[_Documentação/DIAGRAMAS]] → Encontre o fluxo → Clique no link
```

### 🏗️ Desenvolvimento
Enquanto codifica:
```
[[_Documentação/07-Checklist]] → Marque tarefas conforme progride
Consulte fluxos conforme necessário (perms, MQTT, etc)
```

---

## 📈 Progresso

```
✅ Requisitos         — 100% documentado
✅ Arquitetura        — 100% documentado
✅ Banco de dados     — 100% documentado
✅ Filament           — 100% documentado
✅ Permissões         — 100% documentado
✅ Fluxos             — 100% documentado
⏳ Implementação      — Em progresso
⏳ Testes              — Pendente
⏳ Deploy             — Pendente
```

---

## 💡 Tips

- Use **CANVAS.md** para visão geral
- Use **DIAGRAMAS.md** para explorar flowcharts
- Consulte **[[_Documentação/07-Checklist]]** enquanto implementa
- Revise **Fluxos** antes de cada feature

---

*Documentação Completa do MaintSys*
*Versão: 1.0*
*Criado: 2026-04-03*
*Atualizado: 2026-04-03*

---

**Pronto para começar? Visite [[CANVAS]] →**
