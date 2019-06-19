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

class  GlobalFunctionController extends Controller
{
    //Global functions that can be used in any module


    public function getAassessmentCreatedYear($m_no){
        $port_id = Session::get('PORT_ID');
        $checkAssessmentCreated=DB::select('SELECT charge_year FROM assessments ass
                        JOIN manifests AS m ON m.id=ass.manifest_id
                        WHERE m.manifest=? AND m.port_id=? AND ass.port_id=? 
                        ORDER BY ass.id DESC LIMIT 1',[$m_no, $port_id, $port_id]);

        $assessmentCreatedYear=null;

        if ($checkAssessmentCreated){
            $assessmentCreatedYear= $checkAssessmentCreated[0]->charge_year;
        }
       return $assessmentCreatedYear;
    }

public function getManifestId($mani_no){
    return DB::select('SELECT m.id FROM manifests AS m WHERE m.manifest=?',[$mani_no]);
}



//this function return total warehouse charge days
    public function number_of_working_days($from, $to) {
//        $holiday = DB::table('holidays')
//            ->select('holidays.hday')
//            ->get();
//
//        $holi = json_encode($holiday, False);
//
//        $implode = array();
//        $multiple = json_decode($holi, true);
//        foreach ($multiple as $single) {
//            $implode[] = implode(', ', $single);
//
//        }
//
//        $comma_separated = implode(",", $implode);
//
//
//        $workingDays = [0, 1, 2, 3, 4]; # date format = w (1 = Sunnday, ...)
//        $holidayDays = explode(',', $comma_separated);  # variable and fixed holidays

        // $holidayDays = ['*-12-25', '*-05-03', '2017-05-19', '2017-05-27']; # variable and fixed


//        $from = new DateTime($from);
//        $to = new DateTime($to);
        //$receive_date = new DateTime($receive_date);

//        $to->modify('+1 day');
//        $interval = new DateInterval('P1D');
//
//        $periods = new DatePeriod($from, $interval, $to);
//
//        $days = 0;
//        foreach ($periods as $period) {
//
//            //  if (!in_array($period->format('w'), $workingDays)) continue;
//            //if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
//            // if (in_array($period->format('*-m-d'), $holidayDays)) continue;
//            $days++;
//        }
        //  $to->modify('-1 day');
       // if ($receive_date->format('w') >= 5) $days += 1;//add 1 day if unload on holiday
        // if ($to->format('w') >= 5 ) $days+=1;//add 1 day if load on holiday



        $from = new DateTime($from);
        $to = new DateTime($to);
        $interval = $from->diff($to);
        $days = $interval->format("%a") + ($from->format('d-m-Y') == $to->format('d-m-Y') ? 1 :
                                ($interval->format("%a") >= 0 ? 2 : 0));
        return $days;
    }
    public function number_of_working_days_partial($from, $to)
    {


        $workingDays = [0, 1, 2, 3, 4]; # date format = w (1 = Sunnday, ...)


        $from = new DateTime($from);
        $to = new DateTime($to);

        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            $days++;
        }

        return $days;
    }


    public function SlabCharge($goods, $shedYard, $slab)//
    {
        $charge = null;

        if ($shedYard >= 9 && $shedYard <= 24)//yard 9-24
        {
            if ($slab == 1) $charge = DB::select('SELECT t.yard_first_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);
            if ($slab == 2) $charge = DB::select('SELECT t.yard_second_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);
            if ($slab == 3) $charge = DB::select('SELECT t.yard_third_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);

        }

        if ($shedYard >= 25 && $shedYard <= 30)//Shed 25-29
        {
            if ($slab == 1) $charge = DB::select('SELECT t.Shed_first_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);
            if ($slab == 2) $charge = DB::select('SELECT t.Shed_second_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);
            if ($slab == 3) $charge = DB::select('SELECT t.Shed_third_slab AS charge FROM tariff_schedule t WHERE t.goods_id=?', [$goods]);

        }

        return $charge[0]->charge;

    }


    public function GetFreedayEndForWarehouseRent($m_id, $from1)
    {
        // $allForeignTruck = DB::table('truck_entry_regs as tr')
        //     ->whereManfId($m_id)
        //     ->select('tr.receive_datetime')
        //     ->orderBy('id', 'ASC')
        //     ->get();


        $holiday = DB::table('holidays')
            ->select('holidays.hday')
            ->get();

        $holi = json_encode($holiday, False);
        $implode = array();
        $multiple = json_decode($holi, true);
        foreach ($multiple as $single) {
            $implode[] = implode(', ', $single);
        }

        $comma_separated = implode(",", $implode);

        $workingDays = [0, 1, 2, 3, 4];
        $holidayDays = explode(',', $comma_separated);
        $from = new DateTime($from1);
        $unloadHoliday = new DateTime($from1);
        $loopControl = 0;

        $from->modify('+2 day');

        //   return (iterator_count($periods));echo json_encode($period);


        /*for ($i = 1; $i <= 20; $i++) {//check the day is in holiday
            if (!in_array($from->format('w'), $workingDays) || in_array($from->format('Y-m-d'), $holidayDays)) {

                if ($unloadHoliday->format('w') >=5 ) {
                    $unloadHoliday->modify('+2 day');
                    $from->modify('+1 day');
                    $loopControl++;
                } else {
                    $from->modify('+1 day');
                }
            } else {// if the day is not holiday or workingday
                $loopControl++;
                if ($loopControl < 3) {//will break loop if value 3
                    $from->modify('+1 day');
                } else {
                    break;
                }
            }
        }*/

        //below logic is for check if all truck unload same day or different day. if different day then free day will be decreased

      /*  $lastEntryReceiveTime = $allForeignTruck[0]->receive_datetime;//the result is DESC. o meanean last entried truck
        if (count($allForeignTruck) > 1) {//check foreign truck more than 1
            if (date($from1) != date($lastEntryReceiveTime)) {
                $from->modify('-1 day');
            }
        }
        $loop = 0;
        if (count($allForeignTruck) > 2) {//check foreign truck more than 2. this for getting the 3rd truck received in different day too
            $from_date = date('Y-m-d', strtotime($from1));
            foreach ($allForeignTruck as $key => $value) {
                if ($loop != 1) {
                    if (date('Y-m-d', strtotime($value->receive_datetime)) != date('Y-m-d', strtotime($lastEntryReceiveTime))) {
                        if (date('Y-m-d', strtotime($value->receive_datetime)) != $from_date) {
                            $from->modify('-1 day');
                            $loop++;
                        }
                    }
                }else{
                    break;
                }
            }
        }*/
        return $from->format('Y-m-d');

    }


    public function ChargeStartDay($from1)
    {


        /* $holiday = DB::table('holidays')
             ->select('holidays.hday')
             ->get();

         $holi=json_encode($holiday,False);

         $implode = array();
         $multiple = json_decode($holi, true);
         foreach($multiple as $single)
         {
             $implode[] = implode(', ', $single);
         }

         $comma_separated = implode(",", $implode);


         $workingDays = [0,1, 2, 3, 4]; # date format = w (1 = Sunnday, ...)
         $holidayDays =explode(',', $comma_separated);  # variable and fixed holidays
  */
        $from2 = new DateTime($from1);
        $from = $from2->modify('+1 day');
        /*$i=1;
        $loopControl=1;

        for ($i; $i <=20; $i++) {


            if ($loopControl!=2){

                if (!in_array($from->format('w'), $workingDays)  || in_array($from->format('Y-m-d'), $holidayDays))
                {
                    $from->modify('+1 day');
                    continue;
                }
                else// if the day is not holiday or workingday
                {
                    $loopControl++;
                }

            }
            else{//$loopControl == 2
                break;
            }
        }*/
        return $from->format('Y-m-d');

    }

    public function ManifestDetailsForAssessmentAssPartial($r,$status)
    {
        $checkmanifestmiddlestring = DB::select('SELECT  (SUBSTRING_INDEX(SUBSTRING_INDEX(manifest ,\'/\',-2),\'/\',1)) AS truckNo FROM manifests WHERE manifest=?', [$r]);

        //  return is_numeric($checkmanifestmiddlestring[0]->truckNo);
        if (!$checkmanifestmiddlestring) {
            return false;
        }

        if (is_numeric($checkmanifestmiddlestring[0]->truckNo)) {//if manifest no = 900/3/2017


            $checkAssDone = DB::select('SELECT * FROM
            ( SELECT m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
           m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
           yd.yard_shed_name AS posted_yard_shed,yd.yard_shed,
            (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer,
            (SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
                        WHERE id.manf_id=m.id ) AS description_of_goods,
            (SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
            (SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id AND asses.partial_status=?) AS previous_ass_value,
                    (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                          ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                       END) AS chargeable_weight,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight
                    FROM manifests m  
                    LEFT  JOIN yard_details AS yd ON m.posted_yard_shed=yd.id 
                    WHERE m.manifest=? ) AS final', [$status,$r]);

        } else {//if manifest no = 900/A/2017 or 900/A-E/2017
            $checkAssDone = DB::select('SELECT * FROM
            ( SELECT m.manifest AS manifest_no,m.id AS manifest_id, m.manifest_date,m.be_no AS bill_entry_no,m.be_date AS bill_entry_date,m.cnf_name,m.package_no,m.package_type,
           m.exporter_name_addr AS exporter,m.custom_release_order_no AS custom_realise_order_No,m.custom_release_order_date AS custom_realise_order_date,m.transshipment_flag,
           yd.yard_shed_name AS posted_yard_shed,yd.yard_shed, m.gweight AS chargeable_weight,
            (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=m.vatreg_id) AS importer,
            (SELECT GROUP_CONCAT(ic.Description SEPARATOR \', \') AS description FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id 
                        WHERE id.manf_id=m.id ) AS description_of_goods,
            (SELECT COUNT(ic.Code) FROM item_details AS id JOIN item_codes AS ic ON id.item_Code_id=ic.id WHERE id.manf_id=m.id) AS totalItems,
            (SELECT SUM(asses.tcharge) FROM  assesment_details AS asses WHERE asses.manif_id=m.id AND asses.partial_status=?) AS previous_ass_value,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)AS total_weighbridge_weight
                    FROM manifests m  
                    LEFT  JOIN yard_details AS yd ON m.posted_yard_shed=yd.id 
                    WHERE m.manifest=? ) AS final', [$status,$r]);

        }

        return $checkAssDone;
        //return 1;
    }

    public function GetWarehouseForAssesment($mani_no)
    {
        $year = date('Y');
        $assessmentCreatedYear = $this->getAassessmentCreatedYear($mani_no);

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
            FROM manifests m  WHERE m.manifest=?            
            )t', [$mani_no]);


        $receive_date = $w[0]->receive_date;
        $deliver_date = $w[0]->deliver_date;
        $goods_id = $w[0]->goods_id;
        $package_no = $w[0]->package_no;
        $mani_id = $w[0]->m_id;

        $freeEndDay = $this->GetFreedayEndForWarehouseRent($mani_id, $receive_date);//return $receive_date + 3 days excluding holidays
        $ChargeStartDay = $this->ChargeStartDay($freeEndDay);
        $wareHouseRentDay = $this->number_of_working_days($receive_date, $ChargeStartDay, $deliver_date);


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


    public function GetHandlingAndSomeOtherDuesForAssesment($r)
    {
        $year = date('Y');

        $assessmentCreatedYear=$this->getAassessmentCreatedYear($r);

        if ($assessmentCreatedYear){
            $year=$assessmentCreatedYear;
        }

        $handling = DB::select('SELECT manifest,CEIL((m.approximate_labour_load/1000)) AS labor_load,CEIL((m.approximate_equipment_load/1000)) AS equip_load,m.shifting_flag AS loading_shifting,
    m.approximate_delivery_date,m.old_approximate_delivery_date,m.no_del_truck,m.transport_truck,m.transport_van,m.approximate_delivery_type,carpenter_packages,carpenter_repair_packages, 
    m.bd_weighment AS local_truck_weighment,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=2 AND ch.charges_year=? LIMIT 1) AS entrance_fee,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=32 AND ch.charges_year=? LIMIT 1) AS offloading_manual_charges,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=36 AND ch.charges_year=? LIMIT 1) AS offloading_equipment_charges,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=12 AND ch.charges_year=? LIMIT 1) AS weightment_measurement_charges,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=18 AND ch.charges_year=? LIMIT 1) AS document_charges,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=8  AND ch.charges_year=? LIMIT 1) AS carpenter_charges_opening,
              (SELECT ch.rate_of_charges FROM handling_and_othercharges AS ch WHERE ch.charge_id=10 AND ch.charges_year=? LIMIT 1) AS carpenter_charges_repairing,
              (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                          ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) END) AS chargeable_weight,
              (SELECT (CEIL(SUM(truck_entry_regs.labor_unload)/1000)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id )AS labor_unload,
              (SELECT (CEIL(SUM(truck_entry_regs.equip_unload)/1000)) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id )AS equip_unload,
              (SELECT DISTINCT shifting_flag FROM  truck_entry_regs AS ter WHERE ter.manf_id=m.id AND ter.shifting_flag=1 )AS unload_shifting,
              (SELECT COUNT(truck_deliverys.id) FROM  truck_deliverys WHERE truck_deliverys.manf_id=m.id )AS local_truck,
              (SELECT COUNT(truck_entry_regs.id) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id )AS foreign_truck
              FROM manifests m  WHERE m.manifest=?', [$year, $year, $year, $year, $year, $year, $year, $r]);

        return $handling;

    }

    public function CheckItemIsParishable($mani_no)
    {
        $mani_id = DB::table('manifests')
            ->where('manifest', $mani_no)
            ->select('id')
            ->get();
        $parishable = DB::select('SELECT id.id, id.dangerous,id.item_quantity,id.item_type,ic.Description,ic.perishable_flag 
                        FROM item_details AS id 
                        LEFT JOIN item_codes AS ic ON id.item_Code_id=ic.id
                        WHERE id.manf_id =?', [$mani_id[0]->id]);

        if ($parishable) {
            return $parishable;
        } else {
            return null;
        }
    }

    public function GetDocumentChargeForAssessment($mani_no,$partial_status)
    {
        $year=date('Y');
        $assessmentCreatedYear=$this->getAassessmentCreatedYear($mani_no);

        if ($assessmentCreatedYear){
            $year=$assessmentCreatedYear;
        }
        $mani_id = DB::table('manifests')
            ->where('manifest', $mani_no)
            ->select('id')
            ->get();

        $docunemtCharge = DB::select('SELECT *,(SELECT rate_of_charges FROM handling_and_othercharges ch WHERE ch.charge_id=18 AND ch.charges_year=? LIMIT 1) AS document_charges
                                  FROM assessment_documents dc WHERE dc.manifest_id=?  AND dc.partial_status=?
                                  ORDER BY dc.id DESC LIMIT 1', [$year,$mani_id[0]->id,$partial_status]);
        if ($docunemtCharge) {
            return $docunemtCharge;
        }
        return 'notFound';

    }
    public function getAllCharges($year){
        $data=DB::select('SELECT * FROM handling_and_othercharges AS hao WHERE hao.charges_year=? AND hao.port_id=?',[$year, Session::get('PORT_ID')]);
        return $data;
    }

    public function GetHolidayChargesForAssesment($r)
    {
        $year=date('Y');

        $holidayForeign = DB::select('SELECT * FROM (
                              SELECT 
                              truck_no,receive_datetime AS holiday,truck_type,
                              IF(DATE(receive_datetime) IN (SELECT DATE(hday) FROM holydays), charges, 0) AS holiday_Charge
                              FROM (
                              SELECT 
                              (SELECT rate_of_charges FROM handling_and_othercharges WHERE charge_id=14 AND charges_year=?) AS charges,
                              truck_type,truck_no,receive_datetime
                              FROM manifests m 
                              INNER JOIN truck_entry_regs t ON t.manf_id=m.id
                              WHERE m.manifest=?
                              ) AS t
                              ) AS tm WHERE holiday_Charge !=0.00', [$year,$r]);

        $holidayLocal = DB::select('SELECT * FROM (
                              SELECT 
                              truck_no,delivery_dt AS holiday,
                              IF(DATE(delivery_dt) IN (SELECT DATE(hday) FROM holydays), charges,0) AS holiday_Charge
                              FROM (
                              SELECT 
                              (SELECT rate_of_charges FROM handling_and_othercharges WHERE charge_id=14 AND charges_year=?) AS charges,
                              truck_no,delivery_dt
                              FROM manifests m 
                              INNER JOIN truck_deliverys d ON d.manf_id=m.id 
                              WHERE m.manifest=? 
                              ) AS t
                              ) AS tm WHERE holiday_Charge !=0.00', [$year,$r]);

        return array($holidayForeign, $holidayLocal);

    }

    //==================================================Partial Assessment Related Global Funtion===================================================


    public  function getWarehouseRentDetailsPartial($manifest,$partial){

            $year = date('Y');
            $partial_delivery_dt = date('Y-m-d');
            $manifest_no=$manifest;
            $partial_status=$partial;
            $manifest_with_partial_status=$manifest.'/'.$partial_status;

        $assessmentCreatedYear = $this->getAassessmentCreatedYear($manifest_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $get_remaining_weight_package = DB::select('SELECT SUM(labor_package+IFNULL(equipment_package,0)) AS laborPkg,
                            SUM(labor_load+IFNULL(equip_load,0)) AS laborLoad,
                            m.package_no,
                            IF((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(?,\'/\',-3),\'/\',1)) 
                            REGEXP \'^-?[0-9]+$\' > 0, 
                             (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                      ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                   END), /*IF Numeric*/
                            m.gweight /*Else Not Numeric*/) 
                            AS max_weight,
                            
                            IF((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(?,\'/\',-3),\'/\',1)) 
                            REGEXP \'^-?[0-9]+$\' > 0, 
                             (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                      ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                   END), 
                            m.gweight ) -  (SUM(labor_load+IFNULL(equip_load,0))) AS balance_weight,
                            m.package_no - SUM(labor_package+IFNULL(equipment_package,0)) AS bal_pkg
                                
                            FROM truck_deliverys trd
                            
                            INNER JOIN manifests m ON trd.manf_id=m.id
                            WHERE manifest= ? AND 
                            trd.partial_status < ?', [$manifest_with_partial_status, $manifest_with_partial_status, $manifest_no, $partial_status]);


        $w = DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,posted_yard_shed,package_no,m_id,yard_shed
                         FROM(SELECT m.goods_id,m.package_no,m.id AS m_id,m.posted_yard_shed AS posted_yard_shed,m.approximate_delivery_date AS deliver_date,
                        (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.manf_id DESC LIMIT 1)AS truckentry_datetime,
                   /*     (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1) AS receive_date,*/
                        (SELECT yard_shed FROM yard_details WHERE m.posted_yard_shed=yard_details.id) AS yard_shed,
                        (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1) AS ReceiveWeight
                        FROM manifests m  WHERE m.manifest=?)t', [$manifest_no]);

        $previous_delivery_dt = DB::select('SELECT MAX(DATE(delivery_dt)) AS pre_par_date FROM truck_deliverys td   /*5907*/
                                    INNER JOIN manifests m ON  m.id=td.manf_id
                                    WHERE m.manifest=? AND td.partial_status=?-1',[$manifest_no,$partial_status]);

        if ($partial>0){
            $partial_delivery_dt = DB::select('SELECT ass.delivery_date as partial_delivery_date FROM assessments AS ass 
                                                    WHERE ass.manifest_id=? AND ass.partial_status=? ORDER BY ass.id DESC LIMIT 1',[$w[0]->m_id,$partial_status]);
        }



        $receive_date = $w[0]->receive_date;
        $goods_id = $w[0]->goods_id;
        $posted_yard_shed = $w[0]->posted_yard_shed;
        $yard_shed = $w[0]->yard_shed;
        $package_no = $get_remaining_weight_package[0]->bal_pkg;
        $item_wise_charge = null;
        $mani_id = $w[0]->m_id;
        $ChargeStartDay =date('Y-m-d', strtotime($previous_delivery_dt[0]->pre_par_date . ' +1 day'));
        $deliver_date = $partial_delivery_dt[0]->partial_delivery_date;

        $wareHouseRentDay = $this->number_of_working_days_partial($ChargeStartDay, $deliver_date);

        if ($yard_shed == 0)//yard 9-24
        {

            $item_wise_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.yard_first_slab AS first_slab,ts.yard_second_slab AS second_slab,ts.yard_third_slab AS third_slab,ic.Description,
             (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
            WHERE id.manf_id=? AND ts.tariff_year=?', [$mani_id, $year]);
        }

        if ($yard_shed == 1)//Shed 25-29
        {
            $item_wise_charge = DB::select('SELECT id.item_type,id.goods_id,id.dangerous,ts.Shed_first_slab AS first_slab,ts.Shed_second_slab AS second_slab,ts.Shed_third_slab AS third_slab,ic.Description,
            (
            CASE id.item_type
            WHEN 4 THEN (CEIL (id.item_quantity/1000))
            ELSE id.item_quantity
            END
            ) AS item_quantity
            FROM item_details AS id 
            JOIN tariff_schedule AS ts ON ts.goods_id=id.goods_id
            JOIN item_codes AS ic ON ic.id=id.item_Code_id
             WHERE id.manf_id=? AND ts.tariff_year=?', [$mani_id, $year]);
        }
        //calculatd slab charge from item wise charge------------------------------

//get slab charge variable globaly
        $firstSlabCharge = 0;
        $secondSlabCharge = 0;
        $thirdSlabCharge = 0;

        $firstSlabDay = 0;
        $secondSlabDay = 0;
        $thirdSlabDay = 0;

        if ($wareHouseRentDay >= 1 && $wareHouseRentDay <= 21) {//1 slab will be calculated------------------1
            $firstSlabDay = $wareHouseRentDay;

            foreach ($item_wise_charge as $key => $value) {

                if ($value->dangerous == '1') {
                    $firstSlabCharge += (ceil($value->first_slab * 2 * $value->item_quantity * $firstSlabDay));
                } else {
                    $firstSlabCharge += (ceil($value->first_slab * $value->item_quantity * $firstSlabDay));
                }
            }
        } else if ($wareHouseRentDay >= 22 && $wareHouseRentDay <= 50) {//2 slab will be calculated------------------2
            $firstSlabDay = 21;
            $secondSlabDay = ($wareHouseRentDay - 21);

            foreach ($item_wise_charge as $key => $value) {
                $firstSlabCharge += (ceil($value->first_slab * $value->item_quantity * $firstSlabDay));
            }
            foreach ($item_wise_charge as $key => $value) {
                $secondSlabCharge += (ceil($value->second_slab * $value->item_quantity * $secondSlabDay));
            }


        } else if ($wareHouseRentDay >= 51) {//3 slab will be calculated---------------------------------3
            $firstSlabDay = 21;
            $secondSlabDay = 29;
            $thirdSlabDay = ($wareHouseRentDay - 21 - 29);

            foreach ($item_wise_charge as $key => $value) {
                $firstSlabCharge += (ceil($value->first_slab * $value->item_quantity * $firstSlabDay));
            }
            foreach ($item_wise_charge as $key => $value) {
                $secondSlabCharge += (ceil($value->second_slab * $value->item_quantity * $secondSlabDay));
            }
            foreach ($item_wise_charge as $key => $value) {
                $secondSlabCharge += (ceil($value->third_slab * $value->item_quantity * $thirdSlabDay));
            }

        } else {
            $firstSlabCharge = 0;
            $secondSlabCharge = 0;
            $thirdSlabCharge = 0;

            $firstSlabDay = 0;
            $secondSlabDay = 0;
            $thirdSlabDay = 0;

        }


//return  'first slab:'.$firstSlabCharge. ' Second slab:'.$secondSlabCharge.' Third slab:'.$thirdSlabCharge;
        $TotalSlabCharge = DB::select('SELECT ad.tcharge AS total_warehouse_charge FROM assesment_details AS ad WHERE ad.manif_id=? AND ad.sub_head_id=2 AND ad.partial_status=?', [$mani_id,$partial_status]);
        if ($TotalSlabCharge) {
            $total_warehouse_charge = $TotalSlabCharge[0]->total_warehouse_charge;

        } else {
            $total_warehouse_charge = 0;
        }


        return array(

            "WareHouseRentDay" => $wareHouseRentDay,
            'chargeable_ton'=>$get_remaining_weight_package[0]->balance_weight,
            'package_no' => $package_no,
            'FreeEndDate' => null,
            'ChargeStartDay' => $ChargeStartDay,
            'item_wise_charge' => $item_wise_charge,
            'receive_date' => $receive_date,
            'deliver_date' => $deliver_date,
            'goods_id' => $goods_id,
            'posted_yard_shed' => $posted_yard_shed,

            'FirstSlabDay' => $firstSlabDay,
            'SecondSlabDay' => $secondSlabDay,
            'thirdSlabDay' => $thirdSlabDay,

            "FirstSlabCharge" => $firstSlabCharge,
            "SecondSlabCharge" => $secondSlabCharge,
            'ThirdSlabCharge' => $thirdSlabCharge,
            'TotalSlabCharge' => $total_warehouse_charge


        );

    }


    public function convert_number_to_words($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
