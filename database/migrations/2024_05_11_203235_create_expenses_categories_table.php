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
        Schema::create('expenses_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('vat_percentage', 5, 2)->nullable();
            $table->text('tax_method')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('expenses_categories');
    }
};
