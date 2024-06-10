<?php

namespace App\Services;

use App\Mail\ReportMail;
use App\Models\Code_to_validate;
use App\Models\Participation;
use App\Models\Relation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{

    public static function updateUserData($id, $nickname, $email, $profile_pic_Base64)
    {
        //JUST TO SHOW WITHOUT API CONSUMER
        $profile_pic=Base64_decode("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");
        //$profile_pic=Base64_decode($profile_pic_Base64);
        $user = User::firstOrFail('id', $id);
        $user->update([
            'nickname' => $nickname ?? $user->nickname,
            'email' => $email ?? $user->email,
            'profile_pic' => $profile_pic ?? $user->profile_pic,

        ]);
        return true;

    }

    public static function deleteUser($id)
    {
        $user = User::findOrFail($id);

        Participation::where('id_user')->delete();
        Relation::where('id_user', $id)->delete();
        Code_to_validate::where('id_user', $id)->delete();
        Ticket::where('id_content', $id)->where('type', 3)->delete();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        $user->tokens()->delete();
        $user->delete();

        return true;
    }

    public static function report($id, $userId){

        $user = User::findOrFail($id);
        $img_base_64=base64_encode($user->profile_pic);
        $token = Str::random(60);
        if($user->reported != 1){
            Ticket::create([
                'id_content' => $user->id,
                'type' => 3,
                'token' => $token
            ]);
            $user->update(['reported' => 1]);
            Mail::to('ticketsgymkhanamanager@gmail.com')->send(new ReportMail($user, $img_base_64, $userId, 3, $token));
        }
    }

    public static function closeReportTicket($id){
        $user = User::findOrFail($id);
        if($user->reported != 0){
            $user->update(['reported' => 0]);
        }
    }

    public static function resetPicture($id){
        $defaultPic=base64_decode("iVBORw0KGgoAAAANSUhEUgAABIAAAAKICAIAAACHSRZaAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAA2zSURBVHhe7dcxAQAADMOg+TfducgFLrgBAACQEDAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAEtsD9S7Spuq5RpkAAAAASUVORK5CYII=");
        User::where('id', $id)->update([
            'profile_pic' => base64_decode($defaultPic)
        ]);
    }

    public static function resetNickname($id){
        User::where('id', $id)->update([
            'nickname' => 'RemovedNickname'
        ]);
    }
}
