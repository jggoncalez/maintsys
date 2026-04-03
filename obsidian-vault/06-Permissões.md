# 06 - Permissões e Segurança (Spatie)

## 🔐 Instalação

```bash
composer require spatie/laravel-permission
composer require filament/spatie-laravel-permission-plugin

# Publicar config
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"

# Rodas migrations do Spatie
php artisan migrate
```

---

## 👥 Roles (Papéis)

Criar 4 roles principais no DatabaseSeeder:

### **admin**
Acesso total ao sistema. Pode criar, editar e deletar tudo.

```
Permissões: view_any, view, create, update, delete (todas as resources)
```

---

### **gerente**
Responsável por acompanhar saúde dos equipamentos e gerenciar O.S.

```
Máquinas:
  - view_any: ✅
  - view: ✅
  - create: ❌
  - update: ✅ (status, description)
  - delete: ❌

Ordens de Serviço:
  - view_any: ✅
  - view: ✅
  - create: ✅
  - update: ✅
  - delete: ❌ (apenas admin)

Histórico de Manutenção:
  - view_any: ✅
  - view: ✅
  - create: ❌
  - update: ❌
  - delete: ❌

Alertas de Status:
  - view_any: ✅
  - view: ✅
  - create: ❌ (automático)
  - update: ✅ (marcar como lido)
  - delete: ❌

Usuários:
  - view_any: ✅ (listar)
  - view: ✅
  - create: ❌
  - update: ❌
  - delete: ❌
```

---

### **tecnico**
Executa a manutenção. Registra logs e completa ordens.

```
Máquinas:
  - view_any: ✅
  - view: ✅
  - create: ❌
  - update: ❌
  - delete: ❌

Ordens de Serviço:
  - view_any: ✅
  - view: ✅
  - create: ✅ (pode criar para si mesmo se autorizado)
  - update: ⚠️ (apenas próprias O.S.: iniciar e concluir)
  - delete: ❌

Histórico de Manutenção:
  - view_any: ✅
  - view: ✅
  - create: ✅ (registrar log)
  - update: ❌
  - delete: ❌

Alertas de Status:
  - view_any: ✅
  - view: ✅
  - create: ❌
  - update: ❌
  - delete: ❌

Usuários:
  - view_any: ❌
  - view: ⚠️ (apenas perfil próprio)
  - create: ❌
  - update: ❌
  - delete: ❌
```

---

### **operador**
Acesso apenas de leitura. Visualiza estado dos equipamentos.

```
Todas as resources:
  - view_any: ✅
  - view: ✅
  - create: ❌
  - update: ❌
  - delete: ❌
```

---

## 🛡️ Policies (Autorização)

### MachinePolicy

```php
public function viewAny(User $user): bool
{
    return $user->can('view_any_machine');
}

public function view(User $user, Machine $machine): bool
{
    return $user->can('view_machine');
}

public function create(User $user): bool
{
    return $user->hasAnyRole(['admin', 'gerente']);
}

public function update(User $user, Machine $machine): bool
{
    return $user->hasAnyRole(['admin', 'gerente']);
}

public function delete(User $user, Machine $machine): bool
{
    return $user->hasRole('admin');
}
```

---

### ServiceOrderPolicy

```php
public function viewAny(User $user): bool
{
    return $user->can('view_any_service_order');
}

public function view(User $user, ServiceOrder $order): bool
{
    return $user->can('view_service_order');
}

public function create(User $user): bool
{
    return $user->hasAnyRole(['admin', 'gerente', 'tecnico']);
}

public function update(User $user, ServiceOrder $order): bool
{
    return $user->hasAnyRole(['admin', 'gerente']) ||
           ($user->hasRole('tecnico') && $user->id === $order->technician_id);
}

public function delete(User $user, ServiceOrder $order): bool
{
    return $user->hasRole('admin');
}
```

---

### MaintenanceLogPolicy

```php
public function create(User $user): bool
{
    return $user->hasAnyRole(['admin', 'gerente', 'tecnico']);
}

public function update(User $user, MaintenanceLog $log): bool
{
    return $user->hasAnyRole(['admin', 'gerente']);
}

public function delete(User $user, MaintenanceLog $log): bool
{
    return $user->hasRole('admin');
}
```

---

### StatusAlertPolicy

```php
public function update(User $user, StatusAlert $alert): bool
{
    // Apenas admin e gerente podem marcar como lido
    return $user->hasAnyRole(['admin', 'gerente']);
}

public function delete(User $user, StatusAlert $alert): bool
{
    return $user->hasRole('admin');
}
```

---

## 📝 Checklist de Proteção em Resources

### MachineResource
```php
public static function canCreate(): bool
{
    return auth()->user()?->hasAnyRole(['admin', 'gerente']) ?? false;
}

public static function canEdit(Model $record): bool
{
    return auth()->user()?->hasAnyRole(['admin', 'gerente']) ?? false;
}

public static function canDelete(Model $record): bool
{
    return auth()->user()?->hasRole('admin') ?? false;
}
```

### ServiceOrderResource
```php
public static function canCreate(): bool
{
    return auth()->user()?->hasAnyRole(['admin', 'gerente', 'tecnico']) ?? false;
}

// Ações específicas requerem Policies
```

### UserResource
```php
public static function canCreate(): bool
{
    return auth()->user()?->hasRole('admin') ?? false;
}

public static function canEdit(Model $record): bool
{
    return auth()->user()?->hasRole('admin') ?? false;
}

public static function canDelete(Model $record): bool
{
    return auth()->user()?->hasRole('admin') ?? false;
}
```

---

## 🔑 Usuários Seed

### 1. Admin
```
Email: admin@maintsys.com
Password: password
Roles: ['admin']
```

### 2. Gerente
```
Email: gerente@maintsys.com
Password: password
Roles: ['gerente']
```

### 3. Técnicos (3x)
```
tecnico1@maintsys.com → role: tecnico
tecnico2@maintsys.com → role: tecnico
tecnico3@maintsys.com → role: tecnico
Password: password (para todos)
```

---

## 🔐 Middleware e Autenticação

### Garantir Autenticação

```php
// laravel/routes/web.php
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Filament já cuida disso automaticamente
});
```

### Verificar em Seeders

```php
// Após criar users com roles, verificar:
$admin = User::where('email', 'admin@maintsys.com')->first();
$admin->assignRole('admin');
$admin->givePermissionTo(Permission::all()); // Opcional, depende de Spatie
```

---

## 🎯 Fluxo de Autorização

1. **User faz login** → Laravel autentica contra tabela `users`
2. **Filament carrega resource** → verifica `canCreate/Edit/Delete`
3. **Filament renderiza tabela** → filtra ações por role
4. **User clica ação** → Policy (`authorize()`) valida
5. **Controller/Model** → executa ou retorna 403

---

## 📊 Matrix de Permissões

| Resource | Admin | Gerente | Tecnico | Operador |
|----------|-------|---------|---------|----------|
| **Machine** | CRUD | CRU | R | R |
| **ServiceOrder** | CRUD | CRUD | CRU (próprias) | R |
| **MaintenanceLog** | CRUD | CRU | CR | R |
| **StatusAlert** | CRUD | CRU | R | R |
| **User** | CRUD | R | ∅ | ∅ |

**Legenda:** C=Create, R=Read, U=Update, D=Delete, ∅=sem acesso

---

*[[README]] | [[05-Dashboard]] | [[07-Checklist]]*
