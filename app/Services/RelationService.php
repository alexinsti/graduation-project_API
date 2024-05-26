<?php

namespace App\Services;

use App\Models\Relation;

class RelationService
{
    public function getPrivilege($id)
    {
        return Relation::where('id', $id)->value('privilege');
    }

    public function createRelationAsOwner($userId, $codeId)
    {
        Relation::create([
            'id_user' => $userId,
            'id_code' => $codeId,
            'privilege' => 1,
        ]);
        return true;

    }

    public function createRelationAsWinner($userId, $codeId)
    {
        Relation::create([
            'id_user' => $userId,
            'id_code' => $codeId,
            'privilege' => 5,
        ]);
        return true;

    }

    public function getRelationByUserId($userId)
    {
        return Relation::where('id_user', $userId)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public function getRelationByCodeId($codeId)
    {
        return Relation::where('id_code', $codeId)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public function getRelationByPrivilege($privilege)
    {
        return Relation::where('privilege', $privilege)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public function getRelationsByOwner($userId)
    {
        $codeIds = Relation::where('id_user', $userId)
            ->where('privilege', 1)
            ->pluck('id_code');

        return Relation::whereIn('id_code', $codeIds)->get();
    }

    public function getRelationsByWinner($userId)
    {
        $codeIds = Relation::where('id_user', $userId)
            ->where('privilege', 5)
            ->pluck('id_code');

        return Relation::whereIn('id_code', $codeIds)->whereHas('code', function ($query) {
            $query->where('availability', 1);
        })->get();
    }

    public function deleteRelation($id)
    {
        Relation::findOrFail($id)->delete();
        return true;
    }

    public function deleteUserRelations($userId)
    {
        Relation::where('id_user', $userId)->delete();
        return true;
    }

    public function deleteCodeRelations($codeId)
    {
        Relation::where('id_code', $codeId)->delete();
        return true;
    }
}
