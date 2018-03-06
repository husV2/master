<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddMoreGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_user', function (Blueprint $table) {
            $table->integer('hus_group_1_id')->unsigned()->default(1);
            $table->integer('hus_group_2_id')->unsigned()->default(1);
            $table->integer('hus_group_3_id')->unsigned()->nullable(true);
            
            $table->foreign('hus_group_1_id')
                ->references('id')->on('hus_group_1')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('hus_group_2_id')
                ->references('id')->on('hus_group_2')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('hus_group_3_id')
                ->references('id')->on('hus_group_3')
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
            //
        });
    }
}
