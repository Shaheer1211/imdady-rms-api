<?php

namespace App\Http\Controllers;

use App\Models\BusinessSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateBusinessSettingsRequest;
use Validator;
use Illuminate\Support\Facades\Log;

class BusinessSettingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $BusinessSettings;
    public function __construct()
    {
        $this->BusinessSettings = new BusinessSettings();
    }
    public function index(Request $request)
    {
        $outletId = $request->query('outlet_id');
        $type = $request->query('type');

        $query = $this->BusinessSettings->newQuery();

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $businessSettings = $query->get();

        return $businessSettings;
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
            'type' => 'required',
            'value' => 'required',
            'status' => 'required',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $otherData = [];
        foreach ($request->all() as $key => $value) {
            // Exclude 'status' from being stored in 'otherData'
            if ($key !== 'type' && $key !== 'status' && $key !== 'user_id' && $key !== 'outlet_id') {
                $otherData[$key] = $value;
            }
        }

        $type = $request->input('type');
        $status = $request->input('status');
        $user_id = $request->input('user_id');
        $outlet_id = $request->input('outlet_id');

        $record = [
            'type' => $type,
            'value' => json_encode($otherData), // Convert to JSON before storing
            'user_id' => $user_id,
            'outlet_id' => $outlet_id,
            'status' => $status
        ];
        Log::info("record:", $record);

        return $this->BusinessSettings->create($record);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $businessSettings = BusinessSettings::find($id);

        if(is_null($businessSettings)) {
            return $this->sendError('Settings not found');
        }

        return $businessSettings;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessSettings $businessSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessSettingsRequest $request, BusinessSettings $businessSettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessSettings $businessSettings)
    {
        //
    }
}
