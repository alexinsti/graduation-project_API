<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Relation;
use App\Models\Ticket;
use App\Services\GymkhanaService;
use App\Services\TicketService;
use App\Services\UserService;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Validator;
use App\Services\RelationService;
use App\Services\CodeService;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        $codes=Code::all();
        $response=[];
        foreach($codes as $code){
            $code["code_pic"]=base64_encode($code['code_pic']);//we need to encode the binary data to be able to send as json
            $code["location"]=$code['location'];
            $response[]=$code->toArray();
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'code_pic' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId= auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = new Point($latitude, $longitude, 4326);
        $code_pic = $request->input('code_pic');
        $codeId = CodeService::createCode($code_pic, $location);

        RelationService::createRelationAsOwner($userId, $codeId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function search(Request $request)
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
        $codes = CodeService::showCodesInAddingRange($userId, $latitude, $longitude);
        if ($codes) {
            $codes->load([
                'relations' => function ($query) {
                    $query->where('privilege', 1)
                        ->with(['user' => function ($query) {
                            $query->select('id', 'nickname', 'profile_pic');
                        }]);
                }
            ]);
        }

        foreach($codes as $code){
            $code->code_pic=base64_encode($code->code_pic);

            foreach ($code->relations as $relation) {
                $relation->user->profile_pic = base64_encode($relation->user->profile_pic);
            }

        }

        return response()->json($codes);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'code_pic' => 'string',
            'availability' => 'numeric|between:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code') ?? null;
        $userId = auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = null;
        if($latitude!=null && $longitude!=null) {
            $location = new Point($latitude, $longitude, 4326);
        }
        $code_pic = $request->input('code_pic') ?? null;
        $availability = $request->input('availability') ?? null;
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::updateCode($codeId, $code_pic, $location, $availability);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }
        $codeId = $request->input('id_code');
        $userId = auth()->id();
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::deleteCode($codeId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }


    /*
     *
     * SOME USEFUL METHODS
     *
    */
    public function setAvailabilityToPublic(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code');
        $userId = auth()->id();
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::setAvailabilityAsPublic($codeId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function setAvailabilityToPrivate(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code');
        $userId = auth()->id();
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::setAvailabilityAsPrivate($codeId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function follow(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code');
        $userId = auth()->id();
        $relation = Relation::where('id', $codeId)->where('id_user', $userId)->first();

        if($relation != null) {
            RelationService::follow($relation->id);
        }else{
            RelationService::createRelationAsFollower($userId, $codeId);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function unfollow(Request $request){
        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code');
        $userId = auth()->id();
        $relation = Relation::where('id_user', $userId)
            ->where('id_code', $codeId)
            ->first();

        if($relation != null) {
            if($relation->privilege == 6){
                $relation->delete();
            }else{
                RelationService::unfollow($relation->id);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function report(Request $request){

        $validator = Validator::make($request->all(), [
            'id_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $codeId = $request->input('id_code');
        $userId = auth()->id();

        CodeService::report($codeId, $userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

}
