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
        // Los visitantes sin sesión son redirigidos al login (RN-01)
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
