<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_kleur_to_occasions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->string('kleur', 50)->nullable(); // exterieurkleur in 1 veld
        });
    }

    public function down(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropColumn('kleur');
        });
    }
};

