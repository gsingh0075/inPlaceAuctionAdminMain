<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsHasContacts extends Model
{
    use HasFactory;
    protected $table = 'CLIENT_CONTACTS';
    protected $primaryKey = 'CLIENT_CONTACTS_ID';
    public $timestamps = false;

}
