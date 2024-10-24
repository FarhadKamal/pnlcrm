<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_demands', function (Blueprint $table) {
            $table->id();
            $table->string('item_type');
            $table->string('item_brand');
            $table->string('item_name');
            $table->integer('item_quantity');
            $table->string('item_description')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->integer('created_by');
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
        Schema::dropIfExists('items_demands');
    }
}
