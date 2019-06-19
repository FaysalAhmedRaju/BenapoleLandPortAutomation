<?php

namespace App\Http\Controllers\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Assessment\AssessmentWarehouseChargeController;
use DB;
use Auth;
use PDF;
use App\truck_entry_reg;
use App\Manifest;
use Response;
use DateTime;
use DateInterval;
use DatePeriod;
use Symfony\Component\VarDumper\Cloner\Data;

class AssessmentPartialController extends Controller
{
    private $globalFunctionController;
    private $ware;

    public function __construct(GlobalFunctionController $globalFunctionController,AssessmentWarehouseChargeController $ware)
    {
        $this->globalFunctionController = $globalFunctionController;
        $this->ware = $ware;
    }


    public function partialAssessment($manifest, $truck, $year, $nth)
    {
        /* $r=(string)$manifest . "/" . (string)$truck . "/" . (string)$year;
         $status= $nth;

          $getPartialTruckBalance=DB::select('SELECT manifest_id,no_del_truck AS man_del_truck,local_truck AS ass_local_truck,local_transport_type AS man_transport_type,
             IFNULL(no_del_truck-local_truck,0) AS remaining_truck
             FROM (SELECT id,no_del_truck,local_transport_type
             FROM manifests
             WHERE manifest=?) man
               JOIN (SELECT manifest_id,local_truck
             FROM assessments
             WHERE manifest_id=(SELECT id FROM manifests WHERE manifest=?) AND partial_status=?-1
             ORDER BY id DESC LIMIT 1) ass
             ON  man.id = ass.manifest_id', [$r,$r,$status]);
        //return $checkAssDone;
         return $getPartialTruckBalance;*/

        //return $this->globalFunctionController->ManifestDetailsForAssessmentAssPartial($r,$status);

        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;

        return view('Assessment.partial.assessment-sheet', ['mani_no' => $mani_no, 'partial_status' => $partial_status]);

        $chkCode = self::checkPartial($manifest, $truck, $year, $nth);
        if ($chkCode['notfound'] == 1) {
            return view('Assessment.partial.assessment-sheet', ['mani_no' => $mani_no, 'partial_status' => $partial_status]);

        } else {
            return view('Assessment.partial.assessment-partial-not-allowed', ['errorMessage' => $chkCode['notfound']]);
        }

        //echo $chkCode['notfound'];

        /*if ($nth <= 0) {
            $errorMessage = $nth . 'nth No. Partial Not Allowed';
            return view('Assessment.partial.assessment-partial-not-allowed', ['errorMessage' => $errorMessage]);
        }*/
        //return view('Assessment.partial.assessment-sheet', ['mani_no' => $mani_no, 'partial_status' => $partial_status]);
    }

    public function checkPartial($manifest, $truck, $year, $nth)
    {
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;
        if ($nth == 0) {
            $msg['notfound'] = 'Partial not allowed!';
        } else {


            $check_partial_allowed = DB::select('SELECT IFNULL(MAX(partial_status),0) AS last_partial 
                                    FROM assesment_details ad
                                    INNER JOIN manifests m ON m.id=ad.manif_id
                                    WHERE m.manifest= ?', [$manifest_no]);

            if (($partial_status - ($check_partial_allowed[0]->last_partial)) > 1) {

                $msg['notfound'] = 'Partial not allowed.Because Previous Partial Not Exist!';

                // return Response::json(['notfound' => 'Partial not allowed!'], 206);
                // $errorMessage = $partial_status . 'nth No. Partial Not Allowed';
                //return view('Assessment.partial.assessment-partial-not-allowed', ['errorMessage' => $errorMessage]);
            } else if (($partial_status - ($check_partial_allowed[0]->last_partial)) == 1) {

                $check_partial_balance = DB::select("SELECT 
                                                IF((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(?,'/',-2),'/',1)) 
                                                REGEXP '^-?[0-9]+$' > 0, 
                                                 (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                                          ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                                       END), 
                                                m.gweight ) -  (SUM(IFNULL(labor_load,0)+IFNULL(equip_load,0))) AS balance
                                                    
                                                FROM truck_deliverys trd

                                                INNER JOIN manifests m ON trd.manf_id=m.id
                                                WHERE manifest= ?", [$manifest_no, $manifest_no]);

                if ($check_partial_balance[0]->balance > 0) {
                    $pre_partial_status = $partial_status - 1; // Previous Partial

                    $chk_truck_deliver = DB::select("SELECT COUNT(id) AS id FROM truck_deliverys 
                                            WHERE DATE(delivery_dt) IN(
                                            SELECT * FROM (
                                            SELECT DISTINCT IFNULL(delivery_date,'00-00-0000') AS w  FROM assessments ass
                                            INNER JOIN manifests m ON ass.manifest_id =m.id
                                            WHERE m.manifest= ? AND partial_status=?
                                            ORDER BY ass.id DESC LIMIT 1) AS tbl) AND manf_id IN (
                                            SELECT * FROM (
                                            SELECT m.id 
                                            FROM assessments ass INNER JOIN manifests m ON ass.manifest_id =m.id 
                                            WHERE m.manifest= ? AND partial_status= ?
                                            ORDER BY ass.id DESC LIMIT 1) 
                                            AS tbl1
                                            )", [$manifest_no, $pre_partial_status, $manifest_no, $pre_partial_status]);
                    if ($chk_truck_deliver[0]->id > 0) {
                        $get_pre_max_par_dt = DB::select("SELECT MAX(DATE(delivery_dt)) AS pre_par_date 
                                                     FROM truck_deliverys td   
                                                    INNER JOIN manifests m ON  m.id=td.manf_id
                                                    WHERE m.manifest= ? AND td.partial_status= ? ", [$manifest_no, $pre_partial_status]);
                        $msg['notfound'] = 1;
                        // return Response::json(['notfound' => 'You Can Do partial!','get_pre_max_par_dt' => $get_pre_max_par_dt[0] ->pre_par_date], 206);
                    } else {
                        $msg['notfound'] = 'Your Truck Delivery Not Complete For previous partial!';
                        //return Response::json(['notfound' => 'Your Truck Delivery Not Complete For previous partial'], 206);
                    }

                } else {
                    $msg['notfound'] = 'You have not enough balance for partial!';
                    //return Response::json(['notfound' => 'You have not enough balance for partial!'], 206);
                }


            } else if (($partial_status - ($check_partial_allowed[0]->last_partial)) == 0) {
                $msg['notfound'] = 1;
                //return Response::json(['notfound' => 'You Can Edit partial!'], 206);
            }

            // else if (($partial_status - ($check_partial_allowed[0]->last_partial)) < 0) {
            //$msg['notfound'] = 1;
            // $msg['notfound'] = 'Next Partial Already Complete!';
            // return Response::json(['notfound' => 'You Can not Edit partial!'], 206); // Next Partial Already Complete.
            // }
            else
            {
                $msg['notfound'] = 1;
            }
        }
        return $msg;
    }

    public function checkManifesForPartialAssessment(Request $r)
    {


        $manifest_details = null;
        $userRoleId = Auth::user()->role->id;


        $manifest_no = $r->mani_no;
        $partial_status = $r->partial_status;
        $manifest_with_partial_status = $manifest_no . '/' . $partial_status;

        //return $partial_status;


        $manifest_details = $this->globalFunctionController->ManifestDetailsForAssessmentAssPartial($manifest_no, $partial_status);


        if (!$manifest_details) {
            return Response::json(['notfound' => 'manifest not found in our record!'], 204);
        }

        $transshipment = $manifest_details[0]->transshipment_flag ? true : false;

        if (($userRoleId == 12 || $userRoleId == 23) && $transshipment) {//tran and tran admin
            return json_encode($manifest_details);
        } else if (($userRoleId == 9 || $userRoleId == 21) && !$transshipment) {//assess and ass admin
            return json_encode($manifest_details);
        } else {
            return Response::json(['noPermission' => 'You are not permitted to see this manifest.'], 203);
        }

    }


    public function getAllDetailsForPartialAssessment(Request $r)
    {


        $mani='50681/3/2017';

        $dd=$this->ware->getWarehouseForAssesment($mani);
        $item_wise_charge=$dd;

        return $dd;

        $year = date('Y');
        $partial_delivery_dt = date('Y-m-d');
        $manifest_no = $r->mani_no;
        $partial_status = $r->partial_status;
        $manifest_with_partial_status = $manifest_no . '/' . $partial_status;


        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $getAllCharges = $this->globalFunctionController->getAllCharges($year);//get all charges from handling and other charge table
        $docunemt_details = $this->globalFunctionController->getDocumentDetailsForAssessment($manifest_no, $partial_status);

        $get_remaining_weight_package = DB::select('SELECT SUM(IFNULL(labor_package,0)+IFNULL(equipment_package,0)) AS laborPkg,
                            SUM(IFNULL(labor_load,0)+IFNULL(equip_load,0)) AS laborLoad,
                            m.package_no,
                            IF((SELECT (SUBSTRING_INDEX(?,\'/\',-2))) 
                            REGEXP \'^-?[0-9]+$\' > 0, 
                             (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                      ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                   END), /*IF Numeric*/
                            m.gweight /*Else Not Numeric*/) 
                            AS max_weight,
                            IF((SELECT (SUBSTRING_INDEX(?,\'/\',-2)))  
                            REGEXP \'^-?[0-9]+$\' > 0, 
                             (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                      ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                   END), 
                            m.gweight ) -  (SUM(IFNULL(labor_load,0)+IFNULL(equip_load,0))) AS balance_weight,
                            m.package_no - SUM(IFNULL(labor_package,0)+IFNULL(equipment_package,0)) AS bal_pkg
                            FROM truck_deliverys trd
                            INNER JOIN manifests m ON trd.manf_id=m.id
                            WHERE manifest= ? AND 
                            trd.partial_status < ?', [$manifest_no, $manifest_no, $manifest_no, $partial_status]);
        ;

        $w = DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,posted_yard_shed,package_no,m_id,yard_shed
                         FROM(SELECT m.goods_id,m.package_no,m.id AS m_id,m.posted_yard_shed AS posted_yard_shed,m.approximate_delivery_date AS deliver_date,
                        (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.manf_id DESC LIMIT 1)AS truckentry_datetime,
                    /*    (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1) AS receive_date,*/
                        (SELECT yard_shed FROM yard_details WHERE m.posted_yard_shed=yard_details.id) AS yard_shed,
                        (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1) AS ReceiveWeight
                        FROM manifests m  WHERE m.manifest=?)t', [$manifest_no]);

        $previous_delivery_dt = DB::select('SELECT delivery_date AS pre_par_date FROM assessments ass   /*5907*/
                                    INNER JOIN manifests m ON  m.id=ass.manifest_id
                                    WHERE m.manifest=? AND ass.partial_status=?-1
                                    ORDER BY ass.id DESC LIMIT 1', [$r->mani_no, $r->partial_status]);

        if ($partial_status > 0) {
            $get_partial_delivery_dt = DB::select('SELECT ass.delivery_date AS delivery_date FROM assessments AS ass 
                                                    WHERE ass.manifest_id=? AND ass.partial_status=? ORDER BY ass.id DESC LIMIT 1', [$w[0]->m_id, $partial_status]);
            if ($get_partial_delivery_dt && $get_partial_delivery_dt[0]->delivery_date) {
                $partial_delivery_dt = $get_partial_delivery_dt[0]->delivery_date;
            }
        }
        //return $partial_delivery_dt;
        if ($r->delivery_dt) {//here for partial date we also have to check if the partial is already saved. if saved then bd truck delivery date will be the date here
            $partial_delivery_dt = $r->delivery_dt;
        }

        // return $partial_delivery_dt;

        $receive_date = $w[0]->receive_date;
        $goods_id = $w[0]->goods_id;
        $posted_yard_shed = $w[0]->posted_yard_shed;
        $yard_shed = $w[0]->yard_shed;
        $package_no = $w[0]->package_no;
        $item_wise_charge = null;
        $mani_id = $w[0]->m_id;
        //  $ChargeStartDay = date('Y-m-d', strtotime($previous_delivery_dt[0]->pre_par_date . ' +1 day'));
        $freeEndDay = $this->globalFunctionController->GetFreedayEndForWarehouseRent($mani_id, $receive_date);//return $receive_date + 3 days excluding holidays

        $ChargeStartDay = $this->globalFunctionController->ChargeStartDay($freeEndDay);
        $deliver_date = $w[0]->deliver_date;



        $wareHouseRentDay = $this->globalFunctionController->number_of_working_days_partial($ChargeStartDay, $deliver_date);
        //return $wareHouseRentDay;
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
        $getAassessmentsData= DB::select('SELECT assessments.*,local_truck AS remaining_truck FROM assessments
                                    INNER JOIN manifests ON assessments.manifest_id=manifests.id
                                    WHERE manifests.manifest=? AND partial_status=?
                                    ORDER BY assessments.id DESC LIMIT 1',[$manifest_no,$partial_status]);
        if(count($getAassessmentsData)>0)
        {
            $getPartialTruckBalance= $getAassessmentsData;
        }
        else
        {
            $getPartialTruckBalance = DB::select('SELECT manifest_id,no_del_truck AS man_del_truck,

                                (SELECT SUM(IFNULL(local_truck,0)) AS ass_local_truck FROM 
                                (SELECT DISTINCT partial_status,local_truck FROM assessments
                                    INNER JOIN manifests m ON m.id=assessments.manifest_id WHERE manifest=? ORDER BY assessments.id DESC 
                                ) AS tbl ) AS ass_local_truck,

                                local_transport_type AS man_transport_type,

                                            IFNULL(no_del_truck-(SELECT SUM(IFNULL(local_truck,0)) AS ass_local_truck FROM 
                                (SELECT DISTINCT partial_status,local_truck FROM assessments
                                    INNER JOIN manifests m ON m.id=assessments.manifest_id WHERE manifest=? ORDER BY assessments.id DESC 
                                ) AS tbl),0) AS remaining_truck
            
                                FROM (SELECT id,no_del_truck,local_transport_type
                                FROM manifests 
                                WHERE manifest=?) man 
                                  JOIN (SELECT manifest_id,local_truck 
                                FROM assessments 
                                WHERE manifest_id=(SELECT id FROM manifests WHERE manifest=?) AND partial_status=?-1 
                                ORDER BY id DESC LIMIT 1) ass 
                                ON  man.id = ass.manifest_id', [$manifest_no, $manifest_no,$manifest_no,
                $manifest_no,$partial_status]);
        }




//return  'first slab:'.$firstSlabCharge. ' Second slab:'.$secondSlabCharge.' Third slab:'.$thirdSlabCharge;
        $TotalSlabCharge = DB::select('SELECT ad.tcharge AS total_warehouse_charge FROM assesment_details AS ad WHERE ad.manif_id=? AND ad.sub_head_id=2', [$mani_id]);
        if ($TotalSlabCharge) {
            $total_warehouse_charge = $TotalSlabCharge[0]->total_warehouse_charge;

        } else {
            $total_warehouse_charge = 0;
        }


        return array(
            'remaining_weight_package' => $get_remaining_weight_package,
            "WareHouseRentDay" => $wareHouseRentDay,
            'ChargeStartDay' => $ChargeStartDay,
            'item_wise_charge' => $item_wise_charge,
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
            'TotalSlabCharge' => $total_warehouse_charge,
            'allCharges' => $getAllCharges,
            'docunemt_details' => $docunemt_details,
            'getPartialTruckBalance' => $getPartialTruckBalance


        );

    }
}
