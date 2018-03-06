<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_user_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('writer_id')->unsigned();
            $table->string('message', 255)->default('');
            $table->timestamps();
        });
        Schema::table('hus_user_messages', function (Blueprint $table) {
            $table->unique(['created_at', 'user_id', 'writer_id']);
            $table->foreign('user_id')
                ->references('id')->on('hus_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('writer_id')
                ->references('id')->on('hus_user')
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
        Schema::drop('hus_user_messages');
    }
}
