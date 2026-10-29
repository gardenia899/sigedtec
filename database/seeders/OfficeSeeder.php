<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /** Catálogo institucional de oficinas (SDD §24). */
    public function run(): void
    {
        $oficinas = [
            ['code' => 'OFI-01', 'name' => 'Soporte Técnico', 'location' => 'Edificio Central, Piso 1'],
            ['code' => 'OFI-02', 'name' => 'Rectoría', 'location' => 'Edificio Central, Piso 3'],
            ['code' => 'OFI-03', 'name' => 'Secretaría', 'location' => 'Edificio A, Piso 1'],
            ['code' => 'OFI-04', 'name' => 'Laboratorio de Cómputo', 'location' => 'Edificio B, Piso 2'],
        ];

        foreach ($oficinas as $oficina) {
            Office::firstOrCreate(['code' => $oficina['code']], $oficina);
        }
    }
}