<?php

namespace App\Services;

use App\Models\Relation;

class RelationService
{
    public static function getPrivilege($id)
    {
        return Relation::where('id', $id)->value('privilege');
    }

    public static function createRelationAsOwner($userId, $codeId)
    {
        Relation::create([
            'id_user' => $userId,
            'id_code' => $codeId,
            'privilege' => 1,
            'follow' => 0,
        ]);
        return true;

    }

    public static function createRelationAsWinner($userId, $codeId)
    {
        Relation::create([
            'id_user' => $userId,
            'id_code' => $codeId,
            'privilege' => 5,
            'follow' => 0,
        ]);
        return true;

    }

    public static function createRelationAsFollower($userId, $codeId)
    {
        Relation::create([
            'id_user' => $userId,
            'id_code' => $codeId,
            'privilege' => 6,
            'follow' => 1,
        ]);
        return true;

    }

    public static function getRelationByUserId($userId)
    {
        return Relation::where('id_user', $userId)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public static function getRelationByCodeId($codeId)
    {
        return Relation::where('id_code', $codeId)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public static function getRelationByPrivilege($privilege)
    {
        return Relation::where('privilege', $privilege)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public static function getRelationsByOwner($userId)
    {
        $codeIds = Relation::where('id_user', $userId)
            ->where('privilege', 1)
            ->pluck('id_code');

        return Relation::whereIn('id_code', $codeIds)->get();
    }

    public static function getRelationsByWinner($userId)
    {
        $codeIds = Relation::where('id_user', $userId)
            ->where('privilege', 5)
            ->pluck('id_code');

        return Relation::whereIn('id_code', $codeIds)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    /*
     *
     * Get the user's relations as owner
     *
     */
    public static function getRelationsWhereUserIsOwner($userId)
    {
        return Relation::where('id_user', $userId)
            ->where('privilege', 1)
            ->get();

    }

    /*
     *
     * Get the user's relations as winner
     *
     */
    public static function getRelationsWhereUserIsWinner($userId)
    {
        return Relation::where('id_user', $userId)
            ->where('privilege', 5)
            ->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    /*
     *
     * Get the user's relations as follower
     *
     */
    public static function getRelationsWhereUserIsFollower($userId)
    {
        return Relation::where('id_user', $userId)
            ->where('privilege', 6)
            ->whereHas('code', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    public static function updatePrivilege($id, $privilege){
        Relation::findOrFail($id)->update(['privilege' => $privilege]);
        return true;
    }

    public static function setPrivilegeAsOwner($id){
        Relation::findOrFail($id)->update(['privilege' => 1]);
        return true;
    }

    public static function setPrivilegeAsWinner($id){
        Relation::findOrFail($id)->update(['privilege' => 5]);
        return true;
    }

    public static function setPrivilegeAsFollower($id){
        Relation::findOrFail($id)->update(['privilege' => 6]);
        return true;
    }

    public static function follow($id){
        Relation::findOrFail($id)->update(['follow' => 1]);
        return true;
    }

    public static function unfollow($id){
        Relation::findOrFail($id)->update(['follow' => 0]);
        return true;
    }

    public static function deleteRelation($id)
    {
        Relation::findOrFail($id)->delete();
        return true;
    }

    public static function deleteUserRelations($userId)
    {
        Relation::where('id_user', $userId)->delete();
        return true;
    }

    public static function deleteCodeRelations($codeId)
    {
        Relation::where('id_code', $codeId)->delete();
        return true;
    }
}
