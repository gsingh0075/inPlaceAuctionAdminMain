<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\ClientsHasContacts;
use App\Models\Communications;
use App\Notifications\NewCommunicationNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ClientsController extends Controller
{

    // Constructor for Authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Login As Client
    public function loginAsClient($id){

        $client = Clients::findorfail($id);
        // Lets Redirect to Client Dashboard.
        Auth::guard('client')->login($client);
        return redirect()->intended('/clientHome');

    }
    // Get All the Clients
    public function get() {

        $clients = Clients::all();
        return view('clients.list', [ 'clients' => $clients ]);
    }

    // Edit Clients
    public function show($id) {

        $client = Clients::with(['contacts'])->findorfail($id);
        //Log::info($client);
        return view('clients.edit',['client' => $client]);

    }

    // Add Contacts to Client
    public function addContact($id) {

        $client = Clients::findorfail($id);
        return view('clients.addContacts', ['client' => $client]);
    }

    // Add new Client
    public function addForm(){

        return view('clients.add');
    }

    // Update Email Notification
    public function updateInvoiceNotification( Request $request){

        $validator = \Validator::make(
            array(
                'client_id' => $request->get('client_id'),
                'notification' => $request->get('notification'),
            ),
            array(
                'client_id' => 'required',
                'notification'  => 'required',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $client_id = $request->get('client_id');
                $notification =  $request->get('notification');
                $client = Clients::findorfail($client_id);
                $client->invoice_email =  $notification;
                $client->save();

                return response(['status' => true,
                                 'data' => $client,  'message' => array('Client was updated successfully')], 200);



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

    }

    // Ajax request to update Client
    public function updateClient( Request $request ) {

        $client_id = $request->get('client_id');
        $client = Clients::findorfail($client_id);

        $validator = \Validator::make(
            array(
                'client_id' => $request->get('client_id'),
                'approved'  => $request->get('approved'),
                'status' => $request->get('status'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'company_name' => $request->get('company_name'),
                'nick_name' => $request->get('nick_name'),
                'address' => $request->get('address'),
                'city'  => $request->get('city'),
                'state' => $request->get('state'),
                'email' => $request->get('email'),
                'postalCode' => $request->get('postalCode'),
                'mobile'  => $request->get('mobile'),
                'fax' => $request->get('fax'),
                'phone' => $request->get('phone'),
                'commission' => $request->get('commission'),
                'userName' => $request->get('userName'),
                //'password' => $request->get('password'),
                //'confirm_password' => $request->get('confirm_password'),
                'marketingEmails' => $request->get('marketingEmails')
            ),
            array(
                'client_id' => 'required',
                'approved'  => 'required',
                'status' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'company_name' => 'required',
                'nick_name' => 'nullable',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'email' => 'required|email|unique:CLIENT,EMAIL,'.$client->CLIENT_ID.',CLIENT_ID',
                'postalCode' => 'required',
                'mobile' => 'nullable',
                'fax'  => 'nullable',
                'phone' => 'nullable',
                'commission' => 'required|int',
                'userName' => 'required|unique:CLIENT,USERNAME,'.$client->CLIENT_ID.',CLIENT_ID|regex:/^\S*$/u',
                //'password' => 'min:6|required_with:confirm_password|different:current_password|same:confirm_password',
                //'confirm_password' => 'min:6',
                'marketingEmails' => 'nullable'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {
            try {

                $approved = $request->get('approved');
                $status =  $request->get('status');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $company_name = $request->get('company_name');
                $nick_name = $request->get('nick_name');
                $address = $request->get('address');
                $city = $request->get('city');
                $state = $request->get('state');
                $email = $request->get('email');
                $postalCode = $request->get('postalCode');
                $mobile = $request->get('mobile');
                $fax = $request->get('fax');
                $phone = $request->get('phone');
                $commission = $request->get('commission');
                $userName = $request->get('userName');
                $marketingEmails = $request->get('marketingEmails');


                $client->USERNAME = $userName;
                //$client->PASSWORD = $password;
                $client->FIRSTNAME = $first_name;
                $client->LASTNAME = $last_name;
                $client->COMPANY = $company_name;
                $client->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $client->LST_UPD = Carbon::now()->format('Y-m-d H:i:s');
                $client->ADDRESS1 = $address;
                $client->CITY = $city;
                $client->STATE = $state;
                $client->ZIP = $postalCode;
                $client->PHONE = $mobile;
                $client->FAX = $fax;
                $client->CELL = $phone;
                $client->EMAIL = $email;
                $client->STATUS = $status;
                $client->APPROVED = $approved;
                $client->APPRVL_DT =  Carbon::now()->format('Y-m-d H:i:s');
                $client->DEFAULT_COMM_RATE = $commission;
                $client->NICKNAME = $nick_name;
                $client->MKT_EMAIL_FREQ = $marketingEmails;
                $client->save();

                return response(['status' => true,
                    'data' => $client,  'message' => array('Client was updated successfully')], 200);

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


    }
    // Ajax request to add Client
    public function addClient( Request $request ){

        $validator = \Validator::make(
            array(
                'approved'  => $request->get('approved'),
                'status' => $request->get('status'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'company_name' => $request->get('company_name'),
                'nick_name' => $request->get('nick_name'),
                'address' => $request->get('address'),
                'city'  => $request->get('city'),
                'state' => $request->get('state'),
                'email' => $request->get('email'),
                'postalCode' => $request->get('postalCode'),
                'mobile'  => $request->get('mobile'),
                'fax' => $request->get('fax'),
                'phone' => $request->get('phone'),
                'commission' => $request->get('commission'),
                'userName' => $request->get('userName'),
                'password' => $request->get('password'),
                'confirm_password' => $request->get('confirm_password'),
                'marketingEmails' => $request->get('marketingEmails')
            ),
            array(
                'approved'  => 'required',
                'status' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'company_name' => 'required',
                'nick_name' => 'nullable',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'email' => 'required|email|unique:CLIENT',
                'postalCode' => 'required',
                'mobile' => 'nullable',
                'fax'  => 'nullable',
                'phone' => 'nullable',
                'commission' => 'required|int',
                'userName' => 'required|unique:CLIENT|regex:/^\S*$/u',
                'password' => 'min:6|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'min:6',
                'marketingEmails' => 'nullable'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {
            try {

                $approved = $request->get('approved');
                $status =  $request->get('status');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $company_name = $request->get('company_name');
                $nick_name = $request->get('nick_name');
                $address = $request->get('address');
                $city = $request->get('city');
                $state = $request->get('state');
                $email = $request->get('email');
                $postalCode = $request->get('postalCode');
                $mobile = $request->get('mobile');
                $fax = $request->get('fax');
                $phone = $request->get('phone');
                $commission = $request->get('commission');
                $userName = $request->get('userName');
                $password = $request->get('password');
                $marketingEmails = $request->get('marketingEmails');

                $client = new Clients();
                $client->USERNAME = $userName;
                $client->PASSWORD = $password;
                $client->FIRSTNAME = $first_name;
                $client->LASTNAME = $last_name;
                $client->COMPANY = $company_name;
                $client->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $client->LST_UPD = Carbon::now()->format('Y-m-d H:i:s');
                $client->ADDRESS1 = $address;
                $client->CITY = $city;
                $client->STATE = $state;
                $client->ZIP = $postalCode;
                $client->PHONE = $mobile;
                $client->FAX = $fax;
                $client->CELL = $phone;
                $client->EMAIL = $email;
                $client->STATUS = $status;
                $client->APPROVED = $approved;
                $client->APPRVL_DT =  Carbon::now()->format('Y-m-d H:i:s');
                $client->DEFAULT_COMM_RATE = $commission;
                $client->NICKNAME = $nick_name;
                $client->MKT_EMAIL_FREQ = $marketingEmails;
                $client->save();

                return response(['status' => true,
                    'data' => $client,  'message' => array('Client was added successfully')], 200);

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

    }
    // Ajax request to add contact to client
    public function addClientContact( Request $request ){

        $validator = \Validator::make(
            array(
                'client_id' => $request->get('client_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'nick_name' => $request->get('nick_name'),
                'email' => $request->get('email'),
                'mobile'  => $request->get('mobile'),
                'phone' => $request->get('phone'),
                'userName' => $request->get('username'),
                'password' => $request->get('password'),
                'confirm_password' => $request->get('confirm_password'),
                'marketingEmails' => $request->get('marketingEmails')
            ),
            array(
                'client_id'  => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'nick_name' => 'nullable',
                'email' => 'required|email|unique:CLIENT_CONTACTS',
                'mobile' => 'nullable',
                'phone' => 'nullable',
                'userName' => 'required|unique:CLIENT_CONTACTS|regex:/^\S*$/u',
                'password' => 'min:6|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'min:6',
                'marketingEmails' => 'nullable'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {
            try {

                $client_id = $request->get('client_id');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $nick_name = $request->get('nick_name');
                $email = $request->get('email');
                $mobile = $request->get('mobile');
                $phone = $request->get('phone');
                $userName = $request->get('username');
                $password = $request->get('password');
                $marketingEmails = $request->get('marketingEmails');

                $clientContact = new ClientsHasContacts();
                $clientContact->CLIENT_ID = $client_id;
                $clientContact->USERNAME = $userName;
                $clientContact->PASSWORD = $password;
                $clientContact->FIRSTNAME = $first_name;
                $clientContact->LASTNAME = $last_name;
                $clientContact->PHONE = $mobile;
                $clientContact->CELL = $phone;
                $clientContact->EMAIL = $email;
                $clientContact->NICKNAME = $nick_name;
                $clientContact->MKT_EMAIL_FREQ = $marketingEmails;
                $clientContact->save();

                return response(['status' => true,
                    'data' => $clientContact,'clientId' => $clientContact->CLIENT_ID ,  'message' => array('Contact was added successfully')], 200);

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

    }

    // Get All Client Chats
    public function clientChat(){

        $clientCommunications = Clients::with(['communication'])->get();
        //Log::info($clientCommunications);

        return view('clients.communications',['clientCommunications' => $clientCommunications ]);

    }

    // Get Client Specific Chat
    public function getClientChat( Request $request)
    {

        $validator = \Validator::make(
            array(
                'client_id' => $request->get('client_id'),
            ),
            array(
                'client_id' => 'required',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $clientId = $request->get('client_id');
                $clientCommunication = Clients::with(['communication'])->where('CLIENT_ID','=', $clientId )->first();
                //Log::info($clientCommunication);
                return response()->json(['success' => true, 'headerHtml' => $clientCommunication->FIRSTNAME." ". $clientCommunication->LASTNAME, 'html' => View('clients.communicationsChat', compact('clientCommunication'))->render()]);


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
    }

    // Save Communication
    public function saveCommunication( Request $request){

        $validator = \Validator::make(
            array(
                'client_id'     => $request->get('client_id'),
                'note'          => $request->get('note'),
            ),
            array(
                'client_id'     => 'required',
                'note'          => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $client_id = $request->get('client_id');
                $note = $request->get('note');

                $clientInfo = Clients::findOrFail($client_id);

                $communication = new Communications();
                $communication->client_id = $client_id;
                $communication->isprivate = 1;
                $communication->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                $communication->admin_id = Auth::user()->id;
                $communication->posted_by = 'ADMIN';
                $communication->ip_address = $request->ip();
                $communication->communication_note = $note;
                $communication->save();

                Notification::send($clientInfo, new NewCommunicationNotification( $communication, $clientInfo->FIRSTNAME, Auth::user()->name, 'New Message'));

                return response(['status' => true, 'message' => array('Note was saved successfully')], 200);


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

    }

}
