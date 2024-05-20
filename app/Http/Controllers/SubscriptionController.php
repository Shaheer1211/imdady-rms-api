<?php

namespace App\Http\Controllers;

use App\Models\subscription;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\StoresubscriptionRequest;
use App\Http\Requests\UpdatesubscriptionRequest;
use Illuminate\Http\Request;
use Validator;

class SubscriptionController extends BaseController
{ 
    protected $subscription;
    public function __construct()
    {
        $this->subscription = new Subscription();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Subscription::all();
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'heading' => 'nullable|string|max:255',
            'delivery_charges' => 'nullable|numeric',
            'is_delivery_charge' => 'nullable|boolean',
            'is_meal_type' => 'nullable|string|max:255',
            'item_qty' => 'nullable|integer',
            'cat_discount' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'category_meal' => 'nullable|integer',
            'details' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
            'full_amount' => 'nullable|numeric',
            'outlet_id' => 'nullable|exists:companies,id', 
            'is_company_sub' => 'nullable|boolean',
            'expire_days' => 'nullable|integer',
            'del_status' => 'nullable'
        ]);
    
        // If validation fails, return error response
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        return $this->subscription->create($request->all());
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subscription = Subscription::find($id);

        if (is_null($subscription)) {
            return $this->sendError('subscription not found.');
        }

        return $subscription;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesubscriptionRequest $request, subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(subscription $subscription)
    {
        //
    }
}
