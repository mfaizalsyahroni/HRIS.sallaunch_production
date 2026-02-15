<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('workers')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('problem');
            $table->text('solution');
            $table->text('impact')->nullable();

            // OPTIONAL FILES
            $table->string('attachment')->nullable();
            $table->string('demo_video')->nullable();

            $table->enum('status', [
                'draft',
                'published',
                'voting',
                'reviewed',
                'final'
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};