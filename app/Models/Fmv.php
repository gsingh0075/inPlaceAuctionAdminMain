<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fmv extends Model
{
    use HasFactory;
    protected $table = 'FMV';
    protected $primaryKey = 'fmv_id';
    public $timestamps = false;
    protected $guarded = [];

    public function getRequestDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getSentDateAttribute($value){
        
         if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
        //return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id', 'admin_id');
    }

    public function client(){
        return $this->hasOne('App\Models\Clients','CLIENT_ID','client_id');
    }

    public function items() {
        return $this->hasMany('App\Models\FmvHasItems', 'fmv_id', 'fmv_id');
    }

    public function files(){
        return $this->hasMany('App\Models\FmvHasFiles','fmv_id', 'fmv_id');
    }

}
