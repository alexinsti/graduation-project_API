<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\AuthController;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('logout', [AuthController::class, 'logout']);
    //CODES
    Route::get('codes', [CodeController::class, 'index']);
    Route::post('createCode', [CodeController::class, 'create']);
    Route::post('destroyCode', [CodeController::class, 'destroy']);
    Route::post('updateCode', [CodeController::class, 'update']);
    Route::patch('setAvailabilityToPublic', [CodeController::class, 'setAvailabilityToPublic']);
    Route::patch('setAvailabilityToPrivate', [CodeController::class, 'setAvailabilityToPrivate']);


});



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
