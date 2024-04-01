<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'rms'], function () {
    // all customer routes
    Route::group(['namespace' => 'api\auth', 'prefix' => 'customer'], function () {
        Route::controller(CustomerLoginRegister::class)->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('logout', 'logout')->middleware(['customer', 'auth:sanctum']);
        });
    });
    // all admin routes
    Route::group(['namespace' => 'api\auth', 'prefix' => 'admin'], function () {
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
            Route::post('add-outlet', 'store');
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {
        Route::apiResource('categories', FoodMenuCategoriesController::class);
        Route::apiResource('ingredientCategories', IngredientCategoriesController::class);
        Route::apiResource('ingredientUnits', IngredientUnitController::class);
        Route::apiResource('ingredients', IngredientController::class);
        Route::apiResource('modifiers', ModifiersController::class);
        Route::apiResource('subCategories', FoodMenuSubCategoriesController::class);
        Route::apiResource('vats', VatsController::class);
        Route::apiResource('foodMenus', FoodMenusController::class);
    });

    // Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {
    //     Route::apiResource('ingredientCategories', IngredientCategoriesController::class);
    //     Route::apiResource('ingredientUnits', IngredientUnitController::class);
    // });

    // Route::group(['prefix' => 'admin', 'middleware' => ['module:order_management', 'auth:sanctum', 'admin']], function () {
    //     Route::apiResource('ingredientUnits', IngredientUnitController::class);
    // });
});

Route::fallback(function () {
    return response()->json(['message' => 'The requested API route could not be found.'], 404);
})->name('api.fallback');

