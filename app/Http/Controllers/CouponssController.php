<?php

namespace App\Http\Controllers;

use App\Models\couponss;
use App\Http\Requests\StorecouponssRequest;
use App\Http\Requests\UpdatecouponssRequest;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;

class CouponssController extends BaseController
{

    protected $couponss;
    public function __construct()
    {
        $this->couponss = new Couponss();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Couponss::all();
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
        //
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'minimum_purchase_price' => 'nullable|numeric',
            'dis_type' => 'nullable|string|max:255',
            'expired_date' => 'nullable|date',
            'discount_amount' => 'nullable|numeric',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->Couponss->create($request->all());
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $couponss = Couponss::find($id);

        if (is_null($couponss)) {
            return $this->sendError('couponss not found.');
        }

        return $couponss;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(couponss $couponss)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecouponssRequest $request, couponss $couponss)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(couponss $couponss)
    {
        //
    }
}
