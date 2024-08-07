<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItemModifier;
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
    protected $itemModifiers;
    public function __construct()
    {
        $this->cart = new Cart();
        $this->itemModifiers = new CartItemModifier();
    }
    
    public function index(Request $request)
    {
        if (!($request->session_id || $request->outlet_id)) {
            return $this->sendError('Incomplete Request');
        }

        $carts = Cart::with([
            'foodMenu' => function ($query) {
                $query->with(['vat']);
            },
            'deal' => function ($query) {
                $query->with(['vat']);
            },
            'cartItemModifiers.modifier'
        ])
        ->where('session_id', $request->session_id)
        ->where('outlet_id', $request->outlet_id)
        ->where(function ($query) {
            $query->whereNotNull('item_id')
                  ->orWhereNotNull('deal_id');
        })
        ->get();

        // Initialize variables for calculations
        $totalPrice = $totalDiscount = $totalDiscountedPrice = $totalVat = 0;

        // Perform calculations for each cart item
        foreach ($carts as $cart) {
            $itemPrice = 0;
            $vatPercentage = 0;

            if ($cart->deal_id !== null) {
                // Calculations when a deal is present
                $cart->photo = url(Storage::url($cart->deal->photo));
                $itemPrice = $cart->deal->sale_price * $cart->quantity;
                $vatPercentage = $cart->deal->vat->percentage;
                $totalPrice += $itemPrice;
                if ($cart->deal->is_discount == "yes") {
                    $itemDiscount = ($cart->deal->discount_percentage / 100) * $itemPrice;
                    $totalDiscount += $itemDiscount;
                }
            } elseif ($cart->item_id !== null) {
                // Calculations when a food menu item is present
                $cart->photo = url(Storage::url($cart->foodMenu->photo));
                $itemPrice = $cart->foodMenu->sale_price * $cart->quantity;
                $vatPercentage = $cart->foodMenu->vat->percentage;
                $totalPrice += $itemPrice;
                if ($cart->foodMenu->is_discount == "yes") {
                    $itemDiscount = ($cart->foodMenu->discount_amount / 100) * $itemPrice;
                    $totalDiscount += $itemDiscount;
                }
            }

            // Calculate VAT for each item
            $totalVat += ($itemPrice * $vatPercentage) / 100;

            // Add the price of modifiers to the total price and VAT calculations
            foreach ($cart->cartItemModifiers as $modifier) {
                $modifierPrice = $modifier->modifier->price * $modifier->quantity;
                $totalPrice += $modifierPrice;
                if ($modifier->modifier->tax_method == "percentage") {
                    $totalVat += ($modifierPrice * $modifier->modifier->tax) / 100;
                } else {
                    $totalVat += $modifier->modifier->tax;
                }
            }
        }

        // Calculate total discounted price
        $totalDiscountedPrice = $totalPrice - $totalDiscount;

        // Calculate total final price
        $totalFinalPrice = $totalDiscountedPrice + $totalVat;

        // Prepare the response
        $response = [
            'cart_items' => $carts,
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
            'user_id' => 'nullable|exists:customers,id',
            'session_id' => 'nullable|string|max:255',
            'outlet_id' => 'required|exists:outlets,id',
            'item_id' => 'nullable|exists:food_menuses,id',
            'item_modifiers' => 'nullable|array|required_if:item_id,!=,null',
            'item_modifiers.*.modifier_id' => 'exists:modifiers,id',
            'deal_id' => 'nullable|exists:deal,id',
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // print_r($request->item_modifiers);
        // exit();
        $cart = $this->cart->create($request->all());

        if (is_array($request->item_modifiers) && count($request->item_modifiers) > 0) {
            foreach ($request->item_modifiers as $modifier) {
                $cart_item_modifier['modifier_id'] = $modifier['modifier_id'];
                $cart_item_modifier['cart_id'] = $cart->id;
                $cart_item_modifier['quantity'] = $modifier['quantity'] ?? 1;

                $this->itemModifiers->create($cart_item_modifier);
            }
        }

        $carts = Cart::with([
            'foodMenu' => function ($query) {
                $query->with(['vat']);
            },
            'deal' => function ($query) {
                $query->with(['vat']);
            },
            'cartItemModifiers.modifier'
        ])
        ->where('session_id', $request->session_id)
        ->where('outlet_id', $request->outlet_id)
        ->where(function ($query) {
            $query->whereNotNull('item_id')
                  ->orWhereNotNull('deal_id');
        })
        ->get();

        // Initialize variables for calculations
        $totalPrice = $totalDiscount = $totalDiscountedPrice = $totalVat = 0;

        // Perform calculations for each cart item
        foreach ($carts as $cart) {
            $itemPrice = 0;
            $vatPercentage = 0;

            if ($cart->deal_id !== null) {
                // Calculations when a deal is present
                $cart->photo = url(Storage::url($cart->deal->photo));
                $itemPrice = $cart->deal->sale_price * $cart->quantity;
                $vatPercentage = $cart->deal->vat->percentage;
                $totalPrice += $itemPrice;
                if ($cart->deal->is_discount == "yes") {
                    $itemDiscount = ($cart->deal->discount_percentage / 100) * $itemPrice;
                    $totalDiscount += $itemDiscount;
                }
            } elseif ($cart->item_id !== null) {
                // Calculations when a food menu item is present
                $cart->photo = url(Storage::url($cart->foodMenu->photo));
                $itemPrice = $cart->foodMenu->sale_price * $cart->quantity;
                $vatPercentage = $cart->foodMenu->vat->percentage;
                $totalPrice += $itemPrice;
                if ($cart->foodMenu->is_discount == "yes") {
                    $itemDiscount = ($cart->foodMenu->discount_amount / 100) * $itemPrice;
                    $totalDiscount += $itemDiscount;
                }
            }

            // Calculate VAT for each item
            $totalVat += ($itemPrice * $vatPercentage) / 100;

            // Add the price of modifiers to the total price and VAT calculations
            foreach ($cart->cartItemModifiers as $modifier) {
                $modifierPrice = $modifier->modifier->price * $modifier->quantity;
                $totalPrice += $modifierPrice;
                if ($modifier->modifier->tax_method == "percentage") {
                    $totalVat += ($modifierPrice * $modifier->modifier->tax) / 100;
                } else {
                    $totalVat += $modifier->modifier->tax;
                }
            }
        }

        // Calculate total discounted price
        $totalDiscountedPrice = $totalPrice - $totalDiscount;

        // Calculate total final price
        $totalFinalPrice = $totalDiscountedPrice + $totalVat;

        // Prepare the response
        $response = [
            'cart_items' => $carts,
            'cart_details' => [
                'total_price' => $totalPrice,
                'total_discount' => $totalDiscount,
                'total_discounted_price' => $totalDiscountedPrice,
                'total_final_price' => $totalFinalPrice,
                'total_vat' => $totalVat,
            ],
        ];

        return $response;
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

        // Find and delete cart item modifiers associated with the cart item
        $cartItemModifiers = $this->itemModifiers->where('cart_id', $cart->id)->get();
        foreach ($cartItemModifiers as $modifier) {
            $modifier->delete();
        }

        // Delete the cart item
        $cart->delete();

        return response()->json(['message' => 'Cart and associated modifiers deleted successfully'], 200);
    }

}
