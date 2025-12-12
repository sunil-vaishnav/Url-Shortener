<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    private function createRole($name)
    {
        $this->withoutExceptionHandling();
        return Role::factory()->create(['name' => $name]);
    }

    /** @test */
    public function superadmin_can_view_company_list()
    {
        $this->withoutExceptionHandling();
        $role = $this->createRole('SuperAdmin');
        $superAdmin = User::factory()->create(['role' => $role->id]);

        Company::factory()->count(2)->create();

        $response = $this->actingAs($superAdmin)->get('/companies');
        $response->assertStatus(200);
    }

    /** @test */
    public function superadmin_can_add_company()
    {
        $this->withoutExceptionHandling();

        $role = Role::create(['name' => 'SuperAdmin']);
        $superAdmin = User::factory()->create(['role' => $role->id]);

        $response = $this->actingAs($superAdmin)
                         ->withSession(['_token' => csrf_token()])
                         ->post('/companies/add', [
                             'name' => 'My Company'
                         ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('companies', ['name' => 'My Company']);
    }

    /** @test */
    public function admin_or_member_cannot_access_company_routes()
    {
        $roleAdmin = Role::create(['name' => 'Admin']);
        $admin = User::factory()->create(['role' => $roleAdmin->id]);

        $response = $this->actingAs($admin)->get('/companies');
        $response->assertStatus(403);
    }
}
