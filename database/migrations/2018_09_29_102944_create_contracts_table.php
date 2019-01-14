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

            $table->string('contract_no', 100);
            $table->double('amount')->default(0);
            $table->double('net_amount')->default(0);

            $table->tinyInteger('membership')->default(1)->comment('1: Dynasty; 2: Emerald; 3: Crystal');
            $table->tinyInteger('room_type')->default(1)->comment('1: 1 giường; 2: 2 giường; 3: 3 giường; 4: phòng ngủ');
            $table->tinyInteger('limit')->default(1)->comment('1: 2 lớn, 2 nhỏ <6 2: 4 lớn, 2 nhỏ <6 3: 6 lớn, 2 nhỏ <6');

            $table->timestamp('signed_date')->nullable()->comment('Ngày kí hợp đồng');
            $table->timestamp('effective_time')->nullable();
            $table->integer('start_year')->nullable()->comment('Năm bắt đầu');
            $table->integer('end_time')->nullable()->comment('Số năm');

            $table->double('year_cost')->default(0)->comment('Chi phí hàng năm');
            $table->integer('num_of_payment')->default(0)->comment('Số lần thanh toán');
            $table->double('total_payment')->default(0);

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
