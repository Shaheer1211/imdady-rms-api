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
        Schema::create('ingredient_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name')->nullable();
            $table->string('unit_value')->nullable();
            $table->string('description')->nullable();
            $table->integer('user_id');
            $table->integer('outlet_id');
            $table->string('del_status')->nullable()->default("Live");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_units');
    }
};
