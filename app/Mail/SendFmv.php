<?php

namespace App\Mail;

use App\Models\Fmv;
use App\Models\FmvHasFiles;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\File;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SendFmv extends Mailable
{
    use Queueable, SerializesModels;

    public $fmv;

    public function __construct(Fmv $fmv)
    {
        $this->fmv = $fmv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $fmvData = Fmv::with(['files','items'])->findorfail($this->fmv->fmv_id);
        $fmvPdf = PDF::loadView('fmv.fmvPdf', ['fmv' => $fmvData]);
        $uploadFilePath = storage_path('app/public/fmv/' . $this->fmv->fmv_id);

        if (!file_exists($uploadFilePath)) {
            mkdir($uploadFilePath, 0777, true);
        }

        if( file_exists(storage_path('app/public/fmv/'.$this->fmv->fmv_id.'/fmv_estimate_'.$this->fmv->fmv_id.'.pdf'))) {
            unlink(storage_path('app/public/fmv/'.$this->fmv->fmv_id.'/fmv_estimate_'.$this->fmv->fmv_id.'.pdf'));
        }

        $fmvPdf->save(storage_path('app/public/fmv/'.$this->fmv->fmv_id.'/fmv_estimate_'.$this->fmv->fmv_id.'.pdf'));
        //Signed URl
        $url = URL::temporarySignedRoute('fmvToAssignment', now()->addMinutes(10), [
                            'fmv_id' => $this->fmv->fmv_id
        ]);

        // Lets upload file to the system as well.
        /*$fileContent = Storage::get(storage_path('app/public/fmv/'.$this->fmv->fmv_id.'/fmv_estimate_'.$this->fmv->fmv_id.'.pdf'));
        $fileInfo  = Storage::disk('gcs')->put('fmv/'.$this->fmv->fmv_id,$fileContent);
        Storage::disk('gcs')->setVisibility($fileInfo, 'public');
        $fmvHasFiles = new FmvHasFiles();
        $fmvHasFiles->fmv_id = $this->fmv->fmv_id;
        $fmvHasFiles->filename = $fileInfo;
        $fmvHasFiles->fileType = 'pdf';
        $fmvHasFiles->logs = 'FMV sent by admin';
        $fmvHasFiles->status = true;
        $fmvHasFiles->save();*/

        return $this->view('fmv.email')
                    ->subject('Equipment Evaluation Report from InPlaceAuction (ID:'.$this->fmv->fmv_id.' Lease:'.$this->fmv->lease_number.' )')
                    ->with(['fmv' => $this->fmv, 'url' =>$url])
                    ->attach(storage_path('app/public/fmv/'.$this->fmv->fmv_id.'/fmv_estimate_'.$this->fmv->fmv_id.'.pdf'));

    }
}
