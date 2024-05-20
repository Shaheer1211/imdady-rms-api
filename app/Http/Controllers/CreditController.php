<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Models\credit;
use App\Http\Requests\StorecreditRequest;
use App\Http\Requests\UpdatecreditRequest;
use Illuminate\Http\Request;
use Validator;

class CreditController extends Controller
{
    protected $credit;
    public function __construct()
    {
        $this->credit = new Credit();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return credit::all();
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
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|boolean',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|integer',
           
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->credit->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $credit = Credit::find($id);

        if (is_null($credit)) {
            return $this->sendError('credit not found.');
        }

        return $credit;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecreditRequest $request, credit $credit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(credit $credit)
    {
        //
    }
}
