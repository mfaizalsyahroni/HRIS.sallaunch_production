<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();

            $table->integer('employee_id')->unique();
            $table->string('fullname');
            $table->string('password');

            // ✅ Legacy / manual HRD
            $table->string('role'); // bebas: Trader, Admin IT HRIS, dll




            $table->date('working_period_start')->default(DB::raw('CURRENT_DATE'));
            $table->date('working_period_end')->nullable();

            $table->enum('employment_type', [
                'permanent',
                'contract',
                'intern',
                'freelance',
                'probation'
            ])->default('probation');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};