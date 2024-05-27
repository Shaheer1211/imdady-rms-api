<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Models\inventoryadjustment;
use App\Http\Requests\StoreinventoryadjustmentRequest;
use App\Http\Requests\UpdateinventoryadjustmentRequest;
use Illuminate\Http\Request;
use Validator;


class InventoryadjustmentController extends BaseController
{
    
    protected $inventoryadjustment;
    public function __construct()
    {
        $this->inventoryadjustment = new Inventoryadjustment();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inventoryadjustment::all();
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
            'reference_no' => 'nullable|numeric',
            'date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
            'employee_id' => 'nullable|numeric',
            'user_id' => 'nullable|numeric',
            'outlet_id' => 'nullable|numeric',
            'del_status' => 'nullable'
            ]);
           
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            return $this->inventoryadjustment->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        {
            $inventoryadjustment = Inventoryadjustment::find($id);
    
            if (is_null($credit)) {
                return $this->sendError('inventoryadjustment not found.');
            }
    
            return $inventoryadjustment;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(inventoryadjustment $inventoryadjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateinventoryadjustmentRequest $request, inventoryadjustment $inventoryadjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(inventoryadjustment $inventoryadjustment)
    {
        //
    }
}
