# 🎉 Pest PHP - Testes Fáceis

Este projeto usar **Pest PHP** para testes simples, elegantes e produtivos.

## Instalação

```bash
# Install dependencies (if not already installed)
composer install
```

## Executar Testes

### Rodar todos os testes
```bash
php artisan test
```

### Rodar apenas testes Unit
```bash
php artisan test tests/Unit
```

### Rodar apenas testes Feature
```bash
php artisan test tests/Feature
```

### Rodar um arquivo específico
```bash
php artisan test tests/Unit/Models/MachineTest.php
```

### Rodar com verbose output
```bash
php artisan test --verbose
```

### Rodar com coverage (relatório de cobertura)
```bash
php artisan test --coverage
```

### Rodar testes em paralelo (mais rápido)
```bash
php artisan test --parallel
```

## Estrutura de Testes

```
tests/
├── Pest.php                    # Configuração global e helpers
├── TestCase.php               # Classe base para testes
├── Unit/                      # Testes unitários
│   └── Models/               # Testes de Models
│       ├── MachineTest.php
│       └── ServiceOrderTest.php
└── Feature/                  # Testes de funcionalidades
    └── Resources/            # Testes de Resources Filament
        ├── MachineResourceTest.php
        └── ServiceOrderResourceTest.php
```

## Escrevendo Testes com Pest

### Sintaxe Básica

```php
<?php

describe('Feature Description', function () {
    it('does something', function () {
        $result = yourFunction();

        expect($result)->toBe(true);
    });

    it('does another thing', function () {
        expect('foo')->toEqual('foo');
    });
});
```

### Assertions Comuns

```php
// Igualdade
expect($value)->toBe($expected);
expect($value)->toEqual($expected);
expect($value)->toNotBe($unexpected);

// Booleanos
expect($value)->toBeTrue();
expect($value)->toBeFalse();

// Null
expect($value)->toBeNull();
expect($value)->not->toBeNull();

// Arrays
expect($array)->toHaveCount(5);
expect($array)->toHaveKey('name');
expect($array)->toContain('value');

// Strings
expect($string)->toContain('substring');
expect($string)->toMatch('/pattern/');

// Objetos
expect($object)->toBeInstanceOf(MyClass::class);

// Exceções
expect(function () {
    throw new Exception();
})->toThrow(Exception::class);
```

### Setup e Teardow

```php
describe('With Setup', function () {
    beforeEach(function () {
        // Executado antes de cada teste
        $this->user = createUser();
    });

    afterEach(function () {
        // Executado após cada teste
        // Cleanup se necessário
    });

    it('uses the setup', function () {
        expect($this->user)->not->toBeNull();
    });
});
```

### Dados Compartilhados

```php
it('works with multiple datasets', function (int $value, int $expected) {
    expect($value * 2)->toBe($expected);
})->with([
    [1, 2],
    [2, 4],
    [3, 6],
]);
```

## Helpers Disponíveis

Veja `tests/Pest.php` para helpers úteis:

```php
// Criar usuário
$user = createUser();
$user = createUser(['name' => 'João']);

// Criar usuário com role específico
$admin = createUserWithRole('admin');
$tecnico = createUserWithRole('tecnico');
```

## Testes com Database (RefreshDatabase)

Todos os testes usam `RefreshDatabase` para limpar o banco após cada teste:

```php
describe('Database Operations', function () {
    it('creates a record', function () {
        Machine::factory()->create([
            'name' => 'Máquina Test'
        ]);

        expect(Machine::where('name', 'Máquina Test')->exists())->toBeTrue();
    });

    // Banco é resetado automaticamente após este teste
});
```

## Testes Autenticados

```php
describe('Authenticated Routes', function () {
    it('requires authentication', function () {
        $this->get('/admin/machines')->assertRedirect('/login');
    });

    it('grants access with permission', function () {
        $user = createUserWithRole('admin');

        $this->actingAs($user)
            ->get('/admin/machines')
            ->assertSuccessful();
    });
});
```

## Verificando Permissões

```php
describe('Permissions', function () {
    it('admin can delete', function () {
        $admin = createUserWithRole('admin');
        $machine = Machine::factory()->create();

        $this->actingAs($admin)
            ->delete('/admin/machines/' . $machine->id)
            ->assertSuccessful();
    });

    it('tecnico cannot delete', function () {
        $tecnico = createUserWithRole('tecnico');
        $machine = Machine::factory()->create();

        $this->actingAs($tecnico)
            ->delete('/admin/machines/' . $machine->id)
            ->assertForbidden();
    });
});
```

## Documentação Completa

Para mais informações, visite:
- [Pest Documentation](https://pestphp.com)
- [Pest Laravel Plugin](https://pestphp.com/docs/plugins/laravel)
