<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStatistics1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_statistics_1', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_fk')->unsigned();
            $table->bigInteger('completes')->default(0);
            $table->bigInteger('skips')->default(0);
            $table->bigInteger('timeSpent')->default(0);
            $table->timestamps();
        });
        Schema::table('hus_statistics_1', function (Blueprint $table) {
            $table->foreign('group_fk')
                ->references('id')->on('hus_group_1')
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
        Schema::drop('hus_statistics_1');
    }
}
