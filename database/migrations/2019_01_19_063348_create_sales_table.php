<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->decimal('total');
            $table->integer('service_type')->default(0);
            $table->date('next_service');

            $table->string('bill');
            $table->date('paid_date');
            
            $table->unsignedInteger('workshop_id');
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
        Schema::dropIfExists('sales');
    }
}
