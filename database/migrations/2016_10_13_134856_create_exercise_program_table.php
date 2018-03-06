<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_ex_program', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('mon')->unsigned();
            $table->integer('tue')->unsigned();
            $table->integer('wed')->unsigned();
            $table->integer('thu')->unsigned();
            $table->integer('fri')->unsigned();
            $table->integer('sat')->unsigned();
            $table->integer('sun')->unsigned();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
        
        Schema::table('hus_ex_program', function (Blueprint $table){
            $table->foreign('mon')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('tue')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('wed')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('thu')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('fri')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('sat')
                ->references('id')->on('hus_ex_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('sun')
                ->references('id')->on('hus_ex_category')
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
        Schema::drop('hus_ex_program');
    }
}
