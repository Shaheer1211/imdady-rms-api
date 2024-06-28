<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\OutletsSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OutletController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        if ($status) {
            return Outlet::where('status', $status)->get();
        }

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
        if ($validator->fails()) {
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
    public function show($id)
    {
        $outlet = Outlet::find($id);

        if (is_null($outlet)) {
            return $this->sendError('Outlet not found.');
        }

        return $outlet;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $outlet = Outlet::find($id);

        if ($outlet) {
            $outlet->update($request->all());
            return response()->json(['message' => 'Outlet update successfully'], 200);
        } else {
            return response()->json(['message' => 'Outlet not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        //
    }
    public function ordertype($id)
    {
        if (is_null($id)) {
            // Handle the null case, e.g., return an appropriate error response
            return response()->json(['error' => 'Order type ID cannot be null'], 400);
        }

        $result = DB::table('outlet_by_ordertype')
            ->leftJoin('outlets', 'outlets.id', '=', 'outlet_by_ordertype.outlet_id')
            ->where('outlet_by_ordertype.ordertype_id', $id)
            ->get();

        if ($result->isEmpty()) {
            // Return a "no data" message if the result is empty
            return response()->json(['message' => 'No data found for the given order type ID'], 404);
        }

        return response()->json($result);
    }
}
