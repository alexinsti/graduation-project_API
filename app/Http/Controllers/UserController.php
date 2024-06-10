<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function user()
    {
        $user = auth()->user();
        return [
            'id' => $user->id,
            'username' => $user->username,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'profile_pic' => base64_encode($user->profile_pic),
        ];
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'required|string',
            'email'=>'required|string|email|unique:users',
            'profile_pic' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $nickname = $request->input('nickname');
        $email = $request->input('email');
        $profile_pic = $request->input('profile_pic');

        UserService::updateUserData($userId, $nickname, $email, $profile_pic);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {

        $userId = auth()->id();

        UserService::deleteUser($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }
}
