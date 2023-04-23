<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\DataEntryController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->apiResource('rooms', RoomController::class);
// Route::middleware('auth:api')->apiResource('devices', DeviceController::class);
// Route::middleware('auth:api')->apiResource('sensors', SensorController::class);
// Route::middleware('auth:api')->apiResource('data_entry', DataEntryController::class);

// without authentification
// Route::apiResource('rooms', RoomController::class);
Route::apiResource('devices', DeviceController::class);
Route::apiResource('sensors', SensorController::class);
Route::apiResource('data_entry', DataEntryController::class);
Route::apiResource('user', App\Http\Controllers\Api\UserController::class);



Route::post('/createToken', function (Request $request) {
    $nameOrEmail = $request->input('name_or_email');
    $password = $request->input('password');

    $user_id = DB::table('users')->where('email' ,$nameOrEmail )->value('id');

    
    if ($user_id) {
        
        $token = User::find($user_id)->createToken('My Token')->plainTextToken;
        return response()->json(['message' => "creadentials has been created successfully" ,'token' => $token ,compact('user_id')]);
    } else {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
});


