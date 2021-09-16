<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractors extends Model
{
    use HasFactory;
    protected $table = 'CONTRACTOR';
    protected $primaryKey = 'contractor_id';
    public $timestamps = false;
    protected $guarded = [];

    public function contractorCategories(){
        return $this->hasMany('App\Models\ContractorCategories','Contractor_ID','contractor_id');
    }
}
