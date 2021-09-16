<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\updateLatLngItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function updateLatLng(){

        updateLatLngItem::dispatch();

        return response( ['status' => true,
            'data' => 'Latitude and Longitude will be updated soon' ] , 200);
    }
}
