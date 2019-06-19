<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use Response;
use Session;

class TruckToTruckController extends Controller
{
    public function truckToTruckView($manifest = null, $truck = null, $year = null) {
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        return view('default.warehouse.truck-to-truck.truck-to-truck-delivery-request-entry-form', ['manifest_no' => $manifest_no]);
    }

    public function serachByManifest(Request $r) {
        $port_id = Session::get('PORT_ID');
        $chkPermission = DB::select('SELECT COUNT(manifests.id) AS valid
                                        FROM manifests
                                          WHERE manifests.transshipment_flag=0
                                        AND manifests.manifest=? AND manifests.port_id=? ', [$r->mani_no,$port_id]);

        if ($chkPermission[0]->valid == 0) {
            return Response::json(['errorMessage' => 'You are not permitted to see this.'], 203);
        }

        $checkReceive = DB::select('SELECT COUNT(syw.id) AS receive_count FROM shed_yard_weights AS syw 
                                    JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                                    JOIN manifests as m ON m.id = ter.manf_id WHERE m.manifest = ? AND m.port_id = ?',[$r->mani_no, $port_id]);
        if($checkReceive[0]->receive_count > 0) {
            return Response::json(['errorMessage' => $r->mani_no.' is Already Received.'], 203);
        }

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
                               m.ain_no,m.cnf_name,  dr.carpenter_packages, dr.carpenter_repair_packages, dr.truck_to_truck_flag,
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

    public function saveDeliveryRequestData(Request $r) {
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
                    'custom_approved_date' => date('Y-m-d H:i:s')
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
                'truck_to_truck_flag' => $r->truck_to_truck_flag,
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
                        'truck_to_truck_flag' => $r->truck_to_truck_flag,
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
                        'truck_to_truck_flag' => $r->truck_to_truck_flag,
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

    public function updateDeliveryRequestData(Request $r) {
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
                'custom_approved_date' => date('Y-m-d H:i:s')
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
                'truck_to_truck_flag' => $r->truck_to_truck_flag,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')

            ]);

        if ($requestData) {
            return 'Success';
        } else {
            return Response::json(['notSaved' => 'data not saved'], 304);
        }
    }
}
