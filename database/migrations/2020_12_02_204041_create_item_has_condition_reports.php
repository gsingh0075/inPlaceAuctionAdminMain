<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemHasConditionReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_has_condition_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->unsigned()->nullable();
            $table->char('filename', 100);
            $table->char('fileType', '50');
            $table->boolean('status');
            $table->text('logs');
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
        Schema::dropIfExists('item_has_condition_reports');
    }
}
