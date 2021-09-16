<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ClientInvoices;
use App\Models\Clients;
use App\Models\Communications;
use App\Models\Fmv;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientHomeController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:client');
    }

    public function home(){

        //Log::info( Auth::user());
        $communication = Communications::where('client_id', Auth::user()->CLIENT_ID)->whereNull('assignment_id')->whereNull('item_id')->orderBy('dt_stmp','desc')->get();

        $fmv = Fmv::where('client_id',Auth::user()->CLIENT_ID)->get();

        $clientId =  Auth::user()->CLIENT_ID; // Logged in Client ID.
        $assignmentOpen = Assignment::with(['client'])->whereHas('client', function ($q) use ($clientId){
            $q->where('CLIENT_ID','=',$clientId);
        })->where('isopen',1)->get();

        $assignmentClosed = Assignment::with(['client'])->whereHas('client', function ($q) use ($clientId){
            $q->where('CLIENT_ID','=',$clientId);
        })->where('isopen',0)->get();

        return view('clientDashboard.home',
                   ['communication' => $communication,
                    'totalFmv' => count($fmv),
                    'totalOpenAssignment' => count($assignmentOpen),
                    'totalCloseAssignment'=> count($assignmentClosed)]
        );
    }

    /* Load the Client Invoices */
    public function getClientInvoices(){

            try{

                $clientId =  Auth::user()->CLIENT_ID; // Logged in Client ID.
                $clientInvoicesOut = ClientInvoices::with(['client','lines.expense.item'])
                    ->where('sent','=',1)
                    ->whereNull('paid')
                    ->where('client_id',$clientId)
                    ->get();
                //Log::info($clientInvoicesOut);
                return response()->json(['status' => true, 'html' => View('clientDashboard.home.clientInvoice', compact('clientInvoicesOut'))->render()]);

            } catch (Exception $e) {

                if ($e instanceof ModelNotFoundException) {
                    Log::error('Model Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'No Entry matched for Model ' . str_replace('App\v2\\', '', $e->getModel()), 'value' => $e->getIds())], 400);

                } elseif ($e instanceof QueryException) {
                    Log::error('Query Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Data save exception. Please contact administrator')], 500);

                } else {
                    Log::error('Unknown Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Something went wrong')], 500);

                }
            }
        }

    /* View Client Invoice */
    public function viewClientInvoice($invoiceId, $assignmentId){

        $clientInvoice  =  ClientInvoices::with(['client','lines.expense'])->findorfail($invoiceId);
        $assignment = Assignment::findorfail($assignmentId);

        $InvoicePdf = PDF::loadView('assignment.clientInvoicePdf', ['clientInvoice' => $clientInvoice, 'assignment' => $assignment]);
        return $InvoicePdf->download($invoiceId.'.pdf');

    }

    // Update Logo
    public function logoUpload() {

        return view('clientDashboard.client.updateLogo');
    }

    // Update Logo Post Method.
    public function logoUploadPost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('images'), $imageName);

        /* Store $imageName name in DATABASE from HERE */

        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
    }
}
