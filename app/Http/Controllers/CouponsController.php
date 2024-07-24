<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
use Carbon\Carbon;

class CouponsController extends BaseController
{
    protected $couponss;
    public function __construct()
    {
        $this->couponss = new Coupon();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::where('status', true)
        ->where('del_status', 'Live')
        ->get();

        return response()->json($coupons, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:coupons,code',
            'minimum_purchase_price' => 'required|numeric',
            'dis_type' => 'required|string|in:percentage,amount',
            'expired_date' => 'required|date',
            'discount_amount' => 'required|numeric',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'The name field is required.',
            'code.required' => 'The code field is required.',
            'minimum_purchase_price.required' => 'The minimum purchase price field is required.',
            'minimum_purchase_price.numeric' => 'The minimum purchase price must be a number.',
            'dis_type.required' => 'The discount type field is required.',
            'dis_type.in' => 'The discount type must be either percentage or amount.',
            'expired_date.required' => 'The expired date field is required.',
            'expired_date.date' => 'The expired date field must be a valid date.',
            'discount_amount.required' => 'The discount amount field is required.',
            'discount_amount.numeric' => 'The discount amount must be a number.',
            'status.required' => 'The status field is required.',
            'status.boolean' => 'The status field must be true or false.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['code'] = strtoupper($data['code']); // Convert code to uppercase as an example

        $coupon = Coupon::create($data);

        return response()->json(['message' => 'Coupon created successfully', 'data' => $coupon], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coupon = Coupon::where('id', $id)
                        ->where('status', true)
                        ->where('del_status', 'Live')
                        ->first();
        if (is_null($coupon)) {
            return response()->json(['error' => 'Coupon not found or inactive.'], 404);
        }

        return response()->json($coupon, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
     {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:coupons,code',
            'minimum_purchase_price' => 'required|numeric',
            'dis_type' => 'required|string|in:percentage,amount',
            'expired_date' => 'required|date',
            'discount_amount' => 'required|numeric',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'The name field is required.',
            'code.required' => 'The code field is required.',
            'minimum_purchase_price.required' => 'The minimum purchase price field is required.',
            'minimum_purchase_price.numeric' => 'The minimum purchase price must be a number.',
            'dis_type.required' => 'The discount type field is required.',
            'dis_type.in' => 'The discount type must be either percentage or amount.',
            'expired_date.required' => 'The expired date field is required.',
            'expired_date.date' => 'The expired date field must be a valid date.',
            'discount_amount.required' => 'The discount amount field is required.',
            'discount_amount.numeric' => 'The discount amount must be a number.',
            'status.required' => 'The status field is required.',
            'status.boolean' => 'The status field must be true or false.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $coupon = Coupon::where('id', $id)
                        ->where('del_status', 'live')
                        ->first();
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found.'], 404);
        }

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);

        $coupon->update($data);

        return response()->json(['message' => 'Coupon updated successfully', 'data' => $coupon], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::where('id', $id)
                        ->where('del_status', 'live')
                        ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found or already deleted.'], 404);
        }
        $coupon->update(['del_status' => 'delete']);

        return response()->json(['message' => 'Coupon deleted successfully'], 200);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found.'], 404);
        }

        $coupon->update(['status' => $request->status]);

        $statusMessage = $request->status ? 'enabled' : 'disabled';

        return response()->json([
            'message' => "Coupon status updated successfully to $statusMessage.",
            'data' => $coupon
        ], 200);
    }

    public function checkCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $paidAmount = $request->input('paid_amount');
        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('del_status', 'Live')
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid coupon code.'], 404);
        }

        if (!$coupon->status) {
            return response()->json(['message' => 'This coupon is inactive.'], 400);
        }

        if (Carbon::parse($coupon->expired_date)->isPast()) {
            return response()->json(['message' => 'This coupon has expired.'], 400);
        }

        if ($paidAmount < $coupon->minimum_purchase_price) {
            return response()->json(['message' => 'Paid amount does not meet the minimum purchase requirement.'], 400);
        }
        $discount = 0;
        if ($coupon->dis_type === 'percentage') {
            $discount = ($paidAmount * $coupon->discount_amount) / 100;
        } elseif ($coupon->dis_type === 'amount') {
            $discount = $coupon->discount_amount;
        }
        $discount = min($discount, $paidAmount);

        return response()->json([
            'message' => 'This coupon is valid.',
            'data' => $coupon,
            'discount' => $discount,
            'final_amount' => $paidAmount - $discount
        ], 200);
    }
}
