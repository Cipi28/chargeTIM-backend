<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('status')->default(0);

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('car_id');
            $table->foreign('car_id')->references('id')->on('cars');

            $table->integer('station_id');
            $table->foreign('station_id')->references('id')->on('stations');

            $table->integer('plug_id');
            $table->foreign('plug_id')->references('id')->on('plugs');
            $table->softDeletes();
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
        Schema::dropIfExists('bookings');
    }
}
