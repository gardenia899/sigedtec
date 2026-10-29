<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * 26 equipos: 24 activos (14 disponibles, 6 asignados, 4 en mantenimiento)
 * y 2 borrados lógicamente, para probar soft deletes (SDD §24).
 */
class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Category::pluck('id', 'name');
        $admin = User::deRol(User::ROLE_ADMIN)->firstOrFail();

        // [sufijo, categoría, marca, modelo, estado]
        $especificaciones = [
            ['0001', 'Laptop', 'Lenovo', 'ThinkPad E14 Gen 4', 'asignado'],
            ['0002', 'Laptop', 'HP', 'ProBook 440 G9', 'asignado'],
            ['0003', 'Laptop', 'Dell', 'Latitude 5440', 'asignado'],
            ['0004', 'Laptop', 'Lenovo', 'IdeaPad 3 15ITL6', 'disponible'],
            ['0005', 'Laptop', 'HP', '240 G8', 'disponible'],
            ['0006', 'Laptop', 'Acer', 'Aspire 5 A515-56', 'disponible'],
            ['0007', 'Laptop', 'Dell', 'Inspiron 15 3520', 'mantenimiento'],
            ['0008', 'Monitor', 'Samsung', 'S24R350', 'asignado'],
            ['0009', 'Monitor', 'LG', '24MK430H', 'asignado'],
            ['0010', 'Monitor', 'Dell', 'P2422H', 'asignado'],
            ['0011', 'Monitor', 'Samsung', 'LF24T350', 'disponible'],
            ['0012', 'Monitor', 'LG', '22MK400H', 'disponible'],
            ['0013', 'Computadora de escritorio', 'HP', 'ProDesk 400 G7', 'disponible'],
            ['0014', 'Computadora de escritorio', 'Lenovo', 'ThinkCentre M70s', 'disponible'],
            ['0015', 'Computadora de escritorio', 'Dell', 'OptiPlex 7090', 'disponible'],
            ['0016', 'Computadora de escritorio', 'HP', 'Pavilion Desktop TP01', 'disponible'],
            ['0017', 'Impresora', 'Epson', 'EcoTank L3250', 'mantenimiento'],
            ['0018', 'Impresora', 'Canon', 'i-SENSYS LBP226dw', 'disponible'],
            ['0019', 'Impresora', 'HP', 'LaserJet Pro M404dn', 'disponible'],
            ['0020', 'Impresora', 'Epson', 'WorkForce WF-2850', 'disponible'],
            ['0021', 'Escáner', 'Epson', 'Perfection V19', 'mantenimiento'],
            ['0022', 'Escáner', 'Canon', 'CanoScan LiDE 300', 'disponible'],
            ['0023', 'Periféricos', 'Logitech', 'MK270 Combo', 'mantenimiento'],
            ['0024', 'Periféricos', 'Genius', 'Keyboard KB-102', 'disponible'],
            // Borrados lógicamente: prueban que el soft delete funciona (RN-11)
            ['0025', 'Laptop', 'HP', 'Compaq 6510b', 'disponible'],
            ['0026', 'Monitor', 'LG', 'Flatron L1920P', 'disponible'],
        ];

        foreach ($especificaciones as $i => [$sufijo, $categoria, $marca, $modelo, $estado]) {
            $conComponentes = in_array($categoria, ['Laptop', 'Computadora de escritorio'], true);
            $fechaAlta = now()->subDays(30 + $i);

            $equipo = Equipment::factory()->create([
                'patrimonial_code' => 'SIG-2026-'.$sufijo,
                'category_id' => $categorias[$categoria],
                'brand' => $marca,
                'model' => $modelo,
                'serial_number' => 'SN2026'.$sufijo,
                'cpu' => $conComponentes ? fake()->randomElement(['Intel Core i5-1240P', 'Intel Core i3-12100', 'AMD Ryzen 5 5600G']) : null,
                'ram_gb' => $conComponentes ? fake()->randomElement([8, 8, 16]) : null,
                'storage_gb' => $conComponentes ? fake()->randomElement([256, 512, 1024]) : null,
                'storage_type' => $conComponentes ? fake()->randomElement(['ssd', 'nvme', 'hdd']) : null,
                'status' => 'disponible', // toda máquina inicia disponible (§11.3)
                'acquisition_date' => $fechaAlta->copy()->subMonths(6)->format('Y-m-d'),
                'created_at' => $fechaAlta,
                'updated_at' => $fechaAlta,
                'deleted_at' => $sufijo >= '0025' ? now()->subDays(2) : null,
            ]);

            // RN-19: toda máquina deja su fila de alta en la bitácora
            StatusHistory::forceCreate([
                'equipment_id' => $equipo->id,
                'user_id' => $admin->id,
                'from_status' => null,
                'to_status' => 'disponible',
                'reason' => 'creacion',
                'notes' => 'Alta inicial en inventario',
                'created_at' => $fechaAlta,
            ]);

            // Transición real a mantenimiento, registrada también en la bitácora
            if ($estado === 'mantenimiento') {
                $equipo->forceFill(['status' => 'mantenimiento', 'updated_at' => now()->subDays(5)])->save();

                StatusHistory::forceCreate([
                    'equipment_id' => $equipo->id,
                    'user_id' => $admin->id,
                    'from_status' => 'disponible',
                    'to_status' => 'mantenimiento',
                    'reason' => 'mantenimiento',
                    'notes' => 'Ingresado a taller de soporte técnico',
                    'created_at' => now()->subDays(5),
                ]);
            }
        }
    }
}