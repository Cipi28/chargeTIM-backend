<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('adress');
            $table->string('public_id')->nullable();
            $table->longText('image')->nullable();
            $table->string('phone')->nullable();
            $table->json('open_periods')->nullable();
            $table->string('maps_URL')->nullable();
            $table->string('website_URL')->nullable();
            $table->float('rating')->default(0);
            $table->integer('rating_count')->default(0);
            $table->boolean('is_public')->default(false);
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stations');
    }
}
