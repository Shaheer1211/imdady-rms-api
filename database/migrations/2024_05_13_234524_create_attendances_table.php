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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_no')->nullable();
        $table->unsignedBigInteger('employee_id')->nullable();
        $table->date('date')->nullable();
        $table->time('in_time')->nullable();
        $table->time('out_time')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};
