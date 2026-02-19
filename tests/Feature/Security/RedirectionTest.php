<?php

namespace Tests\Feature\Security;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (Role::count() === 0) {
            Role::create(['name' => 'admin', 'description' => 'Admin']);
            Role::create(['name' => 'support', 'description' => 'Support']);
            Role::create(['name' => 'client-admin', 'description' => 'Client Admin']);
            Role::create(['name' => 'client-user', 'description' => 'Client User']);
        }
    }

    public function test_client_is_redirected_from_eva_to_client_panel()
    {
        $role = Role::where('name', 'client-user')->first();
        $client = User::factory()->create([
            'role_id' => $role->id,
            'company_id' => Company::factory()->create()->id,
        ]);

        $response = $this->actingAs($client)->get('/eva');

        $response->assertRedirect('/client');
    }

    public function test_client_admin_is_redirected_from_eva_to_client_panel()
    {
        $role = Role::where('name', 'client-admin')->first();
        $clientAdmin = User::factory()->create([
            'role_id' => $role->id,
            'company_id' => Company::factory()->create()->id,
        ]);

        $response = $this->actingAs($clientAdmin)->get('/eva');

        $response->assertRedirect('/client');
    }

    public function test_admin_is_redirected_from_client_to_eva_panel()
    {
        $role = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $role->id,
            'company_id' => Company::factory()->create()->id,
        ]);

        $response = $this->actingAs($admin)->get('/client');

        $response->assertRedirect('/eva');
    }

    public function test_admin_can_access_eva_panel()
    {
        $role = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $role->id,
            'company_id' => Company::factory()->create()->id,
        ]);

        $response = $this->actingAs($admin)->get('/eva');

        $response->assertStatus(200);
    }
}
