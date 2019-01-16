<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAddMoreInfoToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            //thông tin người đi kèm
            $table->renameColumn('spouce_name', 'spouse_name');
            $table->renameColumn('spouce_phone', 'spouse_phone');
            $table->renameColumn('spouce_birthday', 'spouse_birthday');
            $table->renameColumn('spouce_email', 'spouse_email');

            $table->string('spouse_title')->nullable();

            $table->string('identity')->nullable();
            $table->unsignedInteger('identity_address')->nullable()->comment('Tỉnh cấp CMND');
            $table->date('identity_date')->nullable();

            $table->string('spouse_identity')->nullable();
            $table->unsignedInteger('spouse_identity_address')->nullable()->comment('Tỉnh cấp CMND');
            $table->date('spouse_identity_date')->nullable();

            $table->dropColumn([
                'product_type',
                'membership_type',
            ]);

            $table->unsignedInteger('city')->nullable()->comment('Tỉnh thành phố')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
}
