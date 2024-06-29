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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('alternate_number')->unique()->nullable();
            $table->string('password');
            $table->string('customer_vat')->nullable();
            $table->string('address');
            $table->integer('city_id');
            $table->string('employe_card_no')->nullable();
            $table->string('otp')->nullable();
            $table->string('active_status');
            $table->string('subscription_status')->default('no');
            $table->string('is_free')->default('no');
            $table->timestamp('date_of_birth')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
