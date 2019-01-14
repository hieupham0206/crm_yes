<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callbacks', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('lead_id');
//            $table->unique(['user_id', 'lead_id']);

            $table->timestamp('callback_datetime')->nullable();
            $table->tinyInteger('state')->default(\App\Enums\Confirmation::NO)->comment('1: Done; -1: Not Yet');
            $table->timestamp('call_date')->nullable()->comment('Thời gian đã gọi lại');

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
        Schema::dropIfExists('callbacks');
    }
}
