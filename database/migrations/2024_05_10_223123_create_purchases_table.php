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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
           $table->unsignedBigInteger('supplier_id')->nullable();
           $table->date('date')->nullable();
           $table->decimal('subtotal', 8, 2)->nullable();
           $table->decimal('vat', 8, 2)->nullable();
           $table->decimal('grand_total', 8, 2)->nullable();
           $table->decimal('paid', 8, 2)->nullable();
           $table->decimal('due', 8, 2)->nullable();
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
        Schema::dropIfExists('purchases');
    }
};
