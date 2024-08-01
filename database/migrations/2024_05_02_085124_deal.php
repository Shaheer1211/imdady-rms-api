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
        Schema::create('deal', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_arabic')->nullable();
            $table->integer('category_id')->nullable();
            $table->string("description")->nullable();
            $table->string('is_discount')->default("no");
            $table->float("discount_percentage")->nullable();
            $table->float("sale_price")->nullable();
            $table->float("hunger_station_price")->nullable();
            $table->float("jahiz_price")->nullable();
            $table->string("tax_method")->nullable();
            $table->string("kot_print")->nullable();
            $table->integer("vat_id")->nullable();
            $table->string('photo')->nullable();
            $table->integer("user_id");
            $table->integer("outlet_id");
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal');
    }
};
