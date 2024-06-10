<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Services\Code_to_validateService;
use App\Services\CodeService;
use App\Services\RelationService;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relations=Relation::all();
        $response=$relations->toArray();

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function showRelationsWhereIAmOwner()
    {
        $userId = auth()->id();
        $participations = RelationService::getRelationsWhereUserIsOwner($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname'); // No need profile pic beacuse it's the user one
                },
                'code' => function ($query) {
                    $query->select('id', 'code_pic');
                }
            ]);
        }
        foreach($participations as $participation){
            $participation->code->code_pic=base64_encode($participation->code->code_pic);
        }

        return response()->json($participations);
    }

    public function showRelationsWhereIAmWinner()
    {
        $userId = auth()->id();
        $participations = RelationService::getRelationsWhereUserIsWinner($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'code' => function ($query) {
                    $query->select('id', 'code_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->code->code_pic=base64_encode($participation->code->code_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    public function showRelationsWhereIAmFollower()
    {
        $userId = auth()->id();
        $participations = RelationService::getRelationsWhereUserIsFollower($userId);
        if($participations) {
            $participations->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'code' => function ($query) {
                    $query->select('id', 'code_pic');
                }
            ]);
        }

        foreach($participations as $participation){
            $participation->code->code_pic=base64_encode($participation->code->code_pic);
            $participation->user->profile_pic=base64_encode($participation->user->profile_pic);
        }

        return response()->json($participations);
    }

    /**
     * Remove the specified resource from storage.
     */
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
        $codeId = $request->input('id_code');
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation != null){
            if($relation->privilege == 1){
                CodeService::deleteCode($codeId);
            }else{
                Code_to_validateService::deleteUserCodes_to_validateInGymkhana($userId, $codeId);
                RelationService::deleteRelation($relation->id);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

}
