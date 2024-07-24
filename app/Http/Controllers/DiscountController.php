<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::where('del_status', 'Live')->get();

        $discounts = $discounts->map(function ($discount) {
            if ($discount->specific_customers === 'yes') {
                $discount->multi_customer_id = $discount->customers->pluck('id');
            } else {
                $discount->multi_customer_id = [];
            }
            return $discount;
        });

        return response()->json([
            'data' => $discounts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:food_menuses,id',
            'category_id' => 'nullable|exists:food_menu_categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'dis_type' => 'required|in:percentage,amount',
            'use_discount' => 'required|in:one_time,regular',
            'discount_amount' => 'required|numeric|min:0',
            'specific_customers' => 'required|in:yes,no',
            'multi_customer_id' => 'nullable|array',
            'multi_customer_id.*' => 'nullable|exists:customers,id',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $discount = Discount::create([
        'product_id' => $request->input('product_id'),
        'category_id' => $request->input('category_id'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'dis_type' => $request->input('dis_type'),
        'use_discount' => $request->input('use_discount'),
        'discount_amount' => $request->input('discount_amount'),
        'specific_customers' => $request->input('specific_customers'),
        'multi_customer_id' => $request->input('multi_customer_id')
    ]);

        if ($discount->specific_customers === 'yes') {
            $discount->customers()->sync($request->multi_customer_id);
        }

        return response()->json(['message' => 'Discount created successfully', 'data' => $discount], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $discounts = Discount::where('id',$id)->where('del_status', 'Live')->get();

        $discounts = $discounts->map(function ($discount) {
            if ($discount->specific_customers === 'yes') {
                $discount->multi_customer_id = $discount->customers->pluck('id');
            } else {
                $discount->multi_customer_id = [];
            }
            return $discount;
        });

        return response()->json([
            'data' => $discounts
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:food_menuses,id',
            'category_id' => 'nullable|exists:food_menu_categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'dis_type' => 'required|in:percentage,amount',
            'use_discount' => 'required|in:one_time,regular',
            'discount_amount' => 'required|numeric|min:0',
            'specific_customers' => 'required|in:yes,no',
            'multi_customer_id' => 'nullable|array',
            'multi_customer_id.*' => 'nullable|exists:customers,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['error' => 'Discount not found.'], 404);
        }

        $discount->update($request->all());

        if ($discount->specific_customers === 'yes') {
            $discount->customers()->sync($request->multi_customer_id);
        } else {
            $discount->customers()->detach();
        }

        return response()->json(['message' => 'Discount updated successfully', 'data' => $discount], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['error' => 'Discount not found.'], 404);
        }

        $discount->update(['del_status' => 'delete']);

        return response()->json(['message' => 'Discount deleted successfully'], 200);
    }
}
