<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Models\expenses_category;
use App\Http\Requests\Storeexpenses_categoryRequest;
use App\Http\Requests\Updateexpenses_categoryRequest;
use Illuminate\Http\Request;
use Validator;


class ExpensesCategoryController extends BaseController
{
    protected $expenses_category;
    public function __construct()
    {
        $this->expenses_category = new Expenses_category();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Expenses_category::all();
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
            'vat_percentage' => 'nullable|numeric',
            'tax_method' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'user_id' => 'nullable|numeric',
            'outlet_id' => 'nullable|numeric',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->expenses_category->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(expenses_category $expenses_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $expenses_category = Expenses_category::find($id);

        if (is_null($expenses_category)) {
            return $this->sendError('expenses category not found.');
        }

        return $expenses_category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateexpenses_categoryRequest $request, expenses_category $expenses_category)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(expenses_category $expenses_category)
    {
        //
    }
}
