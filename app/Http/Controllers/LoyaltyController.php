<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Loyalty;
use Validator;

class LoyaltyController extends BaseController
{
    /**
     * Display a listing of  the resource.
     */
    public function index()
    {
        $loyalties = Loyalty::all();
        return response()->json($loyalties, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'convert_points' => 'required|integer|min:0',
            'per_price' => 'required|numeric|min:0',
            'percentage_order_amount' => 'required|numeric|min:0|max:100',
            'minimum_point' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Using updateOrCreate to either create a new entry or update the existing one
        $loyalty = Loyalty::updateOrCreate(
            ['id' => 1], // Assuming only one loyalty entry with ID 1
            $request->all()
        );

        $message = $loyalty->wasRecentlyCreated ? 'Loyalty created successfully' : 'Loyalty updated successfully';

        return response()->json(['message' => $message, 'data' => $loyalty], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $loyalty = Loyalty::findOrFail($id);
        return response()->json($loyalty, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'details' => $validator->errors()], 422);
        }

        $loyalty = Loyalty::find($id);
        if (!$loyalty) {
            return response()->json(['error' => 'Loyalty entry not found.'], 404);
        }

        $loyalty->update(['status' => $request->status]);

        $statusMessage = $request->status ? 'enabled' : 'disabled';

        return response()->json([
            'message' => "Loyalty status updated successfully to $statusMessage.",
            'data' => $loyalty
        ], 200);
    }

}
