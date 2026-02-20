<?php

namespace Database\Seeders;

use App\Enums\BugPriorityEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BugPrioritySeeder extends Seeder
{
    public function run(): void
    {
        foreach (BugPriorityEnum::cases() as $priority) {
            DB::table('bug_priorities')->updateOrInsert(
                ['slug' => $priority->value],
                [
                    'name' => $priority->label(),
                    'color' => $priority->color(),
                    'level' => $priority->level(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
