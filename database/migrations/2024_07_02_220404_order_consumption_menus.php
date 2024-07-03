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
        Schema::create('order_consumption_menus', function(Blueprint $table) {
            $table->id();
            $table->integer('ingredient_id');
            $table->integer('consumption');
            $table->integer('order_id');
            $table->integer('food_menu_id');
            $table->string('del_status');
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
