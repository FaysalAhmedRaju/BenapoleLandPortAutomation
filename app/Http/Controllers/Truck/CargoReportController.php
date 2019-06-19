<?php

namespace App\Http\Controllers\Truck;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use App\Http\Controllers\Truck\TruckController;
use PDF;

class CargoReportController extends Controller {

	private $cargo_ctrl;
	public function __construct(TruckController $cargo_ctrl) {
		$this->cargo_ctrl = $cargo_ctrl;
	}

    public function allReportsView() { //Called From Super Admin Description
        $port_id = Session::get('PORT_ID');
        $year = DB::select('SELECT DISTINCT YEAR(ter.truckentry_datetime) AS year  FROM truck_entry_regs ter WHERE ter.truckentry_datetime IS NOT NULL AND ter.port_id=?',[$port_id]);
        return view('default.truck.all-reports-view', compact('year'));
    }

    public function dateWiseTruckEntryReport(Request $r) {   
        $dates = $r->date;
        $currentTime = date('Y-m-d H:i:s');
        $roleId = Auth::user()->role->id;
        $port_id = Session::get('PORT_ID');


        $flagValue = $r->vehile_type_flage_pdf;

        if ($roleId == 4 || $roleId == 22 || $roleId == 21 || $roleId == 23 || $roleId == 6) {//Truck and Export
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.vehicle_type_flag, truck_entry_regs.truck_type,truck_entry_regs.created_by,
                        truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime, truck_entry_regs.truck_weight, truck_entry_regs.truck_package
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND vehicle_type_flag = ? AND( r.id=4 OR r.id=22) AND truck_entry_regs.port_id=? AND m.port_id=?
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $flagValue, $port_id, $port_id]);//->toArray();

        } else if ($roleId == 7 || $roleId == 8) {//posting and warehouse

            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,
                        truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime, truck_entry_regs.truck_weight, truck_entry_regs.truck_package
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND m.transshipment_flag=0 AND( r.id=7 OR r.id=8)
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$' AND truck_entry_regs.port_id=? AND m.port_id=?
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $port_id, $port_id]);//->toArray();


        } else if ($roleId == 5) {//CnF
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,
                        truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime, truck_entry_regs.truck_weight, truck_entry_regs.truck_package
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                         WHERE DATE(truckentry_datetime)=? AND(r.id=5)
                         AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$' AND truck_entry_regs.port_id=? AND m.port_id=?
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $port_id, $port_id]);
        } else {// this is kept for super admin
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,
                        truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime, truck_entry_regs.truck_weight, truck_entry_regs.truck_package
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND vehicle_type_flag = ?
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$' AND truck_entry_regs.port_id=? AND m.port_id=?
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $flagValue, $port_id, $port_id]);
        }
        $manifest_array = array();
        $date_wiseEntry = (array)$date_wise;
        if ($date_wise) {
            foreach ($date_wiseEntry as $key => $maifest_value) {

                if ($this->cargo_ctrl->checkManifestFoundInNewArray($maifest_value, $manifest_array)) {
                    $newArrayIndex = $this->cargo_ctrl->getNewArrayManifestLastIndex($maifest_value, $manifest_array);
                    array_splice($manifest_array, $newArrayIndex + 1, 0, array($maifest_value));
                } else {
                    $manifest_array[] = $maifest_value;

                }
            }
        }

        if ($date_wise) {
            $pdf = PDF::loadView('default.truck.reports.truck-entry-report', [
                'data' => $manifest_array,
                'requestedDate' => $r->date,
                'flagValue' => $flagValue,
//                'head_name' => $head_name,
                'date' => $currentTime
            ])->setPaper([0, 0, 780, 940]);
            return $pdf->stream('Truck-Entry-Report-'.$r->date.'.pdf');
        } else {
            return view('default.truck.not-found', ['requestedDate' => $r->date]);
        }

    }

    public function dateWiseTruckExitReport(Request $r) {
        $dates = $r->date; //date('Y-m-d');
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');

        $todaysEntry = DB::select("SELECT t.truck_no,t.truck_type,t.id AS truck_entry_sl,m.manifest, u.name,t.driver_card,t.driver_name,t.truckentry_datetime,t.out_date,
                (SELECT GROUP_CONCAT(DISTINCT shed_yards.shed_yard) FROM shed_yards WHERE FIND_IN_SET(shed_yards.id, m.posted_yard_shed) > 0) AS posted_yard_shed
                FROM truck_entry_regs AS t      
                JOIN users AS u ON t.out_by=u.id   
                JOIN manifests AS m ON m.id=t.manf_id
                WHERE DATE(t.out_date)=? AND t.port_id=? AND m.port_id=?", [$dates, $port_id, $port_id]);

        $todaysTotalCount = DB::table('truck_entry_regs')
            ->where('out_date', 'LIKE', "%$dates%")
            ->where('truck_entry_regs.port_id', $port_id)
            ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
            ->join('users', 'truck_entry_regs.out_by', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('truck_entry_regs.*', 'cargo_details.cargo_name', 'users.name')
            ->get()->count();

        $pdf = PDF::loadView('default.truck.reports.truck-exit-report', ['manifestdata' => $todaysEntry, 'date' => $currentTime, 'todaysTotalCount' => $todaysTotalCount])->setPaper([0, 0, 850, 900]);

        return $pdf->stream('truck-exit-report-'.$r->date.'.pdf');

    }


    public function monthlyTruckEntryExitReport(Request $r) {

        $todayWithTime = date('Y-m-d h:i:s a');
        $year = date("Y", strtotime($r->month_entry_exit));
        $month = date("m", strtotime($r->month_entry_exit));
        $port_id = Session::get('PORT_ID');

        $user_role = Auth::user()->role_id;
        //  dd($year);
        if ($user_role == 4 || $user_role == 22 || $user_role == 1) { //truck and export
            $data = DB::select('SELECT DATE(t.truckentry_datetime) AS truckentry_datetime,t.entry_truck,(SELECT COUNT(truck_entry_regs.id)  
                    FROM truck_entry_regs 
                    JOIN users AS u ON u.id=truck_entry_regs.out_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE DATE(truck_entry_regs.out_date)=DATE(t.truckentry_datetime) AND (r.id=4 OR r.id=22 ) AND truck_entry_regs.port_id=?  ) AS exit_truck
                    FROM 
                    (
                    SELECT r.id AS role_id, truck_entry_regs.truckentry_datetime,COUNT(truck_entry_regs.id) AS entry_truck 
                    FROM truck_entry_regs
                    JOIN users AS u ON truck_entry_regs.created_by=u.id 
		            JOIN roles AS r ON r.id=u.role_id
                    JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                    WHERE MONTH(truck_entry_regs.truckentry_datetime)=? AND YEAR(truck_entry_regs.truckentry_datetime)=? AND (r.id=4 OR r.id=22 OR r.id=1)
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND truck_entry_regs.port_id=? AND m.port_id=?
                    GROUP BY DATE(truck_entry_regs.truckentry_datetime)
                    ) AS t', [$port_id, $month, $year, $port_id, $port_id]);

        } else {//for super admin or all
            $data = DB::select('SELECT DATE(t.truckentry_datetime) AS truckentry_datetime ,t.entry_truck,(SELECT COUNT(truck_entry_regs.id)  
                    FROM truck_entry_regs 
                    JOIN users AS u ON u.id=truck_entry_regs.out_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE DATE(truck_entry_regs.out_date)=DATE(t.truckentry_datetime) AND (r.id=4 OR r.id=22 ) AND truck_entry_regs.port_id=?  ) AS exit_truck
                    FROM 
                    (
                    SELECT r.id AS role_id, truck_entry_regs.truckentry_datetime,COUNT(truck_entry_regs.id) AS entry_truck 
                    FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
                    JOIN users AS u ON u.id=truck_entry_regs.created_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE MONTH(truck_entry_regs.truckentry_datetime)=? AND YEAR(truck_entry_regs.truckentry_datetime)=? AND (r.id=4 OR r.id=22)  AND truck_entry_regs.port_id=? AND m.port_id=?
                    GROUP BY DATE(truck_entry_regs.truckentry_datetime)
                    ) AS t', [$port_id, $month, $year, $port_id, $port_id]);
        }


        if ($data) {
            $pdf = PDF::loadView('default.truck.reports.monthly-truck-entry-exit-report', [
                'data' => $data,
                'month' => $r->month_entry_exit,
                'todayWithTime' => $todayWithTime

            ])->setPaper([0, 0, 800, 800]);
            return $pdf->stream($month . '-' . $year . '-truck-entry-exit-report-' . $todayWithTime . '.pdf');

        } else {
            return view('default.truck.not-found', ['requestedDate' => $r->month_entry_exit]);
        }
    }

    public function yearlyTruckEntryExitReport(Request $r) {
        $todayWithTime = date('Y-m-d h:i:s a');
        $year = $r->year;
        $user_role = Auth::user()->role_id;
        $port_id = Session::get('PORT_ID');
        // dd($user_role);

        if ($user_role == 4 || $user_role == 22 || $user_role == 1) {//truck and export
            $data = DB::select('SELECT DATE(t.truckentry_datetime) AS truckentry_datetime ,t.entry_truck,(SELECT COUNT(truck_entry_regs.id)  
                    FROM truck_entry_regs 
                    JOIN users AS u ON u.id=truck_entry_regs.out_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE MONTH(truck_entry_regs.out_date)=MONTH(t.truckentry_datetime) AND (r.id=4 OR r.id=22 ) AND truck_entry_regs.port_id=?) AS exit_truck
                    FROM 
                    (
                    SELECT r.id AS role_id, truck_entry_regs.truckentry_datetime,COUNT(truck_entry_regs.id) AS entry_truck 
                    FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
                    JOIN users AS u ON u.id=truck_entry_regs.created_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE manifests.transshipment_flag=0 AND YEAR(truck_entry_regs.truckentry_datetime)=? AND (r.id=4 OR r.id=22 OR r.id=1)
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND truck_entry_regs.port_id=? AND manifests.port_id=?
                    GROUP BY MONTH(truck_entry_regs.truckentry_datetime)
                    ) AS t', [$port_id, $year, $port_id, $port_id]);

        } else {//for super admin or all
            $data = DB::select('SELECT DATE(t.truckentry_datetime) AS truckentry_datetime ,t.entry_truck,(SELECT COUNT(truck_entry_regs.id)  
                    FROM truck_entry_regs 
                    JOIN users AS u ON u.id=truck_entry_regs.out_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE MONTH(truck_entry_regs.out_date)=MONTH(t.truckentry_datetime) AND (r.id=4 OR r.id=22 ) AND truck_entry_regs.port_id=? ) AS exit_truck
                    FROM 
                    (
                    SELECT r.id AS role_id, truck_entry_regs.truckentry_datetime,COUNT(truck_entry_regs.id) AS entry_truck 
                    FROM manifests JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
                    JOIN users AS u ON u.id=truck_entry_regs.created_by
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE YEAR(truck_entry_regs.truckentry_datetime)=? AND (r.id=4 OR r.id=22 )
                    AND truck_entry_regs.port_id=? AND manifests.port_id=?
                    GROUP BY MONTH(truck_entry_regs.truckentry_datetime)
                    ) AS t', [$port_id, $year, $port_id, $port_id]);
        }


        if ($data) {
            $pdf = PDF::loadView('default.truck.reports.yearly-truck-entry-exit-report', [
                'data' => $data,
                'year' => $year,
                'todayWithTime' => $todayWithTime

            ])->setPaper([0, 0, 800, 800]);
            return $pdf->stream($year . '-truck-entry-exit-report-' . $todayWithTime . '.pdf');

        } else {
            return view('default.truck.not-found', ['requestedDate' => $year]);
        }
    }

    //Only Called From Super Admin Module
    public function fiscalYearWiseTruckEntryReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        $todaysEntry = DB::select("SELECT manifests.manifest, users.name AS entryBy,
(SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
truck_entry_regs.truck_no,truck_entry_regs.truck_type,
truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime,
manifests.transshipment_flag 
FROM truck_entry_regs 
JOIN users ON truck_entry_regs.created_by = users.id 
JOIN manifests ON manifests.id = truck_entry_regs.manf_id
WHERE DATE(truckentry_datetime) BETWEEN ? AND ?
AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$' AND truck_entry_regs.port_id=? AND manifests.port_id=?
ORDER BY truckentry_datetime  DESC", [$firstDate, $lastDate, $port_id, $port_id]);
        if ($todaysEntry) {
            $pdf = PDF::loadView('default.truck.reports.fiscal-year-wise-truck-entry-report', [
                'date' => $todayWithTime,
                'manifestdata' => $todaysEntry,
                'year' => $r->year
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('fiscal-year-wise-truck-entry-report'.$r->year.'-'.$nextYear.'.pdf');
        } else {
        	return view('default.truck.not-found', ['requestedDate' => $todayWithTime]);
        }
    }

    public function fiscalYearWiseTruckExitReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $dates = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        //return $firstDate. " " . $lastDate;
        $todaysEntry = DB::select("SELECT t.id,t.truck_no, t.truck_type, t.driver_card, t.truckentry_datetime, t.out_date, t.out_comment, t.created_by, m.manifest, users.name,
					(SELECT GROUP_CONCAT(shed_yards.shed_yard) FROM shed_yards 
					WHERE FIND_IN_SET(shed_yards.id, m.posted_yard_shed) > 0 ) AS yard_shed_name 
					FROM truck_entry_regs AS t 
					JOIN manifests AS m ON t.manf_id = m.id
					JOIN users ON t.out_by = users.id
					JOIN roles ON roles.id = users.role_id WHERE  DATE(t.out_date) BETWEEN ? AND ?
					AND m.port_id=? AND t.port_id=?", [$firstDate, $lastDate, $port_id, $port_id]);
        $todaysTotalCount = DB::select("SELECT COUNT(t.id) AS id_count
					FROM truck_entry_regs AS t
					JOIN manifests AS m ON t.manf_id = m.id
					JOIN users ON t.out_by = users.id
					JOIN roles ON roles.id = users.role_id 
					WHERE DATE(t.out_date) BETWEEN ? AND ? 
					AND t.port_id=? AND m.port_id=? 
", [$firstDate, $lastDate, $port_id, $port_id]);

        if ($todaysEntry) {
            $pdf = PDF::loadView('default.truck.reports.fiscal-year-wise-truck-exit-report', [
                'date' => $todayWithTime,
                'manifestdata' => $todaysEntry,
                'year' => $r->year,
                'todaysTotalCount' => $todaysTotalCount[0]->id_count
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('fiscal-year-wise-truck-exit-report'.$r->year.'-'.$nextYear.'.pdf');
        } else {
            return view('default.truck.not-found', ['requestedDate' => $todayWithTime]);
        }
    }

    public function dateRangeWiseTruckExitReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $dates = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysEntry = DB::select("SELECT t.id,t.truck_no, t.truck_type, t.driver_card, t.truckentry_datetime, t.out_date, t.out_comment, t.created_by, m.manifest, users.name,
        	(SELECT GROUP_CONCAT(shed_yards.shed_yard) FROM shed_yards 
			WHERE FIND_IN_SET(shed_yards.id, m.posted_yard_shed) > 0 ) AS yard_shed_name 
FROM truck_entry_regs AS t 
JOIN manifests AS m ON t.manf_id = m.id
JOIN users ON t.out_by = users.id
JOIN roles ON roles.id = users.role_id WHERE  DATE(t.out_date) BETWEEN ? AND ? 
AND t.port_id=? AND m.port_id=?", [$r->from_date_truck_Exit, $r->to_date_truck_Exit, $port_id, $port_id]);
        $todaysTotalCount = DB::select("SELECT COUNT(t.id) AS id_count
FROM truck_entry_regs AS t
JOIN manifests AS m ON t.manf_id = m.id
JOIN users ON t.out_by = users.id
JOIN roles ON roles.id = users.role_id WHERE DATE(t.out_date) BETWEEN ? AND ? 
AND t.port_id=? AND m.port_id=?", [$r->from_date_truck_Exit, $r->to_date_truck_Exit, $port_id, $port_id]);
        if ($todaysEntry) {
            $pdf = PDF::loadView('default.truck.reports.date-range-wise-truck-exit-report', [
                'date' => $todayWithTime,
                'todaysTotalCount' => $todaysTotalCount[0]->id_count,
                'manifestdata' => $todaysEntry,
                'from_date_truck_Exit' => $r->from_date_truck_Exit,
                'to_date_truck_Exit' => $r->to_date_truck_Exit
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('daterange-wise-exit-truck-report'.$r->from_date_truck_Exit.'to'.$r->to_date_truck_Exit.'.pdf');
        } else {
            return view('default.truck.not-found', ['requestedDate' => $todayWithTime]);
        }
    }

    public function getDateRangeWiseSlForEntryReport($firstDate, $lastDate) {
    	$port_id = Session::get('PORT_ID');
        $getCount = DB::select('SELECT count(truck_entry_regs.id) AS total_truck
                    FROM truck_entry_regs 
                    JOIN manifests ON manifests.id = truck_entry_regs.manf_id
                    WHERE DATE(truckentry_datetime) BETWEEN ? AND ?
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifests.manifest,"/",2),"/",-1) 
                    NOT REGEXP "^([B-Z]{1}[\-]{1}[B-Z]{1})$" AND truck_entry_regs.port_id=? AND manifests.port_id=?', [$firstDate, $lastDate, $port_id, $port_id]);
        $getCount = $getCount[0]->total_truck;
        $tempCount = 0;
        $itemPerPage = 400;
        $totalArraylength = (int)($getCount / $itemPerPage);
        $getModValue = (int)$getCount % (int)$itemPerPage;
        $slArray = [];
        $firstSl = 1;
        $lastSl = $itemPerPage;
        if ($totalArraylength == 0 && $getModValue != 0) {
            $slArray[0] = array(
                'page' => 0,
                'firstSl' => 1,
                'lastSl' => $getModValue
            );
        }
        for ($i = 0; $i < $totalArraylength; $i++) {
            $lastLimit = 0;
            $slArray[$i] = array(
                'page' => $i,
                'firstSl' => $firstSl,
                'lastSl' => $lastSl
            );
            $lastLimit = $totalArraylength - 1;
            //return $lastLimit;
            $tempCount = $tempCount + $itemPerPage;
            $firstSl = $tempCount + 1;
            //$tempCount = $tempCount + $itemPerPage;
            $lastSl = $lastSl + $itemPerPage;
            if ($getModValue != 0 && $i == $lastLimit) {
                $slArray[$i + 1] = array(
                    'page' => $i + 1,
                    'firstSl' => $firstSl,
                    'lastSl' => $firstSl + $getModValue - 1
                );
            }
            //$tempCount = $getCount + $itemPerPage;
        }
        //return $getCount;
        return json_encode($slArray);
    }

    public function dateRangeWiseTruckEntryReport(Request $r) {
    	$port_id = Session::get('PORT_ID');
        $ranges = explode('-', $r->range);
        $itemsPerPage = 400;
        $pagenumber = filter_var($r->slValue, FILTER_SANITIZE_NUMBER_INT);
        $sl = $ranges[0];
        $firstLimit = $ranges[0] - 1;//($pagenumber)*$itemsPerPage;
        $lastLimit = $itemsPerPage;//$ranges[1];

        $todayWithTime = date('Y-m-d h:i:s a');
        $todaysEntry = DB::select("SELECT manifests.manifest, users.name AS entryBy,
        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
        truck_entry_regs.truck_no,truck_entry_regs.truck_type,
        truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime,
        manifests.transshipment_flag 
        FROM truck_entry_regs 
        JOIN users ON truck_entry_regs.created_by = users.id 
        JOIN manifests ON manifests.id = truck_entry_regs.manf_id
        WHERE DATE(truckentry_datetime) BETWEEN ? AND ?
        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$' AND truck_entry_regs.port_id=? AND manifests.port_id=? 
        ORDER BY truckentry_datetime  DESC LIMIT ?, ?", [$r->from_date_v, $r->to_date_v, $port_id, $port_id, $firstLimit, $lastLimit]);//->toArray();

        if ($todaysEntry) {
            $pdf = PDF::loadView('default.truck.reports.date-range-wise-truck-entry-report', [
                'date' => $todayWithTime,
                'manifestdata' => $todaysEntry,
                'from_date' => $r->from_date_v,
                'to_date' => $r->to_date_v,
                'sl' => $sl,
                'ranges' => $r->range
            ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
            return $pdf->stream('date-range-wise-truck-entry-report'.$r->from_date_v.'to'.$r->to_date_v.'sl'.$firstLimit.'to'.$lastLimit.'.pdf');
        } else {
            return view('default.truck.not-found', ['requestedDate' => $todayWithTime]);
        }
    }
}
