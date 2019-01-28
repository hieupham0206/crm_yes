<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_roles', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('role_id')->nullable();

            $table->tinyInteger('specification')->nullable()->default(1)->comment('-Tele => By Tele
                                                                                                        -Tự hẹn (REP) => Private
                                                                                                        -Khách giới thiệu (By Customer - Ambassador) => By Ambassador');
            $table->tinyInteger('level')->nullable()->default(1)->comment('tổng giá trị net Total vượt 100,000,000 thì được 1.5% trên 100,000,000 đó (Level1)
                                                                                                tổng giá trị net Total vượt 200,000,000 thì được 1.8% trên 100,000,000 đó (Level2)
                                                                                                tổng giá trị net Total vượt 300,000,000 thì được 2.0% trên 100,000,000 đó (Level3) => chỉ áp dụng cho SDM');

            $table->double('percent_commission')->default(0)->nullable();
            $table->double('percent_commission_bonus')->default(0)->nullable();
            $table->double('deal_completed')->default(0)->nullable();

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
        Schema::dropIfExists('commission_roles');
    }
}
