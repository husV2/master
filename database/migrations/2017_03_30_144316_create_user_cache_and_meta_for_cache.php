<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCacheAndMetaForCache extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_user_cache', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('key')->unsigned();
            $table->string('content')->nullable();
            $table->timestamps();
        });
        Schema::table('hus_user_cache', function (Blueprint $table) {
            $table->foreign('key')
                ->references('id')->on('hus_cache_keys')
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
        /* This migration has been invalidated*/
        //Schema::drop('hus_user_cache');
    }
}
