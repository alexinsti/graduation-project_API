<?php

namespace App\Http\Controllers;

use App\Models\Gymkhana;
use App\Models\Participation;
use App\Services\Code_to_validateService;
use App\Services\GymkhanaService;
use App\Services\ParticipationService;
use Illuminate\Http\Request;
use Validator;
use function Symfony\Component\String\u;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participations=Participation::all();
        $response=$participations->toArray();

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId= auth()->id();
        $gymkhanaId = $request->input('id_gymkhana');
        $gymkhana = GymKhana::find($gymkhanaId);
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation === null){
            if($gymkhana->state == 1){
                ParticipationService::createParticipationAsFollower($userId, $gymkhanaId);
            }elseif ($gymkhana->state == 2){
                ParticipationService::createParticipationAsPlayer($userId, $gymkhanaId);
            }
        }elseif($participation->privilege == 6){
            $participation->update(['privilege' => 4]);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function showParticipationsWhereIAmOwner()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsOwner($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname'); // No need profile pic beacuse it's the user one
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
        }

        return response()->json($participations);
    }

    public function showParticipationsWhereIAmAdmin()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsAdmin($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }
        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    public function showParticipationsWhereIAmSupervisor()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsSupervisor($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    public function showParticipationsWhereIAmPlayer()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsPlayer($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    public function showParticipationsWhereIAmWinner()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsWinner($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    public function showParticipationsWhereIAmFollower()
    {
        $userId = auth()->id();
        $participations = ParticipationService::getParticipationsWhereUserIsFollower($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->gymkhana->gymkhana_pic=base64_encode($participation->gymkhana->gymkhana_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

/*
 *                                          make{ROLE} section
 *
 * No need to check the relative privilege between users because its only shown to an user participations with
 * their same or lower privilege so a supervisor would not be able to see an admin to select it as objetive of a makeSMTH
 * function
 *
 * */
    public function makeOwner(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>1){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsOwner($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    public function makeAdmin(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>2){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsAdmin($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    public function makeSupervisor(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>3){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsSupervisor($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    public function makePlayer(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>3){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsPlayer($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    public function makeWinner(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>3){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsWinner($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    public function makeFollower(Request  $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($privilege>3){
            return response()->json(['message'=>'Unauthorized']);
        }

        ParticipationService::setPrivilegeAsFollower($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

    /*****************************************************************************************************/
    /*****************************************************************************************************/

    public function blockUser(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $userToModify = $request->input('id_user');
        $gymkhanaId = $request->input('id_gymkhana');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participation = Participation::where('id_user', $userToModify)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if ($privilege > 2) {
            return response()->json(['message' => 'Unauthorized']);
        }

        ParticipationService::blockUserParticipation($participation->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $gymkhanaId = $request->input('id_gymkhana');
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation != null){
            if($participation->privilege == 1){
                GymkhanaService::deleteGymkhana($gymkhanaId);
            }else{
                Code_to_validateService::deleteUserCodes_to_validateInGymkhana($userId, $gymkhanaId);
                ParticipationService::deleteParticipation($participation->id);
            }
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }
}
