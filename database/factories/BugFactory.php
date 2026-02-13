<?php

namespace Database\Factories;

use App\Models\BugPriority;
use App\Models\BugStatus;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pega status e prioritade aleatórios já existentes (seedados)
        $status = BugStatus::inRandomOrder()->first();
        $priority = BugPriority::inRandomOrder()->first();

        // Se não existirem, tenta pegar o primeiro disponível (fallback)
        if (!$status) {
             $status = BugStatus::first(); 
        }
        if (!$priority) {
             $priority = BugPriority::first();
        }

        return [
            'company_id' => Company::factory(),
            'bug_status_id' => $status?->id,
            'bug_priority_id' => $priority?->id,
            'reported_by_user_id' => User::factory(),
            'assigned_to_user_id' => null,
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(3, true),
            'expected_behavior' => fake()->paragraph(),
            'conversation_link' => fake()->url(),
            'error_datetime' => fake()->dateTimeBetween('-1 month', 'now'),
            'opened_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'estimated_completion_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'completed_at' => null,
            'temporary_guidance' => fake()->optional()->sentence(),
            'observations' => fake()->optional()->paragraph(),
            'total_interactions' => fake()->numberBetween(0, 50),
            'error_interactions' => fake()->numberBetween(0, 10),
            'ai_accuracy_rate' => fake()->optional()->numberBetween(70, 100),
        ];
    }
}
