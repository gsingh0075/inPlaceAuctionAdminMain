<?php

namespace App\Models;

use Carbon\Carbon;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Clients extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'client';
    protected $table = 'CLIENT';
    protected $primaryKey = 'CLIENT_ID';
    public $timestamps = false;
    protected $appends = ['imageSignedUrl'];

    protected $hidden = ['password'];

    public function contacts(){

        return $this->hasMany('App\Models\ClientsHasContacts','CLIENT_ID','CLIENT_ID');
    }

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    public function invoices(){

        return $this->hasMany('App\Models\ClientInvoices', 'client_id', 'CLIENT_ID');
    }

    public function communication(){
        return $this->hasMany('App\Models\Communications','client_id','CLIENT_ID')->whereNull('assignment_id')->whereNull('item_id')->orderBy('dt_stmp', 'desc');
    }

    public function getDtStmpAttribute($value){

        if(!empty($value)){
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            return '';
        }

    }

    public function getImageSignedUrlAttribute (){

        /*$disk = Storage::disk('gcs')->exists('/clientImages/'.$this->IMAGE);

        if( $disk ) {
            $storage = new StorageClient(
                [
                    'projectId' => '848528709093',
                    'keyFilePath' => storage_path('app/google/bold-origin-294912-b916260e934a.json')
                ]
            );
            $bucketName = 'inplaceauction';
            $objectName = $this->filename;
            $bucket = $storage->bucket($bucketName);
            $object = $bucket->object($objectName);

            $expirationDate = Carbon::now();
            $expirationDate->addMinutes(5);
            $file = $object->signedUrl($expirationDate->timestamp);
            //Log::info($file);
            return $file;
        }*/
        return '';
    }

}
