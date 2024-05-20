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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->decimal('delivery_charges')->nullable();
            $table->boolean('is_delivery_charge')->nullable();
            $table->text('is_meal_type')->nullable();
            $table->integer('item_qty')->nullable();
            $table->timestamp('cat_discount')->nullable();
            $table->string('category')->nullable();
            $table->integer('category_meal')->nullable();
            $table->text('details')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('full_amount')->nullable();
            $table->integer('outlet_id')->nullable();
            $table->boolean('is_company_sub')->nullable();
            $table->integer('expire_days')->nullable();
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
