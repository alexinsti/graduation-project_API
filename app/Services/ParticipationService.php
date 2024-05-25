<?php

namespace App\Services;

use App\Models\Participation;
use Illuminate\Support\Facades\Hash;

class ParticipationService
{
    public function getParticipationByUserId($userId)
    {
        return Participation::byUserId($userId)->get();
    }

    public function getParticipationByGymkhanaId($gymkhanaId)
    {
        return Participation::byGymkhanaId($gymkhanaId)->get();
    }

    public function getParticipationByPrivilege($privilege)
    {
        return Participation::byPrivilege($privilege)->get();
    }
    public function getParticipationsByOwner($user)
    {
        $gymkhanaIds = Participation::where('id_user', $user->id)
            ->where('privilege', 1)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)->get();
    }

    public function updateParticipationPrivilege($id, $privilege)
    {
        Participation::findOrFail($id)->update(['privilege'=>$privilege]);
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
