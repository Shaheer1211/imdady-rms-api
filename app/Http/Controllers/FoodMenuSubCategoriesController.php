<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuSubCategories;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateFoodMenuSubCategoriesRequest;
use Validator;

class FoodMenuSubCategoriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $subCategories;
    public function __construct()
    {
        $this->subCategories = new FoodMenuSubCategories();

    }
    public function index()
    {
        return $this->subCategories->all();
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
            'category_id' => 'required|exists:food_menu_categories,id',
            'sub_category_name' => 'required |string|max:255',
            'sub_category_name_arabic' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->subCategories->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subCategories = $this->subCategories->find($id);
        if (is_null($subCategories)) {
            return $this->sendError('Sub Category not found.');
        }

        return $subCategories;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodMenuSubCategories $foodMenuSubCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodMenuSubCategoriesRequest $request, FoodMenuSubCategories $foodMenuSubCategories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodMenuSubCategories $foodMenuSubCategories)
    {
        //
    }
}
