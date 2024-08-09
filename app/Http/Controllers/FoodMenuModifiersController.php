<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuModifiers;
use App\Http\Requests\UpdateFoodMenuModifiersRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;

class FoodMenuModifiersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $foodMenuModifiers;
    public function __construct()
    {
        $this->foodMenuModifiers = new FoodMenuModifiers();

    }
    public function index()
    {
        $foodMenus = DB::table('food_menuses as fm')
            ->leftJoin('food_menu_modifiers as fmm', 'fm.id', '=', 'fmm.food_menu_id')
            ->leftJoin('modifiers as m', 'fmm.modifier_id', '=', 'm.id')
            ->groupBy(
                'fm.id',
                'fm.name',
                'fm.code',
                "fm.name_arabic",
                "fm.add_port_by_product",
                "fm.category_id",
                "fm.sub_category_id",
                "fm.is_discount",
                "fm.discount_amount",
                "fm.description",
                "fm.sale_price",
                "fm.hunger_station_price",
                "fm.jahiz_price",
                "fm.tax_method",
                "fm.kot_print",
                "fm.is_vendor",
                "fm.vendor_name",
                "fm.vat_id",
                "fm.user_id",
                "fm.outlet_id",
                "fm.photo",
                "fm.veg_item",
                "fm.beverage_item",
                "fm.bar_item",
                "fm.stock",
                "fm.is_new",
                "fm.is_tax_fix",
                "fm.del_status",
                "fm.created_at",
                "fm.updated_at"
            )
            ->select('fm.*', DB::raw('GROUP_CONCAT(CONCAT(\'{ "id": \', m.id, \', "addOn": "\', m.name, \', "addOnPrice": "\', m.price, \'"}\') SEPARATOR \', \') AS modifiers'))
            ->get();

        $foodMenus->each(function ($foodMenu) {
            if ($foodMenu->photo) {
                $foodMenu->photo_url = Storage::url($foodMenu->photo);
            }
        });

        return $foodMenus;
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
            'food_menu_id' => 'required|exists:food_menuses,id',
            'modifier_id' => 'required|exists:modifiers,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->foodMenuModifiers->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $foodMenu = DB::table('food_menuses as fm')
            ->leftJoin('food_menu_modifiers as fmm', 'fm.id', '=', 'fmm.food_menu_id')
            ->leftJoin('modifiers as m', 'fmm.modifier_id', '=', 'm.id')
            ->where('fm.id', $id)
            ->groupBy(
                'fm.id',
                'fm.name',
                'fm.code',
                "fm.name_arabic",
                "fm.add_port_by_product",
                "fm.category_id",
                "fm.sub_category_id",
                "fm.is_discount",
                "fm.discount_amount",
                "fm.description",
                "fm.sale_price",
                "fm.hunger_station_price",
                "fm.jahiz_price",
                "fm.tax_method",
                "fm.kot_print",
                "fm.is_vendor",
                "fm.vendor_name",
                "fm.vat_id",
                "fm.user_id",
                "fm.outlet_id",
                "fm.photo",
                "fm.veg_item",
                "fm.beverage_item",
                "fm.bar_item",
                "fm.stock",
                "fm.is_new",
                "fm.is_tax_fix",
                "fm.del_status",
                "fm.created_at",
                "fm.updated_at"
            )
            ->select('fm.*', DB::raw('GROUP_CONCAT(CONCAT(\'{ "id": \', m.id, \', "addOn": "\', m.name, \', "addOnPrice": "\', m.price, \'"}\') SEPARATOR \', \') AS modifiers'))
            ->first();


        // Append the full image URL to the food menu item
        if ($foodMenu->photo) {
            $foodMenu->photo_url = Storage::url($foodMenu->photo);
        }
        return $foodMenu;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodMenuModifiers $foodMenuModifiers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Delete existing modifiers for the given food menu
        FoodMenuModifiers::where('food_menu_id', $id)->delete();
    
        // Iterate over the new modifiers from the request and create them
        foreach ($request->modifiers as $modifierId) {
            $data['food_menu_id'] = $id;
            $data['modifier_id'] = $modifierId;  // Use the modifier ID directly
            $data['user_id'] = $request->input('user_id');  // Fetch user_id from request
            $data['outlet_id'] = $request->input('outlet_id');  // Fetch outlet_id from request
    
            FoodMenuModifiers::create($data);
        }
    
        // Return a success message
        return response()->json(['message' => 'Modifiers updated successfully'], 200);
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodMenuModifiers $foodMenuModifiers)
    {
        //
    }
}
