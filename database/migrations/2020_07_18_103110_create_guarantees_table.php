<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuaranteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantees', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('workshop_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('year');
            $table->string('employee');
            $table->datetime('new_date');
            $table->string('solution');
            $table->string('comment');

            $table->timestamps();
            $table->foreign('workshop_id')->references('id')->on('work_shops');
            $table->foreign('sale_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantees');
    }
}
