<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    private function createRole($name)
    {
        return Role::factory()->create(['name' => $name]);
    }

    /** @test */
    public function admin_can_create_short_url()
    {
        $adminRole = $this->createRole('Admin');
        $company = Company::factory()->create();

        $admin = User::factory()->create([
            'role' => $adminRole->id,
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($admin)->post('/shorturls/add', [
            'original_url' => 'https://google.com'
        ]);

        $response->assertStatus(302);

        //$this->assertDatabaseCount('short_urls', 1);
    }

    /** @test */
    public function member_can_create_short_url()
    {
        $memberRole = $this->createRole('Member');
        $company = Company::factory()->create();

        $member = User::factory()->create([
            'role' => $memberRole->id,
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($member)->post('/shorturls/add', [
            'original_url' => 'https://facebook.com'
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function short_url_redirects_correctly()
    {
        $company = Company::factory()->create();
        $role = $this->createRole('Admin');
        $user = User::factory()->create(['role' => $role->id, 'company_id' => $company->id]);

        $short = ShortUrl::create([
            'company_id' => $company->id,
            'created_by' => $user->id,
            'original_url' => 'https://example.com',
            'short_code' => 'abc123'
        ]);

        $response = $this->get('/s/abc123');
        $response->assertRedirect('https://example.com');
    }
}
