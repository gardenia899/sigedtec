<?php

namespace App\Models;

use Database\Factories\OfficeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    /** @use HasFactory<OfficeFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** Usuarios de la oficina. */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** Solicitudes originadas en la oficina. */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(EquipmentRequest::class);
    }

    /** Solo oficinas activas (selectores de captura). */
    public function scopeActivas($query)
    {
        return $query->where('is_active', true);
    }
}