<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bug_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#6B7280');
            $table->unsignedTinyInteger('level')->default(3);
            $table->timestamps();

            $table->index('slug');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_priorities');
    }
};
