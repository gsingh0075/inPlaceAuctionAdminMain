<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlainPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CLIENT', function (Blueprint $table) {
            //
            $table->string('PLAIN_PASSWORD');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CLIENT', function (Blueprint $table) {
            //
            $table->dropColumn('PLAIN_PASSWORD');
        });
    }
}
