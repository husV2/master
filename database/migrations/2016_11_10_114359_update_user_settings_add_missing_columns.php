<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserSettingsAddMissingColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_user_settings', function (Blueprint $table) {
            $table->integer('workhours')->default(8);
            $table->string('theme')->default('#269b9e');
            $table->string('motto')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hus_user_settings', function (Blueprint $table) {
           
        });
    }
}
