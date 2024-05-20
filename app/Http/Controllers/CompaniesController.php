<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Models\companies;
use App\Http\Requests\StorecompaniesRequest;
use App\Http\Requests\UpdatecompaniesRequest;
use Illuminate\Http\Request;
use Validator;

class CompaniesController extends BaseController
{
    protected $companies;
    public function __construct()
    {
        $this->companies = new Companies();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return companies::all();
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
        {
            $validator = Validator::make($request->all(), [
                'currency_id' => 'nullable|numeric',
                'timezone' => 'nullable|string|max:255',
                'date_format' => 'nullable|string|max:255',
                'outlet_id' => 'nullable|numeric',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            return $this->companies->create($request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $companies = Companies::find($id);

        if (is_null($companies)) {
            return $this->sendError('companies not found.');
        }

        return $companies;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(companies $companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecompaniesRequest $request, companies $companies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(companies $companies)
    {
        //
    }
}
