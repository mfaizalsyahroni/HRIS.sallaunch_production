<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();

            // HRD-friendly fields
            $table->string('position');        // Trader, Secretary, Security
            $table->string('grade_name');      // Junior, Middle, Senior

            $table->bigInteger('basic_salary');

            // Salary level grouping
            $table->unsignedTinyInteger('salary_level')
                ->comment('1=<=6jt(UMR), 2=6-10jt, 3=10-20jt');

            $table->enum('employment_type', [
                'probation',
                'contract',
                'intern',
                'freelance',
                'permanent'
            ])->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
