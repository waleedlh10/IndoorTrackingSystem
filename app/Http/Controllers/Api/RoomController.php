<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $rooms = Room::query();
    
    
        // name filter
        if ($request->has('name')) {
            $rooms->where('Name', $request->name);
        }
        elseif ($request->has('reg_name')) {
            $rooms->where('Name', 'REGEXP' , $request->reg_name);
        }

        // postion x filter
        if ($request->has('postion_x')) {
            $rooms->where('Position_x', $request->postion_x);
        }
        elseif($request->has('min_postion_x') || $request->has('max_postion_x')){
            if($request->has('min_postion_x') && $request->has('max_postion_x')){
                $rooms->whereBetween('Position_x', [$request->min_postion_x, $request->max_postion_x]);
            }
            elseif($request->has('min_postion_x')){
                $rooms->where('Position_x', '>=', $request->min_postion_x);
            }
            elseif($request->has('max_postion_x')){
                $rooms->where('Position_x', '<=', $request->max_postion_x);
            }
        }

        // postion y filter
        if ($request->has('postion_y')) {
            $rooms->where('Position_y', $request->postion_y);
        }
        elseif($request->has('min_postion_y') || $request->has('max_postion_y')){
            if($request->has('min_postion_y') && $request->has('max_postion_y')){
                $rooms->whereBetween('Position_y', [$request->min_postion_y, $request->max_postion_y]);
            }
            elseif($request->has('min_postion_y')){
                $rooms->where('Position_y', '>=', $request->min_postion_y);
            }
            elseif($request->has('max_postion_y')){
                $rooms->where('Position_y', '<=', $request->max_postion_y);
            }
        }

        // length filter
        if ($request->has('length')) {
            $rooms->where('Length', $request->length);
        }
        elseif($request->has('min_length') || $request->has('max_length')){
            if($request->has('min_length') && $request->has('max_length')){
                $rooms->whereBetween('Length', [$request->min_length, $request->max_length]);
            }
            elseif($request->has('min_length')){
                $rooms->where('Length', '>=', $request->min_length);
            }
            elseif($request->has('max_length')){
                $rooms->where('Length', '<=', $request->max_length);
            }
        }

        // width filter
        if ($request->has('width')) {
            $rooms->where('Width', $request->width);
        }
        elseif($request->has('min_width') || $request->has('max_width')){
            if($request->has('min_width') && $request->has('max_width')){
                $rooms->whereBetween('Width', [$request->min_width, $request->max_width]);
            }
            elseif($request->has('min_width')){
                $rooms->where('Width', '>=', $request->min_width);
            }
            elseif($request->has('max_width')){
                $rooms->where('Width', '<=', $request->max_width);
            }
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


        $rooms = $rooms->get();
    
        return response()->json(['rooms' => $rooms ]);
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
            'Length' => 'required|integer',
            'Width' => 'required|integer',
        ]);

        try {
            $room = Room::create($validatedData);
            return response()->json(['message' => 'Room created successfully', 'room' => $room], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create room'], 500);
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string',
            'Position_x' => 'sometimes|required|integer',
            'Position_y' => 'sometimes|required|integer',
            'Length' => 'sometimes|required|integer',
            'Width' => 'sometimes|required|integer',
        ]);

        try {
            $room->update($validatedData);
            return response()->json(['message' => 'Room updated successfully', 'room' => $room], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update room'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
            return response()->json(['message' => 'Room deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete room'], 500);
        }
    }
}
