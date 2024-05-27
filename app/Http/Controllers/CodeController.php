<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Relation;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Validator;
use Illuminate\Support\Facades\DB;
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

        $idUser= auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = new Point($latitude, $longitude);
        $code_pic = $request->input('code_pic');
        $codeId = CodeService::createCode($code_pic, $location);

        RelationService::createRelationAsOwner($idUser, $codeId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
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

        $idCode = $request->input('id_code');
        $idUser = auth()->id();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = new Point($latitude, $longitude);
        $code_pic = $request->input('code_pic');
        $availability = $request->input('availability');
        $relation = Relation::where('id_user', $idUser)
            ->where('id_code', $idCode)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::updateCode($idCode, $code_pic, $location, $availability);
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
        $idCode = $request->input('id_code');
        $idUser = auth()->id();
        $relation = Relation::where('id_user', $idUser)
            ->where('id_code', $idCode)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::deleteCode($idCode);
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

        $idCode = $request->input('id_code');
        $idUser = auth()->id();
        $relation = Relation::where('id_user', $idUser)
            ->where('id_code', $idCode)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::setAvailabilityAsPublic($idCode);
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

        $idCode = $request->input('id_code');
        $idUser = auth()->id();
        $relation = Relation::where('id_user', $idUser)
            ->where('id_code', $idCode)
            ->first();

        if($relation!=null){
            $privilege = $relation->value('privilege');
            if($privilege==1){
                CodeService::setAvailabilityAsPrivate($idCode);
            }else{
                return response()->json(['message'=>'Unauthorized']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully!'
        ], 200);
    }

}
