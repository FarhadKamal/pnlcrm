<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePumpChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_choices', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('req_id');
            $table->integer('product_id');
            $table->double('pump_head')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('qty');
            $table->double('discount_price')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('net_price')->default(0);

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
        Schema::dropIfExists('pump_choices');
    }
}
