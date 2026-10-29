<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Orden determinista y coherente con las reglas del SDD §24:
     * oficinas → categorías → usuarios → equipos → solicitudes.
     */
    public function run(): void
    {
        $this->call([
            OfficeSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            EquipmentSeeder::class,
            RequestSeeder::class,
        ]);
    }
}