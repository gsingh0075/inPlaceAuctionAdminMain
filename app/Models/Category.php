<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'CATEGORY';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    public function items(){

        return $this->hasMany('App\Models\ItemHasCategories','category_id','category_id');
    }
}
