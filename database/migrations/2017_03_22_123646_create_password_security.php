<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordSecurity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_password_security', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('ip', 100);
            $table->string('old', 70);
            $table->string('token');
            $table->timestamps();
        });
        Schema::table('hus_password_security', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hus_password_security');
    }
}
