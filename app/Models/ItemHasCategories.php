<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHasCategories extends Model
{
    use HasFactory;
    protected $table = 'CATEGORY_ITEM_XREF';
    protected $primaryKey = 'category_item_xref_id';
    public $timestamps = false;

    public function category() {
        return $this->hasOne('App\Models\Category', 'category_id', 'category_id');
    }

}
