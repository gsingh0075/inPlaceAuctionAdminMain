<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\GeocodeHelper;
use App\Http\Controllers\Controller;
use App\Jobs\updateLatLngContractor;
use App\Models\Contractors;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions;
use Illuminate\Http\Response;

class ContractorController extends Controller
{
    //

    public function updateLatLng(){

        updateLatLngContractor::dispatch();

        return response( ['status' => true,
                          'data' => 'Latitude and Longitude will be updated soon' ] , 200);

    }

    public function importContractors( Request $request){

        $file = $request->file('uploaded_file');
        if ($file) {

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $this->checkUploadedFileProperties($extension, $fileSize);
            $location = 'uploads'; //Created an "uploads" folder for that
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename); // Reading file
            $file = fopen($filepath, "r");

            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
//Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
// Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading$j = 0;
           //echo '<pre>'; print_r($importData_arr);

            //Lets Insert the contractor data
            $j=1; foreach($importData_arr as $d){

                $first_name = '';
                $last_name = '';

                if(isset($d[0]) && !empty($d[0])){

                    $n = explode(' ',$d[0]);

                    if(isset($n) && !empty($n)){
                        $first_name = $n[0];
                        $last_name = $n[1];
                        }
                    }

                $contractor = new Contractors();
                $contractor->first_name = $first_name;
                $contractor->last_name =  $last_name;
                $contractor->company = $d[1];
                $contractor->address1 = $d[2];
                $contractor->state = $d[3];
                $contractor->phone = $d[4];
                $contractor->email = $d[5];
                $contractor->notes = $d[7];

                $contractor->save();

                $contractorAddress =  $d[2].''. $d[3];
                $address= urlencode($contractorAddress);

                $geoCode = new GeocodeHelper();
                $LatLng = $geoCode->geocode($address);

                //Log::info($LatLng);
                $contractorLat = '';
                $contractorLng = '';

                if( (isset($LatLng['latitude']) && !empty($LatLng['longitude'])) && ( isset($LatLng['longitude']) && !empty($LatLng['longitude']) ) ) {
                    $contractorLat = $LatLng['latitude'];
                    $contractorLng = $LatLng['longitude'];
                }

                $contractor->update([
                    'username' => 'CON'. $contractor->contractor_id. 'IPA',
                    'password' => rand(1000, 50000),
                    'lat' => $contractorLat,
                    'lng' => $contractorLng
                ]);


                $j++;  }


            return response()->json([
                'message' => "$j records successfully uploaded"
            ]);

        } else {
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }


    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize >= $maxFileSize) {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error}
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

}
