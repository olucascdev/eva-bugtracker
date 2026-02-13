<?php

namespace Database\Seeders;

use App\Enums\BugStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BugStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (BugStatusEnum::cases() as $status) {
            DB::table('bug_statuses')->insert([
                'name' => $status->label(),
                'slug' => $status->value,
                'color' => $status->color(),
                'order' => $status->order(),
                'is_default' => $status->isDefault(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
