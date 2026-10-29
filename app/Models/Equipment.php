<?php

namespace App\Models;

use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory, SoftDeletes;

    /** Tabla en inglés singular (D-06): el inflector no pluraliza "equipment". */
    protected $table = 'equipment';

    /**
     * `status` NO es masignable (SDD §21.5): solo se escribe desde rutas controladas
     * (alta, edición admin o la transacción de despacho).
     *
     * @var list<string>
     */
    protected $fillable = [
        'patrimonial_code',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'cpu',
        'ram_gb',
        'storage_gb',
        'storage_type',
        'acquisition_date',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'ram_gb' => 'integer',
            'storage_gb' => 'integer',
            'acquisition_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Registros de asignación históricos del equipo. */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    /** Bitácora de cambios de estado (RN-19). */
    public function history(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    /** Asignación activa actual (returned_at IS NULL). Fuente de verdad de la oficina. */
    public function activeAssignment(): HasOne
    {
        return $this->hasOne(RequestItem::class)->whereNull('returned_at')->latestOfMany();
    }

    /** Equipos no borrados lógicamente y disponibles para despacho. */
    public function scopeDisponibles($query)
    {
        return $query->where('status', 'disponible');
    }
}