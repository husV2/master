<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAchievementsAddCategoryFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hus_achievement', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->default(null)->unsigned();
            $table->foreign('category_id')
                ->references('id')->on('hus_ex_category')
                ->onDelete('set NULL')
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
        Schema::table('hus_achievement', function (Blueprint $table) {
            $table->dropForeign('hus_achievement_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
}
