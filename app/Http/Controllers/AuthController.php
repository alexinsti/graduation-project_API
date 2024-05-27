<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use \stdClass;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'username'=>'required|string|max:255|unique:users',
                'nickname' => 'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:users',
                'password'=>'required|string|min:8|confirmed'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user=User::create(
            [
                'username'=>$request->username,
                'nickname' => $request->nickname,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'data'=>$user,
                'access_token'=>$token,
                'token_type'=>'Bearer',
            ]
            );
    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['message'=>'Unauthorized'], 401);
        }

        auth()->user()->tokens()->delete(); //Delete every other token related to this user so there is only one active session

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'message' => 'Welcome '.$user->username,
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]
        );
    }

    public function logout(Request $request){
        $user = $request->user();
        auth()->user()->tokens()->delete();

        return [
            'message' => 'See you soon '.$user->username,
        ];
    }
}
