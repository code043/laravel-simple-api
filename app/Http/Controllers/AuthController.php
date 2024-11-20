<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validate = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:3'
        ]);
        $user = User::create($validate);
        $token = $user->createToken($validate['name'])->plainTextToken;
        return response()->json([
            'message' => 'User has beed registered!',
            'user' => $user,
            'token' => $token
        ],201);
    }
    public function login(Request $request){
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'The provided credentials are incorrect!'                
            ]);
        }
        $token = $user->createToken($user->name);
        return response()->json([
            'message' => 'User logged',
            'token' => $token->plainTextToken
        ]);
    }
    public function logout(){
        return 'logout';
    }
}
