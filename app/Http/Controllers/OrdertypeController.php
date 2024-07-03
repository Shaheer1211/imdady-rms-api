<?php

namespace App\Http\Controllers;

use App\Models\Ordertype;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\StoreordertypeRequest;
use App\Http\Requests\UpdateordertypeRequest;
use Validator;

class OrdertypeController extends BaseController
{
    protected $ordertype;
    public function __construct(){
        $this->ordertype = new Ordertype(); 
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        return $this->ordertype->all();
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
            
            'type'=> 'nullable|string|max:255',
            'name'=> 'nullable|string|max:255',
            'status'=> 'nullable|string|max:255'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->ordertype->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ordertype = Ordertype::find($id);

        if (is_null($ordertype)) {
            return $this->sendError('ordertype not found.');
        }

        return $ordertype;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ordertype $ordertype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateordertypeRequest $request, ordertype $ordertype)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ordertype $ordertype)
    {
        //
    }
}
