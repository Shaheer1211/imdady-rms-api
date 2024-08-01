<?php

namespace App\Http\Controllers;

use App\Models\FoodMenuSubCategories;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateFoodMenuSubCategoriesRequest;
use Validator;
use Illuminate\Support\Facades\DB;

class FoodMenuSubCategoriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $subCategories;
    public function __construct()
    {
        $this->subCategories = new FoodMenuSubCategories();

    }
    public function index(Request $request)
    {
        $results = FoodMenuSubCategories::with([
            'category',
            'user'
        ])->where('del_status', 'Live');
        return $results->get();
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
            'category_id' => 'required|exists:food_menu_categories,id',
            'sub_category_name' => 'required |string|max:255',
            'sub_category_name_arabic' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->subCategories->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $subCategories = $this->subCategories->find($id);
        $results = DB::table('food_menu_sub_categories')
            ->select('food_menu_sub_categories.*', 'food_menu_categories.category_name AS category_name', 'food_menu_categories.cat_name_arabic AS category_name_ar', 'users.name as added_by')
            ->join('food_menu_categories', 'food_menu_categories.id', '=', 'food_menu_sub_categories.category_id')
            ->join('users', 'users.id', '=', 'food_menu_sub_categories.user_id')
            ->where('food_menu_sub_categories.id', '=', $id)
            ->get();
        if (is_null($results)) {
            return $this->sendError('Sub Category not found.');
        }

        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodMenuSubCategories $foodMenuSubCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subCategories = $this->subCategories->find($id);
        if ($subCategories) {
            $subCategories->update($request->all());
            return response()->json(['message' => 'Sub Category update successfully'], 200);
        } else {
            return response()->json(['message' => 'Sub Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Find the deal by its ID
        $subCat = FoodMenuSubCategories::find($id);

        // Check if the deal exists
        if (!$subCat) {
            return $this->sendError('Sub Category not found.', [], 404);
        }

        // Delete the deal and its associated items
        $subCat['del_status'] = 'deleted';
        $subCat->update();

        return $this->sendResponse('Deal deleted successfully.', $subCat);
    }
}
