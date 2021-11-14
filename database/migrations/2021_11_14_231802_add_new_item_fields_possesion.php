<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewItemFieldsPossesion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ITEM', function (Blueprint $table) {
            $table->string('storage_location',100)->nullable()->after('IN_POSSESSION');
            $table->string('storage_contact_name', 100)->nullable()->after('storage_location');
            $table->string('storage_contact_number',100)->nullable()->after('storage_contact_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ITEM', function (Blueprint $table) {
            $table->dropColumn('storage_location');
            $table->dropColumn('storage_contact_name');
            $table->dropColumn('storage_contact_number');
        });
    }
}
