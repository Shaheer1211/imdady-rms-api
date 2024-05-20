<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\topbanner;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\StoretopbannerRequest;
use App\Http\Requests\UpdatetopbannerRequest;
use Validator;

class TopbannerController extends BaseController
{
    protected $topbanner;
    public function __construct(){
        $this->topbanner = new Topbanner(); 
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->topbanner = new Topbanner();
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
            'text'=> 'nullable|string|max:255',
            'status'=> 'nullable|string|max:255', 
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->topbanner->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $topbanner = Topbanner::find($id);

        if (is_null($topbanner)) {
            return $this->sendError('topbanner not found.');
        }

        return $topbanner;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(topbanner $topbanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatetopbannerRequest $request, topbanner $topbanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(topbanner $topbanner)
    {
        //
    }
}
