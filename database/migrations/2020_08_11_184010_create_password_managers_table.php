<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workshop_id');
            $table->string('name');
            $table->string('password');
            $table->timestamps();
            $table->foreign('workshop_id')->references('id')->on('work_shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_managers');
    }
}
