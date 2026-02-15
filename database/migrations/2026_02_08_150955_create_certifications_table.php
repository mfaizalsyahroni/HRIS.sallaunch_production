<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();

            /* 
                RELASI INTI
             */

            // Aktivitas yang dinilai
            $table->foreignId('learning_progress_id')
                ->constrained('learning_progress')
                ->cascadeOnDelete();

            // Snapshot worker
            $table->foreignId('worker_id')
                ->constrained('workers')
                ->cascadeOnDelete();

            $table->string('employee_id');

            // Modul yang disertifikasi
            $table->foreignId('module_id')
                ->constrained('learning_modules')
                ->cascadeOnDelete();

            /* 
                KEPUTUSAN
             */

            $table->enum('score', ['A', 'B', 'C']);
            $table->enum('status', ['passed', 'failed']);
            $table->text('notes')->nullable();

            /* 
                AUDITOR
             */

            // MT / Admin yang menilai
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('workers')
                ->nullOnDelete();

            $table->timestamps();

            /* 
                RULE PENTING
             */

            // 1 progress = 1 sertifikasi (ANTI DOBEL NILAI)
            $table->unique('learning_progress_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};