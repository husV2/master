<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFriends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_friends', function (Blueprint $table) {
            $table->integer('user')->unsigned();
            $table->integer('friend')->unsigned();
            $table->boolean('accepted')->default(false);
            $table->timestamps();
        });
        Schema::table('hus_friends', function (Blueprint $table) {
            $table->foreign('user')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('friend')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hus_friends');
    }
}
