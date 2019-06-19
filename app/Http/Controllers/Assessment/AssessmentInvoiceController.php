<?php

namespace App\Http\Controllers\Assessment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use PDF;
use Auth;
use App\Http\Controllers\GlobalFunctionController;
use App\Http\Controllers\AssessmentBaseController;
use Session;
use Response;

class AssessmentInvoiceController extends Controller
{
    private $globalFunctionController;
    private $assessment_base_controller;
    public function __construct(GlobalFunctionController $globalFunctionController, AssessmentBaseController $assessment_base_controller) {
        $this->globalFunctionController = $globalFunctionController;
        $this->assessment_base_controller = $assessment_base_controller;

    }


    public function assessmentInvoice() {
    	return view('default.assessment.assessment-invoice');
    }

    public function getPartialList($manifest, $truck, $year) {
        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $port_id = Session::get('PORT_ID');
        $partial_number = $this->assessment_base_controller->getLastPartialStatus($mani_no);
        if(is_null($partial_number[0]->max_partial_number)) {
            return Response::json(['message' => 'Delivery Request not done!'], 203);
        }
        return $partial_number[0]->max_partial_number;
    }

    public function getAssessmentInvoiceReport(Request $r) {
        $partial_status = $r->partial_status_for_challan;
        //return $r->partial_status_for_challan;
        $todayWithTime = date('Y-m-d'); // h:i:s a
        $port_id = Session::get('PORT_ID');
        $checkAssessment = DB::select("SELECT COUNT(assesment_details.id) AS assesment_status 
                                        FROM assesment_details
                                        INNER JOIN manifests ON assesment_details.manif_id=manifests.id
                                        WHERE manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);

        if($checkAssessment[0]->assesment_status == 0) {
            $errorMessage = "Assessment Not Saved! Please Save Assessment.";
            return view('default.assessment.assessment-not-done', compact('errorMessage'));
        }

        $CallanNoCheck = DB::select("SELECT challan_details.challan_no FROM manifests
                              INNER JOIN challan_details ON challan_details.manf_id = manifests.id
                              WHERE manifests.manifest=? AND manifests.port_id=? AND challan_details.port_id=?",[$r->manifest, $port_id, $port_id]);
           // return $CallanNoCheck[0]->challan_no;

            if(!count($CallanNoCheck)){
                $createdBy = Auth::user()->id;
                $createdTime = date('Y-m-d H:i:s');
                $getChallValue = "CH";
                $getMaxIdChalan = DB::select("SELECT MAX( CAST(( SUBSTRING(challan_details.challan_no, 3) ) AS UNSIGNED) ) AS challan_no FROM challan_details WHERE challan_details.port_id=?",[$port_id]);
                $getNumberOfChallan = DB::select("SELECT COUNT(challan_details.id) AS challan_count FROM challan_details WHERE DATE(challan_details.created_at) = DATE(NOW()) AND challan_details.port_id=?",[$port_id]);
                $getNum = $getNumberOfChallan[0]->challan_count + 1;
                if(!is_null($getMaxIdChalan[0]->challan_no)){
                    $challanNumber = $getMaxIdChalan[0]->challan_no + 1;
                }else {
                    $challanNumber=1;
                }
                $CallanNo = $getChallValue.sprintf("%05d",$challanNumber);
                $ChallanNo1 = $CallanNo."/".$getNum;
                $manifestId = DB::select("SELECT m.id FROM manifests AS m WHERE m.manifest=? AND m.port_id=?",[$r->manifest, $port_id]);
                $randomInsertChalan = DB::table('challan_details')
                    ->insert([
                        'manf_id' =>$manifestId[0]->id,
                        'challan_no' =>$ChallanNo1,
                        'created_at' => $createdTime,
                        'created_by' => $createdBy,
                        'port_id' => $port_id
                    ]);
            }

        $manifestReport = DB::select("SELECT manifests.id, manifests.manifest, manifests.manifest_date,manifests.be_no AS bill_of_entry_no, manifests.be_date AS bill_of_entry_date,manifests.package_no, manifests.exporter_name_addr AS consigner, (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=manifests.vatreg_id) AS consignee,
            (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
            JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
            JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
            JOIN manifests AS ma ON ma.id = ter.manf_id
            WHERE ma.id = manifests.id) AS posted_yard_shed
            FROM manifests
            WHERE manifests.manifest=? AND manifests.port_id=?",[$r->manifest, $port_id]);


        $assessmentDetails = DB::select("SELECT /*assessments.perishable_flag,*/ assessments.warehouse_details,
                            assessments.yearly_serial, assessments.assessment_values, assessments.partial_status
                            FROM assessments
                            JOIN manifests ON manifests.id = assessments.manifest_id
                            WHERE manifests.manifest=? AND manifests.port_id=? AND assessments.port_id=? AND assessments.partial_status=? ORDER BY assessments.id DESC LIMIT 1",[$r->manifest, $port_id, $port_id, $partial_status]);
        
        $foreignTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 26 AND 
                                    manifests.manifest=? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);


        $localTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 28 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);

        $localVan = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 228 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //====================Carpenter Charge=======================
        $carpenterChargesOpenningOrClosing = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 30 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $carpenterChargesRepair = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 32 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //====================Carpenter Charge===========================
        //====================Holiday Charge===========================
        $holidayChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 42 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $holidayChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 40 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //====================Holiday Charge===========================
        //====================Night Charge===========================
		$nightChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 38 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $nightChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 36 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //====================Night Charge===========================
        //====================Holtage Charge===========================
		$holtageChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 46 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $holtageChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 44 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //====================Holtage Charge===========================

        //return $holtageChargesFT;

		$documentationCharges = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 52 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //=================WeightMent Charge=======================
		$weighmentChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 50 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $weighmentChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 48 AND 
                                    manifests.manifest = ? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        //=================WeightMent Charge=======================

        $offLoadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 4 AND 
                                    manifests.manifest =? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $offLoadingEquipment = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 6 AND 
                                    manifests.manifest =? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $loadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 8 AND 
                                    manifests.manifest =? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);
        $loadingEquip = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 10 AND 
                                    manifests.manifest =? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);

        $totalWarehouseRent = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 2 AND 
                                    manifests.manifest =? AND manifests.port_id=? AND assesment_details.port_id=? AND assesment_details.partial_status=?",[$r->manifest, $port_id, $port_id, $partial_status]);


  //    //=====================WAREHOUSE CHARGE START==========================
        if($assessmentDetails[0]->warehouse_details == null ) {
            $warehouse_details = json_decode($this->assessment_base_controller->getWarehouseDetails($r->manifest, $partial_status));
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        } else {
            $warehouse_details = json_decode($assessmentDetails[0]->warehouse_details);
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        }
        if($assessmentDetails[0]->assessment_values != null) {
            $assessment_values = json_decode($assessmentDetails[0]->assessment_values);
            $weight = $assessment_values->weight;
            $good_description = $assessment_values->good_description;
            $vat_flag = $assessment_values->vat;
            $perishable_flag = $assessment_values->perishable_flag;
        } else {
            $weight = null;
            $good_description = null;
            $vat_flag = null;
            $perishable_flag = 0;
        }
       // dd($assessment_values);
//        if(!is_null($assessmentDetails[0]->perishable_flag)) {
//            $perishable_flag = $assessmentDetails[0]->perishable_flag;
//        } else {
//           $perishable_flag = 0;
//        }

        $pdf = PDF::loadView('default.assessment.reports.challan-report',[
            'todayWithTime' => $todayWithTime,
            'weight' => $weight,
            'good_description' => $good_description,
            'perishable_flag' => $perishable_flag,
            'vat_flag' => $vat_flag,
            'manifestReport' => $manifestReport,
            'foreignTruck' => $foreignTruck,
            'localTruck' => $localTruck,
            'localVan' => $localVan,
            //carpenter
            'carpenterChargesOpenningOrClosing' => $carpenterChargesOpenningOrClosing,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            //carpenter
            //holiday
            'holidayChargesFT' => $holidayChargesFT,
            'holidayChargesLT' => $holidayChargesLT,
            //holiday
            //night
            'nightChargesFT' => $nightChargesFT,
            'nightChargesLT' => $nightChargesLT,
            //night
            //holtage
            'holtageChargesFT' => $holtageChargesFT,
            'holtageChargesLT' => $holtageChargesLT,
            //holtage
            'documentationCharges' => $documentationCharges,
            //weightment
            'weighmentChargesFT' => $weighmentChargesFT,
            'weighmentChargesLT' => $weighmentChargesLT,
            //weightment
            'offLoadingLabour' => $offLoadingLabour,
            'offLoadingEquipment' => $offLoadingEquipment,
            'loadingLabour' => $loadingLabour,
            'loadingEquip' => $loadingEquip,
            //WareHouse
            "warehouse_rent_for_items" => $warehouse_rent_for_items,
            'free_items' => $free_items,
            'item_wise_shed_details' => $item_wise_shed_details,
            'item_wise_yard_details' => $item_wise_yard_details,
            'TotalSlabCharge' => $totalWarehouseRent,


            //'receive_date' => $warehouseDetail['receive_date'],
            'receive_date' => $receive_date,
            'delivery_date' => $delivery_date,

            'totalWarehouseRent' => $totalWarehouseRent,
            //WareHouse
            //challan
            'CallanNo'=>isset($CallanNo1) ? $CallanNo1 : 0,
            'CallanNoCheck'=>$CallanNoCheck
//            'randomNumber' => isset($CallanNo) ? $CallanNo : 0
            //Challan
        ])
            //->setPaper('B4', 'landscape');
            ->setPaper([0, 0, 980.661, 1009], 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('ChallanPDF.pdf');



    }
	//BANK


	
    public function getAssessmentInvoicePDFBank($manifest,$truck) {

        $mani_no = (string)$manifest."/".(string)$truck;
        $todayWithTime = date('Y-m-d'); // h:i:s a
        $checkAssessment = DB::select("SELECT COUNT(assesment_details.id) AS assesment_status 
                                        FROM assesment_details
                                        INNER JOIN manifests ON assesment_details.manif_id=manifests.id
                                        WHERE manifests.manifest = ?",[$mani_no]);

        if($checkAssessment[0]->assesment_status == 0) {
            return view('Assessment.AssessmentNotDone');
        }



//get or create chalan no
        /* $manifest = DB::select('SELECT c.manf_id FROM challan_details c
         JOIN manifests m ON m.id=c.manf_id
         WHERE m.manifest=?',[$r->manifest]);

         //$mani_id=$manifest[0]->manf_id;

         $ChalanNo=0;

         if ($manifest='[]')//not found -should create no
         {

             $ChalanNo= mt_rand(100000, 999999);

             DB::table('challan_details')->insert(
                 [
                     'manif_id' => $manifest[0]->manf_id,
                     'challan_no'=>$ChalanNo,
                     'challan_dt' => date('Y-m-d H:i:s'),
                     'creator' => Auth::user()->username
                 ]
             );



         }
         else{
             $chalan = DB::select('SELECT c.challan_no FROM challan_details c
         WHERE c.manf_id=?',[$manifest[0]->manf_id]);

             $ChalanNo=$chalan[0]->challan_no;
         }*/



        $manifestReport = DB::select("SELECT manifests.manifest, manifests.manifest_date, manifests.be_no AS bill_of_entry_no, manifests.be_date AS bill_of_entry_date,manifests.id,
								manifests.exporter_name_addr AS consigner, (SELECT vatregs.NAME FROM  vatregs WHERE vatregs.id=manifests.vatreg_id) AS consignee,
								(SELECT truck_entry_regs.truck_posted_yard_shed FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=manifests.id  
								ORDER BY truck_entry_regs.id DESC LIMIT 1) AS posted_yard_shed FROM manifests WHERE manifests.manifest=?",[$mani_no]);
        $goodsNameTotalPkgMaxNet = DB::select("SELECT *
                                                FROM( 
                                                SELECT
                                                     m.package_no,
                                                (SELECT cargo.cargo_name FROM  cargo_details AS cargo WHERE cargo.id=m.goods_id) AS description_of_goods,
                                                (
                                                    CASE
                                                    WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                    ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                    END
                                                ) AS max_Net_Weight
                                                FROM manifests m  
                                                WHERE m.manifest=? ) AS final",[$mani_no]);


        $foreignTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 26 AND 
                                    manifests.manifest = ?",[$mani_no]);


        $localTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 28 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //====================Carpenter Charge=======================
        $carpenterChargesOpenningOrClosing = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 30 AND 
                                    manifests.manifest = ?",[$mani_no]);
        $carpenterChargesRepair = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 32 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //====================Carpenter Charge===========================
        //====================Holiday Charge===========================
        $holidayChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 42 AND 
                                    manifests.manifest = ?",[$mani_no]);
        $holidayChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 40 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //====================Holiday Charge===========================
        //====================Night Charge===========================
        $nightChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 38 AND 
                                    manifests.manifest = ?",[$mani_no]);
        $nightChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 36 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //====================Night Charge===========================
        //====================Holtage Charge===========================
        $holtageChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 46 AND 
                                    manifests.manifest = ?",[$mani_no]);
        $holtageChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 44 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //====================Holtage Charge===========================

        //return $holtageChargesFT;

        $documentationCharges = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 52 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //=================WeightMent Charge=======================
        $weighmentChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 50 AND 
                                    manifests.manifest = ?",[$mani_no]);
        $weighmentChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 48 AND 
                                    manifests.manifest = ?",[$mani_no]);
        //=================WeightMent Charge=======================

        $offLoadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 4 AND 
                                    manifests.manifest =?",[$mani_no]);
        $offLoadingEquipment = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 6 AND 
                                    manifests.manifest =?",[$mani_no]);
        $loadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 8 AND 
                                    manifests.manifest =?",[$mani_no]);
        $loadingEquip = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 10 AND 
                                    manifests.manifest =?",[$mani_no]);


        //    //=====================WAREHOUSE CHARGE START==========================
        $globalfunctionCtrl = new GlobalFunctionController();
        $w= DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,posted_yard_shed,package_no,chalanNO
             FROM(SELECT m.goods_id,m.package_no,m.id as chalanNO,
            (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id DESC LIMIT 1)AS truckentry_datetime,
            (SELECT truck_entry_regs.posted_yard_shed FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id DESC LIMIT 1)AS posted_yard_shed,
          /*  (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1)AS receive_date,*/
            (SELECT truck_deliverys.delivery_dt FROM  truck_deliverys WHERE truck_deliverys.manf_id=m.id ORDER BY truck_deliverys.id DESC LIMIT 1)AS deliver_date,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1)AS ReceiveWeight
            FROM manifests m  WHERE m.manifest=?)t',[$mani_no]);


        //return $w;
        $receive_date=$w[0]->receive_date;
        $deliver_date=$w[0]->deliver_date;
        $goods_id=$w[0]->goods_id;
        $posted_yard_shed=$w[0]->posted_yard_shed;
        $package_no=$w[0]->package_no;
        $ReceiveWeight=ceil($w[0]->ReceiveWeight/1000);
        $wareHouseRentDay= $globalfunctionCtrl->number_of_working_days($receive_date, $deliver_date);
        if ($wareHouseRentDay<=3)
        {
            $wareHouseRentDay=0;
            $freeEndDay=$deliver_date;
            $ChargeStartDay=null;
        }
        else{
            $wareHouseRentDay=$wareHouseRentDay-3;
            $freeEndDay=$globalfunctionCtrl->GetFreedayEndForeWarehouseRent($receive_date);//return $receive_date + 3 days including holidays
            $ChargeStartDay=$globalfunctionCtrl->ChargeStartDay($freeEndDay);
        }
        //get slab charge variable globaly
        $firstSlabCharge=0;
        $secondSlabCharge=0;
        $thirdSlabCharge=0;

        $firstSlabDay=0;
        $secondSlabDay=0;
        $thirdSlabDay=0;


        if ($wareHouseRentDay >= 1 && $wareHouseRentDay <= 21) {//1 slab will be calculated------------------1

            $firstSlabCharge= $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,1);
            // $secondSlabCharge=  0;
            // $thirdSlabCharge=  0;
            $firstSlabDay=$wareHouseRentDay;
        }

        else if ($wareHouseRentDay >= 22 && $wareHouseRentDay <= 50) {//2 slab will be calculated------------------2

            $firstSlabCharge=  $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,1);
            $secondSlabCharge=  $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,2);
            // $thirdSlabCharge=  0;

            $firstSlabDay=21;
            $secondSlabDay=($wareHouseRentDay-21);
        }
        else if($wareHouseRentDay >= 51) {//3 slab will be calculated---------------------------------3
            $firstSlabCharge=  $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,1);
            $secondSlabCharge=  $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,2);
            $thirdSlabCharge=  $globalfunctionCtrl->SlabCharge($goods_id,$posted_yard_shed,3);

            $firstSlabDay=21;
            $secondSlabDay=29;
            $thirdSlabDay=($wareHouseRentDay-21-29);

        }
        else{
            $firstSlabCharge=  0;
            $secondSlabCharge= 0;
            $thirdSlabCharge=  0;

            $firstSlabDay=0;
            $secondSlabDay=0;
            $thirdSlabDay=0;
        }

        $warehouse =   array(
            'WareHouseRent' => $wareHouseRentDay,
            'FreeEndDate'=>$freeEndDay,
            'ChargeStartDay'=>$ChargeStartDay,

            'FirstSlabDay'=>$firstSlabDay,
            'SecondSlabDay'=>$secondSlabDay,
            'thirdSlabDay'=>$thirdSlabDay,

            'FirstSlabCharge' => $firstSlabCharge,
            'SecondSlabCharge' =>$secondSlabCharge,
            'ThirdSlabCharge'=>$thirdSlabCharge,

            'receive_date'=>$receive_date,
            'deliver_date'=>$deliver_date,
            'goods_id'=>$goods_id,
            'posted_yard_shed'=>$posted_yard_shed,
            'ReceiveWeight'=>$ReceiveWeight,

        );
        // return $warehouse;
        //=====================WAREHOUSE CHARGE END==========================





        $pdf = PDF::loadView('Assessment.Challan',[
            'todayWithTime' => $todayWithTime,
            'goodsNameTotalPkgMaxNet' => $goodsNameTotalPkgMaxNet, //GoodsNameTotalPkgAndMaxNet Weight
            'manifestReport' => $manifestReport,
            'foreignTruck' => $foreignTruck,
            'localTruck' => $localTruck,
            //carpenter
            'carpenterChargesOpenningOrClosing' => $carpenterChargesOpenningOrClosing,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            //carpenter
            //holiday
            'holidayChargesFT' => $holidayChargesFT,
            'holidayChargesLT' => $holidayChargesLT,
            //holiday
            //night
            'nightChargesFT' => $nightChargesFT,
            'nightChargesLT' => $nightChargesLT,
            //night
            //holtage
            'holtageChargesFT' => $holtageChargesFT,
            'holtageChargesLT' => $holtageChargesLT,
            //holtage
            'documentationCharges' => $documentationCharges,
            //weightment
            'weighmentChargesFT' => $weighmentChargesFT,
            'weighmentChargesLT' => $weighmentChargesLT,
            //weightment
            'offLoadingLabour' => $offLoadingLabour,
            'offLoadingEquipment' => $offLoadingEquipment,
            'loadingLabour' => $loadingLabour,
            'loadingEquip' => $loadingEquip,
            //WareHouse
            'warehouse'=> $warehouse,
            //WareHouse

            //'ChalanNo'=>$chalan
        ])
            //->setPaper('B4', 'landscape');
            ->setPaper([0, 0, 920.661, 1000.63], 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('ChallanPDF.pdf');
    }
}
