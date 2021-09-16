<?php

namespace App\Models;

use Carbon\Carbon;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemHasConditionReports extends Model
{
    use HasFactory;
    protected $table = 'item_has_condition_reports';
    protected $primaryKey = 'id';
    protected $appends = ['fileSignedUrl'];
    protected $guarded = [];

    public function getFileSignedUrlAttribute(){

        $disk = Storage::disk('gcs')->exists('itemConditionReport/'.$this->item_id.'/'.$this->filename);


        if( $disk ) {
            $storage = new StorageClient(
                [
                    'projectId' => 'inplaceauction-305819',
                    'keyFilePath' => storage_path('app/google/inplaceauction-305819-182aaa61e381.json')
                ]
            );
            $bucketName = 'admin-inplace-auction';
            $objectName = 'itemConditionReport/'.$this->item_id.'/'.$this->filename;
            $bucket = $storage->bucket($bucketName);
            $object = $bucket->object($objectName);

            $expirationDate = Carbon::now();
            $expirationDate->addMinutes(5);
            $file = $object->signedUrl($expirationDate->timestamp);
            //Log::info($file);
            return $file;
        }

        return '';
    }
}
