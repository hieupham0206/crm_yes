<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAddPaymentFeeToPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_details', function (Blueprint $table) {
            $table->unsignedInteger('payment_fee')->after('payment_cost_id')->comment('phí + phí trả góp nếu có')->nullable();
            $table->unsignedInteger('payment_installment_id')->after('payment_fee')->comment('Cột trả góp, link toi bang payment_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_details', function (Blueprint $table) {
            //
        });
    }
}
