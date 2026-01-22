<?php

// database/migrations/xxxx_create_workshop_appointments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('workshop_appointments', function (Blueprint $table) {
            $table->id();

            // stap 1
            $table->string('license_plate', 20);
            $table->unsignedInteger('mileage')->nullable();

            // stap 2
            $table->string('maintenance_option')->nullable(); // bv: "Grote beurt + APK keuring"
            $table->json('extra_services')->nullable();       // checkboxes

            // stap 3
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->boolean('wait_while_service')->default(false);

            // stap 4
            $table->string('company_name')->nullable();
            $table->enum('salutation', ['dhr', 'mevr'])->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('addition')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();

            $table->string('phone')->nullable();
            $table->string('email');

            $table->text('remarks')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('marketing_opt_in')->default(false);

            // status voor later admin
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('workshop_appointments');
    }
};

