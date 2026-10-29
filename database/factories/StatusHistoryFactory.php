<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StatusHistory>
 */
class StatusHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'equipment_id' => Equipment::factory(),
            'request_id' => null,
            'user_id' => User::factory()->admin(),
            'from_status' => null,
            'to_status' => 'disponible',
            'reason' => 'creacion',
            'notes' => null,
        ];
    }

    public function asignacion(int $requestId): static
    {
        return $this->state(fn (array $attributes) => [
            'request_id' => $requestId,
            'from_status' => 'disponible',
            'to_status' => 'asignado',
            'reason' => 'asignacion',
        ]);
    }
}