<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    private function createRole($name)
    {
        return Role::factory()->create(['name' => $name]);
    }

    /** @test */
    public function superadmin_can_invite_admin()
    {
        $this->withoutExceptionHandling();

        $superRole = $this->createRole('SuperAdmin');
        $adminRole = $this->createRole('Admin');

        $super = User::factory()->create(['role' => $superRole->id]);
        $company = Company::factory()->create();

        $response = $this->actingAs($super)->withSession(['_token' => csrf_token()])->post('/invite/admin', [
            'company_id' => $company->id,
            'name' => 'Admin User',
            'email' => 'admin@example.com'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => $adminRole->id
        ]);
    }

    /** @test */
    public function admin_can_invite_member()
    {
        $this->withoutExceptionHandling();

        $adminRole = $this->createRole('Admin');
        $memberRole = $this->createRole('Member');

        $company = Company::factory()->create();

        $admin = User::factory()->create([
            'role' => $adminRole->id,
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($admin)->withSession(['_token' => csrf_token()])->post('/invite/member', [
            'name' => 'Member User',
            'email' => 'member@example.com'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'Member User',
            'email' => 'member@example.com',
            'role' => 3
        ]);
    }

    /** @test */
    public function member_cannot_access_invitations()
    {
        $memberRole = $this->createRole('Member');
        $member = User::factory()->create(['role' => $memberRole->id]);

        $response = $this->actingAs($member)->get('/invitations');
        //$response->assertRedirect('/login');
        $response->assertStatus(403);
    }

    /** @test */
    public function validation_works_for_admin_invite()
    {
        $superRole = $this->createRole('SuperAdmin');
        $super = User::factory()->create(['role' => $superRole->id]);

        $response = $this->actingAs($super)->post('/invite/admin', [
            'name' => '',
            'email' => '',
            'company_id' => ''
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'company_id']);
    }
}
