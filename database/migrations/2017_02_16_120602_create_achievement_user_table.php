<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_achievement_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('achievement_id')->unsigned();
        });
        Schema::table('hus_achievement_user', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('achievement_id')
                ->references('id')->on('hus_achievement')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->primary(['user_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hus_achievement_user');
    }
}
