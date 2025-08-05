<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string'
        ]);
        $credentials=$request->only('username','password');
        if (! $token=auth('api')->attempt($credentials)){
            return response()->json(['error'=>'Invalid username or password'], 401);
        }
        $user = auth('api')->user();
        return response()->json([
            'token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth('api')->factory()->getTTL(),
            'role' => $user->role
        ]);

    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Failed to logout, token missing or invalid'], 500);
        }
    }
}
