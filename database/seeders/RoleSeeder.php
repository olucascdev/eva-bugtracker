<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin' => 'Administrator with full access',
            'support' => 'Support team member',
            'client-admin' => 'Client administrator',
            'client-user' => 'Client regular user',
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }
}
