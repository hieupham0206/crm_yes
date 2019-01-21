<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('payment_cost_id');

            $table->timestamp('pay_date')->nullable()->comment('Ngày hẹn thanh toán');
            $table->integer('pay_time')->nullable()->comment('Lần thanh toán');

            $table->double('total_paid_deal')->default(0);
            $table->double('total_paid_real')->default(0);

            $table->string('bank_name')->nullable();
            $table->string('bank_no')->nullable();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('payment_details');
    }
}
