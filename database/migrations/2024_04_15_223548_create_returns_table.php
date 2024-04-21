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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('date_time')->nullable();
            $table->string('description')->nullable(); 
            $table->integer('user_id')->nullable();
            $table->string('return_amount')->nullable();
            $table->string('return_vat')->nullable();
            $table->string('total_return_amount')->nullable();
            $table->string('qrcode')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('del_status')->nullable()->default('Live');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
