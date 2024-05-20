<?php

namespace App\Http\Controllers;

use App\Models\FoodMenus;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateFoodMenusRequest;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class FoodMenusController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $foodMenus;
    public function __construct()
    {
        $this->foodMenus = new FoodMenus();

    }
    public function index(Request $request)
    {
        $outletId = $request->query('outlet_id');

        $query = $this->foodMenus->newQuery();

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        $foodMenus = $query->get();

        // Append the full image URL to each food menu item
        $foodMenus->each(function ($foodMenu) {
            if ($foodMenu->photo) {
                $foodMenu->photo_url = Storage::url($foodMenu->photo);
            }
        });

        return response()->json($foodMenus);
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
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_arabic' => 'required|string|max:255',
            'add_port_by_product' => 'nullable|string|max:255',
            'category_id' => 'required|exists:food_menu_categories,id',
            'sub_category_id' => 'required|exists:food_menu_sub_categories,id',
            'is_discount' => 'required|string|max:255',
            'discount_amount' => 'nullable|numeric',
            'description' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'hunger_station_price' => 'required|numeric',
            'jahiz_price' => 'required|numeric',
            'tax_method' => 'required|string|max:255',
            'kot_print' => 'required|string|max:255',
            'is_vendor' => 'required|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'vat_id' => 'required|exists:vats,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'required|file',
            'veg_item' => 'nullable|string|max:255',
            'beverage_item' => 'nullable|string|max:255',
            'bar_item' => 'nullable|string|max:255',
            'stock' => 'required|string|max:255',
            'is_new' => 'required|string|max:255',
            'is_tax_fix' => 'required|string|max:255',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('photos', 'public');
        }

        $foodMenu = $this->foodMenus->create(array_merge($request->all(), ['photo' => $photoName]));

        return response()->json(['message' => 'Food Menu created successfully', 'data' => $foodMenu], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $foodMenu = $this->foodMenus->find($id);
        if (is_null($foodMenu)) {
            return $this->sendError('Food Menu not found.');
        }

        // Append the full image URL to the food menu item
        if ($foodMenu->photo) {
            $foodMenu->photo_url = Storage::url($foodMenu->photo);
        }

        return response()->json($foodMenu);
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
