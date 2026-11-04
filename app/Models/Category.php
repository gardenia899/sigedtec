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

    /** Campos técnicos que una categoría puede exigir al registrar un equipo. */
    public const CAMPOS_TECNICOS = [
        'cpu' => 'Procesador',
        'ram_gb' => 'RAM (GB)',
        'storage_gb' => 'Almacenamiento (GB)',
        'storage_type' => 'Tipo de almacenamiento',
    ];

    /** Sin configuración explícita se asume equipo de cómputo (compatibilidad). */
    public const CAMPOS_POR_DEFECTO = ['cpu', 'ram_gb', 'storage_gb', 'storage_type'];

    protected $fillable = [
        'name',
        'description',
        'spec_fields',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'spec_fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Especificaciones que aplican a esta categoría, en el orden del catálogo.
     *
     * @return list<string>
     */
    public function camposTecnicos(): array
    {
        $campos = $this->spec_fields ?? self::CAMPOS_POR_DEFECTO;

        return array_values(array_intersect(array_keys(self::CAMPOS_TECNICOS), $campos));
    }

    public function requiereCampo(string $campo): bool
    {
        return in_array($campo, $this->camposTecnicos(), true);
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