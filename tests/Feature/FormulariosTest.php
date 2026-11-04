<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\Office;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Los formularios se validan con Form Requests por módulo y los campos de
 * decisión (rol, estado de la solicitud, auditoría) los fija el servidor.
 */
class FormulariosTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $oficina = Office::factory()->create();

        return User::factory()->admin()->create(['office_id' => $oficina->id]);
    }

    public function test_el_alta_rechaza_una_categoria_inactiva(): void
    {
        $categoria = Category::factory()->inactiva()->create();

        $this->actingAs($this->admin())->post('/inventario', [
            'patrimonial_code' => 'SIG-T-001',
            'category_id' => $categoria->id,
            'brand' => 'HP',
            'model' => 'ProBook 450',
            'status' => 'disponible',
        ])->assertSessionHasErrors('category_id');
    }

    public function test_editar_un_equipo_no_choca_con_su_propio_codigo(): void
    {
        $admin = $this->admin();
        $equipo = Equipment::factory()->create(['status' => 'mantenimiento']);

        $this->actingAs($admin)->put('/inventario/'.$equipo->id, [
            'patrimonial_code' => $equipo->patrimonial_code,
            'category_id' => $equipo->category_id,
            'brand' => 'Marca editada',
            'model' => $equipo->model,
            'serial_number' => $equipo->serial_number,
            'status' => 'disponible',
        ])->assertRedirect(route('equipos.index'));

        $this->assertSame('Marca editada', $equipo->fresh()->brand);
        // El estado solo cambia desde el listado, para que quede historial.
        $this->assertSame('mantenimiento', $equipo->fresh()->status);
    }

    public function test_cambiar_estado_deja_historial(): void
    {
        $admin = $this->admin();
        $equipo = Equipment::factory()->create(['status' => 'disponible']);

        $this->actingAs($admin)->patch('/inventario/'.$equipo->id.'/estado', [
            'status' => 'mantenimiento',
            'notes' => 'Falla de pantalla',
        ])->assertRedirect();

        $this->assertSame('mantenimiento', $equipo->fresh()->status);
        $this->assertTrue(StatusHistory::where('equipment_id', $equipo->id)->where('to_status', 'mantenimiento')->exists());
    }

    public function test_categoria_se_actualiza_con_su_mismo_nombre(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/categorias', [
            'name' => 'Servidores',
            'description' => 'Descripción inicial',
            'spec_fields' => ['cpu', 'ram_gb'],
        ])->assertRedirect(route('categorias.index'));

        $categoria = Category::where('name', 'servidores')->firstOrFail();

        $this->actingAs($admin)->put('/categorias/'.$categoria->id, [
            'name' => 'Servidores',
            'description' => 'Descripción nueva',
            'is_active' => '1',
        ])->assertRedirect(route('categorias.index'));

        $this->assertSame('Descripción nueva', $categoria->fresh()->description);
    }

    public function test_oficina_se_actualiza_con_su_mismo_codigo(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/oficinas', [
            'code' => 'OFI-T',
            'name' => 'Oficina de prueba',
        ])->assertRedirect(route('oficinas.index'));

        $oficina = Office::where('code', 'OFI-T')->firstOrFail();

        $this->actingAs($admin)->put('/oficinas/'.$oficina->id, [
            'code' => 'OFI-T',
            'name' => 'Oficina de prueba',
            'location' => 'Piso 2',
            'is_active' => '1',
        ])->assertRedirect(route('oficinas.index'));

        $this->assertSame('Piso 2', $oficina->fresh()->location);
    }

    public function test_cambio_de_rol_y_bloqueo_del_propio_rol(): void
    {
        $admin = $this->admin();
        $otro = User::factory()->solicitante()->create(['office_id' => $admin->office_id]);

        $this->actingAs($admin)->put('/usuarios/'.$otro->id, ['role' => 'admin'])
            ->assertRedirect(route('usuarios.index'));
        $this->assertSame('admin', $otro->fresh()->role);

        $this->actingAs($admin)->put('/usuarios/'.$admin->id, ['role' => 'solicitante'])
            ->assertSessionHas('error', 'No puedes cambiar tu propio rol.');
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_solicitante_registra_una_solicitud(): void
    {
        $oficina = Office::factory()->create();
        $solicitante = User::factory()->solicitante()->create(['office_id' => $oficina->id]);
        $categoria = Category::factory()->create();

        $this->actingAs($solicitante)->post('/solicitudes', [
            'category_id' => $categoria->id,
            'quantity' => 2,
            'justification' => 'Reemplazo de equipos con fallas',
        ])->assertRedirect(route('solicitudes.index'));

        $this->assertDatabaseHas('equipment_requests', [
            'user_id' => $solicitante->id,
            'quantity' => 2,
            'status' => 'pendiente',
        ]);
    }

    public function test_solicitante_cancela_su_solicitud(): void
    {
        $oficina = Office::factory()->create();
        $solicitante = User::factory()->solicitante()->create(['office_id' => $oficina->id]);
        $solicitud = EquipmentRequest::factory()->create([
            'user_id' => $solicitante->id,
            'office_id' => $oficina->id,
            'quantity' => 1,
        ]);

        $this->actingAs($solicitante)
            ->post('/solicitudes/'.$solicitud->id.'/cancelar')
            ->assertRedirect(route('solicitudes.index'));

        $this->assertSame('cancelada', $solicitud->fresh()->status);
    }

    public function test_aprobar_exige_exactamente_la_cantidad_solicitada(): void
    {
        $admin = $this->admin();
        $categoria = Category::factory()->create();
        $solicitud = EquipmentRequest::factory()->create([
            'office_id' => $admin->office_id,
            'category_id' => $categoria->id,
            'quantity' => 2,
        ]);
        $equipo = Equipment::factory()->create(['category_id' => $categoria->id, 'status' => 'disponible']);

        $this->actingAs($admin)->post('/admin/solicitudes/'.$solicitud->id.'/aprobar', [
            'equipos' => [$equipo->id],
        ])->assertSessionHasErrors('equipos');

        $segundo = Equipment::factory()->create(['category_id' => $categoria->id, 'status' => 'disponible']);

        $this->actingAs($admin)->post('/admin/solicitudes/'.$solicitud->id.'/aprobar', [
            'equipos' => [$equipo->id, $segundo->id],
        ])->assertRedirect(route('admin.solicitudes.index'));

        $this->assertSame('aprobada', $solicitud->fresh()->status);
        $this->assertSame(2, RequestItem::where('request_id', $solicitud->id)->count());
        $this->assertSame('asignado', $equipo->fresh()->status);
    }

    public function test_rechazo_exige_motivo_suficiente(): void
    {
        $admin = $this->admin();
        $solicitud = EquipmentRequest::factory()->create(['office_id' => $admin->office_id, 'quantity' => 1]);

        $this->actingAs($admin)->post('/admin/solicitudes/'.$solicitud->id.'/rechazar', [
            'admin_observation' => 'No',
        ])->assertSessionHasErrors('admin_observation');

        $this->actingAs($admin)->post('/admin/solicitudes/'.$solicitud->id.'/rechazar', [
            'admin_observation' => 'Sin stock disponible este mes.',
        ])->assertRedirect(route('admin.solicitudes.index'));

        $this->assertSame('rechazada', $solicitud->fresh()->status);
    }
}
