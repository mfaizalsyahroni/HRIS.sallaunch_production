<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('overtimes', function (Blueprint $table) {

            $table->id();

            // ✅ RELATION KE WORKERS
            $table->foreignId('worker_id')
                ->constrained('workers')
                ->onDelete('cascade');

            // ✅ SNAPSHOT DATA WORKER (Payroll Safe)
            $table->string('employee_id');
            $table->string('fullname');

            // ✅ DATA LEMBUR
            $table->date('overtime_date');

            $table->time('start_time');
            $table->time('end_time');

            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->decimal('total_work_hours', 8, 2);
            $table->decimal('overtime_hourly_wage', 15, 2);
            $table->decimal('total_payment', 15, 2);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
