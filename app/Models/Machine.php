<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Notifications\Notification;

class Machine extends Model
{
    use HasFactory;
    protected $fillable = [
        'serial_number',
        'name',
        'model',
        'location',
        'status',
        'installed_at',
        'description',
        'image',
        'last_reading_at',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'last_reading_at' => 'datetime',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                // Create status alert
                StatusAlert::create([
                    'machine_id' => $model->id,
                    'triggered_by' => auth()->id(),
                    'previous_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'message' => "Máquina '{$model->name}' alterou status de {$oldStatus} para {$newStatus}",
                    'triggered_at' => now(),
                ]);

                // Send notification to all users
                $users = \App\Models\User::all();
                foreach ($users as $user) {
                    Notification::make()
                        ->title('Alerta de Status de Máquina')
                        ->body("Máquina '{$model->name}' alterou status para {$newStatus}")
                        ->sendToDatabase($user);
                }
            }
        });
    }

    // Relationships
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(MachineReading::class);
    }

    public function statusAlerts(): HasMany
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
}
