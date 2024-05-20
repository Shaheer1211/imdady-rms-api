<?php

namespace App\Http\Controllers;

use App\Models\socialmedia;
use App\Http\Requests\StoresocialmediaRequest;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdatesocialmediaRequest;
use Illuminate\Http\Request;
use Validator;

class SocialmediaController extends BaseController
{
    protected $Socialmedia;
    public function __construct(){
        $this->Socialmedia = new Socialmedia(); 
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->Socialmedia->all();
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
            'link'=> 'nullable|string|max:255',
            'status'=> 'nullable|string|max:255',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->Socialmedia->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Socialmedia = Socialmedia::find($id);

        if (is_null($socialmedia)) {
            return $this->sendError('Socialmedia not found.');
        }

        return $socialmedia;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(socialmedia $socialmedia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesocialmediaRequest $request, socialmedia $socialmedia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(socialmedia $socialmedia)
    {
        //
    }
}
