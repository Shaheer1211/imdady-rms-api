<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateIngredientRequest;
use Validator;
use Illuminate\Support\Facades\DB;

class IngredientController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $ingredients;
    public function __construct()
    {
        $this->ingredients = new Ingredient();
    }
    public function index()
    {
        // return $this->ingredients->all();
        $ingredients = DB::table('ingredients')
            ->join('ingredient_categories', 'ingredient_categories.id', '=', 'ingredients.category_id')
            ->join('ingredient_units', 'ingredient_units.id', '=', 'ingredients.unit_id')
            ->join('users', 'users.id', '=', 'ingredients.user_id')
            ->select('ingredients.*', 'ingredient_categories.category_name', 'ingredient_units.unit_name', 'users.name as added_by')
            ->get();

        return $ingredients;
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
        //
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:ingredient_categories,id',
            'purchase_price' => 'nullable|numeric',
            'vat_percentage' => 'nullable',
            'tax_method' => 'nullable|string|max:255',
            'ing_vat' => 'nullable|integer',
            'total_amount' => 'nullable|integer',
            'alert_quantity' => 'nullable|numeric',
            'unit_id' => 'required|exists:ingredient_units,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->ingredients->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredient = DB::table('ingredients')
            ->join('ingredient_categories', 'ingredient_categories.id', '=', 'ingredients.category_id')
            ->join('ingredient_units', 'ingredient_units.id', '=', 'ingredients.unit_id')
            ->join('users', 'users.id', '=', 'ingredients.user_id')
            ->select('ingredients.*', 'ingredient_categories.category_name', 'ingredient_units.unit_name', 'users.name as added_by')
            ->where('ingredients.id', $id)
            ->first();

        if (is_null($ingredient)) {
            return response()->json(['error' => 'Ingredient not found.'], 404);
        }

        return response()->json($ingredient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ingredients = $this->ingredients->find($id);

        if ($ingredients) {
            $ingredients->update($request->all());
            return response()->json(['message' => 'Ingredient update successfully'], 200);
        } else {
            return response()->json(['message' => 'Ingredient not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
