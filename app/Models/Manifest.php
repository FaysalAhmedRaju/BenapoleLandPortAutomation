<?php

namespace App\Models;

use App\Models\Assessment\Assessment;
use App\Models\Goods;
use App\Models\Item\ItemDetail;
use App\Models\Warehouse\Delivery\LocalDelivery;
use App\Models\Warehouse\Delivery\DeliveryRequisition;
use Illuminate\Database\Eloquent\Model;
use App\Models\Truck\TruckEntryReg;
use App\Models\Assessment\AssessmentDetails;
use App\Models\Warehouse\ShedYard;
use App\Models\Importer\VatReg;




/**
 * App\Manifest
 *
 * @mixin \Eloquent
 */
class Manifest extends Model
{
    public $timestamps = false;

    protected $table = 'manifests';


// manifest related
    const MANIFEST_NO = 'manifest';
    const SELF_FLAG = 'self_flag';
    const GOODS_ID = 'goods_id';
    const CREATED_BY = 'created_by';
    const CREATED_AT = 'created_at';
    const UPDATED_BY = 'updated_by';
    const UPDATED_AT = 'updated_at';
    const PORT_ID = 'port_id';

    const MANIFEST_DATE = 'manifest_date';
    const MARKS_NO = 'marks_no';
    const NWEIGHT ='nweight';
    const PACKAGE_NO = 'package_no';
    const PACKAGE_TYPE = 'package_type';
    const CNF_VALUE = 'cnf_value';

    const GWEIGHT = 'gweight';
    const POSTED_SHED_YARD = 'posted_yard_shed';
    const IMPORTER = 'vatreg_id';
    const EXPORTER_NAME_ADDR = 'exporter_name_addr';
    const LC_NO = 'lc_no';
    const LC_DATE = 'lc_date';
    const IND_BE_NO = 'ind_be_no';
    const IND_BE_DATE = 'ind_be_date';
    const POSTING_REMARK = 'posting_remark';


//delivery related
    const BE_NO = 'be_no';
    const BE_DATE = 'be_date';
    const CUSTOM_RELEASE_ORDER_NO = 'custom_release_order_no';
    const CUSTOM_RELAEASE_ORDER_DATE = 'custom_release_order_date';
    const AIN_NO = 'ain_no';
    const CARPENTER_PACKAGES = 'carpenter_packages';
    const CARPENTER_REPAIR_PACKAGES = 'carpenter_repair_packages';
    const APPROXIMATE_DELIVERY_DATE = 'approximate_delivery_date';
    const APPROXIMATE_DELIVERY_TYPE = 'approximate_delivery_type';
    const APPROXIMATE_LABOUR_LOAD = 'approximate_labour_load';
    const APPROXIMATE_EQUIPMENT_LOAD = 'approximate_equipment_load';
    const LOCAL_TRANSPORT_TYPE = 'local_transport_type';
    const TRANSPORT_TRUCK = 'transport_truck';
    const TRANSPORT_VAN = 'transport_van';
    const BD_WEIGHMENT = 'bd_weighment';
    const SHIFTING_FLAG = 'shifting_flag';
    const GATE_PASS_NO = 'gate_pass_no';
    const COUNTRY_ID = 'country_id';


    protected $fillable = [

        self::MANIFEST_NO,
        self::SELF_FLAG,
        self::GOODS_ID,
        self::CREATED_AT,
        self::CREATED_BY,
        self::UPDATED_BY,
        self::UPDATED_AT,
        self::PORT_ID,


        self::GWEIGHT,
        self::MANIFEST_DATE,
        self::POSTED_SHED_YARD,
        self::MARKS_NO,
        self::PACKAGE_NO,
        self::IMPORTER,
        self::NWEIGHT,
        self::PACKAGE_NO,
        self::PACKAGE_TYPE,
        self::CNF_VALUE,
        self::EXPORTER_NAME_ADDR,
        self::LC_NO,
        self::LC_DATE,
        self::IND_BE_NO,
        self::IND_BE_DATE,
        self::POSTING_REMARK,




        self::BE_NO,
        self::BE_DATE,
        self::CUSTOM_RELEASE_ORDER_NO,
        self::CUSTOM_RELAEASE_ORDER_DATE,
        self::AIN_NO,
        self::CARPENTER_PACKAGES,
        self::CARPENTER_REPAIR_PACKAGES,
        self::APPROXIMATE_DELIVERY_DATE,
        self::APPROXIMATE_DELIVERY_TYPE,
        self::APPROXIMATE_LABOUR_LOAD,
        self::APPROXIMATE_EQUIPMENT_LOAD,
        self::LOCAL_TRANSPORT_TYPE,
        self::TRANSPORT_TRUCK,
        self::TRANSPORT_VAN,
        self::BD_WEIGHMENT,
        self::SHIFTING_FLAG,
        self::GATE_PASS_NO,
        self::COUNTRY_ID
    ];

    public $ownFields = [

        self::MANIFEST_NO,
        self::SELF_FLAG,
        self::GOODS_ID,
        self::CREATED_AT,

        
        self::GWEIGHT,
        self::MANIFEST_DATE,
        self::POSTED_SHED_YARD,
        self::MARKS_NO,
        self::PACKAGE_NO,
        self::IMPORTER,
        self:: NWEIGHT,
        self::PACKAGE_NO,
        self::PACKAGE_TYPE,
        self::CNF_VALUE,
        self::EXPORTER_NAME_ADDR,
        self::LC_NO,
        self::LC_DATE,
        self::IND_BE_NO,
        self::IND_BE_DATE,
        self::POSTING_REMARK,


        self::BE_NO,
        self::BE_DATE,
        self::CUSTOM_RELEASE_ORDER_NO,
        self::CUSTOM_RELAEASE_ORDER_DATE,
        self::AIN_NO,
        self::CARPENTER_PACKAGES,
        self::CARPENTER_REPAIR_PACKAGES,
        self::APPROXIMATE_DELIVERY_DATE,
        self::APPROXIMATE_DELIVERY_TYPE,
        self::APPROXIMATE_LABOUR_LOAD,
        self::APPROXIMATE_EQUIPMENT_LOAD,
        self::LOCAL_TRANSPORT_TYPE,
        self::TRANSPORT_TRUCK,
        self::TRANSPORT_VAN,
        self::BD_WEIGHMENT,
        self::SHIFTING_FLAG,
        self::GATE_PASS_NO,
        self::COUNTRY_ID
    ];





    public function trucks() {
        return $this->hasMany(TruckEntryReg::Class,'manf_id');
    }
    public function localDeliveries() {
        return $this->hasMany(LocalDelivery::Class,'manf_id');
    }
    public function deliveryRequisitions() {
        return $this->hasMany(DeliveryRequisition::Class,'manifest_id');
    }

    public function shedYard() {
        return $this->belongsTo(ShedYard::Class, 'posted_yard_shed');
    }
    public function port() {
        return $this->belongsTo(Port::Class, 'port_id');
    }

    public function assessmentDetails(){
        return $this->hasMany(AssessmentDetails::Class,'manif_id');
    }
    public function assessments(){
        return $this->hasMany(Assessment::Class,'manifest_id');
    }

    public function importer(){
        return $this->belongsTo(VatReg::Class,'vatreg_id');
    }

    public function goods(){
//        return Goods::whereIn('id', $this->goods_id);
        return $this->belongsTo(Goods::Class,'goods_id');
    }


    public function items(){
        return $this->hasMany(ItemDetail::class);
    }



   /* public function getGoodsIdAttribute()
    {
        if (!$this->relationLoaded('cargo_details')) {
            $layers = Goods::whereIn('id', $this->goods_id)->get();

            $this->setRelation('cargo_details', $layers);
        }

        return $this->getRelation('cargo_details');
    }*/

/*
    public function getGoodsIdAttribute($commaSeparatedIds)
    {
        return explode(',', $commaSeparatedIds);
    }*/









}
