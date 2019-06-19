<?php

namespace App\Http\Controllers\GateOut;
use App\Http\Controllers\Controller;
use App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Session;

class GateLocalController extends Controller
{
    public function welcome()
    {
        $name = Auth::user()->name;

        $currentDate = date('Y-m-d');

        $todaysEntryTruckTotal = DB::select('SELECT COUNT(*) AS today_entry FROM truck_deliverys WHERE DATE(entry_dt)=?', [$currentDate]);

        $todaysExitTruckTotal = DB::select('SELECT COUNT(*) AS today_exit FROM truck_deliverys WHERE DATE(exit_dt)=?', [$currentDate]);

        $todaysTruckExitByUser = DB::select('SELECT COUNT(*) AS user_exit FROM truck_deliverys WHERE DATE(exit_dt) =? AND exit_by = ?', [$currentDate, $name]);

        $todaysTruckEntryByUser = DB::select('SELECT COUNT(*) AS today_entry_by_user FROM truck_deliverys WHERE DATE(entry_dt) =? AND entry_by = ?', [$currentDate, $name]);


//        $todaysManifestTruckOutTotal=DB::select('SELECT COUNT(manifests.id) total_Truck_entry  FROM manifests JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
//        WHERE DATE(created_time)=?',[$currentDate]);
//        //  $upcomingTruckTotal=DB::select('SELECT COUNT(id) total_upcoming_truck FROM truck_entry_regs WHERE truckentry_datetime IS NOT NULL AND wbridg_user1 IS NULL AND weightment_flag=1');
//
//        $postingNotDone=DB::select("SELECT COUNT(*) AS posting_not_done, CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest
//FROM manifests WHERE manifests.gweight IS NULL ORDER BY justManifest DESC");

        //  $todaysManifestByUser=DB::select('SELECT COUNT(id) total_manifest_by_user FROM manifests WHERE created_by=? AND  DATE(created_time) =? ',[$name,$currentDate]);

        return view('GateLocal.welcome', compact('todaysEntryTruckTotal', 'todaysExitTruckTotal', 'todaysTruckExitByUser', 'todaysTruckEntryByUser'));
    }

    public function localTruckGateOutView()
    {
        return view('GateLocal.localTruckGate');
    }

    public function getLocalTrucksDataDetails(Request $r)
    {
        if ($r->searchBy == 'manifestNo') {
            $manifestDetailswithLocalTruck = DB::table('truck_deliverys')
                ->join('manifests', 'truck_deliverys.manf_id', '=', 'manifests.id')
                ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
                ->where('manifests.manifest', $r->searchKey)
                ->select('truck_deliverys.id',
                    'truck_deliverys.truck_no',
                    'truck_deliverys.loading_unit',
                    'truck_deliverys.entry_dt',
                    'truck_deliverys.exit_dt',
                    'manifests.manifest',
                    'manifests.manifest_date',
                    'manifests.be_no',
                    'manifests.be_date',
                    'manifests.marks_no',
                    'cargo_details.cargo_name')
                ->get();
        } elseif ($r->searchBy == 'truckNo') {
            $manifestDetailswithLocalTruck = DB::table('truck_deliverys')
                ->join('manifests', 'truck_deliverys.manf_id', '=', 'manifests.id')
                ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
                ->where('truck_deliverys.truck_no', $r->searchKey)
                ->select('truck_deliverys.id',
                    'truck_deliverys.truck_no',
                    'truck_deliverys.loading_unit',
                    'truck_deliverys.entry_dt',
                    'truck_deliverys.exit_dt',
                    'manifests.manifest',
                    'manifests.manifest_date',
                    'manifests.be_no',
                    'manifests.be_date',
                    'manifests.marks_no',
                    'cargo_details.cargo_name')
                ->get();
        }
        return json_encode($manifestDetailswithLocalTruck);
    }

    public function saveExitData(Request $r)
    {
        $exit_dt = date('Y-m-d H:i:s');
        $exit_by = Auth::user()->name;
        $postExit = DB::table('truck_deliverys')
            ->where('truck_deliverys.id', $r->id)
            ->update(['truck_deliverys.exit_dt' => $exit_dt,
                'truck_deliverys.exit_by' => $exit_by,
                'truck_deliverys.exit_comment' => $r->exit_comment
            ]);
        if ($postExit == true) {
            return "Success";
        }
    }

    public function saveEntryData(Request $r)
    {
        $entry_dt = date('Y-m-d H:i:s');
        $entry_by = Auth::user()->name;
        $postEntry = DB::table('truck_deliverys')
            ->where('truck_deliverys.id', $r->id)
            ->update(['truck_deliverys.entry_dt' => $entry_dt,
                'truck_deliverys.entry_by' => $entry_by,
                'truck_deliverys.entry_comment' => $r->entry_comment
            ]);
        if ($postEntry == true) {
            return "Success";
        }
    }


    public function todaysGateoutExitReport()
    {
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysGateOut = DB::table('truck_deliverys')
            ->join('manifests', 'truck_deliverys.manf_id', '=', 'manifests.id')
            ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
            ->where('truck_deliverys.exit_dt', 'LIKE', "%$today%")
            ->select('truck_deliverys.id',
                'truck_deliverys.truck_no',
                'truck_deliverys.loading_unit',
                'manifests.manifest',
                'manifests.manifest_date',
                'manifests.be_no',
                'manifests.be_date',
                'manifests.marks_no',
                'cargo_details.cargo_name')
            ->get();
        $pdf = PDF::loadView('GateLocal.todaysGateOutPDF', [
            'todaysGateOut' => $todaysGateOut,
            'todayWithTime' => $todayWithTime
        ]);
        return $pdf->stream('GateOutPDFReport.pdf');
    }

    public function todaysGateoutEntryReport()
    {
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysGateIn = DB::table('truck_deliverys')
            ->join('manifests', 'truck_deliverys.manf_id', '=', 'manifests.id')
            ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
            ->where('truck_deliverys.entry_dt', 'LIKE', "%$today%")
            ->select('truck_deliverys.id',
                'truck_deliverys.truck_no',
                'truck_deliverys.loading_unit',
                'manifests.manifest',
                'manifests.manifest_date',
                'manifests.be_no',
                'manifests.be_date',
                'manifests.marks_no',
                'cargo_details.cargo_name')
            ->get();
        $pdf = PDF::loadView('GateLocal.todaysGateInPDF', [
            'todaysGateIn' => $todaysGateIn,
            'todayWithTime' => $todayWithTime
        ]);
        return $pdf->stream('GateInPDFReport.pdf');
    }

    public function getLocalTruckDetailsReport($id)
    {
        //return $id;
        $todayWithTime = date('Y-m-d h:i:s a');
        $getLocalTruckDetails = DB::table('truck_deliverys')
            ->join('manifests', 'truck_deliverys.manf_id', '=', 'manifests.id')
            ->join('cargo_details', 'cargo_details.id', '=', 'manifests.goods_id')
//            ->join('vatregs', 'vatregs.BIN', '=', 'manifests.vat_id')
            ->where('truck_deliverys.id', $id)
            ->select('truck_deliverys.id',
                'truck_deliverys.truck_no',
                'truck_deliverys.loading_unit',
                // 'truck_deliverys.delivery_dt',
                //'truck_deliverys.exit_dt',
                'manifests.manifest',
                'manifests.manifest_date',
                'manifests.be_no',
                'manifests.be_date',
                'manifests.marks_no',
                'cargo_details.cargo_name',
                DB::raw('DATE(truck_deliverys.delivery_dt) AS delivery_dt'),
                DB::raw('DATE(truck_deliverys.exit_dt) AS exit_dt')
        /*        'vatregs.NAME',
                'vatregs.ADD1'*/
            )
            ->get();
        //return $getLocalTruckDetails;
        $pdf = PDF::loadView('GateLocal.localTruckDetailsPDF', [
            'getLocalTruckDetails' => $getLocalTruckDetails,
            'todayWithTime' => $todayWithTime
        ]);
        return $pdf->stream('localTruckDetailsPDF.pdf');
    }

    public function welcomeGatePass() {

        $currentDate = date('Y-m-d');
        $currentUser = Auth::user()->id;
       // dd($currentDate);
        $port_id = Session::get('PORT_ID');
        $todaysAssessmentDetails = DB::select('SELECT (SELECT COUNT(a.total_ass) 
                FROM ( SELECT COUNT(ass.id) AS total_ass FROM assessments ass 
                WHERE DATE(ass.created_at)=? AND ass.transshipment_flag=0 AND ass.port_id=? 
                GROUP BY ass.manifest_id) a) AS total_assessment,
                (SELECT SUM(b.total_charge) FROM (SELECT SUM(asd.tcharge) AS total_charge 
                FROM assesment_details asd 
                JOIN assessments AS ass ON ass.manifest_id = asd.manif_id
                WHERE DATE(ass.created_at)=? AND ass.transshipment_flag=0 AND ass.port_id=? AND asd.port_id=? AND ass.id IN ( SELECT MAX(id) FROM assessments WHERE port_id=? GROUP BY manifest_id) ) b ) AS total_assessment_value,
                (SELECT COUNT(c.completed_assessment) FROM ( SELECT COUNT(ass.id) AS completed_assessment 
                FROM assessments ass WHERE DATE(ass.created_at)=? AND ass.done = 1 
                AND ass.transshipment_flag=0 AND ass.port_id=? GROUP BY ass.manifest_id) c) AS total_assessment_done,
                (SELECT COUNT(d.assessment_created_by_you) FROM (SELECT COUNT(ass.id) AS assessment_created_by_you 
                FROM assessments ass WHERE DATE(ass.created_at) = ? AND ass.created_by=? AND ass.port_id=?
                GROUP BY ass.manifest_id) d ) AS total_assessment_created_by_you', [$currentDate, $port_id,$currentDate, $port_id, $port_id, $port_id, $currentDate, $port_id, $currentDate, $currentUser, $port_id]);

        $TotalAssessmentValue = ceil($todaysAssessmentDetails[0]->total_assessment_value);//number_format(, 2, '.', '');
        // $Vat = number_format((($TotalAssessmentValue * 15) / 100), 2, '.', '');
        $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);


        return view('default.gate-pass.welcome', compact('todaysAssessmentDetails', 'TotalAssessmentWithVat'));
    }
    public function gatePassMonitor() {
        return view('default.gate-pass.gate-pass-monitor');
    }



    public function getDateWiseGatePassManifestData($date)
    {
        $port_id = Session::get('PORT_ID');

                $dateWiseGatePassManifest = DB::select("SELECT 
manifests.manifest, DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date,vatregs.NAME AS importer_name,cargo_details.cargo_name,manifests.cnf_name,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
dr.challan_no,DATE_FORMAT(DATE(delivery_requisitions.approximate_delivery_date), '%d-%m-%Y') AS approximate_delivery_date,delivery_requisitions.gate_pass_no,delivery_requisitions.partial_status,
delivery_requisitions.gate_pass_at,(SELECT users.name FROM users WHERE users.id = delivery_requisitions.gate_pass_by) AS created_by 
FROM manifests
 JOIN      (
              SELECT    MAX(id) max_id, manf_id 
              FROM      challan_details 
              GROUP BY  manf_id
          ) c_max ON (c_max.manf_id = manifests.id)
 JOIN challan_details AS dr ON (dr.id = c_max.max_id)
JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
LEFT JOIN vatregs ON vatregs.id=manifests.vatreg_id 
JOIN cargo_details ON cargo_details.id = manifests.goods_id
WHERE manifests.port_id=? AND delivery_requisitions.port_id=? AND DATE(delivery_requisitions.gate_pass_at) =?
ORDER BY delivery_requisitions.gate_pass_at DESC", [$port_id,$port_id,$date]);

                return json_encode($dateWiseGatePassManifest);

    }

    public function localTruckGatePassSheetReport(Request $req) {
//      dd($req->partial_status_for_gatepass);
        $todayWithTime = date('Y-m-d h:i:s a');
        $port_id = Session::get('PORT_ID');

        $checkManifest = DB::select('SELECT * FROM manifests AS m WHERE m.manifest=? AND m.port_id=?', [$req->manifest, $port_id]);

        if(count($checkManifest) == 0) {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! manifest no. ' . $req->manifest . ' is not found in our record!']);
        }

        $chkGatePassNo = DB::select('SELECT dr.*
            FROM delivery_requisitions dr WHERE dr.manifest_id=? AND dr.partial_status=?',[$checkManifest[0]->id, $req->partial_status_for_gatepass]);

        $chkAssessment = DB::select('SELECT assessments.*
            FROM assessments  WHERE assessments.manifest_id =? AND assessments.partial_status =?',[$checkManifest[0]->id, $req->partial_status_for_gatepass]);
        //dd($chkAssessment);
        if(count($chkAssessment) == 0) {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'Sorry! Assessment not done for ' . $req->manifest.', Partial Status: '.$req->partial_status_for_gatepass]);
        }

        if(count($chkGatePassNo) == 0) {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'Sorry! Delivery request not done for ' . $req->manifest]);
        }
        if(is_null($chkGatePassNo[0]->gate_pass_no)) {
            $randomNumber = mt_rand(100, 900);
            $postGateOut = DB::table('delivery_requisitions')
                ->where('delivery_requisitions.manifest_id', $checkManifest[0]->id)
                ->where('delivery_requisitions.partial_status', $req->partial_status_for_gatepass)
                ->update([
                    'delivery_requisitions.gate_pass_no' => $randomNumber,
                    'delivery_requisitions.gate_pass_by' => Auth::user()->id,
                    'delivery_requisitions.gate_pass_at' => date('Y-m-d H:i:s')
                ]);
        }


        $getManifestWithGatePass = DB::select('SELECT m.id,dr.gate_pass_no, m.manifest,cnf_details.cnf_name,cnf_details.address,
                                    m.exporter_name_addr AS exporter ,
                                    (SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR \', \') FROM yard_details AS yd 
                                    JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
                                    JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
                                    JOIN manifests AS ma ON ma.id = ter.manf_id
                                    WHERE ma.id = m.id) AS yard_shed_name 
                                    FROM manifests AS m 
                                    JOIN delivery_requisitions AS dr ON dr.manifest_id = m.id
                                    JOIN cnf_details ON cnf_details.id =  m.cnf_id
                                    WHERE m.manifest=? AND m.port_id=? AND dr.port_id=? AND dr.partial_status = ?', [$req->manifest, $port_id, $port_id, $req->partial_status_for_gatepass]);

        $getLocalTrucks = DB::select('SELECT cargo_details.cargo_name,
(SELECT vehicle_type_bd.type_name FROM vehicle_type_bd 
WHERE vehicle_type_bd.id = td.truck_type_id)  AS truck_type,
SUBSTRING_INDEX(td.truck_no,\'-\',-1) AS truck_no,
m.manifest,m.manifest_date,m.be_date,m.be_no,m.marks_no, (CAST(m.package_no AS CHAR)+0) AS package_no,(CAST(m.gweight AS CHAR)+0) AS gweight
                                    FROM  manifests AS m 
                                    JOIN cargo_details ON cargo_details.id = m.goods_id
                                    LEFT JOIN truck_deliverys AS td ON m.id=td.manf_id
                                    WHERE m.id=? AND m.port_id=?', [$getManifestWithGatePass[0]->id, $port_id]);

        // dd($getManifestWithGatePass[0]->id);

        $pdf = PDF::loadView('GateLocal.localTruckGatePassSheet', [
            // 'getLocalTruckDetails' => $getLocalTruckDetails,
            'todayWithTime' => $todayWithTime,
            'getManifestWithGatePass' => $getManifestWithGatePass,
            'getLocalTrucks' => $getLocalTrucks ,//? $getLocalTrucks : Null,
            'manifestNo' => $req->manifest
        ])->setPaper([0, 0, 920.661, 1009], 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('localTruckGatePassSheetPDF.pdf');

    }



    public function getGatePassData(Request $req) {
        $port_id = Session::get('PORT_ID');
        $today = $req->date;
        $todayWithTime = date('Y-m-d h:i:s a');
//        Auth::user()->role->id == 12

            $requestData = DB::select("SELECT 
manifests.manifest, DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date,vatregs.NAME AS importer_name,cargo_details.cargo_name,manifests.cnf_name,
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
dr.challan_no,DATE_FORMAT(DATE(delivery_requisitions.approximate_delivery_date), '%d-%m-%Y') AS approximate_delivery_date,delivery_requisitions.gate_pass_no,delivery_requisitions.partial_status,
delivery_requisitions.gate_pass_at,(SELECT users.name FROM users WHERE users.id = delivery_requisitions.gate_pass_by) AS created_by 
FROM manifests
 JOIN      (
              SELECT    MAX(id) max_id, manf_id 
              FROM      challan_details 
              GROUP BY  manf_id
          ) c_max ON (c_max.manf_id = manifests.id)
 JOIN challan_details AS dr ON (dr.id = c_max.max_id)
JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
LEFT JOIN vatregs ON vatregs.id=manifests.vatreg_id 
JOIN cargo_details ON cargo_details.id = manifests.goods_id
WHERE manifests.port_id=? AND delivery_requisitions.port_id=? AND DATE(delivery_requisitions.gate_pass_at) =?
ORDER BY delivery_requisitions.gate_pass_at DESC", [$port_id,$port_id,$today]);


        if(count($requestData)) {
            $pdf = PDF::loadView('default.gate-pass.reports.gate-pass-report', [
                'requestData' => $requestData,
                'todayWithTime' => $todayWithTime,
                'today' => $today
            ])->setPaper([0, 0, 850, 900]);
            return $pdf->stream('gate-pass-report-'.$today.'.pdf');
        } else {
            return view('default.gate-pass.not-found',['requestedDate' => $today]);
        }
    }

//    public function manifestWiseGatePassReport($manifest, $truck, $year) {
//        $manifest = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
//        $port_id = Session::get('PORT_ID');
//        $today = date('Y-m-d');
//        $todayWithTime = date('Y-m-d h:i:s a');
////        Auth::user()->role->id == 12
//
//        $requestData = DB::select("SELECT
//manifests.manifest, DATE_FORMAT(DATE(manifests.manifest_date), '%d-%m-%Y') AS manifest_date,vatregs.NAME AS importer_name,cargo_details.cargo_name,manifests.cnf_name,
//(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd
//JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
//JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
//JOIN manifests AS ma ON ma.id = ter.manf_id
//WHERE ma.id = manifests.id) AS yard_shed_name,
//dr.challan_no,DATE_FORMAT(DATE(delivery_requisitions.approximate_delivery_date), '%d-%m-%Y') AS approximate_delivery_date,delivery_requisitions.gate_pass_no,delivery_requisitions.partial_status,
//delivery_requisitions.gate_pass_at,(SELECT users.name FROM users WHERE users.id = delivery_requisitions.gate_pass_by) AS created_by
//FROM manifests
// JOIN      (
//              SELECT    MAX(id) max_id, manf_id
//              FROM      challan_details
//              GROUP BY  manf_id
//          ) c_max ON (c_max.manf_id = manifests.id)
// JOIN challan_details AS dr ON (dr.id = c_max.max_id)
//JOIN delivery_requisitions ON delivery_requisitions.manifest_id = manifests.id
//LEFT JOIN vatregs ON vatregs.id=manifests.vatreg_id
//JOIN cargo_details ON cargo_details.id = manifests.goods_id
//WHERE manifests.port_id=? AND delivery_requisitions.port_id=?  AND manifests.manifest =?", [$port_id,$port_id,$manifest]);
//
//
//        if(count($requestData)) {
//            $pdf = PDF::loadView('default.gate-pass.reports.manifest-wise-gate-pass-report', [
//                'requestData' => $requestData,
//                'todayWithTime' => $todayWithTime,
//                'manifest' => $manifest
//            ])->setPaper([0, 0, 850, 900]);
//            return $pdf->stream('gate-pass-report-'.$today.'.pdf');
//        } else {
//            return view('default.gate-pass.not-found',['requestedDate' => $today]);
//        }
//
//    }





}
