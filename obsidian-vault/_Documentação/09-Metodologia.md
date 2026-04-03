# 🔧 Metodologia de Desenvolvimento — MaintSys

## 📌 Visão Geral

**Documento:** Metodologia de Desenvolvimento
**Projeto:** MaintSys - Sistema de Manutenção Industrial 4.0
**Versão:** 1.0
**Data:** 2026-04-03
**Versão Laravel:** 11
**Versão Filament:** v3

---

## 🎯 Princípios Fundamentais

### 1️⃣ **MVP First (Minimum Viable Product)**
- Implementar fase 1 primeiro
- Depois otimizar, não antes
- Features simples e funcionais

### 2️⃣ **Qualidade sobre Quantidade**
- 80% coverage em testes
- Code review antes de merge
- Documentação inline

### 3️⃣ **Usuário Centrado**
- Dashboard intuitivo
- Mensagens claras
- Design responsivo

### 4️⃣ **Automação Onde Possível**
- Boot hooks para lógica automática
- Migrations para schema
- Seeders para dados

### 5️⃣ **Segurança por Design**
- RBAC desde o início
- Validação em tudo
- Never trust user input

---
## 🚀 Metodologia Agile

A metodologia escolhida para o desenvolvimento do MaintSys foi o **Scrum**, um framework ágil baseado em ciclos curtos e iterativos de entrega chamados de **Sprints**. Cada Sprint representa um período fixo de trabalho ao final do qual um incremento funcional do produto deve ser entregue, permitindo validações contínuas e o aprimoramento progressivo do sistema.

O Scrum organiza o trabalho em torno de três pilares principais: transparência, inspeção e adaptação. As entregas são planejadas e priorizadas em um **Product Backlog**, e ao início de cada Sprint é definido o que será desenvolvido naquele ciclo por meio do **Sprint Planning**. Ao final, realiza-se uma revisão dos resultados e uma retrospectiva para identificar melhorias no processo.
## 📋 Ciclo de Desenvolvimento

### Sprint (1 semana)

```
MON: Planning + Setup
TUE-THU: Development
FRI: Review + Tests + Deploy
```

### Fases do Projeto

```
Phase 1: MVP (2-3 semanas)
├── Database Schema
├── Models & Relationships
├── Resources (CRUD)
├── Dashboard
├── RBAC
└── Tests

Phase 2: Otimização (1 semana)
├── Performance tuning
├── Caching
├── Indexando
└── Monitoring

Phase 3: Real-time (1-2 semanas)
├── Websockets
├── Live updates
├── Real-time charts

Phase 4: IoT (2-3 semanas)
├── MQTT setup
├── ESP-32 listener
├── Anomaly detection

Phase 5: Advanced (Future)
├── ML predictions
├── Mobile app
├── Multi-tenant
```

---

## 🛠️ Stack & Padrões

### Architecture Pattern
```
Presentation Layer (Filament)
    ↓
Application Layer (Controllers/Actions)
    ↓
Domain Layer (Models/Services)
    ↓
Infrastructure Layer (Database/Cache)
```

### Padrões de Código

#### 1. Models (Domain Layer)
```php
✅ Eloquent Models com:
  - Relationships completas
  - Boot hooks para lógica
  - Scopes para queries
  - Accessors/Mutators
  - Type casts
  - Validação (no controller)
```

**Exemplo:**
```php
class Machine extends Model {
    protected $fillable = [...];
    protected $casts = [...];

    // Relationships
    public function serviceOrders() { ... }

    // Scopes
    public function scopeOperational($query) { ... }

    // Boot (Auto-triggers)
    protected static function boot() { ... }
}
```

#### 2. Resources (Presentation Layer)
```php
✅ Filament Resources com:
  - Forma clara
  - Tabelas estruturadas
  - Filtros relevantes
  - Ações úteis
  - RelationManagers
  - Autorizações (canCreate/Edit/Delete)
```

#### 3. Policies (Authorization)
```php
✅ Laravel Policies para:
  - Criar/ler/editar/deletar
  - Lógica complexa
  - Checks de negócio
```

#### 4. Controllers
```php
✅ Controllers simples:
  - Validam dados
  - Delegam lógica models
  - Retornam response
  - Sem "god methods"
```

#### 5. Events & Listeners
```php
✅ Para ações assíncronas:
  - Status muda → cria alert
  - O.S. completa → notifica
  - Sensor lê → salva reading
```

---

## 📦 Workflow de Features

### Passo 1: Planning
```
1. Escolha feature (ex: Criar O.S.)
2. Identifique Models necessários
3. Identifique Controllers/Actions
4. Identifique testes
```

### Passo 2: Database (TDD)
```
1. Crie migration
2. Run: php artisan migrate
3. Validate schema
```

### Passo 3: Model (TDD)
```
1. Crie Model
2. Adicione relationships
3. Adicione scopes/methods
4. Escreva testes
```

### Passo 4: Validation
```
1. Crie FormRequest ou Rules
2. Valide inputs
3. Teste validações
```

### Passo 5: Logic (TDD)
```
1. Crie Controller/Action
2. Implemente lógica
3. Escreva testes
4. Teste manualmente
```

### Passo 6: UI (Resource)
```
1. Crie Filament Resource
2. Configure Form
3. Configure Table
4. Configure Filters
5. Configure Actions
```

### Passo 7: Autorização
```
1. Crie Policy
2. Adicione aos Resources (canCreate/Edit/Delete)
3. Teste acesso
```

### Passo 8: Testes
```
1. Unit tests (Models)
2. Feature tests (Resources)
3. Teste de integração
```

### Passo 9: Review
```
1. Code review
2. QA testing
3. Fixes
4. Merge
```

---

## 🧪 Estratégia de Testes

### Níveis de Teste

#### Unit Tests
```php
// Testa Models, Methods, Scopes
class MachineTest extends TestCase {
    public function test_machine_can_be_operational() {
        $machine = Machine::factory()->create(['status' => 'operational']);
        $this->assertTrue($machine->isOperational());
    }
}
```

#### Feature Tests
```php
// Testa Resources, Controllers, Autorizações
class ServiceOrderTest extends TestCase {
    public function test_gerente_can_create_service_order() {
        $admin = User::factory()->admin()->create();
        // ...test...
    }
}
```

#### Browser Tests (Futuro - Dusk)
```php
// Testa UI/Interações
$this->browse(function (Browser $browser) {
    $browser->visit('/admin/machines')
            ->clickLink('Create')
            // ...assertions...
});
```

### Coverage Goal
```
✅ Models:        95%+
✅ Controllers:   85%+
✅ Policies:      90%+
✅ Overall:       80%+
```

### Commands para Testes
```bash
# Rodar todos
php artisan test

# Rodar com coverage
php artisan test --coverage

# Rodar um arquivo
php artisan test tests/Feature/MachineTest.php

# Rodar um método
php artisan test --filter=test_machine_can_be_created
```

---

## 🚀 Jenkins CI/CD (Futuro)

### Pipeline

```yaml
Stage 1: Build
├─ Composer install
├─ npm install
└─ php artisan config:cache

Stage 2: Test
├─ php artisan test
├─ php artisan dusk (futuro)
└─ Code style (pint)

Stage 3: Quality
├─ Static analysis (phpstan)
├─ Security scan
└─ Coverage report

Stage 4: Deploy
├─ Database migrate
├─ Cache clear
├─ Assets build
└─ Health check
```

---

## 📝 Git Workflow

### Branch Strategy

```
main (production)
 ↑
develop (staging)
 ↑
feature/* (features)
 ↑
bugfix/* (bug fixes)
```

### Commit Convention

```
<type>: <title> — max 70 chars

<body> — detailed explanation

<footer> — Closes #123, Related #456
```

**Types:**
- `feat:` — Nova feature
- `fix:` — Bug fix
- `refactor:` — Sem mudança funcional
- `test:` — Testes
- `docs:` — Documentação
- `chore:` — Build, deps, config

**Exemplo:**
```
feat: Implement service order status transitions

- Add start() and complete() methods to ServiceOrder
- Create StateAlianzaart machine for O.S. lifecycle
- Add tests for state transitions
- Update dashboard widget

Closes #45
Related #12
```

### PR (Pull Request)

```markdown
## Description
Breve resumo do que foi feito

## Changes
- [ ] Change 1
- [ ] Change 2

## Testing
Como testar:
1. Step 1
2. Step 2

## Coverage
Lines changed: 150
Coverage impact: +2%

## Checklist
- [x] Tested locally
- [x] Tests passing
- [x] Documentation updated
- [x] Code follows style guide
```

---

## 🔒 Segurança

### OWASP Top 10 Prevention

#### 1️⃣ SQL Injection
```php
❌ Bad: DB::select("SELECT * FROM users WHERE id = $id")
✅ Good: User::where('id', $id)->first()
```

#### 2️⃣ Authentication Bypass
```php
✅ Use Laravel Auth + Spatie
✅ Hash passwords with bcrypt
✅ Rate limit login attempts
```

#### 3️⃣ Sensitive Data Exposure
```php
✅ HTTPS/TLS em produção
✅ Never log passwords
✅ Hash IDs with Hashids
```

#### 4️⃣ XML External Entities (XXE)
```php
✅ Disable XML entity expansion
✅ Validate XML input
```

#### 5️⃣ Broken Access Control
```php
✅ Valide em TODOS os endpoints
✅ Use Policies e Gates
✅ Never trust frontend checks
```

#### 6️⃣ Security Misconfiguration
```php
✅ .env file com valores seguros
✅ Disable debug em prod
✅ Set secure headers
```

#### 7️⃣ Cross-Site Scripting (XSS)
```php
❌ Bad: {{ $user->name }}
✅ Good: {{ $user->name ?? 'Unknown' }}
✅ Blade templates escapam por default
```

#### 8️⃣ Insecure Deserialization
```php
✅ Never unserialize untrusted data
✅ Use JSON instead
```

#### 9️⃣ Using Components with Known Vulnerabilities
```bash
✅ composer audit (check for vulnerabilities)
✅ Keep packages updated
```

#### 🔟 Insufficient Logging & Monitoring
```php
✅ Log all auth failures
✅ Log permission denials
✅ Monitor failed queries
```

---

## 📊 Performance Optimization

### Database
```php
✅ Eager loading (with/load)
✅ Query builder with indices
✅ Avoid N+1 queries
✅ Limit returned fields (select)
```

### Caching (Redis)
```php
✅ Cache queries frequently used
✅ Cache configuration
✅ Cache view components
```

### Frontend
```php
✅ Minify CSS/JS
✅ Compress images
✅ Lazy load components
✅ Use CDN for static
```

### Monitoring
```bash
✅ Application Performance Monitoring (APM)
✅ Database slow query log
✅ Error tracking (Sentry)
✅ Uptime monitoring
```

---

## 📚 Padrões Eloquent

### Relationships (Use adequadamente)

```php
// 1-to-Many
class Machine extends Model {
    public function alerts() {
        return $this->hasMany(StatusAlert::class);
    }
}

// Many-to-One
class StatusAlert extends Model {
    public function machine() {
        return $this->belongsTo(Machine::class);
    }
}

// Through
class Machine extends Model {
    public function logs() {
        return $this->hasManyThrough(MaintenanceLog::class, ServiceOrder::class);
    }
}
```

### Eager Loading

```php
❌ Bad (N+1):
foreach($machines as $machine) {
    echo $machine->alerts->count(); // 1 + N queries
}

✅ Good:
$machines = Machine::with('alerts')->get(); // 2 queries
foreach($machines as $machine) {
    echo $machine->alerts->count(); // Zero queries
}
```

### Scopes

```php
class Machine extends Model {
    public function scopeOperational($query) {
        return $query->where('status', 'operational');
    }

    public function scopeInRegion($query, $region) {
        return $query->where('location', 'like', "%$region%");
    }
}

// Usage
$machines = Machine::operational()->inRegion('Galpão A')->get();
```

## 🎨 Filament Conventions

### Resource Structure

```php
class MachineResource extends Resource {
    // Define form
    public static function form(Form $form): Form {}

    // Define table
    public static function table(Table $table): Table {}

    // Define relations
    public static function getRelations(): array {}

    // Define authorization
    public static function canCreate(): bool {}
    public static function canEdit(Model $record): bool {}
    public static function canDelete(Model $record): bool {}
}
```

### Form Components

```php
TextInput::make('serial_number')->required()->unique(ignoreRecord: true),
Select::make('status')->options(['operational' => 'Operacional', ...])->required(),
Textarea::make('description')->nullable(),
DatePicker::make('installed_at')->required(),
FileUpload::make('image')->disk('public')->directory('machines'),
```

### Table Columns

```php
TextColumn::make('serial_number')->searchable()->sortable(),
TextColumn::make('name'),
BadgeColumn::make('status')->colors(['operational' => 'success', ...]),
TextColumn::make('installed_at')->date(),
TextColumn::make('last_reading_at')->dateTime(),
```

---

## 📱 Convenções de Nomes

### Database
```
tables:           plural_snake_case (machines, service_orders)
columns:          singular_snake_case (id, serial_number, status)
foreign_keys:     singular_id (machine_id, user_id)
pivot_tables:     alphabetical (role_user)
```

### Models
```
class:            PascalCase (Machine, ServiceOrder)
file:             singular PascalCase (Machine.php)
table:            plural_snake_case (machines)
primary key:      id
foreign key:      {model}_id
accessor:         camelCase ($appends)
```

### Controllers
```
class:            ResourceController (MachineController)
method:           verb+resource (storeMachine)
route parameter:  singular snake_case (route('machines.edit', $machine))
```

### Tests
```
class:            TestSuffix (MachineTest.php)
method:           test_action_subject_expectation
example:          test_admin_can_create_machine()
```

---

## 🚩 Code Review Checklist

### Antes de Fazer PR Revisar:

- [ ] **Code Quality**
  - [ ] Sem código duplicado
  - [ ] Funções < 50 linhas
  - [ ] Nomes expressivos
  - [ ] Sem TODOs deixados

- [ ] **Tests**
  - [ ] Testes passam (100%)
  - [ ] Coverage adequada (>80%)
  - [ ] Testes novos adicionados

- [ ] **Security**
  - [ ] Sem SQL injection
  - [ ] Validação em lugar certo
  - [ ] Autorizações verificadas
  - [ ] Sem secrets em commit

- [ ] **Performance**
  - [ ] Sem N+1 queries
  - [ ] Eager loading usado
  - [ ] Índices appropriate

- [ ] **Documentation**
  - [ ] Código comentado (onde necessário)
  - [ ] README atualizado
  - [ ] Changelog adicionado

- [ ] **Database**
  - [ ] Migrations reversíveis
  - [ ] Constraints adicionadas
  - [ ] Índices criados

---

## 🔄 Deployment

### Pre-Deploy Checklist

```bash
# 1. Test locally
php artisan test

# 2. Build assets
npm run build

# 3. Check migrations
php artisan migrate:status

# 4. Verify config
php artisan config:cache

# 5. Deploy
git push origin main
```

### Production Steps

```bash
# 1. Pull code
git pull origin main

# 2. Install deps
composer install --no-dev

# 3. Migrate database
php artisan migrate --force

# 4. Cache config
php artisan config:cache

# 5. Build assets
npm run production

# 6. Clear caches
php artisan cache:clear
php artisan view:clear

# 7. Health check
curl https://maintsys.com/health
```

---

## 📊 Métricas de Desenvolvimento

| Métrica | Meta |
|---------|------|
| Test Coverage | >80% |
| Code Review Time | <24h |
| Deploy Frequency | 1-2x day |
| Mean Time to Recovery | <1h |
| Bug Escape Rate | <2% |

---

## 🔄 Fluxos Principais de Implementação

### Fluxo 1: Sistema de Autenticação & Autorização

```
User Login Flow:
1. User visit /admin
2. Filament Auth middleware
3. Credentials validation (email + password)
4. Spatie load roles + permissions
5. Session created
6. Dashboard loaded com user roles
7. Resources habilitados/desabilitados per role
```

**Verificações:**
- Auth middleware protege todas as rota
- Spatie verifica role antes de ação
- Policies validam em create/edit/delete
- Logs de acesso negado

---

### Fluxo 2: Criar & Rastrear Ordem de Serviço

```
Gerente creates O.S. → valida → salva
              ↓
Técnico vê em dashboard → clica "Iniciar"
              ↓
Status muda: open → in_progress (started_at = now)
              ↓
Técnico registra maintenance_logs (ações)
              ↓
Técnico clica "Concluir" com resolution_notes
              ↓
Status muda: in_progress → completed (completed_at = now)
              ↓
Gerente recebe notificação
              ↓
Dashboard widget atualiza
```

---

### Fluxo 3: Alteração Automática de Status + Alerta

```
Gerente altera Machine status (field select)
              ↓
submit form
              ↓
MachineController@update
              ↓
Machine::update(['status' => 'critical'])
              ↓
Boot Hook dispara (updating)
              ↓
isDirty('status')? → Sim
              ↓
Get previous_status (original) = 'operational'
Get new_status (novo) = 'critical'
              ↓
StatusAlert::create([
  machine_id,
  previous_status,
  new_status,
  message = "Máquina X mudou de operational para critical",
  triggered_at = now()
])
              ↓
Notification::make() enviado para todos users
  - Danger color se critical
  - Warning color se maintenance
  - Success color se operational
              ↓
Filament Notification enviada
              ↓
CriticalAlertsWidget recarrega
              ↓
Alerta aparece em Dashboard
```

---

### Fluxo 4: Detecção de Anomalia (MQTT - Futuro)

```
ESP-32 lê sensors (temp, vibration, rpm, pressure)
              ↓
MQTT publish para maintsys/machine/{serial}/sensors
              ↓
Mosquitto MQTT Broker
              ↓
Laravel MqttListener subscribe
              ↓
Process JSON payload
              ↓
Machine::where('serial_number', ...) find
              ↓
foreach sensor → MachineReading::create()
              ↓
Update Machine->last_reading_at
              ↓
Check thresholds (temp > 80, vibration > 5)
              ↓
Anomaly detected? → YES
              ↓
Event SensorAnomalyDetected dispatch
              ↓
Listener mudaстatus para 'critical'
              ↓
Boot hook cria StatusAlert
              ↓
Notification enviada
              ↓
Event broadcast websocket
              ↓
Dashboard atualiza em <1s
```

---

## 📋 Relacionamentos do Modelo (Eloquent)

```
User (1) ──→ (N) ServiceOrder (technician)
User (1) ──→ (N) ServiceOrder (created_by)
User (1) ──→ (N) MaintenanceLog
User (1) ──→ (N) StatusAlert (triggered_by)

Machine (1) ──→ (N) ServiceOrder
Machine (1) ──→ (N) MaintenanceLog
Machine (1) ──→ (N) MachineReading
Machine (1) ──→ (N) StatusAlert

ServiceOrder (1) ──→ (N) MaintenanceLog
```

---

## 🎯 Padrões de Implementação por Entity

### Machine Model

```php
class Machine extends Model {
    protected $fillable = [
        'serial_number',
        'name',
        'model',
        'location',
        'status',
        'installed_at',
        'description',
        'image',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'last_reading_at' => 'datetime',
    ];

    // Relationships
    public function serviceOrders() { ... }
    public function maintenanceLogs() { ... }
    public function readings() { ... }
    public function alerts() { ... }

    // Scopes
    public function scopeOperational($q) { ... }
    public function scopeInMaintenance($q) { ... }
    public function scopeCritical($q) { ... }
    public function scopeOffline($q) { ... }

    // Boot: Auto-create status alerts
    protected static function boot() { ... }
}
```

### ServiceOrder Model

```php
class ServiceOrder extends Model {
    protected $fillable = [
        'machine_id',
        'technician_id',
        'created_by',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'started_at',
        'completed_at',
        'resolution_notes',
    ];

    // Relationships
    public function machine() { ... }
    public function technician() { ... }
    public function creator() { ... }
    public function logs() { ... }

    // Methods
    public function isOpen() { ... }
    public function isCritical() { ... }
    public function start() { ... }
    public function complete(string $notes) { ... }
}
```

---

## 🧪 Estratégia de Testes (Expandido)

### Unit Tests (Models)

```php
class MachineTest extends TestCase {
    public function test_machine_can_be_created() { ... }
    public function test_machine_has_many_alerts() { ... }
    public function test_operational_scope_works() { ... }
    public function test_critical_scope_filters_correctly() { ... }
}

class ServiceOrderTest extends TestCase {
    public function test_service_order_can_be_started() { ... }
    public function test_service_order_can_be_completed() { ... }
    public function test_is_open_returns_true_when_status_open() { ... }
    public function test_is_critical_returns_true_when_priority_critical() { ... }
}
```

### Feature Tests (Resources)

```php
class MachineResourceTest extends TestCase {
    public function test_admin_can_create_machine() { ... }
    public function test_gerente_can_edit_machine() { ... }
    public function test_tecnico_cannot_create_machine() { ... }
    public function test_operador_cannot_edit_machine() { ... }
}

class ServiceOrderResourceTest extends TestCase {
    public function test_gerente_can_create_service_order() { ... }
    public function test_tecnico_cannot_assign_to_other_tecnico() { ... }
    public function test_service_order_state_transitions() { ... }
}
```

---

## 🚀 Inicialização do Projeto (Setup)

```bash
# 1. Clone repo
git clone <repo> maintsys
cd maintsys

# 2. Setup Laravel
composer install
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate:fresh --seed

# 4. Instalar Filament
composer require filament/filament
php artisan filament:install

# 5. Instalar Spatie Permission
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# 6. Setup yarn/npm
npm install
npm run dev

# 7. Build para production
npm run build

# 8. Test
php artisan test

# 9. Serve
php artisan serve
# Visit: http://localhost:8000/admin
# Login: admin@maintsys.com / password
```

---

## 📊 Checklist por Etapa

### Phase 1: Database & Models
- [ ] Migrations em ordem
- [ ] Models com relationships
- [ ] Scopes adicionados
- [ ] Boot hooks implementados
- [ ] Tests unitários passam

### Phase 2: Resources
- [ ] MachineResource funcionando
- [ ] ServiceOrderResource com actions
- [ ] MaintenanceLogResource CRUD
- [ ] UserResource + Roles
- [ ] StatusAlertResource com toggle

### Phase 3: Authorization
- [ ] Spatie roles criados (4)
- [ ] Policies implementadas
- [ ] canCreate/Edit/Delete no resource
- [ ] Field authorization (form)
- [ ] Tests de permissão

### Phase 4: Dashboard
- [ ] 4 Stats cards
- [ ] RecentServiceOrders widget
- [ ] CriticalAlerts widget
- [ ] MaintenanceLogs widget
- [ ] Real-time updates

### Phase 5: Integration & Tests
- [ ] Unit tests 80%+
- [ ] Feature tests passam
- [ ] RBAC matrix validada
- [ ] Alertas funcionam
- [ ] Performance aceitável

---

## 📞 Referências & Documentação

**Documentos Principais:**
- [[01-Requisitos]] — Especificação completa de requisitos
- [[_Documentação/README]] — Visão geral
- [[_Referência/Quick-Reference]] — Code snippets prontos

**Visual & Fluxos:**
- [[_Canvas/MaintSys-Overview.canvas]] — Mapa geral
- [[_Fluxogramas/]] — Todos os flowcharts
- [[_Documentação/DIAGRAMAS]] — Índice de diagramas

**Implementação:**
- [[_Documentação/07-Checklist]] — Passo-a-passo
- [[_Referência/INDEX]] — Busca por tópico
---

## 📊 Métricas e KPIs

| Métrica | Target | Method |
|---------|--------|--------|
| Test Coverage | >80% | `php artisan test --coverage` |
| Code Review Time | <24h | Pull request SLA |
| Deploy Frequency | 1-2x/day | CI/CD pipeline |
| Mean Time to Recovery | <1h | Alert + oncall rotation |
| Bug Escape Rate | <2% | QA + staging tests |
| Performance (Dashboard) | <2s | Frontend profiling |
| RBAC Accuracy | 100% | Authorization tests |

---

## 🚀 Go-Live Checklist

- [ ] Unit tests 80%+ ✅
- [ ] Feature tests passam ✅
- [ ] Code review aprovado ✅
- [ ] Security audit passado ✅
- [ ] Performance OK (<2s) ✅
- [ ] Documentação completa ✅
- [ ] Backup strategy testado ✅
- [ ] Monitoring setup ✅
- [ ] Alert notifications working ✅
- [ ] RBAC matrix validated ✅
- [ ] Error handling tested ✅
- [ ] Database optimization ✅
- [ ] Admin training completo ✅

---

*Metodologia de Desenvolvimento — MaintSys v1.0*
*Documento Master com Workflows, Padrões, Processos*
*Inclui: Ciclos, Testes, Git, OWASP, Performance, Deployment*
*2026-04-03*
