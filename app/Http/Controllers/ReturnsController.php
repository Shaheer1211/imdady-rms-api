<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\returns;
use App\Http\Requests\StorereturnsRequest;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\UpdatereturnsRequest;
use Validator;

class ReturnsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $returns;
    public function __construct(){
        $this->returns = new Returns(); 
    }
    public function index()
    {
        return $this->returns->all();
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
            
            'ref_no'=> 'nullable|string|max:255',
            'invoice_no'=> 'nullable|string|max:255',
            'date_time'=> 'nullable|datetype|max:255',
            'description'=> 'nullable|string|max:255',
            'user_id'=> 'required|exists:users,id',
            'return_amount'=> 'nullable|string|max:255',
            'return_vat' => 'nullable|string|max:255',
            'total_return_amount' => 'nullable|string|max:255',
            'qrcode' => 'nullable|string|max:255',
            'company_id' => 'nullable|numeric',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->returns->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $return = Returns::find($id);

        if (is_null($return)) {
            return $this->sendError('returns not found.');
        }

        return $return;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(returns $returns)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatereturnsRequest $request, returns $returns)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(returns $returns)
    {
        //
    }
}
