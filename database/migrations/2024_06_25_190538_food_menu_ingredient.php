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
        Schema::create('food_menu_ingredient', function (Blueprint $table) {
            $table->id();
            $table->integer('food_menu_id')->nullable();
            $table->integer('ingredient_id')->nullable();
            $table->float('consumption')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('outlet_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
