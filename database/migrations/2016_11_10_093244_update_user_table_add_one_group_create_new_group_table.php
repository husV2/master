<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddOneGroupCreateNewGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_group_5', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('name');
            $table->string('tag');
            $table->timestamps();
        });
        Schema::table('hus_group_5', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')->on('hus_group_4')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        
        Schema::table('hus_user', function (Blueprint $table) {
            $table->integer('hus_group_5_id')->unsigned()->nullable(true);
            
            $table->foreign('hus_group_5_id')
                ->references('id')->on('hus_group_5')
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
        Schema::table('hus_user', function (Blueprint $table) {
            $table->dropForeign('hus_group_5_id');
        });
        Schema::drop('hus_group_5');
    }
}
