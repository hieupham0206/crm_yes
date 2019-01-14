<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lead_id');
//            $table->unique(['user_id', 'lead_id']);

            $table->string('code', 10)->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_phone')->nullable();
            $table->timestamp('appointment_datetime')->nullable();

//            $table->tinyInteger('type')->default(1)->comment('1: Email; 2: SMS ; 3: Both; 4: InState');

            $table->tinyInteger('state')->default(\App\Enums\Confirmation::YES)->comment('-1: Hủy; 1: Sử dụng;');
            $table->tinyInteger('is_show_up')->default(\App\Enums\Confirmation::NO)->comment('-1: Không; 1: Có;');
            $table->tinyInteger('is_queue')->default(\App\Enums\Confirmation::NO)->comment('-1: Không; 1: Có;  2: Chưa biết');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
