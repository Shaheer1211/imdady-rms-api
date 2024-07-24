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
        Schema::create('loyalty', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('convert_points');
            $table->decimal('per_price', 10, 2);
            $table->decimal('percentage_order_amount', 5, 2);
            $table->integer('minimum_point');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty');
    }
};
