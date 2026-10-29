<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\EquipmentRequest>
 */
class EquipmentRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'office_id' => Office::factory(),
            'user_id' => User::factory()->solicitante(),
            'category_id' => \App\Models\Category::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'justification' => fake()->paragraph(),
            'status' => 'pendiente',
        ];
    }

    /** Cambia solo los campos de decisión; nunca se generan desde el cliente. */
    public function aprobada(User $admin): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aprobada',
            'processed_by' => $admin->id,
            'processed_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    public function rechazada(User $admin, string $observacion = 'Inventario insuficiente en este período.'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rechazada',
            'admin_observation' => $observacion,
            'processed_by' => $admin->id,
            'processed_at' => now()->subDays(fake()->numberBetween(1, 15)),
        ]);
    }

    public function cancelada(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelada']);
    }
}