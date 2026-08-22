<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_complainant_dashboard(): void
    {
        $response = $this->get('/complainant/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_complainant_cannot_access_admin_dashboard(): void
    {
        $complainant = User::factory()->create();

        $response = $this->actingAs($complainant)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_complainant_cannot_access_superadmin_area(): void
    {
        $complainant = User::factory()->create();

        $response = $this->actingAs($complainant)->get('/superadmin/users');

        $response->assertForbidden();
    }

    public function test_admin_cannot_access_superadmin_area(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/superadmin/users');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_superadmin_can_access_superadmin_area(): void
    {
        $superadmin = User::factory()->superadmin()->create();

        $response = $this->actingAs($superadmin)->get('/superadmin/users');

        $response->assertOk();
    }

    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
