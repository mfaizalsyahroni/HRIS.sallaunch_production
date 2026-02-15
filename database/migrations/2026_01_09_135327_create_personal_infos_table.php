<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();

            $table->string('photo')->nullable();
            $table->integer('employee_id')->unique();

            $table->string('fullname');
            $table->string('nickname')->nullable();

            $table->enum('gender', ['Male', 'Female'])->nullable();

            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            $table->bigInteger('nik')->nullable();
            $table->bigInteger('kk_number')->nullable();

            $table->string('passport_number')->nullable();
            $table->string('npwp')->nullable();

            $table->bigInteger('bpjs_health')->nullable();
            $table->bigInteger('bpjs_employment')->nullable();

            $table->text('address_current')->nullable();
            $table->text('address_ktp')->nullable();


            $table->bigInteger('postal_code')->nullable();

            $table->string('phone')->nullable();
            $table->string('phone_emergency')->nullable();

            $table->string('email_personal')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();

            $table->date('join_date')->nullable();
            $table->string('employment_status')->nullable();

            $table->string('department')->nullable();
            $table->string('role')->nullable(); // Akan fallback ke Worker jika null

            $table->string('blood_type')->nullable();
            $table->string('shirt_size')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Relasi ke worker
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('workers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
