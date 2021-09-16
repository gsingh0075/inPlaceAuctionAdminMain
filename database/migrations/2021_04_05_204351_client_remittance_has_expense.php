<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientRemittanceHasExpense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_remittance_has_expense', function (Blueprint $table) {
            $table->id();
            $table->integer('client_remittance_id')->unsigned()->nullable();
            $table->string('expenseType', 100);
            $table->decimal('expenseAmount');
            $table->string('logs', 100)->nullable();
            //$table->foreign('client_remittance_id')->references('CLIENT_REMITTANCE_ID')->on('CLIENT_REMITTANCE');
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
        Schema::dropIfExists('client_remittance_has_expense');
    }
}
