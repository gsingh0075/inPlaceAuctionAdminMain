<?php

use App\Models\ItemHasExpense;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('status');
            $table->timestamps();
        });

        // Lets get expense to be added in the system
        $expenseType = ItemHasExpense::distinct()->orderBy('expense_type')->get(['expense_type']);
        if(isset($expenseType) && !empty($expenseType)){
            foreach($expenseType as $type){
                $expense = new \App\Models\ExpenseType();
                $expense->name =  $type->expense_type;
                $expense->status =  true;
                $expense->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_type');
    }
}
