<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('learning_progress', function (Blueprint $table) {

            $table->id();

            /*
            ==========================
            WORKER SNAPSHOT
            ==========================
            */

            $table->unsignedBigInteger('worker_id');

            $table->integer('employee_id');
            $table->string('fullname');
            $table->string('role');

            /*
            ==========================
            MODULE RELATION
            ==========================
            */

            $table->unsignedBigInteger('module_id');

            /*
            ==========================
            FEEDBACK VIDEO
            ==========================
            */

            $table->string('feedback_video')->nullable();

            /*
            ==========================
            STATUS + PROGRESS
            ==========================
            */

            $table->enum('status', ['pending', 'completed'])
                ->default('pending');

            $table->integer('progress_percent')
                ->default(0);

            $table->timestamps();

            /*
            ==========================
            FOREIGN KEY CONSTRAINTS
            ==========================
            */

            $table->foreign('worker_id')
                ->references('id')
                ->on('workers')
                ->onDelete('cascade');

            $table->foreign('module_id')
                ->references('id')
                ->on('learning_modules')
                ->onDelete('cascade');

            /*
            ==========================
            UNIQUE RULE
            ==========================
            Worker hanya boleh submit 1 progress per module
            */

            $table->unique(['worker_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_progress');
    }
};
