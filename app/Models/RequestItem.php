<?php

namespace App\Models;

use Database\Factories\RequestItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestItem extends Model
{
    /** @use HasFactory<RequestItemFactory> */
    use HasFactory;

    /**
     * Todos los campos se generan en servidor dentro de la transacción de despacho;
     * ninguno proviene de la petición (SDD §14-M6).
     *
     * @var list<string>
     */
    protected $fillable = [
        'request_id',
        'equipment_id',
        'assigned_by',
        'status_before',
        'status_after',
        'returned_at',
        'return_condition',
        'return_observations',
    ];

    protected function casts(): array
    {
        return [
            'returned_at' => 'datetime',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(EquipmentRequest::class, 'request_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /** Administrador que ejecutó la asignación. */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /** Asignaciones aún vigentes (sin devolución). */
    public function scopeActiva($query)
    {
        return $query->whereNull('returned_at');
    }
}