<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Models\attendance;
use App\Http\Requests\StoreattendanceRequest;
use App\Http\Requests\UpdateattendanceRequest;
use Illuminate\Http\Request;
use Validator;

class AttendanceController extends BaseController
{
    protected $attendance;
    public function __construct()
    {
        $this->attendance = new Attendance();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return attendance::all();
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
            'reference_no' => 'nullable|numeric',
            'employee_id' => 'nullable|numeric',
            'date' => 'nullable|date',
            'in_time' => 'nullable|date_format:H:i:s',
            'out_time' => 'nullable|date_format:H:i:s',
            'note' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',
        'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->attendance->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return $this->sendError('attendance not found.');
        }

        return $attendance;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateattendanceRequest $request, attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendance $attendance)
    {
        //
    }
}
