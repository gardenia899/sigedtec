<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\RequestItem>
 */
class RequestItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'request_id' => EquipmentRequest::factory(),
            'equipment_id' => Equipment::factory(),
            'assigned_by' => User::factory()->admin(),
            'status_before' => 'disponible',
            'status_after' => 'asignado',
        ];
    }
}