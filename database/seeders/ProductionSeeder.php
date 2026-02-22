<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seed de PRODUÇÃO...');
        $this->command->newLine();

        // 1. Run Core Seeders (Roles, Statuses, Priorities)
        $this->command->info('⚙️  Roles, Statuses e Priorities...');
        $this->call([
            RoleSeeder::class,
            BugStatusSeeder::class,
            BugPrioritySeeder::class,
        ]);

        // 2. Create Default Company "Eva Tecnologia"
        $this->command->info('🏢 Criando empresa Eva Tecnologia...');
        $evaCompany = Company::updateOrCreate(
            ['slug' => 'eva-tecnologia'],
            [
                'name' => 'Eva Tecnologia',
                'email' => 'contato@evatecnologia.com.br',
                'is_active' => true,
            ]
        );
        $this->command->info("   ✓ Empresa garantida: {$evaCompany->name}");

        // 3. Create Admin User
        $this->command->info('👑 Criando administrador...');
        $this->createAdmin($evaCompany->id);

        $this->command->newLine();
        $this->command->info('✅ PRODUÇÃO seed finalizado com sucesso!');
        $this->command->newLine();
    }

    private function createAdmin(int $companyId): void
    {
        $email = config('admin.email');
        $password = config('admin.password');
        $name = config('admin.name', 'Administrador');

        if (! $email || ! $password) {
            $this->command->warn(
                '⚠️  EMAIL_ADMIN ou PASSWORD_ADMIN não definidos. Admin não criado.'
            );

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role_id' => Role::where('name', 'admin')->first()->id,
                'company_id' => $companyId,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("   ✓ Admin garantido: {$user->email}");
    }
}
