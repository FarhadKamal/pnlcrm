<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('quotation_id');
            $table->date('deposit_date');
            $table->double('pay_amount');
            $table->string('transaction_type');
            $table->string('transaction_file');
            $table->string('transaction_remarks')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->integer('verified_by')->nullable();
            $table->date('deposited_date')->nullable();
            $table->string('deposited_remarks')->nullable();
            $table->boolean('is_return')->default(0);
            $table->date('return_date');
            $table->string('return_remarks')->nullable();
            $table->integer('return_by')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
