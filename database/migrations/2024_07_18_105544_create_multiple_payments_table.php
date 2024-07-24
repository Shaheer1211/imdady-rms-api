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
        Schema::create('multiple_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_method_id')->unsigned();
            $table->string('company_name');
            $table->enum('dis_type', ['amount', 'percentage'])->nullable();
            $table->date('expired_date')->nullable();
            $table->string('status');
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->string('photo')->nullable();
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_payments');
    }
};
