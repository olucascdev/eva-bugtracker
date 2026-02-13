<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('expected_behavior')->nullable();
            $table->string('conversation_link')->nullable();
            $table->timestamp('error_datetime')->nullable();
            $table->foreignId('bug_status_id')->constrained()->restrictOnDelete();
            $table->foreignId('bug_priority_id')->constrained()->restrictOnDelete();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reported_by_user_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('estimated_completion_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('temporary_guidance')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedInteger('total_interactions')->default(0);
            $table->unsignedInteger('error_interactions')->default(0);
            $table->decimal('ai_accuracy_rate', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('bug_status_id');
            $table->index('bug_priority_id');
            $table->index('assigned_to_user_id');
            $table->index('reported_by_user_id');
            $table->index('opened_at');
            $table->index('estimated_completion_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
