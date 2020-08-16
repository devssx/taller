<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workshop_id');
            $table->unsignedInteger('week');
            $table->integer('type')->default(0);
            $table->string('concept');
            $table->decimal('amount')->default(0);
            $table->decimal('iva')->default(0);
            $table->decimal('total');
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
        Schema::dropIfExists('expenses');
    }
}
