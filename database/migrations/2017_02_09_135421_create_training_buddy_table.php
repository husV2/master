<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingBuddyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_buddy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->integer('health')->default(7);
            $table->date('ex_streak_date')->nullable();
            $table->integer('exercise_streak')->default(0);
            $table->integer('login_streak')->default(0);
            $table->timestamps();
        });
        Schema::table('hus_buddy', function (Blueprint $table) {
            $table->unique('owner_id');
            $table->foreign('owner_id')
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
        Schema::drop('hus_buddy');
    }
}
