<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuIngredient;
use App\Models\FoodMenus;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class FoodMenusController extends BaseController
{
    protected $foodMenus;
    protected $foodMenuIngredient;

    // Initialize the controller
    public function __construct()
    {
        $this->foodMenus = new FoodMenus();
        $this->foodMenuIngredient = new FoodMenuIngredient();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $outletId = $request->query('outletId');
        $categoryId = $request->query('categoryId');
        $status = $request->query('status');
        $isFeatured = $request->query('isFeatured');

        $data = [];

        $foodMenusQuery = FoodMenus::with([
            'category',
            'subCategory',
            'vat',
            'user',
            'outlet',
            'ingredients.ingredient',
            'ingredients.ingredient.ingredientUnit',
            'modifiers.modifier'
        ])->where('del_status', 'Live');

        if ($outletId) {
            $foodMenusQuery->where('outlet_id', $outletId);
        }

        if ($categoryId) {
            $foodMenusQuery->where('category_id', $categoryId);
        }

        if ($status) {
            $foodMenusQuery->where('status', $status);
        }

        $data['foodMenus'] = $foodMenusQuery->get();

        $data['foodMenus']->each(function ($foodMenu) {
            $foodMenu->photo_url = url(Storage::url($foodMenu->photo));
        });


        if ($isFeatured === 'yes') {
            $featuredQuery = FoodMenus::with([
                'category',
                'subCategory',
                'vat',
                'user',
                'outlet',
                'ingredients.ingredient',
                'modifiers.modifier'
            ])->where('del_status', 'Live')->where('is_new', 'yes');

            if ($outletId) {
                $featuredQuery->where('outlet_id', $outletId);
            }

            if ($categoryId) {
                $featuredQuery->where('category_id', $categoryId);
            }

            if ($status) {
                $featuredQuery->where('status', $status);
            }

            $data['featured'] = $featuredQuery->get();
        }


        return response()->json($data, 200);
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
        // Validate the request
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_arabic' => 'required|string|max:255',
            'add_port_by_product' => 'nullable|string|max:255',
            'category_id' => 'required|exists:food_menu_categories,id',
            'sub_category_id' => 'nullable|exists:food_menu_sub_categories,id',
            'is_discount' => 'required|string|max:255',
            'discount_amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'sale_price' => 'required|numeric',
            'hunger_station_price' => 'required|numeric',
            'jahiz_price' => 'required|numeric',
            'tax_method' => 'required|string|max:255',
            'kot_print' => 'required|string|max:255',
            'is_vendor' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'vat_id' => 'required|exists:vats,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'required|file',
            'veg_item' => 'nullable|string|max:255',
            'beverage_item' => 'nullable|string|max:255',
            'bar_item' => 'nullable|string|max:255',
            'stock' => 'nullable|string|max:255',
            'is_new' => 'required|string|max:255',
            'is_tax_fix' => 'required|string|max:255',
            'del_status' => 'nullable',
            'ingredients' => 'required|array', // Validate that ingredients is an array
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.consumption' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Handle file upload
        $photoName = null;
        if ($request->hasFile('photo')) {
            $imageName = $request->photo->getClientOriginalExtension();
            $image = rand() . '.' . $imageName;
            $request->photo->move('storage/foodmenu', $image);
            $photoName = 'foodmenu/' . $image;
        }

        // Create the food menu
        $foodMenu = $this->foodMenus->create(array_merge($request->all(), ['photo' => $photoName]));
        $ingredients = $request->input('ingredients', []);
        foreach ($ingredients as $ingredient) {
            $this->foodMenuIngredient->create([
                'food_menu_id' => $foodMenu->id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'consumption' => $ingredient['consumption'],
                'user_id' => $request->user_id,
                'outlet_id' => $request->outlet_id,
            ]);
        }

        return response()->json(['message' => 'Food Menu created successfully', 'data' => $foodMenu], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $foodMenu = FoodMenus::with([
            'category',
            'subCategory',
            'vat',
            'user',
            'outlet',
            'ingredients.ingredient',
            'ingredients.ingredient.ingredientUnit',
            'modifiers.modifier'
        ])->where('id', $id)->first();

        if (!$foodMenu) {
            return response()->json(['message' => 'Food Menu not found'], 404);
        }

        return response()->json($foodMenu, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodMenus $foodMenus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'name_arabic' => 'sometimes|required|string|max:255',
            'add_port_by_product' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|required|exists:food_menu_categories,id',
            'sub_category_id' => 'sometimes|exists:food_menu_sub_categories,id',
            'is_discount' => 'sometimes|required|string|max:255',
            'discount_amount' => 'sometimes|nullable|numeric',
            'description' => 'sometimes|string',
            'sale_price' => 'sometimes|required|numeric',
            'hunger_station_price' => 'sometimes|required|numeric',
            'jahiz_price' => 'sometimes|required|numeric',
            'tax_method' => 'sometimes|required|string|max:255',
            'kot_print' => 'sometimes|required|string|max:255',
            'is_vendor' => 'sometimes|string|max:255',
            'vendor_name' => 'sometimes|string|max:255',
            'vat_id' => 'sometimes|required|exists:vats,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'outlet_id' => 'sometimes|required|exists:outlets,id',
            'veg_item' => 'sometimes|string|max:255',
            'beverage_item' => 'sometimes|string|max:255',
            'bar_item' => 'sometimes|string|max:255',
            'stock' => 'sometimes|string|max:255',
            'status' => 'sometimes|required|string|max:255',
            'is_new' => 'sometimes|required|string|max:255',
            'is_tax_fix' => 'sometimes|required|string|max:255',
            'del_status' => 'sometimes',
            'ingredients' => 'sometimes|array', // Validate that ingredients is an array
            'ingredients.*.ingredient_id' => 'required_with:ingredients|exists:ingredients,id',
            'ingredients.*.consumption' => 'required_with:ingredients',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // Find the food menu
        $foodMenu = $this->foodMenus->find($id);
    
        if (!$foodMenu) {
            return response()->json(['message' => 'Food Menu not found'], 404);
        }
    
        // Handle file upload
        $photoName = $foodMenu->photo;
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($photoName) {
                Storage::disk('public')->delete($photoName);
            }
            // Store the new photo
            $photoName = $request->file('photo')->store('storage/photos', 'public');
        }
    
        // Set default discount_amount if not provided or set it to null
        $data = $request->all();
        if (isset($data['discount_amount']) && $data['discount_amount'] === '') {
            $data['discount_amount'] = null;
        }
    
        $foodMenu->update(array_merge($data, ['photo' => $photoName]));
    
        // Update ingredients if provided
        if ($request->has('ingredients')) {
            // Delete existing ingredients
            $this->foodMenuIngredient->where('food_menu_id', $id)->delete();
    
            // Create new ingredients
            $ingredients = $request->input('ingredients', []);
            foreach ($ingredients as $ingredient) {
                $this->foodMenuIngredient->create([
                    'food_menu_id' => $id,
                    'ingredient_id' => $ingredient['ingredient_id'],
                    'consumption' => $ingredient['consumption'],
                    'user_id' => $request->user_id,
                    'outlet_id' => $request->outlet_id,
                ]);
            }
        }
    
        return response()->json(['message' => 'Food Menu updated successfully', 'data' => $foodMenu], 200);
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Find the deal by its ID
        $foodMenu = FoodMenus::find($id);

        // Check if the deal exists
        if (!$foodMenu) {
            return $this->sendError('Item not found.', [], 404);
        }

        // Delete the deal and its associated items
        $foodMenu['del_status'] = 'deleted';
        $foodMenu->update();

        return $this->sendResponse('Deal deleted successfully.', $foodMenu);
    }
}
