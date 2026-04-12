<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->integer('leave_balance')->default(12)->after('role');
             //Mass Update (default) from 0 to 12 for all existing workers
             // \App\Models\Worker::query()->update(['leave_balance' => 12]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn('leave_balance');
        });
    }
};
