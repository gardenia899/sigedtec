<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\Office;
use App\Models\RequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevolucionTest extends TestCase
{
    use RefreshDatabase;

    private function asignacion(): array
    {
        $office = Office::factory()->create();
        $requester = User::factory()->solicitante()->create(['office_id' => $office->id]);
        $admin = User::factory()->admin()->create(['office_id' => $office->id]);
        $equipment = Equipment::factory()->asignado()->create();
        $request = EquipmentRequest::factory()->create([
            'office_id' => $office->id,
            'user_id' => $requester->id,
            'category_id' => $equipment->category_id,
        ]);
        $request->forceFill(['status' => 'aprobada'])->save();
        $item = RequestItem::factory()->create([
            'request_id' => $request->id,
            'equipment_id' => $equipment->id,
            'assigned_by' => $admin->id,
        ]);

        return [$requester, $admin, $request, $equipment, $item];
    }

    public function test_solicitante_puede_solicitar_devolucion_sin_liberar_el_equipo(): void
    {
        [$requester, , $request, $equipment, $item] = $this->asignacion();

        $this->actingAs($requester)
            ->get(route('solicitudes.show', $request))
            ->assertOk()
            ->assertSee('Solicitar devolución');

        $this->actingAs($requester)
            ->post(route('solicitudes.equipos.devolucion', [$request, $item]), [
                'observations' => 'Equipo listo para revisión.',
            ])
            ->assertRedirect(route('solicitudes.show', $request))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('request_items', [
            'id' => $item->id,
            'return_requested_by' => $requester->id,
            'return_request_observations' => 'Equipo listo para revisión.',
            'returned_at' => null,
        ]);
        $this->assertNotNull($item->fresh()->return_requested_at);
        $this->assertSame('asignado', $equipment->fresh()->status);
    }

    public function test_solicitante_no_puede_solicitar_devolucion_de_equipo_ajeno(): void
    {
        [$requester, , $request, $equipment, $item] = $this->asignacion();
        $otherOffice = Office::factory()->create();
        $other = User::factory()->solicitante()->create(['office_id' => $otherOffice->id]);

        $this->actingAs($other)
            ->post(route('solicitudes.equipos.devolucion', [$request, $item]))
            ->assertNotFound();

        $this->actingAs($requester)
            ->post(route('solicitudes.equipos.devolucion', [$request, RequestItem::factory()->create()->id]))
            ->assertNotFound();

        $this->assertNull($item->fresh()->return_requested_at);
        $this->assertSame('asignado', $equipment->fresh()->status);
    }

    public function test_admin_confirma_devolucion_operativa_y_registra_historial(): void
    {
        [$requester, $admin, $request, $equipment, $item] = $this->asignacion();
        $item->update(['return_requested_at' => now(), 'return_requested_by' => $requester->id]);

        $this->actingAs($admin)
            ->get(route('admin.devoluciones.index'))
            ->assertOk()
            ->assertSee($equipment->patrimonial_code)
            ->assertSee('Confirmar devolución');

        $this->actingAs($admin)
            ->post(route('admin.devoluciones.confirmar', $item), [
                'return_condition' => 'operativo',
                'return_observations' => 'Revisado y funcional.',
            ])
            ->assertRedirect(route('admin.devoluciones.index'))
            ->assertSessionHas('success');

        $this->assertSame('disponible', $equipment->fresh()->status);
        $this->assertNotNull($item->fresh()->returned_at);
        $this->assertSame('operativo', $item->fresh()->return_condition);
        $this->assertDatabaseHas('status_history', [
            'equipment_id' => $equipment->id,
            'request_id' => $request->id,
            'user_id' => $admin->id,
            'from_status' => 'asignado',
            'to_status' => 'disponible',
            'reason' => 'devolucion',
            'notes' => 'Revisado y funcional.',
        ]);
    }

    public function test_admin_confirma_devolucion_que_requiere_mantenimiento(): void
    {
        [$requester, $admin, , $equipment, $item] = $this->asignacion();
        $item->update(['return_requested_at' => now(), 'return_requested_by' => $requester->id]);

        $this->actingAs($admin)
            ->post(route('admin.devoluciones.confirmar', $item), [
                'return_condition' => 'requiere_mantenimiento',
            ])
            ->assertRedirect(route('admin.devoluciones.index'));

        $this->assertSame('mantenimiento', $equipment->fresh()->status);
        $this->assertSame('requiere_mantenimiento', $item->fresh()->return_condition);
        $this->assertDatabaseHas('status_history', [
            'equipment_id' => $equipment->id,
            'user_id' => $admin->id,
            'from_status' => 'asignado',
            'to_status' => 'mantenimiento',
            'reason' => 'devolucion',
        ]);
    }

    public function test_solicitante_no_puede_confirmar_devolucion_ni_enviar_dos_solicitudes(): void
    {
        [$requester, , $request, $equipment, $item] = $this->asignacion();

        $this->actingAs($requester)
            ->post(route('solicitudes.equipos.devolucion', [$request, $item]))
            ->assertRedirect(route('solicitudes.show', $request));

        $this->actingAs($requester)
            ->post(route('admin.devoluciones.confirmar', $item), ['return_condition' => 'operativo'])
            ->assertForbidden();

        $this->actingAs($requester)
            ->post(route('solicitudes.equipos.devolucion', [$request, $item]))
            ->assertRedirect(route('solicitudes.show', $request))
            ->assertSessionHas('error');

        $this->assertSame('asignado', $equipment->fresh()->status);
        $this->assertNull($item->fresh()->returned_at);
    }

    public function test_usuario_sin_sesion_no_puede_solicitar_devolucion(): void
    {
        [, , $request, , $item] = $this->asignacion();

        $this->post(route('solicitudes.equipos.devolucion', [$request, $item]))
            ->assertRedirect(route('login'));
    }
}
