<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('customer_name');
            $table->string('group_name');
            $table->string('address');
            $table->string('zone');
            $table->string('district');
            $table->string('division');
            $table->string('tin')->nullable();;
            $table->string('bin')->nullable();;
            $table->string('trade_license')->nullable();;
            $table->string('contact_person');
            $table->string('contact_mobile');
            $table->string('contact_email')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
