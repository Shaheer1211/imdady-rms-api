<?php

namespace App\Http\Controllers;

use App\Models\IngredientCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateIngredientCategoriesRequest;
use Validator;

class IngredientCategoriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $ingredientCategories;
    public function __construct()
    {
        $this->ingredientCategories = new IngredientCategories();

    }
    public function index()
    {
        return IngredientCategories::all();
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
            'category_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->ingredientCategories->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredientCategory = IngredientCategories::find($id);

        if (is_null($ingredientCategory)) {
            return $this->sendError('Ingredient category not found.');
        }

        return $ingredientCategory;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IngredientCategories $ingredientCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingredientCategory = $this->ingredientCategories->find($id);

        if ($ingredientCategory){
            $ingredientCategory->update($request->all());
            return response()->json(['message' => 'Ingredient Category update successfully'], 200);
        }else {
            return response()->json(['message' => 'Ingredient Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientCategories $ingredientCategories)
    {
        //
    }
}
