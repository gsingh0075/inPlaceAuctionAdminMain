<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communications extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'COMMUNICATIONS';
    protected $primaryKey = 'communications_id';
    public $timestamps = false;

    public function getDtStmpAttribute($value){
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
