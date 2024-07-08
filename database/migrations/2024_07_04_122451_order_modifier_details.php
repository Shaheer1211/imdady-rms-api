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
        Schema::create('order_modifier_details', function(Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('order_details_id');
            $table->integer('modifier_id');
            $table->integer('qty');
            $table->float('sell_price');
            $table->float('vat')->nullable();
            $table->string('del_status')->nullable()->default('Live');
            $table->timestamps();
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
