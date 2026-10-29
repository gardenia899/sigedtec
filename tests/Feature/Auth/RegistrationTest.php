<?php

namespace Tests\Feature\Auth;

use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $oficina = Office::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'office_id' => $oficina->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        // RF-02: el rol se fuerza en servidor; el campo nunca se envía en el formulario
        $this->assertSame(User::ROLE_SOLICITANTE, User::first()->role);
        $this->assertSame($oficina->id, User::first()->office_id);
    }

    public function test_registration_requires_an_active_office(): void
    {
        $oficina = Office::factory()->inactiva()->create();

        $response = $this->post('/register', [
            'name' => 'Sin Oficina',
            'email' => 'sinoficina@institucion.gob.ve',
            'password' => 'password',
            'password_confirmation' => 'password',
            'office_id' => $oficina->id,
        ]);

        $response->assertSessionHasErrors('office_id');
        $this->assertGuest();
    }
}
