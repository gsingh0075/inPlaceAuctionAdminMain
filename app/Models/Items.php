<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Items extends Model
{
    use HasFactory;
    protected $table = 'ITEM';
    protected $primaryKey = 'ITEM_ID';
    public $timestamps = false;
    protected $guarded = [];

    public function categories() {
        return $this->hasMany('App\Models\ItemHasCategories', 'item_id', 'ITEM_ID');
    }

    /*public function images(){
        return $this->hasMany('App\Models\ItemImages', 'item_id', 'ITEM_ID');
    }*/

    public function reports(){
        return $this->hasMany('App\Models\ItemHasConditionReports','item_id', 'ITEM_ID');
    }

    public function clientReports(){
        return $this->hasMany('App\Models\ItemHasConditionReports','item_id', 'ITEM_ID')->where('status', '=', 1);
    }

    public function assignment(){
        return $this->hasOne('App\Models\Assignment','assignment_id','ASSIGNMENT_ID');
    }

    public function itemContractor(){
        return $this->hasOne('App\Models\ContractorAuthItem','item_id','ITEM_ID');
    }

    public function bids(){
        return $this->hasMany('App\Models\Bid', 'ITEM_ID', 'ITEM_ID');
    }

    public function expense(){
        return $this->hasMany('App\Models\ItemHasExpense','item_id','ITEM_ID');
    }

    public function invoiceAuth(){
        return $this->hasOne('App\Models\InvoiceHasItems', 'item_id','ITEM_ID');
    }

    public function getOrigSoldDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getInplaceSaleDateAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getItemRecoveryDtAttribute($value){
        //Log::info($value);
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

}
