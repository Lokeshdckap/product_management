<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    public function an_admin_can_create_a_product()
    {

        $admin = Admin::factory()->create();

        $category = Category::factory()->create();

        $this->actingAs($admin, 'admin') 
            ->post('/admin/products', [
                'name' => 'Test Product',
                'description' => 'This is a test product',
                'price' => 99.99,
                'stock' => 10,
                'category_id' => $category->id,
            ])
            ->assertRedirect('/admin/products'); 

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
        ]);
    }
}
