<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineReading extends Model
{
    use HasFactory;
    protected $fillable = [
        'machine_id',
        'sensor_key',
        'value',
        'unit',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'value' => 'decimal:2',
        ];
    }

    // Relationships
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }
}
