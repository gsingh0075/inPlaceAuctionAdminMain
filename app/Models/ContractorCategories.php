<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorCategories extends Model
{
    use HasFactory;
    protected $table = 'CONTRACTOR_CATEGORIES';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $guarded = [];

    public function category(){
        return $this->hasOne('App\Models\Category','category_id','Category_id');
    }
}
