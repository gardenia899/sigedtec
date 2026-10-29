<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->numerify('OFI-##'),
            'name' => ucwords(fake()->unique()->words(2, true)).' '.fake()->randomElement(['Oficina', 'Departamento', 'Sección']),
            'location' => fake()->randomElement(['Edificio A, Piso 1', 'Edificio B, Piso 2', 'Edificio Central, Piso 3', null]),
            'is_active' => true,
        ];
    }

    public function inactiva(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}