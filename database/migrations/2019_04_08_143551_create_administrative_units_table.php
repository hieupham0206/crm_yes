<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministrativeUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_units', function (Blueprint $table) {
            $table->increments('id');

            $table->string('city_name')->comment('Tên tỉnh/thành phố');
            $table->string('city_code')->comment('Mã tỉnh/thành phố');

            $table->string('county_name')->comment('Tên quận/huyện phố');
            $table->string('county_code')->comment('Mã quận/huyện phố');

            $table->string('ward_name')->comment('Tên phường/xã phố');
            $table->string('ward_code')->comment('Mã phường/xã phố');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative_units');
    }
}
