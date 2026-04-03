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

## 📞 Arquivos de Referência

- [[_Documentação/LEVANTAMENTO-REQUISITOS]] — Requisitos detalhados
- [[_Documentação/02-Arquitetura]] — Arquitetura do projeto
- [[_Documentação/07-Checklist]] — Checklist de implementação
- [[_Referência/Quick-Reference]] — Code snippets
- [[_Fluxogramas/]] — Todos os fluxos

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

*Metodologia de Desenvolvimento — MaintSys v1.0*
*Pronto para Implementação*
*2026-04-03*
