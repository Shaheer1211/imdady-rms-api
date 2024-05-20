<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Validator;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
   

    public function index()
    {
        // Fetching all coupons along with additional fields
        return Customer::select('id', 'name', 'email', 'email_verified_at', 'password', 'remember_token'
        , 'created_at', 'updated_at', 'deleted_at')
        ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = Customer::select('id', 'name', 'email', 
         'email_verified_at', 'password', 'remember_token', 'created_at', 
         'updated_at', 'deleted_at'
         )
         ->find($id);
    
        if (is_null($customer)) {
            return $this->sendError('Customer not found.');
        }
    
        return $customer;
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
