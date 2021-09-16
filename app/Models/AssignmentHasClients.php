<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentHasClients extends Model
{
    use HasFactory;
    protected $table = 'CLIENT_XREF_ASSIGNMENT';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function clientInfo(){

        return $this->hasOne('App\Models\Clients','CLIENT_ID','client_id');
    }
}
