<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuCategories;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
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
    public function index(Request $request)
    {
        // Start with the query builder instance
        $query = $this->categories->newQuery();

        // Apply filters if they are present in the request
        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->has('web_status')) {
            $query->where('web_status', $request->query('web_status'));
        }
        if ($request->has('subscriptions_status')) {
            $query->where('subscriptions_status', $request->query('subscriptions_status'));
        }
        if ($request->has('is_sub_cat')) {
            $query->where('is_sub_cat', $request->query('is_sub_cat'));
        }

        // Retrieve the categories based on the query
        $categories = $query->get();

        // Append the full image URL to each category object
        $categories->each(function ($category) {
            $category->cat_image_url = url(Storage::url($category->cat_image));
            $category->cat_banner_url = url(Storage::url($category->cat_banner));
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
            'description' => 'nullable|string',
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
            'is_priority' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = new FoodMenuCategories();
        $category->category_name = $request->input('category_name');
        $category->cat_name_arabic = $request->input('cat_name_arabic');
        $category->description = $request->input('description');
        $category->web_status = $request->input('web_status');
        $category->subscriptions_status = $request->input('subscriptions_status');
        $category->status = $request->input('status');
        $category->is_subscription = $request->input('is_subscription');
        $category->add_port = $request->input('add_port');
        $category->user_id = $request->input('user_id');
        $category->outlet_id = $request->input('outlet_id');
        $category->is_sub_cat = $request->input('is_sub_cat');
        $category->is_priority = $request->input('is_priority');

        // Store the photos if provided
        if ($request->hasFile('cat_image')) {
            $catImage = $request->file('cat_image')->store('storage/category_images', 'public');
            $category->cat_image = $catImage;
        }

        if ($request->hasFile('cat_banner')) {
            $catBanner = $request->file('cat_banner')->store('storage/category_banners', 'public');
            $category->cat_banner = $catBanner;
        }

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
        $category->cat_image_url = url(Storage::url($category->cat_image));
        $category->cat_banner_url = url(Storage::url($category->cat_banner));

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = $this->categories->find($id);
        if ($categories) {
            $validator = Validator::make($request->all(), [
                'category_name' => 'nullable|string|max:255',
                'cat_name_arabic' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'cat_image' => 'nullable|file|max:255',
                'cat_banner' => 'nullable|file|max:255',
                'web_status' => 'nullable|in:active,inactive',
                'subscriptions_status' => 'nullable|in:active,inactive',
                'status' => 'nullable|in:active,inactive',
                'is_subscription' => 'nullable|boolean',
                'add_port' => 'nullable|string|max:255',
                'user_id' => 'nullable|exists:users,id',
                'outlet_id' => 'nullable|exists:outlets,id',
                'is_sub_cat' => 'nullable|boolean',
                'is_priority' => 'nullable|integer',
            ]);
    
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
    
            // Log the request payload for debugging
            error_log('Request payload: ' . print_r($request->all(), true));
    
            // Assign each field
            $categories->category_name = $request->input('category_name', $categories->category_name);
            $categories->cat_name_arabic = $request->input('cat_name_arabic', $categories->cat_name_arabic);
            $categories->description = $request->input('description', $categories->description);
            $categories->web_status = $request->input('web_status', $categories->web_status);
            $categories->subscriptions_status = $request->input('subscriptions_status', $categories->subscriptions_status);
            $categories->status = $request->input('status', $categories->status);
            $categories->is_subscription = $request->input('is_subscription', $categories->is_subscription);
            $categories->add_port = $request->input('add_port', $categories->add_port);
            $categories->user_id = $request->input('user_id', $categories->user_id);
            $categories->outlet_id = $request->input('outlet_id', $categories->outlet_id);
            $categories->is_sub_cat = $request->input('is_sub_cat', $categories->is_sub_cat);
            $categories->is_priority = $request->input('is_priority', $categories->is_priority);
    
            // Log the category before saving
            error_log('Category before saving: ' . print_r($categories->toArray(), true));
    
            // Store the photos if provided
            if ($request->hasFile('cat_image')) {
                $catImage = $request->file('cat_image')->store('storage/category_images', 'public');
                $categories->cat_image = $catImage;
            }
    
            if ($request->hasFile('cat_banner')) {
                $catBanner = $request->file('cat_banner')->store('storage/category_banners', 'public');
                $categories->cat_banner = $catBanner;
            }
    
            // Save the category
            $categories->save();
    
            // Log the category after saving
            error_log('Category after saving: ' . print_r($categories->toArray(), true));
    
            return response()->json(['message' => 'Category updated successfully', 'category' => $categories], 200);
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
