<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $devices = Device::query();
    
        // name filter
        if ($request->has('name')) {
            $devices->where('name', $request->name);
        }
        elseif ($request->has('reg_name')) {
            $devices->where('name', 'REGEXP' , $request->reg_name);
        }

        // status filter
        if ($request->has('status')) {
            if($request->status == "true" || $request->status == "1" || $request->status == "active"){
                $devices->where('status', "active");
            }
            elseif($request->status == "false" || $request->status == "0" || $request->status == "inactive"){
                $devices->where('status', "inactive");
            }
        }

        // postion x filter
        if ($request->has('postion_x')) {
            $devices->where('Position_x', $request->postion_x);
        }
        elseif($request->has('min_postion_x') || $request->has('max_postion_x')){
            if($request->has('min_postion_x') && $request->has('max_postion_x')){
                $devices->whereBetween('Position_x', [$request->min_postion_x, $request->max_postion_x]);
            }
            elseif($request->has('min_postion_x')){
                $devices->where('Position_x', '>=', $request->min_postion_x);
            }
            elseif($request->has('max_postion_x')){
                $devices->where('Position_x', '<=', $request->max_postion_x);
            }
        }

        // postion y filter
        if ($request->has('postion_y')) {
            $devices->where('Position_y', $request->postion_y);
        }
        elseif($request->has('min_postion_y') || $request->has('max_postion_y')){
            if($request->has('min_postion_y') && $request->has('max_postion_y')){
                $devices->whereBetween('Position_y', [$request->min_postion_y, $request->max_postion_y]);
            }
            elseif($request->has('min_postion_y')){
                $devices->where('Position_y', '>=', $request->min_postion_y);
            }
            elseif($request->has('max_postion_y')){
                $devices->where('Position_y', '<=', $request->max_postion_y);
            }
        }

        // room id filter
        if ($request->has('room_id')) {
            $devices->where('room_id', $request->room_id);
        }

        // created at filter
        if ($request->has('created_at')) {
            $devices->where('created_at', $request->created_at);
        }
        elseif($request->has('created_before') || $request->has('created_after')){
            if($request->has('created_before') && $request->has('created_after')){
                $devices->where("created_at",'>=',$request->created_after)->where("created_at",'<=',$request->created_before);
            }
            elseif($request->has('created_before')){
                $devices->where('created_at', '<=', $request->created_before);
            }
            elseif($request->has('created_after')){
                $devices->where('created_at', '>=', $request->created_after);
            }
        }
    
        // updated at filter
        if ($request->has('updated_at')) {
            $devices->where('updated_at', $request->updated_at);
        }
        elseif($request->has('updated_before') || $request->has('updated_after')){
            if($request->has('updated_before') && $request->has('updated_after')){
                $devices->where("updated_at",'>=',$request->updated_after)->where("updated_at",'<=',$request->updated_before);
            }
            elseif($request->has('updated_before')){
                $devices->where('updated_at', '<=', $request->updated_before);
            }
            elseif($request->has('updated_after')){
                $devices->where('updated_at', '>=', $request->updated_after);
            }
        }

        $devices = $devices->get();
        return response()->json(["message" => "The search is complete" ,'devices' => $devices ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'MAC' => 'required|string|unique:devices|mac_address',
            'Name' => 'required|string',
            'Status' => 'required|string|in:active,inactive',
            'Position_x' => 'required|integer',
            'Position_y' => 'required|integer',
            'room_id' => 'required|exists:rooms,id'
        ]);

        try {
            $device = Device::create($validatedData);
            return response()->json(['message' => 'Device created successfully', 'device' => $device], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create device' ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }
        return response()->json(['message' => 'Device found successfully' ,'device' => $device] ,200 );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $device = Device::findOrFail($id);

            $validatedData = $request->validate([
                'Name' => 'sometimes|string',
                'Status' => 'sometimes|boolean',
                'Position_x' => 'sometimes|integer',
                'Position_y' => 'sometimes|integer',
                'room_id' => 'sometimes|exists:rooms,id'
            ]);


            if (!$device) {
                return response()->json(['message' => 'Device not found'], 404);
            }

            $device->update($validatedData);
            return response()->json(['message' => 'Device updated successfully', 'device' => $device ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update device', "message" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $device = Device::findOrFail($id);
            if (!$device) {
                return response()->json(['message' => 'Device not found'], 404);
            }
            $device->delete();
            return response()->json(['message' => 'Device deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete device'], 500);
        }
    }
}
