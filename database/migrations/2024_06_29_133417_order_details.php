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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('food_menu_id')->nullable();
            $table->float('single_discount')->nullable();
            $table->integer('qty')->nullable();
            $table->float('menu_unit_price');
            $table->float('menu_price_with_discount');
            $table->float('menu_unit_price_with_vat')->nullable();
            $table->float('menu_vat_percentage');
            $table->text('menu_taxes')->nullable();
            $table->float('menu_discount_value')->nullable();
            $table->string('discount_type');
            $table->float('discount_amount')->nullable();
            $table->text('menu_note')->nullable();
            $table->string('item_type')->nullable();
            $table->string('cooking_status')->nullable();
            $table->dateTime('cooking_start_time')->nullable();
            $table->dateTime('cooking_end_time')->nullable();
            $table->integer('order_id');
            $table->string('del_status')->nullable()->default('Live');
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
