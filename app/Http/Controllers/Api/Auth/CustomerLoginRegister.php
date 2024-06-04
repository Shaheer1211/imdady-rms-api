<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
// use Illuminate\Support\Facades\Auth;

class CustomerLoginRegister extends BaseController
{
    /**
     * Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:customers,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $customer = Customer::create($input);

        $success['token'] = $customer->createToken($request->email)->plainTextToken;
        $success['customer'] = $customer;
        return $this->sendResponse($success, 'Customer created successfully.');
    }

    /**
     * Login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $customer = Customer::where('email', $request->email)->first();

        // Check password
        if(!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
                ], 401);
        } 
            // $customer = Auth::User(); 
            $success['token'] =  $customer->createToken($request->email)->plainTextToken; 
            $success['customer'] =  $customer;
   
            return $this->sendResponse($success,  'Customer login successfully.');
        
        // else{ 
        //     return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        // } 
    } 
    
    /**
     * Logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $success['logout'] =  auth()->user()->tokens()->delete();
        return $this->sendResponse($success,  'Customer logout successfully.');
    } 

}
