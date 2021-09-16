<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorAuthItem extends Model
{
    use HasFactory;
    protected $table = 'CONTRACTOR_AUTH_XREF_ITEM';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function item() {
        return $this->hasOne('App\Models\Items', 'ITEM_ID', 'item_id');
    }

}
