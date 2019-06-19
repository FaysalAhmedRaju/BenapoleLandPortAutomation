<?php

namespace App\Http\Controllers\Weighbridge;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use DB;
use PDF;

class WeighBridgeReportController extends Controller
{
    public function allReportsView() {
    	$port_id = Session::get('PORT_ID');
        $year = DB::select('SELECT DISTINCT YEAR(truck_entry_regs.wbrdge_time1) AS year 
        	FROM truck_entry_regs WHERE truck_entry_regs.wbrdge_time1 IS NOT NULL AND truck_entry_regs.wbrdge_time1 != 0 AND truck_entry_regs.port_id=?',[$port_id]);
        return view('default.weighbridge.weighbridge-all-reports-view', compact('year'));
    }

    public function getDateWiseWeightbridgeEntryReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        $roleId = Auth::user()->role->id;
        $requestedDate = $r->date;
        if ($roleId == 6) { //WeighBridge
            $user_scale = Auth::user()->scale->scale_id;
            $dateWiseWeightBridgeEntry = DB::select("SELECT truck_entry_regs.truck_type,truck_entry_regs.truck_no,truck_entry_regs.driver_name,
                    truck_entry_regs.gweight_wbridge,truck_entry_regs.wbrdge_time1,
                    truck_entry_regs.tr_weight, truck_entry_regs.tweight_wbridge,truck_entry_regs.wbrdge_time2, manifests.manifest,
                    (SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.entry_scale) AS entry_scale,
                    (SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_user1) AS user_name
                    FROM truck_entry_regs
                    JOIN manifests ON manifests.id = truck_entry_regs.manf_id
                    WHERE DATE(truck_entry_regs.wbrdge_time1)=? 
                    AND truck_entry_regs.entry_scale=? AND truck_entry_regs.port_id = ?
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                    ORDER BY truck_entry_regs.wbrdge_time1 DESC;", [$requestedDate, $user_scale, $port_id]);
        } else {  //super Admin

            $dateWiseWeightBridgeEntry = DB::select("SELECT truck_entry_regs.truck_type,truck_entry_regs.truck_no,truck_entry_regs.driver_name,
					truck_entry_regs.gweight_wbridge,truck_entry_regs.wbrdge_time1,truck_entry_regs.wbridg_user1,
					truck_entry_regs.tr_weight, truck_entry_regs.tweight_wbridge,truck_entry_regs.wbrdge_time2,
					truck_entry_regs.wbridg_user2,manifests.manifest,users.name AS user_name,
					(SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.entry_scale) AS entry_scale
					FROM truck_entry_regs
					JOIN manifests ON manifests.id= truck_entry_regs.manf_id
					JOIN users ON truck_entry_regs.wbridg_user1 = users.id
					WHERE DATE(truck_entry_regs.wbrdge_time1)=? AND truck_entry_regs.port_id = ? 
					AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
					ORDER BY truck_entry_regs.wbrdge_time1 DESC", [$requestedDate, $port_id]);
        }

        if ($dateWiseWeightBridgeEntry) {
            $pdf = PDF::loadView('default.weighbridge.reports.datewise-weighbridge-entry-report', [
                'todayWithTime' => $requestedDate,
                'dateWiseWeightBridgeEntry' => $dateWiseWeightBridgeEntry,
            ])->setPaper([0, 0, 800, 800]);
            return $pdf->stream('datewise-weighbridge-entry-report-' . $requestedDate . '.pdf');
        } else {
            return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function getDateWiseWeightbridgeExitReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        $roleId = Auth::user()->role->id;
        $requestedDate = $r->date;
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        if ($roleId == 6) { //WeighBridge
            $todaysWeightBridgeExit = DB::select("SELECT truck_entry_regs.truck_type,truck_entry_regs.truck_no,
				truck_entry_regs.driver_name,truck_entry_regs.gweight_wbridge,truck_entry_regs.wbrdge_time1,truck_entry_regs.wbridg_user1,
				truck_entry_regs.tr_weight,truck_entry_regs.tweight_wbridge,truck_entry_regs.wbrdge_time2,truck_entry_regs.wbridg_user2, manifests.manifest, (SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.exit_scale) AS exit_scale,
				(SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_user2 ) AS user_name
				FROM truck_entry_regs
				JOIN manifests ON manifests.id= truck_entry_regs.manf_id
				WHERE DATE(truck_entry_regs.wbrdge_time2)=? AND truck_entry_regs.exit_scale=? AND truck_entry_regs.port_id = ? 
				AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
				ORDER BY truck_entry_regs.wbrdge_time2 DESC", [$requestedDate, Auth::user()->scale->scale_id, $port_id]);
        } else {  //super Admin
            $todaysWeightBridgeExit = DB::select("SELECT truck_entry_regs.truck_type,truck_entry_regs.truck_no,
				truck_entry_regs.driver_name,truck_entry_regs.gweight_wbridge,truck_entry_regs.wbrdge_time1,truck_entry_regs.wbridg_user1,
				truck_entry_regs.tr_weight,truck_entry_regs.tweight_wbridge,truck_entry_regs.wbrdge_time2,truck_entry_regs.wbridg_user2,
				manifests.manifest, users.name AS user_name, (SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.exit_scale) AS exit_scale
				FROM truck_entry_regs
				JOIN manifests ON manifests.id= truck_entry_regs.manf_id
				JOIN users ON truck_entry_regs.wbridg_user2 = users.id
				WHERE DATE(truck_entry_regs.wbrdge_time2)=?  AND truck_entry_regs.port_id = ? 
				AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
				ORDER BY truck_entry_regs.wbrdge_time2 DESC", [$requestedDate, $port_id]);
        }
        if($todaysWeightBridgeExit) {
        	$pdf = PDF::loadView('default.weighbridge.reports.datewise-weighbridge-exit-report', [
            	'todaysWeightBridgeExit' => $todaysWeightBridgeExit,
            	'todayWithTime' => $todayWithTime
        	])->setPaper([0, 0, 900, 900]);
        	return $pdf->stream('datewise-weighbridge-exit-report-'.$requestedDate.'.pdf');
        } else {
        	return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function fiscalYearWiseWeighbridgeEntryReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        $todaysWeightBridgeEntry = DB::select("SELECT manifests.manifest, users.name, 
        			t.truck_type, t.truck_no, t.driver_name, t.gweight_wbridge, t.wbrdge_time1,
					t.wbridg_user1,t.tr_weight,t.tweight_wbridge,t.wbrdge_time2,t.wbridg_user2
 					FROM truck_entry_regs AS t
 					JOIN manifests  ON manifests.id = t.manf_id
 					JOIN users ON t.wbridg_user1 = users.id
 					WHERE DATE(t.wbrdge_time1) BETWEEN ? AND ? AND t.port_id=? 
 					AND manifests.port_id=?", [$firstDate, $lastDate, $port_id, $port_id]);
        $requestedDate = $r->year.'-'.$nextYear;
        if ($todaysWeightBridgeEntry) {
            $pdf = PDF::loadView('default.weighbridge.reports.fiscal-year-wise-weighbridge-entry-report', [
                'todaysWeightBridgeEntry' => $todaysWeightBridgeEntry,
                'todayWithTime' => $todayWithTime,
                'year' => $r->year
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('year-wise-weighbridge-entry-report'.$r->year.'-'.$nextYear.'.pdf');
        } else {
            return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function dateRangeWiseWeighbridgeEntryReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysWeightBridgeEntry = DB::select("SELECT manifests.manifest, users.name, t.truck_type, t.truck_no, t.driver_name, t.gweight_wbridge, t.wbrdge_time1,
				t.wbridg_user1,t.tr_weight,t.tweight_wbridge,t.wbrdge_time2,t.wbridg_user2
				FROM truck_entry_regs AS t
				JOIN manifests  ON manifests.id = t.manf_id
				JOIN users ON t.wbridg_user1 = users.id
				WHERE DATE(t.wbrdge_time1) BETWEEN ? AND ? AND t.port_id=? AND manifests.port_id=?", [$r->from_date_v, $r->to_date_v, $port_id, $port_id]);
        $requestedDate = $r->from_date_v.' to '.$r->to_date_v;
        if ($todaysWeightBridgeEntry) {
            $pdf = PDF::loadView('default.weighbridge.reports.date-range-wise-weighbridge-entry-report', [
                'todaysWeightBridgeEntry' => $todaysWeightBridgeEntry,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date_v,
                'to_date' => $r->to_date_v
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('date-range-wise-weighbridge-entry-report'.$r->from_date_v.'to'.$r->to_date_v.'pdf');
        } else {
            return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function dateRangeWiseWeighbridgeExitReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysWeightBridgeExit = DB::select("SELECT manifests.manifest, users.name, t.wbridg_user2, 
        	t.wbrdge_time2, t.tweight_wbridge, t.tr_weight, t.wbridg_user1, t.wbrdge_time1, 
				t.gweight_wbridge, t.driver_name, t.truck_no, t.truck_type 
				FROM truck_entry_regs AS t 
				JOIN manifests ON manifests.id = t.manf_id 
				JOIN users ON t.wbridg_user2 = users.id 
				WHERE DATE(t.wbrdge_time2) BETWEEN ? AND ? 
				AND t.port_id=? AND manifests.port_id=?", [$r->from_date_exit, $r->to_date_Exit, $port_id, $port_id]);
        $requestedDate = $r->from_date_exit.' to '.$r->to_date_Exit;
        if ($todaysWeightBridgeExit) {
            $pdf = PDF::loadView('default.weighbridge.reports.date-range-wise-weighbridge-exit-report', [
                'todaysWeightBridgeExit' => $todaysWeightBridgeExit,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date_exit,
                'to_date' => $r->from_date_exit
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('date-range-wise-weighbridge-exit-report'.$r->to_date_Exit.'-'.$r->from_date_exit.'.pdf');
        } else {
            return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function fiscalYearWiseWeighbridgeExitReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        $todaysWeightBridgeExit = DB::select("SELECT manifests.manifest,users.name, 
        			t.wbridg_user2, t.wbrdge_time2, t.tweight_wbridge, t.tr_weight, 
        			t.wbridg_user1, t.wbrdge_time1, 
					t.gweight_wbridge, t.driver_name, t.truck_no, t.truck_type 
					FROM truck_entry_regs AS t 
					JOIN manifests ON manifests.id = t.manf_id
					JOIN users ON t.wbridg_user2 = users.id
					WHERE DATE(t.wbrdge_time2) BETWEEN ? AND ? 
					AND t.port_id=? AND manifests.port_id=?", [$firstDate, $lastDate, $port_id, $port_id]);
        $requestedDate = $r->year.'-'.$r->year+1;
        if ($todaysWeightBridgeExit) {
            $pdf = PDF::loadView('default.weighbridge.reports.fiscal-year-wise-weighbridge-exit-report', [
                'todaysWeightBridgeExit' => $todaysWeightBridgeExit,
                'todayWithTime' => $todayWithTime,
                'year' => $r->year
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('fiscal-year-wise-weighbridge-exit-report'.$r->year.'-'.$nextYear.'.pdf');
        } else {
            return view('default.weighbridge.not-found', compact('requestedDate'));
        }
    }

    public function monthlyWeightBridgeEntryExitReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $year = date("Y", strtotime($r->month_entry_exit));
        $month = date("m", strtotime($r->month_entry_exit));
        $user_role = Auth::user()->role_id;

        if ($user_role == 6) {//weighbridge
            $data = DB::select('SELECT *,(  SELECT COUNT(truck_entry_regs.id)  
						FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
						WHERE  
 						SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
						AND DATE(truck_entry_regs.wbrdge_time2) = DATE(t.wbrdge_time1)
						) AS exit_truck  
						FROM (
						SELECT truck_entry_regs.wbrdge_time1,COUNT(truck_entry_regs.id) AS entry_truck 
						FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
						WHERE MONTH(truck_entry_regs.wbrdge_time1)=? AND YEAR(truck_entry_regs.wbrdge_time1)=?
						AND truck_entry_regs.port_id = ? 
						AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
						GROUP BY DATE(truck_entry_regs.wbrdge_time1)
						) AS t ', [$month, $year, $port_id]);

        } else {//for super admin or all
            $data = DB::select('SELECT *,(  SELECT COUNT(truck_entry_regs.id)  
						FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
						WHERE 
						SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
						AND DATE(truck_entry_regs.wbrdge_time2) = DATE(t.wbrdge_time1)

						) AS exit_truck  
						FROM (
						SELECT truck_entry_regs.wbrdge_time1,COUNT(truck_entry_regs.id) AS entry_truck 
						FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
						WHERE MONTH(truck_entry_regs.wbrdge_time1)=? AND YEAR(truck_entry_regs.wbrdge_time1)=?
						AND truck_entry_regs.port_id = ?
						AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
						GROUP BY DATE(truck_entry_regs.wbrdge_time1)
						) AS t ', [$month, $year, $port_id]);
        }

        if($data) {
            $pdf = PDF::loadView('default.weighbridge.reports.monthly-weighbridge-entry-exit-report', [
                'data' => $data,
                'month' => $r->month_entry_exit,
                'todayWithTime' => $todayWithTime

            ])->setPaper([0, 0, 800, 800]);
            return $pdf->stream('monthly-weighbridge-entry-exit-report-' . $todayWithTime . '.pdf');

        } else {
            return view('default.weighbridge.not-found', ['requestedDate' => $r->month_entry_exit]);
        }
    }

    public function yearlyWeighbridgeEntryExitReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $year = $r->year;
        $user_role = Auth::user()->role_id;
        if ($user_role == 6) { //WeightBridge
            $data = DB::select('SELECT *,(SELECT COUNT(truck_entry_regs.id)  
					FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
					WHERE  
					SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
					AND MONTH(truck_entry_regs.wbrdge_time2)= MONTH(t.wbrdge_time1)) AS exit_truck
					FROM (
					SELECT truck_entry_regs.wbrdge_time1,COUNT(truck_entry_regs.id) AS entry_truck 
					FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
					WHERE YEAR(truck_entry_regs.wbrdge_time1)=? AND truck_entry_regs.port_id = ?
					AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
					GROUP BY MONTH(truck_entry_regs.wbrdge_time1)
					) AS t', [$year, $port_id]);
        } else { //for super admin or all
            $data = DB::select('SELECT *,(SELECT COUNT(truck_entry_regs.id)  
					FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
					WHERE 
					SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
					AND MONTH(truck_entry_regs.wbrdge_time2)= MONTH(t.wbrdge_time1)) AS exit_truck

					FROM (
					SELECT truck_entry_regs.wbrdge_time1,COUNT(truck_entry_regs.id) AS entry_truck 
					FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
					WHERE YEAR(truck_entry_regs.wbrdge_time1)=?  AND truck_entry_regs.port_id = ? 
					AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
					GROUP BY MONTH(truck_entry_regs.wbrdge_time1)
					) AS t', [$year, $port_id]);
        }
        
        if ($data) {
            $pdf = PDF::loadView('default.weighbridge.reports.yearly-weighbridge-entry-exit-report', [
                'data' => $data,
                'year' => $year,
                'todayWithTime' => $todayWithTime

            ])->setPaper([0, 0, 800, 800]);
            return $pdf->stream('yearly-weighbridge-entry-exit-report-' . $todayWithTime . '.pdf');

        } else {
            return view('default.weighbridge.not-found', ['requestedDate' => $year]);
        }
    }
}
