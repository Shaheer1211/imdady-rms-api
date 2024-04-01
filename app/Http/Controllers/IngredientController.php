<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateIngredientRequest;
use Validator;

class IngredientController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $ingredients;
    public function __construct(){
        $this->ingredients = new Ingredient(); 
    }
    public function index()
    {
        return $this->ingredients->all();
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
            'code'=> 'nullable|string|max:255',
            'name'=> 'nullable|string|max:255',
            'category_id' => 'required|exists:ingredient_categories,id',
            'purchase_price'=> 'nullable|numeric',
            'vat_percentage'=> 'nullable|string|max:255',
            'tax_method'=> 'nullable|string|max:255',
            'ing_vat'=> 'nullable|string|max:255',
            'total_amount'=> 'nullable|string|max:255',
            'alert_quantity'=> 'nullable|numeric',
            'unit_id' => 'required|exists:ingredient_units,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->ingredients->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredient = Ingredient::find($id);

        if (is_null($ingredient)) {
            return $this->sendError('Ingredient not found.');
        }

        return $ingredient;
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
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
