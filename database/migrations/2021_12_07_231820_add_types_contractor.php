<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypesContractor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CONTRACTOR', function (Blueprint $table) {
            $table->boolean('is_equipment_contractor')->default(0);
            $table->boolean('is_appraisal_contractor')->default(0);
            $table->boolean('is_inspection_contractor')->default(0);
        });

        \Illuminate\Support\Facades\DB::statement('UPDATE CONTRACTOR set is_equipment_contractor = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CONTRACTOR', function (Blueprint $table) {
            $table->dropColumn('is_equipment_contractor');
            $table->dropColumn('is_appraisal_contractor');
            $table->dropColumn('is_inspection_contractor');
        });

    }
}
