<?php

namespace App\Models\Warehouse\Delivery;

use Illuminate\Database\Eloquent\Model;
use App\Models\Manifest;

class DeliveryRequisition extends Model
{
    public $timestamps = false;
    //
    protected $table='delivery_requisitions';
    const MANIFEST_ID = 'manifest_id';
    const CARPENTER_PACKAGES = 'carpenter_packages';
    const CARPENTER_REPAIR_PACKAGES = 'carpenter_repair_packages';
    const APPROXIMATE_DELIVERY_DATE = 'approximate_delivery_date';
    const APPROXIMATE_DELIVERY_TYPE = 'approximate_delivery_type';
    const APPROXIMATE_LABOUR_LOAD = 'approximate_labour_load';
    const APPROXIMATE_EQUIPMENT_LOAD = 'approximate_equipment_load';
    const LOCAL_TRANSPORT_TYPE = 'local_transport_type';
    const TRANSPORT_TRUCK = 'transport_truck';
    const TRANSPORT_VAN = 'transport_van';
    const LOCAL_WEIGHMENT = 'local_weighment';
    const SHIFTING_FLAG = 'shifting_flag';
    const GATE_PASS_NO = 'gate_pass_no';
    const LOCAL_HALTAGE = 'local_haltage';
    const UPDATED_BY = 'updated_by';
    const UPDATED_AT = 'updated_at';


    protected $fillable = [
        self::MANIFEST_ID,
        self::CARPENTER_PACKAGES,
        self::CARPENTER_REPAIR_PACKAGES,
        self::APPROXIMATE_DELIVERY_DATE,
        self::APPROXIMATE_DELIVERY_TYPE,

        self::APPROXIMATE_LABOUR_LOAD,
        self::APPROXIMATE_EQUIPMENT_LOAD,
        self::LOCAL_TRANSPORT_TYPE,
        self::TRANSPORT_TRUCK,

        self::TRANSPORT_VAN,
        self::LOCAL_WEIGHMENT,
        self::SHIFTING_FLAG,
        self::GATE_PASS_NO,

        self::UPDATED_BY,
        self::UPDATED_AT,
        self::LOCAL_HALTAGE
    ];

    public $ownFields = [
        self::MANIFEST_ID,
        self::CARPENTER_PACKAGES,
        self::CARPENTER_REPAIR_PACKAGES,
        self::APPROXIMATE_DELIVERY_DATE,
        self::APPROXIMATE_DELIVERY_TYPE,

        self::APPROXIMATE_LABOUR_LOAD,
        self::APPROXIMATE_EQUIPMENT_LOAD,
        self::LOCAL_TRANSPORT_TYPE,
        self::TRANSPORT_TRUCK,

        self::TRANSPORT_VAN,
        self::LOCAL_WEIGHMENT,
        self::SHIFTING_FLAG,
        self::GATE_PASS_NO,

        self::UPDATED_BY,
        self::UPDATED_AT,
        self::LOCAL_HALTAGE

    ];

    public function manifest() {
        return $this->belongsTo(Manifest::Class,'manifest_id');
    }
}
