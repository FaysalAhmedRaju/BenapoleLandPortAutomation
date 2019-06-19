<?php

namespace App\Models;

use App\User;
use App\Models\Cnf\Cnf;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $table = 'ports';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public  function cnfs()
    {
        return $this->belongsToMany(Cnf::class);
    }

    public function portList()
    {
        $list = [];
        foreach ($this->all() As $k => $port) {
            $list[$port->id] = $port->port_name;
        }
        return $list;
    }


    public function userPortList()
    {
        $userPorts = User::FindOrFail(\Auth::user()->id)->ports()->get();
        $list = [];
        foreach ($userPorts As $k => $port) {
            $list[$port->id] = $port->port_name;
        }
        return $list;
    }


}
