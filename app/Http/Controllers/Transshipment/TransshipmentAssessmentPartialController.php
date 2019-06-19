<?php

namespace App\Http\Controllers\Transshipment;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use App\truck_entry_reg;
use App\Manifest;
use App\Http\Controllers\GlobalFunctionController;
use Response;
use DateTime;
use DateInterval;
use DatePeriod;
use Symfony\Component\VarDumper\Cloner\Data;

class TransshipmentAssessmentPartialController extends Controller
{
    private $globalFunctionController;

    public function __construct(GlobalFunctionController $globalFunctionController)
    {
        $this->globalFunctionController = $globalFunctionController;
    }


    public function partialAssessment($manifest, $truck, $year, $nth)
    {

        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;


        $chkCode = self::checkPartial($manifest, $truck, $year, $nth);
        if ($chkCode['text'] == 1) {
            return view('transshipment.assessment.partial.assessment-sheet', ['mani_no' => $mani_no, 'partial_status' => $partial_status]);

        } else {
            return view('transshipment.assessment.partial.assessment-partial-not-allowed', ['errorMessage' => $chkCode['text']]);
        }
    }

    public function checkPartial($manifest, $truck, $year, $nth)
    {
        $manifest_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;
        if ($nth == 0) {
             $msg['text'] = 'Partial not allowed!';
        } else {


            $check_partial_allowed = DB::select('SELECT IFNULL(MAX(partial_status),0) AS last_partial 
                                    FROM assesment_details ad
                                    INNER JOIN manifests m ON m.id=ad.manif_id
                                    WHERE m.manifest= ?', [$manifest_no]);

            if (($partial_status - ($check_partial_allowed[0]->last_partial)) > 1) {

                 $msg['text'] = 'Partial not allowed.Because Previous Partial Not Exist!';

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
                         $msg['text'] = 1;
                        // return Response::json(['notfound' => 'You Can Do partial!','get_pre_max_par_dt' => $get_pre_max_par_dt[0] ->pre_par_date], 206);
                    } else {
                         $msg['text'] = 'Your Truck Delivery Not Complete For previous partial!';
                        //return Response::json(['notfound' => 'Your Truck Delivery Not Complete For previous partial'], 206);
                    }

                } else {
                     $msg['text'] = 'You have not enough balance for partial!';
                    //return Response::json(['notfound' => 'You have not enough balance for partial!'], 206);
                }


            } else if (($partial_status - ($check_partial_allowed[0]->last_partial)) == 0) {
                 $msg['text'] = 1;
                //return Response::json(['notfound' => 'You Can Edit partial!'], 206);
            }

            // else if (($partial_status - ($check_partial_allowed[0]->last_partial)) < 0) {
            // $msg['text'] = 1;
            //  $msg['text'] = 'Next Partial Already Complete!';
            // return Response::json(['notfound' => 'You Can not Edit partial!'], 206); // Next Partial Already Complete.
            // }
            else
            {
                 $msg['text'] = 1;
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
                       /* (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1) AS receive_date,*/
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
        $ChargeStartDay = date('Y-m-d', strtotime($previous_delivery_dt[0]->pre_par_date . ' +1 day'));
        $deliver_date = $partial_delivery_dt;



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


    //======================Partial PDF Report=====================================================================

    public function partialAssessmentReport($manifest, $truck, $year, $nth)
    {

        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;
        $todayWithTime = date('Y-m-d h:i:s a');
        $TotalAssessmentValue = 0;
        $permitted = false;
        $userRoleId = Auth::user()->role->id;


        $manifest_id = DB::table('manifests AS m')//check if assessment done for the manifest
        ->where('m.manifest', $mani_no)
            ->select('m.id', 'm.transshipment_flag')
            ->get();

        if ($manifest_id == '[]') {
            return view('Assessment.AssessmentNotDone', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not found in our record!']);
        }

        $checkAssSave = DB::table('assesment_details as assd')
            ->where('assd.manif_id', $manifest_id[0]->id)
            ->where('assd.partial_status', $partial_status)
            ->select('assd.id')
            ->get();

        if ($checkAssSave == '[]') {
            return view('Assessment.AssessmentNotDone', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not saved yet!']);
        }
//Permission related logic----
        $transshipmentFlag = $manifest_id[0]->transshipment_flag;
        if ($transshipmentFlag && ($userRoleId == 12 || $userRoleId == 23)) {
            $permitted = true;
        } else if (!$transshipmentFlag && ($userRoleId == 9 || $userRoleId == 21)) {
            $permitted = true;
        }
        if ($userRoleId == 1) {
            $permitted = true;
        }


        //=====================Global Variable=======================

        //WareHouse Rent
        $totalWarehouseRent = 0;
        //offload------------------
        $OffloadLabour = 0;
        $OffLoadingEquip = 0;
        $offloadEquipShiftingFlag = null;
        $OffloadLabourCharge = 0;
        $OffLoadingEquipCharge = 0;
        $TotalForOffloadLabour = 0;
        $TotalForOffloadEquip = 0;

        //Load------------------
        $loadLabour = 0;
        $loadLabourCharge = 0;
        $TotalForloadLabour = 0;

        $loadEquip = 0;
        $loadingEquipCharge = 0;
        $TotalForloadEquip = 0;
        $approximate_delivery_type = null;


        //Handling and other Due===================
        //entrace fee----------
        $entranceTotalForeignTruck = 0;
        $entranceFeeForeign = 0;
        $totalEntranceFeeForeign = 0;

        $entranceTotalLocalTruck = 0;
        $entranceFeeLocal = 0;
        $totalEntranceFeeLocal = 0;

        //Carpanter Charge------------
        $carpenterOPPackages = null;
        $carpenterChargesOpenClose = null;
        $totalCarpenterChargesOpenClose = 0;

        $carpenterRepairPackages = null;
        $carpenterChargesRepair = null;
        $totalCarpenterChargesRepair = 0;

        //Night Charge----------
        $night = null;

        //Holiday Charge-----------
        $holidayCharge = 0;
        $holidayTotalTruck = 0;
        $totalHolidayCharge = 0;
        $holiday = null;

        //Haltage  Charge -------------
        $haltage_truck_foreign=0;
        $haltage_day_foreign=0;
        $haltage_charge_foreign=0;
        $haltage_total_foreign=0;


        //weighment------------

        $weighmentChargeForeign = 0;
        $totalForeignTruckForWeighment = 0;
        $totalWeighmentChargeForeign = 0;

        $totalLocalTruckForWeighment = 0;
        $weighmentChargeLocal = 0;
        $totalweightmentChargesLocal = 0;

        //Document Charge-----------
        $totalDocumentCharge = 0;
        $number_of_documents = 0;
        $document_charge = 0;

        //Partial Truck Charge -------
        $countLocalTruck = 0;
        $truckEntranceFee = 0;
        $totalTruckEntranceFee = 0;

//==================================ASSESSMENT DETAILS==========================================

        $assessmentDetails = DB::select('SELECT * 
                                        FROM assesment_details AS ad 
                                        WHERE ad.manif_id=? AND ad.partial_status=?',
            [$manifest_id[0]->id, $partial_status]);


        $getAssessmentTblData = DB::select('SELECT date_of_unloading,free_period,rent_due_period,weight,good_description,no_of_pkg,local_truck 
                                        FROM assessments 
                                        WHERE manifest_id=? AND partial_status=? 
                                        ORDER BY id DESC LIMIT 1',
            [$manifest_id[0]->id, $partial_status]);

        if ($assessmentDetails) {
            foreach ($assessmentDetails as $k => $v) {
                $subHeadId = $v->sub_head_id;

                if ($subHeadId == 2) {//WareHouse Rent
                    // $OffloadLabour = $v->unit;
                    // $OffloadLabourCharge = $v->charge_per_unit;
                    $totalWarehouseRent = $v->tcharge;
                }

                if ($subHeadId == 4) {//offload labour
                    $OffloadLabour = $v->unit;
                    $OffloadLabourCharge = $v->charge_per_unit;
                    $TotalForOffloadLabour = $v->tcharge;
                }

                if ($subHeadId == 6) {//offload equip
                    $OffLoadingEquip = $v->unit;
                    $offloadEquipShiftingFlag = $v->other_unit;//if shifting 1 otherwise null
                    $OffLoadingEquipCharge = $v->charge_per_unit;
                    $TotalForOffloadEquip = $v->tcharge;
                }

                if ($subHeadId == 8) {//load labour
                    $loadLabour = $v->unit;
                    $loadLabourCharge = $v->charge_per_unit;
                    $TotalForloadLabour = $v->tcharge;
                }

                if ($subHeadId == 10) {//loading equip
                    $loadEquip = $v->unit;
                    $loadingEquipCharge = $v->charge_per_unit;
                    $TotalForloadEquip = $v->tcharge;
                }

                if ($subHeadId == 26) {//Entrance Fee-Foreign
                    $entranceTotalForeignTruck = $v->unit;
                    $entranceFeeForeign = $v->charge_per_unit;
                    $totalEntranceFeeForeign = $v->tcharge;
                }

                if ($subHeadId == 28) {//Entrance Fee-local
                    $entranceTotalLocalTruck = $v->unit;
                    $entranceFeeLocal = $v->charge_per_unit;
                    $totalEntranceFeeLocal = $v->tcharge;
                }
                if ($subHeadId == 30) {//Carpanter Charge-open/close
                    $carpenterOPPackages = $v->unit;
                    $carpenterChargesOpenClose = $v->charge_per_unit;
                    $totalCarpenterChargesOpenClose = $v->tcharge;
                }

                if ($subHeadId == 32) {//Carpanter Charge-repair
                    $carpenterRepairPackages = $v->unit;
                    $carpenterChargesRepair = $v->charge_per_unit;
                    $totalCarpenterChargesRepair = $v->tcharge;
                }
                if ($subHeadId == 46) {// Haltage Charge-foreign
                    $haltage_truck_foreign = $v->unit;
                    $haltage_day_foreign=$v->other_unit;
                    $haltage_charge_foreign = $v->charge_per_unit;
                    $haltage_total_foreign = $v->tcharge;
                }
                if ($subHeadId == 48) {//local-weighment
                    $totalLocalTruckForWeighment = $v->unit;
                    $weighmentChargeLocal = $v->charge_per_unit;
                    $totalweightmentChargesLocal = $v->tcharge;
                }
                if ($subHeadId == 50) {//foreign-weighment
                    $weighmentChargeForeign = $v->charge_per_unit;
                    $totalForeignTruckForWeighment = $v->unit;
                    $totalWeighmentChargeForeign = $v->tcharge;
                }


            }

            //Night Charge-both foreign and local truck
            $night = DB::select('SELECT SUM(a.tcharge) TotalNightCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=14 AND a.manif_id=? AND a.partial_status=?', [$manifest_id[0]->id, $partial_status]);


            //Holiday Charge-both foreign and local truck
            $holiday = DB::select('SELECT SUM(a.tcharge) TotalHolidayCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=16 AND a.manif_id=? AND a.partial_status=?', [$manifest_id[0]->id, $partial_status]);



            $documentCharge = DB::select('SELECT * FROM assesment_details WHERE sub_head_id=52 AND partial_status=? AND manif_id=?', [$partial_status, $manifest_id[0]->id]);
            if ($documentCharge) {
                $totalDocumentCharge = $documentCharge[0]->tcharge;
                $number_of_documents = $documentCharge[0]->unit;
                $document_charge = $documentCharge[0]->charge_per_unit;
            }

        }

        $manifestDetails = $this->globalFunctionController->ManifestDetailsForAssessmentAss($mani_no);
        $warehouseDetail = $this->globalFunctionController->getWarehouseRentDetailsPartial($mani_no, $partial_status);

        $get_assessments_data=DB::select('SELECT * FROM assessments AS ass WHERE ass.manifest_id=? AND ass.partial_status=? ORDER BY ass.id DESC LIMIT 1',[$manifest_id[0]->id, $partial_status]);


        // Info About Partial Truck START
        $partialTruckInfo = DB::select('SELECT unit,charge_per_unit,tcharge FROM assesment_details WHERE manif_id=? AND partial_status=? AND sub_head_id=28', [$manifest_id[0]->id, $partial_status]);
        if ($partialTruckInfo) {
            $countLocalTruck = $partialTruckInfo[0]->unit;
            $truckEntranceFee = $partialTruckInfo[0]->charge_per_unit;
            $totalTruckEntranceFee = $partialTruckInfo[0]->tcharge;
        }
        //return $partialTruckInfo;
        // Info About Partial Truck END

        //  dd( $warehouseDetail['WareHouseRentDay']);

        $transshipmentFlag = $manifestDetails[0]->transshipment_flag ? true : false;


//=================Add to Assessment Vlue==============================

//WareHouse Charge
        $TotalAssessmentValue += $totalWarehouseRent;

//handling charge --offload labour
        $TotalAssessmentValue += $TotalForOffloadLabour;
        //handling charge-- offload equipment
        $TotalAssessmentValue += $TotalForOffloadEquip;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadLabour;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadEquip;

//Entrance fee
        /* $entranceFee = $HandlingOtherDue[0]->entrance_fee;
         $totalForeignTruck = $HandlingOtherDue[0]->foreign_truck;*/

        // if ($transshipment) {//============TRANSSHIPMENT
        //$totalLocalTruck = $HandlingOtherDue[0]->local_truck;
        // } else {
        // $totalLocalTruck = $HandlingOtherDue[0]->no_del_truck;
        // }


        $TotalAssessmentValue += $totalEntranceFeeForeign;
        $TotalAssessmentValue += $totalEntranceFeeLocal;

//Carpenter Charges------
        $TotalAssessmentValue += $totalCarpenterChargesOpenClose;
        $TotalAssessmentValue += $totalCarpenterChargesRepair;

//Weighment measurement  Charges
        $TotalAssessmentValue += $totalWeighmentChargeForeign;
        $TotalAssessmentValue += $totalweightmentChargesLocal;

        //holiday charge
        $TotalAssessmentValue += $holiday[0]->TotalHolidayCharge;

        //Night Charge
        $TotalAssessmentValue += $night[0]->TotalNightCharge;

        //Haltage Charge
        $TotalAssessmentValue += $haltage_total_foreign;

        //Document Charge
        $TotalAssessmentValue += $totalDocumentCharge;


        //Tatal Calculation
        $TotalAssessmentValue = ceil($TotalAssessmentValue);//number_format(, 2, '.', '');
        $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        // $Vat = number_format((($TotalAssessmentValue * 15) / 100), 2, '.', '');
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);

        //dd($get_assessments_data[0]->weight);
        $pdf = PDF::loadView('transshipment.assessment.AssessmentSheetPdf', [
            'todayWithTime' => $todayWithTime,
            'permitted' => $permitted,
            'partial_status' => $partial_status,
            //manifest details
            'ManifestDate' => $manifestDetails[0]->manifest_date != null ? $manifestDetails[0]->manifest_date : '',
            'manifestNo' => $manifestDetails[0]->manifest_no,
            'transshipment' => $manifestDetails[0]->transshipment_flag ? true : false,

            'bill_entry_no' => $manifestDetails[0]->bill_entry_no,
            'bill_entry_date' => $manifestDetails[0]->bill_entry_date,
            'importer' => $manifestDetails[0]->importer,
            'exporter' => $manifestDetails[0]->exporter,
            'cnf_name' => $manifestDetails[0]->cnf_name,
            'package_type' => $manifestDetails[0]->package_type,
            'package_no' => $getAssessmentTblData[0]->no_of_pkg,

            'custom_realise_order_No' => $manifestDetails[0]->custom_realise_order_No,
            'custom_realise_order_date' => $manifestDetails[0]->custom_realise_order_date,
            'description_of_goods' => $manifestDetails[0]->description_of_goods,
            'totalItems' => $manifestDetails[0]->totalItems,
            'chargeable_ton' => $get_assessments_data[0]->weight,

            //Warehouse Rent Details--------------------
            "RentDay" => $getAssessmentTblData[0]->rent_due_period,
            "WareHouseRentDay" => $warehouseDetail['WareHouseRentDay'],
            //"WareHouseRentDay" => $warehouseDetail['WareHouseRentDay'],
            'FreeEndDate' => null,
            'item_wise_charge' => $warehouseDetail['item_wise_charge'],
            'ChargeStartDay' => $warehouseDetail['ChargeStartDay'],

            'firstSlabDay' => $warehouseDetail['FirstSlabDay'],
            'secondSlabDay' => $warehouseDetail['SecondSlabDay'],
            'thirdSlabDay' => $warehouseDetail['thirdSlabDay'],

            "firsrSlabTotalCharge" => $warehouseDetail['FirstSlabCharge'],
            "SecondSlabTotalCharge" => $warehouseDetail['SecondSlabCharge'],
            'ThirdSlabTotalCharge' => $warehouseDetail['ThirdSlabCharge'],
            'TotalSlabCharge' => $warehouseDetail['TotalSlabCharge'],


            // 'receive_date' => $warehouseDetail['receive_date'],
            'receive_date' => $getAssessmentTblData[0]->date_of_unloading,
            'deliver_date' => $warehouseDetail['deliver_date'],
            'goods_id' => $warehouseDetail['goods_id'],
            'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,

//Handling ====Charge and other due==================

            //Offload--------------
            'OffloadLabour' => $OffloadLabour,
            'OffloadLabourCharge' => $OffloadLabourCharge,
            'TotalForOffloadLabour' => $TotalForOffloadLabour,

            'OffLoadingEquip' => $OffLoadingEquip,
            'OffLoadingEquipCharge' => $OffLoadingEquipCharge,
            'offloadEquipShiftingFlag' => $offloadEquipShiftingFlag,
            'TotalForOffloadEquip' => $TotalForOffloadEquip,

            //Load-----------
            'loadLabour' => $loadLabour,
            'loadLabourCharge' => $loadLabourCharge,
            'TotalForloadLabour' => $TotalForloadLabour,

            'loadEquip' => $loadEquip,
            'loadingEquipCharge' => $loadingEquipCharge,
            'TotalForloadEquip' => $TotalForloadEquip,

//Entrance fee-------
            'entranceFeeForeign' => $entranceFeeForeign,
            //'entranceFeeLocal' => $entranceFeeLocal,
            'entranceFeeLocal' => $truckEntranceFee,
            'entranceTotalForeignTruck' => $entranceTotalForeignTruck,
            'entranceTotalLocalTruck' => $countLocalTruck,
            //'entranceTotalLocalTruck' => $getAssessmentTblData[0]->local_truck,
            //'entranceTotalLocalTruck' => $entranceTotalLocalTruck,
            'totalForeignTruckEntranceFee' => $totalEntranceFeeForeign,
            //'totalLocalTruckEntranceFee' => $totalEntranceFeeLocal,
            'totalLocalTruckEntranceFee' => $totalTruckEntranceFee,

//carpenter charge-open/close and repair

            'carpenterOPPackages' => $carpenterOPPackages,
            'carpenterChargesOpenClose' => $carpenterChargesOpenClose,
            'totalCarpenterChargesOpenClose' => $totalCarpenterChargesOpenClose,

            'carpenterRepairPackages' => $carpenterRepairPackages,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            'totalCarpenterChargesRepair' => $totalCarpenterChargesRepair,

//Weighment measurement  Charges
            'weighmentChargeForeign' => $weighmentChargeForeign,
            'totalForeignTruckForWeighment' => $totalForeignTruckForWeighment,
            'totalweightmentChargesForeign' => $totalWeighmentChargeForeign,

            'weighmentChargeLocal' => $weighmentChargeLocal,
            'totalLocalTruckForWeighment' => $totalLocalTruckForWeighment,
            'totalweightmentChargesLocal' => $totalweightmentChargesLocal,

//Night Charge
            'NightTotalTruck' => $night[0]->TotalTruck,
            'NightCharge' => $night[0]->charge_per_unit,
            'TotalNightCharge' => $night[0]->TotalNightCharge,

//Holiday Charge
            'HolidayTotalTruck' => $holiday[0]->TotalTruck,
            'HolidayCharge' => $holiday[0]->charge_per_unit,
            'TotalHolidayCharge' => $holiday[0]->TotalHolidayCharge,

//Haltage Charge
            /*'HaltageTotalTruck' => $haltage[0]->TotalTruck,
            'HaltageCharge' => $haltage[0]->charge_per_unit,
            'TotalHaltageCharge' => $haltage[0]->TotalHaltageCharge,
            'TotalHaltageDay' => $haltage[0]->TotalHaltage,*/
            'haltage_truck_foreign' =>$haltage_truck_foreign,
            'haltage_charge_foreign' => $haltage_charge_foreign,
            'haltage_total_foreign' => $haltage_total_foreign,
            'haltage_day_foreign' => $haltage_day_foreign,


            //$documentCharge
            'totalDocumentCharge' => $totalDocumentCharge,
            'number_of_documents' => $number_of_documents,
            'document_charge' => $document_charge,


//Total Calculation
            'TotalAssessmentValue' => $TotalAssessmentValue,
            'Vat' => $Vat,
            'TotalAssessmentWithVat' => $TotalAssessmentWithVat,
            // 'TotalAssessmentInWord'=>$TotalAssessmentInWord

            'role' => Auth::user()->role->name,


        ])->setPaper([0, 0, 1000.661, 800.63], 'landscape');


        return $pdf->stream('Assessment' . '-' . $mani_no . '.pdf');

    }

}
