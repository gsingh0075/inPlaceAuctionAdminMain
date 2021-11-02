<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWriteOffToClientInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CLIENT_INVOICE', function (Blueprint $table) {
            $table->boolean('write_off')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CLIENT_INVOICE', function (Blueprint $table) {
            $table->dropColumn('write_off');
        });
    }
}
