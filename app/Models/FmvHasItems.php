<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FmvHasItems extends Model
{
    use HasFactory;
    protected $table = 'FMV_ITEM';
    protected $primaryKey = 'fmv_item_id';
    public $timestamps = false;
}
