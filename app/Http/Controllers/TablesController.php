<?php

namespace App\Http\Controllers;

use App\Models\Tables;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;
use App\Http\Requests\UpdateTablesRequest;
use Validator;
use Illuminate\Support\Facades\DB;

class TablesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $tables;
    public function __construct(){
        $this->tables = new Tables(); 
    }
    public function index()
    {
        $tables = DB::table('tables')
    ->join('users', 'users.id', '=', 'tables.user_id')
    ->select('tables.*', 'users.name as added_by')
    ->get();
        return $tables;
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
            'name'=> 'nullable|string|max:255',
            'sit_capacity'=> 'nullable|string|max:255',
            'position'=> 'nullable|string|max:255',
            'description'=> 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'outlet_id' => 'required|exists:outlets,id',
            'del_status' => 'nullable'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        return $this->tables->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tables = Tables::find($id);

        if (is_null($tables)) {
            return $this->sendError('Tables not found.');
        }

        return $tables;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tables $tables)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tables = Tables::find($id);

        if ($tables) {
            $tables->update($request->all());
            return response()->json(['message' => 'Table update successfully'], 200);
        } else {
            return response()->json(['message' => 'Table not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tables $tables)
    {
        //
    }
}
