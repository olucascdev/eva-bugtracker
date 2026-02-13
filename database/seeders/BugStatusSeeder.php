<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BugStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Reportado', 'slug' => 'reportado', 'color' => '#EF4444', 'order' => 1, 'is_default' => true],
            ['name' => 'Em Análise', 'slug' => 'em-analise', 'color' => '#F59E0B', 'order' => 2, 'is_default' => false],
            ['name' => 'Em Desenvolvimento', 'slug' => 'em-desenvolvimento', 'color' => '#3B82F6', 'order' => 3, 'is_default' => false],
            ['name' => 'Aguardando Teste', 'slug' => 'aguardando-teste', 'color' => '#8B5CF6', 'order' => 4, 'is_default' => false],
            ['name' => 'Resolvido', 'slug' => 'resolvido', 'color' => '#10B981', 'order' => 5, 'is_default' => false],
            ['name' => 'Fechado', 'slug' => 'fechado', 'color' => '#6B7280', 'order' => 6, 'is_default' => false],
        ];

        foreach ($statuses as $status) {
            DB::table('bug_statuses')->insert([
                ...$status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
