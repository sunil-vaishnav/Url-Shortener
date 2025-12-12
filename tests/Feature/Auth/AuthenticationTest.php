<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login()
    {
        $role = Role::factory()->create(['name' => 'Member']);

        $user = User::factory()->create([
            'role' => $role->id,
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
    }
}
