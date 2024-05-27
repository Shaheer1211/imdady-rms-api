<?php

namespace App\Http\Controllers;

use App\Models\suppliers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\StoresuppliersRequest;
use App\Http\Requests\UpdatesuppliersRequest;
use Illuminate\Http\Request;
use Validator;

class SuppliersController extends BaseController
{
    protected $suppliers;
    public function __construct()
    {
        $this->suppliers = new Suppliers();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Suppliers::all();
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
            'name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        'supplier_vat' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',
        'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->suppliers->create($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show(suppliers $suppliers)
    {
        $suppliers = Suppliers::find($id);

        if (is_null($suppliers)) {
            return $this->sendError('suppliers not found.');
        }

        return $suppliers;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(suppliers $suppliers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesuppliersRequest $request, suppliers $suppliers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(suppliers $suppliers)
    {
        //
    }
}
