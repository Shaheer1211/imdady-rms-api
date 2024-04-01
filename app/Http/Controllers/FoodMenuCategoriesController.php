<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuCategories;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;

class FoodMenuCategoriesController extends BaseController
{
    protected $categories;
    public function __construct(){
        $this->categories = new FoodMenuCategories();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->categories->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'category_name' => 'nullable|string|max:255',
            'cat_name_arabic' => 'required|string|max:255',
            'cat_image' => 'nullable|string|max:255',
            'cat_banner' => 'nullable|string|max:255',
            'web_status' => 'required|in:active,inactive',
            'subscriptions_status' => 'required|in:active,inactive',
            'status' => 'required|in:active,inactive',
            'is_subscription' => 'required|boolean',
            'add_port' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'is_sub_cat' => 'required|boolean',
            'is_priority' => 'required|integer',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->categories->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = $this->categories->find($id);
        if (is_null($categories)) {
            return $this->sendError('Category not found.');
        }

        return $categories;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = $this->categories->find($id);
        if ($categories) {
            $categories->update($request->all()); 
            return response()->json(['message' => 'Category update successfully'], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = $this->categories->find($id);
        if ($categories) {
            $categories->delete();  
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
