<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        //dd('hola');
        $codes=Code::all();
        $response=[];
        /*foreach($codes as $code){
            $code["code_pic"]=base64_encode($code['code_pic']);//we need to encode the binary data to be able to send as json
            $code["location"]=$code['location'];
            $response[]=$code->toArray();
        }*/
        $latitude = 37.165319;
        $longitude = -3.662096;
        $distance = 89; // Distancia en metros
        $codesWithinDistance = Code::withinDistanceOf($latitude, $longitude, $distance)->get();
        foreach($codesWithinDistance as $code){
            $code["code_pic"]=base64_encode($code['code_pic']);//we need to encode the binary data to be able to send as json
            $code["location"]=$code['location'];
            $response[]=$code->toArray();
        }
        //dd($codesWithinDistance);
/*
 SELECT ST_DISTANCE_SPHERE(
    ST_GeomFromText('POINT(37.165319 -3.662096)', 4326),
    ST_GeomFromText('POINT(37.165665 -3.662816)', 4326)
) AS distance_in_meters;
*/
        //dd($response);
        //return $codesWithinDistance;
        return response()->json($response);
    }
    /* VERSION CON MAP
    public function index()
{
    // Obtener todos los registros de la tabla codes
    $codes = Code::all();

    // Convertir los registros a arrays y agregar la representación base64 de code_pic
    $response = $codes->map(function ($code) {
        $data = $code->toArray();
        $data['code_pic'] = base64_encode($data['code_pic']); // Convertir a base64
        return $data;
    });

    // Devolver la respuesta como JSON
    return response()->json($response);
}
*/

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Code $code)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Code $code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Code $code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Code $code)
    {
        //
    }
}
