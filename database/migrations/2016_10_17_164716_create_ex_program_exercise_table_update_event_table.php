<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExProgramExerciseTableUpdateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('hus_ex_program_exercise', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ex_prog_fk')->unsigned();
            $table->integer('exercise_fk')->unsigned();
            $table->integer('day');
            $table->integer('order_num');
            $table->unique(['day', 'order', 'ex_prog_fk']);
            
            $table->foreign('ex_prog_fk')
                ->references('id')->on('hus_ex_program')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            $table->foreign('exercise_fk')
                ->references('id')->on('hus_exercise')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            
        });
        Schema::disableForeignKeyConstraints();
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->dropColumn('day_id');
            $table->dropForeign('ex_prog_fk');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->string('day_id', 10);
            $table->integer('ex_prog_fk')->unsigned();
            $table->foreign('ex_prog_fk')
                ->references('id')->on('hus_ex_program')
                ->onDelete('cascade')
                ->onUpdate('cascade');           
        });
        Schema::drop('hus_ex_program_exercise');
    }
}
