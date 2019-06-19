<?php

namespace App\Models\Designation;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{

    public function designationList()
    {
        $desig = [];
        foreach (Designation::all() as $k => $designation) {
            $desig[$designation->id] = $designation->designation;
        }
        return $desig;

    }

}
