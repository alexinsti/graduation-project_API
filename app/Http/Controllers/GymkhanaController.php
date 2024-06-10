<?php

namespace App\Http\Controllers;

use App\Models\Gymkhana;
use App\Models\Participation;
use App\Services\GymkhanaService;
use App\Services\ParticipationService;
use Illuminate\Http\Request;
use App\Models\Code;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Services\CodeService;

class GymkhanaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        $gymkhanas=Gymkhana::all();
        $response=[];
        foreach($gymkhanas as $gymkhana){
            $gymkhana["gymkhana_pic"]=base64_encode($gymkhana['gymkhana_pic']);//we need to encode the binary data to be able to send as json
            $gymkhana["location"]=$gymkhanas['location'];
            $response[]=$gymkhanas->toArray();
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'string',
            'description' => 'string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'gymkhana_pic' => 'string',
            'availability' => 'numeric|between:0,1',
            'state' => 'numeric|between:1,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId= auth()->id();
        $name = $request->input('name');
        $password = $request->input('password') ?? null;
        $description = $request->input('description') ?? null;
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $starting_point = new Point($latitude, $longitude, 4326);
        $gymkhana_pic = $request->input('gymkhana_pic');
        $availability = $request->input('availability');
        $state = $request->input('state');

        $gymkhanaId = GymkhanaService::createGymkhana($gymkhana_pic, $name, $password, $description, $starting_point, $state, $availability);
        ParticipationService::createParticipationAsOwner($userId, $gymkhanaId);

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
        $gymkhanas = GymkhanaService::showGymkhanasInJoiningRange($userId, $latitude, $longitude);
        if ($gymkhanas) {
            $gymkhanas->load([
                'participations' => function ($query) {
                    $query->where('privilege', 1)
                        ->with(['user' => function ($query) {
                            $query->select('id', 'nickname', 'profile_pic');
                        }]);
                }
            ]);
        }

        foreach($gymkhanas as $gymkhana){
            $gymkhana->gymkhana_pic=base64_encode($gymkhana->gymkhana_pic);

            foreach ($gymkhana->participations as $participation) {
                $participation->user->profile_pic = base64_encode($participation->user->profile_pic);
            }
        }

        return response()->json($gymkhanas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_gymkhana'=> 'required|numeric',
            'name' => 'string',
            'password' => 'string',
            'description' => 'string',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
            'gymkhana_pic' => 'string',
            'availability' => 'numeric|between:0,1',
            'state' => 'numeric|between:1,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId= auth()->id();
        $gymkhanaId = $request->input('id_gymkhana') ?? null;
        $name = $request->input('name') ?? null;
        $password = $request->input('password') ?? null;
        $description = $request->input('description');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $starting_point = null;
        if($latitude!=null && $longitude!=null) {
            $starting_point = new Point($latitude, $longitude, 4326);
        }
        $gymkhana_pic = $request->input('gymkhana_pic') ?? null;
        $availability = $request->input('availability') ?? null;
        $state = $request->input('state') ?? null;
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::updateGymkhana($gymkhanaId, $gymkhana_pic, $name, $password, $description, $starting_point, $state, $availability);
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
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }
        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::deleteGymkhana($gymkhanaId);
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
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::setAvailabilityToPublic($gymkhanaId);
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
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::setAvailabilityToPrivate($gymkhanaId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function setStateToPublished(Request $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::setStateToPublished($gymkhanaId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function setStateToOngoing(Request $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::setStateToOngoing($gymkhanaId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

    public function setStateToClosed(Request $request){
        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        $participation = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first();

        if($participation!=null){
            $privilege = $participation->value('privilege');
            if($privilege==1){
                GymkhanaService::setStateToClosed($gymkhanaId);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }


    public function report(Request $request){

        $validator = Validator::make($request->all(), [
            'id_gymkhana' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $gymkhanaId = $request->input('id_gymkhana');
        $userId = auth()->id();
        GymkhanaService::report($gymkhanaId, $userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);

    }

}
