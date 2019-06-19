<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class ShedYard extends Model
{
    protected $table='shed_yards';

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function yardDetails()
    {
        return $this->hasMany('App\YardDetail');
    }



}
