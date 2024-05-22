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
});

//Basic
Route::get('codes', [CodeController::class, 'index']);

//User control
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
