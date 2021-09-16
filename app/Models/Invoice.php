<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'INVOICE_AUTH';
    protected $primaryKey = 'invoice_auth_id';
    public $timestamps = false;
    protected $guarded = [];

    public function items(){

        return $this->hasMany('App\Models\InvoiceHasItems','invoice_auth_id');

    }

    public function remittance(){

        return $this->hasOne('App\Models\ClientRemittance','INVOICE_AUTH_ID', 'invoice_auth_id');
    }

    public function customer() {

        return $this->hasOne('App\Models\Customer','CUSTOMER_ID','customer_id');
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

    public function getPaidDtAttribute($value){
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

    public function getInvoiceColorStatusAttribute (){

        $differenceInDays = 0;

        if( !empty($this->sent_date) ){
            // Today Date
            $currentDate = Carbon::now()->format('Y-m-d H:i:s');
            $assignmentDate = Carbon::parse($this->sent_date);
            $differenceInDays = $assignmentDate->diffInDays($currentDate);
        }

        return $differenceInDays;
    }

}
