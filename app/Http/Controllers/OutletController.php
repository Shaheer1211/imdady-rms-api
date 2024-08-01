<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\OutletByOrdertype;
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
        $outlets = Outlet::with('ordertypes')
            ->where('del_status', 'Live')
            ->get()
            ->map(function ($outlets) {
                return [
                    "id" => $outlets->id,
                    "code" => $outlets->code,
                    "name" => $outlets->name,
                    "phone" => $outlets->phone,
                    "email" => $outlets->email,
                    "address" => $outlets->address,
                    "city_id" => $outlets->city_id,
                    "status" => $outlets->status,
                    "registration_no" => $outlets->registration_no,
                    "user_id" => $outlets->user_id,
                    "del_status" => $outlets->del_status,
                    "created_at" => $outlets->created_at,
                    "updated_at" => $outlets->updated_at,
                    "deleted_at" => $outlets->deleted_at,
                    'order_type' => $outlets->ordertypes
                ];
            });
        return response()->json($outlets);
    }
    // public function index(Request $request)
    // {
    //     $status = $request->query('status');
    //     $cityId = $request->query('cityId');
    //     $orderTypeId = $request->query('orderTypeId');

    //     $query = DB::table('outlets')->where('del_status', 'Live');

    //     if ($orderTypeId) {
    //         $query->join('outlet_by_ordertype', 'outlets.id', '=', 'outlet_by_ordertype.outlet_id')
    //               ->where('outlet_by_ordertype.ordertype_id', $orderTypeId);
    //     }

    //     if ($status) {
    //         $query->where('status', $status);
    //     }

    //     if ($cityId) {
    //         $query->where('city_id', $cityId);
    //     }

    //     $outlets = $query->get();

    //     return response()->json($outlets);
    // }

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
            'city_id' => 'required|integer',
            'status' => ['required', 'string', 'max:255', 'in:active,inactive'],
            'registration_no' => 'required|string|max:50',
            'order_type' => 'required|array'
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

        $orderType = $request->order_type;

        foreach ($orderType as $type) {
            $type['outlet_id'] = $createdOutlet->id;
            OutletByOrdertype::create($type);
        }

        return $createdOutlet;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $outlet = Outlet::with('ordertypes')
            ->where('id', $id)
            ->get()
            ->map(function ($outlets) {
                return [
                    "id" => $outlets->id,
                    "code" => $outlets->code,
                    "name" => $outlets->name,
                    "phone" => $outlets->phone,
                    "email" => $outlets->email,
                    "address" => $outlets->address,
                    "city_id" => $outlets->city_id,
                    "status" => $outlets->status,
                    "registration_no" => $outlets->registration_no,
                    "user_id" => $outlets->user_id,
                    "del_status" => $outlets->del_status,
                    "created_at" => $outlets->created_at,
                    "updated_at" => $outlets->updated_at,
                    "deleted_at" => $outlets->deleted_at,
                    'order_type' => $outlets->ordertypes
                ];
            });

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
    public function destroy(int $id)
    {
        $outlet = Outlet::find($id);

        if (!$outlet) {
            return $this->sendError('Outlet not found.', [], 404);
        }

        $outlet['del_status'] = 'deleted';
        $outlet->update();

        return $this->sendResponse('Outlet deleted successfully.', $outlet);
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

    public function cities()
    {
        $query = DB::table('cities')
            ->select('*')
            ->get()
            ->map(function ($item) {
                return array_map(function ($value) {
                    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }, (array) $item);
            });

        return response()->json($query);
    }
    public function outletCities()
    {
        $query = DB::table('outlets')
            ->select([
                    'outlets.city_id',
                    'cities.name_en',
                    'cities.name_ar',
                    DB::raw('COUNT(outlets.city_id) AS count')
                ])
            ->join('cities', 'outlets.city_id', '=', 'cities.city_id')
            ->groupBy('outlets.city_id', 'cities.name_en', 'cities.name_ar')
            ->get();

        return $query;
    }
}
