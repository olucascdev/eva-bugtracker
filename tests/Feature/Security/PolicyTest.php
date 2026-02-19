<?php

namespace Tests\Feature\Security;

use App\Models\Bug;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        if (Role::count() === 0) {
            Role::create(['name' => 'admin', 'description' => 'Admin']);
            Role::create(['name' => 'support', 'description' => 'Support']);
            Role::create(['name' => 'client-admin', 'description' => 'Client Admin']);
            Role::create(['name' => 'client-user', 'description' => 'Client User']);
        }

        if (\App\Models\BugStatus::count() === 0) {
            \App\Models\BugStatus::create(['name' => 'Open', 'slug' => 'open', 'color' => 'gray']);
            \App\Models\BugStatus::create(['name' => 'Closed', 'slug' => 'closed', 'color' => 'green']);
        }

        if (\App\Models\BugPriority::count() === 0) {
            \App\Models\BugPriority::create(['name' => 'Low', 'slug' => 'low', 'color' => 'gray']);
            \App\Models\BugPriority::create(['name' => 'High', 'slug' => 'high', 'color' => 'red']);
        }
    }

    protected function createUserWithRole(string $roleName, ?Company $company = null): User
    {
        $role = Role::where('name', $roleName)->first();

        return User::factory()->create([
            'role_id' => $role->id,
            'company_id' => $company ? $company->id : Company::factory()->create()->id,
        ]);
    }

    public function test_admin_can_access_everything()
    {
        $admin = $this->createUserWithRole('admin');
        $otherCompany = Company::factory()->create();
        $otherUser = $this->createUserWithRole('client-user', $otherCompany);
        $otherBug = Bug::factory()->create(['company_id' => $otherCompany->id, 'reported_by_user_id' => $otherUser->id]);

        $this->assertTrue($admin->can('viewAny', User::class));
        $this->assertTrue($admin->can('view', $otherUser));
        $this->assertTrue($admin->can('create', User::class));
        $this->assertTrue($admin->can('update', $otherUser));
        $this->assertTrue($admin->can('delete', $otherUser));

        $this->assertTrue($admin->can('viewAny', Bug::class));
        $this->assertTrue($admin->can('view', $otherBug));
        $this->assertTrue($admin->can('create', Bug::class));
        $this->assertTrue($admin->can('update', $otherBug));
        $this->assertTrue($admin->can('delete', $otherBug));
    }

    public function test_client_admin_can_manage_own_company_users()
    {
        $company = Company::factory()->create();
        $clientAdmin = $this->createUserWithRole('client-admin', $company);
        $ownUser = $this->createUserWithRole('client-user', $company);

        $otherCompany = Company::factory()->create();
        $otherUser = $this->createUserWithRole('client-user', $otherCompany);

        // Can view/manage own user
        $this->assertTrue($clientAdmin->can('view', $ownUser));
        $this->assertTrue($clientAdmin->can('update', $ownUser));
        $this->assertTrue($clientAdmin->can('delete', $ownUser));

        // Cannot view/manage other company user
        $this->assertFalse($clientAdmin->can('view', $otherUser));
        $this->assertFalse($clientAdmin->can('update', $otherUser));
        $this->assertFalse($clientAdmin->can('delete', $otherUser));
    }

    public function test_client_user_cannot_manage_users()
    {
        $company = Company::factory()->create();
        $clientUser = $this->createUserWithRole('client-user', $company);
        $ownUser = $this->createUserWithRole('client-user', $company);

        $this->assertFalse($clientUser->can('viewAny', User::class));
        $this->assertFalse($clientUser->can('view', $ownUser));
        $this->assertFalse($clientUser->can('create', User::class));
        $this->assertFalse($clientUser->can('update', $ownUser));
        $this->assertFalse($clientUser->can('delete', $ownUser));
    }

    public function test_client_can_view_only_own_bugs()
    {
        $company = Company::factory()->create();
        $clientUser = $this->createUserWithRole('client-user', $company);

        $ownBug = Bug::factory()->create([
            'company_id' => $company->id,
            'reported_by_user_id' => $clientUser->id,
            'title' => 'Own Bug',
            'description' => 'Own Bug Description',
        ]);

        $otherCompany = Company::factory()->create();
        $otherUser = $this->createUserWithRole('client-user', $otherCompany);
        $otherBug = Bug::factory()->create([
            'company_id' => $otherCompany->id,
            'reported_by_user_id' => $otherUser->id,
            'title' => 'Other Bug',
            'description' => 'Other Bug Description',
        ]);

        $this->assertTrue($clientUser->can('view', $ownBug));
        $this->assertFalse($clientUser->can('view', $otherBug));
    }
}
