<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            // Verkoop-tracking. De legacy "(VERKOCHT)" markering in model-naam
            // blijft om bestaande filters te behouden, maar deze velden zijn de
            // bron-van-waarheid voor business intelligence (omzet, marge).
            $table->date('verkocht_datum')->nullable()->after('inkoop_prijs');
            $table->decimal('verkoopprijs', 10, 2)->nullable()->after('verkocht_datum');
            $table->string('verkocht_aan', 160)->nullable()->after('verkoopprijs');

            $table->index('verkocht_datum');
        });
    }

    public function down(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropIndex(['verkocht_datum']);
            $table->dropColumn(['verkocht_datum', 'verkoopprijs', 'verkocht_aan']);
        });
    }
};
