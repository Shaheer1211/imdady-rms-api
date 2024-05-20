<?php

namespace App\Http\Controllers;

use App\Models\supplierpayment;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\StoresupplierpaymentRequest;
use App\Http\Requests\UpdatesupplierpaymentRequest;
use Illuminate\Http\Request;
use Validator;

class SupplierpaymentController extends BaseController
{
    protected $supplierpayment;
    public function __construct()
    {
        $this->supplierpayment = new Supplierpayment();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return supplierpayment::all();
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
            'date' => 'nullable|date',
            'supplier_id' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'note' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',
        'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->supplierpayment->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supplierpayment = Supplierpayment::find($id);

        if (is_null($supplierpayment)) {
            return $this->sendError('supplierpayment not found.');
        }

        return $supplierpayment;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(supplierpayment $supplierpayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesupplierpaymentRequest $request, supplierpayment $supplierpayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(supplierpayment $supplierpayment)
    {
        //
    }
}
