<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Catálogo de aparatos del inventario (SDD §24) con las especificaciones
     * técnicas que aplican a cada tipo: a una impresora no se le pide RAM.
     */
    public function run(): void
    {
        $computo = ['cpu', 'ram_gb', 'storage_gb', 'storage_type'];

        $categorias = [
            ['name' => 'laptop', 'description' => 'Computadoras portátiles para personal administrativo.', 'spec_fields' => $computo],
            ['name' => 'computadora de escritorio', 'description' => 'Equipos de torre con monitor.', 'spec_fields' => $computo],
            ['name' => 'tablet', 'description' => 'Tabletas para consulta y levantamiento de información.', 'spec_fields' => $computo],
            ['name' => 'servidor', 'description' => 'Servidores de red y almacenamiento institucional.', 'spec_fields' => $computo],
            ['name' => 'monitor', 'description' => 'Pantallas de 19 a 27 pulgadas.', 'spec_fields' => []],
            ['name' => 'impresora', 'description' => 'Impresoras láser y matriciales.', 'spec_fields' => []],
            ['name' => 'impresora multifuncional', 'description' => 'Equipos de impresión, copia y escaneo.', 'spec_fields' => []],
            ['name' => 'escáner', 'description' => 'Escáneres de documentos.', 'spec_fields' => []],
            ['name' => 'proyector', 'description' => 'Video proyectores para aulas y salas de reunión.', 'spec_fields' => []],
            ['name' => 'periféricos', 'description' => 'Teclados, ratones, audífonos y webcams.', 'spec_fields' => []],
            ['name' => 'router', 'description' => 'Routers y puntos de acceso inalámbrico.', 'spec_fields' => []],
            ['name' => 'switch', 'description' => 'Switches de red para cableado estructurado.', 'spec_fields' => []],
            ['name' => 'ups', 'description' => 'Sistemas de respaldo eléctrico y reguladores.', 'spec_fields' => []],
            ['name' => 'teléfono ip', 'description' => 'Teléfonos IP de la red de voz institucional.', 'spec_fields' => []],
        ];

        foreach ($categorias as $categoria) {
            // Comparación insensible a mayúsculas: el índice único es sobre LOWER(name).
            $registro = Category::whereRaw('LOWER(name) = ?', [$categoria['name']])->first();

            if ($registro === null) {
                Category::create($categoria);

                continue;
            }

            if ($registro->spec_fields === null) {
                $registro->update(['spec_fields' => $categoria['spec_fields']]);
            }
        }
    }
}
