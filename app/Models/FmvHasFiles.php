<?php

namespace App\Models;

use Carbon\Carbon;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FmvHasFiles extends Model
{
    use HasFactory;
    protected $table = 'fmv_has_files';
    protected $primaryKey = 'id';
    protected $appends = ['fileSignedUrl'];

    public function getFileSignedUrlAttribute(){

        $disk = Storage::disk('gcs')->exists($this->filename);

        if( $disk ) {
            $storage = new StorageClient(
                [
                    'projectId' => 'inplaceauction-305819',
                    'keyFilePath' => storage_path('app/google/inplaceauction-305819-182aaa61e381.json')
                ]
            );
            $bucketName = 'admin-inplace-auction';
            $objectName = $this->filename;
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
