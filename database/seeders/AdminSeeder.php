<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Garante que a empresa "EVA" (admin) existe
        $evaCompany = Company::firstOrCreate(
            ['slug' => 'eva-admin'],
            [
                'name' => 'EVA Admin',
                'email' => 'admin@eva.com',
                'is_active' => true,
            ]
        );

        // 2. Cria o usuário Admin solicitado
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role_id' => \App\Models\Role::firstWhere('name', 'admin')->id,
                'company_id' => $evaCompany->id,
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $this->command->info('Admin user created/verified:');
        $this->command->info('Email: '.$admin->email);
        $this->command->info('Password: password');
        $this->command->info('Role: '.$admin->role->name);
        $this->command->info('Company: '.$evaCompany->name);
    }
}
