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
use App\Http\Controllers\GlobalFunctionController;

class AssessmentBaseController extends Controller
{

    private $globalFunctionController;


    public function __construct(GlobalFunctionController $globalFunctionController) {
        $this->globalFunctionController = $globalFunctionController;

    }

    public function getLastPartialStatus($manifest) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $max_partial_status =  DB::select('SELECT MAX(dr.partial_status) AS max_partial_number
                                    FROM delivery_requisitions dr
                                    INNER JOIN manifests m ON dr.manifest_id=m.id
                                    WHERE m.manifest=? AND m.port_id=? AND dr.port_id=?', [$manifest, $port_id, $port_id]);
        return $max_partial_status;
    }

    public function manifestDetailsForAssessment($manifest, $partial_status) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $checkmanifestmiddlestring = DB::select('SELECT (SUBSTRING_INDEX(SUBSTRING_INDEX(manifest ,\'/\',-2),\'/\',1)) AS truckNo FROM manifests WHERE manifest=? AND port_id=?', [$manifest, $port_id]);

        if (!$checkmanifestmiddlestring) {
            return false;
        }

        if (is_numeric($checkmanifestmiddlestring[0]->truckNo)) {//if manifest no = 900/3/2017
            $checkAssDone = DB::select('SELECT  
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR \', \') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = m.id) AS posted_yard_shed, m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
dr.local_transport_type, dr.shifting_flag, dr.perishable_flag, m.self_flag, m.gweight,
(SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer, (SELECT vatregs.vat FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer_vat_flag, dr.shifting_flag AS load_shifting,
(SELECT DISTINCT shed_yard_weights.unload_shifting_flag 
FROM shed_yard_weights
INNER JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
WHERE truck_entry_regs.manf_id = m.id AND shed_yard_weights.unload_shifting_flag = 1 )AS unload_shifting,
(SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
WHERE id.manf_id=m.id ) AS description_of_goods,
(SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
(SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id AND asses.partial_status =?) AS previous_ass_value,
(CASE WHEN m.gweight >(SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
  ELSE (SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
END) AS chargeable_weight, dr.truck_to_truck_flag,
(SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight,
(SELECT CASE WHEN COUNT(syw.id) > 0 THEN 1 ELSE 0 END AS receive_flag
FROM manifests AS ma
JOIN truck_entry_regs AS ter ON ter.manf_id = ma.id
JOIN shed_yard_weights AS syw ON syw.truck_id = ter.id
WHERE ma.id = m.id) AS receive_flag
FROM manifests m
JOIN delivery_requisitions dr ON dr.manifest_id = m.id
WHERE m.manifest=? AND m.port_id=? AND dr.port_id =? AND dr.partial_status=?',[$partial_status, $manifest, $port_id, $port_id, $partial_status]);

        } else {//if manifest no = 900/A/2017 or 900/A-E/2017
            $checkAssDone = DB::select('SELECT m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR \', \') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = m.id) AS posted_yard_shed,m.gweight, m.gweight AS chargeable_weight,dr.local_transport_type,dr.shifting_flag AS load_shifting, m.self_flag,
(SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer,  (SELECT vatregs.vat FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer_vat_flag, dr.shifting_flag, dr.perishable_flag,
(SELECT DISTINCT shed_yard_weights.unload_shifting_flag 
FROM shed_yard_weights
INNER JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
WHERE truck_entry_regs.manf_id = m.id AND shed_yard_weights.unload_shifting_flag = 1 ) AS unload_shifting,
(SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
WHERE id.manf_id=m.id ) AS description_of_goods, dr.truck_to_truck_flag,
(SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
(SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id AND asses.partial_status=?) AS previous_ass_value,
(SELECT SUM(IFNULL(truck_entry_regs.tweight_wbridge,0)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight,
(SELECT CASE WHEN COUNT(syw.id) > 0 THEN 1 ELSE 0 END AS receive_flag
FROM manifests AS ma
JOIN truck_entry_regs AS ter ON ter.manf_id = ma.id
JOIN shed_yard_weights AS syw ON syw.truck_id = ter.id
WHERE ma.id = m.id) AS receive_flag
FROM manifests m 
JOIN delivery_requisitions dr ON dr.manifest_id = m.id 
WHERE m.manifest=? AND m.port_id=? AND dr.port_id =? AND dr.partial_status =?',[$partial_status, $manifest, $port_id, $port_id, $partial_status]);

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

    public function getEntranceCarpenterWeighmentCharge($manifest, $partial_status) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
    	$data = DB::select('SELECT m.manifest, dr.transport_truck, dr.transport_van, dr.carpenter_packages, 
                            dr.carpenter_repair_packages, dr.local_weighment AS local_truck_weighment,
                            (SELECT COUNT(truck_entry_regs.id) FROM truck_entry_regs WHERE truck_entry_regs.manf_id=m.id 
                            AND truck_entry_regs.vehicle_type_flag < 11) AS foreign_truck
                            FROM manifests m
                            JOIN delivery_requisitions dr ON dr.manifest_id = m.id 
                            WHERE m.manifest=? AND m.port_id=? AND dr.port_id=? AND dr.partial_status=?',[$manifest, $port_id, $port_id, $partial_status]);
    	return $data;
    }

    public function getDocumentDetails($mani_no, $partial_status) { //Call From Noraml And Trans
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

    //This function wrote for getting warehouse details when saving normal/Trans assessment - [11-07-2018]
    // and also for old data [18-09-2018]
    public function getWarehouseDetails($manifest, $partial_status) { //Call From Noraml And Trans
        $manifest_id = null;
        $receive_date = null;
        $delivery_date = null;
        $self_flag = null;
        $warehouse_rent_day = null;

        $port_id = Session::get('PORT_ID');

        $year = date('Y');
        $assessment_created_year = $this->globalFunctionController->getAassessmentCreatedYear($manifest);
        if(isset($assessment_created_year)) {
            $year = $assessment_created_year;
        }

        $manifest_data = DB::select('SELECT t.* FROM (SELECT m.id AS manifest_id, m.self_flag, 
                                dr.approximate_delivery_date AS delivery_date,
                                (CASE WHEN MAX(dr.partial_status) = 1
                                THEN (SELECT syws.unload_receive_datetime FROM shed_yard_weights AS syws 
                                JOIN truck_entry_regs AS trs ON trs.id=syws.truck_id 
                                JOIN manifests AS ms ON ms.id=trs.manf_id
                                WHERE ms.manifest=? AND ms.port_id=?
                                ORDER BY syws.unload_receive_datetime ASC LIMIT 1) 
                                ELSE (SELECT DATE_ADD(dre.approximate_delivery_date, INTERVAL 1 DAY) 
                                FROM delivery_requisitions dre 
                                JOIN manifests ma ON ma.id = dre.manifest_id 
                                WHERE ma.manifest=? AND dre.partial_status=?-1 AND ma.port_id=?)
                                END ) AS receive_date
                                FROM manifests m 
                                JOIN delivery_requisitions dr ON dr.manifest_id = m.id
                                WHERE m.manifest=? AND m.port_id=? AND dr.port_id=? AND dr.partial_status=?) t  WHERE t.manifest_id IS NOT NULL',[$manifest, $port_id, $manifest, $partial_status, $port_id, $manifest, $port_id, $port_id, $partial_status]);
        if(count($manifest_data) > 0) {
            $manifest_id = $manifest_data[0]->manifest_id;
            $receive_date = $manifest_data[0]->receive_date;
            $delivery_date = $manifest_data[0]->delivery_date;
            $self_flag = $manifest_data[0]->self_flag;
            $warehouse_rent_day = $this->globalFunctionController->number_of_working_days($receive_date, $delivery_date);
            $partialList = 0; //intialtization
            for($i = 1 ; $i < $partial_status ; $i++) {
              $partialList .= ','.$i;
            }
            

            $check_the_port_has_tariff = DB::select('SELECT  COUNT(tg.id) AS found_tariff FROM tariff_goods AS tg 
                                                      JOIN tariff_schedules_and_charges AS tsac ON tsac.tariff_good_id = tg.id
                                                      WHERE tg.port_id=?', [$port_id]);
            $check_the_port_has_year_tariff = DB::select('SELECT  COUNT(tg.id) AS found_tariff_year FROM tariff_goods AS tg 
                                                      JOIN tariff_schedules_and_charges AS tsac ON tsac.tariff_good_id = tg.id
                                                      WHERE tg.year=?', [$year]);
            if($self_flag == 1 || $self_flag == 2) {
                $query_start = "SELECT
                                  t.*,
                                  (
                                    CASE t.partial_status WHEN 1 THEN t.iq ELSE (
                                      t.iq - (
                                        SELECT
                                          COUNT(ch.id)
                                        FROM
                                          chassis_deliverys ch
                                        WHERE
                                          ch.manifest_id = t.manifest_id
                                          AND ch.partial_status IN(".$partialList.")
                                      )
                                    ) END
                                  ) AS item_quantity
                                FROM
                                  (";
            } else {
                $query_start = "SELECT
                                  t.*,
                                  (
                                    CASE t.item_type WHEN 4 THEN (
                                      CASE t.partial_status WHEN 1 THEN CEIL(t.iq / 1000) ELSE (
                                        CEIL (
                                          (
                                            t.iq - (
                                              SELECT
                                                IFNULL(SUM(IFNULL(ide.loadable_weight, 0)), 0)
                                              FROM
                                                item_deliverys ide
                                              WHERE
                                                ide.item_detail_id = t.item_id
                                                AND ide.partial_status IN (".$partialList.") AND ide.manifest_id = t.manifest_id
                                            )
                                          ) / 1000
                                        )
                                      ) END
                                    ) ELSE (
                                      CASE t.partial_status WHEN 1 THEN CEIL(t.iq) ELSE (
                                        t.iq - (
                                          SELECT
                                            IFNULL(SUM(IFNULL(ide.loadable_weight, 0)), 0)
                                          FROM
                                            item_deliverys ide
                                          WHERE
                                            ide.item_detail_id = t.item_id
                                            AND ide.partial_status IN (".$partialList.") AND ide.manifest_id = t.manifest_id
                                        )
                                      ) END
                                    ) END
                                  ) AS item_quantity
                                FROM
                                  (";
            }
            $shed_selection_query = " SELECT tsac.shed_charge,";
            $yard_selection_query = " SELECT tsac.yard_charge,";
            $base_query = " id.manf_id AS manifest_id, tg.id AS goods_id, tg.particulars AS goods, tgf.flag AS free_time_flag, tgf.duration AS free_time_duration,
                            tsac.slab, tsac.from, tsac.to,
                            id.id AS item_id, ic.Description, id.item_type, id.dangerous, id.item_quantity AS iq, id.tariff_good_id,
                            ".$partial_status." AS partial_status
                            FROM item_details AS id
                            JOIN item_codes AS ic ON ic.id = id.item_Code_id
                            JOIN tariff_goods AS tg ON tg.id = id.tariff_good_id
                            JOIN tariff_goods_freetimes AS tgf ON tgf.tariff_good_id = tg.id
                            JOIN tariff_schedules_and_charges AS tsac ON tsac.tariff_good_id = tg.id  WHERE";
            $base_query .= " id.manf_id=".$manifest_id;
            $base_query .= " AND id.port_id=".$port_id;
            if($check_the_port_has_tariff[0]->found_tariff > 0 && $check_the_port_has_year_tariff[0]->found_tariff_year > 0) {
                $base_query .=" AND tg.port_id=".$port_id;
                $base_query .=" AND tgf.port_id=".$port_id;
                $base_query .=" AND tsac.port_id=".$port_id;
                $base_query .= " AND tg.year=".$year;
            } else if($check_the_port_has_tariff[0]->found_tariff == 0 && $check_the_port_has_year_tariff[0]->found_tariff_year > 0){
                $base_query .=" AND tg.port_id=-1";
                $base_query .=" AND tgf.port_id=-1";
                $base_query .=" AND tsac.port_id=-1";
                $base_query .= " AND tg.year=".$year;
            } else {
                return json_encode(array());
            }
            $query_end = " ) AS t";
            $shed_query = $query_start.$shed_selection_query.$base_query." AND id.yard_shed=1".$query_end;
            $yard_query = $query_start.$yard_selection_query.$base_query." AND id.yard_shed=0".$query_end;

            $item_wise_shed_details_charge = DB::select($shed_query);
            $item_wise_yard_details_charge = DB::select($yard_query);

            $free_items = array();
            $items_rent_day = array();
            //$warehouse_rent_day=30;
            $shed_item_details = array();
            $yard_item_details = array();
            $temp = $warehouse_rent_day;
            $item_flag = -1;
            $start_day = new DateTime($receive_date);
            //return $warehouse_rent_day;
            //return $item_wise_shed_details_charge;
            //$values = array();
            foreach ($item_wise_shed_details_charge as $k => $shed_item) {
                if($item_flag != $shed_item->item_id) {
                    $temp = $warehouse_rent_day;
                    if($shed_item->free_time_flag && $partial_status == 1 && $warehouse_rent_day >= $shed_item->free_time_duration) {
                        $temp = $warehouse_rent_day - $shed_item->free_time_duration;
                        $start_day = new DateTime($receive_date);
                        $start_day->modify('+'.$shed_item->free_time_duration.' day');
                        $free_day_end = (new DateTime($receive_date))
                            ->modify('+'.($shed_item->free_time_duration-1).' day')->format('d-m-Y');
                        $free_items[] = $this->getWarehouseFreeGoodArray($shed_item, $free_day_end);
                        if($temp > 0) {
                            $items_rent_day[] = $this->getWarehouseRentDayForItemArray($shed_item, $temp,
                                $start_day->format('d-m-Y'));
                        }
                    } else if($shed_item->free_time_flag && $partial_status == 1 && $warehouse_rent_day < $shed_item->free_time_duration  ) {
                        $free_duration = $temp - $shed_item->free_time_duration;
                        $temp = $free_duration;
                        $free_day_end = (new DateTime($receive_date))
                            ->modify('+'.($shed_item->free_time_duration-1).' day')->format('d-m-Y');
                        $free_items[] = $this->getWarehouseFreeGoodArray($shed_item, $free_day_end);
                    } else{
                        if($temp > 0) {
                            $start_day = new DateTime($receive_date);
                            $items_rent_day[] = $this->getWarehouseRentDayForItemArray($shed_item, $temp,
                                $start_day->format('d-m-Y'));
                        }
                    }
                    $item_flag = $shed_item->item_id;
                }
//                $values[] = [
//                    'item_flag' => $item_flag,
//                    'temp' => $temp,
//                    'free_duration' => $free_duration
//                    ];
                if($temp>0){
                    $slab_duration = ($shed_item->to - $shed_item->from) + 1;
                    $previous_temp = $temp;
                    $temp=($temp - $slab_duration);
                    $item_flag = $shed_item->item_id;

                    if($temp > 0) {
                        $final_slab_duration =  $slab_duration;
                    } else {
                        $final_slab_duration = $warehouse_rent_day;
                    }

                    if($shed_item->to == -1 || $temp <= 0) {
                        $final_slab_duration = $previous_temp;
                    }
                    $temp_start_day = $start_day;
                    $temp_start_day_format = new DateTime($start_day->format('d-m-Y'));
                    $end_day = $temp_start_day_format->modify('+'.($final_slab_duration).' day');
                    $temp_end_day = (new DateTime($end_day->format('d-m-Y')))->modify('-1 day');
                    $start_day = $temp_start_day_format;
                    $shed_item_details[] = $this->getWareHouseItemArray($shed_item, $final_slab_duration,
                        $temp_start_day->format('d-m-Y'), $temp_end_day->format('d-m-Y'));

                    if($shed_item->to == -1) {
                        $temp = $warehouse_rent_day;
                    }
                } else {
                    if($item_flag == $shed_item->item_id) {
                        continue;
                    } else {
                        $temp = $warehouse_rent_day;
                        $slab_duration = ($shed_item->to - $shed_item->from) + 1;
                        $temp = ($temp - $slab_duration);
                        $item_flag = $shed_item->item_id;

                        if($temp > 0) {
                            $final_slab_duration =  $slab_duration;
                        } else {
                            $final_slab_duration = $warehouse_rent_day;
                        }
                        $temp_start_day = $start_day;
                        $temp_start_day_format = new DateTime($start_day->format('d-m-Y'));
                        $end_day = $temp_start_day_format->modify('+'.($final_slab_duration).' day');
                        $temp_end_day = (new DateTime($end_day->format('d-m-Y')))->modify('-1 day');
                        $start_day = $temp_start_day_format;
                        $shed_item_details[] = $this->getWareHouseItemArray($shed_item, $final_slab_duration,
                            $temp_start_day->format('d-m-Y'), $temp_end_day->format('d-m-Y'));
                    }
                }
            }
            //return $values;
            //////////////////----------------------YARD ITEM----------------------------------------
            $temp = $warehouse_rent_day;
            //return $warehouse_rent_day;
            $item_flag = -1;
            $start_day = new DateTime($receive_date);
            foreach ($item_wise_yard_details_charge as $k => $yard_item) {
                if($item_flag != $yard_item->item_id) {
                    $temp = $warehouse_rent_day;
                    if($yard_item->free_time_flag && $partial_status == 1 && $warehouse_rent_day >= $yard_item->free_time_duration) {
                        $temp = $warehouse_rent_day - $yard_item->free_time_duration;
                        $start_day = new DateTime($receive_date);
                        $start_day->modify('+'.$yard_item->free_time_duration.' day');
                        $free_day_end = (new DateTime($receive_date))
                            ->modify('+'.($yard_item->free_time_duration-1).' day')->format('d-m-Y');
                        $free_items[] = $this->getWarehouseFreeGoodArray($yard_item, $free_day_end);
                        if($temp > 0) {
                            $items_rent_day[] = $this->getWarehouseRentDayForItemArray($yard_item, $temp,
                                $start_day->format('d-m-Y'));
                        }
                    }  else if($yard_item->free_time_flag && $partial_status == 1 && $warehouse_rent_day < $yard_item->free_time_duration  ) {
                        $free_duration = $temp - $yard_item->free_time_duration;
                        $temp = $free_duration;
                        $free_day_end = (new DateTime($receive_date))
                            ->modify('+'.($yard_item->free_time_duration-1).' day')->format('d-m-Y');
                        $free_items[] = $this->getWarehouseFreeGoodArray($yard_item, $free_day_end);
                    } else {
                        if($temp > 0) {
                            $start_day = new DateTime($receive_date);
                            $items_rent_day[] = $this->getWarehouseRentDayForItemArray($yard_item, $temp,
                                $start_day->format('d-m-Y'));
                        }
                    }
                    $item_flag = $yard_item->item_id;
                }

                if($temp>0){
                    $slab_duration = ($yard_item->to - $yard_item->from) + 1;
                    $previous_temp = $temp;
                    $temp=($temp - $slab_duration);
                    $item_flag = $yard_item->item_id;

                    if($temp > 0) {
                        $final_slab_duration =  $slab_duration;
                    } else {
                        $final_slab_duration = $warehouse_rent_day;
                    }

                    if($yard_item->to == -1 || $temp <= 0) {
                        $final_slab_duration = $previous_temp;
                    }
                    $temp_start_day = $start_day;
                    $temp_start_day_format = new DateTime($start_day->format('d-m-Y'));
                    $end_day = $temp_start_day_format->modify('+'.($final_slab_duration).' day');
                    $temp_end_day = (new DateTime($end_day->format('d-m-Y')))->modify('-1 day');
                    $start_day = $temp_start_day_format;
                    $yard_item_details[] = $this->getWareHouseItemArray($yard_item, $final_slab_duration,
                        $temp_start_day->format('d-m-Y'), $temp_end_day->format('d-m-Y'));

                    if($yard_item->to == -1) {
                        $temp = $warehouse_rent_day;
                    }
                } else {
                    if($item_flag == $yard_item->item_id) {
                        continue;
                    } else {
                        $temp = $warehouse_rent_day;
                        $slab_duration = ($yard_item->to - $yard_item->from) + 1;
                        $temp = ($temp - $slab_duration);
                        $item_flag = $yard_item->item_id;

                        if($temp > 0) {
                            $final_slab_duration =  $slab_duration;
                        } else {
                            $final_slab_duration = $warehouse_rent_day;
                        }
                        $temp_start_day = $start_day;
                        $temp_start_day_format = new DateTime($start_day->format('d-m-Y'));
                        $end_day = $temp_start_day_format->modify('+'.($final_slab_duration).' day');
                        $temp_end_day = (new DateTime($end_day->format('d-m-Y')))->modify('-1 day');
                        $start_day = $temp_start_day_format;
                        $yard_item_details[] = $this->getWareHouseItemArray($yard_item, $final_slab_duration,
                            $temp_start_day->format('d-m-Y'), $temp_end_day->format('d-m-Y'));
                    }
                }
            }

            $warehouse_details = array(
                'manifest_id' => $manifest_id,
                'receive_date' => $receive_date,
                'delivery_date' => $delivery_date,
                'free_items' => $free_items,
                'warehouse_rent_for_items' => $items_rent_day,
                'item_wise_shed_details' => $shed_item_details,
                'item_wise_yard_details' => $yard_item_details
            );
            return json_encode($warehouse_details);
        } else {
            return json_encode(array());
        }
    }

    public function getWareHouseItemArray($item, $slab_duration, $start_day, $end_day) {
        return array (
            'manifest_id' => $item->manifest_id,
            'tariff_goods_id' => $item->goods_id,
           'item_id' => $item->item_id,
            'free_time_flag' => $item->free_time_flag,
            'free_time_duration' =>  $item->free_time_duration,
            'tariff_good_name' => $item->goods,
            'item_name' => $item->Description,
            'item_type' => $item->item_type,
            'item_quantity' => $item->item_quantity,
            'dangerous' => $item->dangerous,
            'slab' => $item->slab,
            'from' => $item->from,
            'to' => $item->to,
            'start_day' => $start_day,
            'end_day' => $end_day,
            'slab_duration_day' =>  $slab_duration,
            'charge' => isset($item->shed_charge) ? $item->shed_charge : (isset($item->yard_charge) ? $item->yard_charge : null),
            'total_charge' => ($slab_duration * $item->item_quantity *(
                isset($item->shed_charge) ? $item->shed_charge : (isset($item->yard_charge) ? $item->yard_charge : 0))*
                ($item->dangerous == 1.0 ? 2.0 : 1))
        );
    }

    public function getWarehouseFreeGoodArray($good, $free_day_end) {
        return array (
            'tariff_good_id' => $good->goods_id,
            'item_id' => $good->item_id,
            'tariff_good_name' => $good->goods,
            'item_name' => $good->Description,
            'free_day' => $good->free_time_duration,
            'free_day_end' => $free_day_end
        );
    }

    public function getWarehouseRentDayForItemArray($item, $rent_day, $rent_start_day) {
        return array (
            'tariff_good_id' => $item->goods_id,
            'item_id' => $item->item_id,
            'tariff_good_name' => $item->goods,
            'item_name' => $item->Description,
            'rent_day' => $rent_day,
            'rent_start_day' => $rent_start_day
        );
    }

    public function getItemDetails($items) {

    }




    //===================PDF view ====================================
    //this is for new assessment warehouse change . previous is keept for transhipmen . it is for normal assessment

    public function getWarehouseCharge( $mani_no)
    {
        $year = date('Y');
        $assessmentCreatedYear =$this->globalFunctionController->getAassessmentCreatedYear($mani_no);

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

        $w = DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,posted_yard_shed,package_no,m_id,yard_shed
             FROM(SELECT m.goods_id,m.package_no,m.id AS m_id,m.posted_yard_shed AS posted_yard_shed,m.approximate_delivery_date AS deliver_date,
            (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.manf_id DESC LIMIT 1)AS truckentry_datetime,
            (SELECT syws.unload_receive_datetime FROM shed_yard_weights AS syws 
                JOIN truck_entry_regs AS trs ON trs.id=syws.truck_id 
                JOIN manifests AS ms ON ms.id=trs.manf_id
                WHERE ms.manifest= m.manifest  ORDER BY syws.unload_receive_datetime ASC LIMIT 1)AS receive_date,
            (SELECT yard_shed FROM yard_details WHERE m.posted_yard_shed=yard_details.id) AS yard_shed,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1)AS ReceiveWeight
            FROM manifests m  WHERE m.manifest=?            
            )t', [$mani_no]);


        $receive_date = $w[0]->receive_date;
        $deliver_date = $w[0]->deliver_date;
        $goods_id = $w[0]->goods_id;
        $posted_yard_shed = $w[0]->posted_yard_shed;
        $yard_shed = $w[0]->yard_shed;
        $package_no = $w[0]->package_no;
        $item_wise_charge = null;
        $mani_id = $w[0]->m_id;

        $freeEndDay = $this->globalFunctionController->GetFreedayEndForWarehouseRent($mani_id, $receive_date);//return $receive_date + 3 days excluding holidays
        $ChargeStartDay = $this->globalFunctionController->ChargeStartDay($freeEndDay);
        $wareHouseRentDay = $this->globalFunctionController->number_of_working_days($receive_date, $ChargeStartDay, $deliver_date);


        if ($freeEndDay > $deliver_date) {
            $wareHouseRentDay = 0;
        }

        if ($wareHouseRentDay <= 0) {
            $freeEndDay = $deliver_date;
        }

        $check_the_port_has_tariff = DB::select('SELECT  COUNT(ts.id) AS found_tariff FROM tariff_schedule AS ts WHERE ts.port_id=? AND ts.tariff_year=?', [Session::get('PORT_ID'), $year]);

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
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id=?', [$mani_id, $year, Session::get('PORT_ID'), Session::get('PORT_ID')]);

            $item_wise_shed_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.Shed_first_slab AS first_slab,ts.Shed_second_slab AS second_slab,ts.Shed_third_slab AS third_slab,ic.Description,
            (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
             WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=1 AND id.port_id=? AND ts.port_id=?', [$mani_id, $year, Session::get('PORT_ID'), Session::get('PORT_ID')]);


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
            WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=0 AND id.port_id=? AND ts.port_id IS NULL', [$mani_id, $year, Session::get('PORT_ID')]);

            $item_wise_shed_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.Shed_first_slab AS first_slab,ts.Shed_second_slab AS second_slab,ts.Shed_third_slab AS third_slab,ic.Description,
            (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
             WHERE id.manf_id=? AND ts.tariff_year=? AND id.yard_shed=1 AND id.port_id=? AND ts.port_id IS NULL', [$mani_id, $year, Session::get('PORT_ID')]);
        }


        $mani_id = DB::table('manifests AS a')
            ->where('a.manifest', [$mani_no])
            ->select('a.id')
            ->get();
        $warehouseRent = DB::table('assesment_details AS a')
            ->where('a.manif_id', $mani_id[0]->id)
            ->where('a.sub_head_id', 2)
            ->select('a.tcharge')
            ->get();




//return  'first slab:'.$firstSlabCharge. ' Second slab:'.$secondSlabCharge.' Third slab:'.$thirdSlabCharge;
        $TotalSlabCharge = DB::select('SELECT ad.tcharge AS total_warehouse_charge FROM assesment_details AS ad WHERE ad.manif_id=? AND ad.sub_head_id=2 AND ad.partial_status=0', [$mani_id[0]->id]);

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
            'item_wise_shed_charge' => $item_wise_shed_charge,
            'item_wise_yard_charge' => $item_wise_yard_charge,
            'receive_date' => $receive_date,
            'deliver_date' => $deliver_date,
            'goods_id' => $goods_id,
            'posted_yard_shed' => $posted_yard_shed,
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

    public function getForeignTruckHaltageDetails($manifest) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT t.*, 
                        DATEDIFF(t.receive_datetime,t.truckentry_datetime) AS haltage_days
                        FROM
                        (
                        SELECT ter.id, ter.holtage_charge_flag,ter.tweight_wbridge,ter.receive_weight,
                        CONCAT(truck_type,\'-\',truck_no ) AS truck_no,truckentry_datetime,
                        (SELECT shed_yard_weights.unload_receive_datetime 
                        FROM shed_yard_weights
                        WHERE shed_yard_weights.truck_id = ter.id ORDER BY shed_yard_weights.unload_receive_datetime ASC LIMIT 1) AS receive_datetime
                        FROM manifests m 
                        INNER JOIN truck_entry_regs ter ON ter.manf_id=m.id 
                        WHERE m.manifest=? AND m.port_id=? AND ter.port_id=?) AS t',[$manifest, $port_id, $port_id]);
        return $data;
    }

    public function getLocalTruckHaltageDetails($manifest, $partial_status) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT dr.local_haltage 
                                        FROM delivery_requisitions AS dr
                                        JOIN manifests AS m ON m.id = dr.manifest_id
                                        WHERE m.manifest = ? AND m.port_id=? AND dr.port_id=? AND dr.partial_status=?',[$manifest, $port_id, $port_id, $partial_status]);
        return $data;
    }

    public function getForeignNightDetails($manifest) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $data = DB::select("SELECT COUNT(t.shift='Night') AS total_foreign_truck_night
                        FROM (
                        SELECT DISTINCT DATE(shed_yard_weights.unload_receive_datetime) AS receive_date, 
                        (CASE 
                        WHEN DATE_FORMAT(shed_yard_weights.unload_receive_datetime, '%H:%i')>='18:00' OR 
                        DATE_FORMAT(shed_yard_weights.unload_receive_datetime, '%H:%i')<='09:00' 
                        THEN 'Night' ELSE 'Day' END) AS shift
                        FROM shed_yard_weights
                        INNER JOIN truck_entry_regs ON truck_entry_regs.id = shed_yard_weights.truck_id
                        INNER JOIN manifests ON manifests.id = truck_entry_regs.manf_id
                        WHERE manifests.manifest=? AND manifests.port_id=? AND truck_entry_regs.port_id=? AND shed_yard_weights.port_id=?) AS t WHERE t.shift!='Day'",[$manifest, $port_id, $port_id, $port_id]);
        return $data;
    }

    public function getForeignHolidayDetails($manifest) { //Call From Noraml And Trans
        $port_id = Session::get('PORT_ID');
        $data = DB::select("SELECT * FROM (
                        SELECT truck_type, truck_no, DATE(unload_receive_datetime) AS receive_date,
                        IF(DAYNAME(unload_receive_datetime) IN ('Saturday', 'Friday'), DATE(unload_receive_datetime), 0) AS weekend_holiday,
                        IF(DATE(unload_receive_datetime) IN (SELECT DATE(hday) FROM holidays), DATE(unload_receive_datetime), 0) AS holiday
                        FROM manifests m 
                        JOIN truck_entry_regs t ON t.manf_id = m.id
                        JOIN shed_yard_weights ON shed_yard_weights.truck_id=t.id
                        WHERE m.manifest=? AND m.port_id=? AND t.port_id=? AND shed_yard_weights.port_id=?) AS tt WHERE weekend_holiday != 0 OR holiday != 0", [$manifest, $port_id, $port_id, $port_id]);
        return $data;
    }

    public function getLocalHolidayDetails($manifest, $partial_status) {
        $port_id = Session::get('PORT_ID');
        $data = DB::select("SELECT * FROM (
                                SELECT DATE(approximate_delivery_date) AS delivery_date,
                                IF(DAYNAME(approximate_delivery_date) IN ('Saturday', 'Friday'), DATE(approximate_delivery_date), 0) AS weekend_holiday,
                                IF(DATE(approximate_delivery_date) IN (SELECT DATE(hday) FROM holidays), DATE(approximate_delivery_date), 0) AS holiday
                                FROM (
                                SELECT dr.approximate_delivery_date
                                FROM manifests m
                                JOIN delivery_requisitions dr ON dr.manifest_id = m.id 
                                WHERE m.manifest=? AND m.port_id=? AND dr.port_id=? AND dr.partial_status=?
                                ) AS t
                                ) AS tm WHERE weekend_holiday !=0 OR holiday != 0", [$manifest, $port_id, $port_id, $partial_status]);
        return $data;
    }
}
