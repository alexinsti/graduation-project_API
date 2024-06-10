<?php

use App\Http\Controllers\CodeToValidateController;
use App\Http\Controllers\GymkhanaController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\AuthController;

/*
Route::get('user', function (Request $request) {
    $user = $request->user();

    return response()->json($user, 200);
});
*/

Route::middleware(['auth:sanctum'])->group(function () {
    //AUTH
    Route::get('logout', [AuthController::class, 'logout']);

    //CODES
    Route::get('codes', [CodeController::class, 'index']);
    Route::post('code/create', [CodeController::class, 'create']);
    Route::post('code/update', [CodeController::class, 'update']);
    Route::post('code/destroy', [CodeController::class, 'destroy']);
    Route::post('code/search', [CodeController::class, 'search']);
    Route::patch('code/follow', [CodeController::class, 'follow']);
    Route::patch('code/unfollow', [CodeController::class, 'unfollow']);
    Route::patch('code/setAvailabilityToPublic', [CodeController::class, 'setAvailabilityToPublic']);
    Route::patch('code/setAvailabilityToPrivate', [CodeController::class, 'setAvailabilityToPrivate']);
    Route::patch('code/report', [CodeController::class, 'report']);

    //GYMKHANAS
    Route::get('gymkhanas', [GymkhanaController::class, 'index']);
    Route::post('gymkhana/create', [GymkhanaController::class, 'create']);
    Route::post('gymkhana/update', [GymkhanaController::class, 'update']);
    Route::post('gymkhana/destroy', [GymkhanaController::class, 'destroy']);
    Route::post('gymkhana/search', [GymkhanaController::class, 'search']);
    Route::patch('gymkhana/setAvailabilityToPublic', [GymkhanaController::class, 'setAvailabilityToPublic']);
    Route::patch('gymkhana/setAvailabilityToPrivate', [GymkhanaController::class, 'setAvailabilityToPrivate']);
    Route::patch('gymkhana/setStateToPublished', [GymkhanaController::class, 'setStateToPublished']);
    Route::patch('gymkhana/setStateToOngoing', [GymkhanaController::class, 'setStateToOngoing']);
    Route::patch('gymkhana/setStateToClosed', [GymkhanaController::class, 'setStateToClosed']);
    Route::patch('gymkhana/report', [GymkhanaController::class, 'report']);

    //USERS
    Route::get('/user', [UserController::class, 'user']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::post('user/destroy', [UserController::class, 'destroy']);

    //PARTICIPATIONS
    Route::post('participation/join', [ParticipationController::class, 'create']);
    Route::post('participation/destroy', [ParticipationController::class, 'destroy']);
    Route::get('participation/showParticipationsWhereIAmOwner', [ParticipationController::class, 'showParticipationsWhereIAmOwner']);
    Route::get('participation/showParticipationsWhereIAmAdmin', [ParticipationController::class, 'showParticipationsWhereIAmAdmin']);
    Route::get('participation/showParticipationsWhereIAmSupervisor', [ParticipationController::class, 'showParticipationsWhereIAmSupervisor']);
    Route::get('participation/showParticipationsWhereIAmPlayer', [ParticipationController::class, 'showParticipationsWhereIAmPlayer']);
    Route::get('participation/showParticipationsWhereIAmWinner', [ParticipationController::class, 'showParticipationsWhereIAmWinner']);
    Route::get('participation/showParticipationsWhereIAmFollower', [ParticipationController::class, 'showParticipationsWhereIAmFollower']);
    //Route::patch('participation/makeOwner', [ParticipationController::class, 'makeOwner']);//There is no point in a makeOwner function
    Route::patch('participation/makeAdmin', [ParticipationController::class, 'makeAdmin']);
    Route::patch('participation/makeSupervisor', [ParticipationController::class, 'makeSupervisor']);
    Route::patch('participation/makePlayer', [ParticipationController::class, 'makePlayer']);
    Route::patch('participation/makeWinner', [ParticipationController::class, 'makeWinner']);
    Route::patch('participation/blockUser', [ParticipationController::class, 'blockUser']);

    //RELATIONS
    Route::post('relation/destroy', [RelationController::class, 'destroy']);
    Route::get('relation/showRelationsWhereIAmOwner', [RelationController::class, 'showRelationsWhereIAmOwner']);
    Route::get('relation/showRelationsWhereIAmWinner', [RelationController::class, 'showRelationsWhereIAmWinner']);
    Route::get('relation/showRelationsWhereIAmFollower', [RelationController::class, 'showRelationsWhereIAmFollower']);

    //CODES_TO_VALIDATE
    Route::post('code_to_validate/addTo', [CodeToValidateController::class, 'addTo']);
    Route::post('code_to_validate/validate', [CodeToValidateController::class, 'validate']);
    Route::post('code_to_validate/destroy', [CodeToValidateController::class, 'destroy']);
    //Route::get('code_to_validate/search', [CodeToValidateController::class, 'search']);
    Route::get('code_to_validate/showCodesToAdd', [CodeToValidateController::class, 'showCodesToAdd']);
    Route::get('code_to_validate/showCodesToValidate', [CodeToValidateController::class, 'showCodesToValidate']);
    Route::get('code_to_validate/showUsersValidatedOnGymkhana', [CodeToValidateController::class, 'showUsersValidatedOnGymkhana']);


});



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
