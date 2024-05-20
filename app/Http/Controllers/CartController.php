<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $cart;
    public function __construct()
    {
        $this->cart = new Cart();
    }
    public function index(Request $request)
{
    if (!($request->user_id || $request->outlet_id)) {
        return $this->sendError('Incomplete Request');
    }

    $results = \DB::table('carts')
        ->select(
            'carts.id',
            'carts.quantity',
            'food_menuses.id AS food_menu_id',
            'food_menuses.code AS food_menu_code',
            'food_menuses.name AS food_menu_name',
            'food_menuses.name_arabic AS food_menu_name_arabic',
            'food_menuses.sale_price',
            'food_menuses.is_discount',
            'food_menuses.discount_amount AS food_menu_discount_amount',
            'food_menuses.hunger_station_price AS food_menu_hunger_station_price',
            'food_menuses.jahiz_price AS food_menu_jahiz_price',
            'food_menuses.photo AS food_menu_photo',
            'deal.id AS deal_id',
            'deal.code AS deal_code',
            'deal.name AS deal_name',
            'deal.name_arabic AS deal_name_arabic',
            'deal.sale_price AS deal_sale_price',
            'deal.is_discount AS deal_is_discount',
            'deal.discount_percentage AS deal_discount_percentage',
            'deal.hunger_station_price AS deal_hunger_station_price',
            'deal.jahiz_price AS deal_jahiz_price',
            'deal.photo AS deal_photo',
            'vats.name AS vat_name',
            'vats.percentage AS vats_percentage'
        )
        ->leftJoin('food_menuses', 'carts.item_id', '=', 'food_menuses.id')
        ->leftJoin('deal', 'carts.deal_id', '=', 'deal.id')
        ->leftJoin('vats', function ($join) {
            $join->on('vats.id', '=', \DB::raw('COALESCE(food_menuses.vat_id, deal.vat_id)'));
        })
        ->where('carts.user_id', '=', $request->user_id)
        ->where('carts.outlet_id', '=', $request->outlet_id)
        ->where(function ($query) {
            $query->whereNotNull('carts.item_id')
                ->orWhereNotNull('carts.deal_id');
        })
        ->get();

    // Initialize variables for calculations
    $totalPrice = $totalDiscount = $totalDiscountedPrice = $totalVat = 0;

    // Perform calculations for each cart item
    foreach ($results as $item) {
        if ($item->deal_id !== null) {
            // Calculations when a deal is present
            $item->photo = Storage::url($item->deal_photo);
            $itemPrice = $item->deal_sale_price * $item->quantity;
            $totalPrice += $itemPrice;
            if ($item->deal_is_discount == "yes") {
                $itemDiscount = ($item->deal_discount_percentage / 100) * $itemPrice;
                $totalDiscount += $itemDiscount;
            }
        } elseif ($item->food_menu_id !== null) {
            // Calculations when a food menu item is present
            $item->photp = Storage::url($item->food_menu_photo);
            $itemPrice = $item->sale_price * $item->quantity;
            $totalPrice += $itemPrice;
            if ($item->is_discount == "yes") {
                $itemDiscount = ($item->food_menu_discount_amount / 100) * $itemPrice;
                $totalDiscount += $itemDiscount;
            }
        }

        // Calculate VAT for each item
        $totalVat += ($itemPrice * $item->vats_percentage) / 100;
    }

    // Calculate total discounted price
    $totalDiscountedPrice = $totalPrice - $totalDiscount;

    // Calculate total final price
    $totalFinalPrice = $totalDiscountedPrice + $totalVat;

    // Prepare the response
    $response = [
        'cart_items' => $results,
        'cart_details' => [
            'total_price' => $totalPrice,
            'total_discount' => $totalDiscount,
            'total_discounted_price' => $totalDiscountedPrice,
            'total_final_price' => $totalFinalPrice,
            'total_vat' => $totalVat,
        ],
    ];

    return response()->json($response);
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
            'user_id' => 'required|exists:customers,id',
            'outlet_id' => 'required|exists:outlets,id',
            'item_id' => 'nullable|exists:food_menuses,id',
            'deal_id' => 'nullable|exists:deal,id',
            'quantity' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->cart->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $cart = $this->cart->find($id);

        if ($cart) {
            $cart->update($request->all());
            return response()->json(['message' => 'Cart update successfully'], 200);
        } else {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cart = $this->cart->find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->delete();
        return response()->json(['message' => 'Cart deleted successfully'], 200);
    }
}
