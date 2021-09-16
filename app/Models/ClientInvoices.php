<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Traits\Units;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoices extends Model
{
    use HasFactory;
    protected $table = 'CLIENT_INVOICE';
    protected $primaryKey = 'client_invoice_id';
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['invoice_color_status','time_pay_days'];

    public function lines(){

        return $this->hasMany('App\Models\ClientInvoiceLines','client_invoice_id','client_invoice_id');

    }

    public function client(){
        return $this->hasOne('App\Models\Clients','CLIENT_ID','client_id');
    }

    public function getCreateDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getGeneratedDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getPaidDt1Attribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getSentDtAttribute($value){
        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getInvoiceColorStatusAttribute (){

        $differenceInDays = 0;

        if( !empty($this->sent_dt) ){
            // Today Date
            $currentDate = Carbon::now()->format('Y-m-d H:i:s');
            $assignmentDate = Carbon::parse($this->sent_dt);
            $differenceInDays = $assignmentDate->diffInDays($currentDate);
        }

        return $differenceInDays;
    }

    public function getTimePayDaysAttribute (){

        $payInDays = 0;

        if( !empty($this->sent_dt) && !empty($this->paid_dt1) ){
            // Today Date
            $sentDate = Carbon::parse($this->sent_dt);
            $paidDate = Carbon::parse($this->paid_dt1);
            $payInDays = $paidDate->diffInDays($sentDate);
        }

        return $payInDays;
    }
}
