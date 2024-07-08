<?php

namespace App\Http\Controllers;

use App\Models\Modifiers;
use App\Models\OrderConsumptionMenus;
use App\Models\OrderConsumptionModifiers;
use App\Models\Orders;
use App\Models\couponss;
use App\Models\OrderDetails;
use App\Models\OrderModifierDetails;
use App\Models\FoodMenus;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Validator;
use App\Http\Controllers\Services\InvoiceToken;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $orders;
    protected $coupons;
    protected $orderDetails;
    protected $orderModifierDetails;
    protected $foodMenu;
    protected $modifiers;
    protected $orderMenuConsumption;
    protected $orderModifierConsumption;
    public function __construct()
    {
        $this->orders = new Orders();
        $this->coupons = new couponss();
        $this->orderDetails = new OrderDetails();
        $this->orderModifierDetails = new OrderModifierDetails();
        $this->foodMenu = new FoodMenus();
        $this->modifiers = new Modifiers();
        $this->orderMenuConsumption = new OrderConsumptionMenus();
        $this->orderModifierConsumption = new OrderConsumptionModifiers();
    }
    public function index()
    {
        $data = [];

        $orders = DB::table('orders')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('ordertypes', 'ordertypes.id', '=', 'orders.order_type_id')
            ->leftJoin('outlets', 'outlets.id', '=', 'orders.outlet_id')
            ->leftJoin('users as users_waiter', 'users_waiter.id', '=', 'orders.waiter_id')
            ->leftJoin('users as users_cashier', 'users_cashier.id', '=', 'orders.cashier_id')
            ->select([
                'orders.id as orders_id',
                'orders.customer_id as orders_customer_id',
                'orders.is_coupon as orders_is_coupon',
                'orders.coupon_id as orders_coupon_id',
                'orders.coupon_discount_amount as orders_coupon_discount_amount',
                'orders.cashier_id as orders_cashier_id',
                'orders.sale_no as orders_sale_no',
                'orders.token_no as orders_token_no',
                'orders.total_items as orders_total_items',
                'orders.sub_total as orders_sub_total',
                'orders.paid_amount as orders_paid_amount',
                'orders.due_amount as orders_due_amount',
                'orders.discount as orders_discount',
                'orders.vat_amount as orders_vat_amount',
                'orders.qrcode as orders_qrcode',
                'orders.total_payable as orders_total_payable',
                'orders.loyalty_point_amount as orders_loyalty_point_amount',
                'orders.close_time as orders_close_time',
                'orders.table_id as orders_table_id',
                'orders.total_item_discount_amount as orders_total_item_discount_amount',
                'orders.total_discount_amount as orders_total_discount_amount',
                'orders.sub_total_with_discount as orders_sub_total_with_discount',
                'orders.delivery_charges as orders_delivery_charges',
                'orders.sale_date as orders_sale_date',
                'orders.date_time as orders_date_time',
                'orders.order_time as orders_order_time',
                'orders.cooking_start_time as orders_cooking_start_time',
                'orders.cooking_end_time as orders_cooking_end_time',
                'orders.modified as orders_modified',
                'orders.modified_vat as orders_modified_vat',
                'orders.user_id as orders_user_id',
                'orders.waiter_id as orders_waiter_id',
                'orders.outlet_id as orders_outlet_id',
                'orders.order_status as orders_order_status',
                'orders.order_type_id as orders_order_type_id',
                'orders.order_from as orders_order_from',
                'orders.created_at as orders_created_at',
                'orders.updated_at as orders_updated_at',
                'orders.del_status as orders_del_status',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'customers.alternate_number as customer_alt_num',
                'customers.address as customer_address',
                'customers.customer_vat as customer_vat',
                'customers.employe_card_no as customer_emp_card_no',
                'ordertypes.name as order_type_name',
                'outlets.name as outlet_name',
                'outlets.phone as outlet_phone',
                'outlets.code as outlet_code',
                'outlets.email as outlet_email',
                'outlets.address as outlet_address',
                'outlets.registration_no as outlet_reg_no',
                'users_waiter.name as waiter_name',
                'users_cashier.name as cashier_name'
            ])
            ->groupBy([
                'orders.id',
                'orders.customer_id',
                'orders.is_coupon',
                'orders.coupon_id',
                'orders.coupon_discount_amount',
                'orders.cashier_id',
                'orders.sale_no',
                'orders.token_no',
                'orders.total_items',
                'orders.sub_total',
                'orders.paid_amount',
                'orders.due_amount',
                'orders.discount',
                'orders.vat_amount',
                'orders.qrcode',
                'orders.total_payable',
                'orders.loyalty_point_amount',
                'orders.close_time',
                'orders.table_id',
                'orders.total_item_discount_amount',
                'orders.total_discount_amount',
                'orders.sub_total_with_discount',
                'orders.delivery_charges',
                'orders.sale_date',
                'orders.date_time',
                'orders.order_time',
                'orders.cooking_start_time',
                'orders.cooking_end_time',
                'orders.modified',
                'orders.modified_vat',
                'orders.user_id',
                'orders.waiter_id',
                'orders.outlet_id',
                'orders.order_status',
                'orders.order_type_id',
                'orders.order_from',
                'orders.created_at',
                'orders.updated_at',
                'orders.del_status',
                'customers.name',
                'customers.email',
                'customers.phone',
                'customers.alternate_number',
                'customers.address',
                'customers.customer_vat',
                'customers.employe_card_no',
                'ordertypes.name',
                'outlets.name',
                'outlets.phone',
                'outlets.code',
                'outlets.email',
                'outlets.address',
                'outlets.registration_no',
                'users_waiter.name',
                'users_cashier.name'
            ])
            ->get();

            $data['orders'] = $orders;

            $orderDetails = $this->orderDetails::all();

            $data['$orderDetails'] = $orderDetails;

            return $data;
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
            'customer_id' => 'integer|exists:customers,id',
            'is_coupon' => 'required|string',
            'order_from' => 'required|string',
            'order_type_id' => 'required|integer|exists:ordertypes,id',
            'table_id' => 'required_if:order_type_id,1|integer|exists:tables,id',
            'user_id' => 'integer|exists:users,id',
            'waiter_id' => 'integer|exists:users,id',
            'outlet_id' => 'required|integer|exists:outlets,id',
            'loyalty_point_amount' => 'numeric',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:food_menuses,id',
            'discount' => 'number',
            'items.*.quantity' => 'required|integer',
            'items.*.modifiers' => 'array',
            'items.*.modifiers.*.modifier_id' => 'integer|exists:modifiers,id',
            'items.*.modifiers.*.quantity' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Instantiate the InvoiceToken service and generate the token
        $invoiceTokenService = new InvoiceToken();
        $invoiceToken = $invoiceTokenService->generateTokenNo();

        $orderData['customer_id'] = $request->customer_id;
        $orderData['is_coupon'] = $request->is_coupon;

        $orderData['order_from'] = $request->order_from;
        $orderData['order_type_id'] = $request->order_type_id;
        $orderData['loyalty_point_amount'] = $request->loyalty_point_amount;
        $orderData['discount'] = $request->discount;
        $orderData['table_id'] = $request->table_id;
        $orderData['outlet_id'] = $request->outlet_id;
        $orderData['user_id'] = $request->user_id;
        $orderData['waiter_id'] = $request->waiter_id;
        $orderData['order_status'] = 'new';
        $orderData['sale_no'] = $invoiceTokenService->generateSaleNo($request->outlet_id);
        $orderData['token_no'] = $invoiceToken;
        $orderData['sale_date'] = Carbon::now()->format('Y-m-d');
        $orderData['order_time'] = Carbon::now();
        $orderData['total_items'] = count($request->items);

        $order = $this->orders::create($orderData);

        $foodItems = $request->items;

        $orderId = $order->id;

        $orderData['vat_amount'] = 0;

        foreach ($foodItems as $item) {
            $foodItem = DB::table('food_menuses')
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
                ->where('food_menuses.id', $item['item_id'])
                ->first();

            if (!$foodItem) {
                // Handle case where foodItem is not found
                continue;
            }

            $ingredientsFoodMenu = DB::table('food_menu_ingredient')
                ->where('food_menu_id', $foodItem->id)
                ->get();
            foreach ($ingredientsFoodMenu as $ingredient) {
                $orderMenuConsData['ingredient_id'] = $ingredient->id;
                $orderMenuConsData['consumption'] = $ingredient->consumption;
                $orderMenuConsData['order_id'] = $orderId;
                $orderMenuConsData['food_menu_id'] = $foodItem->id;

                $this->orderMenuConsumption::create($orderMenuConsData);

                // Decrement ingredient quantity
                DB::table('ingredients')
                    ->where('id', $orderMenuConsData['ingredient_id'])
                    ->decrement('quantity', $orderMenuConsData['consumption']);
            }

            $orderDetailData['food_menu_id'] = $item['item_id'];
            $orderDetailData['single_discount'] = $foodItem->discount_amount;
            $orderDetailData['menu_unit_price'] = $foodItem->sale_price;
            $orderDetailData['menu_price_with_discount'] = $foodItem->sale_price;
            $orderDetailData['menu_vat_percentage'] = $foodItem->vat_percentage;
            if ($foodItem->is_discount === 'yes') {
                $orderDetailData['menu_discount_value'] = $foodItem->discount_amount;
                $orderDetailData['menu_price_with_discount'] = $orderDetailData['menu_price_with_discount'] - $orderDetailData['menu_discount_value'];
            }
            $orderDetailData['order_id'] = $orderId;
            $orderDetailData['qty'] = $item['quantity'];

            $orderDetail = $this->orderDetails::create($orderDetailData);

            $orderData['vat_amount'] += ($foodItem->vat_percentage / 100) * $foodItem->sale_price;

            $orderDetailModifier = $item['modifiers'];

            if ($orderDetailModifier && (count($orderDetailModifier) > 0)) {
                foreach ($orderDetailModifier as $modifier) {
                    $modifierData = $this->modifiers::find($modifier['modifier_id']);

                    if (!$modifierData) {
                        continue;
                    }

                    $ingredientsModifier = DB::table('modifiers_ingredient')
                        ->where('modifier_id', $modifierData->id)
                        ->get();

                    foreach ($ingredientsModifier as $ingredient) {
                        $orderModConsData['ingredient_id'] = $ingredient->id;
                        $orderModConsData['consumption'] = $ingredient->consumption;
                        $orderModConsData['order_id'] = $orderId;
                        $orderModConsData['modifier_id'] = $modifier['modifier_id'];

                        $this->orderModifierConsumption::create($orderModConsData);

                        // Decrement ingredient quantity
                        DB::table('ingredients')
                            ->where('id', $orderModConsData['ingredient_id'])
                            ->decrement('quantity', $orderModConsData['consumption']);
                    }

                    $orderModifierDetailData['order_id'] = $orderId;
                    $orderModifierDetailData['order_details_id'] = $orderDetail->id;
                    $orderModifierDetailData['modifier_id'] = $modifier['modifier_id'];
                    $orderModifierDetailData['qty'] = $modifier['quantity'];
                    $orderModifierDetailData['sell_price'] = $modifierData->price;
                    $orderModifierDetailData['vat'] = $modifierData->tax;

                    $this->orderModifierDetails::create($orderModifierDetailData);

                    $orderData['vat_amount'] += $modifierData->tax;
                }
            }
        }


        $order = $this->orders::find($order->id);

        // print_r($order);
        // exit();

        $order->update($orderData);

        return response()->json(['Order Data' => $order]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
