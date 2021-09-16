<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'STATE';
    protected $primaryKey = 'STATE_ID';
    public $timestamps = false;
    protected $keyType = 'string';
}
