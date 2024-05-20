<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodMenus;
use App\Models\Modifiers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;

class FoodMenuCustomerController extends BaseController
{
    public function show($id)
    {
        $menuWithModifiers = FoodMenus::select(
            'food_menus.*',
            DB::raw('GROUP_CONCAT(modifiers.name) as modifiers_names'),
            'food_menus.photo as menu_photo'
        )
        ->leftJoin('modifiers', 'food_menus.id', '=', 'modifiers.food_menu_id')
        ->where('food_menus.id', $id)
        ->groupBy('food_menus.id')
        ->first();
        if (is_null($menuWithModifiers)) {
            return $this->sendError('Food Menu not found.');
        }

        // Append the full image URL to the food menu item
        if ($menuWithModifiers->photo) {
            $menuWithModifiers->photo_url = Storage::url($menuWithModifiers->photo);
        }

        return response()->json($menuWithModifiers);
    }
}
