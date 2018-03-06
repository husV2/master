<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_gamification', function (Blueprint $table) {
            $table->increments('level');
            $table->integer('points');
            $table->string('trophy');
            $table->timestamps();
        });
        Schema::table('hus_user', function (Blueprint $table) {
            $table->integer('points')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hus_gamification');
    }
}
