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
            $table->integer('created_by');
            $table->string('lead_source');
            $table->text('product_requirement');
            $table->string('lead_email');
            $table->string('lead_phone');
            $table->integer('is_won')->default(0);
            $table->integer('is_lost')->default(0);
            $table->string('lost_reason')->nullable();
            $table->text('lost_description')->nullable();
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
