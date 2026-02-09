<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
   Schema::create('reclame_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclame_id')->constrained()->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained('occasions')->cascadeOnDelete();
            $table->unsignedTinyInteger('position'); // 1..4
            $table->timestamps();

            $table->unique(['reclame_id', 'position']);
            $table->unique(['reclame_id', 'occasion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclame_items');
    }
};
