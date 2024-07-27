<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'rms'], function () {
    // all customer routes
    Route::group(['namespace' => 'Api\Auth', 'prefix' => 'customer'], function () {
        Route::controller(CustomerLoginRegister::class)->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('logout', 'logout')->middleware(['customer', 'auth:sanctum']);
            Route::put('update-profile', 'updateProfile')->middleware(['customer', 'auth:sanctum']);
            Route::put('update-password', 'updatePassword')->middleware(['customer', 'auth:sanctum']);
        });
    });
    Route::group(['prefix' => 'customer'], function () {
        Route::get('categories', 'FoodMenuCategoriesController@index');
        Route::get('ingredientCategories', 'IngredientCategoriesController@index');
        Route::get('ingredientUnits', 'IngredientUnitController@index');
        Route::get('ingredients', 'IngredientController@index');
        Route::get('modifiers', 'ModifiersController@index');
        Route::get('subCategories', 'FoodMenuSubCategoriesController@index');
        Route::get('vats', 'VatsController@index');
        Route::get('foodMenus', 'FoodMenusController@index');
        Route::get('foodMenus/{id}', 'FoodMenusController@show');
        Route::get('tables', 'TablesController@index');
        Route::get('returns', 'ReturnsController@index');
        Route::get('deposit', 'DepositController@index');
        Route::get('banners', 'BannersController@index');
        Route::get('settings', 'BusinessSettingsController@index');
        Route::get('deals', 'DealController@index');
        // Route::get('get-outlets', 'OutletController@index');
        Route::get('get-outlets/{id}', 'OutletController@ordertype');
        Route::get('get-order-type', 'OrdertypeController@index');
        Route::get('outletCities', 'OutletController@outletCities');
        Route::get('foodMenuModifier', 'FoodMenuModifiersController@index');
        Route::get('foodMenuModifier/{id}', 'FoodMenuModifiersController@show');
        Route::get('foodmenuWithModifier/{id}', 'FoodMenuCustomerController@show');
        Route::get('cart', 'CartController@index');
        Route::post('cart', 'CartController@store');
        Route::put('cart', 'CartController@update');
        Route::delete('cart', 'CartController@destroy');
        Route::get('get-outlets', 'OutletController@index');
        Route::get('get-outlets/{id}', 'OutletController@show');
        Route::get('cities', 'OutletController@cities');
        Route::get('outletCities', 'OutletController@outletCities');
        Route::post('order', 'OrdersController@store');
        Route::get('order', 'OrdersController@index');
        // Route::controller(OutletController::class)->group(function () {
        //     Route::get('get-outlets', 'index');
        //     Route::get('get-outlets/{id}', 'show');
        //     // Route::post('add-outlet', 'store');
        //     // Route::put('update-outlet/{id}', 'update');
        //     Route::get('cities', 'cities');
        //     Route::get('outletCities', 'outletCities');
        // });
    });
    // all admin routes
    Route::group(['namespace' => 'Api\Auth', 'prefix' => 'admin'], function () {
        Route::controller(UserLoginRegister::class)->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('logout', 'logout')->middleware(['admin', 'auth:sanctum']);
        });
    });
    // only super admin routes
    Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth:sanctum']], function () {
        Route::controller(OutletController::class)->group(function () {
            Route::get('get-outlets', 'index');
            Route::get('get-outlets/{id}', 'show');
            Route::post('add-outlet', 'store');
            Route::put('update-outlet/{id}', 'update');
            Route::get('cities', 'cities');
            Route::get('outletCities', 'outletCities');
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {

        // Route::apiResource('multiple_payments', Payment\MultiplePayments::class);
        // Route::apiResource('payment_methods', Payment\PaymentMethods::class);

        Route::apiResource('categories', FoodMenuCategoriesController::class);
        Route::apiResource('ingredientCategories', IngredientCategoriesController::class);
        Route::apiResource('ingredientUnits', IngredientUnitController::class);
        Route::apiResource('ingredients', IngredientController::class);
        Route::apiResource('modifiers', ModifiersController::class);
        Route::apiResource('subCategories', FoodMenuSubCategoriesController::class);
        Route::apiResource('vats', VatsController::class);
        Route::apiResource('foodMenus', FoodMenusController::class);
        Route::put('updateFoodMenus/{id}', 'FoodMenusController@updateFoodMenu');
        Route::apiResource('foodMenuModifiers', FoodMenuModifiersController::class);
        Route::apiResource('tables', TablesController::class);
        Route::apiResource('returns', ReturnsController::class);
        Route::apiResource('deposit', DepositController::class);
        Route::apiResource('banners', BannersController::class);
        Route::apiResource('settings', BusinessSettingsController::class);
        Route::apiResource('cart', CartController::class);
        Route::apiResource('deal', DealController::class);
        Route::apiResource('topbanner', TopbannerController::class);
        Route::apiResource('ordertype', OrdertypeController::class);
        Route::apiResource('socialmedia', SocialmediaController::class);
        Route::apiResource('coupons', CouponsController::class);
        Route::put('change-coupons/{id}', 'CouponsController@edit');
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('creditcard', CreditController::class);
        Route::apiResource('subscription', SubscriptionController::class);
        Route::apiResource('productdiscount', ProductdiscontController::class);
        //catagory discount pags created in vs
        Route::apiResource('purchase', PurchaseController::class);
        //inventory
        Route::apiResource('inventoryadjustment', InventoryadjustmentController::class);
        Route::apiResource('expensescategory', ExpensesCategoryController::class);
        Route::apiResource('expense', ExpenseController::class);
        Route::apiResource('waste', WasteController::class);
        Route::apiResource('companies', CompaniesController::class);
        Route::apiResource('suppliers', SuppliersController::class);
        Route::apiResource('vendor', VendorController::class);
        Route::apiResource('supplierpayment', SupplierpaymentController::class);
        Route::apiResource('customerDueReceives', CustomerDueReceivesController::class);
        Route::apiResource('attendance', AttendanceController::class);
        // Route::post('add_sale', 'Api\Auth\MainScreenPOS@add_sale');
        Route::apiResource('loyalty', LoyaltyController::class);
        Route::apiResource('discounts', DiscountController::class);
        Route::get('get_sale/{id}', 'Api\Auth\MainScreenPOS@fetchOrderWithDetails');

    });

    // Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {
    //     Route::apiResource('ingredientCategories', IngredientCategoriesController::class);
    //     Route::apiResource('ingredientUnits', IngredientUnitController::class);
    // });

    // Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {
    //     Route::apiResource('ingredientUnits', IngredientUnitController::class);
    // });
});

        Route::apiResource('rms/admin/multiple_payments', Payment\MultiplePayments::class);
        Route::apiResource('rms/admin/payment_methods', Payment\PaymentMethods::class);
        Route::get('rms/admin/get-all-payment', 'Payment\PaymentMethods@getPaymentMethods');
        Route::post('rms/customer/add_sale', 'Api\Auth\MainScreenPOS@add_sale');
        Route::post('rms/customer/check-coupons', 'CouponsController@checkCoupon');


Route::get('maintenance/clear-and-cache', function () {
    // Clear configurations
    Artisan::call('config:clear');
    // Clear routes
    Artisan::call('route:clear');
    // Clear events
    Artisan::call('event:clear');
    // Clear views
    Artisan::call('view:clear');
    // Cache configurations
    Artisan::call('config:cache');

    return response()->json(['message' => 'Configuration, routes, events, and views cleared and configuration cached successfully.']);
});

Route::fallback(function () {
    return response()->json(['message' => 'The requested API route could not be found.'], 404);
})->name('api.fallback');

