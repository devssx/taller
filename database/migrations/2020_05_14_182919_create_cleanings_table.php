<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCleaningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleanings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('day');
            $table->unsignedInteger('user_id');
            $table->datetime('start');
            $table->string('cleaning');
            $table->datetime('breakfast_start');
            $table->datetime('breakfast_end');   
            $table->datetime('lunch_start');
            $table->datetime('lunch_end');
            $table->string('done');
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
        Schema::dropIfExists('cleanings');
    }
}
