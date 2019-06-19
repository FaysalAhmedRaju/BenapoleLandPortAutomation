<?php

namespace App\Http\Controllers\Assessment;

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
use Session;

class AssessmentReportController extends Controller
{
    public function userAndDateWiseReport(Request $req) {
        $port_id = Session::get('PORT_ID');
        $date = $req->entryDate;//date('Y-m-d');
        $reportType = $req->reportType;
        // dd($reportType);
        $todayWithTime = date('Y-m-d h:i:s a');
        $user_role_id = Auth::user()->role->id;
        // dd($reportType." ".$user_role_id);

        if ($user_role_id == 23 || $user_role_id==12) {//trans ass  and trans ass admin
            $flag = 1;
        } else if($user_role_id== 9 || $user_role_id==21) {
            $flag = 0;
        }else{ //other
            
            $flag = "0,1";
        }
//dd($reportType);

        if ($reportType) {//it means user id
            $data = DB::select("SELECT assessments.id,assessments.manifest_id AS manifestId, manifests.manifest, manifests.be_no, 
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, manifests.exporter_name_addr, manifests.cnf_name, 
assessments.created_at, assessments.done, assessments.assessment_values,assessments.partial_status,
(SELECT CEIL(SUM(assesment_details.tcharge)) 
FROM assesment_details 
WHERE assesment_details.manif_id=assessments.manifest_id 
AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
JOIN delivery_requisitions ON manifests.id = delivery_requisitions.manifest_id
WHERE assessments.id IN (
SELECT MAX(id)
FROM assessments
WHERE assessments.port_id=?
GROUP BY assessments.manifest_id)
AND assessments.created_by=?
AND DATE(delivery_requisitions.approximate_delivery_date)=? AND manifests.port_id=? AND assessments.port_id=? ", [$port_id, $reportType, $date, $port_id, $port_id]);
        } else {

            $data = DB::select("SELECT assessments.id,assessments.manifest_id AS manifestId, manifests.manifest, manifests.be_no, 
(SELECT GROUP_CONCAT(DISTINCT yd.yard_shed_name SEPARATOR ', ') FROM yard_details AS yd 
JOIN shed_yard_weights AS syw ON syw.unload_yard_shed = yd.id
JOIN truck_entry_regs AS ter ON ter.id = syw.truck_id
JOIN manifests AS ma ON ma.id = ter.manf_id
WHERE ma.id = manifests.id) AS yard_shed_name,
manifests.custom_release_order_no, manifests.manifest_date, manifests.exporter_name_addr, manifests.cnf_name, 
assessments.created_at, assessments.done, assessments.assessment_values,assessments.partial_status,
(SELECT CEIL(SUM(assesment_details.tcharge)) 
FROM assesment_details 
WHERE assesment_details.manif_id=assessments.manifest_id 
AND assesment_details.partial_status=assessments.partial_status) AS totalAssessmentValue,
(SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=manifests.vatreg_id) AS importerName,
(SELECT users.name FROM users WHERE users.id = assessments.created_by) AS created_by
FROM manifests
JOIN assessments ON manifests.id = assessments.manifest_id
JOIN delivery_requisitions ON manifests.id = delivery_requisitions.manifest_id
WHERE assessments.id IN (
SELECT MAX(id)
FROM assessments
WHERE assessments.port_id=?
GROUP BY assessments.manifest_id)
AND manifests.transshipment_flag IN ($flag)
AND DATE(delivery_requisitions.approximate_delivery_date)=? AND manifests.port_id=? AND assessments.port_id=?", [$port_id, $date, $port_id, $port_id]);
        }

        $pdf = PDF::loadView('default.assessment.reports.user-and-date-wise-assessment-report', [
            'data' => $data,
            'todayWithTime' => $todayWithTime,
            'requestedDate' => $date
        ])->setPaper([0, 0, 900, 800]);
        return $pdf->stream('assessment-entry-report-' . $todayWithTime . '.pdf');
    }

    public function monthlyAssessmentEntryReport(Request $r) { 
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $year = date("Y", strtotime($r->month_entry_exit));
        $month = date("m", strtotime($r->month_entry_exit));
        $user_role = Auth::user()->role_id;

        // dd($r->month_entry_exit);

        if ($user_role == 9 || $user_role == 21) {//Assessment
            $data = DB::select(' SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE MONTH(assessments.done_at) =? AND YEAR(assessments.done_at) =? AND manifests.transshipment_flag=0 AND assessments.port_id=? AND manifests.port_id=? 
                                GROUP BY DATE(assessments.done_at)', [$month, $year, $port_id, $port_id]);

        } elseif ($user_role == 12 || $user_role == 23) {//transshipment
            $data = DB::select(' SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at
                                FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE MONTH(assessments.done_at) = ? AND YEAR(assessments.done_at) =? AND manifests.transshipment_flag =1 AND assessments.port_id=? AND manifests.port_id=? 
                                GROUP BY DATE(assessments.done_at)', [$month, $year, $port_id, $port_id]);

        } else {//for super admin or all
            $data = DB::select(' SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at
                                FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE MONTH(assessments.done_at) =? AND YEAR(assessments.done_at) =? AND assessments.port_id=? AND manifests.port_id=? 
                                GROUP BY DATE(assessments.done_at)', [$month, $year, $port_id, $port_id]);
        }

        // dd($data);

        $pdf = PDF::loadView('default.assessment.reports.monthly-assessment-count-report', [
            'data' => $data,
            'month' => $r->month_entry_exit,
            'todayWithTime' => $todayWithTime

        ])->setPaper([0, 0, 800, 800]);
        return $pdf->stream('monthlyAssessmentEntryPdf-' . $month . '.pdf');
    }

    public function yearlyAssessmentEntryReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $year = $r->year;
        $user_role = Auth::user()->role_id;
        //dd($user_role);
        // $month = date("m",strtotime($r->month_entry_exit));

        if ($user_role == 9 || $user_role == 21) {//WeightBridge
            $data = DB::select('SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at
                                FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE YEAR(assessments.done_at) =? AND manifests.transshipment_flag =0 AND assessments.port_id=? AND manifests.port_id=? 
                                 GROUP BY MONTH(assessments.done_at)', [$year, $port_id, $port_id]);

        } elseif ($user_role == 12 || $user_role == 23) {//transshipment

            $data = DB::select('SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE YEAR(assessments.done_at) =? AND manifests.transshipment_flag =1 AND assessments.port_id=? AND manifests.port_id=? 
                                GROUP BY MONTH(assessments.done_at)', [$year, $port_id, $port_id]);

        } else {//for super admin or all

            $data = DB::select('SELECT COUNT(assessments.id) AS assessment_count, DATE(assessments.done_at) AS created_at
                                FROM assessments
                                JOIN manifests ON assessments.manifest_id = manifests.id
                                WHERE YEAR(assessments.done_at) =? AND assessments.port_id=? AND manifests.port_id=? 
                                GROUP BY MONTH(assessments.done_at)', [$year, $port_id, $port_id]);

        }
        $pdf = PDF::loadView('default.assessment.reports.yearly-assessment-count-report', [
            'data' => $data,
            'year' => $year,
            'todayWithTime' => $todayWithTime

        ])->setPaper([0, 0, 800, 800]);
        return $pdf->stream('YearlyAssessmentCountPDF' . $year . '.pdf');

    }
}
