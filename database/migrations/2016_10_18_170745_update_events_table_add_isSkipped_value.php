<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTableAddIsSkippedValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->boolean('isSkipped')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hus_ex_event', function (Blueprint $table) {
            $table->dropColumn('isSkipped');
        });
    }
}
