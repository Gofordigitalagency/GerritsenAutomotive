<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            // Interne inkoopprijs (NOOIT publiek tonen). Nullable zodat bestaande
            // auto's geen waarde hoeven te hebben — toon dan "onbekend".
            $table->decimal('inkoop_prijs', 10, 2)->nullable()->after('prijs');
        });
    }

    public function down(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropColumn('inkoop_prijs');
        });
    }
};
