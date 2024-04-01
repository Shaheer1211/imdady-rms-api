<?php

namespace App\Http\Controllers;

use App\Models\FoodMenus;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateFoodMenusRequest;
use Illuminate\Http\Request;
use Validator;

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
    public function index()
    {
        return $this->foodMenus->all();
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
            'is_deals' => 'required|string|max:255',
            'deal_items_and_qty' => 'nullable|text',
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
            'photo' => 'required|string|max:255',
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

        return $this->foodMenus->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $foodMenus = $this->foodMenus->find($id);
        if (is_null($foodMenus)) {
            return $this->sendError('Food Menu not found.');
        }

        return $foodMenus;
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
