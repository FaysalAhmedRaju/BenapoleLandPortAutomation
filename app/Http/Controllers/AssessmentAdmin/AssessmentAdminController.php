<?php

namespace App\Http\Controllers\AssessmentAdmin;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Http\Controllers\GlobalFunctionController;
use App\Http\Controllers\AssessmentBaseController;
use Session;

class AssessmentAdminController extends Controller
{

    private $globalFunctionController;
    private $assessment_base_controller;

    public function __construct(GlobalFunctionController $globalFunctionController, AssessmentBaseController $assessment_base_controller)
    {
        $this->middleware('auth');
        $this->globalFunctionController = $globalFunctionController;
        $this->assessment_base_controller = $assessment_base_controller;
    }


    public function Welcome()
    {
        return view('default.assessment-admin.welcome');
    }

    public function completedAssessmentView()
    {
        //dd(Auth::user()->role->id);
        return view('default.assessment-admin.completed-assessment-view');
    }

    public function getCompletedAssessment($date, $a)
    {
        $port_id = Session::get('PORT_ID');
        $today = $date;
        if (Auth::user()->role->id == 1) {   //super Admin role id = 1
            if ($a == 1) {
                $todaysCompletedAssessment = DB::select("SELECT assessments.done, assessments.done_at, assessments.done_by,assessments.id, assessments.manifest_id AS manifestId, 
manifests.manifest, manifests.be_no,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, assessments.assessment_values, assessments.partial_status,assessments.created_at,
manifests.exporter_name_addr, manifests.cnf_name, 
(SELECT CEIL(SUM(assesment_details.tcharge))  
FROM assesment_details WHERE assesment_details.manif_id=assessments.manifest_id AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by,
(SELECT users.name FROM users WHERE users.id = assessments.done_by) AS done_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
WHERE assessments.id IN (
SELECT MAX(id) 
FROM assessments 
WHERE port_id=?
GROUP BY manifest_id, partial_status)
AND DATE(assessments.done_at)=? AND assessments.done = 1
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.done_at ASC", [$port_id, $today, $port_id, $port_id]);
                return json_encode($todaysCompletedAssessment);
            } else {
                $todaysCompletedAssessment = DB::select("SELECT assessments.done, assessments.done_at, assessments.done_by,assessments.id, assessments.manifest_id AS manifestId, 
manifests.manifest, manifests.be_no,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, assessments.assessment_values, assessments.partial_status,assessments.created_at,
manifests.exporter_name_addr, manifests.cnf_name, 
(SELECT CEIL(SUM(assesment_details.tcharge))  
FROM assesment_details WHERE assesment_details.manif_id=assessments.manifest_id AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by,
(SELECT users.name FROM users WHERE users.id = assessments.done_by) AS done_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
WHERE assessments.id IN (
SELECT MAX(id) 
FROM assessments 
WHERE port_id=? 
GROUP BY manifest_id, partial_status)
AND DATE(assessments.updated_at)=? AND assessments.done = 0
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.created_at ASC", [$port_id, $today, $port_id, $port_id]);
                return json_encode($todaysCompletedAssessment);
            }
        } else {
            $flag = 0;
            if ($a == 1) {
                $todaysCompletedAssessment = DB::select("SELECT assessments.done, assessments.done_at, assessments.done_by,assessments.id, assessments.manifest_id AS manifestId, 
manifests.manifest, manifests.be_no,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, assessments.assessment_values, assessments.partial_status,assessments.created_at,
manifests.exporter_name_addr, manifests.cnf_name, 
(SELECT CEIL(SUM(assesment_details.tcharge))  
FROM assesment_details WHERE assesment_details.manif_id=assessments.manifest_id AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by,
(SELECT users.name FROM users WHERE users.id = assessments.done_by) AS done_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
WHERE assessments.id IN (
SELECT MAX(id) 
FROM assessments 
WHERE port_id=? 
GROUP BY manifest_id, partial_status)
AND manifests.transshipment_flag =?
AND DATE(assessments.done_at)=? AND assessments.done = 1
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.done_at ASC", [$port_id, $flag, $today, $port_id, $port_id]);
                return json_encode($todaysCompletedAssessment);
            } else {
                $todaysCompletedAssessment = DB::select("SELECT assessments.done, assessments.done_at, assessments.done_by,assessments.id, assessments.manifest_id AS manifestId, 
manifests.manifest, manifests.be_no,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, assessments.assessment_values, assessments.partial_status,assessments.created_at,
manifests.exporter_name_addr, manifests.cnf_name, 
(SELECT CEIL(SUM(assesment_details.tcharge))  
FROM assesment_details WHERE assesment_details.manif_id=assessments.manifest_id AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by,
(SELECT users.name FROM users WHERE users.id = assessments.done_by) AS done_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
WHERE assessments.id IN (
SELECT MAX(id) 
FROM assessments 
WHERE port_id=? 
GROUP BY manifest_id, partial_status)
AND manifests.transshipment_flag =?
AND DATE(assessments.updated_at)=? AND assessments.done = 0
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.updated_at ASC", [$port_id, $flag, $today, $port_id, $port_id]);
                return json_encode($todaysCompletedAssessment);
            }
        }
    }

    public function checkAssessmentDone(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        $mani_no = $req->mani_no;
        $assessment_id = $req->assessment_id;
        $partial_status = $req->partial_status;
        $manifest_id = $req->manifest_id;

        $data = DB::select('SELECT assessments.* 
            FROM assessments WHERE assessments.id=? 
            AND assessments.port_id=?', [$assessment_id, $port_id]);

        $assessmentDetails = DB::select('SELECT * FROM assesment_details AS ad 
                                        WHERE ad.manif_id=? AND ad.partial_status=? AND ad.port_id=?', [$manifest_id, $partial_status, $port_id]);
        $getAssessmentTblData = DB::select('SELECT yearly_serial, assessment_values, 
                                            warehouse_details, charge_year
                                            FROM assessments WHERE manifest_id=? 
                                            AND partial_status=? AND port_id = ? 
                                            ORDER BY id DESC LIMIT 1', [$manifest_id, $partial_status, $port_id]);


//        if ($getAssessmentTblData[0]->warehouse_details == null) {
//            $warehouse_details = json_decode($this->assessment_base_controller->getWarehouseDetails($mani_no, $partial_status));
//
//            $warehouse_rent_day = $warehouse_details->warehouse_rent_day;
//            $item_wise_yard_charge = $warehouse_details->item_wise_yard_details_charge;
//            $item_wise_shed_charge = $warehouse_details->item_wise_shed_details_charge;
//            $charge_start_day = $warehouse_details->charge_start_day;
//            $first_slab_day = $warehouse_details->first_slab_day;
//            $second_slab_day = $warehouse_details->second_slab_day;
//            $third_slab_day = $warehouse_details->third_slab_day;
//            $date_of_unloading = $warehouse_details->receive_date;
//        } else {
//            $warehouse_details = json_decode($getAssessmentTblData[0]->warehouse_details);
//            $warehouse_rent_day = $warehouse_details->warehouse_rent_day;
//            $item_wise_yard_charge = $warehouse_details->item_wise_yard_details_charge;
//            $item_wise_shed_charge = $warehouse_details->item_wise_shed_details_charge;
//            $charge_start_day = $warehouse_details->charge_start_day;
//            $first_slab_day = $warehouse_details->first_slab_day;
//            $second_slab_day = $warehouse_details->second_slab_day;
//            $third_slab_day = $warehouse_details->third_slab_day;
//            $date_of_unloading = $warehouse_details->receive_date;
//        }

        return json_encode([$data, $assessmentDetails]);
    }

    public function getAssessementDetails($manifest, $truck, $year, $assessment_id, $partial_status)
    {
        $port_id = Session::get('PORT_ID');
        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $todayWithTime = date('Y-m-d h:i:s a');
        $TotalAssessmentValue = 0;


        $manifest_id = DB::table('manifests AS m')//check if manifest in record
        ->where('m.manifest', $mani_no)
            ->where('m.port_id', $port_id)
            ->select('m.id')
            ->get();

        if ($manifest_id == '[]') {

            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not found in our record!']);
        }

        $assessmentSavedOrNot = DB::table('assesment_details AS a')
            ->where('a.manif_id', $manifest_id[0]->id)
            ->where('a.partial_status', $partial_status)
            ->where('a.port_id', $port_id)
            ->select('a.id')
            ->get();

        // dd($assessmentSavedOrNot);
        if ($assessmentSavedOrNot == '[]') {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! assessment is not done for manifest- ' . $mani_no]);
        }
//==================================ASSESSMENT DETAILS==========================================
        $totalWarehouseRent = 0;
        //Load------------------
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
        $loading_shifting = null;

        //Handling and other Due===================
        //entrace fee----------
        $entranceTotalForeignTruck = 0;
        $entranceFeeForeign = 0;
        $totalEntranceFeeForeign = 0;

        $entranceTotalLocalTruck = 0;
        $entranceFeeLocalTruck = 0;
        $totalEntranceFeeLocal = 0;

        $totalLocalVan = 0;
        $entranceFeeVan = 0;
        $totalLocalVanEntranceFee = 0;

        //Carpanter Charge------------
        $carpenterOPPackages = null;
        $carpenterChargesOpenClose = null;
        $totalCarpenterChargesOpenClose = 0;

        $carpenterRepairPackages = null;
        $carpenterChargesRepair = null;
        $totalCarpenterChargesRepair = 0;

        //Holiday Charge-----------
        $holidayCharge = 0;
        $holidayTotalTruck = 0;
        $totalHolidayCharge = 0;
        $holiday = null;

        //Haltage  Charge -------------
        $haltage_truck_foreign = 0;
        $haltage_day_foreign = 0;
        $haltage_charge_foreign = 0;
        $haltage_total_foreign = 0;

        $haltage_truck_local = 0;
        $haltage_day_local = 0;
        $haltage_charge_local = 0;
        $haltage_total_local = 0;


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

        //Night Charge
        $NightTotalTruck = 0;
        $NightCharge = 0;
        $TotalNightCharge = 0;

        $assessmentDetails = DB::select('SELECT * FROM assesment_details AS ad 
                                        WHERE ad.manif_id=? AND ad.partial_status=? AND ad.port_id=?', [$manifest_id[0]->id, $partial_status, $port_id]);


        $getAssessmentTblData = DB::select('SELECT yearly_serial, assessment_values, 
                                            warehouse_details, charge_year
                                            FROM assessments WHERE manifest_id=? 
                                            AND partial_status=? AND port_id = ? 
                                            ORDER BY id DESC LIMIT 1', [$manifest_id[0]->id, $partial_status, $port_id]);

        $holiday = DB::select('SELECT SUM(a.tcharge) TotalHolidayCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=16 AND a.manif_id=? AND a.port_id=? AND a.partial_status=?', [$manifest_id[0]->id, $port_id, $partial_status]);

        $get_foreign_haltage_charge = $this->assessment_base_controller->getForeignTruckHaltageDetails($mani_no);

        $get_local_truck_haltage_charge = $this->assessment_base_controller->getLocalTruckHaltageDetails($mani_no, $partial_status);


        if ($assessmentDetails) {
            foreach ($assessmentDetails as $k => $v) {
                $subHeadId = $v->sub_head_id;

                if ($subHeadId == 2) {//WareHouse Rent
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
                //return $offloadEquipShiftingFlag;
                if ($subHeadId == 8) {//load labour
                    $loadLabour = $v->unit;
                    $loadLabourCharge = $v->charge_per_unit;
                    $TotalForloadLabour = $v->tcharge;
                }

                if ($subHeadId == 10) {//loading equip
                    $loadEquip = $v->unit;
                    $loading_shifting = $v->other_unit;//if shifting or not
                    $loadingEquipCharge = $v->charge_per_unit;
                    $TotalForloadEquip = $v->tcharge;
                }
                if ($subHeadId == 26) {//Entrance Fee-Foreign
                    $entranceTotalForeignTruck = $v->unit;
                    $entranceFeeForeign = $v->charge_per_unit;
                    $totalEntranceFeeForeign = $v->tcharge;
                }

                if ($subHeadId == 28) {//Entrance Fee-local truck
                    $entranceTotalLocalTruck = $v->unit;
                    $entranceFeeLocalTruck = $v->charge_per_unit;
                    $totalEntranceFeeLocal = $v->tcharge;
                }
                if ($subHeadId == 228) {//Entrance Fee-local truck
                    $totalLocalVan = $v->unit;
                    $entranceFeeVan = $v->charge_per_unit;
                    $totalLocalVanEntranceFee = $v->tcharge;
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
                    $haltage_day_foreign = $v->other_unit;
                    $haltage_charge_foreign = $v->charge_per_unit;
                    $haltage_total_foreign = $v->tcharge;
                }

                if ($subHeadId == 44) {// Haltage Charge-Local
                    $haltage_truck_local = $v->unit;
                    $haltage_day_local = $v->other_unit;
                    $haltage_charge_local = $v->charge_per_unit;
                    $haltage_total_local = $v->tcharge;
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
                if ($subHeadId == 38) { //Nightcharge-Foreign
                    $NightTotalTruck = $v->unit;
                    $NightCharge = $v->charge_per_unit;
                    $TotalNightCharge = $v->tcharge;
                }
                if ($subHeadId == 52) { //Document Charge
                    $totalDocumentCharge = $v->tcharge;
                    $number_of_documents = $v->unit;
                    $document_charge = $v->charge_per_unit;
                }
            }
        }


        if($getAssessmentTblData[0]->warehouse_details == null) {
            $warehouse_details = json_decode($this->assessment_base_controller->getWarehouseDetails($mani_no, $partial_status));
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        } else {
            $warehouse_details = json_decode($getAssessmentTblData[0]->warehouse_details);
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        }

        if ($getAssessmentTblData[0]->assessment_values != null) {
            $assessment_values = json_decode($getAssessmentTblData[0]->assessment_values);
            $weight = $assessment_values->weight;
            $good_description = $assessment_values->good_description;
            $self_flag = $assessment_values->self_flag;
            $vat_flag = $assessment_values->vat;
        } else {
            return 'Please Contact to Maintanance Team!';
        }

        $manifestDetails = $this->assessment_base_controller->manifestDetailsForAssessment($mani_no, $partial_status);


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

        $TotalAssessmentValue += $totalEntranceFeeForeign;
        $TotalAssessmentValue += $totalEntranceFeeLocal;
        $TotalAssessmentValue += $totalLocalVanEntranceFee;

//Carpenter Charges------
        $TotalAssessmentValue += $totalCarpenterChargesOpenClose;
        $TotalAssessmentValue += $totalCarpenterChargesRepair;

//Weighment measurement  Charges
        $TotalAssessmentValue += $totalWeighmentChargeForeign;
        $TotalAssessmentValue += $totalweightmentChargesLocal;

        //holiday charge
        $TotalAssessmentValue += $holiday[0]->TotalHolidayCharge;

        //Night Charge
        $TotalAssessmentValue += $TotalNightCharge;

        //Haltage Charge
        $TotalAssessmentValue += $haltage_total_foreign;
        $TotalAssessmentValue += $haltage_total_local;

        //
        //Document Charge
        $TotalAssessmentValue += $totalDocumentCharge;


        //dd($totalWarehouseRent);
        //Tatal Calculation
        $TotalAssessmentValue = ceil($TotalAssessmentValue);//number_format(, 2, '.', '');
        if ($vat_flag == 1 || is_null($vat_flag)) {
            $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        } else {
            $Vat = 0;
        }
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);

        return view('default.assessment-admin.assessment-details', [
            'todayWithTime' => $todayWithTime,
            'partial_status' => $partial_status,
            //manifest details
            'ManifestDate' => $manifestDetails[0]->manifest_date != null ? $manifestDetails[0]->manifest_date : '',
            'manifestNo' => $manifestDetails[0]->manifest_no,
            'manifest_id' => $manifestDetails[0]->manifest_id,
            'bill_entry_no' => $manifestDetails[0]->bill_entry_no,
            'bill_entry_date' => $manifestDetails[0]->bill_entry_date,
            'importer' => $manifestDetails[0]->importer,
            'exporter' => $manifestDetails[0]->exporter,
            'cnf_name' => $manifestDetails[0]->cnf_name,
            'package_type' => $manifestDetails[0]->package_type,
            'package_no' => $manifestDetails[0]->package_no,
            'custom_realise_order_No' => $manifestDetails[0]->custom_realise_order_No,
            'custom_realise_order_date' => $manifestDetails[0]->custom_realise_order_date,
            'description_of_goods' => $good_description,
            'self_flag' => $self_flag,
            'chargeable_ton' => $weight,
            //Warehouse Rent Details--------------------
            "warehouse_rent_for_items" => $warehouse_rent_for_items,
            'free_items' => $free_items,
            'item_wise_shed_details' => $item_wise_shed_details,
            'item_wise_yard_details' => $item_wise_yard_details,
            'TotalSlabCharge' => $totalWarehouseRent,


            //'receive_date' => $warehouseDetail['receive_date'],
            'receive_date' => $receive_date,
            'delivery_date' => $delivery_date,
            'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,

            //Offload--------------
            'OffloadLabour' => $OffloadLabour,
            'OffloadLabourCharge' => $OffloadLabourCharge,
            'TotalForOffloadLabour' => $TotalForOffloadLabour,

            'OffLoadingEquip' => $OffLoadingEquip,
            'OffLoadingEquipCharge' => $OffLoadingEquipCharge,
            'offloadEquipShiftingFlag' => $offloadEquipShiftingFlag > 0 ? true : false,
            'TotalForOffloadEquip' => $TotalForOffloadEquip,

            //Load-----------
            'loadLabour' => $loadLabour,
            'loadLabourCharge' => $loadLabourCharge,
            'TotalForloadLabour' => $TotalForloadLabour,

            'loadEquip' => $loadEquip,
            'loadingEquipCharge' => $loadingEquipCharge,
            'TotalForloadEquip' => $TotalForloadEquip,
            'loading_shifting' => $loading_shifting > 0 ? true : false,
            //Entrance fee-------
            'entranceFeeForeign' => $entranceFeeForeign,
            'totalForeignTruckEntranceFee' => $totalEntranceFeeForeign,
            'entranceTotalForeignTruck' => $entranceTotalForeignTruck,

            'entranceTotalLocalTruck' => $entranceTotalLocalTruck,//  $getAssessmentTblData[0]->local_truck ? $getAssessmentTblData[0]->local_truck : $getAssessmentTblData[0]->transport_truck,
            'entranceFeeLocalTruck' => $entranceFeeLocalTruck,
            'totalLocalTruckEntranceFee' => $totalEntranceFeeLocal,

            'entranceTotalLocalVan' => $totalLocalVan,
            'entranceFeeVan' => $entranceFeeVan,
            'totalLocalVanEntranceFee' => $totalLocalVanEntranceFee,
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
            'NightTotalTruck' => $NightTotalTruck,
            'NightCharge' => $NightCharge,
            'TotalNightCharge' => $TotalNightCharge,

//Holiday Charge
            'HolidayTotalTruck' => $holiday[0]->TotalTruck,
            'HolidayCharge' => $holiday[0]->charge_per_unit,
            'TotalHolidayCharge' => $holiday[0]->TotalHolidayCharge,

//Haltage Charge

            'haltage_truck_foreign' => $haltage_truck_foreign,
            'haltage_charge_foreign' => $haltage_charge_foreign,
            'haltage_total_foreign' => $haltage_total_foreign,
            'haltage_day_foreign' => $haltage_day_foreign,
            'haltage_truck_local' => $haltage_truck_local,
            'haltage_day_local' => $haltage_day_local,
            'haltage_charge_local' => $haltage_charge_local,
            'haltage_total_local' => $haltage_total_local,


            'foreign_haltage_details' => $get_foreign_haltage_charge,
            'local_truck_haltage_details' => $get_local_truck_haltage_charge,




            //$documentCharge
            'totalDocumentCharge' => $totalDocumentCharge,
            'number_of_documents' => $number_of_documents,
            'document_charge' => $document_charge,

//Total Calculation

            'assessment_id' => $assessment_id,
            'TotalAssessmentValue' => $TotalAssessmentValue,
            'Vat' => $Vat,
            'vat_flag' => $vat_flag,
            'TotalAssessmentWithVat' => $TotalAssessmentWithVat,
        ]);

    }

    public function assessmentDone($Mani_id, $assessment_id, $partial_status)
    {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;
        $time = date('Y-m-d H:i:s');
        $postAssessmentDone = DB::table('assessments')
            ->where('assessments.id', $assessment_id)
            ->update([
                'assessments.done' => 1,
                'assessments.done_by' => $user_id,
                'assessments.done_at' => $time
            ]);

        $checkAssDone = DB::table('transactions AS t')
            ->where('t.manif_id', $Mani_id)
            ->where('t.port_id', $port_id)
            ->get()->first();

        DB::table('delivery_requisitions')
            ->where('manifest_id', $Mani_id)
            ->where('port_id', $port_id)
            ->where('partial_status', $partial_status)
            ->update([
                'gate_pass_no' => rand(1000, 10000),
                'gate_pass_by' => $user_id,
                'gate_pass_at' => $time
            ]);

        if ($checkAssDone) {//old transactions / old challan
            DB::table('transactions')->where('manif_id', $Mani_id)->where('port_id', $port_id)->delete();
        } else {
            // DB::table('transactions')->where('manif_id', $Mani_id)->delete();
            // DB::table('challan_details')->where('manf_id', $Mani_id)->delete();
        }
        $challan_id = null;
        //Challan No generate===============
        $CallanNoCheck = DB::select("SELECT ch.id FROM challan_details AS ch WHERE ch.manf_id = ? AND ch.port_id=?", [$Mani_id, $port_id]);
        if (!count($CallanNoCheck)) {//challan not found

            $createdBy = Auth::user()->name;
            $createdTime = date('Y-m-d H:i:s');
            $getChallValue = "CH";
            $getMaxIdChalan = DB::select("SELECT MAX(CAST((SUBSTRING(challan_details.challan_no, 3)) AS UNSIGNED)) AS challan_no FROM challan_details  WHERE challan_details.port_id=?", [$port_id]);
            $getNumberOfChallan = DB::select("SELECT COUNT(challan_details.id) AS challan_count FROM challan_details WHERE DATE(challan_details.created_at) = DATE(NOW()) AND challan_details.port_id=?", [$port_id]);
            $getNum = $getNumberOfChallan[0]->challan_count + 1;

            if (!is_null($getMaxIdChalan[0]->challan_no)) {
                $challanNumber = $getMaxIdChalan[0]->challan_no + 1;
            } else {
                $challanNumber = 1;
            }
            $CallanNo = $getChallValue . sprintf("%06d", $challanNumber);
            $ChallanNo1 = $CallanNo . "/" . $getNum;

            $challan_id = DB::table('challan_details')
                ->insertGetId([
                    'manf_id' => $Mani_id,
                    'challan_no' => $ChallanNo1,
                    'created_at' => $createdTime,
                    'creator' => $createdBy,
                    'created_by' => $port_id
                ]);
        } else {
            $challan_id = $CallanNoCheck[0]->id;
        }

        //return $challan_id;
//Warehouse  fee==========
        $warehouseRent = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 2)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        //return;
        if ($warehouseRent != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 2,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $warehouseRent[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        // return;
        //Handling Charge==============
        $handlingOLabour = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 4)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        //---Offload-Labour
        if ($handlingOLabour != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 4,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingOLabour[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );

        }
        //----OffLoad-Equip
        $handlingoffEq = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 6)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($handlingoffEq != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 6,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingoffEq[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }

        //----load-Labour
        $handlingLoadLabour = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 8)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($handlingLoadLabour != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 8,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingLoadLabour[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        // return;
        //----load-Equip
        $handlingLoadEq = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 10)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();

        if ($handlingLoadEq != '[]') {
            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 10,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingLoadEq[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        //return 'ok';
//Entrance fee==========
        $IndTruckEntrance = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 26)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();

        $BdTruckEntrance = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 28)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        $BdVanEntrance = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 228)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        //Foreign_Truck-----
        if ($IndTruckEntrance != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 26,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $IndTruckEntrance[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        if ($BdTruckEntrance != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 28,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $BdTruckEntrance[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        if ($BdVanEntrance != '[]') {
            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 228,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $BdVanEntrance[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        //carpenter charge==================
        //opening / closing----
        $carpenterOpening = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 30)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();

        if ($carpenterOpening != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 30,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $carpenterOpening[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        //Repair----
        $carpenterRepair = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 32)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();

        if ($carpenterRepair != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 32,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $carpenterRepair[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }

        //Holiday charge==================

        $holiday_Charge_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 42)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($holiday_Charge_foreign != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 42,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $holiday_Charge_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
//----local---
        $holiday_Charge_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 40)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($holiday_Charge_local != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 40,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $holiday_Charge_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        //Night charge==================
        $Night_charges_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 38)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($Night_charges_foreign != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 38,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $Night_charges_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
        //Night_charges==========
//----local---
        $Night_charges_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 36)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($Night_charges_local != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 36,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $Night_charges_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
//Haltage Charge==============

        //----foreign
        $HaltageCharge_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 46)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($HaltageCharge_foreign != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 46,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $HaltageCharge_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
//----local---
        $HaltageCharge_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 44)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();


        if ($HaltageCharge_local != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 44,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $HaltageCharge_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id

                ]
            );
        }
//weighbridge charge


        //----foreign
        $weighment_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 50)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($weighment_foreign != '[]') {
            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 50,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $weighment_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }
//----local---
        $weighment_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 48)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();
        if ($weighment_local != '[]') {


            DB::table('transactions')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 48,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $weighment_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }

        $document_charge = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 52)
            ->where('a.port_id', $port_id)
            ->where('a.partial_status', $partial_status)
            ->select('a.tcharge')
            ->get();

        if ($document_charge != '[]') {
            DB::table('transactions')->insert(
                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 52,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $document_charge[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->id,
                    'port_id' => $port_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]
            );
        }

        if ($postAssessmentDone == true) {
            return "Success";
        }
    }

    public function getPreviousCompletedAssessment()
    {
        if (Auth::user()->role->id == 23) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $getPreviousCompletedAssessment = DB::select('SELECT assessments.id,assessments.manifest_id AS manifestId, manifests.manifest, manifests.be_no, 
manifests.custom_release_order_no, manifests.manifest_date, manifests.exporter_name_addr, manifests.cnf_name, 
assessments.created_at, assessments.done, /*assessments.good_description AS goodsName,*/
(SELECT CEIL(CEIL(SUM(assesment_details.tcharge)) + (CEIL(SUM(assesment_details.tcharge))*15/100)) 
    FROM assesment_details WHERE assesment_details.manif_id=assessments.manifest_id) AS totalAssessmentValue,
/*(SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id) AS importerName,*/
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
WHERE assessments.id IN (
  SELECT MAX(id)
  FROM assessments
  GROUP BY manifest_id)
            AND manifests.transshipment_flag = ?
            AND assessments.done = 0
            AND DATE(assessments.created_at) != CURDATE()
            ORDER BY assessments.created_at ASC', [$flag]);
        return json_encode($getPreviousCompletedAssessment);

    }


    public function assessmentAdminTruckReport()
    {
        return view('default.assessment-admin.truck-report-view');
    }

    public function assessmentAdminWeighbridgeReport()
    {
        return view('default.assessment-admin.weighbridge-report-view');
    }

    public function assessmentAdminPostingReport()
    {
        return view('default.assessment-admin.posting-report-view');
    }

    public function assessmentAdminWarehouseReceiveReport()
    {
        return view('default.assessment-admin.warehouse-receive-report-view');
    }

    public function assessmentAdminWarehouseDeliveryReport()
    {
        return view('default.assessment-admin.warehouse-delivery-report-view');
    }
}
