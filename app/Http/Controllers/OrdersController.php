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

        $orders = DB::table('orders')
            ->select([
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
                'customers.name AS customer_name',
                'customers.email AS customer_email',
                'customers.phone AS customer_phone',
                'customers.alternate_number AS customer_alt_num',
                'customers.address AS customer_address',
                'customers.customer_vat',
                'customers.employe_card_no AS customer_emp_card_no',
                'ordertypes.name AS order_type_name',
                'outlets.name AS outlet_name',
                'outlets.phone AS outlet_phone',
                'outlets.code AS outlet_code',
                'outlets.email AS outlet_email',
                'outlets.address AS outlet_address',
                'outlets.registration_no AS outlet_reg_no',
                'users_waiter.name AS waiter_name',
                'users_cashier.name AS cashier_name',
                'tables.name AS table_name',
                DB::raw(
                    "CONCAT('[', GROUP_CONCAT(
                CONCAT(
                    '{ \"food_menu_name\": \"', IFNULL(food_menuses.name, ''), '\", ',
                    '\"food_menu_code\": \"', IFNULL(food_menuses.code, ''), '\", ',
                    '\"food_menu_id\": ', IFNULL(food_menuses.id, 'NULL'), ', ',
                    '\"food_menu_single_discount\": ', IFNULL(order_details.single_discount, 0), ', ',
                    '\"food_menu_qty\": ', IFNULL(order_details.qty, 0), ', ',
                    '\"food_menu_unit_price\": ', IFNULL(order_details.menu_unit_price, 0), ', ',
                    '\"food_menu_price_with_discount\": ', IFNULL(order_details.menu_price_with_discount, 0), ', ',
                    '\"food_menu_note\": \"', IFNULL(order_details.menu_note, ''), '\", ',
                    '\"food_menu_cooking_status\": \"', IFNULL(order_details.cooking_status, ''), '\", ',
                    '\"food_menu_cooking_start_time\": \"', IFNULL(order_details.cooking_start_time, ''), '\", ',
                    '\"food_menu_cooking_end_time\": \"', IFNULL(order_details.cooking_end_time, ''), '\", ',
                    '\"food_menu_taxes\": \"', IFNULL(order_details.menu_taxes, ''), '\", ',
                    '\"food_menu_item_type\": \"', IFNULL(order_details.item_type, ''), '\", ',
                    '\"order_item_modifiers\": [', 
                        COALESCE(order_item_modifiers.modifiers, '[]'), 
                    '] }'
                ) SEPARATOR ','
            ), ']') AS order_items"
                )
            ])
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('ordertypes', 'ordertypes.id', '=', 'orders.order_type_id')
            ->leftJoin('outlets', 'outlets.id', '=', 'orders.outlet_id')
            ->leftJoin('users as users_waiter', 'users_waiter.id', '=', 'orders.waiter_id')
            ->leftJoin('users as users_cashier', 'users_cashier.id', '=', 'orders.cashier_id')
            ->leftJoin('tables', 'tables.id', '=', 'orders.table_id')
            ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
            ->leftJoin('food_menuses', 'food_menuses.id', '=', 'order_details.food_menu_id')
            ->leftJoin(DB::raw("(
        SELECT 
            order_details_id, 
            GROUP_CONCAT(
                CONCAT(
                    '{ \"modifier_name\": \"', IFNULL(modifiers.name, ''), '\", ',
                    '\"modifier_description\": \"', IFNULL(modifiers.description, ''), '\", ',
                    '\"modifier_qty\": ', IFNULL(order_modifier_details.qty, 0), ', ',
                    '\"modifier_sell_price\": ', IFNULL(order_modifier_details.sell_price, 0), ', ',
                    '\"modifier_vat\": ', IFNULL(order_modifier_details.vat, 0), ', ',
                    '\"modifier_id\": ', IFNULL(order_modifier_details.id, 'NULL'), 
                '}'
            ) SEPARATOR ','
        ) AS modifiers
        FROM order_modifier_details
        LEFT JOIN modifiers ON modifiers.id = order_modifier_details.modifier_id
        GROUP BY order_details_id
    ) AS order_item_modifiers"), 'order_item_modifiers.order_details_id', '=', 'order_details.id')
            ->groupBy(
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
                'users_cashier.name',
                'tables.name'
            )
            ->get();

        // $data['orders'] = $orders;

        return $orders;
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
