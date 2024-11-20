<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    public function login(){
        return 'login';
    }
    public function logout(){
        return 'logout';
    }
}
