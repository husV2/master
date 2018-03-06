<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHusAdminCacheAndAdminCacheMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hus_cache_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('type')->default("json");
            $table->string('description')->default("");
            $table->timestamps();
        });
        Schema::create('hus_admin_cache', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content')->nullable();
            $table->integer('key')->unsigned()->unique();
            $table->timestamps();
        });
        Schema::table('hus_admin_cache', function (Blueprint $table) {
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
        /* This migration has been invalidated */
//        Schema::table('hus_admin_cache', function (Blueprint $table) {
//            $table->dropForeign(['key']);
//        });
//        Schema::drop('hus_cache_keys');
//        Schema::drop('hus_admin_cache');
    }
}
