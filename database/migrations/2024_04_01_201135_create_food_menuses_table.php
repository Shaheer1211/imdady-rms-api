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
        Schema::create('food_menuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_arabic')->nullable();
            $table->string('add_port_by_product')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->string('is_discount')->default("no");
            $table->flaot("discount_amount")->nullable();
            $table->string("description")->nullable();
            $table->float("sale_price")->nullable();
            $table->float("hunger_station_price")->nullable();
            $table->float("jahiz_price")->nullable();
            $table->string("tax_method")->nullable();
            $table->string("kot_print")->nullable();
            $table->string("is_vendor")->nullable();
            $table->string("vendor_name")->nullable();
            $table->integer("vat_id")->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('outlet_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('veg_item')->nullable()->default('Veg No');
            $table->string('beverage_item')->nullable()->default('Beverage No');
            $table->string('bar_item')->nullable()->default('Bar No');
            $table->string('stock')->default('enable');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('is_new')->default('no');
            $table->string('is_tax_fix')->default('no');
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_menuses');
    }
};
