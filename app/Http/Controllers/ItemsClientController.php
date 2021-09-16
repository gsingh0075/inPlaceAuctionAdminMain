<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class ItemsClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:client');
    }

    public function get(){

        $clientId = auth()->user()->CLIENT_ID;

        $items = Items::whereHas('assignment.client',  function (Builder $query) use ($clientId) {
                            $query->where('client_id', '=', $clientId);
            })->with(['categories','assignment.client.clientInfo','bids'])->get();

        //Log::info($items);

        return view('clientDashboard.items.list', [ 'items' => $items ]);
    }
}
