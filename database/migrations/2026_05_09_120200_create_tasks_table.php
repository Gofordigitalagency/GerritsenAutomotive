<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('occasion_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->text('body')->nullable();
            $table->string('priority', 10)->default('normal'); // low, normal, high
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['completed_at', 'due_at']);
            $table->index('occasion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
