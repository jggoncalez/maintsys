<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusAlert extends Model
{
    use HasFactory;
    protected $fillable = [
        'machine_id',
        'triggered_by',
        'previous_status',
        'new_status',
        'message',
        'is_read',
        'triggered_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'triggered_at' => 'datetime',
    ];

    // Relationships
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
