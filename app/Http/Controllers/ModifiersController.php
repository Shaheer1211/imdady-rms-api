<?php

namespace App\Http\Controllers;

use App\Models\ModifierIngredient;
use Illuminate\Http\Request;
use App\Models\Modifiers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateModifiersRequest;
use Validator;
use Illuminate\Support\Facades\DB;

class ModifiersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $modifiers;
    protected $modifiersIngredient;
    public function __construct()
    {
        $this->modifiers = new Modifiers();
        $this->modifiersIngredient = new ModifierIngredient();
    }
    public function index()
    {
        $results = Modifiers::with([
            'modifierIngredients.ingredient', // Nested eager loading
            'user'
        ])->where('del_status', 'Live')->get();
        return response()->json($results);
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
            'code' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
            'tax_method' => 'nullable|string|max:255',
            'tax' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable',
            'ingredients' => 'required|array', // Validate that ingredients is an array
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.consumption' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $modifierData = $request->only(['code', 'name', 'price', 'description', 'tax_method', 'tax', 'user_id', 'outlet_id', 'del_status']);
        $modifier = $this->modifiers->create($modifierData);
        $ingredients = $request->ingredients;
        $ingredients = $request->input('ingredients', []);
        foreach ($ingredients as $ingredient) {
            $this->modifiersIngredient->create([
                'modifier_id' => $modifier->id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'consumption' => $ingredient['consumption'],
                'user_id' => $request->user_id,
                'outlet_id' => $request->outlet_id,
            ]);
        }
        return response()->json(['success' => true, 'modifier' => $modifier], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modifier = Modifiers::find($id);

        if (is_null($modifier)) {
            return $this->sendError('Modifier not found.');
        }

        return $modifier;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modifiers $modifiers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModifiersRequest $request, Modifiers $modifiers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $modifier = Modifiers::find($id);

        if (!$modifier) {
            return $this->sendError('Modifier not found.', [], 404);
        }

        $modifier['del_status'] = 'deleted';
        $modifier->update();

        return $this->sendResponse('Modifier deleted successfully.', $modifier);
    }
}
