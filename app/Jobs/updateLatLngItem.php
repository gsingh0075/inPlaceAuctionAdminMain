<?php

namespace App\Jobs;

use App\Helpers\GeocodeHelper;
use App\Models\Items;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class updateLatLngItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {

    }


    public function handle()
    {

        Items::chunk(50, function ($items) {
            foreach($items as $item) {

                if( isset($item['LOC_CITY']) && !empty($item['LOC_CITY'])) {

                    $itemAddress = $item['LOC_CITY'].''.$item['LOC_STATE'].''.$item['LOC_ZIP'];
                    $address= urlencode($itemAddress);

                    $geoCode = new GeocodeHelper();
                    $LatLng = $geoCode->geocode($address);

                    Log::info($LatLng);

                    if( (isset($LatLng['latitude']) && !empty($LatLng['longitude'])) && ( isset($LatLng['longitude']) && !empty($LatLng['longitude']) ) ) {

                        $item->update(['lat' => $LatLng['latitude'], 'lng' => $LatLng['longitude']]);


                    } else {

                        Log::info( 'Lat Lng cannot be found for '.$item['ITEM_ID']);
                    }

                } else {

                    Log::info( 'Address cannot be found for '.$item['ITEM_ID']);
                }
            }
        });

    }
}
