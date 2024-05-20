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
        Schema::create('food_menu_modifiers', function (Blueprint $table) {
            $table->id();
            $table->integer("food_menu_id");
            $table->integer("modifier_id");
            $table->integer("user_id");
            $table->integer("outlet_id");
            $table->timestamps();
            $table->string('del_status')->nullable()->default('Live');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_menu_modifiers');
    }
};
