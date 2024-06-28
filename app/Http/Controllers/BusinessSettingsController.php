<?php

namespace App\Http\Controllers;

use App\Models\BusinessSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateBusinessSettingsRequest;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        // Process each setting to include the full image URL if the type is 'logo'
        $businessSettings->each(function ($setting) {
            if ($setting->type === 'logo') {
                $value = json_decode($setting->value, true);
                if (isset($value['logo_image'])) {
                    $value['logo_image_url'] = url(Storage::url($value['logo_image']));
                    $setting->value = json_encode($value);
                }
            }
        });

        return response()->json($businessSettings);
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
            'status' => 'required',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'logo_image' => 'required_if:type,logo|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation for logo image
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $otherData = [];
        foreach ($request->all() as $key => $value) {
            // Exclude certain fields from being stored in 'otherData'
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
            'user_id' => $user_id,
            'outlet_id' => $outlet_id,
            'status' => $status
        ];

        if ($type === 'logo') {
            // Store the image in the storage folder
            $imageName = $request->file('logo_image')->getClientOriginalExtension();
            $image = rand().'.'.$imageName;
            $request->file('logo_image')->move('storage/logos', $image);
            $imagePath = 'logos/'.$image;
            $record['value'] = json_encode(['logo_image' => $imagePath]);
        } else {
            $record['value'] = json_encode($otherData); // Convert other data to JSON before storing
        }

        Log::info("record:", $record);

        return $this->BusinessSettings->create($record);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $businessSettings = BusinessSettings::find($id);

        if (is_null($businessSettings)) {
            return $this->sendError('Settings not found');
        }

        // Process setting to include the full image URL if the type is 'logo'
        if ($businessSettings->type === 'logo') {
            $value = json_decode($businessSettings->value, true);
            if (isset($value['logo_image'])) {
                $value['logo_image_url'] = url(Storage::url($value['logo_image']));
                $businessSettings->value = json_encode($value);
            }
        }

        return response()->json($businessSettings);
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
