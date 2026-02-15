<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {

            $table->id();

            /* SNAPSHOT WORKER */
            $table->integer('employee_id');
            $table->string('fullname');
            $table->string('role');

            /* SNAPSHOT SALARY GRADE */
            $table->foreignId('salary_grade_id')
                ->constrained('salary_grades')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /* PAYROLL PERIOD */
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');

            /* SALARY COMPONENT */
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('fixed_allowance', 15, 2)->default(0);

            /* ATTENDANCE RESULT */
            $table->unsignedTinyInteger('working_days_in_month')->default(21);
            $table->unsignedTinyInteger('present_days')->default(0);
            $table->decimal('earned_salary', 15, 2)->default(0);

            /* OVERTIME */
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_rate', 15, 2)->default(0);
            $table->decimal('overtime_pay', 15, 2)->default(0);

            /* ===============================
             * BPJS CONTRIBUTION (INDONESIA)
             * =============================== */

            /* BPJS KESEHATAN */
            $table->decimal('bpjs_kesehatan_employer', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_employee', 15, 2)->default(0);

            /* BPJS KETENAGAKERJAAN */
            $table->decimal('bpjs_jkk', 15, 2)->default(0); // Employer
            $table->decimal('bpjs_jkm', 15, 2)->default(0); // Employer

            $table->decimal('bpjs_jht_employer', 15, 2)->default(0);
            $table->decimal('bpjs_jht_employee', 15, 2)->default(0);

            $table->decimal('bpjs_jp_employer', 15, 2)->default(0);
            $table->decimal('bpjs_jp_employee', 15, 2)->default(0);

            /* DAILY SALARY */
            $table->decimal('daily_salary', 15, 2)->default(0);

            /* BONUS / COMPENSATION */
            $table->decimal('thr', 15, 2)->default(0);
            $table->decimal('severance_pay', 15, 2)->default(0);
            $table->decimal('upmk', 15, 2)->default(0);
            $table->decimal('uph', 15, 2)->default(0);

            /* DEDUCTION & TOTAL */
            $table->decimal('deduction', 15, 2)->default(0);

            $table->decimal('total_salary', 15, 2)->default(0);

            /* TAX & NET */
            $table->decimal('tax_deduction', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);

            $table->timestamps();

            /* CONSTRAINT */
            $table->unique(['employee_id', 'month', 'year']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
