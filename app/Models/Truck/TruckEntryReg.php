<?php

namespace App\Models\Truck;

use Illuminate\Database\Eloquent\Model;
use App\Models\Manifest;
use App\Models\Warehouse\ShedYardWeight;
use DB;
use Session;

class TruckEntryReg extends Model
{
    protected $table = 'truck_entry_regs';
    public $timestamps = false;

    const VEHICLE_TYPE_FLAG ='vehicle_type_flag';
    const TRUCK_TYPE ='truck_type';
    const TRUCK_NO ='truck_no';
    const ENTRY_SERIAL = 'entry_sl';
    const DRIVER_NAME ='driver_name';
    const DRIVER_CARD ='driver_card';
    const ENTRY_TIME ='truckentry_datetime';
    const GOODS_ID = 'goods_id';
    const MANIFEST_ID = 'manf_id';
    const WEIGHMENT_FLAG ='weightment_flag';
    const CREATED_BY = 'created_by';
    const CREATED_AT = 'created_at';
    const UPDATED_BY = 'updated_by';
    const UPDATED_AT = 'updated_at';
    const PORT_ID = 'port_id';
    const RECEIVE_PACKAGE = 'receive_package';

    const RECEIVE_WEIGHT ='receive_weight';
    const GROSS_WEIGHT ='gweight_wbridge';
    const TARE_WEIGHT ='tr_weight';
    const TOTAL_WEIGHT ='nweight';
    const NET_WEIGHT ='tweight_wbridge';
    const WBRDGE_TIME1 ='wbrdge_time1';
    const WBRDGE_TIME2 = 'wbrdge_time2';
    const WBRIDG_USER1 = 'wbridg_user1';
    const WBRIDG_USER2 = 'wbridg_user2';
    const ENTRY_SCALE = 'entry_scale';
    const WBRIDG_CREATED_AT2 = 'wbridg_created_at2';
    const WBRIDG_UPDATED_BY1 = 'wbridg_updated_by1';
    const WBRIDG_UPDATED_BY2 = 'wbridg_updated_by2';
    const WBRIDG_UPDATE_AT2 = 'wbridg_updated_at2';
    const WBRIDG_UPDATED_AT1 = 'wbridg_updated_at1';
    const TRUCK_WEIGHT = 'truck_weight';
    const TRUCK_PACKAGE = 'truck_package';


    protected $fillable = [

        self::VEHICLE_TYPE_FLAG,
        self::TRUCK_TYPE,
        self::TRUCK_NO,
        self::ENTRY_SERIAL,
        self::DRIVER_NAME,
        self::DRIVER_CARD,
        self::ENTRY_TIME,
        self::GOODS_ID,
        self::MANIFEST_ID,
        self::WEIGHMENT_FLAG,
        self::CREATED_BY,
        self::CREATED_AT,
        self::UPDATED_BY,
        self::UPDATED_AT,
        self::PORT_ID,
        self::RECEIVE_WEIGHT,
        self::GROSS_WEIGHT,
        self::TARE_WEIGHT,
        self::TOTAL_WEIGHT,
        self::NET_WEIGHT,
        self::WBRDGE_TIME1,
        self::WBRDGE_TIME2,
        self::WBRIDG_USER1,
        self::WBRIDG_USER2,
        self::ENTRY_SCALE,
        self::WBRIDG_CREATED_AT2,
        self::WBRIDG_UPDATED_BY1,
        self::WBRIDG_UPDATED_BY2,
        self::WBRIDG_UPDATE_AT2,
        self::WBRIDG_UPDATED_AT1,
        self::RECEIVE_PACKAGE,
        self::TRUCK_WEIGHT,
        self::TRUCK_PACKAGE
        ];
    public $ownFields = [

        self::VEHICLE_TYPE_FLAG,
        self::TRUCK_TYPE,
        self::TRUCK_NO,
        self::DRIVER_NAME,
        self::DRIVER_CARD,
        self::ENTRY_TIME,
        self::WEIGHMENT_FLAG,
        self::RECEIVE_WEIGHT,
        self::GROSS_WEIGHT,
        self::TARE_WEIGHT,
        self::TOTAL_WEIGHT,
        self::NET_WEIGHT,
        self::CREATED_AT,
        self::WBRDGE_TIME1,
        self::WBRDGE_TIME2,
        self::WBRIDG_USER1,
        self::WBRIDG_USER2,
        self::ENTRY_SCALE,
        self::WBRIDG_CREATED_AT2,
        self::WBRIDG_UPDATED_BY1,
        self::WBRIDG_UPDATED_BY2,
        self::WBRIDG_UPDATE_AT2,
        self::WBRIDG_UPDATED_AT1,
        self::RECEIVE_PACKAGE,
        self::TRUCK_WEIGHT,
        self::TRUCK_PACKAGE
        ];

    public function manifest() {
        return $this->belongsTo(Manifest::Class,'manf_id');
    }

    public function shedYardWeights() {
        return $this->hasMany(ShedYardWeight::class,'truck_id');
    }

    public function getTruckSerial($manifest_no, $date) {
        $current_date = date('Y-m-d', strtotime($date));
        $port_id = Session::get('PORT_ID');

        $split_manifest_no = explode('/', $manifest_no, 3);
        $check_manifest_for_the_date = DB::select('SELECT t.entry_sl FROM manifests AS m 
                    JOIN truck_entry_regs AS t ON m.id=t.manf_id
                    WHERE DATE(t.truckentry_datetime)=? AND m.manifest=? AND m.port_id=? AND t.port_id=? ORDER BY t.id DESC LIMIT 1', [$current_date, $manifest_no, $port_id, $port_id]);
        $entry_sl = 0;
        if (count($check_manifest_for_the_date) > 0) {//the manifest's truck exist for today
            $entry_sl = $check_manifest_for_the_date[0]->entry_sl;
        } else {//the manifest's truck does not exist for today
            $get_last_entry = DB::select('SELECT tr.entry_sl AS last_entry_sl,m.manifest
                                    FROM truck_entry_regs AS tr 
                                    JOIN manifests AS m ON tr.manf_id=m.id
                                    WHERE DATE(tr.truckentry_datetime)=? AND m.port_id=? 
                                    AND tr.port_id=?  AND tr.entry_sl>0 
                                    ORDER BY tr.id DESC LIMIT 1', [$current_date, $port_id, $port_id]);


            if (count($get_last_entry) > 0) {// at least one truck entry found for the day

                $get_last_entry_manifest_split = explode('/', $get_last_entry[0]->manifest, 3);

                if (!is_numeric($get_last_entry_manifest_split[1]) || $get_last_entry_manifest_split[1] == 1) {//check middle part of 947/A-E/2017 is not numeric or 1
                    $entry_sl = $get_last_entry[0]->last_entry_sl;
                } else {//manifest middle part is numeric and more than 1

                    $get_last_entry_manifest_truck_total_entered = DB::select('SELECT COUNT(tr.id) AS count
                                        FROM truck_entry_regs AS tr 
                                        LEFT JOIN manifests AS m ON m.id=tr.manf_id 
                                        WHERE m.manifest=? AND m.port_id=? AND tr.port_id=?', [$get_last_entry[0]->manifest, $port_id, $port_id]);

                    $entry_sl = $get_last_entry[0]->last_entry_sl + ($get_last_entry_manifest_split[1] - $get_last_entry_manifest_truck_total_entered[0]->count);//947/5/2017 already entred 3 so, (5-3) sl should be kept for then
                }
            } else {//this is the first truck entry for the date
                $entry_sl = 0;
            }
        }

        return $entry_sl + 1;
    }

}
