<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoiceLines extends Model
{
    use HasFactory;
    protected $table = 'CLIENT_INVOICE_LINES';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function expense(){

        return $this->hasOne('App\Models\ItemHasExpense','item_expense_id','item_expense_id');
    }

    public function invoice(){

        return $this->hasOne('App\Models\ClientInvoices', 'client_invoice_id', 'client_invoice_id');
    }
}
