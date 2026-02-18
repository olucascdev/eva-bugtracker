<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure existing roles present or create them if missing
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'support', 'description' => 'Support Staff'],
            ['name' => 'client', 'description' => 'Client (Deprecated - Split into admin/user)'],
            ['name' => 'client-admin', 'description' => 'Client Administrator - Can manage company users'],
            ['name' => 'client-user', 'description' => 'Client User - Can report bugs'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
