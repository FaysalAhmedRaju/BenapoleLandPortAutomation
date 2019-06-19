<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;
use Response;
use Session;

class TransshipmentAssessmentBaseController extends Controller
{

    public function __construct(GlobalFunctionController $globalFunctionController) {
        $this->globalFunctionController = $globalFunctionController;

    }

    public function ManifestDetailsForAssessmentAss($r) { //Not Used From [23/9/2018]
        $port_id = Session::get('PORT_ID');
        $checkmanifestmiddlestring = DB::select('SELECT  (SUBSTRING_INDEX(SUBSTRING_INDEX(manifest ,\'/\',-2),\'/\',1)) AS truckNo FROM manifests WHERE manifest=? AND port_id=?', [$r, $port_id]);

        //  return is_numeric($checkmanifestmiddlestring[0]->truckNo);
        if (!$checkmanifestmiddlestring) {
            return false;
        }

        if (is_numeric($checkmanifestmiddlestring[0]->truckNo)) {//if manifest no = 900/3/2017
            $checkAssDone = DB::select('SELECT  
                    (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR \', \') FROM yard_details AS yd 
                    JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
                    JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                    JOIN manifests AS ma ON ma.id = ter.manf_id
                    WHERE ma.id = m.id) AS posted_yard_shed, 
           m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
           m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
           m.local_transport_type,m.shifting_flag,m.perishable_flag, m.self_flag,
            (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer,m.shifting_flag AS load_shifting,
              (SELECT DISTINCT shed_yard_weights.unload_shifting_flag 
                FROM shed_yard_weights
                INNER JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
                WHERE truck_entry_regs.manf_id = m.id AND shed_yard_weights.unload_shifting_flag = 1 )AS unload_shifting,
            (SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
                        WHERE id.manf_id=m.id ) AS description_of_goods,
            (SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
            (SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id) AS previous_ass_value,
                    (CASE WHEN m.gweight >(SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                          ELSE (SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                       END) AS chargeable_weight,
            (SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight
                    FROM manifests m 
                    WHERE m.manifest=? AND m.port_id=?', [$r, $port_id]);
        } else {//if manifest no = 900/A/2017 or 900/A-E/2017
            $checkAssDone = DB::select('SELECT m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
           m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
                    (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR \', \') FROM yard_details AS yd 
                    JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
                    JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                    JOIN manifests AS ma ON ma.id = ter.manf_id
                    WHERE ma.id = m.id) AS posted_yard_shed, m.gweight AS chargeable_weight,m.local_transport_type,m.shifting_flag AS load_shifting, m.self_flag,
            (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer,m.shifting_flag,m.perishable_flag,
            (SELECT DISTINCT shed_yard_weights.unload_shifting_flag 
                FROM shed_yard_weights
                INNER JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
                WHERE truck_entry_regs.manf_id = m.id AND shed_yard_weights.unload_shifting_flag = 1 ) AS unload_shifting,
            (SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
                        WHERE id.manf_id=m.id ) AS description_of_goods,
            (SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
            (SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id) AS previous_ass_value,
            (SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight
                    FROM manifests m 
                    WHERE m.manifest=? AND m.port_id=?', [$r, $port_id]);

        }
        return $checkAssDone;
    }

    public function getHandlingCharge($manifest, $partial_status) {
        $port_id = Session::get('PORT_ID');
        $handling = DB::select('SELECT manifest,CEIL((IFNULL(dr.approximate_labour_load,0)/1000)) AS labor_load,
CEIL((IFNULL(dr.approximate_equipment_load,0)/1000)) AS equip_load,
(CASE WHEN dr.truck_to_truck_flag = 0 THEN
(SELECT (CEIL(SUM(IFNULL(shed_yard_weights.unload_labor_weight,0))/1000))
FROM shed_yard_weights
JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
WHERE truck_entry_regs.manf_id=m.id ) ELSE CEIL((IFNULL(dr.approximate_labour_load,0)/1000)) END) AS labor_unload,
(CASE WHEN dr.truck_to_truck_flag = 0 THEN
(SELECT (CEIL(SUM(IFNULL(shed_yard_weights.unload_equip_weight,0))/1000))
FROM shed_yard_weights
JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
WHERE truck_entry_regs.manf_id=m.id ) ELSE CEIL((IFNULL(dr.approximate_equipment_load,0)/1000)) END)AS equip_unload
FROM manifests m
JOIN delivery_requisitions dr ON dr.manifest_id = m.id
WHERE m.manifest=? AND m.port_id=? AND dr.port_id=? AND dr.partial_status = ?', [$manifest, $port_id, $port_id, $partial_status]);
        return $handling;
    }

    public function getEntranceCarpenterWeighmentCharge($manifest) {  //Not Used From [23/9/2018]
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT m.manifest, m.transport_truck,m.transport_van, m.carpenter_packages, m.carpenter_repair_packages, m.bd_weighment AS local_truck_weighment, (SELECT COUNT(truck_entry_regs.id) FROM truck_entry_regs WHERE truck_entry_regs.manf_id=m.id AND truck_entry_regs.vehicle_type_flag < 11) AS foreign_truck
            FROM manifests m  WHERE m.manifest=? AND m.port_id=?',[$manifest, $port_id]);
        return $data;
    }

    public function getDocumentDetailsForAssessment($mani_no, $partial_status) { //Not Used From [23/9/2018] 
        $port_id = Session::get('PORT_ID');
        $mani_id = DB::table('manifests')
            ->where('manifest', $mani_no)
            ->where('port_id', $port_id)
            ->select('id')
            ->get();
        $docunemtCharge = DB::select('SELECT dc.number_of_document FROM assessment_documents dc 
                            WHERE dc.manifest_id=? AND dc.partial_status=? AND dc.port_id=? 
                            ORDER BY dc.id DESC LIMIT 1', [$mani_id[0]->id, $partial_status, $port_id]);
        if($docunemtCharge) {
            return $docunemtCharge;
        } else {
            return 'notFound';
        }

    }

    public function GetWarehouseForAssesment($mani_no) {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($mani_no);

        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }
//get slab charge variable globaly
        $firstSlabCharge = 0;
        $secondSlabCharge = 0;
        $thirdSlabCharge = 0;

        $firstSlabDay = 0;
        $secondSlabDay = 0;
        $thirdSlabDay = 0;

        $w = DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,package_no,m_id
             FROM(SELECT m.goods_id,m.package_no,m.id AS m_id,m.approximate_delivery_date AS deliver_date,
            (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.manf_id DESC LIMIT 1)AS truckentry_datetime,
            (SELECT syws.unload_receive_datetime FROM shed_yard_weights AS syws 
                JOIN truck_entry_regs AS trs ON trs.id=syws.truck_id 
                JOIN manifests AS ms ON ms.id=trs.manf_id
                WHERE ms.manifest= m.manifest  ORDER BY syws.unload_receive_datetime ASC LIMIT 1)AS receive_date,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1)AS ReceiveWeight
            FROM manifests m  WHERE m.manifest=? AND m.port_id=?            
            )t', [$mani_no, $port_id]);


        $receive_date = $w[0]->receive_date;
        $deliver_date = $w[0]->deliver_date;
        $goods_id = $w[0]->goods_id;
        $package_no = $w[0]->package_no;
        $mani_id = $w[0]->m_id;

        $freeEndDay = $this->globalFunctionController->GetFreedayEndForWarehouseRent($mani_id, $receive_date);//return $receive_date + 3 days excluding holidays
        $ChargeStartDay =$this->globalFunctionController->ChargeStartDay($freeEndDay);
        $wareHouseRentDay = $this->globalFunctionController->number_of_working_days($receive_date, $ChargeStartDay, $deliver_date);


        if ($freeEndDay > $deliver_date) {
            $wareHouseRentDay = 0;
        }

        if ($wareHouseRentDay <= 0) {
            $freeEndDay = $deliver_date;
        }

        $check_the_port_has_tariff = DB::select('SELECT  COUNT(ts.id) AS found_tariff FROM tariff_schedule AS ts WHERE ts.port_id=? AND ts.tariff_year=?', [$port_id, $year]);

        //  dd($check_the_port_has_tariff[0]->found_tariff);

        if ($check_the_port_has_tariff[0]->found_tariff > 0) {
            // $tariff_port_id=Session::get('PORT_ID');
            $item_wise_yard_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.yard_first_slab AS first_slab,ts.yard_second_slab AS second_slab,ts.yard_third_slab AS third_slab,ic.Description,
             (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id=?', [$mani_id, $year, Session::get('PORT_ID'), $port_id]);
        } else {
            $item_wise_yard_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.yard_first_slab AS first_slab,ts.yard_second_slab AS second_slab,ts.yard_third_slab AS third_slab,ic.Description,
             (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id IS NULL', [$mani_id, $year, $port_id]);
        }


        $mani_id = DB::table('manifests AS a')
            ->where('a.manifest', [$mani_no])
            ->where('a.port_id', $port_id)
            ->select('a.id')
            ->get();

        $warehouseRent = DB::table('assesment_details AS a')
            ->where('a.manif_id', $mani_id[0]->id)
            ->where('a.sub_head_id', 2)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', 0)
            ->select('a.tcharge')
            ->get();

        $TotalSlabCharge = DB::select('SELECT ad.tcharge AS total_warehouse_charge FROM assesment_details AS ad WHERE ad.manif_id=? AND ad.sub_head_id=2 AND ad.partial_status=0 AND ad.port_id=?', [$mani_id[0]->id, $port_id]);

        //dd($mani_id[0]->id);

        if ($TotalSlabCharge) {
            $total_warehouse_charge = $TotalSlabCharge[0]->total_warehouse_charge;

        } else {
            $total_warehouse_charge = 0;
        }


        //get slab charge variable globaly
        $firstSlabCharge = 0;
        $secondSlabCharge = 0;
        $thirdSlabCharge = 0;

        $firstSlabDay = 0;
        $secondSlabDay = 0;
        $thirdSlabDay = 0;

        if ($wareHouseRentDay >= 1 && $wareHouseRentDay <= 21) {//1 slab will be calculated------------------1
            $firstSlabDay = $wareHouseRentDay;


        } else if ($wareHouseRentDay >= 22 && $wareHouseRentDay <= 50) {//2 slab will be calculated------------------2
            $firstSlabDay = 21;
            $secondSlabDay = ($wareHouseRentDay - 21);

        } else if ($wareHouseRentDay >= 51) {//3 slab will be calculated---------------------------------3
            $firstSlabDay = 21;
            $secondSlabDay = 29;
            $thirdSlabDay = ($wareHouseRentDay - 21 - 29);


        } else {

            $firstSlabDay = 0;
            $secondSlabDay = 0;
            $thirdSlabDay = 0;

        }


        return array(

            "WareHouseRentDay" => $wareHouseRentDay,
            'FreeEndDate' => $freeEndDay,
            'ChargeStartDay' => $ChargeStartDay,
            'item_wise_yard_charge' => $item_wise_yard_charge,
            'receive_date' => $receive_date,
            'deliver_date' => $deliver_date,
            'goods_id' => $goods_id,
            'package_no' => $package_no,
            'FirstSlabDay' => $firstSlabDay,
            'SecondSlabDay' => $secondSlabDay,
            'thirdSlabDay' => $thirdSlabDay,

            "FirstSlabCharge" => $firstSlabCharge,
            "SecondSlabCharge" => $secondSlabCharge,
            'ThirdSlabCharge' => $thirdSlabCharge,
            'TotalSlabCharge' => $total_warehouse_charge
        );

    }
}
