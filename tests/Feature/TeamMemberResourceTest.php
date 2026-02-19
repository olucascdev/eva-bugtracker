<?php

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Filament\Client\Resources\TeamMemberResource\Pages\ManageTeamMembers;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup essential roles
    Role::create(['name' => 'client-admin', 'description' => 'Client Admin']);
    Role::create(['name' => 'client-user', 'description' => null]); // Null description to test robustness
});

it('can render the team members management page for client-admin', function () {
    $company = Company::factory()->create();
    $adminRole = Role::where('name', 'client-admin')->first();
    
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
        'company_id' => $company->id,
    ]);

    $this->actingAs($user);

    Livewire\Livewire::test(ManageTeamMembers::class)
        ->assertSuccessful();
});

it('allows client-admin to create a team member', function () {
    $company = Company::factory()->create();
    $adminRole = Role::where('name', 'client-admin')->first();
    $userRole = Role::where('name', 'client-user')->first();
    
    $admin = User::factory()->create([
        'role_id' => $adminRole->id,
        'company_id' => $company->id,
    ]);

    $this->actingAs($admin);

    Livewire\Livewire::test(ManageTeamMembers::class)
        ->callAction('create', data: [
            'name' => 'New Team Member',
            'email' => 'newmember@example.com',
            'role_id' => $userRole->id,
            'password' => 'password123',
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'New Team Member',
        'email' => 'newmember@example.com',
        'company_id' => $company->id,
        'role_id' => $userRole->id,
    ]);
});
