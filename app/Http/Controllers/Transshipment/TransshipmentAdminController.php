<?php

namespace App\Http\Controllers\Transshipment;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use App\Http\Controllers\GlobalFunctionController;
use App\Http\Controllers\TransshipmentAssessmentBaseController;
use App\Http\Controllers\AssessmentBaseController;

class TransshipmentAdminController extends Controller
{

    private $globalFunctionController;
    private $trans_assessment_base_controller;
    private $assessment_base_controller;


    public function __construct(GlobalFunctionController $globalFunctionController, TransshipmentAssessmentBaseController $trans_assessment_base_controller, AssessmentBaseController $assessment_base_controller)
    {
        $this->globalFunctionController = $globalFunctionController;
        $this->trans_assessment_base_controller = $trans_assessment_base_controller;
        $this->assessment_base_controller = $assessment_base_controller;

    }


    public function welcome() {
        return view('default.transshipment.admin.welcome');
    }

    public function completedAssessmentView() {
        //dd(Auth::user()->role->id);
        return view('default.transshipment.admin.completed-assessment-view');
    }

    public function getCompletedAssessments($date,$a) {
        $port_id = Session::get('PORT_ID');
        $today = $date;//date('Y-m-d');
        // return $today;
        $flag = 1;
        if ($a == 1){
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
GROUP BY manifest_id)
AND manifests.transshipment_flag =?
AND DATE(assessments.done_at)=? AND assessments.done = 1
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.done_at ASC", [$port_id, $flag, $today, $port_id, $port_id]);
                return json_encode($todaysCompletedAssessment);
            }else{
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
GROUP BY manifest_id)
AND manifests.transshipment_flag =?
AND DATE(assessments.updated_at)=? AND assessments.done = 0
AND assessments.port_id=? AND manifests.port_id=?
ORDER BY assessments.created_at ASC", [$port_id, $flag, $today, $port_id, $port_id]);
            return json_encode($todaysCompletedAssessment);
        }
    }

    public function assessementDetails($manifest, $truck, $year, $assessment_id, $partial_status) {

        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $todayWithTime = date('Y-m-d h:i:s a');
        $manifestDetails = $this->assessment_base_controller->manifestDetailsForAssessment($mani_no, $partial_status);
        return view('default.transshipment.admin.assessment-details-preview',[

            'todayWithTime' => $todayWithTime,
            //manifest details
            'manifestNo' => $manifestDetails[0]->manifest_no,
            'manifest_id'=>$manifestDetails[0]->manifest_id,
            'role'=> Auth::user()->role->name,
            'assessment_id' => $assessment_id,
            'partial_status' => $partial_status
        ]);

    }

    public function checkAssessmentDone($assessment_id) {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT assessments.done 
            FROM assessments WHERE assessments.id = ? AND assessments.port_id=?', [$assessment_id,$port_id]);
        return json_encode($data);
    }

    public function doneAssessment($Mani_id, $assessment_id, $partial_status) {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;
        $time = date('Y-m-d H:i:s');
        $postAssessmentDone = DB::table('assessments')
                                    ->where('assessments.id',$assessment_id)
                                    ->update([
                                        'assessments.done' => 1,
                                        'assessments.done_by' => $user_id,
                                        'assessments.done_at' => $time
                                        ]);

        $checkAssDone = DB::table('transactions AS t')
            ->where('t.manif_id', $Mani_id)
            ->where('t.port_id', $port_id)
            ->get()->first();

        DB::table('manifests')
            ->where('id', $Mani_id)
            ->where('port_id', $port_id)
            ->update([
                'gate_pass_no' => rand(1000,10000)
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
            $getMaxIdChalan = DB::select("SELECT MAX(CAST((SUBSTRING(challan_details.challan_no, 3)) AS UNSIGNED)) AS challan_no FROM challan_details  WHERE challan_details.port_id=?",[$port_id]);
            $getNumberOfChallan = DB::select("SELECT COUNT(challan_details.id) AS challan_count FROM challan_details WHERE DATE(challan_details.created_at) = DATE(NOW()) AND challan_details.port_id=?",[$port_id]);
            $getNum = $getNumberOfChallan[0]->challan_count + 1;

            if (!is_null($getMaxIdChalan[0]->challan_no)) {
                $challanNumber = $getMaxIdChalan[0]->challan_no + 1;
            } else {
                $challanNumber = 1;
            }
            $CallanNo = $getChallValue.sprintf("%06d", $challanNumber);
            $ChallanNo1 = $CallanNo."/".$getNum;

            $challan_id = DB::table('challan_details')
                ->insertGetId([
                    'manf_id' => $Mani_id,
                    'challan_no' => $ChallanNo1,
                    'created_at' => $createdTime,
                    'creator' => $createdBy,
                    'created_by' => $port_id
                ]);
        }

        else{
            $challan_id=$CallanNoCheck[0]->id;
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
                ]
            );
        }
        if($BdVanEntrance != '[]') {
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
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
                    'port_id' => $port_id
                ]
            );
        }
        
        if($postAssessmentDone == true) {
            return "Success";
        }
    }

    public function getPreviousCompletedAssessment() {
        if(Auth::user()->role->id == 23) {
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
            ORDER BY assessments.created_at ASC',[$flag]);
        return json_encode($getPreviousCompletedAssessment);

    }





    public  function assessmentAdminTruckReport(){
        return view('Assessment Admin.truckReport');
    }

    public  function assessmentAdminWeighbridgeReport(){
        return view('Assessment Admin.weighbridgeReport');
    }

    public  function assessmentAdminPostingReport(){
        return view('Assessment Admin.postingReports');
    }

    public  function assessmentAdminWarehouseReceiveReport(){
        return view('Assessment Admin.warehouseReceiveReports');
    }

    public  function assessmentAdminWarehouseDeliveryReport(){
        return view('Assessment Admin.warehouseDeliveryReports');
    }
}
