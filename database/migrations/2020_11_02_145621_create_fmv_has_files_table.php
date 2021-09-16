<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFmvHasFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fmv_has_files', function (Blueprint $table) {
            $table->id();
            $table->integer('fmv_id')->unsigned()->nullable();
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
        Schema::dropIfExists('fmv_has_files');
    }
}
