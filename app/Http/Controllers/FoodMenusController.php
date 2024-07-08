<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuIngredient;
use App\Models\FoodMenus;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateFoodMenusRequest;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        $outletId = $request->query('outlet_id');
        $categoryId = $request->query('category_id');
        $isFeatured = $request->query('isFeatured');

        $data = [];

        // Build the query to get food menus with their ingredients count and additional details
        $query = DB::table('food_menuses')
            ->select(
                'food_menuses.id',
                'food_menuses.code',
                'food_menuses.name',
                'food_menuses.name_arabic',
                'food_menuses.add_port_by_product',
                'food_menuses.sale_price',
                'food_menuses.is_discount',
                'food_menuses.discount_amount',
                'food_menuses.hunger_station_price',
                'food_menuses.jahiz_price',
                'food_menuses.tax_method',
                'food_menuses.kot_print',
                'food_menuses.photo',
                'food_menuses.veg_item',
                'food_menuses.beverage_item',
                'food_menuses.bar_item',
                'food_menuses.is_new',
                'food_menuses.is_tax_fix',
                'food_menuses.del_status',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic',
                'vats.name AS vat_name',
                'vats.percentage AS vat_percentage',
                'users.name as added_by',
                DB::raw('COUNT(food_menu_ingredient.id) as ingredient_count')
            )
            ->leftJoin('food_menu_ingredient', 'food_menu_ingredient.food_menu_id', '=', 'food_menuses.id')
            ->join('food_menu_categories', 'food_menu_categories.id', '=', 'food_menuses.category_id')
            ->join('users', 'users.id', '=', 'food_menuses.user_id')
            ->join('vats', 'vats.id', '=', 'food_menuses.vat_id')
            ->groupBy(
                'food_menuses.id',
                'food_menuses.code',
                'food_menuses.name',
                'food_menuses.name_arabic',
                'food_menuses.add_port_by_product',
                'food_menuses.sale_price',
                'food_menuses.is_discount',
                'food_menuses.discount_amount',
                'food_menuses.hunger_station_price',
                'food_menuses.jahiz_price',
                'food_menuses.tax_method',
                'food_menuses.kot_print',
                'food_menuses.photo',
                'food_menuses.veg_item',
                'food_menuses.beverage_item',
                'food_menuses.bar_item',
                'food_menuses.is_new',
                'food_menuses.is_tax_fix',
                'food_menuses.del_status',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic',
                'vats.name',
                'vats.percentage',
                'users.name'
            );

        // Apply filters based on the request
        if ($outletId) {
            $query->where('food_menuses.outlet_id', $outletId);
        }
        if ($categoryId) {
            $query->where('food_menuses.category_id', $categoryId);
        }

        $foodMenus = $query->get();

        // Append the full image URL to each food menu item
        $foodMenus->each(function ($foodMenu) {
            if ($foodMenu->photo) {
                $foodMenu->photo_url = url(Storage::url($foodMenu->photo));
            }
        });

        // If featured food menus are requested
        if ($isFeatured === 'yes') {
            $featuredQuery = DB::table('food_menuses')
                ->select(
                    'food_menuses.id',
                    'food_menuses.code',
                    'food_menuses.name',
                    'food_menuses.name_arabic',
                    'food_menuses.add_port_by_product',
                    'food_menuses.sale_price',
                    'food_menuses.is_discount',
                    'food_menuses.discount_amount',
                    'food_menuses.hunger_station_price',
                    'food_menuses.jahiz_price',
                    'food_menuses.tax_method',
                    'food_menuses.kot_print',
                    'food_menuses.photo',
                    'food_menuses.veg_item',
                    'food_menuses.beverage_item',
                    'food_menuses.bar_item',
                    'food_menuses.is_new',
                    'food_menuses.is_tax_fix',
                    'food_menuses.del_status',
                    'food_menu_categories.category_name',
                    'food_menu_categories.cat_name_arabic',
                    'users.name as added_by',
                    DB::raw('COUNT(food_menu_ingredient.id) as ingredient_count')
                )
                ->leftJoin('food_menu_ingredient', 'food_menu_ingredient.food_menu_id', '=', 'food_menuses.id')
                ->join('food_menu_categories', 'food_menu_categories.id', '=', 'food_menuses.category_id')
                ->join('users', 'users.id', '=', 'food_menuses.user_id')
                ->groupBy(
                    'food_menuses.id',
                    'food_menuses.code',
                    'food_menuses.name',
                    'food_menuses.name_arabic',
                    'food_menuses.add_port_by_product',
                    'food_menuses.sale_price',
                    'food_menuses.is_discount',
                    'food_menuses.discount_amount',
                    'food_menuses.hunger_station_price',
                    'food_menuses.jahiz_price',
                    'food_menuses.tax_method',
                    'food_menuses.kot_print',
                    'food_menuses.photo',
                    'food_menuses.veg_item',
                    'food_menuses.beverage_item',
                    'food_menuses.bar_item',
                    'food_menuses.is_new',
                    'food_menuses.is_tax_fix',
                    'food_menuses.del_status',
                    'food_menu_categories.category_name',
                    'food_menu_categories.cat_name_arabic',
                    'users.name'
                )
                ->where('food_menuses.is_new', 'yes');

            // Apply filters based on the request
            if ($outletId) {
                $featuredQuery->where('food_menuses.outlet_id', $outletId);
            }
            if ($categoryId) {
                $featuredQuery->where('food_menuses.category_id', $categoryId);
            }

            $featured = $featuredQuery->get();
            $data['featured'] = $featured;
        }

        $data['menu'] = $foodMenus;

        return response()->json($data);
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
            'sub_category_id' => 'required|exists:food_menu_sub_categories,id',
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
            $photoName = $request->file('photo')->store('storage/photos', 'public');
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
        $query = DB::table('food_menuses')
            ->select(
                'food_menuses.id',
                'food_menuses.code',
                'food_menuses.name',
                'food_menuses.name_arabic',
                'food_menuses.add_port_by_product',
                'food_menuses.sale_price',
                'food_menuses.is_discount',
                'food_menuses.discount_amount',
                'food_menuses.hunger_station_price',
                'food_menuses.jahiz_price',
                'food_menuses.tax_method',
                'food_menuses.kot_print',
                'food_menuses.photo',
                'food_menuses.veg_item',
                'food_menuses.beverage_item',
                'food_menuses.bar_item',
                'food_menuses.is_new',
                'food_menuses.is_tax_fix',
                'food_menuses.del_status',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic',
                'vats.name AS vat_name',
                'vats.percentage AS vat_percentage',
                'users.name as added_by',
                DB::raw('COUNT(food_menu_ingredient.id) as ingredient_count')
            )
            ->leftJoin('food_menu_ingredient', 'food_menu_ingredient.food_menu_id', '=', 'food_menuses.id')
            ->join('food_menu_categories', 'food_menu_categories.id', '=', 'food_menuses.category_id')
            ->join('users', 'users.id', '=', 'food_menuses.user_id')
            ->join('vats', 'vats.id', '=', 'food_menuses.vat_id')
            ->groupBy(
                'food_menuses.id',
                'food_menuses.code',
                'food_menuses.name',
                'food_menuses.name_arabic',
                'food_menuses.add_port_by_product',
                'food_menuses.sale_price',
                'food_menuses.is_discount',
                'food_menuses.discount_amount',
                'food_menuses.hunger_station_price',
                'food_menuses.jahiz_price',
                'food_menuses.tax_method',
                'food_menuses.kot_print',
                'food_menuses.photo',
                'food_menuses.veg_item',
                'food_menuses.beverage_item',
                'food_menuses.bar_item',
                'food_menuses.is_new',
                'food_menuses.is_tax_fix',
                'food_menuses.del_status',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic',
                'vats.name',
                'vats.percentage',
                'users.name'
            )
            ->where('food_menuses.id', $id);
        $foodMenu = $query->get();
        // print_r($foodMenu);
        // exit();

        if (is_null($foodMenu[0])) {
            return $this->sendError('Food Menu not found.');
        }

        // Append the full image URL to the food menu item
        if ($foodMenu[0]->photo) {
            $foodMenu[0]->photo_url = Storage::url($foodMenu[0]->photo);
        }

        return response()->json($foodMenu[0]);
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
    public function update(UpdateFoodMenusRequest $request, FoodMenus $foodMenus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodMenus $foodMenus)
    {
        //
    }
}
