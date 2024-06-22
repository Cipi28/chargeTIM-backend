<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->longText('comment')->nullable();
            $table->string('reviewer_name')->nullable();
            $table->longText('reviewer_photo')->nullable();
            $table->boolean('is_public_reviewer')->default(false);
            $table->integer('station_id');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade');
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('stations')->onDelete('cascade');
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
        Schema::dropIfExists('reviews');
    }
}
