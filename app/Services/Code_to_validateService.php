<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Code_to_validate;
use App\Models\Participation;

class Code_to_validateService
{
    public function createCode_to_validateAsOwner($userId, $gymkhanaId, $codeId)
    {
        Code_to_validate::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'code_id' => $codeId,
            'privilege' => 1,
        ]);
        return true;

    }

    public function createCode_to_validateAsPlayer($userId, $gymkhanaId, $codeId)
    {
        $code_to_validate=Code_to_validate::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'id_code' => $codeId,
            'privilege' => 4,
        ]);
        return $code_to_validate->id;

    }

    public function getCode_to_validateByUserId($userId)
    {
        return Code_to_validate::where('id_user', $userId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getCode_to_validateByGymkhanaId($gymkhanaId)
    {
        return Code_to_validate::where('id_gymkhana', $gymkhanaId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getCode_to_validateByPrivilege($privilege)
    {
        return Code_to_validate::where('privilege', $privilege)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getCodes_to_validateByOwner($userLatitude, $userLongitude)
    {
        $maxDistance = 10;

        return Code::where('availability', 1)
            ->withinDistanceOf($userLatitude, $userLongitude, $maxDistance);
    }

    public function getCode_to_validateAsPlayerOf($gymkhanaId, $userId, $userLatitude, $userLongitude)
    {
        $maxDistance = 5;

        $ownerId = Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 1)->value('id_user');

        $ownerCodesIds = Code_to_validate::where('id_user', $ownerId)
            ->where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 1)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->pluck('id_code');

        $userValidatedCodesIds = Code_to_validate::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 4)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->pluck('id_code');

        $userCodesToValidateIds = $ownerCodesIds->diff($userValidatedCodesIds);

        return Code::whereIn('id', $userCodesToValidateIds)
            ->withinDistanceOf($userLatitude, $userLongitude, $maxDistance)
            ->get();
    }

    public function getValidated_codesAsOwnerOf($gymkhanaId, $userToCheckId)
    {
        return Code_to_validate::where('id_gymkhana', $gymkhanaId)
            ->where('id_user', $userToCheckId)
            ->where('privilege', 4);
    }

    public function getValidated_codesAsAdminOf($gymkhanaId, $userToCheckId)
    {
        return Code_to_validate::where('id_gymkhana', $gymkhanaId)
            ->where('id_user', $userToCheckId)
            ->where('privilege', '>=', 2)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    public function geValidated_codesAsSupervisorOf($gymkhanaId, $userToCheckId)
    {
        return Code_to_validate::where('id_gymkhana', $gymkhanaId)
            ->where('id_user', $userToCheckId)
            ->where('privilege', '>=', 3)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getValidated_codesAsPlayer($userToCheckId, $gymkhanaId)
    {
        return Code_to_validate::where('id_gymkhana', $gymkhanaId)
            ->where('id_user', $userToCheckId)
            ->where('privilege', 4)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getValidated_codesAsWinnerOf($gymkhanaId, $userId)
        {
            return Code_to_validate::where('id_gymkhana', $gymkhanaId)
                ->where('id_user', $userId)
                ->where('privilege', 5)
                ->whereHas('gymkhana', function ($query) {
                    $query->where('availability', 1)
                        ->where('state', '!=', 3);
                })->whereHas('code', function ($query) {
                        $query->where('availability', 1);
                })
                ->get();
        }

    public function deleteCode_to_validate($id)
    {
        Code_to_validate::findOrFail($id)->delete();
        return true;
    }

    public function deleteUserCodes_to_validate($userId, $gymkhanaId)
    {
        Code_to_validate::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->delete();
        return true;
    }

    public function deleteGymkhanaCodes_to_validate($gymkhanaId)
    {
        Code_to_validate::where('id_gymkhana', $gymkhanaId)->delete();
        return true;
    }
}
