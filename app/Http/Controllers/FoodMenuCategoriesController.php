<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuCategories;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class FoodMenuCategoriesController extends BaseController
{
    protected $categories;
    public function __construct()
    {
        $this->categories = new FoodMenuCategories();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categories->all();

    // Append the full image URL to each category object
    $categories->each(function ($category) {
        $category->cat_image_url = Storage::url($category->cat_image);
        $category->cat_banner_url = Storage::url($category->cat_banner);
    });
    return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'category_name' => 'nullable|string|max:255',
            'cat_name_arabic' => 'required|string|max:255',
            'cat_image' => 'nullable|file|max:255',
            'cat_banner' => 'nullable|file|max:255',
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
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $catImage = $request->file('cat_image')->store('category_images', 'public');
        $catBanner = $request->file('cat_banner')->store('category_banners', 'public');

        $catImage = $request->file('cat_image')->store('category_images', 'public');
        $catBanner = $request->file('cat_banner')->store('category_banners', 'public');
        $category = new FoodMenuCategories();
        $category->category_name = $request->input('category_name');
        $category->cat_name_arabic = $request->input('cat_name_arabic');
        $category->cat_image = $catImage;
        $category->cat_banner = $catBanner;
        $category->web_status = $request->input('web_status');
        $category->subscriptions_status = $request->input('subscriptions_status');
        $category->status = $request->input('status');
        $category->is_subscription = $request->input('is_subscription');
        $category->add_port = $request->input('add_port');
        $category->user_id = $request->input('user_id');
        $category->outlet_id = $request->input('outlet_id');
        $category->is_sub_cat = $request->input('is_sub_cat');
        $category->is_priority = $request->input('is_priority');

        $category->save();
        return response()->json(['message' => 'Category created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categories->find($id);
        
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
    
        // Append the full image URL to the category object
        $category->cat_image_url = Storage::url($category->cat_image);
        $category->cat_banner_url = Storage::url($category->cat_banner);
        
        return response()->json($category);
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
