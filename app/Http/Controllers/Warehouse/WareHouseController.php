<?php

namespace App\Http\Controllers\Warehouse;

use App\Models\Warehouse\ShedYard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse\YardDetail;
use DB;
use Auth;
use PDF;
use Symfony\Component\Process\Exception\RuntimeException;
use Response;
use Session;
use App\Http\Controllers\Base\ProjectBaseController;

class WareHouseController extends ProjectBaseController
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Welcome()
    {
        $currentDate = date('Y-m-d');
        $port_id = Session::get('PORT_ID');
        $todaysWareHouse = DB::select("SELECT (SELECT COUNT(ter.id) total_truck 
                        FROM truck_entry_regs AS ter
                        JOIN manifests AS m ON m.id = ter.manf_id
                        JOIN shed_yard_weights AS syw ON syw.truck_id = ter.id
                        WHERE m.transshipment_flag=0 AND DATE(syw.unload_receive_datetime)=?
                        AND m.port_id=? AND ter.port_id=? AND syw.port_id=?) AS total_truck_receive,
                        (SELECT COUNT(ter.id) total_truck 
                        FROM truck_entry_regs AS ter
                        JOIN manifests AS m ON m.id = ter.manf_id
                           JOIN delivery_requisitions AS dr ON m.id = dr.manifest_id
                        WHERE m.transshipment_flag=0 AND DATE(dr.approximate_delivery_date)=?
                        AND m.port_id=? AND ter.port_id=?) AS total_truck_delivery ,
                        (SELECT COUNT(DISTINCT ter.manf_id) total_manifest
                        FROM truck_entry_regs AS ter
                        JOIN manifests AS m ON m.id = ter.manf_id
                        JOIN shed_yard_weights AS syw ON syw.truck_id = ter.id
                        WHERE m.transshipment_flag=0 AND DATE(syw.unload_receive_datetime)=?
                        AND m.port_id=? AND ter.port_id=? AND syw.port_id=?) AS total_manifest_receive,
                        (SELECT COUNT(DISTINCT ter.manf_id) total_manifest
                        FROM truck_entry_regs AS ter
                        JOIN manifests AS m ON m.id = ter.manf_id
                        JOIN delivery_requisitions AS dr ON m.id = dr.manifest_id
                        WHERE m.transshipment_flag=0 AND DATE(dr.approximate_delivery_date)=?
                        AND m.port_id=? AND ter.port_id=?) AS total_manifest_delivery", [$currentDate, $port_id, $port_id, $port_id, $currentDate, $port_id, $port_id, $currentDate, $port_id, $port_id, $port_id, $currentDate, $port_id, $port_id]);


        return view('default.warehouse.welcome', compact('todaysWareHouse'));
    }

    //============================Warehouse Entry==================================
    public function wareHouseReceiveEntryForm() {
        $port_id = Session::get('PORT_ID');
        $arrayShedYardId = array();
        foreach (Auth::user()->shedYards as $k => $v) {
            $arrayShedYardId[] = $v->id;
        }
        if (Auth::user()->role_id==11){//for maintencace role
            $shed_yards = ShedYard::where('port_id',$port_id)->get();
            foreach($shed_yards as $k => $v) {
                $arrayShedYardId[] = $v->id;
            }
        }

        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('yard_shed', 0)->where('port_id', $port_id)->get();
        $shed_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('yard_shed', 1)->where('port_id', $port_id)->get();
        $shed_yard_details = YardDetail::whereIn('shed_yard_id',$arrayShedYardId)->where('port_id', $port_id)->get();

        return view('default.warehouse.receive.warehouse-receive-entry-form',['yard_details_array' => $yard_details_array, 'shed_details_array' => $shed_details_array, 'shed_yard_details' => $shed_yard_details]);
    }

    public function Yard_WereHouseRecevingDetailsJson()
    {
        $port_id = Session::get('PORT_ID');

        if (Auth::user()->role->name == "TransShipment") {
            $yard = DB::table('yard_details')
                ->where('yard_details.id', '=', 55)
                ->where('yard_details.port_id', $port_id)
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        } else if (Auth::user()->role->name == "Super Admin") {
            $yard = DB::table('yard_details')
                ->where('yard_details.port_id', $port_id)
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        } else {
            $yard = DB::table('yard_details')
                ->where('yard_details.id', '!=', 55)
                ->where('yard_details.port_id', $port_id)
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        }

        return json_encode($yard);
    }

    public function DateWiseWarehouseReceive()
    {

        return view('WareHouse.DateWiseWarehouseReceive');
    }

    public function shedYardWeightCount(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');
        $countYardNO = DB::select("SELECT COUNT(syw.unload_yard_shed)+1 AS yard_level_no
           FROM shed_yard_weights AS syw WHERE DATE(syw.created_at)='$today' AND syw.unload_yard_shed=? AND syw.port_id=?", [$req->yard_no,$port_id]);
        return json_encode($countYardNO);
    }


    public function countCurrentDateShedYardNoCheck(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');
        $countYardNO = DB::select("SELECT COUNT(syw.unload_yard_shed)+1 AS yard_level_no
           FROM shed_yard_weights AS syw WHERE DATE(syw.created_at)='$today' AND syw.unload_yard_shed=? AND syw.port_id=?", [$req->yard_no,$port_id]);
        return json_encode($countYardNO);
    }


    public function searchTruckDetailsData(Request $r) {
        $port_id = Session::get('PORT_ID');
        if($r->search_by == 'manifestNo') {
            $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        WHERE manifests.transshipment_flag=0
                                        AND manifests.manifest=? AND manifests.port_id=?', [$r->manf_id, $port_id]);
        } else if($r->search_by == 'truckNo') {
            $string = $r->truck_no;
            if(preg_match("/[a-z]/i", $string)) {
                $truckTypeAndNumber = explode('-', $string);
                $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
                                        WHERE manifests.transshipment_flag=0
                                        AND truck_entry_regs.truck_no=? AND truck_entry_regs.truck_type=? AND truck_entry_regs.port_id=? AND manifests.port_id=?', [$truckTypeAndNumber[1], $truckTypeAndNumber[0], $port_id, $port_id]);
            } else {
                return Response::json(['error' => 'Enter Valid Truck Type-No. Example: WB-3287'], 203);
            }
        }
        if($chkPermission[0]->valid == 0) {
            return Response::json(['error' => 'You Are Not Permitted To See This.'], 203);
        }


        if($r->search_by == 'truckNo') {
            $string = $r->truck_no;
            if (preg_match("/[a-z]/i", $string)) {
                $truckTypeAndNumber = explode('-', $string);
                $truckData = DB::select("SELECT t.id, t.truck_type, t.truck_no, t.goods_id, t.receive_weight, t.manf_id, t.receive_package, t.tweight_wbridge, 
t.holtage_charge_flag, t.weightment_flag, t.gweight_wbridge,
t.receive_created_at, t.vehicle_type_flag, m.manifest, m.exporter_name_addr, v.NAME,
yg.yard_id, yg.row, yg.column, yg.weight,m.posted_yard_shed,
(SELECT GROUP_CONCAT(DISTINCT cd.cargo_name) FROM manifests
LEFT JOIN cargo_details AS cd ON FIND_IN_SET(cd.id, manifests.goods_id) > 0
WHERE manifests.id = m.id) AS cargo_name,
(SELECT GROUP_CONCAT(syw.unload_comment)
FROM shed_yard_weights AS syw 
WHERE syw.truck_id = t.id) AS recive_comment,
(SELECT GROUP_CONCAT(syw.unload_receive_datetime)
FROM shed_yard_weights AS syw 
WHERE syw.truck_id = t.id) AS receive_datetime,
(SELECT GROUP_CONCAT(yard_details.yard_shed_name SEPARATOR '?')
FROM manifests 
INNER JOIN yard_details 
ON FIND_IN_SET(yard_details.shed_yard_id, manifests.posted_yard_shed) > 0  
WHERE manifests.id = t.manf_id) AS posted_yard_shed_name,
(SELECT GROUP_CONCAT(yd.yard_shed_name)
FROM shed_yard_weights AS syw JOIN yard_details AS yd ON yd.id = syw.unload_yard_shed 
WHERE syw.truck_id = t.id) AS shed_yard,
(SELECT SUM(syw.unload_labor_weight) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_labor_weight,
(SELECT SUM(syw.unload_labor_package) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_labor_pkg,
(SELECT SUM(syw.unload_equip_weight) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_equip_weight,
(SELECT SUM(syw.unload_equipment_package) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_equip_pkg,
(SELECT GROUP_CONCAT(syw.unload_equip_name SEPARATOR ' ') 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS all_equip_name
FROM truck_entry_regs AS t 
JOIN manifests AS m ON m.id = t.manf_id
LEFT JOIN vatregs AS v ON v.id = m.vatreg_id
LEFT JOIN yard_graphs AS yg ON yg.truck_id = t.id
WHERE t.truck_type=? AND t.truck_no=?  AND t.port_id=? AND m.port_id=?",[$truckTypeAndNumber[0], $truckTypeAndNumber[1], $port_id, $port_id]);
                return json_encode($truckData);
            } else {
                return;
            }
        } else if ($r->search_by == 'manifestNo') {
            $truckData = DB::select("SELECT t.id, t.truck_type, t.truck_no, t.goods_id, t.receive_weight, t.manf_id, t.receive_package, t.tweight_wbridge, 
t.holtage_charge_flag, t.weightment_flag, t.gweight_wbridge,
t.receive_created_at, t.vehicle_type_flag, m.manifest, m.exporter_name_addr, v.NAME,
yg.yard_id, yg.row, yg.column, yg.weight,m.posted_yard_shed,
(SELECT GROUP_CONCAT(DISTINCT cd.cargo_name) FROM manifests
LEFT JOIN cargo_details AS cd ON FIND_IN_SET(cd.id, manifests.goods_id) > 0
WHERE manifests.id = m.id) AS cargo_name,
(SELECT GROUP_CONCAT(syw.unload_comment)
FROM shed_yard_weights AS syw 
WHERE syw.truck_id = t.id) AS recive_comment,
(SELECT GROUP_CONCAT(syw.unload_receive_datetime)
FROM shed_yard_weights AS syw 
WHERE syw.truck_id = t.id) AS receive_datetime,
(SELECT GROUP_CONCAT(yard_details.yard_shed_name SEPARATOR '?')
FROM manifests 
INNER JOIN yard_details 
ON FIND_IN_SET(yard_details.shed_yard_id, manifests.posted_yard_shed) > 0  
WHERE manifests.id = t.manf_id) AS posted_yard_shed_name,
(SELECT GROUP_CONCAT(yd.yard_shed_name)
FROM shed_yard_weights AS syw JOIN yard_details AS yd ON yd.id = syw.unload_yard_shed 
WHERE syw.truck_id = t.id) AS shed_yard,
(SELECT SUM(syw.unload_labor_weight) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_labor_weight,
(SELECT SUM(syw.unload_labor_package) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_labor_pkg,
(SELECT SUM(syw.unload_equip_weight) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_equip_weight,
(SELECT SUM(syw.unload_equipment_package) 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS total_equip_pkg,
(SELECT GROUP_CONCAT(syw.unload_equip_name SEPARATOR ' ') 
FROM shed_yard_weights AS syw  
WHERE syw.truck_id = t.id) AS all_equip_name
FROM truck_entry_regs AS t 
JOIN manifests AS m ON m.id = t.manf_id
LEFT JOIN vatregs AS v ON v.id = m.vatreg_id
LEFT JOIN yard_graphs AS yg ON yg.truck_id = t.id
WHERE m.manifest=? AND t.port_id=? AND m.port_id=?", [$r->manf_id, $port_id, $port_id]);

            return json_encode($truckData);
        } else {
            return Response::json(['error' => 'Please Select Search By.'], 203);
        }
    }


    public function getGoodsDetailsData(Request $r)
    {
        $goodsData = DB::select("SELECT GROUP_CONCAT(cargo_details.cargo_name) AS cargo_name FROM cargo_details WHERE cargo_details.id IN ($r->goods_id)");
        return json_encode($goodsData);
    }

    public function getShedData($truck_id) {
        $shed_data = DB::select('SELECT s.*, ya.id AS shed_yard_id, ya.yard_shed_name  
                                FROM shed_yard_weights AS s 
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? AND ya.yard_shed = 1', [$truck_id, Session::get('PORT_ID')]);
        return json_encode($shed_data);
    }

    public function getYardData($truck_id) {
        $yard_data = DB::select('SELECT s.*, ya.id AS shed_yard_id, ya.yard_shed_name  
                                FROM shed_yard_weights AS s 
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? AND ya.yard_shed = 0', [$truck_id, Session::get('PORT_ID')]);
        return json_encode($yard_data);
    }

    public function saveShedData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $yard_weight = DB::select('SELECT 
                                (IFNULL(s.unload_labor_weight,0) + IFNULL(s.unload_equip_weight,0)) AS yard_weight
                                FROM shed_yard_weights AS s
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? 
                                AND ya.yard_shed = 0', [$r->truck_id, $port_id]);

        if(count($yard_weight) > 0 && $r->receive_weight != null) {
            $shed_labor = $r->labor_unload_shed != null ? $r->labor_unload_shed : 0;
            $shed_equip = $r->equip_unload_shed != null ? $r->equip_unload_shed : 0;
            $total_shed = $shed_labor + $shed_equip;
            $total_weight = $yard_weight[0]->yard_weight + $total_shed;
            if($total_weight > $r->receive_weight) {
                return Response::json(['error' => 'Can not input more then receive weight. Yard already received '.$yard_weight[0]->yard_weight.' unit.' ], 203);
            }
        }

        $shedYardAllocatedList = DB::select("SELECT yard_details.id as ShedYardId FROM  
                                             yard_details WHERE yard_details.shed_yard_id IN ($r->allocatedShedYard) AND yard_details.port_id=?",[$port_id]);

        $shedYardArray = array();
        foreach ($shedYardAllocatedList  as $k => $v){
            $shedYardArray[]  = $v->ShedYardId;
        }
        $arrayImplode = implode(',', $shedYardArray);
        $arrayExplode = explode(',', $arrayImplode);
        $TruckShed = $r->posted_shed;

        if (in_array($TruckShed, $arrayExplode, TRUE)) {
            $receive_by = Auth::user()->id;
            $chk_manifest = DB::select('SELECT m.manifest_posted_done_flag 
                                    FROM truck_entry_regs AS tr 
                                    JOIN manifests AS m ON tr.manf_id = m.id
                                    WHERE tr.id=? AND tr.port_id=? AND m.port_id=?', [$r->truck_id, $port_id, $port_id]);
            if ($chk_manifest[0]->manifest_posted_done_flag == 0) {
                return Response::json(['error' => 'Posted By CNF. Please Contact Posting Branch.'], 203);
            }
            if ($r->receive_created_at == null) {
                $wareHouse_shed_posting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_by' => $receive_by,
                        'truck_entry_regs.receive_created_at' => date('Y-m-d H:i:s')
                    ]);
                if($r->shed_yard_weight_id == null) {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.truck_id=? 
                        AND shed_yard_weights.port_id=? ORDER BY shed_yard_weights.id DESC LIMIT 1',[$r->truck_id, $port_id]);
                    if(count($get_receive_datetime) > 0) {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->where('truck_id', $r->truck_id)
                            ->update([
                                'unload_labor_package' => $r->labor_package_shed,
                                'unload_labor_weight' => $r->labor_unload_shed,
                                'unload_equipment_package' => $r->equipment_package_shed,
                                'unload_equip_weight' => $r->equip_unload_shed,
                                'unload_equip_name' => $r->equip_name_shed,
                                'unload_yard_shed' => $r->posted_shed,
                                'unload_shifting_flag' => $r->shifting_flag_shed,
                                'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                                'unload_comment' => $r->recive_comment_shed,
                                'port_id' => $port_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => $receive_by
                            ]);
                    } else {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->insert([
                                'truck_id' => $r->truck_id,
                                'unload_labor_package' => $r->labor_package_shed,
                                'unload_labor_weight' => $r->labor_unload_shed,
                                'unload_equipment_package' => $r->equipment_package_shed,
                                'unload_equip_weight' => $r->equip_unload_shed,
                                'unload_equip_name' => $r->equip_name_shed,
                                'unload_yard_shed' => $r->posted_shed,
                                'unload_shifting_flag' => $r->shifting_flag_shed,
                                'unload_receive_datetime' => date('Y-m-d H:i:s'),
                                'unload_comment' => $r->recive_comment_shed,
                                'port_id' => $port_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $receive_by
                            ]);
                    }
                } else {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.id=? AND shed_yard_weights.port_id=?',[$r->shed_yard_weight_id, $port_id]);
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_weight_id)
                        ->update([
                            'truck_id' => $r->truck_id,
                            'unload_labor_package' => $r->labor_package_shed,
                            'unload_labor_weight' => $r->labor_unload_shed,
                            'unload_equipment_package' => $r->equipment_package_shed,
                            'unload_equip_weight' => $r->equip_unload_shed,
                            'unload_equip_name' => $r->equip_name_shed,
                            'unload_yard_shed' => $r->posted_shed,
                            'unload_shifting_flag' => $r->shifting_flag_shed,
                            'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                            'unload_comment' => $r->recive_comment_shed,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                }
            } else {
                $wareHouse_shed_posting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_updated_by' => $receive_by,
                        'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                    ]);
                if ($r->shed_yard_weight_id != null) {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.id = ? AND shed_yard_weights.port_id=?',[$r->shed_yard_weight_id, $port_id]);
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_weight_id)
                        ->update([
                            'truck_id' => $r->truck_id,
                            'unload_labor_package' => $r->labor_package_shed,
                            'unload_labor_weight' => $r->labor_unload_shed,
                            'unload_equipment_package' => $r->equipment_package_shed,
                            'unload_equip_weight' => $r->equip_unload_shed,
                            'unload_equip_name' => $r->equip_name_shed,
                            'unload_yard_shed' => $r->posted_shed,
                            'unload_shifting_flag' => $r->shifting_flag_shed,
                            'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                            'unload_comment' => $r->recive_comment_shed,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                } else {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime, shed_yard_weights.unload_yard_shed
                        FROM shed_yard_weights WHERE shed_yard_weights.truck_id=?
                        AND shed_yard_weights.port_id=? ORDER BY shed_yard_weights.id DESC LIMIT 1',[$r->truck_id, $port_id]);
                    if(count($get_receive_datetime) > 0 && $get_receive_datetime[0]->unload_yard_shed == null) {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->where('truck_id', $r->truck_id)
                            ->update([
                                'unload_labor_package' => $r->labor_package_shed,
                                'unload_labor_weight' => $r->labor_unload_shed,
                                'unload_equipment_package' => $r->equipment_package_shed,
                                'unload_equip_weight' => $r->equip_unload_shed,
                                'unload_equip_name' => $r->equip_name_shed,
                                'unload_yard_shed' => $r->posted_shed,
                                'unload_shifting_flag' => $r->shifting_flag_shed,
                                'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                                'unload_comment' => $r->recive_comment_shed,
                                'port_id' => $port_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => $receive_by
                            ]);
                    } else {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->insert([
                                'truck_id' => $r->truck_id,
                                'unload_labor_package' => $r->labor_package_shed,
                                'unload_labor_weight' => $r->labor_unload_shed,
                                'unload_equipment_package' => $r->equipment_package_shed,
                                'unload_equip_weight' => $r->equip_unload_shed,
                                'unload_equip_name' => $r->equip_name_shed,
                                'unload_yard_shed' => $r->posted_shed,
                                'unload_shifting_flag' => $r->shifting_flag_shed,
                                'unload_receive_datetime' => date('Y-m-d H:i:s'),
                                'unload_comment' => $r->recive_comment_shed,
                                'port_id' => $port_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $receive_by
                            ]);
                   }
                }
            }

            //graph details save or update-------------------------------------

//            $truckExist = DB::select('SELECT * FROM yard_graphs WHERE truck_id=?', [$r->truck_id]);
//            if ($truckExist)//update
//            {
//                DB::table('yard_graphs')
//                    ->where('yard_graphs.truck_id', $r->truck_id)
//                    ->update([
//                        'yard_graphs.yard_id' => $r->posted_yard_shed,
//                        'yard_graphs.row' => $r->row,
//                        'yard_graphs.column' => $r->column,
//                        'yard_graphs.truck_id' => $r->truck_id,
//                        'yard_graphs.weight' => $r->receive_weight
//
//                    ]);
//            } else {
//                DB::table('yard_graphs')
//                    ->insert([
//                        'yard_graphs.yard_id' => $r->posted_yard_shed,
//                        'yard_graphs.row' => $r->row,
//                        'yard_graphs.column' => $r->column,
//                        'yard_graphs.truck_id' => $r->truck_id,
//                        'yard_graphs.weight' => $r->receive_weight
//
//                    ]);
//            }
            if ($wareHouse_shed_posting == true && $shed_yard_weights == true) {
                return 'Success';
            }
        }  else {
            return Response::json(['error' => 'Manifest Can Not Assign In This Shed'], 203);
        }
    }

    public function deleteShedData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;
        $yard_data = DB::select('SELECT count(s.id) as yard_count  
                                FROM shed_yard_weights AS s 
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? AND ya.yard_shed = 0', [$r->truck_id, $port_id]);
//        $shedYardAllocatedList = DB::select("SELECT yard_details.id as ShedYardId FROM
//                                             yard_details WHERE yard_details.shed_yard_id IN ($r->allocatedShedYard)
//                                             AND yard_details.port_id=?",[$port_id]);

        $TruckShed = $r->posted_shed;
        $userShedYardId = array();
        $usershedArray = array();
        foreach (Auth::user()->shedYards as $k => $v) {
            $userShedYardId[] = $v->id;
        }
        if(Auth::user()->role_id==11){//for maintencace role
            $shed_yards = ShedYard::where('port_id',$port_id)->get();
            foreach($shed_yards as $k => $v) {
                $userShedYardId[] = $v->id;
            }
        }
        $shed_yard_details = YardDetail::whereIn('shed_yard_id',$userShedYardId)->where('yard_shed', 1)->where('port_id', $port_id)->get();
        foreach ($shed_yard_details  as $k => $v){
            $usershedArray[] = $v->id;
        }
        $arrayImplode = implode(',', $usershedArray);
        $userShedArrayExplode = explode(',', $arrayImplode);
        if (in_array($TruckShed, $userShedArrayExplode, TRUE)) {
            $deleteShed = DB::table('shed_yard_weights')
                ->where('id', $r->shed_yard_weight_id)
                ->update([
                    'unload_labor_package' => null,
                    'unload_labor_weight' => null,
                    'unload_equip_weight' => null,
                    'unload_equipment_package' => null,
                    'unload_equip_name' => null,
                    'unload_yard_shed' => null,
                    'unload_shifting_flag' => null,
                    'unload_comment' => null
                ]);
            if(count($yard_data) > 0 && $yard_data[0]->yard_count == 0) {
                $updateTruckReceiveData = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => null,
                        'truck_entry_regs.receive_package' => null,
                        'truck_entry_regs.holtage_charge_flag' => 0,
                        /*'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : null,*/
                        'truck_entry_regs.receive_updated_by' => $user_id,
                        'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $updateTruckReceiveData = true;
            }

            if ($deleteShed == true && $updateTruckReceiveData == true) {
                return "Deleted";
            }
        } else {
            return Response::json(['error' => 'You Are Not Allowed to Clear Shed Receive'], 203);
        }
    }

    public function saveYardData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $shed_weight = DB::select('SELECT 
                                (IFNULL(s.unload_labor_weight,0) + IFNULL(s.unload_equip_weight,0)) AS shed_weight
                                FROM shed_yard_weights AS s
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? 
                                AND ya.yard_shed = 1', [$r->truck_id, $port_id]);

        if(count($shed_weight) > 0 && $r->receive_weight != null) {
            $yard_labor = $r->labor_unload_yard != null ? $r->labor_unload_yard : 0;
            $yard_equip = $r->equip_unload_yard != null ? $r->equip_unload_yard : 0;
            $total_yard = $yard_labor + $yard_equip;
            $total_weight = $shed_weight[0]->shed_weight + $total_yard;
            if($total_weight > $r->receive_weight) {
                return Response::json(['error' => 'Can not input more then receive weight. Shed already received '.$shed_weight[0]->shed_weight.' unit.' ], 203);
            }
        }
        $shedYardAllocatedList = DB::select("SELECT yard_details.id as ShedYardId FROM  
                                             yard_details  WHERE yard_details.shed_yard_id IN ($r->allocatedShedYard) AND yard_details.port_id=?",[$port_id]);

        $shedYardArray = array();
        foreach ($shedYardAllocatedList  as $k => $v){
            $shedYardArray[]  = $v->ShedYardId;
        }
        $arrayImplode = implode(',', $shedYardArray);
        $arrayExplode = explode(',', $arrayImplode);
        $TruckYard = $r->posted_yard;

        if (in_array($TruckYard, $arrayExplode, TRUE)) {
            $receive_by = Auth::user()->id;
            $chk_manifest = DB::select('SELECT m.manifest_posted_done_flag 
                                    FROM truck_entry_regs AS tr 
                                    JOIN manifests AS m ON tr.manf_id = m.id
                                    WHERE tr.id=? AND m.port_id=? AND tr.port_id=?', [$r->truck_id, $port_id, $port_id]);
            if ($chk_manifest[0]->manifest_posted_done_flag == 0) {
                return Response::json(['error' => 'Posted By CNF. Please Contact Posting Branch.'], 203);
            }
            if ($r->receive_created_at == null) {
                $wareHouse_yard_posting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_by' => $receive_by,
                        'truck_entry_regs.receive_created_at' => date('Y-m-d H:i:s')
                    ]);
                if($r->shed_yard_weight_id == null) {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.truck_id=? 
                        AND shed_yard_weights.port_id=? ORDER BY shed_yard_weights.id DESC LIMIT 1',[$r->truck_id, $port_id]);
                    if(count($get_receive_datetime) > 0) {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->where('truck_id', $r->truck_id)
                            ->update([
                                'unload_labor_package' => $r->labor_package_yard,
                                'unload_labor_weight' => $r->labor_unload_yard,
                                'unload_equipment_package' => $r->equipment_package_yard,
                                'unload_equip_weight' => $r->equip_unload_yard,
                                'unload_equip_name' => $r->equip_name_yard,
                                'unload_yard_shed' => $r->posted_yard,
                                'unload_shifting_flag' => $r->shifting_flag_yard,
                                'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                                'unload_comment' => $r->recive_comment_yard,
                                'port_id' => $port_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => $receive_by
                            ]);
                    } else {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->insert([
                                'truck_id' => $r->truck_id,
                                'unload_labor_package' => $r->labor_package_yard,
                                'unload_labor_weight' => $r->labor_unload_yard,
                                'unload_equipment_package' => $r->equipment_package_yard,
                                'unload_equip_weight' => $r->equip_unload_yard,
                                'unload_equip_name' => $r->equip_name_yard,
                                'unload_yard_shed' => $r->posted_yard,
                                'unload_shifting_flag' => $r->shifting_flag_yard,
                                'unload_receive_datetime' => date('Y-m-d H:i:s'),
                                'unload_comment' => $r->recive_comment_yard,
                                'port_id' => $port_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $receive_by
                            ]);
                    }
                } else {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.id=? AND shed_yard_weights.port_id=?',[$r->shed_yard_weight_id, $port_id]);
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_weight_id)
                        ->update([
                            'truck_id' => $r->truck_id,
                            'unload_labor_package' => $r->labor_package_yard,
                            'unload_labor_weight' => $r->labor_unload_yard,
                            'unload_equipment_package' => $r->equipment_package_yard,
                            'unload_equip_weight' => $r->equip_unload_yard,
                            'unload_equip_name' => $r->equip_name_yard,
                            'unload_yard_shed' => $r->posted_yard,
                            'unload_shifting_flag' => $r->shifting_flag_yard,
                            'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                            'unload_comment' => $r->recive_comment_yard,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                }
            } else {
                $wareHouse_yard_posting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_updated_by' => $receive_by,
                        'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                    ]);
                if ($r->shed_yard_weight_id != null) {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights WHERE shed_yard_weights.id = ? AND shed_yard_weights.port_id=?',[$r->shed_yard_weight_id, $port_id]);
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_weight_id)
                        ->update([
                            'truck_id' => $r->truck_id,
                            'unload_labor_package' => $r->labor_package_yard,
                            'unload_labor_weight' => $r->labor_unload_yard,
                            'unload_equipment_package' => $r->equipment_package_yard,
                            'unload_equip_weight' => $r->equip_unload_yard,
                            'unload_equip_name' => $r->equip_name_yard,
                            'unload_yard_shed' => $r->posted_yard,
                            'unload_shifting_flag' => $r->shifting_flag_yard,
                            'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                            'unload_comment' => $r->recive_comment_yard,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                } else {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime, shed_yard_weights.unload_yard_shed
                        FROM shed_yard_weights WHERE shed_yard_weights.truck_id=?
                        AND shed_yard_weights.port_id=? ORDER BY shed_yard_weights.id DESC LIMIT 1',[$r->truck_id, $port_id]);
                    if(count($get_receive_datetime) > 0 && $get_receive_datetime[0]->unload_yard_shed == null) {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->where('truck_id', $r->truck_id)
                            ->update([
                                'unload_labor_package' => $r->labor_package_yard,
                                'unload_labor_weight' => $r->labor_unload_yard,
                                'unload_equipment_package' => $r->equipment_package_yard,
                                'unload_equip_weight' => $r->equip_unload_yard,
                                'unload_equip_name' => $r->equip_name_yard,
                                'unload_yard_shed' => $r->posted_yard,
                                'unload_shifting_flag' => $r->shifting_flag_yard,
                                'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                                'unload_comment' => $r->recive_comment_yard,
                                'port_id' => $port_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => $receive_by
                            ]);
                    } else {
                        $shed_yard_weights = DB::table('shed_yard_weights')
                            ->insert([
                                'truck_id' => $r->truck_id,
                                'unload_labor_package' => $r->labor_package_yard,
                                'unload_labor_weight' => $r->labor_unload_yard,
                                'unload_equipment_package' => $r->equipment_package_yard,
                                'unload_equip_weight' => $r->equip_unload_yard,
                                'unload_equip_name' => $r->equip_name_yard,
                                'unload_yard_shed' => $r->posted_yard,
                                'unload_shifting_flag' => $r->shifting_flag_yard,
                                'unload_receive_datetime' => date('Y-m-d H:i:s'),
                                'unload_comment' => $r->recive_comment_yard,
                                'port_id' => $port_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $receive_by
                            ]);
                    }
                }
            }

            //graph details save or update-------------------------------------

//            $truckExist = DB::select('SELECT * FROM yard_graphs WHERE truck_id=?', [$r->truck_id]);
//            if ($truckExist)//update
//            {
//                DB::table('yard_graphs')
//                    ->where('yard_graphs.truck_id', $r->truck_id)
//                    ->update([
//                        'yard_graphs.yard_id' => $r->posted_yard_shed,
//                        'yard_graphs.row' => $r->row,
//                        'yard_graphs.column' => $r->column,
//                        'yard_graphs.truck_id' => $r->truck_id,
//                        'yard_graphs.weight' => $r->receive_weight
//
//                    ]);
//            } else {
//                DB::table('yard_graphs')
//                    ->insert([
//                        'yard_graphs.yard_id' => $r->posted_yard_shed,
//                        'yard_graphs.row' => $r->row,
//                        'yard_graphs.column' => $r->column,
//                        'yard_graphs.truck_id' => $r->truck_id,
//                        'yard_graphs.weight' => $r->receive_weight
//
//                    ]);
//            }
            if ($wareHouse_yard_posting == true && $shed_yard_weights == true) {
                return 'Success';
            }
        }  else {
            return Response::json(['error' => 'Manifest Can not assign in this Yard'], 203);
        }
    }

    public function deleteYardData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;
        $shed_data = DB::select('SELECT count(s.id) as shed_count  
                                FROM shed_yard_weights AS s 
                                JOIN yard_details AS ya ON ya.id = s.unload_yard_shed
                                WHERE s.truck_id = ? AND s.port_id = ? AND ya.yard_shed = 1', [$r->truck_id, $port_id]);
//        $shedYardAllocatedList = DB::select("SELECT yard_details.id as id FROM
//                                             yard_details WHERE yard_details.shed_yard_id IN ($r->allocatedShedYard)
//                                             AND yard_details.port_id=?",[$port_id]);

        $TruckYard = $r->posted_yard;
        $userShedYardId = array();
        $userYardArray = array();
        foreach (Auth::user()->shedYards as $k => $v) {
            $userShedYardId[] = $v->id;
        }
        if (Auth::user()->role_id==11){//for maintencace role
            $shed_yards = ShedYard::where('port_id',$port_id)->get();
            foreach($shed_yards as $k => $v) {
                $userShedYardId[] = $v->id;
            }
        }
        $shed_yard_details = YardDetail::whereIn('shed_yard_id',$userShedYardId)->where('yard_shed', 0)->where('port_id', $port_id)->get();
        foreach ($shed_yard_details  as $k => $v){
            $userYardArray[] = $v->id;
        }
        $arrayImplode = implode(',', $userYardArray);
        $userYardArrayExplode = explode(',', $arrayImplode);
        if (in_array($TruckYard, $userYardArrayExplode, TRUE)) {
            $deleteyard = DB::table('shed_yard_weights')
                ->where('id', $r->shed_yard_weight_id)
                ->update([
                    'unload_labor_package' => null,
                    'unload_labor_weight' => null,
                    'unload_equip_weight' => null,
                    'unload_equipment_package' => null,
                    'unload_equip_name' => null,
                    'unload_yard_shed' => null,
                    'unload_shifting_flag' => null,
                    'unload_comment' => null
                ]);
            if(count($shed_data) && $shed_data[0]->shed_count == 0) {
                $updateTruckReceiveData = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->truck_id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => null,
                        'truck_entry_regs.receive_package' => null,
                        'truck_entry_regs.holtage_charge_flag' => 0,
                        /*'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : null,*/
                        'truck_entry_regs.receive_updated_by' => $user_id,
                        'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $updateTruckReceiveData = true;
            }
            if ($deleteyard == true && $updateTruckReceiveData == true) {
                return "Deleted";
            }
        } else {
            return Response::json(['error' => 'You Are Not Allowed to Clear Yard Receive'], 203);
        }
    }

    public function saveWareHouseEntryFormData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $shedYardAllocatedList = DB::select("SELECT yard_details.id as ShedYardId FROM  
                                             yard_details  WHERE yard_details.port_id=? AND yard_details.shed_yard_id IN ($r->allocatedShedManif)",[$port_id]);

        $shedYardArray = array();

        foreach ($shedYardAllocatedList  as $k => $v){
            $shedYardArray[]  = $v->ShedYardId;
        }
        $arrayImplode = implode(',', $shedYardArray);
        $arrayExplode = explode(',', $arrayImplode);
        $TruckYardShed = $r->posted_yard_shed_t;

        if (in_array($TruckYardShed, $arrayExplode, TRUE)) {
            $receive_by = Auth::user()->id;
            $chk_manifest = DB::select('SELECT m.manifest_posted_done_flag 
                                    FROM truck_entry_regs AS tr 
                                    JOIN manifests AS m ON tr.manf_id = m.id
                                    WHERE tr.id = ? AND tr.port_id=? AND m.port_id=?', [$r->id,$port_id,$port_id]);
            if ($chk_manifest[0]->manifest_posted_done_flag == 0) {
                return Response::json(['posting_error' => 'Posted By CNF. Please Contact Posting Branch.'], 203);
            }
            if ($r->receive_created_at == null) {
                $WareHousePosting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        //'truck_entry_regs.recive_comment' => $r->recive_comment,
                        //'truck_entry_regs.truck_posted_yard_shed' => $r->posted_yard_shed_t,
                        //'truck_entry_regs.equip_name' => $r->equip_name,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_by' => $receive_by,
                        'truck_entry_regs.receive_created_at' => date('Y-m-d H:i:s')
                    ]);

                $shed_yard_weights = DB::table('shed_yard_weights')
                    ->insert([
                        'truck_id' => $r->truck_id,
                        'port_id' =>$port_id,
                        'unload_labor_package' => $r->labor_package,
                        'unload_labor_weight' => $r->labor_unload,
                        'unload_equipment_package' => $r->equipment_package,
                        'unload_equip_weight' => $r->equip_unload,
                        'unload_equip_name' => $r->equip_name,
                        'unload_yard_shed' => $r->posted_yard_shed_t,
                        'unload_shifting_flag' => $r->shifting_flag,
                        'unload_receive_datetime' => date('Y-m-d H:i:s'),
                        'unload_comment' => $r->recive_comment,
                        'port_id' => Auth::user()->port_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $receive_by

                    ]);


            } else {
                $WareHousePosting = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->update([
                        'truck_entry_regs.receive_weight' => $r->receive_weight,
                        'truck_entry_regs.receive_package' => $r->receive_package,
                        //'truck_entry_regs.recive_comment' => $r->recive_comment,
                        //'truck_entry_regs.truck_posted_yard_shed' => $r->posted_yard_shed_t,
                        //'truck_entry_regs.equip_name' => $r->equip_name,
                        'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                        'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                        'truck_entry_regs.receive_updated_by' => $receive_by,
                        'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                    ]);
                if ($r->shed_yard_details_id != null) {
                    $get_receive_datetime = DB::select('SELECT shed_yard_weights.unload_receive_datetime
                        FROM shed_yard_weights
                        WHERE shed_yard_weights.id = ?',[$r->shed_yard_details_id]);
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_details_id)
                        ->update([
                            'truck_id' => $r->truck_id,
                            'unload_labor_package' => $r->labor_package,
                            'unload_labor_weight' => $r->labor_unload,
                            'unload_equipment_package' => $r->equipment_package,
                            'unload_equip_weight' => $r->equip_unload,
                            'unload_equip_name' => $r->equip_name,
                            'unload_yard_shed' => $r->posted_yard_shed_t,
                            'unload_shifting_flag' => $r->shifting_flag,
                            'unload_receive_datetime' => $get_receive_datetime[0]->unload_receive_datetime,
                            'unload_comment' => $r->recive_comment,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                } else {
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->insert([
                            'truck_id' => $r->truck_id,
                            'port_id' =>$port_id,
                            'unload_labor_package' => $r->labor_package,
                            'unload_labor_weight' => $r->labor_unload,
                            'unload_equipment_package' => $r->equipment_package,
                            'unload_equip_weight' => $r->equip_unload,
                            'unload_equip_name' => $r->equip_name,
                            'unload_yard_shed' => $r->posted_yard_shed_t,
                            'unload_shifting_flag' => $r->shifting_flag,
                            'unload_receive_datetime' => date('Y-m-d H:i:s'),
                            'unload_comment' => $r->recive_comment,
                            'port_id' => Auth::user()->port_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $receive_by
                        ]);
                }
            }

            //graph details save or update-------------------------------------

            $truckExist = DB::select('SELECT * FROM yard_graphs WHERE truck_id=?', [$r->truck_id]);
            if($truckExist)//update
            {
                DB::table('yard_graphs')
                    ->where('yard_graphs.truck_id', $r->truck_id)
                    ->update([
                        'yard_graphs.yard_id' => $r->posted_yard_shed,
                        'yard_graphs.row' => $r->row,
                        'yard_graphs.column' => $r->column,
                        'yard_graphs.truck_id' => $r->truck_id,
                        'yard_graphs.weight' => $r->receive_weight

                    ]);
            } else {
                DB::table('yard_graphs')
                    ->insert([
                        'yard_graphs.yard_id' => $r->posted_yard_shed,
                        'yard_graphs.row' => $r->row,
                        'yard_graphs.column' => $r->column,
                        'yard_graphs.truck_id' => $r->truck_id,
                        'yard_graphs.weight' => $r->receive_weight

                    ]);
            }


            //================EQUIPMENT OR LABOUR OR BOTH FLAG INPUT=================
            if ($r->labor_unload != NULL || $r->labor_unload != 0) {
                $offloadingManualChargesId = 32; //FROM charge Details Table
                $offloadingManual = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->where('truck_entry_regs.port_id', $port_id)
                    ->where('manifests.port_id', $port_id)
                    ->update([
//                        'truck_entry_regs.offloading_manual' => $offloadingManualChargesId
                    ]);
            } else {
                $offloadingManual = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->where('truck_entry_regs.port_id', $port_id)
                    ->where('manifests.port_id', $port_id)
                    ->update([
//                        'truck_entry_regs.offloading_manual' => NULL
                    ]);
            }
            if ($r->equip_unload != NULL || $r->equip_unload != 0) {
                $loadingOffloadingEquipmentId = 36; //FROM charge Details Table
                $offloadingEquipment = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->where('truck_entry_regs.port_id', $port_id)
                    ->where('manifests.port_id', $port_id)
                    ->update([
//                        'truck_entry_regs.offloading_equipment' => $loadingOffloadingEquipmentId
                    ]);
            } else {
                $offloadingEquipment = DB::table('truck_entry_regs')
                    ->where('truck_entry_regs.id', $r->id)
                    ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                    ->where('truck_entry_regs.port_id', $port_id)
                    ->where('manifests.port_id', $port_id)
                    ->update([
//                        'truck_entry_regs.offloading_equipment' => NULL
                    ]);
            }
            //================EQUIPMENT OR LABOUR OR BOTH FLAG INPUT=================
            if ($WareHousePosting == true && ($offloadingManual == true || $offloadingEquipment == true)) {
                return 'Success';
            }


        } else {
//            $abc = "value not found";
            return Response::json(['Shed_yard_error' => 'Manifest Can not assign in this Shed'], 206);
        }


//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"Testing".$abc);
//            fclose($file);
//            return;


    }
    
    public function dateWiseWarehouseReceiveReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        $requestedDate = $r->date;
        if (Auth::user()->role->id == 12) {
            $date_wise = DB::select("SELECT CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(t.manifes_no,'/',1)) AS UNSIGNED) AS justManifest, 
                            t.manifes_no, t.manifest_id, t.truck_no, t.truck_type, t.receive_by 
                            FROM
                            (
                            SELECT (SELECT manifest FROM manifests WHERE manifests.port_id=? AND manifests.id=truck_entry_regs.manf_id) AS manifes_no, 
                            (SELECT manifests.id FROM manifests WHERE manifests.port_id=? AND manifests.id=truck_entry_regs.manf_id) AS manifest_id,
                            truck_entry_regs.truck_no,truck_entry_regs.truck_type,users.name AS receive_by
                            FROM truck_entry_regs
                            JOIN users ON users.id = truck_entry_regs.receive_by
                            JOIN roles ON roles.id = users.role_id
                            WHERE truck_entry_regs.port_id=? AND DATE(receive_datetime)=?  AND roles.name='TransShipment'
                            ) AS t
                            ORDER BY justManifest DESC", [$port_id,$port_id,$port_id,$r->date]);
        } else {
            $date_wise = DB::select("SELECT CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(t.manifes_no,'/',1)) AS UNSIGNED) AS justManifest, 
                            t.manifes_no, t.manifest_id, t.truck_no, t.truck_type, t.receive_by 
                            FROM
                            (
                            SELECT (SELECT manifest FROM manifests WHERE manifests.port_id=? AND manifests.id=truck_entry_regs.manf_id) AS manifes_no, 
                            (SELECT manifests.id FROM manifests WHERE manifests.port_id=? AND manifests.id=truck_entry_regs.manf_id) AS manifest_id,
                            truck_entry_regs.truck_no,truck_entry_regs.truck_type,users.name AS receive_by
                            FROM truck_entry_regs
                            JOIN users ON users.id = truck_entry_regs.receive_by
                            JOIN roles ON roles.id = users.role_id
                            WHERE truck_entry_regs.port_id=? AND DATE(receive_datetime)=? AND roles.name!='TransShipment'
                            ) AS t
                            ORDER BY justManifest DESC", [$port_id,$port_id,$port_id,$r->date]);
        }
        if ($date_wise) {
            $pdf = PDF::loadView('default.warehouse.receive.reports.date-wise-warehouse-report', ['data' => $date_wise, 'requestedDate' => $r->date, 'date' => $currentTime]);
            return $pdf->stream('DateWiseWarehouseReport-' . $currentTime . '.pdf');
        }
        return view('default.warehouse.not-found', compact('requestedDate'));

    }

    public function getManifestGrossWeightForReceive($manifest_id, $truck_id) {
        $getWeight = DB::select('SELECT m.gweight, tr.tweight_wbridge
                        FROM manifests AS m
                        LEFT JOIN truck_entry_regs AS tr ON tr.manf_id = m.id
                          WHERE m.id = ? AND tr.id = ? AND m.port_id=?',[$manifest_id, $truck_id, Session::get('PORT_ID')]);
        return json_encode($getWeight);
    }


    public function truckDifferentShedYard(Request $r)
    {

        $port_id = Session::get('PORT_ID');
        $shed_yard_weights_data = DB::select("  SELECT COUNT(shed_yard_weights.id) AS moreThanOneTruckShed
FROM shed_yard_weights WHERE shed_yard_weights.truck_id = ? AND shed_yard_weights.port_id=?", [$r->truck_id,$port_id]);


        return json_encode($shed_yard_weights_data);
    }

    //============================Warehouse Entry END==================================


    //=====================DeliveryRequest============================================

    public function deliveryRequest($manifest = null, $truck = null, $year = null)
    {
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;

//        $delivery_requisition_data = DB::select('SELECT delivery_requisitions.id AS delivery_id, COUNT(delivery_requisitions.partial_status) AS  partial_status
//FROM delivery_requisitions WHERE  delivery_requisitions.manifest_id=?', ['18259']);

        return view('default.warehouse.delivery.warehouse-delivery-request-entry-form', ['manifest_no' => $manifest_no]);

    }


    //======================Add Importer
    public function saveCnfNameAinData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $chkDuplicate = DB::table('cnf_details')
            ->where('cnf_details.ain_no', $r->ain_no)
            ->count();
        if ($chkDuplicate > 0) {
            return Response::json(['duplicate' => 'AIN No Already Exist.'], 401);
        }
        //return;
        $createdBy = Auth::user()->id;
        $createDate = date('Y-m-d H:i:s');
        $cnfNameAin = DB::table('cnf_details')
            ->insertGetId([
                'ain_no' => $r->ain_no,
                'cnf_name' => $r->cnf_name,
                'created_by' => $createdBy,
                'created_at' => $createDate
            ]);

        DB::table('cnf_port')
            ->insert([
                'cnf_id' => $cnfNameAin,
                'port_id' => $port_id

            ]);

        if ($cnfNameAin == true) {
            return "Success";
        }
    }


    public function ainNoCnfNameData(Request $req)
    {
        $term = $req->term;//Input::get('term');
        $results = array();
        $queries = DB::table('cnf_details')
            ->where('ain_no', 'LIKE', '%' . $term . '%')
            // ->orWhere('last_name', 'LIKE', '%'.$term.'%')
            ->take(20)->get();
        foreach ($queries as $query) {
            $results[] = ['id' => $query->ain_no, 'value' => $query->ain_no, 'cnf_name' => $query->cnf_name, 'ain_no' => $query->ain_no];

        }

        return json_encode($results);
    }




    public function saveDeliveryRequestData(Request $r)
    {
//         $value = Auth::user()->role->name;

        if (Auth::user()->role->name == 'Assessment') {
            $ain_id = 0;
//            if ($r->custom_approved_date == null){
            $file = fopen("Truckentry.txt", "w");
            echo fwrite($file, "save assessment");
            fclose($file);
            $requestData = DB::table('manifests')
                ->where('id', $r->manifest_id)
                ->update([
                    'be_no' => $r->be_no,
                    'be_date' => $r->be_date,
                    'ain_no' => $r->ain_no,
                    'cnf_id' => $ain_id,
                    'cnf_name' => $r->cnf_name,
                    'no_del_truck' => $r->no_del_truck,
                    'carpenter_packages' => $r->carpenter_packages,
                    'carpenter_repair_packages' => $r->carpenter_repair_packages,
                    'custom_release_order_no' => $r->custom_release_order_no,
                    'custom_release_order_date' => $r->custom_release_order_date,
                    'approximate_delivery_date' => $r->approximate_delivery_date,
                    'approximate_delivery_type' => $r->approximate_delivery_type,
                    'custom_approved_by' => Auth::user()->id,
                    'custom_approved_date' => date('Y-m-d H:i:s'),
                    'local_transport_type' => $r->local_transport_type,
                    'transport_truck' => $r->transport_truck,
                    'transport_van' => $r->transport_van
                ]);
//            }else{
//
//                $file = fopen("Truckentry.txt","w");
//                echo fwrite($file,"update assessment");
//                fclose($file);
//
//                $requestData = DB::table('manifests')
//                    ->where('id', $r->manifest_id)
//                    ->update([
//                        'be_no' => $r->be_no,
//                        'be_date' => $r->be_date,
//
//                        'ain_no' => $r->ain_no,
//                        'cnf_id' => $ain_id,
//
//                        'cnf_name' => $r->cnf_name,
//                        'no_del_truck' => $r->no_del_truck,
//                        'carpenter_packages'=>$r->carpenter_packages,
//                        'carpenter_repair_packages'=>$r->carpenter_repair_packages,
//                        'custom_release_order_no' => $r->custom_release_order_no,
//                        'custom_release_order_date' => $r->custom_release_order_date,
//                        'approximate_delivery_date' => $r->approximate_delivery_date,
//                        'approximate_delivery_type' => $r->approximate_delivery_type,
//                        'custom_approved_updated_by' => Auth::user()->id,
//                        'custom_approved_updated_at' => date('Y-m-d H:i:s'),
//                        'local_transport_type' => $r->local_transport_type
//                    ]);
//
//            }


//            if($requestData) {
//                return 'Success';
//            }


        } else {
            $ain_id = DB::select('SELECT cnf_details.id AS cnf_id FROM cnf_details WHERE  ain_no=?', [$r->ain_no]);

            if ($r->custom_approved_date == null) {

                $requestData = DB::table('manifests')
                    ->where('id', $r->manifest_id)
                    ->update([
                        'be_no' => $r->be_no,
                        'be_date' => $r->be_date,
                        'paid_tax' => $r->paid_tax,
                        'ain_no' => $r->ain_no,
                        'cnf_id' => $ain_id[0]->cnf_id,
                        'paid_date' => $r->paid_date,
                        'cnf_name' => $r->cnf_name,
                        'no_del_truck' => $r->no_del_truck,
                        'carpenter_packages' => $r->carpenter_packages,
                        'carpenter_repair_packages' => $r->carpenter_repair_packages,
                        'custom_release_order_no' => $r->custom_release_order_no,
                        'custom_release_order_date' => $r->custom_release_order_date,
                        'approximate_delivery_date' => $r->approximate_delivery_date,
                        'approximate_delivery_type' => $r->approximate_delivery_type,
                        'custom_approved_by' => Auth::user()->id,
                        'custom_approved_date' => date('Y-m-d H:i:s'),
                        'local_transport_type' => $r->local_transport_type,
                        'transport_truck' => $r->transport_truck,
                        'transport_van' => $r->transport_van
                    ]);
            } else {
                $requestData = DB::table('manifests')
                    ->where('id', $r->manifest_id)
                    ->update([
                        'be_no' => $r->be_no,
                        'be_date' => $r->be_date,
                        'paid_tax' => $r->paid_tax,
                        'ain_no' => $r->ain_no,
                        'cnf_id' => $ain_id[0]->cnf_id,
                        'paid_date' => $r->paid_date,
                        'cnf_name' => $r->cnf_name,
                        'no_del_truck' => $r->no_del_truck,
                        'carpenter_packages' => $r->carpenter_packages,
                        'carpenter_repair_packages' => $r->carpenter_repair_packages,
                        'custom_release_order_no' => $r->custom_release_order_no,
                        'custom_release_order_date' => $r->custom_release_order_date,
                        'approximate_delivery_date' => $r->approximate_delivery_date,
                        'approximate_delivery_type' => $r->approximate_delivery_type,
                        'custom_approved_updated_by' => Auth::user()->id,
                        'custom_approved_updated_at' => date('Y-m-d H:i:s'),
                        'local_transport_type' => $r->local_transport_type,
                        'transport_truck' => $r->transport_truck,
                        'transport_van' => $r->transport_van
                    ]);
            }
        }

//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello".$value);
//        fclose($file);
//        return;

//        if(Auth::user()->role->name == 'Assessment') {
//            $requestData = DB::table('manifests')
//            ->where('id', $r->manifest_id)
//            ->update([
//                'be_no' => $r->be_no,
//                'be_date' => $r->be_date,
//                'paid_tax' => $r->paid_tax,
//                'ain_no' => $r->ain_no,
//                'cnf_id' => $ain_id,
//                'paid_date' => $r->paid_date,
//                'cnf_name' => $r->cnf_name,
//                'no_del_truck' => $r->no_del_truck,
//                'carpenter_packages'=>$r->carpenter_packages,
//                'carpenter_repair_packages'=>$r->carpenter_repair_packages,
//                'custom_release_order_no' => $r->custom_release_order_no,
//                'custom_release_order_date' => $r->custom_release_order_date,
//                'approximate_delivery_date' => $r->approximate_delivery_date,
//                'approximate_delivery_type' => $r->approximate_delivery_type,
//                'custom_approved_by' => Auth::user()->id,
//                'custom_approved_date' => date('Y-m-d H:i:s'),
//                'local_transport_type' => $r->local_transport_type
//            ]);
//            if($requestData) {
//                return 'Success';
//            }
//        }

//        if($r->custom_approved_date == null) {
//            $requestData = DB::table('manifests')
//            ->where('id', $r->manifest_id)
//            ->update([
//                'be_no' => $r->be_no,
//                'be_date' => $r->be_date,
//                'paid_tax' => $r->paid_tax,
//                'ain_no' => $r->ain_no,
//                'cnf_id' => $ain_id[0]->cnf_id,
//                'paid_date' => $r->paid_date,
//                'cnf_name' => $r->cnf_name,
//                'no_del_truck' => $r->no_del_truck,
//                'carpenter_packages'=>$r->carpenter_packages,
//                'carpenter_repair_packages'=>$r->carpenter_repair_packages,
//                'custom_release_order_no' => $r->custom_release_order_no,
//                'custom_release_order_date' => $r->custom_release_order_date,
//                'approximate_delivery_date' => $r->approximate_delivery_date,
//                'approximate_delivery_type' => $r->approximate_delivery_type,
//                'custom_approved_by' => Auth::user()->id,
//                'custom_approved_date' => date('Y-m-d H:i:s'),
//                'local_transport_type' => $r->local_transport_type
//            ]);
//        } else {
//            $requestData = DB::table('manifests')
//            ->where('id', $r->manifest_id)
//            ->update([
//                'be_no' => $r->be_no,
//                'be_date' => $r->be_date,
//                'paid_tax' => $r->paid_tax,
//                'ain_no' => $r->ain_no,
//                'cnf_id' => $ain_id[0]->cnf_id,
//                'paid_date' => $r->paid_date,
//                'cnf_name' => $r->cnf_name,
//                'no_del_truck' => $r->no_del_truck,
//                'carpenter_packages'=>$r->carpenter_packages,
//                'carpenter_repair_packages'=>$r->carpenter_repair_packages,
//                'custom_release_order_no' => $r->custom_release_order_no,
//                'custom_release_order_date' => $r->custom_release_order_date,
//                'approximate_delivery_date' => $r->approximate_delivery_date,
//                'approximate_delivery_type' => $r->approximate_delivery_type,
//                'custom_approved_updated_by' => Auth::user()->id,
//                'custom_approved_updated_at' => date('Y-m-d H:i:s'),
//                'local_transport_type' => $r->local_transport_type
//            ]);
//        }

        if ($requestData) {
            return 'Success';
        } else {
            return Response::json(['notSaved' => 'data not saved'], 304);

////            abort(405, 'Unauthorized action.');
        }
    }


    public function saveBdTruckData(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        //return 'Hello';
        $bdTruckType = DB::SELECT("SELECT type_name  FROM vehicle_type_bd  WHERE id= ?", [$req->truck_type_id]);
        $partial_status = DB::SELECT('SELECT MAX(partial_status) AS partial_stat FROM assessments
                                    WHERE manifest_id=? AND assessments.port_id =?', [$req->manf_id,$port_id]);
        //return $partial_status;

        if ($req->bd_truck_id == null) {//Save Data

            $saveBdTruckData = DB::table('truck_deliverys')->insert([
                'manf_id' => $req->manf_id,
                'partial_status' => $partial_status[0]->partial_stat,
                'truck_no' => $req->truck_no . '-' . $bdTruckType[0]->type_name,
                'truck_type_id' => $req->truck_type_id,
                'driver_name' => $req->driver_name,
                'labor_load' => $req->labor_load,
                'port_id' =>$port_id,
                'labor_package' => $req->labor_package,
                'equip_load' => $req->equip_load,
                'equipment_package' => $req->equipment_package,
                'weightment_flag' => $req->weightment_flag,
                'equip_name' => $req->equip_name,
                'haltage_day' => $req->haltage_day,
                'delivery_req_by' => Auth::user()->id,
                'delivery_req_dt' => date('Y-m-d H:i:s'),
                'delivery_dt' => $req->delivery_dt,
                'transport_type' => $req->transport_type
            ]);

            if ($saveBdTruckData == 1) {
                return 'saved';
            } else {
                return 'errors';
            }


        } else {//update bd Truck Data

            $updateBdTruckData = DB::table('truck_deliverys')
                ->where('id', $req->bd_truck_id)
                ->update([
                    //'partial_status'=>$partial_status[0]->partial_stat,
                    'truck_no' => $req->truck_no . '-' . $bdTruckType[0]->type_name,
                    'truck_type_id' => $req->truck_type_id,
                    'driver_name' => $req->driver_name,

                    'labor_load' => $req->labor_load,
                    'labor_package' => $req->labor_package,
                    'equip_load' => $req->equip_load,
                    'equipment_package' => $req->equipment_package,
                    'equip_name' => $req->equip_name,
                    'weightment_flag' => $req->weightment_flag,

                    'delivery_dt' => $req->delivery_dt,
                    'loading_unit' => $req->loading_unit,
                    'haltage_day' => $req->haltage_day,
                    'delivery_req_updated_by' => Auth::user()->id,
                    'delivery_req_updated_at' => date('Y-m-d H:i:s'),
                    'transport_type' => $req->transport_type

                ]);
            return 'updated';
        }
    }

//END saveBdTruckData


    public function getBdTruckData($id)
    {
        $port_id = Session::get('PORT_ID');
        $trucks = DB::table('truck_deliverys AS td')
            ->where('td.manf_id', $id)
            ->where('td.port_id', $port_id)
            ->join('manifests AS m', 'td.manf_id', '=', 'm.id')
            ->join('vehicle_type_bd AS v', 'td.truck_type_id', '=', 'v.id')
            ->select(
                'm.id AS m_id',
                'm.manifest',
                'm.gweight AS m_gweight',
                'm.nweight AS m_nweight',
                'm.no_del_truck',
                'td.id AS bd_truck_id',
                'td.truck_no',
                'td.truck_type_id',
                'td.driver_name',
                'td.gweight AS td_gweight',
                'td.labor_load',
                'td.labor_package',
                'td.equip_load',
                'td.equipment_package',
                'td.equip_name',
                'td.delivery_dt',
                'td.weightment_flag',
                'td.haltage_day',
                'v.type_name',
                'td.transport_type'

            )
            ->get();


        /*$totalWeight=DB::table('truck_entry_regs as t')
            ->selectRaw('t.manf_id, sum(t.tweight_wbridge) as Totalweight')
            ->where('t.manf_id', $id)
            ->groupBy('t.manf_id')
            ->pluck('Totalweight', 't.manf_id');*/

        return json_encode($trucks);

    }



    public function deleteBdTruck($id)
    {
        DB::table('truck_deliverys')->where('id', $id)->delete();

        return 'success';
    }


    //Called from Warehouse Receive Page Too
    public function manifestInformationDetailsData($manifest, $truck, $year)
    {
        $port_id = Session::get('PORT_ID');
        $totalFigure = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        //return $totalFigure;

        if (Auth::user()->role->name == 'TransShipment') {
            $chkPermission = DB::select(' SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        JOIN users ON manifests.manifest_posted_by = users.id
                                        JOIN roles ON roles.id = users.role_id
                                        WHERE roles.name="TransShipment" 
                                        AND manifests.manifest=? AND manifests.port_id=?', [$totalFigure,$port_id]);
        } else {
            $chkPermission = DB::select(' SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        JOIN users ON manifests.manifest_posted_by = users.id
                                        JOIN roles ON roles.id = users.role_id
                                        WHERE roles.name!="TransShipment" 
                                        AND manifests.manifest=? AND manifests.port_id=?', [$totalFigure,$port_id]);
        }
        //return $chkPermission;
        if ($chkPermission[0]->valid == 0) {
            return view('noPermission');
        }

        $todayWithTime = date('Y-m-d h:i:s a');
        $bdTruckData = DB::table('manifests')
            ->join('truck_deliverys', 'truck_deliverys.manf_id', '=', 'manifests.id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->where('truck_deliverys.port_id', $port_id)
            ->select('manifests.manifest', 'truck_deliverys.*')
            ->get();

        $indianTruckData = DB::table('manifests')
            ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->where('truck_entry_regs.port_id', $port_id)
            ->select('manifests.manifest', 'truck_entry_regs.*')
            ->get();
        $manifestDetails = DB::table('manifests')
            ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
            ->join('vatregs', 'vatregs.id', '=', 'manifests.vatreg_id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->select('manifests.*', 'cargo_details.*', 'vatregs.*')
            ->get();
        $deliveryRequisition = DB::select('SELECT delivery_requisitions.*,manifests.manifest  FROM delivery_requisitions 
JOIN manifests ON manifests.id = delivery_requisitions.manifest_id
WHERE manifests.manifest=? AND manifests.port_id=? AND delivery_requisitions.port_id=?', [$totalFigure,$port_id,$port_id]);
        //return $manifestDetails;
        $pdf = PDF::loadView('default.warehouse.delivery.reports.manifest-be-done-report', [
            'bdTruckData' => $bdTruckData,
            'indianTruckData' => $indianTruckData,
            'todayWithTime' => $todayWithTime,
            'manifestDetails' => $manifestDetails,
            'manifestNo' => $totalFigure,
            'deliveryRequisition' =>$deliveryRequisition
        ])
            ->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('manifestBEDonePDFReport.blade.pdf');
    }


    public function todaysTruckDeliveryEntryReport()
    {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');

        // $todaysTruckDeliveryEntry = DB::table('truck_deliverys')
        //     ->where('delivery_dt','LIKE',"%$today%")
        //     ->join('manifests', 'manifests.id', '=','truck_deliverys.manf_id')
        //     ->select('truck_deliverys.id',
        //         'truck_deliverys.truck_no',
        //         'truck_deliverys.driver_name',
        //         // 'truck_deliverys.gweight',
        //         'truck_deliverys.loading_unit',
        //         'truck_deliverys.package',
        //         //DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
        //         'truck_deliverys.delivery_dt',
        //         //'truck_deliverys.approve_dt',
        //         'truck_deliverys.labor_load',
        //         'truck_deliverys.labor_package',
        //         'truck_deliverys.equip_load',
        //         'truck_deliverys.equip_name',
        //         'truck_deliverys.equipment_package',
        //         //'truck_deliverys.loading_flag',
        //         'manifests.manifest')
        //     ->get();
        if (Auth::user()->role->name == 'TransShipment') {
//            $todaysTruckDeliveryEntry = DB::table('truck_deliverys')
//                ->where('delivery_dt', 'LIKE', "%$today%")
//                ->where('roles.name', '=', "TransShipment")
//                ->where('manifests.port_id', $port_id)
//                ->where('truck_deliverys.port_id', $port_id)
//                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
//                ->join('users', 'truck_deliverys.delivery_req_by', '=', 'users.id')
//                ->join('roles', 'users.role_id', '=', 'roles.id')
//                ->select('truck_deliverys.id',
//                    'truck_deliverys.truck_no',
//                    'truck_deliverys.driver_name',
//                    // 'truck_deliverys.gweight',
//                    'truck_deliverys.loading_unit',
//                    'truck_deliverys.package',
//                    //DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
//                    'truck_deliverys.delivery_dt',
//                    //'truck_deliverys.approve_dt',
//                    'truck_deliverys.labor_load',
//                    'truck_deliverys.labor_package',
//                    'truck_deliverys.equip_load',
//                    'truck_deliverys.equip_name',
//                    'truck_deliverys.equipment_package',
//                    //'truck_deliverys.loading_flag',
//                    'manifests.manifest')
//                ->get();

            $todaysTruckDeliveryEntry = DB::select("SELECT delivery_requisitions.*,manifests.manifest  FROM delivery_requisitions 
JOIN manifests ON manifests.id = delivery_requisitions.manifest_id
WHERE manifests.transshipment_flag=1 AND delivery_requisitions.approximate_delivery_date=? 
AND manifests.port_id=? AND delivery_requisitions.port_id=?", [$today,$port_id,$port_id]);
        } else {
//            $todaysTruckDeliveryEntry = DB::table('truck_deliverys')
//                ->where('delivery_dt', 'LIKE', "%$today%")
//                ->where('roles.name', '!=', "TransShipment")
//                ->where('manifests.port_id', $port_id)
//                ->where('truck_deliverys.port_id', $port_id)
//                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
//                ->join('users', 'truck_deliverys.delivery_req_by', '=', 'users.id')
//                ->join('roles', 'users.role_id', '=', 'roles.id')
//                ->select('truck_deliverys.id',
//                    'truck_deliverys.truck_no',
//                    'truck_deliverys.driver_name',
//                    // 'truck_deliverys.gweight',
//                    'truck_deliverys.loading_unit',
//                    'truck_deliverys.package',
//                    //DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
//                    'truck_deliverys.delivery_dt',
//                    //'truck_deliverys.approve_dt',
//                    'truck_deliverys.labor_load',
//                    'truck_deliverys.labor_package',
//                    'truck_deliverys.equip_load',
//                    'truck_deliverys.equip_name',
//                    'truck_deliverys.equipment_package',
//                    //'truck_deliverys.loading_flag',
//                    'manifests.manifest')
//                ->get();
            $todaysTruckDeliveryEntry = DB::select("SELECT delivery_requisitions.*,manifests.manifest  FROM delivery_requisitions 
JOIN manifests ON manifests.id = delivery_requisitions.manifest_id
WHERE manifests.transshipment_flag=0 AND delivery_requisitions.approximate_delivery_date=? 
AND manifests.port_id=? AND delivery_requisitions.port_id=?", [$today,$port_id,$port_id]);






        }
        //return $todaysWeightBridgeEntry;
        $pdf = PDF::loadView('default.warehouse.delivery.reports.todays-delivery-request-report', [
            'todaysTruckDeliveryEntry' => $todaysTruckDeliveryEntry,
            'todayWithTime' => $todayWithTime
        ])->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('todaysDeliveryEntryReport.pdf');
    }


//
//
//
//    public function dateWiseDeliveryRequestReport(Request $req)
//    {

//        $today = $req->date;
//        $todayWithTime = date('Y-m-d h:i:s a');
//
//        if (Auth::user()->role->name == 'TransShipment') {
//            $todaysDeliveryRequest = DB::table('manifests')
//                ->where('manifests.approximate_delivery_date', 'LIKE', "%$today%")
//                ->where('roles.name', '=', "TransShipment")
//                ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
//                ->join('vatregs', 'vatregs.id', '=', 'manifests.vatreg_id')
//                ->join('users', 'users.id', '=', 'manifests.custom_approved_by')
//                ->join('roles', 'roles.id', '=', 'users.role_id')
//                ->select('manifests.*', 'cargo_details.*', 'vatregs.*')
//                ->get();
//        } else {
//            $todaysDeliveryRequest = DB::table('manifests')
//                ->where('manifests.approximate_delivery_date', 'LIKE', "%$today%")
//                ->where('roles.name', '!=', "TransShipment")
//                ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
//                ->join('vatregs', 'vatregs.id', '=', 'manifests.vatreg_id')
//                ->join('users', 'users.id', '=', 'manifests.custom_approved_by')
//                ->join('roles', 'roles.id', '=', 'users.role_id')
//                ->select('manifests.*', 'cargo_details.*', 'vatregs.*')
//                ->get();
//        }
//        //return $todaysWeightBridgeEntry;
//        $pdf = PDF::loadView('default.warehouse.reports.date-wise-delivery-request-report', [
//            'todaysDeliveryRequest' => $todaysDeliveryRequest,
//            'todayWithTime' => $todayWithTime,
//            'today' => $today
//        ])->setPaper('a4', 'landscape');
//        //return $pdf->download('user.pdf');
//        return $pdf->stream('dateWiseDeliveryRequestReport.pdf');
//
//    }




    public function getBdTruckInfo($id)
    {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');

        // $bdTruckInfo = DB::table('truck_deliverys AS d')
        //     ->join('manifests As m','m.id', '=','d.manf_id')
        //     ->where('d.manf_id',$id)

        //     ->select(
        //         'm.manifest',
        //         'd.truck_no',
        //         'd.driver_name',
        //         'd.gweight',
        //         'd.delivery_dt',
        //         'd.labor_load',
        //         'd.equip_load',
        //         'd.labor_package',
        //         'd.equipment_package',
        //         'd.equip_name'
        //     )
        //     ->get();
        if (Auth::user()->role->name == 'TransShipment') {
            $bdTruckInfo = DB::table('truck_deliverys AS d')
                ->join('manifests As m', 'm.id', '=', 'd.manf_id')
                ->where('roles.name', '=', "TransShipment")
                ->join('users', 'd.delivery_created_by', '=', 'users.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('d.manf_id', $id)
                ->where('d.port_id', $port_id)
                ->where('m.port_id', $port_id)
                ->select(
                    'm.manifest',
                    'd.truck_no',
                    'd.driver_name',
//                    'd.gweight',
//                    'd.delivery_dt',
                    'd.labor_load',
                    'd.equip_load',
                    'd.labor_package',
                    'd.equipment_package',
                    'd.equip_name'
                )
                ->get();
        } else {
            $bdTruckInfo = DB::table('truck_deliverys AS d')
                ->join('manifests As m', 'm.id', '=', 'd.manf_id')
                ->where('roles.name', '!=', "TransShipment")
                ->join('users', 'd.delivery_req_by', '=', 'users.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('d.manf_id', $id)
                ->where('d.port_id', $port_id)
                ->where('m.port_id', $port_id)
                ->select(
                    'm.manifest',
                    'd.truck_no',
                    'd.driver_name',
                    'd.gweight',
                    'd.delivery_dt',
                    'd.labor_load',
                    'd.equip_load',
                    'd.labor_package',
                    'd.equipment_package',
                    'd.equip_name'
                )
                ->get();
        }



        //return $todaysWeightBridgeEntry;
        $pdf = PDF::loadView('default.warehouse.delivery.reports.get-bd-truck-information-report', [
            'bdTruckInfo' => $bdTruckInfo,
            'today' => $today
        ]);
        //return $pdf->download('user.pdf');
        return $pdf->stream('getBdTruckInfoReport.pdf');
    }

    //========================= Truck Delivery================================

    public function TruckDeliveryEntryForm()
    {
        return view('WareHouse.TruckDeliveryEntryForm');
    }

    public function searchByTruckNoOrManifestNoJsonReturn(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        if ($r->search_by == 'truckNo') {
            $bdTruckData = DB::table('truck_deliverys')
                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
                ->where('truck_no', $r->truck_no)
                ->where('truck_deliverys.port_id', $port_id)
                ->where('manifests.port_id', $port_id)
                ->select('truck_deliverys.id',
                    'truck_deliverys.truck_no',
                    'truck_deliverys.driver_name',
                    // 'truck_deliverys.gweight',
                    'truck_deliverys.loading_unit',
                    'truck_deliverys.package',
                    // DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
                    'truck_deliverys.labor_load',
                    'truck_deliverys.labor_package',
                    'truck_deliverys.delivery_dt',
                    // 'truck_deliverys.approve_dt',
                    'truck_deliverys.equip_load',
                    'truck_deliverys.equip_name',
                    'truck_deliverys.equipment_package',
                    // 'truck_deliverys.loading_flag',
                    'manifests.manifest')
                ->get();
            return json_encode(array('bdTruck' => $bdTruckData));
        } else if ($r->search_by == 'manifestNo') {
            $bdTruckData = DB::table('manifests')
                ->join('truck_deliverys', 'truck_deliverys.manf_id', '=', 'manifests.id')
                ->where('manifest', $r->manifest)
                ->where('truck_deliverys.port_id', $port_id)
                ->where('manifests.port_id', $port_id)
                ->select('truck_deliverys.id',
                    'truck_deliverys.truck_no',
                    'truck_deliverys.driver_name',
                    // 'truck_deliverys.gweight',
                    'truck_deliverys.loading_unit',
                    'truck_deliverys.package',
                    // DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
                    'truck_deliverys.delivery_dt',
                    //'truck_deliverys.approve_dt',
                    'truck_deliverys.labor_load',
                    'truck_deliverys.labor_package',
                    'truck_deliverys.equip_load',
                    'truck_deliverys.equip_name',
                    'truck_deliverys.equipment_package',
                    //'truck_deliverys.loading_flag',
                    'manifests.manifest')
                ->get();
            $indianTruck = DB::table('manifests')
                ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
                ->where('manifest', $r->manifest)
                ->where('truck_entry_regs.port_id', $port_id)
                ->where('manifests.port_id', $port_id)
                ->select('truck_entry_regs.truck_no',
                    'truck_entry_regs.driver_name',
                    // 'truck_entry_regs.nweight',
                    'truck_entry_regs.tweight_wbridge', //Weightbridge Net Weight
                    'truck_entry_regs.receive_package',
                    'truck_entry_regs.equip_name',
                    'manifests.manifest')
                ->get();
            return json_encode(array('bdTruck' => $bdTruckData, 'indianTruck' => $indianTruck));
        }
    }

    public function truckDeliveryEntryJson(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $approve_by = Auth::user()->name;
        //return json_encode($r->truck_no);
        //return $r->posted_time;
        $truckDeliveryPosting = DB::table('truck_deliverys')
            ->where('truck_deliverys.id', $r->id)
            ->where('manifests.port_id', $port_id)
            ->where('truck_deliverys.port_id', $port_id)
            ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
            ->update([
                //'truck_deliverys.gweight' => $r->gweight,
                'truck_deliverys.loading_unit' => $r->loading_unit,
                'truck_deliverys.package' => $r->package,
                'truck_deliverys.delivery_dt' => $r->delivery_dt,
                'truck_deliverys.labor_load' => $r->labor_load,
                'truck_deliverys.labor_package' => $r->labor_package,
                'truck_deliverys.equip_load' => $r->equip_load,
                'truck_deliverys.equip_name' => $r->equip_name,
                'truck_deliverys.equipment_package' => $r->equipment_package,
                'truck_deliverys.approve_by' => $approve_by
                //'truck_deliverys.approve_dt' => $r->approve_dt,
                //'truck_deliverys.loading_flag' => $r->loading_flag,
            ]);
        //================EQUIPMENT OR LABOUR OR BOTH FLAG INPUT=================
        if ($r->labor_load != NULL || $r->labor_load != 0) {
            $loadingManualChargesId = 34; //FROM charge Details Table
            $loadingManual = DB::table('truck_deliverys')
                ->where('truck_deliverys.id', $r->id)
                ->where('manifests.port_id', $port_id)
                ->where('truck_deliverys.port_id', $port_id)
                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
                ->update([
                    'truck_deliverys.loading_manual' => $loadingManualChargesId
                ]);
        } else {
            $loadingManual = DB::table('truck_deliverys')
                ->where('truck_deliverys.id', $r->id)
                ->where('manifests.port_id', $port_id)
                ->where('truck_deliverys.port_id', $port_id)
                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
                ->update([
                    'truck_deliverys.loading_manual' => NULL
                ]);
        }
        if ($r->equip_load != NULL || $r->equip_load != 0) {
            $loadingOffloadingEquipmentId = 36; //FROM charge Details Table
            $loadingEquipment = DB::table('truck_deliverys')
                ->where('truck_deliverys.id', $r->id)
                ->where('manifests.port_id', $port_id)
                ->where('truck_deliverys.port_id', $port_id)
                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
                ->update([
                    'truck_deliverys.loading_equipment' => $loadingOffloadingEquipmentId
                ]);
        } else {
            $loadingEquipment = DB::table('truck_deliverys')
                ->where('truck_deliverys.id', $r->id)
                ->where('manifests.port_id', $port_id)
                ->where('truck_deliverys.port_id', $port_id)
                ->join('manifests', 'manifests.id', '=', 'truck_deliverys.manf_id')
                ->update([
                    'truck_deliverys.loading_equipment' => NULL
                ]);
        }
        //================EQUIPMENT OR LABOUR OR BOTH FLAG INPUT=================
        if ($truckDeliveryPosting == true && ($loadingManual == true || $loadingEquipment == true)) {
            return 'Success';
        }
    }



    public function ManifestDetailsPDF($manifest, $truck, $year)
    {
        $port_id = Session::get('PORT_ID');
        $totalFigure = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        //return $totalFigure;
        $todayWithTime = date('Y-m-d h:i:s a');
        $bdTruckData = DB::table('manifests')
            ->join('truck_deliverys', 'truck_deliverys.manf_id', '=', 'manifests.id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->where('truck_deliverys.port_id', $port_id)
            ->select('truck_deliverys.id',
                'truck_deliverys.truck_no',
                'truck_deliverys.driver_name',
                // 'truck_deliverys.gweight',
                'truck_deliverys.loading_unit',
                'truck_deliverys.package',
                //DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
                'truck_deliverys.delivery_dt',
                //'truck_deliverys.approve_dt',
                'truck_deliverys.labor_load',
                'truck_deliverys.labor_package',
                'truck_deliverys.equip_load',
                'truck_deliverys.equip_name',
                'truck_deliverys.equipment_package',
                //'truck_deliverys.loading_flag',
                'manifests.manifest')
            ->get();
        $indianTruckData = DB::table('manifests')
            ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->where('truck_entry_regs.port_id', $port_id)
            ->select('truck_entry_regs.truck_no',
                'truck_entry_regs.driver_name',
                // 'truck_entry_regs.nweight',
                'truck_entry_regs.tweight_wbridge', //Weightbridge Net Weight
                'truck_entry_regs.receive_package',
                'truck_entry_regs.equip_name',
                //'truck_entry_regs.offloading_flag',
                'manifests.manifest')
            ->get();
        $manifestDetails = DB::table('manifests')
            ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
            ->join('vatregs', 'vatregs.id', '=', 'manifests.vatreg_id')
            ->where('manifest', $totalFigure)
            ->where('manifests.port_id', $port_id)
            ->where('truck_entry_regs.port_id', $port_id)
            ->select('manifests.manifest_date',
                'manifests.gweight',
                'manifests.nweight',
                'manifests.package_no',
                'manifests.package_type',
                'manifests.cnf_value',
                'manifests.exporter_name_addr',
                'manifests.lc_no',
                'manifests.lc_date',
                'manifests.be_no',
                'manifests.be_date',
                'manifests.ind_be_no',
                'manifests.ind_be_date',
                'cargo_details.cargo_name',
                'vatregs.NAME',
                'vatregs.ADD1'
            )
            ->get();
        //return $manifestDetails;
        $pdf = PDF::loadView('WareHouse.manifestPDFReport', [
            'bdTruckData' => $bdTruckData,
            'indianTruckData' => $indianTruckData,
            'todayWithTime' => $todayWithTime,
            'manifestDetails' => $manifestDetails,
            'manifestNo' => $totalFigure
        ])
            ->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('ManifestPDFReport.pdf');
    }

    public function truckDetailsData($id)
    {
        $port_id = Session::get('PORT_ID');
        $entrance_fee = DB::select("SELECT id AS truck_id,type_name FROM vehicle_type_bd  WHERE vehicle_type ='1' ORDER BY type_name ASC");

        $data_items_details = DB::select('SELECT item_details.*,ic.Description
  FROM item_details 
JOIN item_codes AS ic  ON item_details.item_Code_id = ic.id
WHERE item_details.manf_id =?', [$id]);

        return json_encode(array($entrance_fee,$data_items_details));
    }


    public function checkAssessmentStatus(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $checkAssessmentStatus = DB::select("SELECT DISTINCT assesment_details.manif_id,
                                        (
                                            CASE 
                                            WHEN verified='0'  THEN 'Assessment Pending'
                                            WHEN verified='1'  AND  approved='0' THEN 'Assessment Processing'
                                            WHEN approved='1'  THEN 'Assessment Done'
                                            ELSE NULL
                                            END
                                        ) AS assessmet_status
                                        FROM manifests 
                                        INNER JOIN assesment_details ON assesment_details.manif_id=manifests.id
                                        WHERE manifests.id/*manifest*/=? AND manifests.port_id=?", [$r->manifest,$port_id]);
        return json_encode($checkAssessmentStatus);
    }
    //========================= Truck Delivery================================
    //========================= Other Reports=================================
    public function othersReportWarehouseView()
    {
        $port_id = Session::get('PORT_ID');
//        $years = DB::select('SELECT DISTINCT YEAR(truck_entry_regs.receive_datetime) AS YEAR
//                            FROM truck_entry_regs
//                            WHERE truck_entry_regs.port_id=? AND truck_entry_regs.receive_datetime IS NOT NULL',[$port_id]);
        return view('default.warehouse.warehouse-others-reports'/*, compact('years')*/);
    }

    public function postingBranchEntryDoneButWarehouseEntryNotDoneReport()
    {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $today = date('Y-m-d');
        $data = DB::select("SELECT CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest,
  manifests.manifest AS manifest, manifests.id AS manifest_id,truck_entry_regs.truck_no, truck_entry_regs.truck_type
                            FROM truck_entry_regs
                            JOIN manifests ON manifests.id=truck_entry_regs.manf_id
                            WHERE truck_entry_regs.port_id=? AND manifests.port_id=? 
                          /*  AND truck_entry_regs.receive_datetime IS NULL*/ AND manifests.gweight IS NOT NULL ORDER BY justManifest DESC",[$port_id,$port_id]);
        $pdf = PDF::loadView('WareHouse.postingBranchEntryDoneButWarehouseEntryNotDoneReportPDF', [
            'todayWithTime' => $todayWithTime,
            'data' => $data,
        ]);
        return $pdf->stream('postingBranchEntryDoneButWarehouseEntryNotDoneReport-' . $today . '.pdf');
    }


    //===============================================bdTruckEntryForm============================================================================Here======
    public function bdTruckEntryFormView()
    {
        return view('cnf.bdTruckEntryForm');


    }




    public function getBdLocalTruckEntryData(Request $id)
    {
        $port_id = Session::get('PORT_ID');
//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hi".$id);
//        fclose($file);
//        return;

        $trucks = DB::table('manifests AS m')
            ->where('m.manifest', $id->manf_id)
            ->where('m.port_id', $port_id)
            ->where('td.port_id', $port_id)
            ->leftJoin('truck_deliverys AS td', 'm.id', '=', 'td.manf_id')
            ->leftJoin('vehicle_type_bd AS v', 'td.truck_type_id', '=', 'v.id')
            ->select(
                'm.id AS m_id',
                'm.manifest',
                'm.gweight AS m_gweight',
                'm.nweight AS m_nweight',
                'm.no_del_truck',
                'td.id AS bd_truck_id',
                'td.truck_no',
                'td.truck_type_id',
                'td.driver_name',
                'td.gweight AS td_gweight',
                'td.labor_load',
                'td.labor_package',
                'td.equip_load',
                'td.equipment_package',
                'td.equip_name',
                'td.delivery_dt',
                'td.weightment_flag',
                'td.haltage_day',
                'v.type_name'

            )
            ->get();


        /*$totalWeight=DB::table('truck_entry_regs as t')
            ->selectRaw('t.manf_id, sum(t.tweight_wbridge) as Totalweight')
            ->where('t.manf_id', $id)
            ->groupBy('t.manf_id')
            ->pluck('Totalweight', 't.manf_id');*/

        return json_encode($trucks);

    }


    public function CnfBDLocalTruckSave(Request $req)
    {
        $port_id = Session::get('PORT_ID');

        $bdTruckType = DB::SELECT("SELECT type_name  FROM vehicle_type_bd  WHERE id= ?", [$req->truck_type_id]);

        if ($req->bd_truck_id == null) {//Save Data

            $saveBdTruckData = DB::table('truck_deliverys')->insert([
                'manf_id' => $req->manf_id,
                'truck_no' => $req->truck_no . '-' . $bdTruckType[0]->type_name,
                'truck_type_id' => $req->truck_type_id,
                'driver_name' => $req->driver_name,
                'port_id' =>$port_id,
//                'labor_load'=>$req->labor_load,
//                'labor_package'=>$req->labor_package,
//                'equip_load'=>$req->equip_load,
//                'equipment_package'=>$req->equipment_package,
//                'weightment_flag'=>$req->weightment_flag,
//                'equip_name'=>$req->equip_name,
                'delivery_dt' => $req->delivery_dt,
                'delivery_req_by' => Auth::user()->id,
                'delivery_req_dt' => date('Y-m-d H:i:s'),
//                'haltage_day' => $req->haltage_day


            ]);

            if ($saveBdTruckData == 1) {
                return 'saved';
            } else {
                return 'errors';
            }


        } else {//update bd Truck Data

            $updateBdTruckData = DB::table('truck_deliverys')
                ->where('id', $req->bd_truck_id)
                ->update([
                    'truck_no' => $req->truck_no . '-' . $bdTruckType[0]->type_name,
                    'truck_type_id' => $req->truck_type_id,
                    'driver_name' => $req->driver_name,
                    'delivery_dt' => $req->delivery_dt,
                    'delivery_req_by' => Auth::user()->id,
                    'delivery_req_dt' => date('Y-m-d H:i:s'),


                ]);
            return 'updated';
        }
    }

//END saveBdTruckData


    public function cnfdeleteBdTruck($id)
    {
        DB::table('truck_deliverys')->where('id', $id)->delete();

        return 'success';
    }


    public function cnfBDTruckEntryDateWisePdf(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $requestedDate = $r->from_date;

        $DWRDate = DB::select("SELECT users.name,manifests.manifest, truck_deliverys.* FROM truck_deliverys
LEFT JOIN manifests  ON manifests.id=truck_deliverys.manf_id 
LEFT JOIN users ON users.id=truck_deliverys.delivery_req_by
  WHERE DATE(delivery_req_dt)=? AND truck_deliverys.port_id =? AND manifests.port_id=? ", [$r->from_date,$port_id,$port_id]);

//        return

        if ($DWRDate) {

            $pdf = PDF::loadView('cnf.dateWiseBdTruckEntryReportCnfPDF', [
                'DWRDate' => $DWRDate,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date
            ])->setPaper([0, 0, 808, 620.63], 'landscape');
            // ->setPaper([0, 0, 808.661, 1020.63], 'landscape');
            // ->setPaper([0, 0, 808, 620.63], 'landscape');
            return $pdf->stream('dateWiseBdTruckEntryReportCnfPDF.pdf');

        } else {

            //  return view('posting.notFound',compact(''/*'requestedDate'*/));
            return view('Export.error');

        }
    }


    //===============================================BdTruckEntryForm End====================
    //========================Monthly Warehouse Entry
    public function monthlyWarehouseEntryReport(Request $r)
    {
        $year = date("Y", strtotime($r->month_entry));
        $month = date("m", strtotime($r->month_entry));
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT COUNT(truck_entry_regs.id) AS truck_count,shed_yard_weights.unload_receive_datetime as receive_datetime
            FROM truck_entry_regs
            INNER JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
            WHERE MONTH(shed_yard_weights.unload_receive_datetime)=?
            AND YEAR(shed_yard_weights.unload_receive_datetime)=? AND shed_yard_weights.port_id=? AND truck_entry_regs.port_id=?
            GROUP BY DATE(shed_yard_weights.unload_receive_datetime)', [$month, $year, $port_id, $port_id]);
        $todayWithTime = date('Y-m-d h:i:s a');
        $today = date('Y-m-d');
        $pdf = PDF::loadView('default.warehouse.reports.monthly-warehouse-entry-report', [
            'todayWithTime' => $todayWithTime,
            'data' => $data,
        ]);
        return $pdf->stream('monthlyWarehouseEntryPDF-' . $today . '.pdf');
    }

    //===========Chassis Start=======================
    public function saveChassisData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        if ($r->chassis_id) {


            $updateChassis = DB::table('chassis_details')
                ->where('id', $r->chassis_id)
                ->update([
                    'chassis_type' => $r->chassis_type,
                    'chassis_no' => $r->chassis_no
                ]);
            if ($updateChassis) {
                return "Success";
            }

        } else {


            $saveChasisData = DB::table('chassis_details')
                ->insert([
                    'truck_id' => $r->truck_id,
                    'chassis_type' => $r->chassis_type,
                    'chassis_no' => $r->chassis_no,
                    'manifest_id' => $r->manifest_id,
                    'port_id' => $port_id
                ]);
            if ($saveChasisData) {
                return "Success";
            }
        }


    }

    //===========Chassis End=========================

    public function deleteChassis($id)
    {
        DB::table('chassis_details')->where('id', $id)->delete();

        return 'success';
    }

    //===================================chassis details==========================
    public function allChassisDetails($id)
    {
        //return $chassis;
        $getChassis = DB::table('chassis_details')
            ->where('chassis_details.truck_id', $id)
            ->where('chassis_details.port_id', Session::get('PORT_ID'))
            ->select(
                'chassis_details.id',
                'chassis_details.truck_id',
                'chassis_details.chassis_type',
                'chassis_details.chassis_no'
            )
            ->get();
        return json_encode($getChassis);
    }
    //============================chassis details =============================

    public function warehouseRecieveMonitorView() {
        return view('default.warehouse.receive.warehouse-receive-monitor-view');
    }

    public function getWarehouseReceiveEntryDetailsForMonitor($date) {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT shed_yard_weights.unload_receive_datetime AS receive_datetime, 
                            yard_details.yard_shed_name,manifests.id AS manifest_id, 
                            manifests.manifest, truck_entry_regs.id AS truck_id, 
                            truck_entry_regs.truck_type, truck_entry_regs.truck_no, 
                            (IFNULL(shed_yard_weights.unload_labor_weight,0) + IFNULL(shed_yard_weights.unload_equip_weight,0)) AS receive_weight,
                            (SELECT users.name FROM users WHERE users.id = shed_yard_weights.created_by) AS receive_created_by,
                             shed_yard_weights.created_at AS receive_created_at,
                            (SELECT users.name FROM users WHERE users.id = shed_yard_weights.updated_by) AS receive_updated_by, truck_entry_regs.vehicle_type_flag,
                            shed_yard_weights.updated_at AS receive_updated_at
                            FROM manifests
                            JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id 
                            JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id 
                            JOIN yard_details ON yard_details.id = shed_yard_weights.unload_yard_shed 
                            WHERE DATE(shed_yard_weights.unload_receive_datetime) =? 
                            AND truck_entry_regs.port_id=? AND manifests.port_id=? AND shed_yard_weights.port_id=?
                            ORDER BY yard_details.yard_shed DESC, TIME(shed_yard_weights.unload_receive_datetime) DESC', [$date, $port_id, $port_id, $port_id]);
        return json_encode($data);
    }
}
