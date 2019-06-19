<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class YardDetail extends Model
{
    protected $table='yard_details';


    public function users()
    {
        return $this->belongsToMany('App\User');
    }



}



