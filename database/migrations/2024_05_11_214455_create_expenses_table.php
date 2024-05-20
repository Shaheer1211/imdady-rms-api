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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->decimal('amount')->nullable();
            $table->boolean('vat')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->integer('vat_percentage')->nullable();
            $table->text('tax_method')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
