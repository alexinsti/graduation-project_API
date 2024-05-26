<?php

namespace App\Services;

use App\Models\Code_to_validate;
use App\Models\Participation;
use App\Models\Relation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function updateUserData($nickname, $email, $profile_pic_Base64)
    {
        $profile_pic=Base64_decode($profile_pic_Base64);
        $user=User::create([
            'nickname' => $nickname,
            'email' => $email,
            'profile_pic' => $profile_pic,

        ]);
        return true;

    }

    public function deleteUSer($id)
    {
        Participation::where('id_user')->delete();
        Relation::where('id_user', $id)->delete();
        Code_to_validate::where('id_user', $id)->delete();
        User::findOrFail($id)->delete();
        return true;
    }
}
