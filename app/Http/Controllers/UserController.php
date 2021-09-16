<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //

    public function __construct(){

        $this->middleware('auth');
    }

    public function changePassword(){

        return view('users.changePassword');
    }

    public function updatePassword( Request $request){

        $validator = \Validator::make(
            array(
                'current_password'  => $request->input('current_password'),
                'new_password' => $request->input('new_password'),
                'confirm_password' => $request->input('confirm_password'),
            ),
            array(
                'current_password'  => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try{

                $currentPassword = $request->input('current_password');
                $newPassword = $request->input('new_password');

                $user = User::find(auth()->user()->id);

                if( Hash::check($currentPassword, $user->password) ){

                    $user->update(['password' => Hash::make($newPassword)]);

                    return response( ['status' => true, 'message' => 'Password successfully updated'], 200 );

                } else {
                    return response( ['status' => false, 'errors' => array('Current Password do not match') ], 200 );
                }

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
