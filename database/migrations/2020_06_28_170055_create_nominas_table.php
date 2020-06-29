<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNominasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngeneral', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('week');
            $table->unsignedInteger('user_id');
            $table->string('comment');
            $table->decimal('total');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('ndetail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('week');
            $table->unsignedInteger('user_id');
           
            $table->integer('type');
            $table->decimal('comission');
            $table->decimal('discount');
            $table->decimal('salary');
            $table->decimal('total');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('ncomment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('week');
            $table->unsignedInteger('user_id');
            $table->string('comment');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ngeneral');
        Schema::dropIfExists('ncomment');
        Schema::dropIfExists('ndetail');
    }
}
