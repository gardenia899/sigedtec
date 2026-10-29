<?php

namespace Tests\Feature;

use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Criterios de aceptación de la Fase 1 (SDD §25.2):
 * sin sesión → login; solicitante fuera de rutas admin → 403.
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/oficinas')->assertRedirect(route('login'));
    }

    public function test_solicitante_cannot_access_admin_routes(): void
    {
        $oficina = Office::factory()->create();

        $this->actingAs(User::factory()->solicitante()->create(['office_id' => $oficina->id]));

        $this->get('/oficinas')->assertForbidden(); // CA-02
    }

    public function test_admin_can_access_office_catalog(): void
    {
        $oficina = Office::factory()->create();

        $this->actingAs(User::factory()->admin()->create(['office_id' => $oficina->id]));

        $this->get('/oficinas')->assertOk();
    }

    public function test_solicitante_can_access_dashboard(): void
    {
        $oficina = Office::factory()->create();

        $this->actingAs(User::factory()->solicitante()->create(['office_id' => $oficina->id]));

        $this->get('/dashboard')->assertOk();
    }

    public function test_admin_dashboard_shows_counters_from_database(): void
    {
        $oficina = Office::factory()->create();

        $this->actingAs(User::factory()->admin()->create(['office_id' => $oficina->id]));

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Equipos disponibles')
            ->assertSee('Solicitudes pendientes');
    }
}
