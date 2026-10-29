<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /** Categorías principales del inventario (SDD §24). */
    public function run(): void
    {
        $categorias = [
            ['name' => 'Laptop', 'description' => 'Computadoras portátiles para personal administrativo.'],
            ['name' => 'Computadora de escritorio', 'description' => 'Equipos de torre con monitor.'],
            ['name' => 'Monitor', 'description' => 'Pantallas de 19 a 27 pulgadas.'],
            ['name' => 'Impresora', 'description' => 'Impresoras láser y matriciales.'],
            ['name' => 'Escáner', 'description' => 'Escáneres de documentos.'],
            ['name' => 'Periféricos', 'description' => 'Teclados, ratones, audífonos y webcams.'],
        ];

        foreach ($categorias as $categoria) {
            Category::firstOrCreate(['name' => $categoria['name']], $categoria);
        }
    }
}