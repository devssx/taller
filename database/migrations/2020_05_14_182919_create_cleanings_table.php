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
            
            $table->unsignedInteger('user_id');
            $table->timestamp('start', 0);
            $table->string('cleaning');
            $table->timestamp('breakfast_start', 0);
            $table->timestamp('breakfast_end', 0);   
            $table->timestamp('lunch_start', 0);
            $table->timestamp('lunch_end', 0);
            $table->tinyInteger('done');
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
