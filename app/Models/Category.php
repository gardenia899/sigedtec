<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    // Sin SoftDeletes: las categorías se desactivan con `is_active` (SDD §14-M3,
    // RN-07) para no duplicar dos mecanismos con el mismo significado.

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relación base exigida por el enunciado: una categoría tiene muchos equipos (1:N).
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /** Solicitudes que refieren esta categoría. */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(EquipmentRequest::class);
    }

    /** Solo categorías activas (selectores de captura). */
    public function scopeActivas($query)
    {
        return $query->where('is_active', true);
    }
}