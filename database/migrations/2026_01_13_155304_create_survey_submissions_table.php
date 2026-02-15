<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();

            // foreign key ke tabel surveys
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');

            // employee_id → sesuai dengan workers.employee_id (integer)
            $table->unsignedBigInteger('employee_id');

            // fullname (opsional)
            $table->string('fullname')->nullable();

            // tanggal & waktu survey
            $table->date('survey_date');
            $table->time('survey_time');

            $table->timestamps();
        });

        // foreign key ke tabel workers
        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('workers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        Schema::dropIfExists('survey_submissions');
    }
};
