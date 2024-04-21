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
        Schema::create('outlet_settings', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_name')->nullable();
            $table->integer('service_delivery_charge');
            $table->string('outlet_code')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->string('days')->nullable();
            $table->string('hours')->nullable();
            $table->enum('invoice_print', ['Yes', 'No'])->nullable();
            $table->string('is_android')->default('no');
            $table->date('starting_date')->nullable();
            $table->string('printer_local_ip')->nullable();
            $table->string('invoice_footer')->nullable();
            $table->string('invoice_footer_text')->nullable();
            $table->string('invoice_footer_text_1')->nullable();
            $table->integer('order_date')->nullable();
            $table->integer('statement_date')->nullable();
            $table->integer('print_date')->nullable();
            $table->string('printer_detail')->nullable();
            $table->string('jspm_print')->default('yes');
            $table->string('print_kot_invoice')->nullable()->default('Yes');
            $table->string('collect_tax')->nullable();
            $table->string('tax_title')->nullable();
            $table->string('tax_percentage')->nullable();
            $table->string('invoice')->default('print_invoice');
            $table->string('theme_design')->default('index');
            $table->string('invoice_language')->default('default');
            $table->integer('round_off')->default(2);
            $table->string('logo_name')->nullable();
            $table->string('tax_registration_no')->nullable();
            $table->string('tax_is_gst')->default('None');
            $table->integer('sale_date_time')->default(1);
            $table->string('state_code')->nullable();
            $table->string('pre_or_post_payment')->default('Post Payment');
            $table->integer('user_id')->nullable();
            $table->integer('outlet_id')->nullable();
            $table->string('del_status')->default('Live');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_settings');
    }
};
