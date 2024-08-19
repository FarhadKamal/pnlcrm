<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('old_code')->nullable();
            $table->integer('new_code');
            $table->string('mat_name');
            $table->string('brand_name');
            $table->string('country_name');
            $table->string('pump_type');
            $table->string('itm_group')->nullable();
            $table->string('phase')->nullable();
            $table->double('kw')->default(0);
            $table->double('hp')->default(0);
            $table->double('suction_dia')->default(0);
            $table->double('delivery_dia')->default(0);
            $table->string('min_capacity')->default(0);
            $table->string('max_capacity')->default(0);
            $table->double('min_head')->default(0);
            $table->double('max_head')->default(0);
            $table->string('unit_name')->nullable();
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
        Schema::dropIfExists('items');
    }
}
