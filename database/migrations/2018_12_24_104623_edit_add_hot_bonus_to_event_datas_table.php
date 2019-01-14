<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAddHotBonusToEventDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_datas', function (Blueprint $table) {
            $table->tinyInteger('hot_bonus')->default(-1)->comment('-1: Không; 1: Có');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_datas', function (Blueprint $table) {
            $table->dropColumn('hot_bonus');
        });
    }
}
