<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cctvs', function (Blueprint $table) {
            $table->id();

            // Nama kamera
            $table->string('name');

            // Lokasi kamera
            $table->string('location');

            // Link/url streaming
            $table->text('source');

            // Jenis kamera (IP, Analog, Wireless, dll)
            $table->string('type')->nullable();

            // Status online (true = online, false = offline)
            $table->boolean('online')->default(true);

            // Catatan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctvs');
    }
};
