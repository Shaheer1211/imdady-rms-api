<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\OutletsSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Auth;

class OutletController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Outlet::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'status' => ['required', 'string', 'max:255', 'in:active,inactive'],
            'registration_no' => 'required|string|max:50', 
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        // Merge the user_id into the request data
        $requestData = $request->merge(['user_id' => $userId])->all();

        // If the validation passes, you can create a new record
        $createdOutlet = Outlet::create($requestData);
        OutletsSettings::create(['outlet_id' => $createdOutlet->id]);
        return $createdOutlet;
    }

    /**
     * Display the specified resource.
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outlet $outlet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        //
    }
}
