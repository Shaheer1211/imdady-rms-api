<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Models\waste;
use App\Http\Requests\StorewasteRequest;
use App\Http\Requests\UpdatewasteRequest;
use Illuminate\Http\Request;
use Validator;

class WasteController extends BaseController
{
    protected $waste;
    public function __construct()
    {
        $this->waste = new Waste();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Waste::all();
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
                'reference_no' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'total_loss' => 'nullable|numeric',
                'note' => 'nullable|string|max:255',
                'food_menu_id' => 'nullable|numeric',
                'food_menu_waste_qty' => 'nullable|string|max:255',
                'user_id' => 'nullable|numeric',
                'outlet_id' => 'nullable|numeric',
                'del_status' => 'nullable|string|max:255'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            return $this->waste->create($request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $waste = Waste::find($id);

        if (is_null($waste)) {
            return $this->sendError('waste not found.');
        }

        return $waste;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(waste $waste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatewasteRequest $request, waste $waste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(waste $waste)
    {
        //
    }
}
