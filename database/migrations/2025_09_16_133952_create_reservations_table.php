<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Resource: 'aanhanger' | 'stofzuiger'
            $table->string('resource_type', 30);

            // Tijden
            $table->dateTime('start_at');
            $table->dateTime('end_at');

            // Klantinfo (optioneel)
            $table->string('reserved_by', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 160)->nullable();

            // Status
            $table->string('status', 20)->default('confirmed'); // confirmed|pending|cancelled

            $table->text('notes')->nullable();

            // Aangemaakt door admin (optioneel; comment deze regel uit als je geen users FK wilt)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Index
            $table->index(['resource_type', 'start_at'], 'reservations_resource_start_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
