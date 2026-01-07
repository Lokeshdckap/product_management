<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a guest accessing '/' is redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        // Expecting redirect (302) to login
        $response->assertStatus(302);
        $response->assertRedirect('/login'); // Changed from '/admin/login'
    }

    /**
     * Test that an authenticated admin can access the dashboard.
     */
    public function test_authenticated_admin_can_access_dashboard(): void
    {
        // Create a test admin using factory
        $admin = Admin::factory()->create();

        // Act as this admin using the 'admin' guard
        $response = $this->actingAs($admin, 'admin')
                         ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admins'); // check that the page content includes "Admins"
    }
}