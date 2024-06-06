<?php

namespace App\Http\Controllers;

use App\Models\IngredientUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateIngredientUnitRequest;
use Validator;

class IngredientUnitController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $ingredientUnits;
    public function __construct()
    {
        $this->ingredientUnits = new IngredientUnit();
    }
    public function index()
    {
        return $this->ingredientUnits->all();
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
            'unit_name' => 'nullable|string|max:255',
            'unit_value' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->ingredientUnits->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredientUnit = IngredientUnit::find($id);

        if (is_null($ingredientUnit)) {
            return $this->sendError('Ingredient unit not found.');
        }

        return $ingredientUnit;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IngredientUnit $ingredientUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ingredientUnit = IngredientUnit::find($id);
        if ($ingredientUnit) {
            $ingredientUnit->update($request->all());
            return response()->json(['message' => 'Ingredient Unit update successfully'], 200);
        } else {
            return response()->json(['message' => 'Ingredient Unit not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientUnit $ingredientUnit)
    {
        //
    }
}
