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
        Schema::create('nominas_global_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('week');
            $table->string('comment');
            $table->decimal('total');
            $table->timestamps();
        });

        Schema::create('nominas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('week');
            $table->unsignedInteger('user_id');
           
            $table->integer('type');
            $table->decimal('total_week');
            $table->decimal('comission');
            $table->decimal('discount');
            $table->decimal('salary');
            $table->decimal('total');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('nominas_user_comments', function (Blueprint $table) {
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
        Schema::dropIfExists('nominas');
        Schema::dropIfExists('nominas_user_comments');
        Schema::dropIfExists('nominas_global_comments');
    }
}
