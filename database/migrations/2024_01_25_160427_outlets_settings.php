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
        Schema::create('outlets_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->decimal('service_delivery_charge', 8, 2)->default(0);
            $table->string('whatsapp_number')->nullable();
            $table->string('days')->nullable();
            $table->string('hours')->nullable();
            $table->enum('is_android', ['yes', 'no'])->default('no');
            $table->string('printer_local_ip')->nullable();
            $table->enum('is_print_type', ['category', 'product'])->default('product');
            $table->enum('is_jspm_print', ['yes', 'no'])->default('no');
            $table->text('template')->nullable();
            $table->enum('is_round_off', ['yes', 'no'])->default('no');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets_settings');
    }
};
