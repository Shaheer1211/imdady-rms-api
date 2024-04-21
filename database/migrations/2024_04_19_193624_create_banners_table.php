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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->text('banner_image')->nullable();
            $table->string('banner_name')->nullable();
            $table->string('status')->nullable();
            $table->integer('user_id')->constrained()->onDelete('cascade');
            $table->integer('outlet_id')->constrained()->onDelete('cascade');
            $table->string('del_status')->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
