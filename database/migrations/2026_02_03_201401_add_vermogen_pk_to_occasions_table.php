<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('occasions', function (Blueprint $table) {
        $table->integer('vermogen_pk')->nullable()->after('aantal_cilinders');
    });
}
public function down(): void
{
    Schema::table('occasions', function (Blueprint $table) {
        $table->dropColumn('vermogen_pk');
    });
}
};
