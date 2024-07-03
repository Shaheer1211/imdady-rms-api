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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('is_coupon');
            $table->integer('coupon_id');
            $table->float('coupon_discount_amount')->nullable();
            $table->integer('cashier_id')->nullable();
            $table->string('sale_no');
            $table->string('token_no')->nullable();
            $table->integer('total_items')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('paid_amount')->nullable();
            $table->float('due_amount')->nullable();
            $table->float('discount')->nullable();
            $table->float('vat_amount')->nullable();
            $table->text('qrcode')->nullable();
            $table->float('total_payable')->nullable();
            // $table->string('is_loyalty');
            $table->float('loyalty_point_amount')->nullable()->default(0);
            $table->time('close_time');
            $table->integer('table_id')->nullable();
            $table->float('total_item_discount_amount');
            $table->float('total_discount_amount');
            $table->float('sub_total_with_discount');
            $table->float('delivery_charges');
            $table->dateTime('sale_date');
            $table->dateTime('date_time')->useCurrent();
            $table->time('order_time');
            $table->dateTime('cooking_start_time')->nullable();
            $table->dateTime('cooking_end_time')->nullable();
            $table->enum('modified', ['yes', 'no']);
            $table->float('modified_vat')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('waiter_id')->nullable();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->enum('order_status', ['new', 'invoiced', 'closed']);
            $table->integer('order_type_id');
            $table->string('order_from');
            $table->timestamps();
            $table->string('del_status')->default('Live');
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
