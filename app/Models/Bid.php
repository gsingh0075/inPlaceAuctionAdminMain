<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;
    protected $table = 'BID';
    protected $primaryKey = 'BID_ID';
    public $timestamps = false;
    protected $guarded = [];

    public function customer(){
        return $this->hasOne('App\Models\Customer','CUSTOMER_ID','CUSTOMER_ID');
    }

    public function item(){

        return $this->hasOne('App\Models\Items', 'ITEM_ID', 'ITEM_ID');
    }

    public function getBidDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }
}
