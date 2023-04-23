<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sensors = Sensor::query();


        // name filter
        if ($request->has('name')) {
            $sensors->where('Name', $request->name);
        }
        elseif ($request->has('reg_name')) {
            $sensors->where('Name', 'REGEXP' , $request->reg_name);
        }

        // postion x filter
        if ($request->has('postion_x')) {
            $sensors->where('Position_x', $request->postion_x);
        }
        elseif($request->has('min_postion_x') || $request->has('max_postion_x')){
            if($request->has('min_postion_x') && $request->has('max_postion_x')){
                $sensors->whereBetween('Position_x', [$request->min_postion_x, $request->max_postion_x]);
            }
            elseif($request->has('min_postion_x')){
                $sensors->where('Position_x', '>=', $request->min_postion_x);
            }
            elseif($request->has('max_postion_x')){
                $sensors->where('Position_x', '<=', $request->max_postion_x);
            }
        }

        // postion y filter
        if ($request->has('postion_y')) {
            $sensors->where('Position_y', $request->postion_y);
        }
        elseif($request->has('min_postion_y') || $request->has('max_postion_y')){
            if($request->has('min_postion_y') && $request->has('max_postion_y')){
                $sensors->whereBetween('Position_y', [$request->min_postion_y, $request->max_postion_y]);
            }
            elseif($request->has('min_postion_y')){
                $sensors->where('Position_y', '>=', $request->min_postion_y);
            }
            elseif($request->has('max_postion_y')){
                $sensors->where('Position_y', '<=', $request->max_postion_y);
            }
        }

        // details filter
        if ($request->has('details')) {
            $sensors->where('Details', $request->details);
        }
        elseif ($request->has('reg_details')) {
            $sensors->where('Details', 'REGEXP' , $request->reg_details);
        }


        // created at filter
        if ($request->has('created_at')) {
            $sensors->where('created_at', $request->created_at);
        }
        elseif($request->has('created_before') || $request->has('created_after')){
            if($request->has('created_before') && $request->has('created_after')){
                $sensors->where("created_at",'>=',$request->created_after)->where("created_at",'<=',$request->created_before);
            }
            elseif($request->has('created_before')){
                $sensors->where('created_at', '<=', $request->created_before);
            }
            elseif($request->has('created_after')){
                $sensors->where('created_at', '>=', $request->created_after);
            }
        }
    
        // updated at filter
        if ($request->has('updated_at')) {
            $sensors->where('updated_at', $request->updated_at);
        }
        elseif($request->has('updated_before') || $request->has('updated_after')){
            if($request->has('updated_before') && $request->has('updated_after')){
                $sensors->where("updated_at",'>=',$request->updated_after)->where("updated_at",'<=',$request->updated_before);
            }
            elseif($request->has('updated_before')){
                $sensors->where('updated_at', '<=', $request->updated_before);
            }
            elseif($request->has('updated_after')){
                $sensors->where('updated_at', '>=', $request->updated_after);
            }
        }
        
        $sensors = $sensors->get();
        return response()->json(['sensors' => $sensors ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string',
            'Position_x' => 'required|integer',
            'Position_y' => 'required|integer',
            'Details' => 'nullable|string'
        ]);
        try {
            $sensor = Sensor::create($validatedData);
            return response()->json(['message' => 'Sensor created successfully', 'sensor' => $sensor], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create sensor'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Sensor not found'], 500);
        }
        return response()->json($sensor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Sensor not found'], 500);
        }
        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string',
            'Position_x' => 'sometimes|required|integer',
            'Position_y' => 'sometimes|required|integer',
            'Details' => 'sometimes|nullable|string'
        ]);
        try {
            $sensor->update($validatedData);
            return response()->json(['message' => 'Sensor updated successfully', 'sensor' => $sensor], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create sensor'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sensor = Sensor::findOrFail($id);
            $sensor->delete();
            return response()->json(['message' => 'Sensor deleted successfully'], 204);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete sensor'], 500);
        }
    }
}
