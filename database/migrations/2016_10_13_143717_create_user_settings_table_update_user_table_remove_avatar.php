<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTableUpdateUserTableRemoveAvatar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_user_settings', function (Blueprint $table) {
            $table->integer('user_fk')->unsigned();
            $table->string('avatar')->nullable();
            $table->integer('ex_program_fk')->unsigned()->default(1);
            $table->integer('event_interval')->default(30);
        });
        Schema::table('hus_user_settings', function (Blueprint $table){
            $table->foreign('user_fk')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('ex_program_fk')
                ->references('id')->on('hus_ex_program')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('hus_user', function (Blueprint $table){
            $table->dropColumn('avatar');
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
            $table->string('avatar')->nullable();
        });
        Schema::drop('hus_user_settings');
    }
}
