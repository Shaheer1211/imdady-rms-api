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
         Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('dis_type', ['percentage', 'amount']);
            $table->enum('use_discount', ['one_time', 'regular']);
            $table->decimal('discount_amount', 8, 2);
            $table->enum('specific_customers', ['yes', 'no']);
            $table->json('multi_customer_id')->nullable();
            $table->enum('del_status', ['Live', 'delete'])->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
