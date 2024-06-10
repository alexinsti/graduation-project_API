<?php

namespace App\Services;

use App\Models\Participation;

class ParticipationService
{
    public static function getPrivilege($id)
    {
        return Participation::where('id', $id)->value('privilege');
    }

    public static function createParticipationAsOwner($userId, $gymkhanaId)
    {
        Participation::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'privilege' => 1,
        ]);
        return true;

    }

    public static function createParticipationAsPlayer($userId, $gymkhanaId)
    {
        Participation::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'privilege' => 4,
        ]);
        return true;

    }

    public static function createParticipationAsFollower($userId, $gymkhanaId)
    {
        Participation::create([
            'id_user' => $userId,
            'id_gymkhana' => $gymkhanaId,
            'privilege' => 6,
        ]);
        return true;

    }

    public static function getParticipationByUserId($userId)
    {
        return Participation::where('id_user', $userId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public static function getParticipationByGymkhanaId($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public static function getParticipationByPrivilege($privilege)
    {
        return Participation::where('privilege', $privilege)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }
    /*
     *
     * Get the user's participations as owner
     *
     */
    public static function getParticipationsWhereUserIsOwner($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 1)
            ->get();

    }

    /*
     *
     * Get the user's participations as admin
     *
     */
    public static function getParticipationsWhereUserIsAdmin($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 2)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    /*
     *
     * Get the user's participations as supervisor
     *
     */
    public static function getParticipationsWhereUserIsSupervisor($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 3)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    /*
     *
     * Get the user's participations as player
     *
     */
    public static function getParticipationsWhereUserIsPlayer($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 4)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                ->where('state', '!=', 3);
            })->get();

    }

    /*
     *
     * Get the user's participations as winner
     *
     */
    public static function getParticipationsWhereUserIsWinner($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 5)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->get();

    }

    /*
     *
     * Get the user's participations as follower
     *
     */
    public static function getParticipationsWhereUserIsFollower($userId)
    {
        return Participation::where('id_user', $userId)
            ->where('privilege', 6)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1)
                    ->where('state', '!=', 3);
            })->get();

    }


    /*
     *
     * Get all the participations related to a given gymkhana an user based on the privilege it holds
     *
     */
    public static function getParticipantsOf($userId, $gymkhanaId) //user lo pilla de auth en el controller
    {
        $privilege = Participation::where('id_user', $userId)
            ->where('id_gymkhana', $gymkhanaId)
            ->first()->privilege;
        $participations=null;
        switch ($privilege){
            case 1:
                $participations = Participation::where('id_gymkhana', $gymkhanaId)
                    ->where('privilege', '>', 1)->get();
                break;

            case 2:
                $participations = Participation::where('id_gymkhana', $gymkhanaId)
                    ->where('privilege', '>=', 2)->get();
                break;


            case 3:
                $participations = Participation::where('id_gymkhana', $gymkhanaId)
                    ->where('privilege', '>=', 3)->get();
                break;


            case 4:
                $participations = Participation::where('id_gymkhana', $gymkhanaId)
                    ->where('privilege', 4)->get();
                break;


            case 5:
                $participations = Participation::where('id_gymkhana', $gymkhanaId)
                    ->where('privilege', 5)->get();
                break;
        }

        return $participations;
    }

    /*
     *
     * Get all the participations related to gymkhanas an user own
     *
     */
    public static function getParticipationsByOwner($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 1)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)->get();
    }

    /*
     *
     * Get all the participations related to gymkhanas I admin, but the ones above me
     *
     */
    public static function getParticipationsByAdmin($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 2)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    /*
     *
     * Get all the participations related to gymkhanas I supervise, but the ones above me
     *
     */
    public static function getParticipationsBySupervisor($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 3)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    /*
     *
     * Get all the participations related to gymkhanas where I play, but the ones above me
     *
     */
    public static function getParticipationsByPlayer($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 4)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    /*
     *
     * Get all the participations related to gymkhanas I have won, but the ones above me
     *
     */
    public static function getParticipationsByWinner($userId)
    {
        $gymkhanaIds = Participation::where('id_user', $userId)
            ->where('privilege', 5)
            ->pluck('id_gymkhana');

        return Participation::whereIn('id_gymkhana', $gymkhanaIds)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    /*
     *
     * Get all the participations related to a determined gymkhana the owner sees, but the ones above me
     *
     */
    public static function getParticipationsToManageAsOwnerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->get();
    }

    public static function getParticipationsToManageAsAdminOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', '>=', 2)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();

    }

    public static function getParticipationsToManageAsSupervisorOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', '>=', 3)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public static function getParticipationsToManageAsPlayerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 4)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public static function getParticipationsToManageAsWinnerOf($gymkhanaId)
    {
        return Participation::where('id_gymkhana', $gymkhanaId)
            ->where('privilege', 5)
            ->whereHas('gymkhana', function ($query) {
                $query->where('availability', 1);
            })->get();
    }

    public static function updateParticipationPrivilege($id, $privilege)
    {
        Participation::findOrFail($id)->update(['privilege' => $privilege]);
        return true;
    }

    public static function setPrivilegeAsOwner($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 1]);
        return true;
    }

    public static function setPrivilegeAsAdmin($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 2]);
        return true;
    }

    public static function setPrivilegeAsSupervisor($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 3]);
        return true;
    }

    public static function setPrivilegeAsPlayer($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 4]);
        return true;
    }

    public static function setPrivilegeAsWinner($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 5]);
        return true;
    }

    public static function setPrivilegeAsFollower($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 6]);
        return true;
    }

    public static function blockUserParticipation($id)
    {
        Participation::findOrFail($id)->update(['privilege' => 101]);
        return true;
    }


    public static function deleteParticipation($id)
    {
        Participation::findOrFail($id)->delete();
        return true;
    }

    public static function deleteUserParticipations($userId)
    {
        Participation::where('id_user', $userId)->delete();
        return true;
    }

    public static function deleteGymkhanaParticipations($gymkhanaId)
    {
        Participation::where('id_gymkhana', $gymkhanaId)->delete();
        return true;
    }

}
