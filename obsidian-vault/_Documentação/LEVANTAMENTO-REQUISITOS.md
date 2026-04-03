# 📋 Levantamento de Requisitos — MaintSys

## 📌 Visão Geral

**Sistema:** MaintSys - Sistema de Manutenção Industrial 4.0
**Versão:** 1.0
**Data:** 2026-04-03
**Status:** ✅ Especificação Completa

---

## 🎯 Contexto & Justificativa

### 📊 Problema a Resolver

A manutenção de máquinas industriais é crítica para operações. Atualmente:
- ❌ Técnicos registram ordens de forma desorganizada
- ❌ Gerentes não têm visibilidade em tempo real
- ❌ Histórico de manutenção não é rastreado
- ❌ Alertas de problemas são lentos
- ❌ Dados de sensores não são centralizados

### 💡 Solução Proposta

**MaintSys:** Plataforma web centralizada para:
- ✅ Registro de ordens de serviço (preventiva/corretiva)
- ✅ Rastreamento de manutenção
- ✅ Alertas em tempo real
- ✅ Histórico completo de intervenções
- ✅ Integração com sensores IoT (futuro)

---

## 📊 Requisitos Funcionais (RF)

### RF-1: Autenticação de Usuários
**Descrição:** Usuários devem fazer login com email e senha
**Prioridade:** 🔴 Crítica
**Atores:** Todo usuário
**Fluxo:**
1. User acessa `/admin`
2. Preenche email + password
3. Sistema valida credenciais
4. Se OK: cria session + redireciona para dashboard
5. Se erro: exibe mensagem

**Validações:**
- Email válido
- Senha obrigatória
- Máximo 3 tentativas de erro

**Relacionado:** [[_Fluxogramas/Fluxo-Autenticacao]]

---

### RF-2: Níveis de Acesso (RBAC)
**Descrição:** Sistema de 4 roles com permissões diferenciadas
**Prioridade:** 🔴 Crítica
**Roles:**
- **Admin:** Acesso total a tudo
- **Gerente:** Gerencia O.S., vê alertas, cria máquinas
- **Técnico:** Executa manutenção, cria logs próprios
- **Operador:** Visualização apenas

**Permissões por Resource:**

| Resource | Admin | Gerente | Técnico | Operador |
|----------|:-----:|:-------:|:-------:|:--------:|
| Machines | CRUD | RUD | R | R |
| ServiceOrders | CRUD | CRUD | RU* | R |
| MaintenanceLogs | CRUD | RU | CR | R |
| StatusAlerts | RUD | RU | R | R |
| Users | CRUD | - | - | - |
| Roles | CRUD | - | - | - |

`*` = Apenas próprias O.S.

**Relacionado:** [[_Documentação/06-Permissões]], [[_Canvas/RBAC-Permissoes.canvas]]

---

### RF-3: Cadastro de Máquinas
**Descrição:** Gerentes e Admin podem registrar máquinas
**Prioridade:** 🔴 Crítica
**Campos:**
- Serial Number (único)
- Nome
- Modelo
- Localização
- Data de Instalação
- Status (operational, maintenance, critical, offline)
- Descrição (opcional)
- Imagem (opcional)

**Validações:**
- Serial number único
- Localização deve existir
- Data instalação ≤ hoje

**Escopos (Scopes):**
- `operational()` - apenas máquinas operacionais
- `inMaintenance()` - em manutenção
- `critical()` - estado crítico
- `offline()` - desligadas

---

### RF-4: Ordens de Serviço (O.S.)
**Descrição:** Criar, atribuir e rastrear manutenções
**Prioridade:** 🔴 Crítica

#### RF-4.1: Criar O.S.
- **Ator:** Gerente/Admin
- **Campos:**
  - Título
  - Descrição
  - Máquina (select)
  - Tipo (preventiva/corretiva)
  - Prioridade (low, medium, high, critical)
  - Técnico assignado (select de users com role tecnico)
- **Status inicial:** `open`
- **Notificação:** Email ao técnico

#### RF-4.2: Técnico Inicia O.S.
- **Ator:** Técnico assignado
- **Ação:** Botão "Iniciar O.S."
- **Efeito:**
  - Status → `in_progress`
  - `started_at` = now()
  - Notifica Gerente

#### RF-4.3: Registrar Maintenance Log
- **Ator:** Técnico
- **Campos:**
  - Ação (ex: "Troca de correia")
  - Descrição
  - Defect Type (ex: "Desgaste")
  - Service Order (opcional)
  - Timestamp
- **Efeito:**
  - Machine `last_reading_at` atualiza

#### RF-4.4: Concluir O.S.
- **Ator:** Técnico
- **Ação:** Botão "Concluir O.S."
- **Modal:** Pede `resolution_notes` (obrigatório)
- **Efeito:**
  - Status → `completed`
  - `completed_at` = now()
  - Salva notas
  - Notifica Gerente
  - O.S. sai de "Recentes"

#### RF-4.5: Estados da O.S.
```
open → in_progress → completed
       ↓
       cancelled (por Gerente)
```

**Relacionado:** [[_Fluxogramas/Fluxo-Ordem-Servico]]

---

### RF-5: Alertas de Status (StatusAlert)
**Descrição:** Notificar mudanças de status de máquinas
**Prioridade:** 🟡 Alta

**Trigger:** Mudança de status em Machine
- `operational` → `maintenance` = Alerta AMARELO
- `operational` → `critical` = Alerta VERMELHO urgente
- `maintenance` → `operational` = Alerta VERDE
- `critical` → `offline` = Alerta CINZA
- Etc...

**Ação Automática:**
1. Boot hook detecta mudança `isDirty('status')`
2. Cria registro `StatusAlert` com:
   - `previous_status`
   - `new_status`
   - `message` (ex: "Máquina X mudou de operational para critical")
   - `triggered_at` = now()
3. Enviar Filament Notification para todos os users
4. Widget `CriticalAlertsWidget` atualiza no dashboard

**Relacionado:** [[_Fluxogramas/Fluxo-Status-Alert]]

---

### RF-6: Dashboard em Tempo Real
**Descrição:** Visualização central das operações
**Prioridade:** 🔴 Crítica

**Widgets:**

#### StatsOverviewWidget
4 cards com:
- Total de Máquinas (com ícone gear)
- Operacionais (verde, check icon)
- Em Manutenção (amarelo, wrench icon)
- Críticas (vermelho, alert icon)

#### RecentServiceOrdersWidget
Tabela com últimas 5 O.S. abertas:
- Machine (link)
- Título
- Tipo (badge: preventive=info, corrective=warning)
- Prioridade (badge colorido)
- Técnico assignado
- Criada em (data)

#### CriticalAlertsWidget
Últimos alertas não lidos (`is_read = false`):
- Machine
- Message
- Status anterior → Novo
- Timestamp
- **Ação:** Toggle "Marcar como lido"

#### MaintenanceLogWidget
Últimas 5 intervenções:
- Machine
- Ação
- Defect Type
- User que registrou
- Timestamp

---

### RF-7: Leitura de Sensores (MQTT - Futuro)
**Descrição:** Receber dados de ESP-32 via MQTT
**Prioridade:** 🟢 Baixa (Phase 5)
**Status:** Design apenas

**Fluxo:**
1. ESP-32 envia payload MQTT a cada 5s
2. Laravel listener subscreve a `maintsys/machine/+/sensors`
3. Processa JSON com temperatura, vibração, RPM, pressão
4. Cria registro `MachineReading` no BD
5. Detecta anomalias (temp > 80°C, vibration > 5mm/s)
6. Se anomalia: muda status Machine para `critical`
7. Dispara evento SensorReadingReceived
8. Broadcast websocket para dashboard atualizar em <1s

**Relacionado:** [[_Fluxogramas/Fluxo-MQTT]]

---

### RF-8: Histórico de Manutenção
**Descrição:** Registry completo de todas intervenções
**Prioridade:** 🔴 Crítica

**MaintenanceLogResource:**
- Tabela com todos os logs
- Filtros: por machine, defect_type, date range
- Columns: machine, action, defect_type, user, logged_at
- Relação com ServiceOrder (se houver)
- Permite análise de reincidência

**Análises possíveis:**
- Máquinas com mais intervenções
- Tipos de defeito mais comuns
- Técnico com melhor taxa de sucesso
- Tempo médio de resolução

---

## 🏗️ Requisitos Não-Funcionais (RNF)

### RNF-1: Performance
- ✅ Dashboard carrega em < 2 segundos
- ✅ Queries com índices apropriados
- ✅ Paginação em listagens > 100 itens
- ✅ Lazy loading em relation managers

### RNF-2: Escalabilidade
- ✅ Arquitetura pronta para 1000+ máquinas
- ✅ MQTT listener rodando em background job
- ✅ Websockets para real-time (futuro)
- ✅ Cache com Redis (futuro)

### RNF-3: Segurança
- ✅ HTTPS/TLS em produção
- ✅ CSRF protection em todos forms
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS prevention (Blade templates)
- ✅ Password hashing (bcrypt)
- ✅ Rate limiting em login

### RNF-4: Usabilidade
- ✅ Interface intuitiva (Filament)
- ✅ Dark mode (theme Filament)
- ✅ Responsive design (mobile-friendly)
- ✅ Textos em português (pt_BR)
- ✅ Ícones expressivos
- ✅ Toasts com feedback

### RNF-5: Confiabilidade
- ✅ Validação em todas as entradas
- ✅ Constraints no BD
- ✅ Foreign keys com cascades
- ✅ Backups regulares
- ✅ Error logging (Sentry/Similar)

### RNF-6: Manutenibilidade
- ✅ Código bem estruturado
- ✅ Padrões SOLID
- ✅ Documentação completa
- ✅ Testes unitários (>80% coverage)
- ✅ CI/CD pipeline

### RNF-7: Disponibilidade
- ✅ Uptime > 99.5%
- ✅ Graceful degradation
- ✅ Health checks (futuro)
- ✅ Auto-recovery (futuro)

---

## 🗄️ Requisitos de Dados

### Entidades Principais

#### Machine
- Máquina industrial identificável
- Múltiplos status ao longo do tempo
- Histórico de manutenção
- Últimas leituras de sensores

#### ServiceOrder
- Ordem de trabalho para técnico
- Rastreável do início ao fim
- Pode ser preventiva ou corretiva
- Tem prioridade e status

#### MaintenanceLog
- Record de cada intervenção
- Ligada a uma O.S. (opcional)
- Classifica tipo de defeito
- Base para análises

#### MachineReading
- Dado de sensor (temperatura, vibração, etc)
- Timestamp exato
- Fonte: ESP-32 (futuro) ou manual

#### StatusAlert
- Histórico de mudanças de status
- Trigger de notificações
- Rastreável (quem, quando, o quê)

#### User + Roles (Spatie)
- 4 Roles principais
- Permissões granulares
- Relação para técnicos de O.S.

---

## 🎯 Casos de Uso Principais

### UC-1: Gerente Cria Ordem de Serviço
**Resumo:** Gerente identifica necessidade de manutenção
**Pré-condição:** Gerente autenticado, máquina existe
**Fluxo:**
1. Acessa Resources → ServiceOrders
2. Clica "New"
3. Preenche título, descrição, máquina, tipo, prioridade
4. Seleciona técnico
5. Submit
6. Sistema valida
7. Cria O.S. com status "open"
8. Envia email ao técnico
9. Sucesso 💚

---

### UC-2: Técnico Executa Manutenção
**Resumo:** Técnico recebe O.S. e registra trabalho
**Pré-condição:** O.S. aberta, técnico assignado
**Fluxo:**
1. Técnico vê em dashboard/lista
2. Clica "Iniciar O.S." → status muda para "in_progress"
3. Trabalha na máquina (fora do sistema)
4. Retorna ao Filament
5. Clica "Concluir O.S."
6. Modal pede "resolution_notes"
7. Tipo notas completamente
8. Submit
9. O.S. → "completed"
10. Gerente notificado 💚

---

### UC-3: Monitor Status de Máquinas
**Resumo:** Gerente acompanha saúde dos equipamentos
**Pré-condição:** Gerente no dashboard
**Fluxo:**
1. Abre MaintSys
2. Dashboard mostra 4 métricas
3. Vê últimas O.S. abertas
4. Vê alertas críticos em destaque
5. Clica em máquina crítica
6. Toma ação (marca manual, cria O.S., etc)

---

### UC-4: Detecção Automática de Anomalia (Futuro)
**Resumo:** Sensor envia dados, sistema detecta problema
**Pré-condição:** ESP-32 transmitindo, listener rodando
**Fluxo:**
1. ESP-32 lê temperatura = 85°C
2. Envia via MQTT
3. Laravel listener recebe
4. Detecta > 80°C = anomalia
5. Cria MachineReading
6. Muda Machine status → "critical"
7. Boot hook cria StatusAlert
8. Notificação enviada
9. Dashboard atualiza em tempo real 💚

---

## 📦 Stack Tecnológico

### Backend
- **Framework:** Laravel 11
- **ORM:** Eloquent
- **Auth:** Laravel Auth + Spatie Permission
- **Admin:** Filament v3
- **Real-time:** Livewire + Alpine.js (presente), Laravel Echo (futuro)

### Frontend
- **Admin UI:** Filament v3
- **Forms:** Filament Forms
- **Tables:** Filament Tables
- **Icons:** Heroicons
- **CSS:** Tailwind CSS
- **Interactivity:** JavaScript (Alpine.js, Livewire)

### Database
- **DBMS:** MySQL 8.0+
- **Migrations:** Laravel Migrations
- **Seeders:** Database Seeders
- **Relationship:** Eloquent

### Services
- **Broadcasting:** Laravel Echo (futuro)
- **Queue:** Redis/Database (futuro)
- **Cache:** Redis (futuro)
- **MQTT:** Mosquitto (futuro)
- **Email:** SMTP

### DevOps
- **Container:** Docker
- **Compose:** Docker Compose
- **VCS:** Git
- **Deployment:** Manual/CI-CD (futuro)

---

## 🎨 Constraints & Restrições

### Constraints Técnicas
1. **Database**
   - Foreign keys obrigatórias
   - Índices em FK e enums
   - Constraints de integridade

2. **Autenticação**
   - Email único
   - Password mínimo 8 caracteres
   - Máximo 3 tentativas de login

3. **Business Logic**
   - O.S. não pode ser deletada, apenas cancelada
   - Machine não pode ter status "deleted"
   - Técnico so pode editar próprias O.S.

4. **Performance**
   - Queries com Where + Order By + Limit
   - Relações loaded com EAGER loading
   - max 1000 rows por página

### Constraints de Negócio
1. Apenas Admin pode gerenciar Users e Roles
2. Apenas Gerente+ pode criar Máquinas
3. Técnico vê apenas suas O.S.
4. Operador é read-only
5. Logs não podem ser deletados (auditoria)

---

## 🧪 Critérios de Aceitação

### CA-1: Login Funciona
- [ ] User preenche email/password
- [ ] Dashboard carrega
- [ ] Sessão persiste após F5
- [ ] Logout funciona

### CA-2: O.S. Completa
- [ ] Gerente cria O.S.
- [ ] Técnico vê em seu dashboard
- [ ] Técnico inicia (status muda)
- [ ] Técnico conclui (com notas)
- [ ] Gerente recebe notificação
- [ ] Widget atualiza

### CA-3: Alertas Funcionam
- [ ] Gerente muda status Machine
- [ ] StatusAlert criada automaticamente
- [ ] Notificação enviada
- [ ] Alert aparece no widget
- [ ] User marca como "lido"
- [ ] Sai da lista

### CA-4: Permissões Aplicadas
- [ ] Admin vê tudo
- [ ] Gerente não vê Users
- [ ] Técnico não pode criar Machines
- [ ] Operador não pode editar nada
- [ ] Botões desabilitados conforme role

### CA-5: Dashboard Real-time
- [ ] Cards atualizam estatísticas
- [ ] Widgets mostram dados corretos
- [ ] Alertas críticos em destaque
- [ ] Carrega em < 2 segundos

---

## 📈 Métricas de Sucesso

| Métrica | Meta |
|---------|------|
| Uptime | > 99.5% |
| Load Dashboard | < 2s |
| Latência de Alert | < 1s |
| Taxa de Tests | > 80% coverage |
| Bugs em Produção | < 5 por mês |
| User Satisfaction | > 4/5 |

---

## 🔄 Roadmap

### ✅ Phase 1: MVP (Now)
- ✅ Autenticação & RBAC
- ✅ Máquinas CRUD
- ✅ Ordens de Serviço CRUD
- ✅ Maintenance Logs
- ✅ Status Alerts
- ✅ Dashboard básico
- ✅ Documentação completa

### 🟡 Phase 2: Otimização
- [ ] Performance tuning
- [ ] Caching strategy
- [ ] Database optimization
- [ ] CI/CD pipeline

### 🟢 Phase 3: Real-time
- [ ] Websockets
- [ ] Live notifications
- [ ] Real-time charts
- [ ] Browser push

### 🔵 Phase 4: IoT Integration
- [ ] MQTT Broker setup
- [ ] ESP-32 listener
- [ ] Sensor readings
- [ ] Anomaly detection

### 🟣 Phase 5: Advanced
- [ ] ML predictions
- [ ] Analytics dashboards
- [ ] Mobile app
- [ ] Multi-tenant

---

## 📞 Referências

- [[_Documentação/README]] — Overview
- [[_Documentação/01-Requisitos]] — Este arquivo
- [[_Documentação/02-Arquitetura]] — Arquitetura
- [[_Documentação/07-Checklist]] — Implementação
- [[_Fluxogramas/]] — Todos os fluxos

---

*Levantamento de Requisitos — MaintSys v1.0*
*Completo e Pronto para Implementação*
*2026-04-03*
