<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // La portada pública presenta el sistema; las rutas privadas siguen exigiendo sesión (RN-01)
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Bienvenido a');
        $response->assertSee('SigedTec');
    }
}
