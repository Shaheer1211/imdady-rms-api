<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Models\productdiscont;
use App\Http\Requests\StoreproductdiscontRequest;
use App\Http\Requests\UpdateproductdiscontRequest;
use Illuminate\Http\Request;
use Validator;

class ProductdiscontController extends BaseController
{
    protected $productdiscont;
    public function __construct()
    {
        $this->productdiscont = new Productdiscont();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return productdiscont::all();
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
        'product_id' => 'nullable|string|max:255',
        'category_id' => 'nullable|numeric',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'dis_type' => 'nullable|integer',
        'use_discount' => 'nullable|date',
        'discount_amount' => 'nullable|numeric',
        'user_id' => 'nullable|numeric',
        'outlet_id' => 'nullable|exists:outlets,id',
        'specific_customers' => 'nullable|numeric',
        'multi_customer_id' => 'nullable|string|max:255',
        'del_status' => 'nullable'
        ]);
       
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->productdiscont->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        {
            $productdiscont = Productdiscont::find($id);
    
            if (is_null($credit)) {
                return $this->sendError('productdiscont not found.');
            }
    
            return $productdiscont;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(productdiscont $productdiscont)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateproductdiscontRequest $request, productdiscont $productdiscont)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(productdiscont $productdiscont)
    {
        //
    }
}
