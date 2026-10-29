<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SOLICITANTE = 'solicitante';

    /**
     * `role` y `office_id` NO son masignables (SDD §8.5 y §21.5): el rol nunca
     * llega del formulario y la oficina se asigna explícitamente en el registro.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /** Solicitudes creadas por el usuario. */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(EquipmentRequest::class);
    }

    /** Solicitudes procesadas (aprobadas/rechazadas) como administrador. */
    public function solicitudesProcesadas(): HasMany
    {
        return $this->hasMany(EquipmentRequest::class, 'processed_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function scopeDeRol($query, string $role)
    {
        return $query->where('role', $role);
    }
}