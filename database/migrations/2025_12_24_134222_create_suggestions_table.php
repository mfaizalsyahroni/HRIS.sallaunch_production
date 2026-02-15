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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('category');
            $table->string('title');
            $table->text('description');

            // ⬇️ tambahan
            $table->string('attachment_path')->nullable();
            $table->enum('attachment_type', ['image', 'video'])->nullable();

            $table->enum('status', ['new', 'read', 'in_progress', 'resolved'])
                ->default('new');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
