<?php

namespace App\Services;

use App\Models\Participation;

class ParticipationService
{
    public function getPrivilege($id)
    {
        return Participation::where('id', $id)->value('privilege');
    }

    public function createParticipationAsOwner($userId, $gymkhanaId)
    {
        Participation::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'privilege' => 1,
        ]);
        return true;

    }

    public function createParticipationAsPlayer($userId, $gymkhanaId)
    {
        Participation::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'privilege' => 4,
        ]);
        return true;

    }

    public function getParticipationByUserId($userId)
    {
        return Participation::where('id_user', $userId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationByGymkhanaId($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationByPrivilege($privilege)
    {
        return Participation::where('privilege', $privilege)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsByOwner($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 1)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)->get();
    }

    public function getParticipationsByAdmin($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 2)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsBySupervisor($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 3)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsByPlayer($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 4)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsByWinner($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 5)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsAsOwnerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->get();
    }

    public function getParticipationsAsAdminOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', '>=', 2)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    public function getParticipationsAsSupervisorOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', '>=', 3)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsAsPlayerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 4)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function getParticipationsAsWinnerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 5)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public function updateParticipationPrivilege($id, $privilege)
    {
        Participation::findOrFail($id)->update(['privilege' => $privilege]);
        return true;
    }

    public function setPrivilegeAsOwner($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 1]);
        return true;
    }

    public function setPrivilegeAsAdmin($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 2]);
        return true;
    }

    public function setPrivilegeAsSupervisor($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 3]);
        return true;
    }

    public function setPrivilegeAsPlayer($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 4]);
        return true;
    }

    public function setPrivilegeAsWinner($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 5]);
        return true;
    }

    public function blockUserParticipation($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 101]);
        return true;
    }


    public function deleteParticipation($id)
    {
        Participation::findOrFail($id)->delete();
        return true;
    }

    public function deleteUserParticipations($userId)
    {
        Participation::where('id_user', $userId)->delete();
        return true;
    }

    public function deleteGymkhanaParticipations($gymkhanaId)
    {
        Participation::where('id_gymkhana', $gymkhanaId)->delete();
        return true;
    }

}
