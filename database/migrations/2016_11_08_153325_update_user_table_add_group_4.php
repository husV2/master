<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTableAddGroup4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_user', function (Blueprint $table) {
            $table->integer('hus_group_4_id')->unsigned()->nullable(true);
            
            $table->foreign('hus_group_4_id')
                ->references('id')->on('hus_group_4')
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
