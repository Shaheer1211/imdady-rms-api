<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->nullable();
            $table->string('cat_name_arabic');
            $table->text('description')->nullable();
            $table->string('cat_image')->nullable();
            $table->string('cat_banner')->nullable();
            $table->enum('web_status', ['active', 'inactive'])->default('active');
            $table->enum('subscriptions_status', ['active', 'inactive'])->default('inactive');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_subscription')->default(false);
            $table->string('add_port')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('outlet_id');
            $table->boolean('is_sub_cat')->default(false);
            $table->integer('is_priority')->default(0);
            $table->string('del_status')->default('Live');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_menu_categories');
    }
};
