<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Run Core Seeders (Roles, Statuses, Priorities)
        $this->call([
            RoleSeeder::class,
            BugStatusSeeder::class,
            BugPrioritySeeder::class,
        ]);

        // 2. Create Default Company "Eva Tecnologia"
        $evaCompany = Company::updateOrCreate(
            ['slug' => 'eva-tecnologia'],
            [
                'name' => 'Eva Tecnologia',
                'email' => 'contato@evatecnologia.com.br',
                'is_active' => true,
            ]
        );

        // 3. Create Admin User
        $adminEmail = config('admin.email');
        $adminPassword = config('admin.password');

        if ($adminEmail) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Admin Eva',
                    'password' => Hash::make($adminPassword),
                    'role_id' => Role::where('name', 'admin')->first()->id,
                    'company_id' => $evaCompany->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info("Admin user created/updated with email: {$adminEmail}");
        } else {
            $this->command->warn("EMAIL_ADMIN not set in .env, skipping admin user creation.");
        }

        $this->command->info("Production seeding completed successfully.");
    }
}
