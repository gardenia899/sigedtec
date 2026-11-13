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

    public function test_editar_un_equipo_conserva_su_codigo_y_registra_el_cambio_de_estado(): void
    {
        $admin = $this->admin();
        $equipo = Equipment::factory()->create(['status' => 'mantenimiento']);

        $this->actingAs($admin)->put('/inventario/'.$equipo->id, [
            'patrimonial_code' => 'SIG-9999-9999',
            'category_id' => $equipo->category_id,
            'brand' => 'Marca editada',
            'model' => $equipo->model,
            'serial_number' => $equipo->serial_number,
            'status' => 'disponible',
        ])->assertRedirect(route('equipos.index'));

        $this->assertSame('Marca editada', $equipo->fresh()->brand);
        $this->assertSame($equipo->patrimonial_code, $equipo->fresh()->patrimonial_code);
        $this->assertSame('disponible', $equipo->fresh()->status);
        $this->assertDatabaseHas('status_history', [
            'equipment_id' => $equipo->id,
            'from_status' => 'mantenimiento',
            'to_status' => 'disponible',
            'reason' => 'edicion_manual',
        ]);
    }

    public function test_codigo_patrimonial_se_genera_al_guardar_y_no_se_consumen_codigos_si_falla_validacion(): void
    {
        $admin = $this->admin();
        $categoria = Category::factory()->create();

        $this->actingAs($admin)->get('/inventario')
            ->assertOk()
            ->assertSee(sprintf('SIG-%d-0001', now()->year))
            ->assertDontSee('name="patrimonial_code"', false);

        $this->actingAs($admin)->post('/inventario', [
            'category_id' => $categoria->id,
            'brand' => '',
            'model' => 'ProBook 450',
            'status' => 'disponible',
            'ram_gb' => '8x',
        ])->assertSessionHasErrors(['brand', 'ram_gb']);

        $this->assertDatabaseMissing('equipment_code_sequences', ['year' => now()->year]);

        $this->actingAs($admin)->post('/inventario', [
            'patrimonial_code' => 'CODIGO-ENVIADO-POR-USUARIO',
            'category_id' => $categoria->id,
            'brand' => 'HP',
            'model' => 'ProBook 450',
            'status' => 'disponible',
        ])->assertRedirect(route('equipos.index'));

        $this->assertDatabaseHas('equipment', [
            'patrimonial_code' => sprintf('SIG-%d-0001', now()->year),
        ]);
        $this->assertDatabaseHas('equipment_code_sequences', [
            'year' => now()->year,
            'last_number' => 1,
        ]);
    }

    public function test_codigo_de_equipo_es_solo_lectura_y_el_modal_tiene_un_solo_control_de_cierre(): void
    {
        $admin = $this->admin();
        Category::factory()->create();

        $this->actingAs($admin)
            ->get('/inventario?crear=1')
            ->assertOk()
            ->assertSee('readonly', false)
            ->assertDontSee('name="patrimonial_code"', false)
            ->assertSee('Cancelar')
            ->assertDontSee('aria-label="Cerrar modal"', false)
            ->assertDontSee('Cambiar</button>', false);
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

    public function test_cambiar_estado_sin_notas_usa_un_motivo_predeterminado(): void
    {
        $admin = $this->admin();
        $equipo = Equipment::factory()->create(['status' => 'disponible']);

        $this->actingAs($admin)->patch('/inventario/'.$equipo->id.'/estado', [
            'status' => 'mantenimiento',
        ])->assertRedirect(route('equipos.index'));

        $this->assertDatabaseHas('status_history', [
            'equipment_id' => $equipo->id,
            'to_status' => 'mantenimiento',
            'notes' => 'Cambio manual de estado',
        ]);
    }

    public function test_el_cambio_manual_no_puede_marcar_un_equipo_como_asignado(): void
    {
        $admin = $this->admin();
        $categoria = Category::factory()->create();
        $equipo = Equipment::factory()->create([
            'category_id' => $categoria->id,
            'status' => 'disponible',
        ]);

        $this->actingAs($admin)->patch('/inventario/'.$equipo->id.'/estado', [
            'status' => 'asignado',
        ])->assertSessionHasErrors('status');

        $this->assertSame('disponible', $equipo->fresh()->status);

        $this->actingAs($admin)->post('/inventario', [
            'category_id' => $categoria->id,
            'brand' => 'HP',
            'model' => 'ProBook',
            'status' => 'asignado',
        ])->assertSessionHasErrors('status');

        $asignado = Equipment::factory()->create([
            'category_id' => $categoria->id,
            'status' => 'asignado',
        ]);

        $this->actingAs($admin)->put('/inventario/'.$asignado->id, [
            'category_id' => $categoria->id,
            'brand' => $asignado->brand,
            'model' => $asignado->model,
            'status' => 'disponible',
        ])->assertSessionHasErrors('status');

        $this->assertSame('asignado', $asignado->fresh()->status);
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
            'code' => 'CODIGO-IGNORADO',
            'name' => 'Oficina de prueba',
        ])->assertRedirect(route('oficinas.index'));

        $oficina = Office::where('name', 'Oficina de prueba')->firstOrFail();
        $codigoOriginal = $oficina->code;
        $this->assertMatchesRegularExpression('/^OFI-\d{6}$/', $codigoOriginal);

        $this->actingAs($admin)->put('/oficinas/'.$oficina->id, [
            'code' => 'CODIGO-CAMBIADO',
            'name' => 'Oficina de prueba',
            'location' => 'Piso 2',
            'is_active' => '1',
        ])->assertRedirect(route('oficinas.index'));

        $this->assertSame('Piso 2', $oficina->fresh()->location);
        $this->assertSame($codigoOriginal, $oficina->fresh()->code);
    }

    public function test_codigo_de_oficina_se_muestra_solo_lectura_al_crear(): void
    {
        $this->actingAs($this->admin())
            ->get('/oficinas/crear')
            ->assertOk()
            ->assertSee('readonly', false)
            ->assertDontSee('name="code"', false);
    }

    public function test_nombres_rechazan_numeros_y_codigo_de_oficina_no_acepta_valores_del_cliente(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/oficinas', [
            'code' => 'OFI-A12',
            'name' => 'Oficina 2',
        ])->assertSessionHasErrors('name');

        $this->actingAs($admin)->post('/categorias', [
            'name' => 'Categoria 2',
        ])->assertSessionHasErrors('name');

        $this->actingAs($admin)->post('/oficinas', [
            'code' => 'OFI-A12',
            'name' => 'Oficina Central',
        ])->assertRedirect(route('oficinas.index'));

        $oficina = Office::where('name', 'Oficina Central')->firstOrFail();
        $this->assertNotSame('OFI-A12', $oficina->code);
        $this->assertMatchesRegularExpression('/^OFI-\d{6}$/', $oficina->code);
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

    public function test_formulario_de_aprobacion_muestra_solo_equipos_disponibles_de_la_categoria(): void
    {
        $admin = $this->admin();
        $categoria = Category::factory()->create();
        $otraCategoria = Category::factory()->create();
        $solicitud = EquipmentRequest::factory()->create([
            'office_id' => $admin->office_id,
            'category_id' => $categoria->id,
            'quantity' => 1,
        ]);

        Equipment::factory()->create([
            'patrimonial_code' => 'SIG-AVAILABLE-01',
            'category_id' => $categoria->id,
            'status' => 'disponible',
        ]);
        Equipment::factory()->create([
            'patrimonial_code' => 'SIG-OTHER-CATEGORY',
            'category_id' => $otraCategoria->id,
            'status' => 'disponible',
        ]);
        Equipment::factory()->create([
            'patrimonial_code' => 'SIG-MAINTENANCE',
            'category_id' => $categoria->id,
            'status' => 'mantenimiento',
        ]);

        $this->actingAs($admin)
            ->get('/admin/solicitudes/'.$solicitud->id.'/aprobar')
            ->assertOk()
            ->assertSee('SIG-AVAILABLE-01')
            ->assertDontSee('SIG-OTHER-CATEGORY')
            ->assertDontSee('SIG-MAINTENANCE');
    }

    public function test_cada_reporte_tiene_exportacion_pdf_completa(): void
    {
        $admin = $this->admin();
        $office = Office::factory()->create();
        $solicitante = User::factory()->solicitante()->create(['office_id' => $office->id]);
        $categoria = Category::factory()->create();
        $solicitud = EquipmentRequest::factory()->create([
            'office_id' => $office->id,
            'user_id' => $solicitante->id,
            'category_id' => $categoria->id,
            'quantity' => 1,
            'status' => 'aprobada',
        ]);
        $equipo = Equipment::factory()->create([
            'category_id' => $categoria->id,
            'status' => 'asignado',
        ]);
        RequestItem::factory()->create([
            'request_id' => $solicitud->id,
            'equipment_id' => $equipo->id,
            'assigned_by' => $admin->id,
        ]);
        StatusHistory::create([
            'equipment_id' => $equipo->id,
            'request_id' => $solicitud->id,
            'user_id' => $admin->id,
            'from_status' => 'disponible',
            'to_status' => 'asignado',
            'reason' => 'asignacion',
            'notes' => 'Prueba de exportación',
        ]);

        foreach ([
            '/reportes/inventario/pdf',
            '/reportes/solicitudes/pdf',
            '/reportes/asignaciones/pdf',
            '/reportes/movimientos/pdf',
        ] as $uri) {
            $response = $this->actingAs($admin)->get($uri);

            $response->assertOk()
                ->assertHeader('content-type', 'application/pdf');
            $this->assertStringStartsWith('%PDF-', $response->getContent());
        }
    }

    public function test_notificaciones_de_solicitudes_y_navegacion_del_solicitante_son_funcionales(): void
    {
        $oficina = Office::factory()->create();
        $solicitante = User::factory()->solicitante()->create(['office_id' => $oficina->id]);
        $categoria = Category::factory()->create();
        EquipmentRequest::factory()->create([
            'office_id' => $oficina->id,
            'user_id' => $solicitante->id,
            'category_id' => $categoria->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($solicitante)->get('/dashboard')->assertOk();
        $response->assertSee('Mis solicitudes recientes')
            ->assertSee('Solicitudes')
            ->assertDontSee('nav-item-active">Nueva solicitud', false);

        $this->assertSame(1, substr_count($response->getContent(), 'Nueva solicitud'));

        $admin = $this->admin();
        $pendiente = EquipmentRequest::factory()->create([
            'office_id' => $admin->office_id,
            'category_id' => $categoria->id,
            'quantity' => 1,
        ]);

        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Solicitudes pendientes')
            ->assertSee(route('admin.solicitudes.form', $pendiente), false);
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
