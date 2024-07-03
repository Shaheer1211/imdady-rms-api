<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\DB;

class OrdersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(), [
            'customer_id' => 'integer|exists:customers,id',
            'is_coupon' => 'required|string',
            'order_from' => 'required|string',
            'order_type_id' => 'required|integer|exists:ordertypes,id',
            'table_id' => 'integer|exists:tables,id',
            'user_id' => 'integer|exists:users,id',
            'waiter_id' => 'integer|exists:users,id',
            'outlet_id' => 'integer|exists:outlets,id',
            'loyalty_point_amount' => 'number',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
