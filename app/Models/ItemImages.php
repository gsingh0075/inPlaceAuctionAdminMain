<?php

namespace App\Models;

use Carbon\Carbon;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemImages extends Model
{
    use HasFactory;
    protected $table = 'IMAGES';
    protected $primaryKey = 'image_id';
    public $timestamps = false;
    protected $appends = ['imageSignedUrl'];

    public function getImageSignedUrlAttribute(){

        $fileSignedUrl = '';
        // Lets Check our cache DB to get no need to go to google. We have signed URL over there
        //if( \Illuminate\Support\Facades\Cache::get('Item-'.$this->image_id)) {

            //$fileSignedUrl = \Illuminate\Support\Facades\Cache::get('Item-'.$this->image_id);
            //Log::info('Cache Call');
            //Log::info($this->image_id);

       // }   else { // Seems Like new Call.

            //Log::info('Real Call');
            //Log::info($this->image_id);
            $disk = Storage::disk('gcs')->exists('itemImages/'.$this->big_image_file);
            //Log::info($disk);
            if( $disk ) {

                $storage = new StorageClient(
                    [
                        'projectId' => 'inplaceauction-305819',
                        'keyFilePath' => storage_path('app/google/inplaceauction-305819-182aaa61e381.json')
                    ]
                );
                $bucketName = 'admin-inplace-auction';
                $objectName = 'itemImages/'.$this->big_image_file;
                $bucket = $storage->bucket($bucketName);
                $object = $bucket->object($objectName);

                $expirationDate = Carbon::now();
                //$expirationDate->addMinutes(5);
                $expirationDate->addDay();
                $fileSignedUrl = $object->signedUrl($expirationDate->timestamp);
                //Log::info($file);
               // \Illuminate\Support\Facades\Cache::put('Item-'.$this->image_id, $fileSignedUrl);

          //  }

        }

        return $fileSignedUrl;

    }
}
