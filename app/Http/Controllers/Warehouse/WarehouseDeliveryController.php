<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use Symfony\Component\Process\Exception\RuntimeException;
use Response;
use Session;


class WarehouseDeliveryController extends Controller
{


    public function deliveryRequestTest()
    {
        $mani_id = '3,5';

        foreach ($mani_id as $chassis_tructor_id) {
            //return $chassis_tructor_id;
            $chassis_tructor_data = DB::select('SELECT * FROM chassis_details AS cd WHERE cd.id=?', [$chassis_tructor_id]);
            if (!empty($chassis_tructor_data)) {
                $id = DB::table('chassis_deliverys')->insert([
                    'chassis_details_id' => $chassis_tructor_id,
                    'truck_id' => $local_truck_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'delivery_req_date' => $req->delivery_dt,
                ]);
            }


        }

        // dd($all_chassis_truck);
    }


    public function localTransportDelivery($manifest=null,$truck=null,$year=null){
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;

        return view('default.warehouse.delivery.local-transport-delivery',['manifest_no'=>$manifest_no]);
    }

    public function serachByManifest(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                          WHERE manifests.transshipment_flag=0
                                        AND manifests.manifest=? AND manifests.port_id=? ', [$r->mani_no,$port_id]);
        if ($chkPermission[0]->valid == 0) {
            return Response::json(['noPermission' => 'You are not permitted to see this.'], 203);
        }


        /*  if (Auth::user()->role->name == 'Assessment') {
              $trucks = DB::table('manifests AS m')
                  ->where('m.manifest', $r->mani_no)
                  ->join('vatregs AS v', 'm.vatreg_id', '=', 'v.id')
                  ->select(
                      'm.id AS m_id',
                      'm.manifest',
                      'v.NAME AS importer',
                      'm.be_no',
                      'm.be_date',
                      'm.ain_no',
                      'm.cnf_name',
                      'm.no_del_truck',
                      'm.carpenter_packages',
                      'm.carpenter_repair_packages',
                      'm.gate_pass_no',
                      'm.custom_release_order_no',
                      'm.custom_release_order_date',
                      'm.approximate_delivery_date',
                      'm.approximate_delivery_type',
                      'm.approximate_labour_load',
                      'm.approximate_equipment_load',
                      'm.local_transport_type'
                  )
                  ->get();
          } else {*/
        $trucks = DB::select('SELECT 
 (SELECT SUM(IFNULL(syw.unload_labor_weight,0) + IFNULL(syw.unload_equip_weight,0)) AS receive_weight
FROM manifests AS mm
INNER JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id
INNER JOIN shed_yard_weights AS syw ON syw.truck_id = tt.id 
WHERE mm.id = m.id) AS receive_weight,
m.id AS m_id,dr.id AS delivery_req_id, m.manifest, m.gweight AS m_gweight, m.nweight AS m_nweight,dr.local_weighment AS bd_weighment, dr.shifting_flag  AS m_shifting_flag,
m.custom_release_order_no, m.custom_release_order_date, dr.approximate_delivery_date, dr.approximate_delivery_type,  dr.approximate_labour_load,dr.approximate_equipment_load,
                                  (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                   ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                  END) AS chargeable_weight,
                                     (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) AS weigh_bridge_net_weight,
                              
					
                                (SELECT GROUP_CONCAT(DISTINCT yard_details.yard_shed_name) FROM manifests
LEFT JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
LEFT JOIN yard_details  ON FIND_IN_SET(yard_details.id, shed_yard_weights.unload_yard_shed)
 WHERE manifests.id=m.id
) AS posted_yard_shed,
                                 m.exporter_name_addr, m.vatreg_id, m.be_no, m.be_date, m.custom_approved_date,  dr.local_transport_type,
                                  dr.transport_truck, dr.transport_van, dr.local_haltage AS bd_haltage,
                               m.ain_no,m.cnf_name,  dr.carpenter_packages, dr.carpenter_repair_packages,
                                 dr.gate_pass_no, t.truck_no, t.id AS t_id, 
                                 t.driver_name, t.driver_card, t.truck_type,  t.receive_package, 
                                 v.NAME AS importer, c.ain_no, c.cnf_name
                             FROM manifests AS m 
                                JOIN truck_entry_regs AS t ON m.id = t.manf_id 
                                LEFT JOIN delivery_requisitions AS dr ON m.id = dr.manifest_id
                                LEFT JOIN cnf_details AS c ON m.cnf_id = c.id 
                                LEFT JOIN vatregs AS v ON m.vatreg_id = v.id
                                LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = t.id
                                WHERE m.manifest =? AND m.port_id=? AND t.port_id=? GROUP BY dr.id', [$r->mani_no,$port_id,$port_id]);


        return json_encode($trucks);
    }


    public function saveDeliveryRequestData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $ain_id = DB::select('SELECT cnf_details.id AS cnf_id FROM cnf_details WHERE  ain_no=?', [$r->ain_no]);
        $delivery_requisition_data = DB::select('SELECT delivery_requisitions.id AS delivery_id, COUNT(delivery_requisitions.partial_status) + 1  AS  partial_status
FROM delivery_requisitions WHERE  delivery_requisitions.manifest_id=?', [$r->manifest_id]);

        if($delivery_requisition_data[0]->delivery_id == null){
            $partial_status = 1;
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



            DB::table('delivery_requisitions')->insert([
                'manifest_id' => $r->manifest_id,
                'port_id' => $port_id,
                'partial_status' => $partial_status,
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
                'shifting_flag' => $r->shifting_flag,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')

            ]);
        }else{

            if( $r->local_transport_type == '2'){ //chassis_deliverys table

                $delivery_requi = DB::select('SELECT COUNT(DISTINCT delivery_requisitions.id) AS req_id FROM delivery_requisitions WHERE 
delivery_requisitions.manifest_id=?', [$r->manifest_id]);

                $chassis_delivery = DB::select('SELECT COUNT(DISTINCT chassis_deliverys.id) AS delivery_id FROM chassis_deliverys WHERE  
chassis_deliverys.manifest_id=?', [$r->manifest_id]);

                if ($delivery_requi[0]->req_id == $chassis_delivery[0]->delivery_id){

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
                        'shifting_flag' => $r->shifting_flag,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')

                    ]);



                }else{
                    return Response::json(['blocked' => 'Local Delivery Not Done!'], 204);
                }

            }else{      //truck_deliverys tble

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
                        'shifting_flag' => $r->shifting_flag,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')

                    ]);
                }else{

                    return Response::json(['blocked' => 'Local Delivery Not Done!'], 204);

                }
            }
        }

        if ($requestData) {
            return 'Success';
        } else {
            return Response::json(['notSaved' => 'data not saved'], 304);
        }
    }

    public function updateDeliveryRequestData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $ain_id = DB::select('SELECT cnf_details.id AS cnf_id FROM cnf_details WHERE  ain_no=?', [$r->ain_no]);

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
                'shifting_flag' => $r->shifting_flag,
                 'local_haltage' => $r->bd_haltage,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')

            ]);

        if ($requestData) {
            return 'Success';
        } else {
            return Response::json(['notSaved' => 'data not saved'], 304);
        }
    }

    //Local transport (truck or Van)===============================================

    //local transport delivery=====================================================

    public function getBillOfEntryData(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                          WHERE manifests.transshipment_flag=0
                                        AND manifests.manifest=? AND manifests.port_id=?', [$req->mani_no,$port_id]);
        $checkAssessmentDone = DB::select('SELECT COUNT(manifests.id) AS notDone
                                        FROM assessments
                                        JOIN manifests ON manifests.id=assessments.manifest_id
                                     WHERE manifests.manifest=? AND manifests.port_id=?', [$req->mani_no,$port_id]);

        $partial_number = DB::select('SELECT MAX(dr.partial_status) AS max_partial_number
                                    FROM delivery_requisitions dr
                                    INNER JOIN manifests m ON dr.manifest_id=m.id
                                    WHERE m.manifest=? AND m.port_id=? AND dr.port_id=?', [$req->mani_no, $port_id, $port_id]);

        if ($chkPermission[0]->valid == 0) {
            return Response::json(['noPermission' => 'You are not permitted to see this.'], 203);
        }elseif ($checkAssessmentDone[0]->notDone == 0){
            return Response::json(['noPermission' => 'Assessment not done.'], 203);
        }elseif (is_null($partial_number[0]->max_partial_number)){
            return Response::json(['noPermission' => 'Delivery Request not done!'], 203);
        }

        if(is_null($req->partial_status)) {
            $partial_status = $partial_number[0]->max_partial_number;
        } else {
            $partial_status = $req->partial_status;
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
            [$req->mani_no,$port_id,$port_id,$partial_status]);

        return json_encode($data);

//        array($data,$partial_number)

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

    }
    public function getLocalVanData($id,$req_id)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT td.delivery_requisition_id, td.id AS bd_truck_id,m.id AS m_id,m.manifest,m.gweight AS m_gweight,m.nweight AS m_nweight,m.no_del_truck,
                                    td.truck_no, td.truck_type_id, td.driver_name, td.gweight AS td_gweight,  td.labor_load, td.labor_package,
                                    td.equip_load, td.equipment_package, td.equip_name, td.delivery_dt AS delivery_req_dt, td.delivery_created_at,
                                    (SELECT COUNT(*) FROM chassis_deliverys AS cd WHERE cd.truck_delivery_id=td.id)AS chassis_on_this_vehicle,
                                    
                                    (SELECT GROUP_CONCAT(cd.chassis_details_id SEPARATOR \',\') FROM chassis_deliverys AS cd WHERE cd.truck_delivery_id=td.id)AS chassis_ids_on_vehicle,
                                    (SELECT SUM(IFNULL(ttd.equip_load,0)) + SUM(IFNULL(ttd.labor_load,0)) FROM truck_deliverys AS ttd WHERE ttd.manf_id=m.id  ) AS total_loaded_weight,
                                    
                                    (SELECT SUM(IFNULL(ttd.labor_package,0)) + SUM(IFNULL(ttd.equipment_package,0)) FROM truck_deliverys AS ttd WHERE ttd.manf_id=m.id  ) AS total_loaded_package,                                   
                                    (SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=0 AND truck_deliverys.manf_id=td.manf_id) AS total_truck,
                                    (SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=1 AND truck_deliverys.manf_id=td.manf_id) AS total_Van,
                                    vtb.type_name, td.transport_type, "" AS item_delivery
                                    FROM truck_deliverys AS td 
                                    JOIN manifests AS m ON m.id=td.manf_id
                                    LEFT JOIN vehicle_type_bd AS vtb ON vtb.id=td.truck_type_id
                                    WHERE td.transport_type=1 AND m.id=? AND td.delivery_requisition_id=? AND td.port_id=? AND m.port_id=?', [$id,$req_id,$port_id,$port_id]);

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

    }

    public function getTotalLocalWeight($id) {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT SUM(IFNULL(ttd.equip_load,0)) + SUM(IFNULL(ttd.labor_load,0)) 
                            AS total_loaded_weight,
                            SUM(IFNULL(ttd.labor_package,0)) + SUM(IFNULL(ttd.equipment_package,0)) AS total_loaded_package,
                            (SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=0 AND truck_deliverys.manf_id=ttd.manf_id) AS total_truck,
                            (SELECT COUNT(truck_deliverys.id) FROM truck_deliverys WHERE truck_deliverys.transport_type=1 AND truck_deliverys.manf_id=ttd.manf_id) AS total_Van
                            FROM truck_deliverys AS ttd WHERE ttd.manf_id=? AND ttd.port_id=?',[$id,$port_id]);
        return json_encode($data);
    }

    public function deleteLocalTransport($id)
    {
        $chkChassis = DB::select('SELECT chassis_deliverys.id AS c_id FROM chassis_deliverys
WHERE chassis_deliverys.truck_delivery_id=?', [$id]);

        $item_details_data = DB::select('SELECT item_deliverys.id AS it_id FROM item_deliverys
WHERE item_deliverys.truck_delivery_id=?', [$id]);
//                \Log::info($id);
//
//                    $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"Hello r".$id);
//            fclose($file);
        if (isset($item_details_data[0]->it_id)){
            DB::table('item_deliverys')->where('truck_delivery_id', $id)->delete();
        }
        if (isset($chkChassis[0]->c_id)){
            DB::table('chassis_deliverys')->where('truck_delivery_id', $id)->delete();
        }
        DB::table('truck_deliverys')->where('id', $id)->delete();



        return 'success';
    }


    public function getUndeliveredChassisListByManifest($mani_id)
    {
        $port_id = Session::get('PORT_ID');
        $undelivered_chassis_list = DB::select('SELECT * FROM chassis_details AS cd 
                                WHERE cd.id NOT IN(SELECT cdv.chassis_details_id FROM chassis_deliverys AS cdv 
                                JOIN chassis_details AS cd ON cd.id=cdv.chassis_details_id
                                WHERE cdv.port_id=? AND cd.manifest_id=?)
                                AND  cd.manifest_id=? AND cd.port_id=?', [$port_id,$mani_id, $mani_id,$port_id]);

        return json_encode($undelivered_chassis_list);

    }


    public function getItemDeliverysUpdateForLocalTransport($item_id)
    {
        $port_id = Session::get('PORT_ID');



        $item_delivery_list = DB::select('SELECT item_deliverys.loadable_weight, item_deliverys.loadable_package,item_details.*,ic.Description FROM item_deliverys 
JOIN item_details ON item_details.id = item_deliverys.item_detail_id
JOIN  item_codes AS ic  ON item_details.item_Code_id = ic.id
  WHERE item_deliverys.truck_delivery_id=?', [$item_id]);

        return json_encode($item_delivery_list);

    }



    public function getChassisListForLocalTransport($trans_id)
    {
        $port_id = Session::get('PORT_ID');
        $get_mani_id = DB::select('SELECT td.manf_id AS manifest_id 
  FROM truck_deliverys AS td
  WHERE td.port_id=? AND td.id=? LIMIT 1', [$port_id,$trans_id]);

        $mani_id = $get_mani_id ? $get_mani_id[0]->manifest_id : null;

        // return $mani_id;

        $chassis_list = DB::select('SELECT * FROM chassis_details AS cd 
                                        WHERE cd.id NOT IN(SELECT cd.id FROM  chassis_deliverys AS cdv 
                                        JOIN chassis_details AS cd  ON cd.id=cdv.chassis_details_id 
                                        WHERE cdv.port_id=? AND cdv.truck_delivery_id!=? OR cdv.truck_delivery_id IS NULL AND cd.manifest_id=?)
                                        AND  cd.manifest_id=? AND cd.port_id=?', [$port_id,$trans_id, $mani_id, $mani_id,$port_id]);

        return json_encode($chassis_list);

    }

    public function getSelfDeliveredChassisListByManifest($mani_id)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT cdv.*,cd.chassis_type,cd.chassis_no,cd.manifest_id,"" AS item_delivery 
                                    FROM chassis_deliverys AS cdv 
                                    JOIN chassis_details AS cd ON cd.id=cdv.chassis_details_id
                                    WHERE cd.port_id=? AND cdv.port_id=? AND  cd.manifest_id=? AND cdv.truck_delivery_id IS  NULL', [$port_id,$port_id,$mani_id]);


        if(count($data) > 0) {
            for( $i = 0; $i < count($data); $i++) {
                // return $data[$i]->item_delivery;
                $item_data = DB::select("SELECT item_details.*,ic.Description FROM item_deliverys 
                              JOIN item_details ON item_details.id = item_deliverys.item_detail_id
                              JOIN  item_codes AS ic  ON item_details.item_Code_id = ic.id
                              WHERE item_deliverys.truck_delivery_id=?", [$data[$i]->id]);
                $data[$i]->item_delivery = $item_data;
                //$result = array_merge( (array)$data[$i]->item_delivery, (array)$item_data);
                //array_push($data[$i]->item_delivery, $item_data);
                //array_merge( (array)$data[$i]->item_delivery, (array)$item_data);
                // $data[$i]->item_delivery = $item_data;
            }

            //return $data;
        }


        return json_encode($data);

    }

    public function saveSelfTransportData(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        //  return $req->selfTransportDriverName;


           if ($req->update_chassis_del_id == null){//Save Data


               $chassis_id = DB::table('chassis_deliverys')->insertGetId([
                   'port_id' => $port_id,
                   'manifest_id' => $req->manf_id,
                   'chassis_details_id' => $req->selfTransportId,
                   'delivery_requisition_id' => $req->delivery_requisition_id,
                   'driver_name' => $req->selfTransportDriverName,
                   'driver_card' => $req->selfTransportDriverCard,
                   'delivery_dt' => $req->delivery_req_date,
                   'partial_status' => $req->dr_partial_status,
                   'created_at' => date('Y-m-d H:i:s'),
                   'created_by' => Auth::user()->id,


               ]);


               if($req->delivery_item_list) {

                   foreach ($req->delivery_item_list as $key => $value) {

                       if(isset($value['checkbox'])){

                           $id = DB::table('item_deliverys')->insert([
                               'manifest_id' =>$req->manf_id,
                               'item_detail_id' => $value['id'],
                               'truck_delivery_id' => $chassis_id,
                               'partial_status' => $req->dr_partial_status,
                               'created_by' => Auth::user()->id,
                               'created_at' => date('Y-m-d H:i:s')

                           ]);
                       }
                   }
               }


//               return Response::json(200);
               return Response::json(203);

           }else{

//               $updatechassis = DB::table('chassis_deliverys')
//                   ->where('id', $req->update_chassis_del_id)
//                   ->update([
//                   'chassis_details_id' => $req->selfTransportId,
//                   'delivery_requisition_id' => $req->delivery_requisition_id,
//                   'driver_name' => $req->selfTransportDriverName,
//                   'driver_card' => $req->selfTransportDriverCard,
//                   'delivery_dt' => $req->delivery_req_date,
//                   'updated_at' => date('Y-m-d H:i:s'),
//                   'updated_by' => Auth::user()->id,
//
//               ]);


               if($req->delivery_item_list) {
                   DB::table('item_deliverys')->where('truck_delivery_id', $req->update_chassis_del_id)->delete();
                   foreach ($req->delivery_item_list as $key => $value) {

                       if(isset($value['checkbox'])){
                           if(isset($value['checkbox'])){
                               if ($value['checkbox'] == true){
                                   $id = DB::table('item_deliverys')->insert([
                                       'manifest_id' =>$req->manf_id,
                                       'item_detail_id' => $value['id'],
                                       'truck_delivery_id' => $req->update_chassis_del_id,
                                       'partial_status' => $req->dr_partial_status,
                                       'created_by' => Auth::user()->id,
                                       'created_at' => date('Y-m-d H:i:s')

                                   ]);
                               }

                           }

                       }
                   }
               }


//               return Response::json(201);
               return Response::json(203);
           }


    }

    public function deleteSelfTransportDelivery($id)
    {

        $item_details_data = DB::select('SELECT item_deliverys.id AS it_id FROM item_deliverys
WHERE item_deliverys.truck_delivery_id=?', [$id]);
        if (isset($item_details_data[0]->it_id)){
            DB::table('item_deliverys')->where('truck_delivery_id', $id)->delete();
        }
        //return $id;
        DB::table('chassis_deliverys')->where('id', $id)->delete();
        return Response::json(203);
    }


    public function saveLocalTransportData(Request $req)
    {//local transport with or without chassis/tructor on it
//        Log::info("working");
        $port_id = Session::get('PORT_ID');
//        $bdTruckType = DB::SELECT("SELECT type_name  FROM vehicle_type_bd  WHERE id= ?", [$req->truck_type_id]);
        $partial_status = DB::SELECT('SELECT MAX(partial_status) AS partial_stat FROM assessments
                                    WHERE manifest_id=? AND assessments.port_id =? ', [$req->manf_id,$port_id]);
        // return $req->all_onVehicleTransportId_array;

//        $trans_no='';
//        if ($req->transport_type==0)//truck
//        {
//            $trans_no= $req->truck_no . '-' . $bdTruckType[0]->type_name;
//        }elseif ($req->transport_type==1)//van
//        {
//            $trans_no= $req->truck_no;
//        }

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
//                'haltage_day' => $req->haltage_day,
                'delivery_dt' => $req->delivery_dt,
                'delivery_created_at' => date('Y-m-d H:i:s'),
                'delivery_created_by' => Auth::user()->id
//                'partial_status' => $partial_status[0]->partial_stat,

            ]);

//            \Log::info($req->delivery_item_list);
//            if($req->delivery_item_list){
//
//            }

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
           // \Log::info($value['id']);
            //return $local_truck_id;
            if ($req->all_onVehicleTransportIds) {
                // return $local_truck_id;
                $myArray = explode(',', $req->all_onVehicleTransportIds);
                foreach ($myArray as $chassis_tructor_id) {
                    // return $chassis_tructor_id;
                    $chassis_tructor_data = DB::select('SELECT * FROM chassis_details AS cd WHERE cd.id=?', [$chassis_tructor_id]);
                    if (!empty($chassis_tructor_data)) {
                        $id = DB::table('chassis_deliverys')->insert([
                            'manifest_id' =>$req->manf_id,
                            'partial_status' => $req->dr_partial_status,
                            'delivery_requisition_id' => $req->delivery_requisition_id,
                            'chassis_details_id' => $chassis_tructor_id,
                            'port_id' => $port_id,
                            'truck_delivery_id' => $local_truck_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->id,
                            'delivery_dt' => $req->delivery_dt
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


            if ($req->all_onVehicleTransportIds) {
                DB::table('chassis_deliverys')->where('truck_delivery_id', $req->bd_truck_id)->delete();
                $myArray = explode(',', $req->all_onVehicleTransportIds);
                foreach ($myArray as $chassis_tructor_id) {
                    // return $chassis_tructor_id;
                    $chassis_tructor_data = DB::select('SELECT * FROM chassis_details AS cd WHERE cd.id=?', [$chassis_tructor_id]);
                    if (!empty($chassis_tructor_data)) {
                        $id = DB::table('chassis_deliverys')->insert([
                            'manifest_id' =>$req->manf_id,
                            'partial_status' => $req->dr_partial_status,
                            'delivery_requisition_id' => $req->delivery_requisition_id,
                            'chassis_details_id' => $chassis_tructor_id,
                            'port_id' => $port_id,
                            'truck_delivery_id' => $req->bd_truck_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->id,
                            'delivery_dt' => $req->delivery_dt
                        ]);
                    }


                }
            }

            return Response::json(201);
        }
    }

    //Warehouse Delivery
    public function warehouseDeliveryMonitorView()
    {
        return view('default.warehouse.delivery.warehouse-delivery-monitor');
    }


    public function getDateWiseWarehouseDeliveryMonitor($date)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::select("SELECT (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ')               FROM yard_details AS yd 
                            JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
                            JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                            JOIN manifests AS ma ON ma.id = ter.manf_id
                            WHERE ma.id = manifests.id) AS yard_shed_name, manifests.self_flag,delivery_requisitions.local_transport_type, 
                            manifests.manifest, CONCAT(truck_deliverys.truck_no,'-',(SELECT vehicle_type_bd.type_name 
                            FROM vehicle_type_bd WHERE vehicle_type_bd.id = truck_deliverys.truck_type_id)) AS truck_type_no,
                            (SELECT CONCAT(chassis_details.chassis_no,'-',chassis_details.chassis_type) FROM chassis_details WHERE
                            chassis_details.id = chassis_deliverys.chassis_details_id) AS self_type_no,
                            truck_deliverys.driver_name, 
                            delivery_requisitions.approximate_delivery_date, truck_deliverys.delivery_dt AS truck_delivery_date,
                            (SELECT users.name FROM users WHERE users.id = delivery_requisitions.created_by) AS created_by,
                            delivery_requisitions.created_at,
                            (SELECT users.name FROM users WHERE users.id = delivery_requisitions.updated_by) AS updated_by,
                            delivery_requisitions.updated_at,
                            (SELECT users.name FROM users WHERE users.id = truck_deliverys.delivery_created_by) AS local_transport_entry_by,
                            truck_deliverys.delivery_created_at AS local_transport_entry_at,
                            chassis_deliverys.delivery_dt AS self_delivery_date,
                            (SELECT users.name FROM users WHERE users.id = truck_deliverys.delivery_updated_by) AS local_transport_updated_by,
                            truck_deliverys.delivery_created_at AS local_transport_updated_at,
                            (SELECT users.name FROM users WHERE users.id = chassis_deliverys.created_by) AS local_self_entry_by,
                            chassis_deliverys.created_at AS local_self_entry_at,
                            (SELECT users.name FROM users WHERE users.id = chassis_deliverys.updated_by) AS local_self_updated_by,
                            chassis_deliverys.updated_at AS local_self_updated_at
                            FROM manifests
                            JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
                            LEFT JOIN truck_deliverys ON truck_deliverys.delivery_requisition_id = delivery_requisitions.id
                            LEFT JOIN chassis_deliverys ON chassis_deliverys.delivery_requisition_id = delivery_requisitions.id
                            WHERE DATE(delivery_requisitions.approximate_delivery_date) = ? AND manifests.port_id=?
                            ORDER BY DATE(delivery_requisitions.id) DESC", [$date,$port_id]);
        return json_encode($data);
    }


    public function getDatewiseDeliveryReport($date) {
        $todayWithTime = date('Y-m-d h:i:s a');
        $port_id = Session::get('PORT_ID');
        $dataAll = DB::select("SELECT (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ')               FROM yard_details AS yd 
                            JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
                            JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                            JOIN manifests AS ma ON ma.id = ter.manf_id
                            WHERE ma.id = manifests.id) AS yard_shed_name, manifests.self_flag,delivery_requisitions.local_transport_type, 
                            manifests.manifest, CONCAT(truck_deliverys.truck_no,'-',(SELECT vehicle_type_bd.type_name 
                            FROM vehicle_type_bd WHERE vehicle_type_bd.id = truck_deliverys.truck_type_id)) AS truck_type_no,
                            (SELECT CONCAT(chassis_details.chassis_no,'-',chassis_details.chassis_type) FROM chassis_details WHERE
                            chassis_details.id = chassis_deliverys.chassis_details_id) AS self_type_no,
                            truck_deliverys.driver_name, 
                            delivery_requisitions.approximate_delivery_date, truck_deliverys.delivery_dt AS truck_delivery_date,
                            (SELECT users.name FROM users WHERE users.id = delivery_requisitions.created_by) AS created_by,
                            delivery_requisitions.created_at,
                            (SELECT users.name FROM users WHERE users.id = delivery_requisitions.updated_by) AS updated_by,
                            delivery_requisitions.updated_at,
                            (SELECT users.name FROM users WHERE users.id = truck_deliverys.delivery_created_by) AS local_transport_entry_by,
                            truck_deliverys.delivery_created_at AS local_transport_entry_at,
                            chassis_deliverys.delivery_dt AS self_delivery_date,
                            (SELECT users.name FROM users WHERE users.id = truck_deliverys.delivery_updated_by) AS local_transport_updated_by,
                            truck_deliverys.delivery_created_at AS local_transport_updated_at,
                            (SELECT users.name FROM users WHERE users.id = chassis_deliverys.created_by) AS local_self_entry_by,
                            chassis_deliverys.created_at AS local_self_entry_at,
                            (SELECT users.name FROM users WHERE users.id = chassis_deliverys.updated_by) AS local_self_updated_by,
                            chassis_deliverys.updated_at AS local_self_updated_at
                            FROM manifests
                            JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
                            LEFT JOIN truck_deliverys ON truck_deliverys.delivery_requisition_id = delivery_requisitions.id
                            LEFT JOIN chassis_deliverys ON chassis_deliverys.delivery_requisition_id = delivery_requisitions.id
                            WHERE DATE(delivery_requisitions.approximate_delivery_date) = ? AND manifests.port_id=?
                            ORDER BY DATE(delivery_requisitions.id) DESC", [$date,$port_id]);

        if ($dataAll) {
            $pdf = PDF::loadView('default.warehouse.delivery.reports.date-wise-warehouse-delivery-report', [

                'dataAll' => $dataAll,
                'date' => $date,
                'todayWithTime' => $todayWithTime

            ])->setPaper([0, 0, 980, 1000]);
            return $pdf->stream('date-wise-warehouse-delivery-report.pdf');
        }
        else {
            return view('default.warehouse.not-found',['requestedDate'=> $date]);
        }




    }



}
