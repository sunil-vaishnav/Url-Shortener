<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private function createRole($name)
    {
        return Role::factory()->create(['name' => $name]);
    }

    /** @test */
    public function superadmin_can_access_superadmin_routes()
    {
        $role = $this->createRole('SuperAdmin');
        $user = User::factory()->create(['role' => $role->id]);

        $response = $this->actingAs($user)->get('/companies');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_cannot_access_superadmin_routes()
    {
        $this->createRole('SuperAdmin');
        $role = $this->createRole('Admin');

        $user = User::factory()->create(['role' => $role->id]);

        $response = $this->actingAs($user)->get('/companies');
        $response->assertStatus(403);
    }

    /** @test */
    public function member_cannot_access_admin_routes()
    {
        $role = $this->createRole('Member');
        $user = User::factory()->create(['role' => $role->id]);

        $response = $this->actingAs($user)->get('/invite/member');
        $response->assertStatus(403);
    }
}
