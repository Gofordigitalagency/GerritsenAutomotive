<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('occasions', function (Blueprint $table) {
            // JSON arrays met opties (mag ook TEXT; JSON is netter)
            $table->json('exterieur_options')->nullable();
            $table->json('interieur_options')->nullable();
            $table->json('veiligheid_options')->nullable();
            $table->json('overige_options')->nullable();

            // Lange omschrijving
            $table->longText('omschrijving')->nullable();
        });
    }

    public function down(): void {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropColumn([
                'exterieur_options','interieur_options','veiligheid_options','overige_options','omschrijving'
            ]);
        });
    }
};
