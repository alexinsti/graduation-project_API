<?php

namespace App\Services;

use App\Models\Code_to_validate;
use App\Models\Gymkhana;
use App\Models\Participation;

class GymkhanaServices
{

    public function getGymkhanaByAvailability($availability)
    {
        return Gymkhana::where('availability', $availability)->get();
    }

    public function updateGymkhanaAvailability($id, $availability)
    {
        Gymkhana::findOrFail($id)->update(['availability'=>$availability]);
        return true;
    }
    public function setAvailabilityToPublic($id)
    {
        Gymkhana::findOrFail($id)->update(['availability' => 1]);
        return true;
    }
    public function setAvailabilityToPrivate($id)
    {
        Gymkhana::findOrFail($id)->update(['availability' => 0]);
        return true;
    }

    public function updateGymkhanaState($id, $availability)
    {
        Gymkhana::findOrFail($id)->update(['availability'=>$availability]);
        return true;
    }
    public function setStateToPublished($id)
    {
        Gymkhana::findOrFail($id)->update(['availability' => 1]);
        return true;
    }
    public function setStateToOngoing($id)
    {
        Gymkhana::findOrFail($id)->update(['availability' => 2]);
        return true;
    }
    public function setStateToClosed($id)
    {
        Gymkhana::findOrFail($id)->update(['availability' => 3]);
        return true;
    }

    public function deleteGymkhana($id)
    {
        Participation::where('id_gymkhana', $id)->delete();
        Code_to_validate::where('id_gymkhana', $id)->delete();
        Gymkhana::findOrFail($id)->delete();
        return true;
    }
}
