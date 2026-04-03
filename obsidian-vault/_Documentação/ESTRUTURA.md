# ESTRUTURA DE DOCUMENTAÇÃO — MaintSys

## 📚 Organização Geral

```
MaintSys Documentation
│
├── 📄 README.md                    ← Índice principal
│
├── 📋 01-Requisitos.md             ← Objetivos e contexto
│   └── RF: Gestão de máquinas, O.S., histórico, alertas, controle de acesso
│
├── 🏗️ 02-Arquitetura.md            ← Estrutura geral e fluxos
│   ├── Stack: Laravel 11 + Filament v3
│   ├── Fluxos: O.S., Alertas
│   └── Segurança: RBAC com Spatie
│
├── 🗄️ 03-Banco-de-Dados.md         ← Schema SQL e Eloquent
│   ├── 6 Tabelas principais
│   ├── Relacionamentos completos
│   └── Índices e performance
│
├── 📊 04-Filament-Resources.md      ← UI e Formulários
│   ├── MachineResource
│   ├── ServiceOrderResource
│   ├── MaintenanceLogResource
│   ├── UserResource
│   ├── StatusAlertResource
│   └── Relation Managers
│
├── 📱 05-Dashboard.md              ← Widgets e visualizações
│   ├── StatsOverviewWidget (4 cards)
│   ├── RecentServiceOrdersWidget
│   ├── CriticalAlertsWidget
│   └── MaintenanceLogWidget
│
├── 🔐 06-Permissões.md             ← Spatie RBAC
│   ├── 4 Roles: admin, gerente, tecnico, operador
│   ├── Policies (autorização)
│   └── Matrix de permissões
│
├── ✅ 07-Checklist.md              ← Progresso de implementação
│   ├── Fase 1: Banco de Dados
│   ├── Fase 2: Models
│   ├── Fase 3: Resources
│   ├── Fase 4: Permissões
│   ├── Fase 5: Dashboard
│   ├── Fase 6: Seeds
│   ├── Fase 7: Configuração
│   └── Testes Funcionais
│
└── 📈 08-Diagrama-ER.md            ← Modelo de dados visual
    ├── Mermaid ER Diagram
    ├── Relacionamentos
    └── Queries comuns
```

---

## 🔀 Fluxo de Leitura Recomendado

### Para Arquitetar o Projeto
1. **README.md** — Entender o contexto geral
2. **01-Requisitos.md** — Ler o que o sistema deve fazer
3. **02-Arquitetura.md** — Ver como será estruturado
4. **03-Banco-de-Dados.md** — Entender o schema

### Para Implementar
5. **07-Checklist.md** — Seguir passo a passo
6. **03-Banco-de-Dados.md** — Criar migrations
7. **04-Filament-Resources.md** — Buildar UI
8. **06-Permissões.md** — Proteger com RBAC
9. **05-Dashboard.md** — Completar visualizações

### Para Debugar
- **08-Diagrama-ER.md** — Entender relacionamentos
- **06-Permissões.md** — Verificar autorização
- **04-Filament-Resources.md** — Revisar validações

---

## 🎯 Checklist de Documentação

### Cobertura
- [x] Requisitos funcionais e não-funcionais
- [x] Stack tecnológico
- [x] Arquitetura de camadas
- [x] Schema completo com tipos e constraints
- [x] Relacionamentos Eloquent
- [x] UI/UX com Filament
- [x] Permissões e autorização
- [x] Widgets e dashboard
- [x] Fluxos de negócio
- [x] Checklist de implementação
- [x] Diagrama ER com Mermaid
- [x] Queries de exemplo

### Manutenção
- [ ] Atualizar após cada release
- [ ] Adicionar novas features ao 01-Requisitos
- [ ] Documentar mudanças de banco em 03-Banco-de-Dados
- [ ] Registrar seeds e testes em 07-Checklist

---

## 📞 Referências Rápidas

### Endpoints Filament
- Dashboard: `/admin`
- Machines: `/admin/machines`
- Service Orders: `/admin/service-orders`
- Maintenance Logs: `/admin/maintenance-logs`
- Users: `/admin/users`
- Status Alerts: `/admin/status-alerts`

### Usuários Seed
- **Admin**: admin@maintsys.com / password
- **Gerente**: gerente@maintsys.com / password
- **Técnico**: tecnico@maintsys.com / password

### Roles (Spatie)
- `admin` — acesso total
- `gerente` — gestão operacional
- `tecnico` — execução de manutenção
- `operador` — visualização

---

## 🔄 Diagrama de Navegação

```
┌─────────────────────────────────────────────┐
│          LOGIN (Filament Auth)              │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│              DASHBOARD                      │
│  (4 cards + 4 widgets com dados principais) │
└─────────────────────────────────────────────┘
         ↙      ↓      ↓      ↘
      Equips  O.S. Logs Alertas Admin
        │      │     │      │      │
    Machines  Service Maint  Status Users
           Orders    Logs    Alerts
```

---

*Documentação completa do MaintSys — v1.0 (2026-04-03)*
