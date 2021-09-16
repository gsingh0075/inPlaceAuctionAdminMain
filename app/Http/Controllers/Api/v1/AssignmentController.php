<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    //
    public function show(){

       $assignment = Assignment::with(['items.invoiceAuth.invoice'])
                     ->whereDoesntHave('items.invoiceAuth')
                     ->whereHas('items')
                     ->where('isopen', '=', '1')
                     ->where('approved', '=', 1)
                     ->get();

        return response( ['status' => true,
            'data' => $assignment ] , 200);

    }
}
