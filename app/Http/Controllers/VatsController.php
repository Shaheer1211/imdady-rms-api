<?php

namespace App\Http\Controllers;

use App\Models\Vats;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVatsRequest;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateVatsRequest;
use Validator;

class VatsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $vats;
    public function __construct(){
        $this->vats = new Vats(); 
    }
    public function index()
    {
        return $this->vats->all();
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
            'name'=> 'nullable|string|max:255',
            'percentage'=> 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->vats->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vats = vats::find($id);

        if (is_null($vats)) {
            return $this->sendError('Vats not found.');
        }

        return $vats;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vats $vats)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVatsRequest $request, Vats $vats)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vats $vats)
    {
        //
    }
}
