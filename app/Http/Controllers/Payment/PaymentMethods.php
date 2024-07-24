<?php

namespace App\Http\Controllers\Payment;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use Validator;

class PaymentMethods extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = Payments::where('del_status', 'Live')->get();
        return response()->json($paymentMethods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_methods',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $paymentMethod = Payments::create($request->all());
        return response()->json($paymentMethod, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paymentMethod = Payments::where('del_status', 'Live')->find($id);
        if (!$paymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }
        return response()->json($paymentMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
       $paymentMethod = Payments::find($id);
        if (!$paymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_methods',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $paymentMethod->update($request->all());
        return response()->json($paymentMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paymentMethod = Payments::find($id);
        if (!$paymentMethod) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        $paymentMethod->delete();
        return response()->json(['message' => 'Payment method deleted successfully']);
    
    }

    public function getPaymentMethods()
    {
        $paymentMethods = Payments::with('multiplePayments')
            ->where('del_status', 'Live') // Ensure active payment methods
            ->get();

        return response()->json($paymentMethods);
    }
}
