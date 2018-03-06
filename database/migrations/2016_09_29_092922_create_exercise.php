<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            $this->drop();
        }
        Schema::create('hus_exercise', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content_html');
            $table->float('duration');
            $table->integer('count')->unsigned();
            $table->integer('ex_category_fk')->unsigned();
            $table->timestamps(); // not needed?
        });
        Schema::table('hus_exercise', function (Blueprint $table)
        {
            $table->foreign('ex_category_fk') // Olisiko parempi että exercisella voi olla useampi kategoria? (pivot table)
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
        Schema::drop('hus_exercise');
    }
}
