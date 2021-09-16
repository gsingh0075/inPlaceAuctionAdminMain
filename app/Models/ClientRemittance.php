<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRemittance extends Model
{
    use HasFactory;
    protected $table = 'CLIENT_REMITTANCE';
    protected $primaryKey = 'CLIENT_REMITTANCE_ID';
    public $timestamps = false;
    protected $guarded = [];

    public function invoice(){
        return $this->hasOne('App\Models\Invoice','invoice_auth_id','INVOICE_AUTH_ID');
    }

    public function client(){
        return $this->hasOne('App\Models\Clients','CLIENT_ID', 'CLIENT_ID');
    }

    public function remittanceExpense(){
        return $this->hasMany('App\Models\ClientRemittanceHasExpense','client_remittance_id', 'CLIENT_REMITTANCE_ID');
    }

    public function getRemittanceDateAttribute($value){
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

    public function getSentDateAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }
}
