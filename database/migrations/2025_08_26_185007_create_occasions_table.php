<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('occasions', function (Blueprint $table) {
            $table->id();

            $table->string('merk');
            $table->string('model');
            $table->string('type')->nullable();
            $table->string('slug')->unique();
            $table->string('transmissie');
            $table->string('brandstof');
            $table->string('kenteken')->nullable();
            $table->string('interieurkleur')->nullable();
            $table->string('btw_marge')->nullable();
            $table->integer('cilinderinhoud')->nullable();
            $table->string('carrosserie')->nullable();
            $table->integer('max_trekgewicht')->nullable();
            $table->date('apk_tot')->nullable();
            $table->string('energielabel')->nullable();
            $table->string('wegenbelasting_min')->nullable();

            $table->integer('aantal_deuren')->nullable();
            $table->integer('tellerstand')->nullable();
            $table->integer('bouwjaar')->nullable();
            $table->integer('prijs')->nullable(); // hele euro’s
            $table->string('bekleding')->nullable();
            $table->integer('aantal_cilinders')->nullable();
            $table->integer('topsnelheid')->nullable();
            $table->integer('gewicht')->nullable();
            $table->integer('laadvermogen')->nullable();
            $table->string('bijtelling')->nullable();
            $table->decimal('gemiddeld_verbruik',4,2)->nullable();

            $table->string('hoofdfoto_path')->nullable();
            $table->json('galerij')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('occasions');
    }
};

