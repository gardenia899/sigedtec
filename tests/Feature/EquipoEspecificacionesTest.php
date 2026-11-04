<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Las especificaciones técnicas dependen de la categoría: a una impresora no
 * se le registran procesador, RAM ni almacenamiento.
 */
class EquipoEspecificacionesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $oficina = Office::factory()->create();

        return User::factory()->admin()->create(['office_id' => $oficina->id]);
    }

    public function test_el_formulario_de_alta_carga_las_especificaciones_de_cada_categoria(): void
    {
        Category::factory()->create(['name' => 'impresora', 'spec_fields' => []]);

        $this->actingAs($this->admin())
            ->get('/inventario/crear')
            ->assertOk()
            ->assertSee('Especificaciones técnicas')
            ->assertSee('Impresora');
    }

    public function test_descarta_las_especificaciones_que_no_aplican_a_la_categoria(): void
    {
        $impresora = Category::factory()->create(['name' => 'impresora', 'spec_fields' => []]);
        $laptop = Category::factory()->create(['name' => 'laptop', 'spec_fields' => null]);

        $admin = $this->admin();

        $this->actingAs($admin)->post('/inventario', [
            'patrimonial_code' => 'SIG-IMP-001',
            'category_id' => $impresora->id,
            'brand' => 'Epson',
            'model' => 'L3250',
            'status' => 'disponible',
            'cpu' => 'Intel Core i5',
            'ram_gb' => 8,
            'storage_gb' => 512,
            'storage_type' => 'ssd',
        ])->assertRedirect(route('equipos.index'));

        $this->actingAs($admin)->post('/inventario', [
            'patrimonial_code' => 'SIG-LAP-001',
            'category_id' => $laptop->id,
            'brand' => 'Lenovo',
            'model' => 'ThinkPad E14',
            'status' => 'disponible',
            'cpu' => 'Intel Core i5',
            'ram_gb' => 8,
            'storage_gb' => 512,
            'storage_type' => 'ssd',
        ])->assertRedirect(route('equipos.index'));

        $impresoraRegistrada = Equipment::where('patrimonial_code', 'SIG-IMP-001')->firstOrFail();
        $this->assertNull($impresoraRegistrada->cpu);
        $this->assertNull($impresoraRegistrada->ram_gb);
        $this->assertNull($impresoraRegistrada->storage_gb);
        $this->assertNull($impresoraRegistrada->storage_type);

        $laptopRegistrada = Equipment::where('patrimonial_code', 'SIG-LAP-001')->firstOrFail();
        $this->assertSame('Intel Core i5', $laptopRegistrada->cpu);
        $this->assertSame(8, $laptopRegistrada->ram_gb);
    }
}
