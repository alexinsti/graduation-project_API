<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Code_to_validate;
use App\Models\Relation;

class CodeService
{

    public function createCode($code_pic_Base64, $location)
    {
        $code_pic=Base64_decode($code_pic_Base64);
        $code=Code::create([
            'code_pic' => $code_pic,
            'location' => $location,
            'availability' => 1,
        ]);
        return $code->id;

    }
    public function getCodeByAvailability($availability)
    {
        return Code::where('availability', $availability)->get();
    }

    public function updateCodeAvailability($id, $availability)
    {
        Code::findOrFail($id)->update(['availability'=>$availability]);
        return true;
    }
    public function setAvailabilityAsPublic($id)
    {
        Code::findOrFail($id)->update(['availability' => 1]);
        return true;
    }
    public function setAvailabilityAsPrivate($id)
    {
        Code::findOrFail($id)->update(['availability' => 0]);
        return true;
    }

    public function deleteCode($id)
    {
        Relation::where('id_code', $id)->delete();
        Code_to_validate::where('id_code', $id)->delete();
        Code::findOrFail($id)->delete();
        return true;
    }

}
