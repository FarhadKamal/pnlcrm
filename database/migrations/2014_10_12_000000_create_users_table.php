<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_phone')->nullable();
            $table->string('user_email')->unique();
            $table->string('password');
            $table->integer('user_desg');
            $table->integer('user_dept');
            $table->integer('user_location');
            $table->string('user_signature')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_active')->default(1);
            $table->string('assign_to')->nullable();
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
        Schema::dropIfExists('users');
    }
}
