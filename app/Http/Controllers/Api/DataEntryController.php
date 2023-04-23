<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\data_entry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data_entries = data_entry::query();
    
        // mac filter
        if ($request->has('mac')) {
            $data_entries->where('mac', $request->mac);
        }
    
        // sensor filter
        if ($request->has('sensor_id')) {
            $data_entries->where('sensor_id', $request->sensor_id);
        }
    
        // power filter
        if ($request->has('pwr')) {
            $data_entries->where('PWR', $request->pwr);
        }
        elseif($request->has('min_pwr') || $request->has('max_pwr')){
            if($request->has('min_pwr') && $request->has('max_pwr')){
                $data_entries->whereBetween('PWR', [$request->min_pwr, $request->max_pwr]);
            }
            elseif($request->has('min_pwr')){
                $data_entries->where('PWR', '>=', $request->min_pwr);
            }
            elseif($request->has('max_pwr')){
                $data_entries->where('PWR', '<=', $request->max_pwr);
            }
        }
        
        // log at filter
        if ($request->has('log_at')) {
            $data_entries->where('log_at', $request->log_at);
        }
        elseif($request->has('log_before') || $request->has('log_after')){
            if($request->has('log_before') && $request->has('log_after')){
                $data_entries->where("log_at",'>=',$request->log_after)->where("log_at",'<=',$request->log_before);
            }
            elseif($request->has('log_before')){
                $data_entries->where('log_at', '<=', $request->log_before);
            }
            elseif($request->has('log_after')){
                $data_entries->where('log_at', '>=', $request->log_after);
            }
        }
    
        // updated at filter
        if ($request->has('updated_at')) {
            $data_entries->where('updated_at', $request->updated_at);
        }
        elseif($request->has('updated_before') || $request->has('updated_after')){
            if($request->has('updated_before') && $request->has('updated_after')){
                $data_entries->where("updated_at",'>=',$request->updated_after)->where("updated_at",'<=',$request->updated_before);
            }
            elseif($request->has('updated_before')){
                $data_entries->where('updated_at', '<=', $request->updated_before);
            }
            elseif($request->has('updated_after')){
                $data_entries->where('updated_at', '>=', $request->updated_after);
            }
        }
    
        $data_entries = $data_entries->get();
    
        return response()->json(['data_entries' => $data_entries ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'MAC' => 'required|string|mac_address|exists:devices,MAC',
            'sensor_id' => 'required|string|exists:sensors,id',
            'pwr' => 'required|integer'
        ]);

        try {
            $dataEntry = data_entry::create($validatedData);
            return response()->json(['message' => 'Data registered successfully',compact('dataEntry')], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to register data'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $device = data_entry::find($id);
        if (!$device) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($device);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, data_entry $data_entry)
    {
        $validatedData = $request->validate([
            'MAC' => 'sometimes|string|mac_address|exists:devices,MAC',
            'sensor_id' => 'sometimes|string|exists:sensors,id',
            'pwr' => 'sometimes|integer'
        ]);

        try {
            $dataEntry = $data_entry->update($validatedData);
            return response()->json(['message' => 'Data updated successfully' ,compact('data_entry')], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update data'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(data_entry $data_entry)
    {
        try {
            $data_entry->delete();
            return response()->json(['message' => 'Data deleted successfully',compact('data_entry')], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete data'], 500);
        }
    }

    // public function softDelete( $id )
    // {
    //     $product = Product::find($id)->delete();
    //     return redirect()->route("product.index")
    //     ->with("success" ,"Product deleted successfully");
    // }

    // public function trashed()
    // {
    //     // $products = product::all()->paginate(4);
    //     $products = product::withTrashed()->latest()->paginate(4);

    //     return view("product.trashed" ,compact("products"));
    // }

    // public function restore($id)
    // {
    //     // $products = product::all()->paginate(4);
    //     $p = product::onlyTrashed()->where('id' ,$id)->first()->restore();
    //     $products = product::withTrashed()->latest()->paginate(4);

    //     return view("product.trashed" ,compact("products"));
    // }


}
