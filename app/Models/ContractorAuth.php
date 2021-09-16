<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorAuth extends Model
{
    use HasFactory;
    protected $table = 'CONTRACTOR_AUTH';
    protected $primaryKey = 'contractor_auth_id';
    public $timestamps = false;
    protected $guarded = [];

    public function contractor(){
        return $this->hasOne('App\Models\Contractors', 'contractor_id','contractor_id');
    }
    public function authItems() {
        return $this->hasMany('App\Models\ContractorAuthItem', 'contractor_auth_id', 'contractor_auth_id');
    }

    public function getCreateDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getSentDateAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }
    public function getGeneratedDateAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }
}
