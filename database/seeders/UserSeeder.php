<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuarios de prueba con ambos roles (SDD §24).
 *
 * ⚠ Las contraseñas de este seeder son SOLO para desarrollo local
 * y jamás deben usarse en producción (RNF-11, §26.4).
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Administrador — Soporte Técnico
        User::firstOrCreate(
            ['email' => 'admin@institucion.gob.ve'],
            [
                'name' => 'Carlos Soporte Técnico',
                'password' => $password,
                'role' => User::ROLE_ADMIN,
                'office_id' => Office::where('code', 'OFI-01')->firstOrFail()->id,
                'email_verified_at' => now(),
            ]
        );

        // Solicitantes — Jefes de Oficina (uno por oficina)
        $solicitantes = [
            ['email' => 'jefe.rectoria@institucion.gob.ve', 'name' => 'María Jefe de Rectoría', 'oficina' => 'OFI-02'],
            ['email' => 'jefe.secretaria@institucion.gob.ve', 'name' => 'Juan Jefe de Secretaría', 'oficina' => 'OFI-03'],
            ['email' => 'jefe.laboratorio@institucion.gob.ve', 'name' => 'Ana Jefe de Laboratorio', 'oficina' => 'OFI-04'],
            ['email' => 'jefe.sistemas@institucion.gob.ve', 'name' => 'Luis Jefe de Sistemas', 'oficina' => 'OFI-01'],
        ];

        foreach ($solicitantes as $datos) {
            User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'name' => $datos['name'],
                    'password' => $password,
                    'role' => User::ROLE_SOLICITANTE,
                    'office_id' => Office::where('code', $datos['oficina'])->firstOrFail()->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}