<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Models\customer_due_receives;
use App\Http\Requests\Storecustomer_due_receivesRequest;
use App\Http\Requests\Updatecustomer_due_receivesRequest;
use Illuminate\Http\Request;
use Validator;

class CustomerDueReceivesController extends Controller
{
    protected $customer_due_receives;
    public function __construct()
    {
        $this->customer_due_receives = new Customer_due_receives();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return customer_due_receives::all();
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
            'only_date' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'customer_id' => 'required|exists:customers,id',
            'note' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->customer_due_receives->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer_due_receives = customer_due_receives::find($id);

        if (is_null($customer_due_receives)) {
            return $this->sendError('customer_due_receives not found.');
        }

        return $customer_due_receives;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(customer_due_receives $customer_due_receives)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecustomer_due_receivesRequest $request, customer_due_receives $customer_due_receives)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(customer_due_receives $customer_due_receives)
    {
        //
    }
}
