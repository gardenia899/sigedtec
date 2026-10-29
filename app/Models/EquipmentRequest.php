<?php

namespace App\Models;

use Database\Factories\EquipmentRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentRequest extends Model
{
    /** @use HasFactory<EquipmentRequestFactory> */
    use HasFactory;

    /**
     * `status`, `admin_observation`, `processed_by` y `processed_at` NO son masignables:
     * el cliente jamás decide el resultado de su propia solicitud (SDD §21.5).
     *
     * @var list<string>
     */
    protected $fillable = [
        'office_id',
        'user_id',
        'category_id',
        'quantity',
        'justification',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /** Solicitante (Jefe de Oficina). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Administrador que aprobó o rechazó (RN-17). */
    public function procesadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /** Equipos concretos despachados en esta solicitud. */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class, 'request_id');
    }

    /** Movimientos de equipos generados por esta solicitud. */
    public function history(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'request_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('status', 'pendiente');
    }
}