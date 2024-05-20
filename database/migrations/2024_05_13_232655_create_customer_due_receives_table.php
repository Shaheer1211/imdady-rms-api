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
        Schema::create('customer_due_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_no')->nullable();
        $table->date('only_date')->nullable();
        $table->unsignedBigInteger('customer_id')->nullable();
        $table->decimal('amount')->nullable();
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
        Schema::dropIfExists('customer_due_receives');
    }
};
