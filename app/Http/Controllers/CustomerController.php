<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // List All Customers
    public function getAllCustomers(){

        $customer =  Customer::all();
        return view('customer.customerList', ['customer' => $customer ]);

    }

    // Add New Customer
    public function addForm() {

        return view('customer.add', ['states' => State::all() ]);
    }

    // Post Add Customer
    public function addCustomer( Request $request )
    {

        //Log::info($request);
        $validator = \Validator::make(
            array(
                'status' => $request->get('status'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'title'  => $request->get('title'),
                'company-name' => $request->get('company-name'),
                'address' => $request->get('address'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'zip' => $request->get('zip'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'fax' => $request->get('fax'),
                'cell_phone' => $request->get('cell_phone'),
            ),
            array(
                'status' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'title' => 'nullable',
                'company-name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip' => 'required',
                'email' => 'required|email',
                'phone' => 'nullable',
                'fax' => 'nullable',
                'cell_phone' => 'nullable'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $status = $request->get('status');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $title = $request->get('title');
                $companyName = $request->get('company-name');
                $address = $request->get('address');
                $city = $request->get('city');
                $state = $request->get('state');
                $zip = $request->get('zip');
                $email = $request->get('email');
                $phone = $request->get('phone');
                $fax = $request->get('fax');
                $cellPhone = $request->get('cell_phone');

                $customer = new Customer();
                $customer->ACTIVE = $status;
                $customer->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $customer->LST_UPD = Carbon::now()->format('Y-m-d H:i:s');
                $customer->USERNAME = $email;
                $customer->PASSWORD = 'TEMP!@#';
                $customer->FIRSTNAME = $first_name;
                $customer->LASTNAME = $last_name;
                $customer->TITLE = $title;
                $customer->COMPANY = $companyName;
                $customer->ADDRESS1 = $address;
                $customer->CITY = $city;
                $customer->STATE = $state;
                $customer->ZIP = $zip;
                $customer->PHONE = $phone;
                $customer->FAX = $fax;
                $customer->EMAIL = $email;
                $customer->CELL = $cellPhone;
                $customer->save();

                return response(['status' => true,
                                 'data' => $customer, 'message' => array('Customer was added successfully') ], 200);


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

    // Edit Customer
    public function editForm($id) {

        $customer = Customer::findorfail($id);

        return view('customer.edit', ['states' => State::all(), 'customer' => $customer ]);
    }

    // Update Customer
    public function updateCustomer( Request $request) {

        //Log::info($request);
        $validator = \Validator::make(
            array(
                'customer_id' => $request->get('customer_id'),
                'status' => $request->get('status'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'title'  => $request->get('title'),
                'company-name' => $request->get('company-name'),
                'address' => $request->get('address'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'zip' => $request->get('zip'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'fax' => $request->get('fax'),
                'cell_phone' => $request->get('cell_phone'),
            ),
            array(
                'customer_id' => 'required|int',
                'status' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'title' => 'nullable',
                'company-name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip' => 'required',
                'email' => 'required|email',
                'phone' => 'nullable',
                'fax' => 'nullable',
                'cell_phone' => 'nullable'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $customer_id = $request->get('customer_id');
                $status = $request->get('status');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $title = $request->get('title');
                $companyName = $request->get('company-name');
                $address = $request->get('address');
                $city = $request->get('city');
                $state = $request->get('state');
                $zip = $request->get('zip');
                $email = $request->get('email');
                $phone = $request->get('phone');
                $fax = $request->get('fax');
                $cellPhone = $request->get('cell_phone');

                $customer = Customer::findorfail($customer_id);
                $customer->ACTIVE = $status;
                $customer->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $customer->LST_UPD = Carbon::now()->format('Y-m-d H:i:s');
                $customer->USERNAME = $email;
                $customer->PASSWORD = 'TEMP!@#';
                $customer->FIRSTNAME = $first_name;
                $customer->LASTNAME = $last_name;
                $customer->TITLE = $title;
                $customer->COMPANY = $companyName;
                $customer->ADDRESS1 = $address;
                $customer->CITY = $city;
                $customer->STATE = $state;
                $customer->ZIP = $zip;
                $customer->PHONE = $phone;
                $customer->FAX = $fax;
                $customer->EMAIL = $email;
                $customer->CELL = $cellPhone;
                $customer->save();

                return response(['status' => true,
                    'data' => $customer, 'message' => array('Customer was added successfully')], 200);


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

    // Update Notification
    public function updateCustomerInvoiceNotification( Request $request ){


        $validator = \Validator::make(
            array(
                'customer_id' => $request->get('customer_id'),
                'notification' => $request->get('notification'),
            ),
            array(
                'customer_id' => 'required',
                'notification'  => 'required',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $customer_id = $request->get('customer_id');
                $notification =  $request->get('notification');
                $customer = Customer::findorfail($customer_id);
                $customer->invoice_email =  $notification;
                $customer->save();

                return response(['status' => true,
                    'data' => $customer,  'message' => array('Customer was updated successfully')], 200);



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
