<?php

namespace App\Jobs;

use App\Helpers\GeocodeHelper;
use App\Models\Contractors;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class updateLatLngContractor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Contractors::chunk(50, function ($contractors) {
             foreach($contractors as $contractor) {
                 //Log::info($contractor['address1']);

                 if( isset($contractor['address1']) && !empty($contractor['address1'])) {

                     $contractorAddress = $contractor['address1'].''.$contractor['state'].''.$contractor['city'].''.$contractor['zip1'];
                     $address= urlencode($contractorAddress);

                     $geoCode = new GeocodeHelper();
                     $LatLng = $geoCode->geocode($address);

                     Log::info($LatLng);

                     if( (isset($LatLng['latitude']) && !empty($LatLng['longitude'])) && ( isset($LatLng['longitude']) && !empty($LatLng['longitude']) ) ) {

                         $contractor->update(['lat' => $LatLng['latitude'], 'lng' => $LatLng['longitude']]);


                     } else {

                         Log::info( 'Lat Lng cannot be found for '.$contractor['contractor_id']);
                     }

                 } else {

                     Log::info( 'Address cannot be found for '.$contractor['contractor_id']);
                 }
             }
        });
    }
}
