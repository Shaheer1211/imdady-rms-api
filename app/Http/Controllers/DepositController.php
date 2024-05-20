<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;

class DepositController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $deposit;
    public function __construct()
    {
        $this->deposit = new Deposit();

    }

    public function index()
    {
        return Deposit::all();
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
            'phone' => 'nullable|string|max:255',
            'amount' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255', // change 'text' to 'string'
            'return_amount' => 'nullable|string|max:255',
            'date' => 'nullable|date|max:255', // change 'timestamp' to 'date'
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->deposit->create($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $deposit = Deposit::find($id);

        if (is_null($deposit)) {
            return $this->sendError('deposit not found.');
        }

        return $deposit;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
