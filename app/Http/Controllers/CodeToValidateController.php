<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Code_to_validate;
use App\Models\Participation;
use App\Models\Relation;
use App\Services\Code_to_validateService;
use App\Services\CodeService;
use App\Services\ParticipationService;
use App\Services\RelationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Validator;

class CodeToValidateController extends Controller
{

    public function addTo(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
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
        $gymkhanaId = $request->get('id_gymkhana');

        Code_to_validateService::createCode_to_validateAsOwner($userId, $gymkhanaId, $codeId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function validate(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $codeId = $request->input('id_code');
        $gymkhanaIdsWhereUserIsPlayer = ParticipationService::getParticipationsWhereUserIsPlayer($userId)->pluck('id_gymkhana');

        RelationService::createRelationAsWinner($userId, $codeId);

        foreach ($gymkhanaIdsWhereUserIsPlayer as $gymkhanaId) {
            $codeInGymkhanaIds = Code_to_validate::where('privilege', 1)
                ->where('id_gymkhana', $gymkhanaId)
                ->where('id_code', $codeId)
                ->get();
            if(!$codeInGymkhanaIds->isEmpty()){
                Code_to_validateService::createCode_to_validateAsPlayer($userId, $gymkhanaId, $codeId);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }
    /*public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $usersGymkhanaIds = Participation::where('id_user', $userId)->pluck('id_gymkhana');

        $codes = new Collection();
        foreach ($usersGymkhanaIds as $gymkhanaId){
            $gymkhanaCodes = Code_to_validateService::getCode_to_validateAsPlayerOf($userId, $gymkhanaId, $latitude, $longitude);
            $codes = $codes->merge($gymkhanaCodes);

        }

        return response()->json($codes);

    }*/

    public function showCodesToAdd(Request $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $gymkhanaId = $request->get('id_gymkhana');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $codesToAdd = Code_to_validateService::getCodesToAddAsOwnerOf($userId, $gymkhanaId, $latitude, $longitude);

        foreach ($codesToAdd as $codeToAdd) {
            $codeToAdd->code_pic = base64_encode($codeToAdd->code_pic);
        }

        return $codesToAdd;

    }

    public function showCodesToValidate(Request $request){
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $gymkhanaIdsWhereUserIsPlayer = ParticipationService::getParticipationsWhereUserIsPlayer($userId)->pluck('id_gymkhana');

        $codesToValidate = new Collection();
        foreach ($gymkhanaIdsWhereUserIsPlayer as $gymkhanaId) {
            $gymkhanaCodes = Code_to_validateService::getCode_to_validateAsPlayerOf($userId, $gymkhanaId, $latitude, $longitude);
            $codesToValidate = $codesToValidate->merge($gymkhanaCodes);
        }

        foreach ($codesToValidate as $codeToValidate) {
            $codeToValidate->code_pic = base64_encode($codeToValidate->code_pic);
        }

        return $codesToValidate;
    }

    public function showUsersValidatedOnGymkhana(Request $request){
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
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
        $userToCheckId = $request->input('id_user');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->value('privilege');
        $userValidated_codes= new Collection();
        switch ($privilege){
            case 1:
                $userValidated_codes = Code_to_validateService::getUserValidated_codesAsOwnerOf($gymkhanaId, $userToCheckId);
                break;

            case 2:
                $userValidated_codes = Code_to_validateService::getUserValidated_codesAsAdminOf($gymkhanaId, $userToCheckId);
                break;


            case 3:
                $userValidated_codes = Code_to_validateService::getUserValidated_codesAsSupervisorOf($gymkhanaId, $userToCheckId);
                break;

        }

        if ($userValidated_codes) {
            $userValidated_codes->load([
                'user' => function ($query) {
                    $query->select('id', 'nickname', 'profile_pic');
                },
                'gymkhana' => function ($query) {
                    $query->select('id', 'name', 'password', 'description', 'gymkhana_pic');
                },
                'code' => function ($query) {
                $query->select('id', 'code_pic');
                }
            ]);
        }

        foreach($userValidated_codes as $userValidated_code){
            $userValidated_code->user->profile_pic=base64_encode($userValidated_code->user->profile_pic);
            $userValidated_code->gymkhana->gymkhana_pic=base64_encode($userValidated_code->user->gymkhana_pic);
            $userValidated_code->code->code_pic=base64_encode($userValidated_code->code->code_pic);
        }

        return response()->json($userValidated_codes);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
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
        $codeId = $request->input('id_code');
        $gymkhanaId = $request->input('id_gymkhana');
        $userToCheckId = $request->input('id_user');
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->value('privilege');

        if($privilege<=2){
            Code_to_validate::where('id_user', $userToCheckId)
                ->where('id_gymkhana', $gymkhanaId)
                ->where('id_code', $codeId)
                ->delete();
        }else{
            return response()->json(['message'=>'Unauthorized']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }


}
