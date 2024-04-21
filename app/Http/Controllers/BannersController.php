<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatebannersRequest;
use Validator;
use App\Http\Controllers\API\Auth\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;

class BannersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $outletId = $request->query('outlet');
        $status = $request->query('status');

        $query = Banners::query();

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $banners = $query->get();

        // Append the full image URL to each banner object
        $banners->each(function ($banner) {
            $banner->image_url = Storage::url($banner->banner_image);
        });

        return response()->json($banners);
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
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_name' => 'nullable|string|max:55',
            'status' => 'nullable|string|max:55',
            'user_id' => 'required|integer|exists:users,id',
            'outlet_id' => 'required|integer|exists:outlets,id',
        ]);

        // Store the image in the storage folder
        $imagePath = $request->file('banner_image')->store('banners', 'public');

        // Create a new banner record in the database
        $banner = new Banners();
        $banner->banner_image = $imagePath;
        $banner->banner_name = $request->input('banner_name');
        $banner->status = $request->input('status');
        $banner->user_id = $request->input('user_id');
        $banner->outlet_id = $request->input('outlet_id');
        $banner->save();

        return response()->json(['message' => 'Banner created successfully'], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banner = Banners::find($id);

        if (is_null($banner)) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        // Append the full image URL to the banner object
        $banner->image_url = Storage::url($banner->banner_image);

        return response()->json($banner);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(banners $banners)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatebannersRequest $request, banners $banners)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(banners $banners)
    {
        //
    }
}
