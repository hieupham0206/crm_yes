<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('contract_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->double('net_total')->default(0)->nullable();

            $table->double('to_percent')->default(0)->nullable();
            $table->double('tele_amount')->default(0)->nullable();

            $table->double('rep_percent')->default(0)->nullable();
            $table->double('cs_percent')->default(0)->nullable();
            $table->double('total_percent')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commissions');
    }
}
