<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->string('quotation_ref');
            $table->string('quotation_file');
            $table->boolean('is_accept')->default(0);
            $table->string('accept_file')->nullable();
            $table->string('accept_description')->nullable();
            $table->string('quotation_po')->nullable();
            $table->date('quotation_po_date')->nullable();
            $table->boolean('is_return')->default(0);
            $table->string('return_reason')->nullable();
            $table->string('return_description')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
