<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        $tipo = fake()->randomElement(['laptop', 'desktop', 'monitor', 'impresora']);

        return [
            'patrimonial_code' => sprintf('SIG-2026-%04d', fake()->unique()->numberBetween(1, 9999)),
            'category_id' => Category::factory(),
            'brand' => fake()->randomElement(['Lenovo', 'HP', 'Dell', 'Epson', 'Canon', 'Samsung', 'LG', 'Acer']),
            'model' => strtoupper(fake()->bothify('????-####')),
            'serial_number' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'cpu' => $tipo === 'laptop' || $tipo === 'desktop'
                ? fake()->randomElement(['Intel Core i5-1240P', 'Intel Core i3-12100', 'AMD Ryzen 5 5600G', 'Intel Core i7-1355U'])
                : null,
            'ram_gb' => $tipo === 'laptop' || $tipo === 'desktop' ? fake()->randomElement([8, 8, 16, 16, 32]) : null,
            'storage_gb' => $tipo === 'laptop' || $tipo === 'desktop' ? fake()->randomElement([256, 512, 512, 1024]) : null,
            'storage_type' => $tipo === 'laptop' || $tipo === 'desktop' ? fake()->randomElement(['ssd', 'nvme', 'hdd']) : null,
            'status' => 'disponible',
            'acquisition_date' => fake()->dateTimeBetween('-4 years', '-1 year')->format('Y-m-d'),
            'observations' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }

    public function asignado(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'asignado']);
    }

    public function mantenimiento(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'mantenimiento']);
    }
}