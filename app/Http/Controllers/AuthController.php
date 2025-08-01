<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return response()->json([
            'token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth('api')->factory()->getTTL()
        ]);

    }
}
