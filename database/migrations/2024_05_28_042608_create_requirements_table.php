<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->string('type_of_use');
            $table->string('suction_type')->nullable();
            $table->double('suction_pipe_dia')->nullable();
            $table->string('delivery_head')->nullable();
            $table->double('delivery_pipe_dia')->nullable();
            $table->double('horizontal_pipe_length')->nullable();
            $table->string('source_of_water')->nullable();
            $table->double('water_consumption')->nullable();
            $table->string('liquid_type')->nullable();
            $table->double('pump_running_hour')->nullable();
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
        Schema::dropIfExists('requirements');
    }
}
