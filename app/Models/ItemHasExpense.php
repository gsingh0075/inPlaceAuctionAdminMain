<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHasExpense extends Model
{
    use HasFactory;
    protected $table = 'ITEM_EXPENSE';
    protected $primaryKey = 'item_expense_id';
    public $timestamps = false;
    protected $guarded = [];

    public function expenseAuth(){
         return $this->hasOne('App\Models\ClientInvoiceLines', 'item_expense_id', 'item_expense_id');
    }

    public function item(){
        return $this->hasOne('App\Models\Items','ITEM_ID','item_id');
    }

    public function getExpenseDtAttribute($value){
        //Log::info($value);
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

}
