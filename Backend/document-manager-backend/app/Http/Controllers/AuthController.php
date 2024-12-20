<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {

      $validatedData =   $request->validate([
            'firstName'=>'required|max:255',
            'lastName' =>'required|max:255',
            'email'=>'required|email|unique:users',
            'password' => 'required|confirmed'
      ]);

        $user = User::create($validatedData);

        $token = $user->createToken($request->firstName);
        return [
            'user' => $user ,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request) {

        $request->validate([
            'email'=>'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email' , $request->email)->first();

        if(!$user || !Hash::check($request->password , $user->password)) {
            return response()->json(['message'=>'infos provided are incorrect'], 404);
        }

        $token = $user->createToken($user->firstName);

        return [
            'user' => $user ,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return [
            'message' => 'user logged out successfully'
        ];
    }
}
