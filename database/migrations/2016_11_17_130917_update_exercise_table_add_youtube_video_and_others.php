<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExerciseTableAddYoutubeVideoAndOthers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_exercise', function (Blueprint $table) {
            $table->string('text')->nullable(true);
            $table->string('audio')->nullable(true);
            $table->string('video')->nullable(true);
            $table->string('image')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hus_exercise', function (Blueprint $table) {
            //
        });
    }
}
