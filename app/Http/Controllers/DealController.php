<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealItem;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DealController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $deal;
    public function __construct()
    {
        $this->deal = new Deal();
    }
    public function index(Request $request)
    {
        // Start building the base query
        $query = DB::table('deal')
            ->select(
                'deal.id as deal_id',
                'deal.name as deal_name',
                'deal.name_arabic as deal_name_arabic',
                'deal.sale_price',
                'deal.photo as deal_photo',
                'deal.is_discount',
                'deal.discount_percentage',
                'vats.name as vat_name',
                'vats.percentage as vat_percentage',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic'
            )
            ->join('food_menu_categories', 'food_menu_categories.id', '=', 'deal.category_id')
            ->join('vats', 'vats.id', '=', 'deal.vat_id');

        // Check if category_id is provided in the request
        if ($request->has('category')) {
            // Filter deals by the provided category_id
            $category_id = $request->input('category');
            $query->where('deal.category_id', $category_id);
        }

        // Fetch deals based on the query
        $deals = $query->get();

        // Fetch related food_menuses for each deal
        foreach ($deals as &$deal) {
            $deal->deal_photo = Storage::url($deal->deal_photo);
            $deal->food_menuses = DB::table('deal_item')
                ->select(
                    'food_menuses.name as menu_name',
                    'food_menuses.name_arabic as menu_name_arabic',
                    'food_menuses.code as menu_code',
                    'food_menuses.description as menu_description',
                    'food_menuses.photo as menu_photo'
                )
                ->join('food_menuses', 'food_menuses.id', '=', 'deal_item.item_id')
                ->where('deal_item.deal_id', $deal->deal_id)
                ->get();
        }

        return $this->sendResponse($deals, 'Deals fetched successfully.');
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
            'category_id' => 'required|exists:food_menu_categories,id',
            'is_discount' => 'required|string|max:255',
            'discount_percentage' => 'nullable|numeric',
            'description' => 'required|string',
            'sale_price' => 'required|numeric',
            'hunger_station_price' => 'required|numeric',
            'jahiz_price' => 'required|numeric',
            'tax_method' => 'required|string|max:255',
            'kot_print' => 'required|string|max:255',
            'vat_id' => 'required|exists:vats,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'required|file',
            'items' => 'required|array',
            'del_status' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Create a new Deal instance
        $deal = $this->deal;
        $deal->code = $request->input('code');
        $deal->name = $request->input('name');
        $deal->name_arabic = $request->input('name_arabic');
        $deal->category_id = $request->input('category_id');
        $deal->is_discount = $request->input('is_discount');
        $deal->discount_percentage = $request->input('discount_percentage');
        $deal->description = $request->input('description');
        $deal->sale_price = $request->input('sale_price');
        $deal->hunger_station_price = $request->input('hunger_station_price');
        $deal->jahiz_price = $request->input('jahiz_price');
        $deal->tax_method = $request->input('tax_method');
        $deal->kot_print = $request->input('kot_print');
        $deal->vat_id = $request->input('vat_id');
        $deal->user_id = $request->input('user_id');
        $deal->outlet_id = $request->input('outlet_id');
        // You may need to handle date attributes similarly if you have any


        // Store the photo if provided
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('deals', 'public');
            $deal->photo = $photoName;
        }

        // Save the Deal to the database
        $deal->save();

        foreach ($request->input('items') as $item) {
            $dealItem = new DealItem();
            $dealItem->deal_id = $deal->id;
            $dealItem->item_id = $item['item_id'];
            $dealItem->quantity = $item['quantity'];
            $dealItem->save();
        }

        // exit();
        return $this->sendResponse('Deal created successfully.', $deal, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retrieve the deal based on the provided ID
        $deal = DB::table('deal')
            ->select(
                'deal.id as deal_id',
                'deal.name as deal_name',
                'deal.name_arabic as deal_name_arabic',
                'deal.sale_price',
                'deal.photo as deal_photo',
                'deal.is_discount',
                'deal.discount_percentage',
                'vats.name as vat_name',
                'vats.percentage',
                'food_menu_categories.category_name',
                'food_menu_categories.cat_name_arabic'
            )
            ->join('food_menu_categories', 'food_menu_categories.id', '=', 'deal.category_id')
            ->join('vats', 'vats.id', '=', 'deal.vat_id')
            ->where('deal.id', $id)
            ->first();

        // Check if the deal exists
        if (!$deal) {
            return $this->sendError('Deal not found.', [], 404);
        }
        $deal->deal_photo = Storage::url($deal->deal_photo);

        // Fetch related food_menuses for the deal
        $deal->food_menuses = DB::table('deal_item')
            ->select(
                'food_menuses.name as menu_name',
                'food_menuses.name_arabic as menu_name_arabic',
                'food_menuses.code as menu_code',
                'food_menuses.description as menu_description',
                'food_menuses.photo as menu_photo'
            )
            ->join('food_menuses', 'food_menuses.id', '=', 'deal_item.item_id')
            ->where('deal_item.deal_id', $deal->deal_id)
            ->get();

        return $this->sendResponse($deal, 'Deal fetched successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deal $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'name_arabic' => 'required|string|max:255',
            'category_id' => 'required|exists:food_menu_categories,id',
            'is_discount' => 'required|string|max:255',
            'discount_percentage' => 'nullable|numeric',
            'description' => 'required|string',
            'sale_price' => 'required|numeric',
            'hunger_station_price' => 'required|numeric',
            'jahiz_price' => 'required|numeric',
            'tax_method' => 'required|string|max:255',
            'kot_print' => 'required|string|max:255',
            'vat_id' => 'required|exists:vats,id',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'photo' => 'nullable|file',
            'items' => 'required|array',
            'del_status' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Find the deal by its ID
        $deal = Deal::find($id);

        // Check if the deal exists
        if (!$deal) {
            return $this->sendError('Deal not found.', [], 404);
        }

        // Update the deal attributes
        $deal->code = $request->input('code');
        $deal->name = $request->input('name');
        $deal->name_arabic = $request->input('name_arabic');
        $deal->category_id = $request->input('category_id');
        $deal->is_discount = $request->input('is_discount');
        $deal->discount_percentage = $request->input('discount_percentage');
        $deal->description = $request->input('description');
        $deal->sale_price = $request->input('sale_price');
        $deal->hunger_station_price = $request->input('hunger_station_price');
        $deal->jahiz_price = $request->input('jahiz_price');
        $deal->tax_method = $request->input('tax_method');
        $deal->kot_print = $request->input('kot_print');
        $deal->vat_id = $request->input('vat_id');
        $deal->user_id = $request->input('user_id');
        $deal->outlet_id = $request->input('outlet_id');
        // You may need to handle date attributes similarly if you have any

        // Store the photo if provided
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('deals', 'public');
            $deal->photo = $photoName;
        }

        // Save the updated Deal to the database
        $deal->save();

        // Delete existing deal items
        $deal->items()->delete();

        // Create/update deal items
        foreach ($request->input('items') as $item) {
            $dealItem = new DealItem();
            $dealItem->deal_id = $deal->id;
            $dealItem->item_id = $item['item_id'];
            $dealItem->quantity = $item['quantity'];
            $dealItem->save();
        }

        return $this->sendResponse('Deal updated successfully.', $deal);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

    }
}
