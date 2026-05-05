<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->boolean('binnenkort')->default(false)->after('prijs');
            $table->decimal('verwachte_prijs', 10, 2)->nullable()->after('binnenkort');
            $table->decimal('oude_prijs', 10, 2)->nullable()->after('verwachte_prijs');
        });
    }

    public function down(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropColumn(['binnenkort', 'verwachte_prijs', 'oude_prijs']);
        });
    }
};
