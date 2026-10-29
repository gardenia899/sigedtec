<?php

namespace App\Models;

use Database\Factories\StatusHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Bitácora append-only de cambios de estado del equipo (RN-19, SDD §16.7).
 * Nunca se actualiza ni se elimina.
 */
class StatusHistory extends Model
{
    /** @use HasFactory<StatusHistoryFactory> */
    use HasFactory;

    /** Tabla singular: es una bitácora, no un plural (SDD §15). */
    protected $table = 'status_history';

    /** Solo created_at: los registros de auditoría son inmutables. */
    public const UPDATED_AT = null;

    protected $fillable = [
        'equipment_id',
        'request_id',
        'user_id',
        'from_status',
        'to_status',
        'reason',
        'notes',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(EquipmentRequest::class, 'request_id');
    }

    /** Usuario que ejecutó el cambio (puede ser nulo si se eliminó). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}