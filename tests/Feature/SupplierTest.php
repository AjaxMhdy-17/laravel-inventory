<?php

namespace App\tests\Features;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{

    use RefreshDatabase;


    public function test_supplier_page_is_loaded_fine()
    {
        $admin = User::factory()->create() ; 
        $this->actingAs($admin) ; 

        $response = $this->get(route('admin.supplier.index'));
        $response->assertStatus(200);
    }


    public function test_supplier_can_be_created()
    {

        $admin = User::factory()->create() ; 
        $this->actingAs($admin) ; 

        $response = $this->post(route('admin.supplier.store'), [
            'name' => 'Test Supplier',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $response->assertRedirect(route('admin.supplier.index'));
        $this->assertDatabaseHas('suppliers', [
            'email' => 'test@example.com',
        ]);
    }
}
