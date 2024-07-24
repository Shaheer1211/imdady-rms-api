<?php

namespace App\Http\Controllers\Payment;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MultiplePayment;
use Validator;

class MultiplePayments extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $multiplePayments = MultiplePayment::where('del_status', 'Live')->get();
        return response()->json($multiplePayments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|integer',
            'company_name' => 'required|string|max:255|unique:multiple_payments',
            'dis_type' => 'nullable|in:amount,percentage',
            'expired_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|string',
            'discount_amount' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $multiplePayment = MultiplePayment::create($request->all());
        return response()->json($multiplePayment, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $multiplePayment = MultiplePayment::where('del_status', 'Live')->find($id);
        if (!$multiplePayment) {
            return response()->json(['message' => 'Multiple payment not found'], 404);
        }
        return response()->json($multiplePayment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $multiplePayment = MultiplePayment::find($id);
        if (!$multiplePayment) {
            return response()->json(['message' => 'Multiple payment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|integer',
            'company_name' => 'required|string|max:255|unique:multiple_payments',
            'dis_type' => 'nullable|in:amount,percentage',
            'expired_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|string',
            'discount_amount' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $multiplePayment->update($request->all());
        return response()->json($multiplePayment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $multiplePayment = MultiplePayment::find($id);
        if (!$multiplePayment) {
            return response()->json(['message' => 'Multiple payment not found'], 404);
        }

        $multiplePayment->delete();
        return response()->json(['message' => 'Multiple payment deleted successfully']);
    }
}
