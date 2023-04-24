<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'show' ,// Could add bunch of more methods too
            'update' ,// Could add bunch of more methods too
            'destroy' // Could add bunch of more methods too
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate the input data
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);


            // Create a new user in the database
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->save();

            // Return a response indicating success
            return response()->json([
                'message' => 'User created successfully' ,
                'user_id' => $user->id 
            ], 201);            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create new user'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['message' => 'User found successfully' ,"user" => $user] ,200 );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate the input data
            $validatedData = $request->validate([
                'name' => 'sometimes',
                'email' => 'sometimes|email|unique:users,email',
                'password' => 'sometimes|min:8',
            ]);

            $user = User::find($id);
            if(!$user){
                return response()->json(['message' => 'User not found' ], 404);
            }
            $user->update($validatedData);

            // Return a response indicating success
            return response()->json([
                'message' => 'User updated successfully' ,
                'user_id' => $user->id 
            ], 201);            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user' ,"error" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if(!$user){
                return response()->json(['message' => 'User not found' ], 404);
            }
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete user'], 500);
        }
    }

}
