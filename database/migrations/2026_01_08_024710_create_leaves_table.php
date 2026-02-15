<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relasi karyawan
            $table->integer('employee_id');
            $table->string('fullname'); // snapshot nama
            $table->string('role');     // snapshot role

            // Data cuti
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('leave_reason')->nullable();

            // Status approval
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->timestamps();

            // 🔗 FK ke workers
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('workers')
                ->cascadeOnDelete();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
