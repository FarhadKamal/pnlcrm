<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Leads2024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('created_by');
            $table->string('lead_source');
            $table->text('product_requirement');
            $table->string('lead_person');
            $table->string('lead_email')->nullable();
            $table->string('lead_phone');
            $table->string('current_stage');
            $table->string('current_subStage');
            $table->integer('is_return')->default(0);
            $table->integer('is_won')->default(0);
            $table->integer('is_lost')->default(0);
            $table->string('lost_reason')->nullable();
            $table->text('lost_description')->nullable();
            $table->integer('need_credit_approval')->default(0);
            $table->integer('need_discount_approval')->default(0);
            $table->integer('need_top_approval')->default(0);
            $table->string('payment_type')->nullable();
            $table->double('creditAmt')->default(0);
            $table->double('aitAmt')->default(0);
            $table->double('vatAmt')->default(0);
            $table->text('delivery_from')->nullable();
            $table->integer('accounts_clearance')->default(0);
            $table->integer('is_outstanding')->default(0);
            $table->integer('sap_invoice')->default(0);
            $table->date('invoice_date')->nullable();
            $table->integer('invoice_by')->nullable()->comment('User who generated invoice');
            $table->string('delivery_challan')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_person')->nullable();
            $table->string('delivery_mobile')->nullable();
            $table->string('delivery_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
