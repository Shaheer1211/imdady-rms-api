<?php

namespace App\Http\Controllers;

use App\Models\purchase;
use App\Http\Requests\StorepurchaseRequest;
use App\Http\Requests\UpdatepurchaseRequest;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;

class PurchaseController extends BaseController
{
    protected $purchase;
    public function __construct()
    {
        $this->purchase = new Purchase();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return purchase::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference_no' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|numeric',
            'date' => 'nullable|date',
            'subtotal' => 'nullable|numeric',
            'vat' => 'nullable|integer',
            'grand_total' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'due' => 'nullable|numeric|exists:outlets,id',
            'note' => 'nullable|string',
            'user_id' => 'nullable|numeric',
            'outlet_id' => 'nullable|numeric',
            'del_status' => 'nullable'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            return $this->purchase->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        {
            $purchase = Purchase::find($id);
    
            if (is_null($purchase)) {
                return $this->sendError('purchase not found.');
            }
    
            return $purchase;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepurchaseRequest $request, purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(purchase $purchase)
    {
        //
    }
}
