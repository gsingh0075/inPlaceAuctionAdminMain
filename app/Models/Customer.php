<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'CUSTOMER';
    protected $primaryKey = 'CUSTOMER_ID';
    public $timestamps = false;

}
