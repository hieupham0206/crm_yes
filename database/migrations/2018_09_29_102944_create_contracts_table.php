<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('member_id');
            $table->unsignedInteger('event_data_id');

            $table->double('amount')->default(0);
            $table->double('net_amount')->default(0);

            $table->string('paid_first_date');
            $table->string('paid_first');
            $table->string('paid_last_date');
            $table->string('paid_last');
            $table->string('payment_method');
            $table->string('bank_name');

            $table->tinyInteger('state')->default(-1)->comment('-1: Not yet; 1: Done; 2: Problem');

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
        Schema::dropIfExists('contracts');
    }
}
