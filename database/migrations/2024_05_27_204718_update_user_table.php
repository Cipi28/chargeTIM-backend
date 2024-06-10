<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('profile_photo')->nullable()->default(null);
            $table->integer('role')->default(0);
            $table->string('address')->nullable()->default(null);
            $table->double('personal_rating')->nullable()->default(0);
            $table->integer('personal_rating_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
            $table->dropColumn('role');
            $table->dropColumn('address');
            $table->dropColumn('personal_rating');
            $table->dropColumn('personal_rating_count');
        });
    }
}
