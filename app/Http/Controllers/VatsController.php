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
    public function __construct()
    {
        $this->vats = new Vats();
    }
    public function index()
    {
        return Vats::where('del_status', 'Live')->get();
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
            'percentage' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
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
    public function update(Request $request, string $id)
    {
        $vats = vats::find($id);
        if ($vats) {
            $vats->update($request->all());
            return response()->json(['message' => 'Vats update successfully'], 200);
        } else {
            return response()->json(['message' => 'Vats not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Find the deal by its ID
        $vat = Vats::find($id);

        // Check if the deal exists
        if (!$vat) {
            return $this->sendError('Vat not found.', [], 404);
        }

        // Delete the deal and its associated items
        $vat['del_status'] = 'deleted';
        $vat->update();

        return $this->sendResponse('Deal deleted successfully.', $vat);
    }
}
