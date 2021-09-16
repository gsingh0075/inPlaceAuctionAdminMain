<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHasItems extends Model
{
    use HasFactory;
    protected $table = 'INVOICE_AUTH_XREF_ITEM';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function item(){

        return $this->hasOne('App\Models\Items','ITEM_ID','item_id');
    }

    public function invoice(){

        return $this->hasOne('App\Models\Invoice', 'invoice_auth_id', 'invoice_auth_id');
    }

}
