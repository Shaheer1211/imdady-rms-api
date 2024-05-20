<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Models\vendor;
use App\Http\Requests\StorevendorRequest;
use App\Http\Requests\UpdatevendorRequest;
use Illuminate\Http\Request;
use Validator;

class VendorController extends BaseController
{
    protected $vendor;
    public function __construct()
    {
        $this->vendor = new Vendor();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return vendor::all();
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
            'vendor_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',
        'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->vendor->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::find($id);

        if (is_null($suppliers)) {
            return $this->sendError('vendor not found.');
        }

        return $vendor;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatevendorRequest $request, vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vendor $vendor)
    {
        //
    }
}
