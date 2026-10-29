<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\Office;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Solicitudes en distintos estados con asignaciones coherentes (SDD §24):
 * 2 aprobadas con sus 6 equipos asignados, 1 pendiente y 1 rechazada.
 */
class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::deRol(User::ROLE_ADMIN)->firstOrFail();
        $categorias = Category::pluck('id', 'name');
        $oficinas = Office::pluck('id', 'code');

        $jefeRectoria = User::where('email', 'jefe.rectoria@institucion.gob.ve')->firstOrFail();
        $jefeSecretaria = User::where('email', 'jefe.secretaria@institucion.gob.ve')->firstOrFail();
        $jefeLaboratorio = User::where('email', 'jefe.laboratorio@institucion.gob.ve')->firstOrFail();

        // ── 1. Solicitud aprobada: 3 laptops para Rectoría ────────────────
        $aprobada1 = $this->crear([
            'office_id' => $oficinas['OFI-02'],
            'user_id' => $jefeRectoria->id,
            'category_id' => $categorias['Laptop'],
            'quantity' => 3,
            'justification' => 'Se requieren equipos para el equipo directivo en la reestructuración de oficinas.',
        ], $admin, now()->subDays(20), ['status' => 'aprobada']);

        $this->asignar($aprobada1, ['SIG-2026-0001', 'SIG-2026-0002', 'SIG-2026-0003'], $admin, now()->subDays(20));

        // ── 2. Solicitud aprobada: 3 monitores para Secretaría ────────────
        $aprobada2 = $this->crear([
            'office_id' => $oficinas['OFI-03'],
            'user_id' => $jefeSecretaria->id,
            'category_id' => $categorias['Monitor'],
            'quantity' => 3,
            'justification' => 'Sustitución de pantallas obsoletas en las mesas de atención al público.',
        ], $admin, now()->subDays(12), ['status' => 'aprobada']);

        $this->asignar($aprobada2, ['SIG-2026-0008', 'SIG-2026-0009', 'SIG-2026-0010'], $admin, now()->subDays(12));

        // ── 3. Solicitud pendiente (esperando decisión del administrador) ──
        $this->crear([
            'office_id' => $oficinas['OFI-04'],
            'user_id' => $jefeLaboratorio->id,
            'category_id' => $categorias['Impresora'],
            'quantity' => 2,
            'justification' => 'Impresión de guías de laboratorio para el período académico en curso.',
        ], $admin, now()->subDays(2));

        // ── 4. Solicitud rechazada con observación (RN-18) ────────────────
        $this->crear([
            'office_id' => $oficinas['OFI-04'],
            'user_id' => $jefeLaboratorio->id,
            'category_id' => $categorias['Laptop'],
            'quantity' => 5,
            'justification' => 'Equipos para el nuevo taller de robótica.',
        ], $admin, now()->subDays(5), [
            'status' => 'rechazada',
            'admin_observation' => 'Solo hay 3 laptops disponibles; reduzca la cantidad o espere la próxima compra institucional.',
        ]);
    }

    /**
     * Crea una solicitud con fechas coherentes y, si se indica, su decisión
     * (los campos de decisión nunca se envían desde el cliente: SDD §21.5).
     *
     * @param  array<string, mixed>  $extras
     */
    private function crear(array $atributos, User $admin, $fecha, array $extras = []): EquipmentRequest
    {
        $solicitud = EquipmentRequest::forceCreate($atributos);

        $solicitud->forceFill([
            'created_at' => $fecha->copy()->subDays(3),
            'updated_at' => $fecha,
        ])->save();

        if (isset($extras['status'])) {
            $campos = [
                'status' => $extras['status'],
                'processed_by' => $admin->id,
                'processed_at' => $fecha,
                'updated_at' => $fecha,
            ];

            if ($extras['status'] === 'rechazada') {
                $campos['admin_observation'] = $extras['admin_observation']; // RN-18
            }

            $solicitud->forceFill($campos)->save();
        }

        return $solicitud;
    }

    /**
     * Registra el despacho exacto: N equipos elegibles → asignados (RN-15..RN-19).
     *
     * @param  list<string>  $codigos
     */
    private function asignar(EquipmentRequest $solicitud, array $codigos, User $admin, $fecha): void
    {
        foreach ($codigos as $codigo) {
            $equipo = Equipment::where('patrimonial_code', $codigo)->firstOrFail();

            $equipo->forceFill(['status' => 'asignado', 'updated_at' => $fecha])->save();

            RequestItem::create([
                'request_id' => $solicitud->id,
                'equipment_id' => $equipo->id,
                'assigned_by' => $admin->id,
                'status_before' => 'disponible',
                'status_after' => 'asignado',
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);

            StatusHistory::forceCreate([
                'equipment_id' => $equipo->id,
                'request_id' => $solicitud->id,
                'user_id' => $admin->id,
                'from_status' => 'disponible',
                'to_status' => 'asignado',
                'reason' => 'asignacion',
                'notes' => 'Despacho aprobado por Soporte Técnico',
                'created_at' => $fecha,
            ]);
        }
    }
}