<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modifiers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateModifiersRequest;
use Validator;

class ModifiersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $modifiers;
    public function __construct(){
        $this->modifiers = new Modifiers(); 
    }
    public function index()
    {
        return $this->modifiers->all();
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
            'code'=> 'nullable|string|max:255',
            'name'=> 'nullable|string|max:255',
            'price'=> 'nullable|numeric',
            'description'=> 'nullable|string|max:255',
            'tax_method'=> 'nullable|string|max:255',
            'tax'=> 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->modifiers->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modifier = Modifiers::find($id);

        if (is_null($modifier)) {
            return $this->sendError('Modifier not found.');
        }

        return $modifier;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modifiers $modifiers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModifiersRequest $request, Modifiers $modifiers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modifiers $modifiers)
    {
        //
    }
}
