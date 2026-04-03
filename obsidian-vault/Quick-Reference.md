# 💻 Quick Reference - Snippets & Exemplos de Código

## 🚀 Copiar & Colar - Pronto para Usar

Este arquivo contém snippets prontos para accelerar a implementação.

---

## 🗄️ Migrations

### Machine Migration
```php
// database/migrations/2024_01_01_000000_create_machines_table.php

Schema::create('machines', function (Blueprint $table) {
    $table->id();
    $table->string('serial_number')->unique();
    $table->string('name');
    $table->string('model');
    $table->string('location');
    $table->enum('status', ['operational', 'maintenance', 'critical', 'offline'])->default('operational');
    $table->date('installed_at');
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->timestamp('last_reading_at')->nullable();
    $table->timestamps();

    $table->index('status');
    $table->index('location');
    $table->fullText(['name', 'serial_number']);
});
```

### ServiceOrder Migration
```php
Schema::create('service_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
    $table->foreignId('technician_id')->constrained('users')->restrictOnDelete();
    $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
    $table->string('title');
    $table->text('description');
    $table->enum('type', ['preventive', 'corrective']);
    $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
    $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->text('resolution_notes')->nullable();
    $table->timestamps();

    $table->index(['machine_id', 'status']);
    $table->index('technician_id');
});
```

---

## 📦 Models

### Machine Model com Boot Hook
```php
// app/Models/Machine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StatusAlert;
use Filament\Notifications\Notification;

class Machine extends Model
{
    protected $fillable = [
        'serial_number', 'name', 'model', 'location', 'status',
        'installed_at', 'description', 'image', 'last_reading_at'
    ];

    protected $casts = [
        'installed_at' => 'date',
        'last_reading_at' => 'datetime',
    ];

    // Relationships
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function readings()
    {
        return $this->hasMany(MachineReading::class);
    }

    public function alerts()
    {
        return $this->hasMany(StatusAlert::class);
    }

    // Scopes
    public function scopeOperational($query)
    {
        return $query->where('status', 'operational');
    }

    public function scopeInMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    // Boot Hook: Alerta ao mudar status
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($machine) {
            if ($machine->isDirty('status')) {
                $previous = $machine->getOriginal('status');
                $new = $machine->status;

                StatusAlert::create([
                    'machine_id' => $machine->id,
                    'previous_status' => $previous,
                    'new_status' => $new,
                    'message' => "Máquina {$machine->name} mudou de {$previous} para {$new}",
                    'triggered_at' => now(),
                ]);

                // Notificação Filament
                Notification::make()
                    ->title("Alerta: {$machine->name}")
                    ->body("Status alterado para: {$new}")
                    ->when($new === 'critical', fn($n) => $n->danger())
                    ->when($new === 'maintenance', fn($n) => $n->warning())
                    ->when($new === 'operational', fn($n) => $n->success())
                    ->sendToDatabase(User::all());
            }
        });
    }
}
```

### ServiceOrder Model
```php
// app/Models/ServiceOrder.php

class ServiceOrder extends Model
{
    protected $fillable = [
        'machine_id', 'technician_id', 'created_by', 'title', 'description',
        'type', 'priority', 'status', 'started_at', 'completed_at', 'resolution_notes'
    ];

    // Relationships
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    // Methods
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isCritical(): bool
    {
        return $this->priority === 'critical';
    }

    public function start()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete(string $notes)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }
}
```

---

## 🎨 Filament Resources

### MachineResource struktura básica
```php
// app/Filament/Resources/MachineResource.php

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;
    protected static ?string $navigationLabel = 'Máquinas';
    protected static ?string $navigationGroup = 'Equipamentos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('serial_number')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('name')->required(),
            TextInput::make('model')->required(),
            TextInput::make('location')->required(),
            DatePicker::make('installed_at')->required(),
            Select::make('status')
                ->options([
                    'operational' => 'Operacional',
                    'maintenance' => 'Manutenção',
                    'critical' => 'Crítica',
                    'offline' => 'Offline',
                ])
                ->default('operational'),
            Textarea::make('description'),
            FileUpload::make('image')
                ->disk('public')
                ->directory('machines'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('serial_number'),
            TextColumn::make('name'),
            TextColumn::make('model'),
            TextColumn::make('location'),
            BadgeColumn::make('status')
                ->colors([
                    'success' => 'operational',
                    'warning' => 'maintenance',
                    'danger' => 'critical',
                    'gray' => 'offline',
                ]),
            TextColumn::make('installed_at')->date(),
            TextColumn::make('last_reading_at')->dateTime(),
        ])->filters([
            SelectFilter::make('status'),
            SelectFilter::make('location'),
        ])->actions([
            EditAction::make(),
            Action::make('mark_critical')
                ->label('Marcar como Crítica')
                ->action(fn(Machine $machine) => $machine->update(['status' => 'critical']))
                ->requiresConfirmation(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            ServiceOrdersRelationManager::class,
            MaintenanceLogsRelationManager::class,
            MachineReadingsRelationManager::class,
            StatusAlertsRelationManager::class,
        ];
    }

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
}
```

---

## 🛡️ Policies

### MachinePolicy
```php
// app/Policies/MachinePolicy.php

class MachinePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Machine $machine): bool
    {
        return true;
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
}
```

---

## 📊 Widgets

### StatsOverviewWidget
```php
// app/Filament/Widgets/StatsOverviewWidget.php

class StatsOverviewWidget extends Widget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Máquinas', Machine::count())
                ->icon('heroicon-o-cog-6-tooth'),
            Stat::make('Operacionais', Machine::operational()->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Em Manutenção', Machine::inMaintenance()->count())
                ->icon('heroicon-o-wrench')
                ->color('warning'),
            Stat::make('Crítica', Machine::critical()->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
```

---

## 🔐 Spatie Setup

### DatabaseSeeder com Roles
```php
// database/seeders/DatabaseSeeder.php

public function run(): void
{
    // Criar roles
    $admin = Role::findOrCreate('admin');
    $gerente = Role::findOrCreate('gerente');
    $tecnico = Role::findOrCreate('tecnico');
    $operador = Role::findOrCreate('operador');

    // Criar usuários
    $adminUser = User::create([
        'name' => 'Admin',
        'email' => 'admin@maintsys.com',
        'password' => bcrypt('password'),
    ]);
    $adminUser->assignRole($admin);

    $gerenteUser = User::create([
        'name' => 'Gerente',
        'email' => 'gerente@maintsys.com',
        'password' => bcrypt('password'),
    ]);
    $gerenteUser->assignRole($gerente);

    // Técnicos
    for ($i = 1; $i <= 3; $i++) {
        $tecnicoUser = User::create([
            'name' => "Técnico $i",
            'email' => "tecnico$i@maintsys.com",
            'password' => bcrypt('password'),
        ]);
        $tecnicoUser->assignRole($tecnico);
    }
}
```

---

## 🧪 Testes

### Test: Create Machine
```php
// tests/Feature/MachineResourceTest.php

public function test_admin_can_create_machine()
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->post('/admin/machines', [
        'serial_number' => 'SN-2024-001',
        'name' => 'Máquina Teste',
        'model' => 'XYZ-100',
        'location' => 'Galpão A',
        'installed_at' => now()->date(),
        'status' => 'operational',
    ])->assertSuccessful();

    $this->assertDatabaseHas('machines', [
        'serial_number' => 'SN-2024-001',
    ]);
}

public function test_tecnico_cannot_create_machine()
{
    $tecnico = User::factory()->create();
    $tecnico->assignRole('tecnico');

    $this->actingAs($tecnico)->post('/admin/machines', [
        'serial_number' => 'SN-2024-001',
        'name' => 'Máquina Teste',
        // ...
    ])->assertForbidden();
}
```

---

## 📡 MQTT Listener (Futuro)

```php
// app/Console/Commands/ListenMqttCommand.php

class ListenMqttCommand extends Command
{
    protected $signature = 'mqtt:listen';

    public function handle()
    {
        $mqtt = new \PhpMqtt\Client\MqttClient(
            env('MQTT_HOST'),
            env('MQTT_PORT')
        );

        $mqtt->connect();
        $mqtt->subscribe('maintsys/machine/+/sensors', function (
            $topic,
            $message
        ) {
            $payload = json_decode($message, true);
            $serialNumber = explode('/', $topic)[2];

            $machine = Machine::where('serial_number', $serialNumber)->first();

            if ($machine) {
                foreach ($payload['sensors'] as $key => $value) {
                    MachineReading::create([
                        'machine_id' => $machine->id,
                        'sensor_key' => $key,
                        'value' => $value,
                        'unit' => $this->getUnit($key),
                        'read_at' => now(),
                    ]);
                }

                $machine->update(['last_reading_at' => now()]);
            }
        });

        $mqtt->loop(true);
        $mqtt->disconnect();
    }

    private function getUnit(string $key): string
    {
        return match ($key) {
            'temperature' => '°C',
            'vibration' => 'mm/s',
            'rpm' => 'RPM',
            'pressure' => 'bar',
            default => '',
        };
    }
}
```

---

## 📋 Routes Filament

```php
// config/filament.php or AdminPanelProvider

$panel
    ->homeUrl('/admin')
    ->navigationItems([
        'dashboard' => [],
        'equipamentos' => [
            MachineResource::class,
        ],
        'operacoes' => [
            ServiceOrderResource::class,
            MaintenanceLogResource::class,
        ],
        'alertas' => [
            StatusAlertResource::class,
        ],
        'administracao' => [
            UserResource::class,
        ],
    ]);
```

---

## 🛠️ Artisan Commands

```bash
# Create Resource com Filament
php artisan make:filament-resource Machine

# Create Model with Migration
php artisan make:model Machine -m

# Create Seeder
php artisan make:seeder MachineSeeder

# Create Policy
php artisan make:policy MachinePolicy --model=Machine

# Migrations
php artisan migrate:fresh --seed

# Publish Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Cache clearance
php artisan config:cache
php artisan view:cache
php artisan cache:clear
```

---

## ⚙️ .env Configuration

```env
APP_NAME=MaintSys
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maintsys
DB_USERNAME=root
DB_PASSWORD=

FILAMENT_LOCALE=pt_BR

# MQTT (Futuro)
MQTT_HOST=mosquitto
MQTT_PORT=1883
MQTT_USERNAME=maintsys_user
MQTT_PASSWORD=secure_password
MQTT_PROTOCOL=tcp

# Thresholds
SENSOR_TEMP_MAX=80
SENSOR_VIBRATION_MAX=5
```

---

## 📝 Validation Rules

```php
// app/Http/Requests/MachineRequest.php

class MachineRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'serial_number' => 'required|string|unique:machines',
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'installed_at' => 'required|date',
            'status' => 'required|in:operational,maintenance,critical,offline',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
```

---

## 🧪 Checklist: Copy & Paste

```php
// ✅ Verify after each phase

// Phase 1: Migrations
php artisan migrate:fresh

// Phase 2: Models
Machine::count() // Should be 0
User::count() // Should be 1 (default)

// Phase 3: Resources
// Visit http://localhost:8000/admin

// Phase 4: Permissions
auth()->user()->hasRole('admin') // true

// Phase 5: Dashboard
// Widgets render?

// Phase 6: Seeds
php artisan migrate:fresh --seed
Machine::count() // Should be 10
ServiceOrder::count() // Should be 20
```

---

*Quick Reference — MaintSys*
*v1.0 — 2026-04-03*

*[[README]] | [[07-Checklist]] | [[Arquitetura-Tecnica]]*
