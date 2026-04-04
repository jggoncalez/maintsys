# 🎉 Pest PHP Integration Completo

## O que foi implementado

### 1️⃣ **Instalação do Pest**
- ✅ Adicionado `pestphp/pest` v2.34 ao `composer.json`
- ✅ Adicionado `pestphp/pest-plugin-laravel` v2.1 ao `composer.json`
- ✅ Configurado `phpunit.xml` para SQLite em memória durante testes

### 2️⃣ **Estrutura de Testes**
```
tests/
├── Pest.php                           # Configuração global + helpers
├── Feature/
│   └── Resources/
│       ├── MachineResourceTest.php     # Testes do MachineResource
│       └── ServiceOrderResourceTest.php # Testes do ServiceOrderResource
└── Unit/
    └── Models/
        ├── MachineTest.php             # Testes do Model Machine
        └── ServiceOrderTest.php         # Testes do Model ServiceOrder
```

### 3️⃣ **Testes Implementados**

#### Machine Model Tests (8 testes)
- ✅ Criar máquina
- ✅ Scopes: `operational()`, `inMaintenance()`, `critical()`, `offline()`
- ✅ Criar `StatusAlert` ao mudar status
- ✅ Relacionamentos: serviceOrders, maintenanceLogs, readings, statusAlerts

#### ServiceOrder Model Tests (9 testes)
- ✅ Criar ordem de serviço
- ✅ Métodos: `isOpen()`, `isCritical()`, `start()`, `complete()`
- ✅ Relacionamentos: machine, technician, creator, maintenanceLogs

#### Machine Resource Tests (6 testes)
- ✅ Permissões por role (admin, gerente, tecnico)
- ✅ Create/Edit/Delete com verificações de permissão
- ✅ Acesso a recursos

#### ServiceOrder Resource Tests (8 testes)
- ✅ Permissões por role
- ✅ Create/Edit/Delete com verificações
- ✅ Filtros de status, tipo, prioridade

### 4️⃣ **Ferramentas Criadas**

#### `test.sh` - Script Helper
```bash
./test.sh all          # Rodar todos os testes
./test.sh unit         # Apenas testes unitários
./test.sh feature      # Apenas testes de feature
./test.sh coverage     # Com relatório de cobertura
./test.sh parallel     # Testes em paralelo
./test.sh watch        # Watch mode (re-executa ao mudar)
./test.sh seed 10      # Seed com 10 registros
```

#### Comando `seed:test-data`
```bash
php artisan seed:test-data        # Seed com 5 registros padrão
php artisan seed:test-data --count=20  # Seed com 20 registros
```

Cria:
- ✅ 3 Roles: admin, gerente, tecnico
- ✅ Usuários de teste para cada role
- ✅ Máquinas, Ordens de Serviço, Logs de Manutenção

### 5️⃣ **Helpers Disponíveis** (em `tests/Pest.php`)

```php
// Criar usuário simples
$user = createUser();
$user = createUser(['name' => 'João', 'email' => 'joao@test.local']);

// Criar usuário com role
$admin = createUserWithRole('admin');
$gerente = createUserWithRole('gerente');
$tecnico = createUserWithRole('tecnico');
```

### 6️⃣ **Factories Criadas**
- ✅ `MachineFactory` - Gera máquinas com dados realistas
- ✅ `ServiceOrderFactory` - Gera ordens de serviço
- ✅ `MaintenanceLogFactory` - Gera logs de manutenção
- ✅ `MachineReadingFactory` - Gera leituras de sensores
- ✅ `StatusAlertFactory` - Gera alertas de status
- ✅ `UserFactory` - Já existente, atualizado

### 7️⃣ **Documentação**
- ✅ `TESTING.md` - Guia completo de uso do Pest

## Como Começar

### 1. Instalar dependências
```bash
composer install
```

### 2. Rodar migrations
```bash
php artisan migrate
```

### 3. Seed dados de teste
```bash
php artisan seed:test-data --count=10
```

### 4. Executar testes
```bash
# Via script
./test.sh all

# Ou direto
php artisan test
```

## Exemplos de Uso

### Rodar teste específico
```bash
php artisan test tests/Unit/Models/MachineTest.php
```

### Com verbose
```bash
php artisan test --verbose
```

### Apenas um método de teste
```bash
php artisan test tests/Unit/Models/MachineTest.php --filter "can create a machine"
```

### Watch mode (auto-execute ao salvar)
```bash
./test.sh watch
```

### Coverage report
```bash
./test.sh coverage
```

## Assertions Comuns

```php
// Básico
expect($value)->toBe($expected);
expect($value)->toBeTrue();
expect($value)->toBeNull();

// Collections
expect($array)->toHaveCount(5);
expect($array)->toContain('value');

// Database
expect(Machine::where('name', 'Test')->exists())->toBeTrue();

// Requests HTTP
$this->get('/url')->assertSuccessful();
$this->post('/url', $data)->assertForbidden();

// Exceções
expect(fn() => throw new Exception())->toThrow(Exception::class);
```

## Próximos Passos (Opcional)

1. Criar mais testes para:
   - MaintenanceLogResource
   - StatusAlertResource
   - UserResource

2. Adicionar testes de integração para:
   - Fluxo completo de ordem de serviço
   - Mudanças de status de máquina

3. Setup CI/CD:
   - GitHub Actions para rodar testes automaticamente
   - Coverage badges

## Referências
- [Pest Documentation](https://pestphp.com)
- [Pest Laravel Plugin](https://pestphp.com/docs/plugins/laravel)
- [PHPUnit Assertions](https://phpunit.de/manual/current/en/assertions.html)
