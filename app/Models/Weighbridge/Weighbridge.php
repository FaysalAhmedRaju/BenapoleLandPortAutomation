<?php

namespace App\Models\Weighbridge;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Port;

class Weighbridge extends Model
{
    protected $table='weighbridges';

    const SCALE_NAME = 'scale_name';
    const PORT_ID = 'port_id';
    const CREATED_BY ='created_by';
    const CREATED_AT ='created_at';
    const UPDATED_BY ='updated_by';
    const UPDATED_AT ='updated_at';



    protected $fillable = [
        self::SCALE_NAME,
        self::PORT_ID,
        self::CREATED_BY,
        self::CREATED_AT,
        self::UPDATED_BY,
        self::UPDATED_AT

    ];


    public $ownFields = [

        self::SCALE_NAME,
        self::PORT_ID,
        self::CREATED_BY,
        self::CREATED_AT,
        self::UPDATED_BY,
        self::UPDATED_AT
    ];


    public function users(){
        return $this->belongsToMany(User::class,'weighbridge_users','scale_id');
    }


    public function  port(){
        return $this->belongsTo(Port::class);
    }


    public function userWeighbridgeList()
    {
        $userWeighbridge = User::FindOrFail(\Auth::user()->id)->weighbridges()->get();
        $list = [];
        foreach ($userWeighbridge As $k => $weighbridge) {
            $list[$weighbridge->id] = $weighbridge->port_name;
        }
        return $list;
    }

}
