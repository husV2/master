<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventTableModifyForPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::disableForeignKeyConstraints();
        Schema::table('hus_ex_program', function (Blueprint $table) {
            $table->dropForeign('mon');
            $table->dropForeign('tue');
            $table->dropForeign('wed');
            $table->dropForeign('thu');
            $table->dropForeign('fri');
            $table->dropForeign('sat');
            $table->dropForeign('sun');
            
        });
        Schema::enableForeignKeyConstraints();
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->string('day_id', 10);
            $table->integer('ex_prog_fk')->unsigned();
            $table->foreign('ex_prog_fk')
                ->references('id')->on('hus_ex_program')
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
        Schema::table('hus_ex_program', function (Blueprint $table) {
            $table->integer('mon')->unsigned();
            $table->integer('tue')->unsigned();
            $table->integer('wed')->unsigned();
            $table->integer('thu')->unsigned();
            $table->integer('fri')->unsigned();
            $table->integer('sat')->unsigned();
            $table->integer('sun')->unsigned();
            
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
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->dropColumn('day_id');
            $table->dropForeign('ex_prog_fk');
        });
    }
}
