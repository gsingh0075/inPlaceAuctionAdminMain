<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $table = 'ASSIGNMENT';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;
    protected $appends = ['assignment_color_status'];


    public function getAssignmentColorStatusAttribute (){

        $differenceInDays = 0;

        if( !empty($this->dt_stmp) ){
            // Today Date
            $currentDate = Carbon::now()->format('Y-m-d H:i:s');
            $assignmentDate = Carbon::parse($this->dt_stmp);
            $differenceInDays = $assignmentDate->diffInDays($currentDate);
        }

        return $differenceInDays;
    }

    public function getDtLeaseInceptionAttribute($value){

        if(!empty($value)) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }

    }

    public function getDtStmpAttribute($value){

        if(!empty($value)) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }

    }

    public function getLstUpdAttribute($value){
        if(!empty($value)) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }
    }

    public function getRecoveryDtAttribute($value){

        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }

    }

    public function communicationsPrivate(){
        return $this->hasMany('App\Models\Communications','assignment_id','assignment_id')->where('isprivate','=',1)->orderBy('dt_stmp', 'desc');
    }

    public function communicationsPublic(){
        return $this->hasMany('App\Models\Communications','assignment_id','assignment_id')->where('ispublic','=',1)->orderBy('dt_stmp', 'desc');;
    }

    public function client(){

        return $this->hasOne('App\Models\AssignmentHasClients','assignment_id','assignment_id');
    }

    public function files(){

        return $this->hasMany('App\Models\AssignmentHasFiles', 'assignment_id', 'assignment_id');
    }

    public function clientFiles(){

        return $this->hasMany('App\Models\AssignmentHasFiles', 'assignment_id', 'assignment_id')->where('status', '=', 1);
    }

    public function items(){

        return $this->hasMany('App\Models\Items', 'ASSIGNMENT_ID', 'assignment_id');
    }
}
