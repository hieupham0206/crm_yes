<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAddMoreInfoToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('contract_no', 100)->after('id');

            $table->tinyInteger('membership')->default(1)->nullable()->comment('1: Dynasty; 2: Emerald; 3: Crystal');
            $table->tinyInteger('room_type')->default(1)->nullable()->comment('1: 1 giường; 2: 2 giường; 3: 3 giường; 4: phòng ngủ');
            $table->tinyInteger('limit')->default(1)->nullable()->comment('1: 2 lớn, 2 nhỏ <6 2: 4 lớn, 2 nhỏ <6 3: 6 lớn, 2 nhỏ <6');

            $table->timestamp('signed_date')->nullable()->comment('Ngày kí hợp đồng');
            $table->timestamp('effective_time')->nullable();
            $table->date('start_date')->nullable()->comment('Năm bắt đầu');
            $table->integer('end_time')->nullable()->comment('Số năm');

            $table->double('year_cost')->nullable()->default(0)->comment('Chi phí hàng năm');
            $table->integer('num_of_payment')->nullable()->default(0)->comment('Số lần thanh toán');
            $table->double('total_payment')->nullable()->default(0);

            $table->dropColumn([
                'paid_first_date',
                'paid_first',
                'paid_last_date',
                'paid_last',
                'payment_method',
                'bank_name',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
}
