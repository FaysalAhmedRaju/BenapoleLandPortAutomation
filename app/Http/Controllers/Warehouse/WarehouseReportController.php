<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse\ShedYard;
use Auth;
use Session;
use PDF;
use DB;
use App\Models\Warehouse\YardDetail;

class WarehouseReportController extends Controller
{
    public function warehouseAllReportsView(){
    	$port_id = Session::get('PORT_ID');
        $item_list=DB::select('SELECT * FROM item_codes ORDER BY Description ASC');
        $yard_shed_list = DB::select('SELECT yd.id, yd.yard_shed_name FROM yard_details AS yd WHERE yd.port_id=?',[$port_id]);
        return view('default.warehouse.warehouse-all-reports-view',['item_list'=>$item_list,'yard_shed_list'=>$yard_shed_list]);
    }

    public function dateWiseWareHouseEntryReport(Request $r) {
        //return $r->submit_manifest;
        $port_id = Session::get('PORT_ID');
        $arrayShedYardId = array();
        $shedYardName = array();

        foreach (Auth::user()->shedYards as $k => $v) {

            $arrayShedYardId[] = $v->id;
            $shedYardName[] = $v->shed_yard;
        }

        if (Auth::user()->role_id==11){//for maintencace role
            $shed_yards = ShedYard::where('port_id',$port_id)->get();
            foreach($shed_yards as $k => $v) {
                $shedYardName[] = $v->shed_yard;
            }
        }

        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('port_id', $port_id)->get();

        $array_name = array();
        foreach ($yard_details_array  as $k => $v){
            $array_name[]  = $v->id;
        }

        $shedYard = implode(',', $array_name);

        $flagValue = isset($r->vehile_type_flage_pdf) ? $r->vehile_type_flage_pdf : 1;


        $date = date('Y-m-d');

        if ($r->date) {
            $date = $r->date;
        }

        $todayWithTime = date('Y-m-d h:i:s a');
        $manifestWiseWareHouseEntry = [];
        $todaysWareHouseEntry = [];
        if(isset($r->submit_truck) && $r->submit_truck == 1) {
            $wise = 'Truck';
            if (Auth::user()->role->id == 8) {    // wareHouse Receive is 8
                $todaysWareHouseEntry = DB::select("SELECT  yard_details.yard_shed_name,sw.unload_receive_datetime,yard_details.yard_shed,sw.unload_yard_shed,
                                        m.transshipment_flag,t.id,sw.id,
                                        CONCAT(t.truck_type,'-',t.truck_no) AS truck_type_no,t.receive_weight, t.receive_package, sw.unload_comment,
                                        sw.unload_labor_weight,sw.unload_equip_weight,  sw.unload_equip_name, sw.unload_labor_package, sw.unload_equipment_package,  
                                        m.manifest,t.vehicle_type_flag, users.name, dr.approximate_delivery_date
                                        FROM manifests AS m 
                                        JOIN truck_entry_regs AS t ON t.manf_id = m.id 
                                        JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
                                        LEFT JOIN users ON users.id = sw.created_by
                                        LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
                                        LEFT JOIN delivery_requisitions AS dr ON dr.manifest_id = m.id
                                        WHERE sw.unload_yard_shed IN ($shedYard) AND  DATE(sw.unload_receive_datetime)=?
                                        AND t.port_id =? AND sw.port_id=? AND  t.vehicle_type_flag=?
                                        ORDER BY m.id DESC", [$date, $port_id, $port_id, $flagValue]);
            }
            if (Auth::user()->role->id == 1 || Auth::user()->role->id == 11) {
                $todaysWareHouseEntry = DB::select("SELECT  yard_details.yard_shed_name,sw.unload_receive_datetime,yard_details.yard_shed,sw.unload_yard_shed,
                                        m.transshipment_flag,t.id,sw.id,
                                        CONCAT(t.truck_type,'-',t.truck_no) AS truck_type_no, t.receive_weight, t.receive_package, sw.unload_comment,
                                        sw.unload_labor_weight,sw.unload_equip_weight,  sw.unload_equip_name, sw.unload_labor_package, sw.unload_equipment_package,  
                                        m.manifest,t.vehicle_type_flag, users.name, dr.approximate_delivery_date
                                        FROM manifests AS m 
                                        JOIN truck_entry_regs AS t ON t.manf_id = m.id 
                                        JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
                                        LEFT JOIN users ON users.id = sw.created_by
                                        LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
                                        LEFT JOIN delivery_requisitions AS dr ON dr.manifest_id = m.id
                                        WHERE DATE(sw.unload_receive_datetime)=?
                                        AND t.port_id =? AND sw.port_id=? AND  t.vehicle_type_flag=?
                                        ORDER BY m.id DESC", [$date, $port_id, $port_id, $flagValue]);
            }
        } else {
            $wise = 'Manifest';
            if (Auth::user()->role->id == 8) {
                $manifestWiseWareHouseEntry = DB::SELECT("SELECT  GROUP_CONCAT(DISTINCT yard_details.yard_shed_name SEPARATOR ', ') AS yard_shed_name,
                                            GROUP_CONCAT(DISTINCT DATE(sw.unload_receive_datetime) SEPARATOR ', ') AS unload_receive_datetime,
                                            GROUP_CONCAT(CONCAT(t.truck_type,'-', t.truck_no) SEPARATOR ', ') AS truck_type_no , 
                                            SUM(IFNULL(t.receive_weight,0)) AS receive_weight,
                                            CASE WHEN t.receive_package REGEXP '^[[:digit:]]+$' THEN 
                                               SUM(IFNULL(t.receive_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT t.receive_package SEPARATOR ', ')
                                            END AS receive_package,
                                            GROUP_CONCAT(DISTINCT sw.unload_comment SEPARATOR ', ') AS unload_comment,
                                            SUM(IFNULL(sw.unload_labor_weight, 0)) AS unload_labor_weight,
                                            SUM(IFNULL(sw.unload_equip_weight, 0)) AS unload_equip_weight, 
                                            GROUP_CONCAT(DISTINCT sw.unload_equip_name SEPARATOR ', ') AS unload_equip_name,
                                            CASE WHEN sw.unload_labor_package REGEXP '^[[:digit:]]+$' THEN 
                                             SUM(IFNULL(sw.unload_labor_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT sw.unload_labor_package SEPARATOR ', ')
                                            END AS unload_labor_package,
                                            CASE WHEN sw.unload_equipment_package REGEXP '^[[:digit:]]+$' THEN
                                              SUM(IFNULL(sw.unload_equipment_package,0)) 
                                            ELSE 
                                              GROUP_CONCAT(DISTINCT sw.unload_equipment_package SEPARATOR ', ')
                                            END AS unload_equipment_package,
                                            m.manifest, DATE_FORMAT(DATE(m.manifest_date), '%d-%m-%Y') AS manifest_date, users.name,
                                            GROUP_CONCAT(DISTINCT cd.cargo_name SEPARATOR ', ') AS goods_name, m.marks_no, m.gweight,
                                            CASE WHEN m.package_no REGEXP '^[[:digit:]]+$' THEN
                                              NULLIF(REPLACE(FORMAT(m.package_no,0), ',', ''), 0)
                                            ELSE
                                              m.package_no
                                            END AS manifest_receive_package, 
                                            NULLIF(REPLACE(FORMAT(m.cnf_value,0), ',', ''), 0) AS cnf_value,
                                            GROUP_CONCAT(DISTINCT DATE_FORMAT(DATE(t.truckentry_datetime), '%d-%m-%Y') SEPARATOR ', ') AS truckentry_datetime,
                                            CONCAT(v.name, CASE WHEN v.ADD1 IS NOT NULL THEN CONCAT(', ', v.ADD1) ELSE '' END) AS importer_name_and_address,
                                            DATE_FORMAT(DATE(dr.approximate_delivery_date), '%d-%m-%Y') AS approximate_delivery_date
                                            FROM manifests AS m 
                                            JOIN truck_entry_regs AS t ON t.manf_id = m.id 
                                            JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
                                            LEFT JOIN users ON users.id = sw.created_by
                                            LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
                                            JOIN cargo_details AS cd ON FIND_IN_SET(cd.id, m.goods_id) > 0
                                            JOIN vatregs as v on v.id = m.vatreg_id
                                            LEFT JOIN delivery_requisitions AS dr ON dr.manifest_id = m.id
                                            WHERE sw.unload_yard_shed IN ($shedYard) AND  DATE(sw.unload_receive_datetime)=?
                                            AND t.port_id =? AND sw.port_id=? AND  t.vehicle_type_flag=?
                                            GROUP BY m.id ORDER BY m.id DESC",[$date, $port_id, $port_id, $flagValue]);
            }
            if (Auth::user()->role->id == 1 || Auth::user()->role->id == 11) {
                $manifestWiseWareHouseEntry = DB::SELECT("SELECT  GROUP_CONCAT(DISTINCT yard_details.yard_shed_name SEPARATOR ', ') AS yard_shed_name,
                                            GROUP_CONCAT(DISTINCT DATE(sw.unload_receive_datetime) SEPARATOR ', ') AS unload_receive_datetime,
                                            GROUP_CONCAT(CONCAT(t.truck_type,'-', t.truck_no) SEPARATOR ', ') AS truck_type_no , 
                                            SUM(IFNULL(t.receive_weight,0)) AS receive_weight,
                                            CASE WHEN t.receive_package REGEXP '^[[:digit:]]+$' THEN 
                                               SUM(IFNULL(t.receive_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT t.receive_package SEPARATOR ', ')
                                            END AS receive_package,
                                            GROUP_CONCAT(DISTINCT sw.unload_comment SEPARATOR ', ') AS unload_comment,
                                            SUM(IFNULL(sw.unload_labor_weight, 0)) AS unload_labor_weight,
                                            SUM(IFNULL(sw.unload_equip_weight, 0)) AS unload_equip_weight, 
                                            GROUP_CONCAT(DISTINCT sw.unload_equip_name SEPARATOR ', ') AS unload_equip_name,
                                            CASE WHEN sw.unload_labor_package REGEXP '^[[:digit:]]+$' THEN 
                                             SUM(IFNULL(sw.unload_labor_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT sw.unload_labor_package SEPARATOR ', ')
                                            END AS unload_labor_package,
                                            CASE WHEN sw.unload_equipment_package REGEXP '^[[:digit:]]+$' THEN
                                              SUM(IFNULL(sw.unload_equipment_package,0)) 
                                            ELSE 
                                              GROUP_CONCAT(DISTINCT sw.unload_equipment_package SEPARATOR ', ')
                                            END AS unload_equipment_package,
                                            m.manifest, DATE_FORMAT(DATE(m.manifest_date), '%d-%m-%Y') AS manifest_date, users.name,
                                            GROUP_CONCAT(DISTINCT cd.cargo_name SEPARATOR ', ') AS goods_name, m.marks_no, m.gweight,
                                            CASE WHEN m.package_no REGEXP '^[[:digit:]]+$' THEN
                                              NULLIF(REPLACE(FORMAT(m.package_no,0), ',', ''), 0)
                                            ELSE
                                              m.package_no
                                            END AS manifest_receive_package,
                                            NULLIF(REPLACE(FORMAT(m.cnf_value,0), ',', ''), 0) AS cnf_value,
                                            GROUP_CONCAT(DISTINCT DATE_FORMAT(DATE(t.truckentry_datetime), '%d-%m-%Y') SEPARATOR ', ') AS truckentry_datetime,
                                            CONCAT(v.name, CASE WHEN v.ADD1 IS NOT NULL THEN CONCAT(', ', v.ADD1) ELSE '' END) AS importer_name_and_address,
                                            DATE_FORMAT(DATE(dr.approximate_delivery_date), '%d-%m-%Y') AS approximate_delivery_date
                                            FROM manifests AS m 
                                            JOIN truck_entry_regs AS t ON t.manf_id = m.id 
                                            JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
                                            LEFT JOIN users ON users.id = sw.created_by
                                            LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
                                            JOIN cargo_details AS cd ON FIND_IN_SET(cd.id, m.goods_id) > 0
                                            JOIN vatregs AS v ON v.id = m.vatreg_id
                                            LEFT JOIN delivery_requisitions AS dr ON dr.manifest_id = m.id
                                            WHERE DATE(sw.unload_receive_datetime)=?
                                            AND t.port_id =? AND sw.port_id=? AND  t.vehicle_type_flag=?
                                            GROUP BY m.id ORDER BY m.id DESC",[$date, $port_id, $port_id, $flagValue]);
            }
        }

        if ($flagValue == 1) {
            $typeOfReports = 'Received Truck Report';
        } elseif ($flagValue == 2) {
            $typeOfReports = 'Received Chassis(on Trailer) Report';
        } elseif ($flagValue == 3) {
            $typeOfReports = 'Received Tractor(on Trailer) Report';
        } elseif ($flagValue == 4) {
            $typeOfReports = 'Received Tractor(on Trailer) Report';
        } elseif ($flagValue == 5) {
            $typeOfReports = 'Received Lorry Report';
        } elseif ($flagValue == 6) {
            $typeOfReports = 'Received Mini Pickup Report';
        } elseif ($flagValue == 7) {
            $typeOfReports = 'Received Prime Mover Report';
        } elseif ($flagValue == 8) {
            $typeOfReports = 'Received Tanker Report';
        } elseif ($flagValue == 9) {
            $typeOfReports = 'Received Vehicle in CBU Report';
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
        } elseif ($flagValue == 18) {
            $typeOfReports = 'Received Trailor(Self) Report';
        } else {
            $typeOfReports = 'Received Report';
        }


        if(count($todaysWareHouseEntry) && isset($r->submit_truck) && $r->submit_truck == 1) {

	        $pdf = PDF::loadView('default.warehouse.receive.reports.date-wise-warehouse-entry-report',
	            [
	                'todaysWareHouseEntry' => $todaysWareHouseEntry,
	                'todayWithTime' => $todayWithTime,
	                'typeOfReports' => $typeOfReports,
	                'date' => $date,
                    'wise' => $wise

	            ])/*->setPaper('a4', 'landscape');*/
	        ->setPaper([0, 0, 900, 900]);
	        //return $pdf->download('user.pdf');
	        return $pdf->stream($date . '-datewise-warehouse-entry-report.pdf');
        } elseif (count($manifestWiseWareHouseEntry) && isset($r->submit_manifest) && $r->submit_manifest == 1) {
            $pdf = PDF::loadView('default.warehouse.receive.reports.date-and-manifest-wise-warehouse-entry-report',
                [
                    'data' => $manifestWiseWareHouseEntry,
                    'todayWithTime' => $todayWithTime,
                    'typeOfReports' => $typeOfReports,
                    'date' => $date,
                    'wise' => $wise,
                    'shedYardName' => $shedYardName
                ])
            ->setPaper([0, 0, 1000, 900]);
            return $pdf->stream($date . '-date-and-manifest-warehouse-entry-report.pdf');
        } else {
        	return view('default.warehouse.not-found',['requestedDate' => $date]);
        }
    }

    public function dateWiseDeliveryRequestReport(Request $req) {
        $port_id = Session::get('PORT_ID');
        $today = $req->date;
        $todayWithTime = date('Y-m-d h:i:s a');
        if (Auth::user()->role->id == 12) {   // transhipment
            $todaysDeliveryRequest = DB::select("SELECT manifests.manifest,delivery_requisitions.approximate_delivery_date, yard_details.yard_shed_name AS yard_shed_name,
manifests.*,cargo_details.*,vatregs.* FROM manifests
				JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
				JOIN truck_entry_regs ON truck_entry_regs.manf_id= manifests.id
				LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
				JOIN cargo_details  ON cargo_details.id = manifests.goods_id
				JOIN vatregs ON vatregs.id = manifests.vatreg_id
				JOIN users ON users.id = manifests.custom_approved_by
				JOIN yard_details ON yard_details.id = shed_yard_weights.unload_yard_shed
				WHERE manifests.transshipment_flag=1 AND manifests.port_id =? AND
				delivery_requisitions.approximate_delivery_date =?
				GROUP BY manifests.id  /*ORDER BY manifests.approximate_delivery_date DESC*/", [$port_id, $today]);
        } else if(Auth::user()->role->id == 8) { //wareHouse
        	$todaysDeliveryRequest = DB::select("SELECT manifests.manifest,delivery_requisitions.approximate_delivery_date, 
GROUP_CONCAT( DISTINCT yard_details.yard_shed_name) AS yard_shed_name,

 manifests.*,cargo_details.*,vatregs.* FROM manifests
				JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
				JOIN truck_entry_regs ON truck_entry_regs.manf_id= manifests.id
				LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
				JOIN cargo_details  ON cargo_details.id = manifests.goods_id
				JOIN vatregs ON vatregs.id = manifests.vatreg_id
				JOIN users ON users.id = manifests.custom_approved_by
				JOIN yard_details ON FIND_IN_SET(yard_details.shed_yard_id, shed_yard_weights.unload_yard_shed) > 0
				WHERE manifests.transshipment_flag=0 AND manifests.port_id =? AND
				delivery_requisitions.approximate_delivery_date =?
				GROUP BY manifests.id  /*ORDER BY manifests.approximate_delivery_date DESC*/", [$port_id, $today]);
        } else { 
            $todaysDeliveryRequest = DB::select("SELECT manifests.manifest,delivery_requisitions.approximate_delivery_date, 
                              GROUP_CONCAT( DISTINCT yard_details.yard_shed_name) AS yard_shed_name,
                              manifests.*,cargo_details.*,vatregs.* FROM manifests
				JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
				JOIN truck_entry_regs ON truck_entry_regs.manf_id= manifests.id
				LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
				JOIN cargo_details  ON cargo_details.id = manifests.goods_id
				JOIN vatregs ON vatregs.id = manifests.vatreg_id
				JOIN users ON users.id = manifests.custom_approved_by
				JOIN yard_details ON FIND_IN_SET(yard_details.shed_yard_id, shed_yard_weights.unload_yard_shed) > 0
				WHERE manifests.port_id =? AND
				delivery_requisitions.approximate_delivery_date =?
				GROUP BY manifests.id", [$port_id,$today]);

        }
        if(count($todaysDeliveryRequest)) {
	        $pdf = PDF::loadView('default.warehouse.delivery.reports.date-wise-delivery-request-report', [
	            'todaysDeliveryRequest' => $todaysDeliveryRequest,
	            'todayWithTime' => $todayWithTime,
	            'today' => $today
	        ])->setPaper([0, 0, 1000, 900]);
	        return $pdf->stream('date-wise-delivery-request-report-'.$today.'.pdf');	
        } else {
        	return view('default.warehouse.not-found',['requestedDate' => $today]);
        }
    }

    public function datewiseAndShedsWiseWareHouseEntryReport(Request $r) {
        $date = date('Y-m-d');
        $shed_yard = $r->item;
        $todayWithTime = date('Y-m-d h:i:s a');
        if ($r->date) {
            $date = $r->date;
        }
        $port_id = Session::get('PORT_ID');

        $WareHouseEntry = DB::select("SELECT yard_details.yard_shed_name,sw.unload_receive_datetime,						yard_details.yard_shed,sw.unload_yard_shed,m.transshipment_flag,t.id,sw.id,
							t.truck_type,t.truck_no,t.receive_weight, t.receive_package,sw.unload_comment,
							sw.unload_labor_weight,sw.unload_equip_weight,  sw.unload_equip_name, sw.unload_labor_package, sw.unload_equipment_package,
 								m.manifest,t.vehicle_type_flag, users.name
							FROM manifests AS m 
							JOIN truck_entry_regs AS t ON t.manf_id = m.id 
							JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
							LEFT JOIN users ON users.id = sw.created_by
							LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
							WHERE DATE(sw.unload_receive_datetime)=? AND t.port_id =? AND sw.port_id=? 
							AND sw.unload_yard_shed = ? 
							ORDER BY yard_details.yard_shed DESC , sw.unload_receive_datetime DESC", [$date,$port_id,$port_id, $shed_yard]);
        if(count($WareHouseEntry)) {
	        $pdf = PDF::loadView('default.Warehouse.reports.date-and-yard-shed-wise-warehouse-entry-report',
	            [
	                'todaysWareHouseEntry' => $WareHouseEntry,
	                'todayWithTime' => $todayWithTime,
	                'date' => $date,
	            ])/*->setPaper('a4', 'landscape');*/
	        ->setPaper([0, 0, 1000, 1000]);
	        //return $pdf->download('user.pdf');
	        return $pdf->stream($date . '-date-and-yard-shed-wise-warehouse-entry-report.pdf');	
        } else {
        	return view('default.warehouse.not-found',['requestedDate' => $date]);
        }
    }

    public function warehouseLyingReport(Request $r){
    	$port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        if($r->item){
            $data=DB::select('SELECT *, (t.weight-(IFNULL(t.local_truck_delivered,0))) current_weight FROM( 
                SELECT (SELECT SUM(td.labor_load) FROM truck_deliverys AS td JOIN manifests AS mm ON td.manf_id=mm.id WHERE mm.id=m.id GROUP BY td.manf_id LIMIT 1 ) local_truck_delivered,
                ic.id,ic.Description ,SUM(id.item_quantity) AS weight FROM item_codes ic 
                JOIN item_details AS id ON ic.id=id.item_Code_id 
                JOIN manifests AS m ON id.manf_id=m.id 
                where ic.id=? AND m.port_id=? 
                GROUP BY id.item_Code_id) t',[$r->item, $port_id]);
        }
        else{
            $data=DB::select('SELECT *,(t.weight-(IFNULL(t.local_truck_delivered,0))) current_weight FROM( 
                        SELECT (SELECT SUM(td.labor_load) FROM truck_deliverys AS td JOIN manifests AS mm ON td.manf_id=mm.id WHERE mm.id=m.id GROUP BY td.manf_id LIMIT 1 ) local_truck_delivered,
                        ic.id,ic.Description ,SUM(id.item_quantity) AS weight FROM item_codes ic 
                        JOIN item_details AS id ON ic.id=id.item_Code_id
                        JOIN manifests AS m ON id.manf_id=m.id  
                        GROUP BY id.item_Code_id AND m.port_id=? 
                        ORDER BY ic.Description ASC) t'[$port_id]);
        }

        if(count($data)) {
	        $pdf = PDF::loadView('default.warehouse.reports.warehouse-lying-report', [
	            'data' => $data,
	            'date' => $currentTime

	        ])->setPaper([0, 0, 700, 800]);
	        return $pdf->stream('warehouse-lying-report.pdf');	
        } else {
        	return view('default.warehouse.not-found',['requestedDate' => 'this item']);
        }
    }

    public function monthWiseLocalTransportReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $arrayShedYardId = array();
        $shedYardName = array();
        foreach (Auth::user()->shedYards as $k => $v) {

            $arrayShedYardId[] = $v->id;
            $shedYardName[] = $v->shed_yard;
        }
        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('port_id', $port_id)->get();

        $array_name = array();
        foreach ($yard_details_array  as $k => $v){
            $array_name[]  = $v->id;
        }

        $shedYard = implode(',', $array_name);
        $year = date("Y", strtotime($r->month_wise_delivery_report));
        $month = date("m", strtotime($r->month_wise_delivery_report));

        $todayWithTime = date('Y-m-d h:i:s a');

        $data = DB::SELECT("SELECT truck_deliverys.truck_no,truck_deliverys.truck_type_id,cnf_details.address,
(
SELECT SUM(IFNULL(CAST(s.unload_labor_weight AS CHAR)+0,0) + IFNULL(CAST(s.unload_equip_weight AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_weights,
 (
SELECT SUM(IFNULL(CAST(s.unload_labor_package AS CHAR)+0,0) + IFNULL(CAST(s.unload_equipment_package AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_packages,
 
 (
SELECT SUM(IFNULL(td.equipment_package,0) + IFNULL(td.labor_package,0)) FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_package,
 (
SELECT SUM(IFNULL( CAST(td.labor_load AS CHAR)+0,0) + IFNULL(CAST(td.equip_load AS CHAR)+0,0))  FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_weight,
manifests.cnf_name, manifests.be_no,DATE_FORMAT(DATE(manifests.be_date), '%d-%m-%Y') AS be_date, dr.gate_pass_no,DATE_FORMAT(DATE(dr.approximate_delivery_date), '%d-%m-%Y') AS delivery_date, dr.gate_pass_at,
(IFNULL(dr.transport_truck,0) + IFNULL(dr.transport_van,0)) AS transport_number,
(IFNULL	(item_details.item_quantity,0)) AS quantity,
 manifests.manifest,DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date, DATE_FORMAT(DATE(truck_deliverys.delivery_dt), '%d-%m-%Y') AS truck_delivery_date,
 GROUP_CONCAT(DISTINCT
CASE WHEN truck_deliverys.truck_type_id IS NOT NULL THEN
 (SELECT CONCAT(vehicle_type_bd.type_name,'-') FROM vehicle_type_bd 
WHERE vehicle_type_bd.id = truck_deliverys.truck_type_id) ELSE '' END,truck_deliverys.truck_no SEPARATOR ', ') AS truck_type_no, 
 chassis_details.chassis_type,chassis_details.chassis_no,chassis_deliverys.driver_name,chassis_deliverys.driver_card,
chassis_deliverys.delivery_dt AS self_delivery_date,
(SELECT CONCAT(chassis_details.chassis_no,'-',chassis_details.chassis_type) FROM chassis_details 
WHERE chassis_details.id = chassis_deliverys.chassis_details_id) AS self_type_no  
FROM manifests
JOIN      (
              SELECT    MAX(id) max_id, manifest_id 
              FROM      delivery_requisitions 
              GROUP BY  manifest_id
          ) c_max ON (c_max.manifest_id = manifests.id)
JOIN      delivery_requisitions AS dr ON (dr.id = c_max.max_id)
JOIN truck_deliverys ON truck_deliverys.manf_id = manifests.id
JOIN cnf_details ON cnf_details.id =  manifests.cnf_id
JOIN item_deliverys ON item_deliverys.truck_delivery_id = truck_deliverys.id
JOIN item_details ON item_details.id = item_deliverys.item_detail_id
JOIN item_codes ON item_codes.id = item_details.item_Code_id
LEFT JOIN chassis_deliverys ON chassis_deliverys.manifest_id = manifests.id
LEFT JOIN chassis_details ON chassis_details.id = chassis_deliverys.chassis_details_id
JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id 
JOIN shed_yard_weights AS sw ON sw.truck_id = truck_entry_regs.id 
WHERE  sw.unload_yard_shed IN ($shedYard) AND
manifests.transshipment_flag=0 AND manifests.port_id=? AND 
 MONTH(dr.approximate_delivery_date) = ? AND YEAR(dr.approximate_delivery_date)=? 
/*MONTH(truck_deliverys.delivery_dt) = ? AND YEAR(truck_deliverys.delivery_dt)=? 
OR MONTH(chassis_deliverys.delivery_dt) = ? AND YEAR(chassis_deliverys.delivery_dt) =?*/
 GROUP BY manifests.id ORDER BY manifests.id DESC", [$port_id,$month,$year]);

        if(count($data)) {
            $pdf = PDF::loadView('default.warehouse.delivery.reports.month-wise-local-transport-delivery-report', [
                'data' => $data,
                'date' => $todayWithTime,
                'monthYear' => $r->month_wise_delivery_report,
                'shedYardName' => $shedYardName

            ])/*->setPaper([0, 0, 1000, 1000], 'landscape');*/ ->setPaper([0, 0, 1000, 900]);

            return $pdf->stream($r->month_wise_delivery_report . '-month-wise-wareHouse-local-transport-delivery-report.pdf');
        } else {
            return view('default.warehouse.not-found',['requestedDate' => $r->month_wise_delivery_report]);
        }
    }

    public function dateAndManifestWiseLocalTransportReport(Request $r) {
        $date = $r->date;
        $port_id = Session::get('PORT_ID');
        $arrayShedYardId = array();
        $shedYardName = array();
        foreach (Auth::user()->shedYards as $k => $v) {

            $arrayShedYardId[] = $v->id;

        }

        if (Auth::user()->role_id==11){//for maintencace role
            $shed_yards = ShedYard::where('port_id',$port_id)->get();
            foreach($shed_yards as $k => $v) {
                $shedYardName[] = $v->shed_yard;
            }
        }

        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('port_id', $port_id)->get();

        $array_name = array();
        foreach ($yard_details_array  as $k => $v){
            $array_name[]  = $v->id;
        }

        $shedYard = implode(',', $array_name);

        if(Auth::user()->role->id == 8) {
            $data = DB::SELECT("SELECT truck_deliverys.truck_no,truck_deliverys.truck_type_id,cnf_details.address,
(
SELECT SUM(IFNULL(CAST(s.unload_labor_weight AS CHAR)+0,0) + IFNULL(CAST(s.unload_equip_weight AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_weights,
 (
SELECT SUM(IFNULL(CAST(s.unload_labor_package AS CHAR)+0,0) + IFNULL(CAST(s.unload_equipment_package AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_packages,
 
 (
SELECT SUM(IFNULL(td.equipment_package,0) + IFNULL(td.labor_package,0)) FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_package,
 (
SELECT SUM(IFNULL( CAST(td.labor_load AS CHAR)+0,0) + IFNULL(CAST(td.equip_load AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_weight,
manifests.cnf_name, manifests.be_no,DATE_FORMAT(DATE(manifests.be_date), '%d-%m-%Y') AS be_date, dr.gate_pass_no,DATE_FORMAT(DATE(dr.approximate_delivery_date), '%d-%m-%Y') AS delivery_date, dr.gate_pass_at,
(IFNULL(dr.transport_truck,0) + IFNULL(dr.transport_van,0)) AS transport_number,
(IFNULL	(item_details.item_quantity,0)) AS quantity,
 manifests.manifest,DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date, DATE_FORMAT(DATE(truck_deliverys.delivery_dt), '%d-%m-%Y') AS truck_delivery_date,
 GROUP_CONCAT(DISTINCT
CASE WHEN truck_deliverys.truck_type_id IS NOT NULL THEN
 (SELECT CONCAT(vehicle_type_bd.type_name,'-') FROM vehicle_type_bd 
WHERE vehicle_type_bd.id = truck_deliverys.truck_type_id) ELSE '' END,truck_deliverys.truck_no SEPARATOR ', ') AS truck_type_no, 
 chassis_details.chassis_type,chassis_details.chassis_no,chassis_deliverys.driver_name,chassis_deliverys.driver_card,
chassis_deliverys.delivery_dt AS self_delivery_date,
(SELECT CONCAT(chassis_details.chassis_no,'-',chassis_details.chassis_type) FROM chassis_details 
WHERE chassis_details.id = chassis_deliverys.chassis_details_id) AS self_type_no  
FROM manifests
JOIN      (
              SELECT    MAX(id) max_id, manifest_id 
              FROM      delivery_requisitions 
              GROUP BY  manifest_id
          ) c_max ON (c_max.manifest_id = manifests.id)
JOIN      delivery_requisitions AS dr ON (dr.id = c_max.max_id)
JOIN truck_deliverys ON truck_deliverys.manf_id = manifests.id
JOIN cnf_details ON cnf_details.id =  manifests.cnf_id
JOIN item_deliverys ON item_deliverys.truck_delivery_id = truck_deliverys.id
JOIN item_details ON item_details.id = item_deliverys.item_detail_id
JOIN item_codes ON item_codes.id = item_details.item_Code_id
LEFT JOIN chassis_deliverys ON chassis_deliverys.manifest_id = manifests.id
LEFT JOIN chassis_details ON chassis_details.id = chassis_deliverys.chassis_details_id
JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id 
JOIN shed_yard_weights AS sw ON sw.truck_id = truck_entry_regs.id 
WHERE  sw.unload_yard_shed IN ($shedYard) AND  manifests.transshipment_flag=0 AND manifests.port_id=? AND 
DATE(dr.approximate_delivery_date) = ?
/*DATE(truck_deliverys.delivery_dt) = ? OR DATE(chassis_deliverys.delivery_dt) = ?*/
 GROUP BY manifests.id ORDER BY manifests.id DESC", [$port_id, $date]);
        } else {
            $data = DB::SELECT("SELECT truck_deliverys.truck_no,truck_deliverys.truck_type_id,cnf_details.address,
(
SELECT SUM(IFNULL(CAST(s.unload_labor_weight AS CHAR)+0,0) + IFNULL(CAST(s.unload_equip_weight AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_weights,
 (
SELECT SUM(IFNULL(CAST(s.unload_labor_package AS CHAR)+0,0) + IFNULL(CAST(s.unload_equipment_package AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_entry_regs AS tt ON tt.manf_id = mm.id 
JOIN shed_yard_weights AS s ON s.truck_id = tt.id 
 WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS receive_packages,
 
 (
SELECT SUM(IFNULL(td.equipment_package,0) + IFNULL(td.labor_package,0)) FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_package,
 (
SELECT SUM(IFNULL( CAST(td.labor_load AS CHAR)+0,0) + IFNULL(CAST(td.equip_load AS CHAR)+0,0)) FROM manifests AS mm
JOIN truck_deliverys AS td ON td.manf_id = mm.id
WHERE mm.id =  manifests.id
 LIMIT 1
 ) AS labor_equ_weight,
manifests.cnf_name, manifests.be_no,DATE_FORMAT(DATE(manifests.be_date), '%d-%m-%Y') AS be_date, dr.gate_pass_no,DATE_FORMAT(DATE(dr.approximate_delivery_date), '%d-%m-%Y') AS delivery_date, dr.gate_pass_at,
(IFNULL(dr.transport_truck,0) + IFNULL(dr.transport_van,0)) AS transport_number,
(IFNULL	(item_details.item_quantity,0)) AS quantity,
 manifests.manifest,DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date, DATE_FORMAT(DATE(truck_deliverys.delivery_dt), '%d-%m-%Y') AS truck_delivery_date,
 GROUP_CONCAT(DISTINCT
CASE WHEN truck_deliverys.truck_type_id IS NOT NULL THEN
 (SELECT CONCAT(vehicle_type_bd.type_name,'-') FROM vehicle_type_bd 
WHERE vehicle_type_bd.id = truck_deliverys.truck_type_id) ELSE '' END,truck_deliverys.truck_no SEPARATOR ', ') AS truck_type_no, 
 chassis_details.chassis_type,chassis_details.chassis_no,chassis_deliverys.driver_name,chassis_deliverys.driver_card,
chassis_deliverys.delivery_dt AS self_delivery_date,
(SELECT CONCAT(chassis_details.chassis_no,'-',chassis_details.chassis_type) FROM chassis_details 
WHERE chassis_details.id = chassis_deliverys.chassis_details_id) AS self_type_no  
FROM manifests
JOIN      (
              SELECT    MAX(id) max_id, manifest_id 
              FROM      delivery_requisitions 
              GROUP BY  manifest_id
          ) c_max ON (c_max.manifest_id = manifests.id)
JOIN      delivery_requisitions AS dr ON (dr.id = c_max.max_id)
JOIN truck_deliverys ON truck_deliverys.manf_id = manifests.id
JOIN cnf_details ON cnf_details.id =  manifests.cnf_id
JOIN item_deliverys ON item_deliverys.truck_delivery_id = truck_deliverys.id
JOIN item_details ON item_details.id = item_deliverys.item_detail_id
JOIN item_codes ON item_codes.id = item_details.item_Code_id
LEFT JOIN chassis_deliverys ON chassis_deliverys.manifest_id = manifests.id
LEFT JOIN chassis_details ON chassis_details.id = chassis_deliverys.chassis_details_id
JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id 
JOIN shed_yard_weights AS sw ON sw.truck_id = truck_entry_regs.id 
WHERE manifests.transshipment_flag=0 AND manifests.port_id=? AND 
DATE(dr.approximate_delivery_date) = ?
/*DATE(truck_deliverys.delivery_dt) = ? OR DATE(chassis_deliverys.delivery_dt) = ?*/
 GROUP BY manifests.id ORDER BY manifests.id DESC", [$port_id, $date]);
        }
        //return $data;
        if(count($data)) {
            $pdf = PDF::loadView('default.warehouse.delivery.reports.date-and-manifest-wise-local-transport-delivery-report', [
                'data' => $data,
                'date' => $date,
                'shedYardName' => $shedYardName

            ])/*->setPaper([0, 0, 1000, 1000], 'landscape');*/ ->setPaper([0, 0, 1000, 900]);

            return $pdf->stream($date . '-date-and-manifest-wise-wareHouse-local-transport-delivery-report.pdf');
        } else {
            return view('default.warehouse.not-found',['requestedDate' => $date]);
        }
    }

    public function monthAndManifestWiseEntryReport(Request $r) {
        $year = date("Y", strtotime($r->manifest_wise_month_entry));
        $month = date("m", strtotime($r->manifest_wise_month_entry));
        $flagValue = isset($r->vehile_type_flage_pdf) ? $r->vehile_type_flage_pdf : 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $port_id = Session::get('PORT_ID');
        $arrayShedYardId = array();
        $shedYardName = array();
        foreach (Auth::user()->shedYards as $k => $v) {
            $arrayShedYardId[] = $v->id;
            $shedYardName[] = $v->shed_yard;
        }

        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('port_id', $port_id)->get();

        $array_name = array();
        foreach ($yard_details_array  as $k => $v){
            $array_name[]  = $v->id;
        }
        $shedYard = implode(',', $array_name);

        $manifestandMonthWiseWareHouseEntry = DB::SELECT("SELECT  GROUP_CONCAT(DISTINCT yard_details.yard_shed_name SEPARATOR ', ') AS yard_shed_name,
                                            GROUP_CONCAT(DISTINCT DATE(sw.unload_receive_datetime) SEPARATOR ', ') AS unload_receive_datetime,
                                            GROUP_CONCAT(CONCAT(t.truck_type,'-', t.truck_no) SEPARATOR ', ') AS truck_type_no , 
                                            SUM(IFNULL(t.receive_weight,0)) AS receive_weight,
                                            CASE WHEN t.receive_package REGEXP '^[[:digit:]]+$' THEN 
                                               SUM(IFNULL(t.receive_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT t.receive_package SEPARATOR ', ')
                                            END AS receive_package,
                                            GROUP_CONCAT(DISTINCT sw.unload_comment SEPARATOR ', ') AS unload_comment,
                                            SUM(IFNULL(sw.unload_labor_weight, 0)) AS unload_labor_weight,
                                            SUM(IFNULL(sw.unload_equip_weight, 0)) AS unload_equip_weight, 
                                            GROUP_CONCAT(DISTINCT sw.unload_equip_name SEPARATOR ', ') AS unload_equip_name,
                                            CASE WHEN sw.unload_labor_package REGEXP '^[[:digit:]]+$' THEN 
                                             SUM(IFNULL(sw.unload_labor_package, 0)) 
                                            ELSE
                                              GROUP_CONCAT(DISTINCT sw.unload_labor_package SEPARATOR ', ')
                                            END AS unload_labor_package,
                                            CASE WHEN sw.unload_equipment_package REGEXP '^[[:digit:]]+$' THEN
                                              SUM(IFNULL(sw.unload_equipment_package,0)) 
                                            ELSE 
                                              GROUP_CONCAT(DISTINCT sw.unload_equipment_package SEPARATOR ', ')
                                            END AS unload_equipment_package,
                                            m.manifest, DATE_FORMAT(DATE(m.manifest_date), '%d-%m-%Y') AS manifest_date, users.name,
                                            GROUP_CONCAT(DISTINCT cd.cargo_name SEPARATOR ', ') AS goods_name, m.marks_no, m.gweight, 
                                            CASE WHEN m.package_no REGEXP '^[[:digit:]]+$' THEN
                                              NULLIF(REPLACE(FORMAT(m.package_no,0), ',', ''), 0)
                                            ELSE
                                              m.package_no
                                            END AS manifest_receive_package,
                                            NULLIF(REPLACE(FORMAT(m.cnf_value,0), ',', ''), 0) AS cnf_value,
                                            GROUP_CONCAT(DISTINCT DATE_FORMAT(DATE(t.truckentry_datetime), '%d-%m-%Y') SEPARATOR ', ') AS truckentry_datetime,
                                            CONCAT(v.name, CASE WHEN v.ADD1 IS NOT NULL THEN CONCAT(', ', v.ADD1) ELSE '' END) AS importer_name_and_address 
                                            FROM manifests AS m 
                                            JOIN truck_entry_regs AS t ON t.manf_id = m.id 
                                            JOIN shed_yard_weights AS sw ON sw.truck_id = t.id 
                                            LEFT JOIN users ON users.id = sw.created_by
                                            LEFT JOIN yard_details ON sw.unload_yard_shed = yard_details.id
                                            JOIN cargo_details AS cd ON FIND_IN_SET(cd.id, m.goods_id) > 0
                                            JOIN vatregs as v on v.id = m.vatreg_id
                                            WHERE sw.unload_yard_shed IN ($shedYard) AND MONTH(sw.unload_receive_datetime)=?
                                            AND YEAR(sw.unload_receive_datetime)=? AND t.port_id =? AND sw.port_id=?
                                            AND  t.vehicle_type_flag=?
                                            GROUP BY m.id ORDER BY m.id DESC",[$month, $year, $port_id, $port_id, $flagValue]);
        //return count($manifestandMonthWiseWareHouseEntry);
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
        if(count($manifestandMonthWiseWareHouseEntry)) {
            $pdf = PDF::loadView('default.warehouse.receive.reports.month-and-manifest-wise-warehouse-entry-report',
                [
                    'data' => $manifestandMonthWiseWareHouseEntry,
                    'todayWithTime' => $todayWithTime,
                    'month_year' => $r->manifest_wise_month_entry,
                    'typeOfReports' => $typeOfReports,
                    'shedYardName' => $shedYardName
                ])
                ->setPaper([0, 0, 1000, 900]);
            return $pdf->stream(date("m Y", strtotime($r->manifest_wise_month_entry)) .'-month-and-manifest-wise-warehouse-entry-report.pdf');
        } else {
            return view('default.warehouse.not-found',['requestedDate' => date("m Y", strtotime($r->manifest_wise_month_entry))]);
        }
    }
}
