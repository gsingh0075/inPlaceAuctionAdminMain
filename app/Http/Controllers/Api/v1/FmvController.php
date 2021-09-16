<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Fmv;
use Illuminate\Http\Request;

class FmvController extends Controller
{
    //
    public function show(){

        $allFmv = Fmv::with(['client','items'])->get();

        return response( ['status' => true,
            'data' => $allFmv ] , 200);

    }
}
