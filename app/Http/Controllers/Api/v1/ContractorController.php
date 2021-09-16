<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\updateLatLngContractor;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    //

    public function updateLatLng(){

        updateLatLngContractor::dispatch();

        return response( ['status' => true,
                          'data' => 'Latitude and Longitude will be updated soon' ] , 200);

    }
}
