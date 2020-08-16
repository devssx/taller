<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('week');
            $table->unsignedInteger('number');
            $table->datetime('start');
            $table->datetime('end');
            $table->unsignedInteger('week');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('workshop_id');
            $table->integer('type');
            $table->decimal('total_week');
            $table->decimal('comission');
            $table->decimal('discount');
            $table->decimal('salary');
            $table->decimal('total');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('workshop_id')->references('id')->on('workshops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
