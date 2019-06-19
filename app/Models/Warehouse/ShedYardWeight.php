<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;
use App\Models\Truck\TruckEntryReg;
use App\Models\Warehouse\YardDetail;
use DB;
use Session;

class ShedYardWeight extends Model
{
    protected $table = 'shed_yard_weights';
    public $timestamps = false;

    const UNLOAD_YARD_SHED = 'unload_yard_shed';
    const UNLOAD_RECEIVE_DATETIME = 'unload_receive_datetime';
    const TRUCK_ID = 'truck_id';
    const UNLOAD_LABOR_PACKAGE ='unload_labor_package';
    const UNLOAD_LABOR_WEIGHT ='unload_labor_weight';
    const  UNLOAD_EQUIP_WEIGHT = 'unload_equip_weight';
    const  UNLOAD_EQUIPMENT_PACKAGE ='unload_equipment_package';
    const  UNLOAD_EQUIP_NAME = 'unload_equip_name';
    const  UNLOAD_COMMENT = 'unload_comment';



    protected $fillable = [

        self::UNLOAD_YARD_SHED,
        self::UNLOAD_RECEIVE_DATETIME,
        self::TRUCK_ID,
        self::UNLOAD_LABOR_PACKAGE,
        self::UNLOAD_LABOR_WEIGHT,
        self::UNLOAD_EQUIP_WEIGHT,
        self::UNLOAD_EQUIPMENT_PACKAGE,
        self::UNLOAD_EQUIP_NAME,
        self::UNLOAD_COMMENT,

    ];

    public $ownFields = [

        self::UNLOAD_YARD_SHED,
        self::TRUCK_ID,
        self::UNLOAD_RECEIVE_DATETIME,
        self::UNLOAD_LABOR_PACKAGE,
        self::UNLOAD_LABOR_WEIGHT,
        self::UNLOAD_EQUIP_WEIGHT,
        self::UNLOAD_EQUIPMENT_PACKAGE,
        self::UNLOAD_EQUIP_NAME,
        self::UNLOAD_COMMENT,
    ];


    public function truck()
    {
        return $this->belongsTo(TruckEntryReg::Class,'truck_id');
    }


    public function yardDetail(){
        return $this->belongsTo(YardDetail::Class,'unload_yard_shed');
    }


}
