<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableExerciseCategoryAddThemeColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_ex_category', function (Blueprint $table) {
            $table->string('color')->default("#269b9e");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hus_ex_category', function (Blueprint $table) {
            //
        });
    }
}
