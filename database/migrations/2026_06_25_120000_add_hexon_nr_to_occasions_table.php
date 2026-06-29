<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            // Uniek Hexon/Mobilox voertuig-ID. NULL = handmatig toegevoegd.
            $table->unsignedBigInteger('hexon_nr')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('occasions', function (Blueprint $table) {
            $table->dropUnique(['hexon_nr']);
            $table->dropColumn('hexon_nr');
        });
    }
};
