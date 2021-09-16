<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRemittanceHasExpense extends Model
{
    use HasFactory;
    protected $table = 'client_remittance_has_expense';
    protected $primaryKey = 'id';
    protected $guarded = [];

}
