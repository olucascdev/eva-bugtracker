<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BugPrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Crítica', 'slug' => 'critica', 'color' => '#DC2626', 'level' => 5],
            ['name' => 'Alta', 'slug' => 'alta', 'color' => '#F59E0B', 'level' => 4],
            ['name' => 'Média', 'slug' => 'media', 'color' => '#3B82F6', 'level' => 3],
            ['name' => 'Baixa', 'slug' => 'baixa', 'color' => '#10B981', 'level' => 2],
            ['name' => 'Mínima', 'slug' => 'minima', 'color' => '#6B7280', 'level' => 1],
        ];

        foreach ($priorities as $priority) {
            DB::table('bug_priorities')->insert([
                ...$priority,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
