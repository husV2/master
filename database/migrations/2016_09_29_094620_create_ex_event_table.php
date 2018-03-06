<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_ex_event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_fk')->unsigned();
            $table->integer('ex_fk')->unsigned();
            $table->boolean('completed')->default(false);
            $table->datetime('start_date');
            $table->datetime('complete_date')->nullable();
            $table->timestamps();
        });
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->foreign('user_fk')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('ex_fk')
                ->references('id')->on('hus_exercise')
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
        Schema::drop('hus_ex_event');
    }
}
