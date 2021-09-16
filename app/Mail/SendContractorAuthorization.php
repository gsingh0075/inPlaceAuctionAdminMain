<?php

namespace App\Mail;

use App\Models\Assignment;
use App\Models\ContractorAuth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContractorAuthorization extends Mailable
{
    use Queueable, SerializesModels;


    public $contractorAuthorization;
    public $assignment;

    public function __construct( ContractorAuth $contractorAuthorization, Assignment $assignment)
    {
        $this->contractorAuthorization = $contractorAuthorization;
        $this->assignment = $assignment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $contractorAuthorizations =  ContractorAuth::with(['authItems.item', 'contractor'])->findorfail($this->contractorAuthorization->contractor_auth_id);
        $assignment = Assignment::findorfail($this->assignment->assignment_id);

        $authorizationPdf = PDF::loadView('assignment.contractorAuthPdf', ['authorization' => $contractorAuthorizations, 'assignment' => $assignment]);

        $uploadFilePath = storage_path('app/public/contractor_authorization/' . $this->contractorAuthorization->contractor_auth_id);

        if (!file_exists($uploadFilePath)) {
            mkdir($uploadFilePath, 0777, true);
        }

        if( file_exists(storage_path('app/public/contractor_authorization/'.$this->contractorAuthorization->contractor_auth_id.'/contractor_auth_'.$this->contractorAuthorization->contractor_auth_id.'.pdf'))) {
            unlink(storage_path('app/public/contractor_authorization/'.$this->contractorAuthorization->contractor_auth_id.'/contractor_auth_'.$this->contractorAuthorization->contractor_auth_id.'.pdf'));
        }

        $authorizationPdf->save(storage_path('app/public/contractor_authorization/'.$this->contractorAuthorization->contractor_auth_id.'/contractor_auth_'.$this->contractorAuthorization->contractor_auth_id.'.pdf'));

        return $this->view('contractors.email')
            ->subject('Pickup Authorization from InPlaceAuction ( Auth # :'.$this->contractorAuthorization->contractor_auth_id.' )')
            ->with(['contractor' => $contractorAuthorizations])
            ->attach(storage_path('app/public/contractor_authorization/'.$this->contractorAuthorization->contractor_auth_id.'/contractor_auth_'.$this->contractorAuthorization->contractor_auth_id.'.pdf'));

    }
}
