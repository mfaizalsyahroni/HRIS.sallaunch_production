<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->date('established_at')->nullable();
            $table->string('logo')->nullable();

            // Dashboard Counts
            $table->integer('employee_count')->default(0);
            $table->integer('department_count')->default(0);
            $table->integer('branch_count')->default(0);
            $table->integer('project_count')->default(0);

            // Stock
            $table->bigInteger('stock_value')->default(0);
            $table->decimal('stock_growth', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
