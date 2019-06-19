<?php

namespace App\Http\Controllers\Transshipment;
use App\Models\Warehouse\YardDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use Symfony\Component\Process\Exception\RuntimeException;
use Response;
use Session;

class TransshipmentWarehosueController extends Controller
{

    public function wareHouseEntryForm() {


        $arrayShedYardId = array();
        foreach (Auth::user()->shedYards as $k => $v) {

            $arrayShedYardId[] = $v->id;
        }
        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->get();
       // dd($yard_details_array[0]);

        return view('default.transshipment.warehouse.receive.warehouse-entry-form',['yard_details_array' => $yard_details_array]);
    }


    public function deliveryRequestForm($manifest=null,$truck=null,$year=null){
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        // dd($manifest_no);

        return view('default.transshipment.warehouse.delivery.delivery-request-form',['manifest_no'=>$manifest_no]);

    }

    //==================Transshiopment Warehosue Receive Section===========================================



    public function getAllTrucksListForReceive(Request $r) {
        $port_id = Session::get('PORT_ID');
        if($r->search_by == 'manifestNo') {

            $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        WHERE manifests.transshipment_flag=1
                                        AND manifests.manifest=? AND manifests.port_id=?',[$r->manf_id,$port_id]);
        } else if($r->search_by == 'truckNo') {
            $string = $r->truck_no;
            if(preg_match("/[a-z]/i", $string)) {
                $truckTypeAndNumber = explode('-', $string);
                $chkPermission = DB::select('   SELECT COUNT(manifests.id) AS valid
                                            FROM manifests
                                            JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
                                            WHERE manifests.transshipment_flag=1
                                            AND truck_entry_regs.truck_no=? AND truck_entry_regs.truck_type = ?
                                            AND manifests.port_id=? AND truck_entry_regs.port_id=?',[$truckTypeAndNumber[1], $truckTypeAndNumber[0],$port_id,$port_id]);
            } else {
                return Response::json(['noPermission' => 'Enter Valid Truck Type-No. Example: WB-3287'], 203);
            }
        }

        if($chkPermission[0]->valid == 0) {
            return Response::json(['noPermission' => 'You are not permitted to see this.'], 203);
        }


        if($r->search_by == 'truckNo') {
            $string = $r->truck_no;
            if(preg_match("/[a-z]/i", $string)) {
                $truckTypeAndNumber = explode('-', $string);
                $truckData = DB::select('SELECT tr.id, tr.truck_type, tr.truck_no, tr.goods_id, tr.receive_weight, tr.receive_package, 
                        tr.tweight_wbridge,syw.unload_comment,syw.id AS shed_yard_weight_id, syw.unload_receive_datetime, 
                        syw.unload_labor_weight, syw.unload_labor_package, syw.unload_equip_weight, syw.unload_equip_name, 
                        syw.unload_equipment_package, syw.unload_shifting_flag, syw.unload_yard_shed, tr.holtage_charge_flag,
                        m.manifest, m.posted_yard_shed, m.exporter_name_addr,  m.transshipment_flag, v.NAME, cd.cargo_name, 
                        tr.manf_id, tr.gweight_wbridge, tr.receive_created_at, tr.chassis_on_truck_flag, tr.vehicle_type_flag
                        FROM truck_entry_regs AS tr 
                        JOIN manifests AS m ON m.id=tr.manf_id
                        LEFT JOIN vatregs AS v ON v.id=m.vatreg_id
                        LEFT JOIN cargo_details AS cd ON cd.id=m.goods_id
                        LEFT JOIN shed_yard_weights AS syw ON syw.truck_id = tr.id
                        WHERE tr.truck_type=? AND tr.truck_no=? AND tr.port_id = ? AND m.port_id=? ',[$truckTypeAndNumber[0], $truckTypeAndNumber[1],$port_id,$port_id]);
                return json_encode($truckData);
            } else {
                return;
            }

        } else {//manifestNo
            $truckData = DB::select('SELECT tr.id, tr.truck_type, tr.truck_no, tr.goods_id, tr.receive_weight, tr.receive_package, 
                    tr.tweight_wbridge,syw.unload_comment,syw.id AS shed_yard_weight_id, syw.unload_receive_datetime, 
                    syw.unload_labor_weight, syw.unload_labor_package, syw.unload_equip_weight, syw.unload_equip_name, 
                    syw.unload_equipment_package, syw.unload_shifting_flag, syw.unload_yard_shed, tr.holtage_charge_flag,
                    m.manifest, m.posted_yard_shed, m.exporter_name_addr,  m.transshipment_flag, v.NAME, cd.cargo_name, 
                    tr.manf_id, tr.gweight_wbridge, tr.receive_created_at, tr.chassis_on_truck_flag, tr.vehicle_type_flag
                    FROM truck_entry_regs AS tr 
                    JOIN manifests AS m ON m.id=tr.manf_id
                    LEFT JOIN vatregs AS v ON v.id=m.vatreg_id
                    LEFT JOIN cargo_details AS cd ON cd.id=m.goods_id
                    LEFT JOIN shed_yard_weights AS syw ON syw.truck_id = tr.id
                    WHERE m.manifest=? AND tr.port_id =? AND m.port_id=?',[$r->manf_id,  $port_id,$port_id]);
            return json_encode($truckData);
        }

    }


    public function getManifestGrossWeightForReceive($manifest_no) {
        $port_id = Session::get('PORT_ID');
        //return $manifest_no
        $getManifestGrossWeight = DB::table('manifests')
            ->where('manifests.id', $manifest_no)
            ->where('manifests.port_id', $port_id)
            ->select('manifests.gweight')
            ->get();
        return json_encode($getManifestGrossWeight);
    }


    public function saveWarehouseTruckReceiveData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $receive_by = Auth::user()->id;
        //return json_encode($r->truck_no);
        //return $r->posted_time;
        $chk_manifest = DB::select('SELECT m.manifest_posted_done_flag 
                                    FROM truck_entry_regs AS tr 
                                    JOIN manifests AS m ON tr.manf_id = m.id
                                    WHERE tr.id = ? AND tr.port_id=? AND m.port_id=? ',[$r->id,$port_id,$port_id]);
        if($chk_manifest[0]->manifest_posted_done_flag == 0) {
            return Response::json(['posting_error' => 'Posted By CNF. Please Contact Posting Branch.'], 203);
        }
        if($r->receive_created_at == null) {//save
            $WareHousePosting = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=','truck_entry_regs.manf_id')
                ->update([
                    'truck_entry_regs.receive_weight' => $r->receive_weight,
                    'truck_entry_regs.receive_package' => $r->receive_package,
                    'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                    'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                    'truck_entry_regs.receive_by' => $receive_by,
                    'truck_entry_regs.receive_created_at' => date('Y-m-d H:i:s')
                ]);

            if ($r->shed_yard_weight_id != null) {
                $shed_yard_weights = DB::table('shed_yard_weights')
                    ->where('id', $r->shed_yard_weight_id)
                    ->update([
                        'truck_id' => $r->id,
                        'unload_labor_package' => $r->labor_package,
                        'unload_labor_weight' => $r->labor_unload,
                        'unload_equipment_package' => $r->equipment_package,
                        'unload_equip_weight' => $r->equip_unload,
                        'unload_equip_name' => $r->equip_name,
                        'unload_yard_shed' => 81,
                        'unload_shifting_flag' => $r->shifting_flag,
                        'unload_receive_datetime' => $r->receive_datetime,
                        'unload_comment' => $r->recive_comment,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => $receive_by
                    ]);
            } else {
                $shed_yard_weights = DB::table('shed_yard_weights')
                    ->insert([
                        'truck_id' => $r->id,
                        'unload_labor_package' => $r->labor_package,
                        'unload_labor_weight' => $r->labor_unload,
                        'unload_equipment_package' => $r->equipment_package,
                        'unload_equip_weight' => $r->equip_unload,
                        'unload_equip_name' => $r->equip_name,
                        'unload_yard_shed' => 81,
                        'unload_shifting_flag' => $r->shifting_flag,
                        'unload_receive_datetime' => $r->receive_datetime,
                        'unload_comment' => $r->recive_comment,
                        'port_id' => Session::get('PORT_ID'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $receive_by
                    ]);
            }

        } else {//update
            $WareHousePosting = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=','truck_entry_regs.manf_id')
                ->update([
                    //'truck_entry_regs.truck_posted_yard_shed' => 81,      // shed
                    'truck_entry_regs.receive_weight' => $r->receive_weight,
                    'truck_entry_regs.receive_package' => $r->receive_package,
                    //'truck_entry_regs.recive_comment' => $r->recive_comment,
                    //'truck_entry_regs.equip_unload' => $r->equip_unload,
                    //'truck_entry_regs.equip_name' => $r->equip_name,
                    'truck_entry_regs.holtage_charge_flag' => $r->holtage_charge_flag,
                    'truck_entry_regs.tweight_wbridge' => $r->gweight_wbridge == null ? 0 : $r->receive_weight,
                    'truck_entry_regs.receive_updated_by' => $receive_by,
                    'truck_entry_regs.receive_updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($r->shed_yard_weight_id != null) {
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->where('id', $r->shed_yard_weight_id)
                        ->update([
                            'truck_id' => $r->id,
                            'unload_labor_package' => $r->labor_package,
                            'unload_labor_weight' => $r->labor_unload,
                            'unload_equipment_package' => $r->equipment_package,
                            'unload_equip_weight' => $r->equip_unload,
                            'unload_equip_name' => $r->equip_name,
                            'unload_yard_shed' => 81,
                            'unload_shifting_flag' => $r->shifting_flag,
                            'unload_receive_datetime' => $r->receive_datetime,
                            'unload_comment' => $r->recive_comment,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $receive_by
                        ]);
                } else {
                    $shed_yard_weights = DB::table('shed_yard_weights')
                        ->insert([
                            'truck_id' => $r->id,
                            'unload_labor_package' => $r->labor_package,
                            'unload_labor_weight' => $r->labor_unload,
                            'unload_equipment_package' => $r->equipment_package,
                            'unload_equip_weight' => $r->equip_unload,
                            'unload_equip_name' => $r->equip_name,
                            'unload_yard_shed' => 81,
                            'unload_shifting_flag' => $r->shifting_flag,
                            'unload_receive_datetime' => $r->receive_datetime,
                            'unload_comment' => $r->recive_comment,
                            'port_id' => Session::get('PORT_ID'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $receive_by
                        ]);
                }
        }


        return Response::json(200);
    }




    public function datewiseWareHouseEntryReport(Request $r)
    {   
        $port_id = Session::get('PORT_ID');
        $flagValue = $r->vehile_type_flage_pdf;
        $date = $r->date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysWareHouseEntry = DB::select("SELECT  yard_details.yard_shed_name,sw.unload_receive_datetime,yard_details.yard_shed,sw.unload_yard_shed,m.transshipment_flag,t.id,sw.id,
t.truck_type,t.truck_no,t.receive_weight, t.receive_package, sw.unload_comment,
sw.unload_labor_weight,sw.unload_equip_weight,  sw.unload_equip_name, sw.unload_labor_package, sw.unload_equipment_package,
 m.manifest,t.vehicle_type_flag, users.name
FROM manifests AS m 
JOIN truck_entry_regs AS t ON t.manf_id = m.id 
JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
 LEFT JOIN users ON users.id = t.receive_by
 LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
WHERE sw.unload_yard_shed IN (81) AND  DATE(sw.unload_receive_datetime)=? AND  m.port_id=? AND t.port_id=? 
AND  t.vehicle_type_flag=? ORDER BY  yard_details.yard_shed   DESC , sw.unload_receive_datetime DESC", [$date,$port_id,$port_id,$flagValue]);

       // dd($todaysWareHouseEntry);

                                if ($flagValue == 1) {
                                    $typeOfReports = 'Received Goods Report';
                                } elseif ($flagValue == 2) {
                                    $typeOfReports = 'Received Chassis(Chassis On Truck) Report';
                                } elseif ($flagValue == 3) {
                                    $typeOfReports = 'Received Trucktor(Trucktor On Truck) Report';
                                } elseif ($flagValue == 11) {
                                    $typeOfReports = 'Received Chassis(Self) Report';
                                } elseif ($flagValue == 12) {
                                    $typeOfReports = 'Received Trucktor(Self) Report';
                                } elseif ($flagValue == 13) {
                                    $typeOfReports = 'Received Bus Report';
                                } elseif ($flagValue == 14) {
                                    $typeOfReports = 'Received Three Wheller Report';
                                } elseif ($flagValue == 15) {
                                    $typeOfReports = 'Received Rickshaw Report';
                                } elseif ($flagValue == 16) {
                                    $typeOfReports = 'Received Car(Self) Report';
                                } elseif ($flagValue == 17) {
                                    $typeOfReports = 'Received Pick Up(Self) Report';
                                } else {
                                    $typeOfReports = 'Received Report';
                                }

        // dd($todaysWareHouseEntry);
        //return $todaysWeightBridgeEntry;
        $pdf = PDF::loadView('default.transshipment.warehouse.receive.reports.date-wise-warehouse-entry-report',
            [
                'todaysWareHouseEntry' => $todaysWareHouseEntry,
                'todayWithTime' => $todayWithTime,
                'typeOfReports' => $typeOfReports,
                'date' => $date
            ])/*->setPaper('a4', 'landscape');*/
        ->setPaper([0, 0, 900, 900]);
        //return $pdf->download('user.pdf');
        return $pdf->stream($date . '-WareHouseEntry.pdf');
    }
    public function getLocalDeliveryData(Request $r)
    {

        $port_id = Session::get('PORT_ID');
        $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        WHERE manifests.transshipment_flag=1
                                        AND manifests.manifest=? AND manifests.port_id=?', [$r->mani_no,$port_id]);

        $checkAssessmentDone = DB::select('SELECT COUNT(manifests.id) AS notDone
                                        FROM assessments
                                        JOIN manifests ON manifests.id=assessments.manifest_id
                                     WHERE manifests.manifest=? AND manifests.port_id=?', [$r->mani_no,$port_id]);

        $partial_number = DB::select('SELECT MAX(dr.partial_status) AS max_partial_number
                                    FROM delivery_requisitions dr
                                    INNER JOIN manifests m ON dr.manifest_id=m.id
                                    WHERE m.manifest=? AND m.port_id=? AND dr.port_id=?', [$r->mani_no, $port_id, $port_id]);

        if ($chkPermission[0]->valid == 0) {
            return Response::json(['noPermission' => 'You are not permitted to see this.'], 203);
        }elseif ($checkAssessmentDone[0]->notDone == 0){
            return Response::json(['noPermission' => 'Assessment not done.'], 203);
        }elseif (is_null($partial_number[0]->max_partial_number)){
            return Response::json(['noPermission' => 'Delivery Request not done!'], 203);
        }



        if(is_null($r->partial_status)) {
            $partial_status = $partial_number[0]->max_partial_number;
        } else {
            $partial_status = $r->partial_status;
        }

        $data = DB::select('SELECT  dr.id AS dr_id,
(
SELECT SUM(IFNULL(syw.unload_labor_weight,0))
FROM manifests 
INNER JOIN truck_entry_regs AS t ON t.manf_id = manifests.id
INNER JOIN shed_yard_weights AS syw ON syw.truck_id = t.id
WHERE  manifests.manifest = m.manifest
) AS approximate_labour_load,
(
SELECT SUM(IFNULL(syw.unload_equip_weight,0))
FROM manifests
INNER JOIN truck_entry_regs AS t ON t.manf_id = manifests.id
INNER JOIN shed_yard_weights AS syw ON syw.truck_id = t.id
WHERE manifests.manifest = m.manifest
) AS approximate_equipment_load,
(
SELECT SUM(IFNULL(syw.unload_labor_package,0) + IFNULL(syw.unload_equipment_package,0))
FROM manifests AS mm
INNER JOIN truck_entry_regs AS t ON t.manf_id = mm.id
INNER JOIN shed_yard_weights AS syw ON syw.truck_id = t.id
WHERE mm.manifest = m.manifest
) AS loadable_package,
(
SELECT MAX(dr.partial_status) 
FROM delivery_requisitions dr
INNER JOIN manifests mm ON dr.manifest_id = mm.id
WHERE mm.manifest =  m.manifest
) AS total_partial_status,

dr.partial_status AS dr_partial_status, m.id AS m_id, m.manifest, m.gweight AS m_gweight, 
m.nweight AS m_nweight, dr.carpenter_packages, dr.carpenter_repair_packages, dr.transport_truck, dr.transport_van, m.exporter_name_addr,
(IFNULL(dr.transport_truck,0) + IFNULL(dr.transport_van,0)) AS total_transport_requested,
m.custom_release_order_no, m.custom_release_order_date, m.be_no, m.be_date,
c.ain_no,c.cnf_name,dr.gate_pass_no,m.custom_approved_date,dr.local_transport_type,dr.approximate_delivery_type,dr.approximate_delivery_date,m.vatreg_id,
v.NAME AS importer,m.self_flag
FROM manifests AS m
JOIN delivery_requisitions AS dr ON m.id = dr.manifest_id
LEFT JOIN cnf_details AS c ON m.cnf_id = c.id 
LEFT JOIN vatregs AS v ON m.vatreg_id = v.id 
WHERE m.manifest =? AND m.port_id=? AND dr.port_id=? AND dr.partial_status=?',
            [$r->mani_no,$port_id,$port_id,$partial_status]);

        return json_encode($data);
    }

//===========Transshipment Warehouse Delivery Section=======================================


    public function getManifestBillOfEntryDetailsForDeliveryRequest(Request $r) {
        $port_id = Session::get('PORT_ID');
        $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                        WHERE manifests.transshipment_flag=1
                                        AND manifests.manifest=? AND manifests.port_id=?', [$r->manf_id,$port_id]);

            if ($chkPermission[0]->valid == 0) {
                return Response::json(['noPermission' => 'You are not permitted to see this.'], 203);
            }
       // return $chkPermission[0]->valid;
            $data = DB::select('SELECT 
(SELECT SUM(IFNULL(syw.unload_labor_weight,0) + IFNULL(syw.unload_equip_weight,0)) AS receive_weight
FROM manifests AS mm
INNER JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id
INNER JOIN shed_yard_weights AS syw ON syw.truck_id = tt.id 
WHERE mm.id = m.id) AS receive_weight,
m.id AS m_id,dr.id AS delivery_req_id, m.manifest, m.gweight AS m_gweight, m.nweight AS m_nweight,dr.local_weighment AS bd_weighment, 
dr.shifting_flag  AS m_shifting_flag, m.custom_release_order_no, m.custom_release_order_date, dr.approximate_delivery_date, dr.approximate_delivery_type, 
dr.approximate_labour_load,dr.approximate_equipment_load,
(CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) END) AS chargeable_weight,
(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) AS weigh_bridge_net_weight,
(SELECT GROUP_CONCAT(DISTINCT yard_details.yard_shed_name) FROM manifests
LEFT JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
LEFT JOIN yard_details  ON FIND_IN_SET(yard_details.id, shed_yard_weights.unload_yard_shed)
 WHERE manifests.id=m.id
) AS posted_yard_shed,
 m.exporter_name_addr, m.vatreg_id, m.be_no, m.be_date, m.custom_approved_date,  dr.local_transport_type,dr.transport_truck, dr.transport_van,
  dr.local_haltage AS bd_haltage, m.ain_no,m.cnf_name, dr.carpenter_packages, dr.carpenter_repair_packages, dr.gate_pass_no, 
  t.truck_no, t.id AS t_id, t.driver_name, t.driver_card, t.truck_type,  t.receive_package, v.NAME AS importer,
   c.ain_no, c.cnf_name, dr.perishable_flag, dr.truck_to_truck_flag
FROM manifests AS m 
JOIN truck_entry_regs AS t ON m.id = t.manf_id 
LEFT JOIN delivery_requisitions AS dr ON m.id = dr.manifest_id
LEFT JOIN cnf_details AS c ON m.cnf_id = c.id 
LEFT JOIN vatregs AS v ON m.vatreg_id = v.id
LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = t.id
WHERE m.manifest =? AND m.port_id=? AND t.port_id=? GROUP BY dr.id',[$r->manf_id,$port_id,$port_id]);

        return json_encode($data);
    }



    public function saveDeliveryRequestData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
            $ain_id = DB::select('SELECT cnf_details.id AS cnf_id FROM cnf_details WHERE  ain_no=?', [$r->ain_no]);

        $delivery_requisition_data = DB::select('SELECT delivery_requisitions.id AS delivery_id, COUNT(delivery_requisitions.partial_status) + 1  AS  partial_status
FROM delivery_requisitions WHERE  delivery_requisitions.manifest_id=?', [$r->manifest_id]);
        if($r->del_req_id == null){ //save
            if ($delivery_requisition_data[0]->delivery_id == null) {

                $requestData = DB::table('manifests')
                    ->where('id', $r->manifest_id)
                    ->update([
                        'be_no' => $r->be_no,
                        'be_date' => $r->be_date,
                        'custom_release_order_no' => $r->custom_release_order_no,
                        'custom_release_order_date' => $r->custom_release_order_date,
                        'cnf_id' => $ain_id[0]->cnf_id,
                        'ain_no' => $r->ain_no,
                        'cnf_name' => $r->cnf_name,
                        'custom_approved_by' => Auth::user()->id,
                        'custom_approved_date' => date('Y-m-d H:i:s')
                    ]);

                DB::table('delivery_requisitions')->insert([
                    'manifest_id' => $r->manifest_id,
                    'port_id' => $port_id,
                    'partial_status' => $delivery_requisition_data[0]->partial_status,
                    'carpenter_packages' => $r->carpenter_packages,
                    'carpenter_repair_packages' => $r->carpenter_repair_packages,
                    'approximate_delivery_date' => $r->approximate_delivery_date,
                    'approximate_delivery_type' => $r->approximate_delivery_type,
                    'approximate_labour_load' => $r->approximate_labour_load,
                    'approximate_equipment_load' => $r->approximate_equipment_load,
                    'local_transport_type' => $r->local_transport_type,

                    'transport_truck' => $r->transport_truck,
                    'transport_van' => $r->transport_van,
                    'perishable_flag' => $r->perishable_flag,
                    'local_weighment' => $r->bd_weighment,
                    'local_haltage' => $r->bd_haltage,
                    'truck_to_truck_flag' => $r->truck_to_truck_flag,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')

                ]);




            } else {

                $truck_delivery = DB::select('SELECT   COUNT(DISTINCT truck_deliverys.delivery_requisition_id) AS delivery_id FROM truck_deliverys WHERE  
truck_deliverys.manf_id=?', [$r->manifest_id]);

                $delivery_requi = DB::select('SELECT COUNT(DISTINCT delivery_requisitions.id) AS req_id FROM delivery_requisitions WHERE 
delivery_requisitions.manifest_id=?', [$r->manifest_id]);

                if ($delivery_requi[0]->req_id == $truck_delivery[0]->delivery_id){

                    $requestData = DB::table('delivery_requisitions')->insert([
                        'manifest_id' => $r->manifest_id,
                        'port_id' => $port_id,
                        'partial_status' => $delivery_requisition_data[0]->partial_status,
                        'carpenter_packages' => $r->carpenter_packages,
                        'carpenter_repair_packages' => $r->carpenter_repair_packages,
                        'approximate_delivery_date' => $r->approximate_delivery_date,
                        'approximate_delivery_type' => $r->approximate_delivery_type,
                        'approximate_labour_load' => $r->approximate_labour_load,
                        'approximate_equipment_load' => $r->approximate_equipment_load,
                        'local_transport_type' => $r->local_transport_type,
                        'transport_truck' => $r->transport_truck,
                        'transport_van' => $r->transport_van,
                        'local_weighment' => $r->bd_weighment,
                        'local_haltage' => $r->bd_haltage,
                        'truck_to_truck_flag' => $r->truck_to_truck_flag,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')

                    ]);
                }else{

                    return Response::json(['blocked' => 'Local Delivery Not Done!'], 204);

                }



            }



            if ($requestData) {
                return 'saved';
            } else {
                return Response::json(['notSaved' => 'data not saved'], 304);
            }
        }else{  //update
            $requestData = DB::table('manifests')
                ->where('id', $r->manifest_id)
                ->update([
                    'be_no' => $r->be_no,
                    'be_date' => $r->be_date,
                    'custom_release_order_no' => $r->custom_release_order_no,
                    'custom_release_order_date' => $r->custom_release_order_date,
                    'ain_no' => $r->ain_no,
                    'cnf_name' => $r->cnf_name,
                    'cnf_id' => $ain_id[0]->cnf_id,
                    'custom_approved_by' => Auth::user()->id,
                    'custom_approved_date' => date('Y-m-d H:i:s'),
//                    'paid_tax' => $r->paid_tax,
//                    'paid_date' => $r->paid_date,
//                    'no_del_truck' => $r->no_del_truck,

                ]);



            DB::table('delivery_requisitions')
                ->where('id', $r->del_req_id)
                ->update([
                    'carpenter_packages' => $r->carpenter_packages,
                    'carpenter_repair_packages' => $r->carpenter_repair_packages,
                    'approximate_delivery_date' => $r->approximate_delivery_date,
                    'approximate_delivery_type' => $r->approximate_delivery_type,
                    'approximate_labour_load' => $r->approximate_labour_load,
                    'approximate_equipment_load' => $r->approximate_equipment_load,
                    'local_transport_type' => $r->local_transport_type,
                    'transport_truck' => $r->transport_truck,
                    'transport_van' => $r->transport_van,
                    'local_weighment' => $r->bd_weighment,
                    'perishable_flag' => $r->perishable_flag,
                    'local_haltage' => $r->bd_haltage,
                    'truck_to_truck_flag' => $r->truck_to_truck_flag,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')

                ]);

            if ($requestData) {
                return 'updated';
            } else {
                return Response::json(['notSaved' => 'data not saved'], 304);
            }



        }

    }

    public function getLocalTransportData($id,$req_id)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT td.id AS bd_truck_id,td.delivery_requisition_id, m.id AS m_id, m.manifest, m.gweight AS m_gweight, m.nweight AS m_nweight,  td.truck_no, td.truck_type_id, 
td.driver_name, td.gweight AS td_gweight, td.labor_load, td.labor_package, td.equip_load, td.equipment_package, td.equip_name, td.delivery_created_at, td.delivery_dt AS delivery_req_dt,
vtb.type_name, td.transport_type,
(
SELECT SUM(IFNULL(ttd.equip_load,0)) + SUM(IFNULL(ttd.labor_load,0)) FROM truck_deliverys AS ttd WHERE ttd.manf_id=m.id  
) AS total_loaded_weight,
(
SELECT SUM(IFNULL(ttd.labor_package,0)) + SUM(IFNULL(ttd.equipment_package,0)) FROM truck_deliverys AS ttd WHERE ttd.manf_id=m.id  
) AS total_loaded_package,                          
(
SELECT COUNT(*) FROM chassis_deliverys AS cd WHERE cd.truck_delivery_id=td.id
) AS chassis_on_this_vehicle,
(
SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=0 AND truck_deliverys.manf_id=td.manf_id
) AS total_truck,
(
SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=1 AND truck_deliverys.manf_id=td.manf_id
) AS total_Van,
(
SELECT GROUP_CONCAT(cd.chassis_details_id SEPARATOR \',\') FROM chassis_deliverys AS cd WHERE cd.truck_delivery_id=td.id
)AS chassis_ids_on_vehicle, "" AS item_delivery
FROM truck_deliverys AS td 
JOIN manifests AS m ON m.id=td.manf_id
LEFT JOIN vehicle_type_bd AS vtb ON vtb.id=td.truck_type_id
WHERE td.transport_type=0 AND m.id=? AND td.delivery_requisition_id=? AND m.port_id=? AND td.port_id=?', [$id,$req_id,$port_id,$port_id]);
        //return $data;
        //return $data[0]->item_delivery;
        if(count($data) > 0) {
            for( $i = 0; $i < count($data); $i++) {
                // return $data[$i]->item_delivery;
                $item_data = DB::select("SELECT item_deliverys.loadable_weight, item_deliverys.loadable_package, 
                              item_details.*,ic.Description FROM item_deliverys 
                              JOIN item_details ON item_details.id = item_deliverys.item_detail_id
                              JOIN  item_codes AS ic  ON item_details.item_Code_id = ic.id
                              WHERE item_deliverys.truck_delivery_id=? ", [$data[$i]->bd_truck_id]);
                $data[$i]->item_delivery = $item_data;
                //$result = array_merge( (array)$data[$i]->item_delivery, (array)$item_data);
                //array_push($data[$i]->item_delivery, $item_data);
                //array_merge( (array)$data[$i]->item_delivery, (array)$item_data);
                // $data[$i]->item_delivery = $item_data;
            }

            //return $data;
        }


        return json_encode($data);

//        $port_id = Session::get('PORT_ID');
//        $data = DB::select('SELECT td.id AS bd_truck_id,m.id AS m_id,m.manifest,m.gweight AS m_gweight,m.nweight AS m_nweight, m.transport_truck, m.transport_van,
//                                    td.truck_no, td.truck_type_id, td.driver_name, td.gweight AS td_gweight,  td.labor_load, td.labor_package,
//                                    td.equip_load, td.equipment_package, td.equip_name, td.delivery_dt,
//                                    vtb.type_name, td.transport_type
//                                    FROM truck_deliverys AS td
//                                    JOIN manifests AS m ON m.id=td.manf_id
//                                    LEFT JOIN vehicle_type_bd AS vtb ON vtb.id=td.truck_type_id
//                                    WHERE m.id=? AND td.port_id=? AND m.port_id=?', [$id,$port_id,$port_id]);
//
//
//        /*$totalWeight=DB::table('truck_entry_regs as t')
//            ->selectRaw('t.manf_id, sum(t.tweight_wbridge) as Totalweight')
//            ->where('t.manf_id', $id)
//            ->groupBy('t.manf_id')
//            ->pluck('Totalweight', 't.manf_id');*/
//
//        return json_encode($data);

    }


    public function getNetWeightAndDeliveryDate($id)
    {
        $port_id = Session::get('PORT_ID');
        $getNetWeight = DB::select('SELECT *,
                                    (CASE 
                                    WHEN t.gross_weight > t.net_weight THEN t.gross_weight
                                    ELSE t.net_weight
                                    END ) AS max_weight
                                    FROM
                                    ( SELECT m.manifest AS manifest_no,m.id AS manifest_id, m.gweight AS gross_weight, m.package_no, m.approximate_delivery_date AS delivery_date,
                                    (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.port_id=? AND  truck_entry_regs.manf_id=m.id) AS net_weight
                                    FROM manifests m WHERE m.port_id=? AND  m.id=? ) AS t', [$port_id,$port_id,$id]);

        return json_encode($getNetWeight);
    }



    public function saveLocalTransportData(Request $req)
    {//local transport with or without chassis/tructor on it

        $port_id = Session::get('PORT_ID');
//        $bdTruckType = DB::select("SELECT type_name  FROM vehicle_type_bd  WHERE id= ?", [$req->truck_type_id]);
//        $partial_status = DB::select('SELECT MAX(partial_status) AS partial_stat FROM assessments
//                                    WHERE manifest_id=? AND assessments.port_id=?', [$req->manf_id,$port_id]);
        // return $req->all_onVehicleTransportId_array;

        if ($req->bd_truck_id == null) {//Save Data

            $local_truck_id = DB::table('truck_deliverys')->insertGetId([
                'truck_no' => $req->truck_no,
                'truck_type_id' => $req->truck_type_id,
                'delivery_requisition_id' => $req->delivery_requisition_id,
                'manf_id' => $req->manf_id,
                'transport_type' => $req->transport_type,
                'driver_name' => $req->driver_name,
                'labor_load' => $req->labor_load,
                'labor_package' => $req->labor_package,
                'port_id' => $port_id,
                'equip_load' => $req->equip_load,
                'equipment_package' => $req->equipment_package,
                'equip_name' => $req->equip_name,
                'delivery_dt' => $req->delivery_dt,
                'delivery_created_at' => date('Y-m-d H:i:s'),
                'delivery_created_by' => Auth::user()->id

            ]);

            if($req->delivery_item_list) {

                foreach ($req->delivery_item_list as $key => $value) {
//                    if(empty($value['loadable_weight'])){
//                        $value['loadable_weight'] = 0;
//                    }


                    if(isset($value['checkbox'])){
                        $id = DB::table('item_deliverys')->insert([
                            'manifest_id' =>$req->manf_id,
                            'item_detail_id' => $value['id'],
                            'loadable_weight' =>  !empty($value['loadable_weight']) ? $value['loadable_weight'] : 0,
                            'loadable_package' => !empty($value['loadable_package']) ? $value['loadable_package'] : 0,
                            'truck_delivery_id' => $local_truck_id,
                            'partial_status' => $req->dr_partial_status,
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s')

                        ]);
                    }
                }
            }



            return Response::json(200);

        } else {//update bd Truck Data

            $updateBdTruckData = DB::table('truck_deliverys')
                ->where('id', $req->bd_truck_id)
                ->update([
                    //'partial_status'=>$partial_status[0]->partial_stat,
                    'truck_no' => $req->truck_no,
                    'truck_type_id' => $req->truck_type_id,
                    'delivery_requisition_id' => $req->delivery_requisition_id,
                    'transport_type' => $req->transport_type,
                    'driver_name' => $req->driver_name,
                    'labor_load' => $req->labor_load,
                    'labor_package' => $req->labor_package,
                    'equip_load' => $req->equip_load,
                    'equipment_package' => $req->equipment_package,
                    'equip_name' => $req->equip_name,
//                    'haltage_day' => $req->haltage_day,
                    'delivery_dt' => $req->delivery_dt,
//                    'loading_unit' => $req->loading_unit,
                    'delivery_updated_by' => Auth::user()->id,
                    'delivery_updated_at' => date('Y-m-d H:i:s'),


                ]);


            if($req->delivery_item_list) {
                DB::table('item_deliverys')->where('truck_delivery_id', $req->bd_truck_id)->delete();
                foreach ($req->delivery_item_list as $key => $value) {
                    if(isset($value['checkbox'])){
                        if($value['checkbox'] == true){
                            $id = DB::table('item_deliverys')->insert([
                                'manifest_id' =>$req->manf_id,
                                'item_detail_id' => $value['id'],
                                'loadable_weight' => !empty($value['loadable_weight']) ? $value['loadable_weight'] : 0,
                                'loadable_package' => !empty($value['loadable_package']) ? $value['loadable_package'] : 0,
                                'truck_delivery_id' => $req->bd_truck_id,
                                'partial_status' => $req->dr_partial_status,
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s')

                            ]);
                        }

                    }
                }
            }



            return Response::json(201);
        }
    }

    public function deleteLocalTransport($id)
    {
        $item_details_data = DB::select('SELECT item_deliverys.id AS it_id FROM item_deliverys
WHERE item_deliverys.truck_delivery_id=?', [$id]);
        if (isset($item_details_data[0]->it_id)){
            DB::table('item_deliverys')->where('truck_delivery_id', $id)->delete();
        }


        DB::table('truck_deliverys')->where('id', $id)->delete();

        return Response::json(200);
    }
}
