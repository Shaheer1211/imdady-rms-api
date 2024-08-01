<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Coupon;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Modifiers;
use App\Models\OrderModifierDetails;
use App\Models\FoodMenus;
use App\Models\Loyalty;
use App\Models\Vats;
use App\Models\MultiplePayment;
use App\Http\Controllers\Services\InvoiceToken;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Customer;
use Validator;

class MainScreenPOS extends BaseController
{
    public function fetchAllOrder()
{
    $order = Orders::where('del_status','Live')->get();

    return response()->json($order);
}

    public function fetchOrderWithDetails($orderId)
{
    $order = Orders::with([
        'customer',
        'payment',
        'orderDetails.foodMenu.category',
        'orderDetails.foodMenu.subCategory',
        'orderDetails.foodMenu.vat',
        'orderDetails.modifiers'
    ])->find($orderId);

    if ($order) {
        $multiplePayments = $order->multiple_payments;
        $processedPayments = [];

        if (is_array($multiplePayments)) {
            foreach ($multiplePayments as $payment) {
                $paymentRecord = MultiplePayment::find($payment['id']);
                if ($paymentRecord) {
                    $processedPayments[] = [
                        'company_name' => $paymentRecord->company_name,
                        'amount' => $payment['amount']
                    ];
                }
            }
        }

        $order->multiple_payments = $processedPayments;
    }

    return response()->json($order);
}

    public function add_sale(Request $request)
{
    $validator = $this->validateSaleRequest($request);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    // $validatedData = $validator->validated();
    $validatedData = $request->all();

    $discount = 0;
    $paidAmount = $request->input('paid_amount');
    if ($request->input('is_coupon') === 'yes') {
        $discount = $this->applyCoupon($request, $paidAmount);
        if (is_array($discount)) {
            return response()->json($discount, $discount['code']);
        }
    }

    $finalAmount = $paidAmount - $discount;
    $loyalty_point_amount = $this->applyLoyaltyPoints($validatedData, $finalAmount);
    

    $orderData = $this->prepareOrderData($validatedData, $discount, $finalAmount, $loyalty_point_amount);
    $order = Orders::create($orderData);
    $this->processOrderItems($order->id, $validatedData['items'], $validatedData);

    // return response()->json(['status' => 'Order created successfully']);
    return response()->json(['message' => 'Sale added successfully.', 'order_id' => $order->id], 200);
}

private function validateSaleRequest($request)
{
     return Validator::make($request->all(), [
        'customer_id' => 'required|integer|exists:customers,id',
         'is_coupon' => 'required|in:yes,no',
        'coupon_id' => [
            'nullable',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->input('is_coupon') === 'yes' && !$value) {
                    $fail('The coupon ID is required when a coupon is used.');
                } elseif ($request->input('is_coupon') === 'yes' && !Coupon::where('id', $value)->exists()) {
                    $fail('The selected coupon does not exist.');
                }
            },
        ],
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
        'order_type_id' => 'required|integer|exists:ordertypes,id',
        'sub_total' => 'required|numeric|min:0',
        'paid_amount' => 'required|numeric|min:0|max:' . $request->input('sub_total'),
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:food_menuses,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.modifiers' => 'array',
        'items.*.modifiers.*.modifier_id' => 'integer|exists:modifiers,id',
        'items.*.modifiers.*.quantity' => 'integer|min:1',
        'outlet_id' => 'required|integer|exists:outlets,id',
        'multiple_payments' => 'array',
        'multiple_payments.*.id' => 'integer|exists:multiple_payments,id',
        'multiple_payments.*.amount' => 'numeric|min:0',
    ], [
        'customer_id.required' => 'The customer ID is required.',
        'customer_id.exists' => 'The selected customer does not exist.',
        'is_coupon.required' => 'The coupon status is required.',
        'is_coupon.in' => 'The coupon status must be either yes or no.',
        'coupon_id.required_if' => 'The coupon ID is required when using a coupon.',
        'coupon_id.exists' => 'The selected coupon does not exist.',
        'order_type_id.required' => 'The order type ID is required.',
        'order_type_id.exists' => 'The selected order type does not exist.',
        'sub_total.required' => 'The sub total is required.',
        'sub_total.numeric' => 'The sub total must be a number.',
        'sub_total.min' => 'The sub total must be at least 0.',
        'paid_amount.required' => 'The paid amount is required.',
        'paid_amount.numeric' => 'The paid amount must be a number.',
        'paid_amount.min' => 'The paid amount must be at least 0.',
        'paid_amount.max' => 'The paid amount cannot be greater than the sub total.',
        'items.required' => 'The items field is required.',
        'items.array' => 'The items field must be an array.',
        'items.*.item_id.required' => 'Each item must have an item ID.',
        'items.*.item_id.exists' => 'The selected item does not exist.',
        'items.*.quantity.required' => 'Each item must have a quantity.',
        'items.*.quantity.integer' => 'Each item quantity must be an integer.',
        'items.*.modifiers.*.quantity.min' => 'Each item quantity must be at least 1.',
        'items.*.modifiers.array' => 'The modifiers field must be an array.',
        'items.*.modifiers.*.modifier_id.integer' => 'Each modifier ID must be an integer.',
        'items.*.modifiers.*.modifier_id.exists' => 'The selected modifier does not exist.',
        'items.*.modifiers.*.quantity.integer' => 'Each modifier quantity must be an integer.',
        'items.*.modifiers.*.quantity.min' => 'Each modifier quantity must be at least 1.',
        'outlet_id.required' => 'The outlet ID is required.',
        'outlet_id.exists' => 'The selected outlet does not exist.',
    ]);
}

private function applyCoupon($request, $paidAmount)
{
    $coupon = Coupon::where('id', $request->input('coupon_id'))
        ->where('del_status', 'Live')
        ->first();

    if (!$coupon) {
        return ['message' => 'Invalid coupon.', 'code' => 404];
    }

    if (!$coupon->status) {
        return ['message' => 'This coupon is inactive.', 'code' => 400];
    }

    if (Carbon::parse($coupon->expired_date)->isPast()) {
        return ['message' => 'This coupon has expired.', 'code' => 400];
    }

    if ($paidAmount < $coupon->minimum_purchase_price) {
        return ['message' => 'Paid amount does not meet the minimum purchase requirement.', 'code' => 400];
    }

    if ($coupon->dis_type === 'percentage') {
        $discount = ($paidAmount * $coupon->discount_amount) / 100;
    } elseif ($coupon->dis_type === 'amount') {
        $discount = $coupon->discount_amount;
    }

    return min($discount, $paidAmount);
}

private function applyLoyaltyPoints($validatedData, $finalAmount)
{
    $loyalty = Loyalty::where('status', true)->first();
    $currencyValue = 0;
    if ($loyalty) {
        $c_id = $validatedData['customer_id'];
        $total_pay = $finalAmount ?? 0;
        $customerPoint = 0;
        $usePoints = $validatedData['redeem_point'] ?? 0;
        if ($c_id != 1) {
        $customer = Customer::find($c_id);

        if ($customer) {
            if ($usePoints > $customer->loyalty_points) {
                 return ['message' => 'Redeemed points exceed available loyalty points.', 'code' => 400];
            }
            $points = $total_pay / 100 * $loyalty->percentage_order_amount;

            if ($points >= $loyalty->minimum_point) {
                $customerPoint = $points;
            }
            $c_point = $customer->loyalty_points + $customerPoint - $usePoints;

            $convertPoints = $loyalty->convert_points;
            $perPrice = $loyalty->per_price;
            $currencyValue = ($usePoints / $convertPoints) * $perPrice;

            $customer->loyalty_points = $c_point;
            $customer->save();

            return $currencyValue;
        }
      }
    }
}


private function prepareOrderData($validatedData, $discount, $finalAmount, $loyalty_point_amount)
{
    $invoiceTokenService = new InvoiceToken();
    $invoiceToken = $invoiceTokenService->generateTokenNo();
    $user = Auth::user();
    if ($validatedData['is_coupon'] === 'no') {
        $validatedData['coupon_id'] = null;
    }
    return [
        'customer_id' => $validatedData['customer_id'],
        'is_coupon' => $validatedData['is_coupon'] ?? 'no',
        'coupon_id' => $validatedData['coupon_id'] ?? null,
        'coupon_discount_amount' => $discount ?? 0,
        'cashier_id' => $validatedData['cashier_id'] ?? null,
        'sale_no' => $invoiceTokenService->generateSaleNo($validatedData['outlet_id']) ?? null,
        'token_no' => $invoiceToken ?? null,
        'total_items' => count($validatedData['items']),
        'sub_total' => $validatedData['sub_total'],
        'paid_amount' => $finalAmount,
        'due_amount' => $validatedData['sub_total'] -  $finalAmount,
        'vat_amount' => $validatedData['vat_amount'] ?? 0,
        'qrcode' => $validatedData['qrcode'] ?? null,
        'total_payable' => $validatedData['total_payable'] ?? 0,
        'loyalty_point_amount' => $loyalty_point_amount ?? 0,
        'close_time' => $validatedData['close_time'] ?? null,
        'table_id' => $validatedData['table_id'] ?? null,
        'total_item_discount_amount' => $validatedData['total_item_discount_amount'] ?? 0,
        'total_discount_amount' => $validatedData['total_discount_amount'] ?? 0,
        'sub_total_with_discount' => $validatedData['sub_total_with_discount'] ?? 0,
        'delivery_charges' => $validatedData['delivery_charges'] ?? 0,
        'sale_date' => Carbon::now()->format('Y-m-d'),
        'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
        'order_time' => Carbon::now()->format('H:i:s'),
        'cooking_start_time' => $validatedData['cooking_start_time'] ?? null,
        'cooking_end_time' => $validatedData['cooking_end_time'] ?? null,
        'user_id' => $user->id ?? 1,
        'waiter_id' => $validatedData['waiter_id'] ?? null,
        'outlet_id' => $validatedData['outlet_id'],
        'order_status' => $validatedData['order_status'] ?? 'new',
        'order_type_id' => $validatedData['order_type_id'] ?? null,
        'order_from' => $validatedData['order_from'] ?? null,
        'main_screen_discount_type' => $validatedData['main_screen_discount_type'] ?? null,
        'main_screen_discount' => $validatedData['main_screen_discount'] ?? 0,
        'payment_method_id' => $validatedData['payment_method_id'],
        'multiple_payments' => $validatedData['multiple_payments'],
        'order_menu_taxes' => $validatedData['order_menu_taxes'] ?? [],
        'card_discount_type' => $validatedData['card_discount_type'] ?? null,
        'card_discount' => $validatedData['card_discount'] ?? 0,
    ];
}

private function processOrderItems($orderId, $items, $validatedData)
{
    foreach ($items as $itemData) {
        $foodMenu = $this->fetchFoodMenu($itemData['item_id']);
        $productDiscount = $this->fetchProductDiscount($foodMenu, $validatedData);
        $vats = Vats::find($foodMenu->vat_id);
        $discountDetails = $this->calculateDiscount($foodMenu, $productDiscount);

        $item = new OrderDetails();
        $item->order_id = $orderId;
        $item->qty = $itemData['quantity'];
        $item->food_menu_id = $foodMenu->id;
        $item->name = $foodMenu->name;
        $item->name_arabic = $foodMenu->name_arabic;
        $item->is_tax_fix = $foodMenu->is_tax_fix;
        $item->menu_unit_price = $foodMenu->sale_price;
        $item->menu_price_with_discount = $foodMenu->sale_price - $discountDetails['SingleDiscountAmount'];


        $vatPercentage = $vats->percentage;
        $normalTaxAmount = $foodMenu->sale_price * ($vatPercentage / 100);
        $taxFixPercentage = 0;
        $additionalTaxAmount =0;

        if ($foodMenu->is_tax_fix === 'yes') {
            $taxFixPercentage = 100;
            $additionalTaxAmount = $foodMenu->sale_price * ($taxFixPercentage / 100);    
        }

        $totalTaxAmount = $normalTaxAmount + $additionalTaxAmount;
        $item->menu_unit_price_with_vat = $foodMenu->sale_price + $totalTaxAmount;

        $item->menu_taxes = [
                'additional_vat_percentage' => $taxFixPercentage,
                'additional_vat' => $additionalTaxAmount,
                'vat_percentage' => $vatPercentage,
                'vat' => $normalTaxAmount,
                'total_qty_vat' => $totalTaxAmount * $itemData['quantity'],
                'single_discount' => $discountDetails['SingleDiscountAmount'] ?? 0,
                'total_qty_discount' => $discountDetails['SingleDiscountAmount'] * $itemData['quantity'],
                'item_price' => $foodMenu->sale_price,
                'total_qty_items_price' => $foodMenu->sale_price * $itemData['quantity'],
                'total_price' => ($item->menu_unit_price_with_vat * $itemData['quantity'] - ($discountDetails['SingleDiscountAmount'] * $itemData['quantity']))
            ];

        $item->discount_type = $discountDetails['discount_type'] ?? 'none';
        $item->menu_note = $itemData['menu_note'] ?? '';
        $item->item_type = $itemData['item_type'] ?? '';
        $item->cooking_status = 'started';
        $item->cooking_start_time = Carbon::now()->format('Y-m-d H:i:s');
        $item->cooking_end_time = Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s');
        $item->save();

            if (isset($itemData['modifiers']) && is_array($itemData['modifiers'])) {
            $orderDetailModifier = $itemData['modifiers'];

            if ($orderDetailModifier && (count($orderDetailModifier) > 0)) {
                foreach ($orderDetailModifier as $modifier) {
                    $modifierData = Modifiers::find($modifier['modifier_id']);
                    $orderModifierDetailData['order_id'] = $orderId;
                    $orderModifierDetailData['order_details_id'] = $item->id;
                    $orderModifierDetailData['modifier_id'] = $modifier['modifier_id'];
                    $orderModifierDetailData['qty'] = $modifier['quantity'];
                    $orderModifierDetailData['sell_price'] = $modifierData->price - ($modifierData->price * ($vatPercentage / 100));
                    $orderModifierDetailData['vat'] = $modifierData->price * ($vatPercentage / 100);

                    OrderModifierDetails::create($orderModifierDetailData);
                }
            }}

    }
}

private function fetchFoodMenu($itemId)
{
    return FoodMenus::find($itemId);
}

private function fetchProductDiscount($foodMenu, $validatedData)
{

    $currentDate = Carbon::now();
    $get_discount = Discount::where('product_id', $foodMenu->id)
        ->orWhere('category_id', $foodMenu->category_id)
        ->where(function ($query) use ($currentDate) {
            $query->where('start_date', '<=', $currentDate)
                  ->where('end_date', '>=', $currentDate);
        })
        ->first();
    
    if ($get_discount) {
        if ($get_discount->specific_customers === 'yes') {
            $multiCustomerIds = $get_discount->multi_customer_id;

            if (is_array($multiCustomerIds) && in_array($validatedData['customer_id'], $multiCustomerIds)) {
                return $get_discount;
            } else {
                return null; // Customer not eligible for discount
            }
        } else{
            return $get_discount;
        }
    }

    return null; // No applicable discount found
}


private function calculateDiscount($foodMenu, $productDiscount)
{
    $SingleDiscountAmount = 0;
    $discount_type = '';

    if ($productDiscount) {
        $discount_type = $productDiscount->dis_type;
        if ($productDiscount->dis_type == "percentage") {
            $SingleDiscountAmount = ($foodMenu->sale_price * $productDiscount->discount_amount) / 100;
        } else {
            $SingleDiscountAmount = $productDiscount->discount_amount;
        }
    }

    return [
        'SingleDiscountAmount' => $SingleDiscountAmount,
        'discount_type' => $discount_type
    ];
}


    // public function add_sale(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //     'customer_id' => 'required|integer|exists:customers,id',
    //     'is_coupon' => 'required|in:yes,no',
    //     'coupon_id' => 'required_if:is_coupon,yes|exists:coupons,id',
    //     'order_type_id' =>'required|integer|exists:ordertypes,id',
    //     'sub_total' => 'required|numeric|min:0',
    //     'paid_amount' => 'required|numeric|min:0|max:' . $request->input('sub_total'),
    //     'items' => 'required|array',
    //     'items.*.item_id' => 'required|exists:food_menuses,id',
    //     'items.*.quantity' => 'required|integer|min:1',
    //     'items.*.modifiers' => 'array',
    //     'items.*.modifiers.*.modifier_id' => 'integer|exists:modifiers,id',
    //     'items.*.modifiers.*.quantity' => 'integer|min:1',
    // ], [
    //     'customer_id.required' => 'The customer ID is required.',
    //     'customer_id.exists' => 'The selected customer does not exist.',
    //     'sub_total.required' => 'The sub total is required.',
    //     'sub_total.numeric' => 'The sub total must be a number.',
    //     'sub_total.min' => 'The sub total must be at least 0.',
    //     'paid_amount.required' => 'The paid amount is required.',
    //     'paid_amount.numeric' => 'The paid amount must be a number.',
    //     'paid_amount.min' => 'The paid amount must be at least 0.',
    //     'paid_amount.max' => 'The paid amount cannot be greater than the sub total.',
    //     'items.required' => 'The items field is required.',
    //     'items.array' => 'The items field must be an array.',
    //     'items.*.item_id.required' => 'Each item must have an item ID.',
    //     'items.*.item_id.exists' => 'The selected item does not exist.',
    //     'items.*.quantity.required' => 'Each item must have a quantity.',
    //     'items.*.quantity.integer' => 'Each item quantity must be an integer.',
    //     'items.*.modifiers.*.quantity.min' => 'Each item quantity must be at least 1.',
    //     'items.*.modifiers.array' => 'The modifiers field must be an array.',
    //     'items.*.modifiers.*.modifier_id.integer' => 'Each modifier ID must be an integer.',
    //     'items.*.modifiers.*.modifier_id.exists' => 'The selected modifier does not exist.',
    //     'items.*.modifiers.*.quantity.integer' => 'Each modifier quantity must be an integer.',
    //     'items.*.modifiers.*.quantity.min' => 'Each modifier quantity must be at least 1.',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json(['errors' => $validator->errors()], 422);
    // }
    // $validatedData = $validator->validated();

    // $paidAmount = $request->input('paid_amount');
    // $discount = 0;

    // if ($request->input('is_coupon') === 'yes') {
    //     $coupon = Coupon::where('id', $request->input('coupon_id'))
    //         ->where('del_status', 'Live')
    //         ->first();

    //     if (!$coupon) {
    //         return response()->json(['message' => 'Invalid coupon.'], 404);
    //     }

    //     if (!$coupon->status) {
    //         return response()->json(['message' => 'This coupon is inactive.'], 400);
    //     }

    //     if (Carbon::parse($coupon->expired_date)->isPast()) {
    //         return response()->json(['message' => 'This coupon has expired.'], 400);
    //     }

    //     if ($paidAmount < $coupon->minimum_purchase_price) {
    //         return response()->json(['message' => 'Paid amount does not meet the minimum purchase requirement.'], 400);
    //     }

    //     if ($coupon->dis_type === 'percentage') {
    //         $discount = ($paidAmount * $coupon->discount_amount) / 100;
    //     } elseif ($coupon->dis_type === 'amount') {
    //         $discount = $coupon->discount_amount;
    //     }

    //     $discount = min($discount, $paidAmount);
    // }

    // $finalAmount = $paidAmount - $discount;

    // // Fetch the active loyalty record
    //     $loyalty = Loyalty::where('status', true)->first();

    //     if ($loyalty) {
    //         $c_id = $validatedData['customer_id'];
    //         $total_pay = $finalAmount ?? 0;
    //         $customerPoint = 0;
    //         $usePoints = $validatedData['redeem_point'] ?? 0;
            
    //         $points = $total_pay / 100 * $loyalty->percentage_order_amount;
            
    //         if ($points >= $loyalty->minimum_point) {
    //             $customerPoint = $points;
    //         }
            
    //         if ($c_id != 1) {
    //             $customer = Customer::find($c_id);
    //             if ($customer) {
                   
    //                 $c_point = $customer->loyalty_points + $customerPoint - $usePoints;
                    
    //                 $customer->loyalty_points = $c_point;
    //                 $customer->save();
    //             }
    //         }
    //     }



    // $invoiceTokenService = new InvoiceToken();
    // $invoiceToken = $invoiceTokenService->generateTokenNo();
    // $user = Auth::user();
   
    // $user_id = $user->id;
    // $outlet_id = $user->outlet_id;

    // $orderData = [
    //     'customer_id' => $validatedData['customer_id'],
    //     'is_coupon' => $validatedData['is_coupon'] ?? false,
    //     'coupon_id' => $validatedData['coupon_id'] ?? null,
    //     'coupon_discount_amount' => $discount ?? 0,
    //     'cashier_id' => $validatedData['cashier_id'] ?? null,
    //     'sale_no' => $invoiceTokenService->generateSaleNo($outlet_id) ?? null,
    //     'token_no' => $invoiceToken ?? null,
    //     'total_items' => $validatedData['total_items'] ?? null,
    //     'sub_total' => $validatedData['sub_total'],
    //     'paid_amount' => $finalAmount,
    //     'due_amount' => $validatedData['due_amount'] ?? 0,
    //     'discount' => $validatedData['discount'] ?? 0,
    //     'vat_amount' => $validatedData['vat_amount'] ?? 0,
    //     'qrcode' => $validatedData['qrcode'] ?? null,
    //     'total_payable' => $validatedData['total_payable'] ?? 0,
    //     'loyalty_point_amount' => $validatedData['loyalty_point_amount'] ?? 0,
    //     'close_time' => $validatedData['close_time'] ?? null,
    //     'table_id' => $validatedData['table_id'] ?? null,
    //     'total_item_discount_amount' => $validatedData['total_item_discount_amount'] ?? 0,
    //     'total_discount_amount' => $validatedData['total_discount_amount'] ?? 0,
    //     'sub_total_with_discount' => $validatedData['sub_total_with_discount'] ?? 0,
    //     'delivery_charges' => $validatedData['delivery_charges'] ?? 0,
    //     'sale_date' => Carbon::now()->format('Y-m-d'),
    //     'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
    //     'order_time' => Carbon::now()->format('H:i:s'),
    //     'cooking_start_time' => $validatedData['cooking_start_time'] ?? null,
    //     'cooking_end_time' => $validatedData['cooking_end_time'] ?? null,
    //     'modified' => $validatedData['modified'] ?? 'yes',
    //     'modified_vat' => $validatedData['modified_vat'] ?? false,
    //     'user_id' => $user_id,
    //     'waiter_id' => $validatedData['waiter_id'] ?? null,
    //     'outlet_id' => $outlet_id,
    //     'order_status' => $validatedData['order_status'] ?? 'new',
    //     'order_type_id' => $validatedData['order_type_id'] ?? null,
    //     'order_from' => $validatedData['order_from'] ?? null,
    // ];

    // $order = Orders::create($orderData);
    // $lastOrderId = $order->id;
    // $SingleDiscountAmount = 0;
    // $discount_type = '';
    // $sda = 0;

    //  foreach ($validatedData['items'] as $itemData) {
    //     // Fetch data from food_menuses table
    //     $foodMenu = FoodMenus::find($itemData['item_id']);
    //     // $productDiscount = Discount::where('product_id', $foodMenu->id)
    //     //     ->orWhere('category_id', $foodMenu->category_id)
    //     //     ->first();
    //     $currentDate = Carbon::now();
    //     $productDiscount = Discount::where('product_id', $foodMenu->id)
    //         ->orWhere('category_id', $foodMenu->category_id)
    //         ->where(function ($query) use ($currentDate) {
    //             $query->where('start_date', '<=', $currentDate)
    //                   ->where('end_date', '>=', $currentDate);
    //         })
    //         ->first();

    //         if($productDiscount){
    //                 $discount_type = $productDiscount->dis_type;
    //             if($productDiscount->dis_type == "percantage"){
    //                    $SingleDiscountAmount = $foodMenu->price / $productDiscount->discount_amount;
    //                    $sda = $foodMenu->price - $SingleDiscountAmount;
    //             }else{
    //                     $SingleDiscountAmount = $productDiscount->discount_amount;
    //                     $sda = $foodMenu->price - $SingleDiscountAmount;
    //             }
    //         }



    //     $item = new OrderDetails();
    //     $item->order_id = $lastOrderId;
    //     $item->qty = $itemData['quantity'];
    //     $item->food_menu_id = $foodMenu->id;
    //     $item->single_discount = $SingleDiscountAmount ?? 0;
    //     $item->menu_unit_price = $foodMenu->sale_price;
    //     $item->menu_price_with_discount = $foodMenu->sale_price - ($SingleDiscountAmount?? 0);
    //     $item->menu_unit_price_with_vat = $foodMenu->sale_price + ($foodMenu->sale_price * ($foodMenu->vat_percentage / 100));
    //     $item->menu_vat_percentage = 15;
    //     $item->menu_taxes = $foodMenu->taxes;
    //     $item->menu_discount_value = $SingleDiscountAmount * $itemData['quantity'];
    //     $item->discount_type = $discount_type ?? 'none';
    //     $item->discount_amount = $sda ?? 0;
    //     $item->menu_note = $itemData['menu_note'] ?? '';
    //     $item->item_type = $itemData['item_type'] ?? '';
    //     $item->cooking_status = $itemData['cooking_status'] ?? 'not started';
    //     $item->cooking_start_time = $itemData['cooking_start_time'] ?? null;
    //     $item->cooking_end_time = $itemData['cooking_end_time'] ?? null;
    //     $item->save();

        
    //     }
    
    //      return response()->json(['status' => 'Create order successfully']);

    // }


}
