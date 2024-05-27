<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use Illuminate\Http\Request;
use Validator;

class ExpenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $expense;
    public function __construct()
    {
        $this->expense = new Expense();

    }

    public function index()
    {
        return Expense::all();
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
            'amount' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'vat_percentage' => 'nullable|numeric',
            'tax_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'category_id' => 'nullable|numeric',
            'payment_method_id' => 'nullable|numeric',
            'employee_id' => 'nullable|numeric',
            'note' => 'nullable|string|max:255',
            'user_id' => 'nullable|numeric',
            'outlet_id' => 'nullable|numeric',
            'del_status' => 'nullable|string|max:255'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->expense->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $deposit = Expense::find($id);

        if (is_null($expense)) {
            return $this->sendError('expense not found.');
        }

        return $expense;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
