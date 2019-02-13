<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_costs', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('payment_method')->comment('1: Tiền mặt
2: Trả góp ngân hàng
3: Cà thẻ
4: Chuyển khoản
5: Phí cố định')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cost')->nullable();
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
        Schema::dropIfExists('payment_costs');
    }
}
