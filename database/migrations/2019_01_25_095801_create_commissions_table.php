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

            $table->double('sdm_percent')->default(0)->nullable();
            $table->double('to_percent')->default(0)->nullable();
            $table->double('tele_percent')->default(0)->nullable();
            $table->double('private_percent')->default(0)->nullable();
            $table->double('ambassador_percent')->default(0)->nullable();
            $table->double('rep_percent')->default(0)->nullable();
            $table->double('cs_percent')->default(0)->nullable();
            $table->double('homesit_percent')->default(0)->nullable();
            $table->double('total_percent')->default(0)->nullable();

            $table->double('provisional_commission')->default(0)->nullable();

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
