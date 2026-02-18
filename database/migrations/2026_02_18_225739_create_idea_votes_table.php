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
        Schema::create('idea_votes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('idea_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('worker_id')
                ->constrained('workers')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['idea_id', 'worker_id']); // 1 worker = 1 vote
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_votes');
    }
};
