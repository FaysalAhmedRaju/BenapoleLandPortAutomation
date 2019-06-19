<?php

namespace App\Http\Controllers\Posting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use Session;
use App\Manifest;
use App\truck_entry_reg;
use App\Role;
use Exception;

class PostingReportController extends Controller
{



    // public function reportPosting() {
    //     return view('posting.reportPosting');
    // }


    public function postingReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $requestedDate = $r->from_date;
        $user_role = Auth::user()->role_id;

        /*  $DWRDate = DB::select("SELECT CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest,
            manifest,manifests.manifest_created_time,manifests.gweight,manifests.id,
            truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.truckentry_datetime
            FROM manifests
            INNER JOIN truck_entry_regs ON truck_entry_regs.manf_id=manifests.id
            WHERE DATE(manifests.manifest_created_time)=?  ORDER BY justManifest DESC
            ",[$r->from_date]);*/

        if ( $user_role == 7 ) {//WeighBridge
            $dateWisePostingData = DB::select('SELECT manifests.id AS m_id, manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
                manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight,
                manifests.nweight AS m_nweight, manifests.package_no AS m_package_no, manifests.package_type AS m_package_type,
                manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
                manifests.lc_no AS m_lc_no, manifests.lc_date AS m_lc_date, manifests.ind_be_no AS m_ind_be_no,
                manifests.ind_be_date AS m_ind_be_date,GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed,
                cargo_details.cargo_name, vatregs.NAME, vatregs.BIN AS m_vat_id, vatregs.ADD1
                FROM manifests
                JOIN cargo_details ON manifests.goods_id = cargo_details.id
                JOIN vatregs ON vatregs.id = manifests.vatreg_id
                JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
                WHERE DATE(manifests.manifest_created_time)=? AND manifests.port_id =?
                GROUP BY manifests.id',[$requestedDate,$port_id]);

        } else if($user_role== 12){
            $dateWisePostingData = DB::select('SELECT manifests.id AS m_id, manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
                manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight,
                manifests.nweight AS m_nweight, manifests.package_no AS m_package_no, manifests.package_type AS m_package_type,
                manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
                manifests.lc_no AS m_lc_no, manifests.lc_date AS m_lc_date, manifests.ind_be_no AS m_ind_be_no,
                manifests.ind_be_date AS m_ind_be_date,GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed,
                cargo_details.cargo_name, vatregs.NAME, vatregs.BIN AS m_vat_id, vatregs.ADD1
                FROM manifests
                JOIN cargo_details ON manifests.goods_id = cargo_details.id
                JOIN vatregs ON vatregs.id = manifests.vatreg_id
                JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
                WHERE manifests.transshipment_flag = 1 AND DATE(manifests.manifest_created_time)=? AND manifests.port_id =?
                GROUP BY manifests.id',[$requestedDate,$port_id]);
        } else{
            $dateWisePostingData = DB::select('SELECT manifests.id AS m_id, manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
                manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight,
                manifests.nweight AS m_nweight, manifests.package_no AS m_package_no, manifests.package_type AS m_package_type,
                manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
                manifests.lc_no AS m_lc_no, manifests.lc_date AS m_lc_date, manifests.ind_be_no AS m_ind_be_no,
                manifests.ind_be_date AS m_ind_be_date,GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed,
                cargo_details.cargo_name, vatregs.NAME, vatregs.BIN AS m_vat_id, vatregs.ADD1
                FROM manifests
                JOIN cargo_details ON manifests.goods_id = cargo_details.id
                JOIN vatregs ON vatregs.id = manifests.vatreg_id
                JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
                WHERE DATE(manifests.manifest_created_time)=? AND manifests.port_id=?
                GROUP BY manifests.id',[$requestedDate,$port_id]);
        }
        if($dateWisePostingData) {
            $pdf = PDF::loadView('default.posting.reports.datewise-posting-report',[
                'dateWisePostingData'=>$dateWisePostingData,
                'from_date' => $r->from_date,
                'todayWithTime' => $todayWithTime
            ])->setPaper([0, 0, 808.661, 1020.63], 'landscape');
            return $pdf->stream('DateWisePostingReport.pdf');
        } else {
            return view('default.posting.not-found',compact('requestedDate'));
        }
    }

    //=============Other Reports=============
    public function otherReportsPostingView() {
        return view('default.posting.other-reports-view');
    }

    public function truckEntryDoneButPostingBranchEntryNotDoneReport() {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $today = date('Y-m-d');
        $data = DB::select("SELECT CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest,
manifest,
(SELECT truck_entry_regs.truck_no
FROM truck_entry_regs WHERE truck_entry_regs.port_id = ? AND  truck_entry_regs.manf_id=manifests.id LIMIT 1 ) AS truck_no,
(SELECT truck_entry_regs.truck_type
FROM truck_entry_regs WHERE  truck_entry_regs.port_id = ? AND  truck_entry_regs.manf_id=manifests.id LIMIT 1 ) AS truck_type
FROM manifests WHERE manifests.gweight IS NULL AND manifests.port_id = ?  ORDER BY justManifest DESC",[$port_id,$port_id,$port_id]);
        $pdf = PDF::loadView('default.posting.reports.truck-entry-done-but-posting-entry-not-done-report',[
            'todayWithTime' => $todayWithTime,
            'data' => $data,
        ]);
        return $pdf->stream('truckEntryDoneButPostingEntryNotDoneReport-'.$today.'.pdf');
    }

    //Monthly Posting Report
    public function monthlyPostingEntryReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $monthShowPdf = $r->month_entry;
        $year = date("Y",strtotime($r->month_entry));

        $month = date("m",strtotime($r->month_entry));

        $todayWithTime = date('Y-m-d h:i:s a');
        $user_role = Auth::user()->role_id;

        //  dd($user_role); 7

        if ($user_role == 7) {//posting

            $data = DB::select('SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE MONTH(manifests.manifest_created_time)=?
                AND YEAR(manifests.manifest_created_time)=? AND manifests.transshipment_flag =0 AND  manifests.port_id = ?
                GROUP BY DATE(manifests.manifest_created_time)', [$month, $year,$port_id]);

        }elseif ($user_role == 12){//transshipment}
            $data = DB::select('    SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE MONTH(manifests.manifest_created_time)=?
                AND YEAR(manifests.manifest_created_time)=? AND manifests.transshipment_flag =1 AND  manifests.port_id = ?
                GROUP BY DATE(manifests.manifest_created_time)', [$month, $year,$port_id]);

        } else {//for super admin or all

            $data = DB::select('       SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE MONTH(manifests.manifest_created_time)=?
                AND YEAR(manifests.manifest_created_time)=? AND  manifests.port_id = ?
                GROUP BY DATE(manifests.manifest_created_time)', [$month, $year,$port_id]);
        }
//        dd($data);
        $pdf = PDF::loadView('default.posting.reports.monthly-posting-entry-report',[
            'todayWithTime' => $todayWithTime,
            'data' => $data,
            'month' => $r->month_entry,
        ]);
        return $pdf->stream('MonthlyPostingEntryReport-'.$monthShowPdf.'.pdf');
    }


    //Yearly Posting Report
    public function yearlyPostingEntryReport(Request $r) {
        $port_id = Session::get('PORT_ID');
        $year = $r->year;
        //  dd($year);
        // $year = date("Y",strtotime($r->month_entry));

        // $month = date("m",strtotime($r->month_entry));

        $todayWithTime = date('Y-m-d h:i:s a');
        $user_role = Auth::user()->role_id;

        //  dd($user_role);

        if ($user_role == 7) {//posting

            $data = DB::select('SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE 
                 YEAR(manifests.manifest_created_time)=? AND manifests.transshipment_flag =0 AND  manifests.port_id = ?
                GROUP BY MONTH(manifests.manifest_created_time)', [$year,$port_id]);

        }elseif ($user_role == 12){//transshipment}
            $data = DB::select('  SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE 
                 YEAR(manifests.manifest_created_time)=? AND manifests.transshipment_flag =1 AND  manifests.port_id = ?
                GROUP BY MONTH(manifests.manifest_created_time)', [$year,$port_id]);

        } else {//for super admin or all

            $data = DB::select('  SELECT manifests.manifest_created_time,
                COUNT(manifests.id) AS manifest_count 
                FROM manifests 
                WHERE 
                 YEAR(manifests.manifest_created_time)=?  AND  manifests.port_id = ?
                GROUP BY MONTH(manifests.manifest_created_time)', [$year,$port_id]);
        }
        // dd($data);
        $pdf = PDF::loadView('default.posting.reports.yearly-posting-entry-report',[
            'todayWithTime' => $todayWithTime,
            'data' => $data,
            'year' => $year,
        ]);
        return $pdf->stream('YearlyPostingEntryReport-'.$year.'.pdf');
    }



    public function postingAllReports(){
        $port_id = Session::get('PORT_ID');
        $year = DB::select('SELECT DISTINCT YEAR(m.manifest_created_time) AS year 
            FROM manifests m WHERE m.manifest_created_time IS NOT NULL AND m.manifest_created_time != 0 AND m.port_id=?',[$port_id]);
        return view('default.posting.posting-all-reports-view', compact('year'));
    }




    public function yearWisePostingReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        //return $firstDate. " " . $lastDate;
        $requestedDate = $r->year.  ' to ' . $nextYear;
       // dd($requestedDate);
        $mainData = DB::select("SELECT GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed, manifests.posted_yard_shed AS posted_yard_shed_id, manifests.id AS m_id,manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight, manifests.nweight AS m_nweight, manifests.package_no AS m_package_no,
manifests.package_type AS m_package_type, manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
manifests.lc_no AS m_lc_no,manifests.lc_date AS m_lc_date,manifests.ind_be_no AS m_ind_be_no,manifests.ind_be_date AS m_ind_be_date,
cargo_details.cargo_name,vatregs.NAME,vatregs.BIN AS m_vat_id,
vatregs.ADD1
 FROM manifests 
JOIN cargo_details ON manifests.goods_id = cargo_details.id
JOIN vatregs ON vatregs.id = manifests.vatreg_id
JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
WHERE DATE(manifests.manifest_created_time) BETWEEN ? AND ? AND manifests.port_id =?
 GROUP BY manifests.id", [$firstDate, $lastDate,$port_id]);

        if ($mainData) {
            $pdf = PDF::loadView('default.posting.reports.year-wise-posting-report', [

                'mainData' => $mainData,
                'todayWithTime' => $todayWithTime,
                'year' => $r->year
            ])->setPaper([0, 0, 1200, 1200], 'landscape');
            return $pdf->stream('YearWisePostingReportPdf.pdf');
        } else {

            return view('default.posting.not-found', compact('requestedDate'));
        }
    }



    public function monthWisePostingEntryReport(Request $r)
    {  $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $mainData = DB::select("SELECT GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed, manifests.posted_yard_shed AS posted_yard_shed_id, manifests.id AS m_id,manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight, manifests.nweight AS m_nweight, manifests.package_no AS m_package_no,
manifests.package_type AS m_package_type, manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
manifests.lc_no AS m_lc_no,manifests.lc_date AS m_lc_date,manifests.ind_be_no AS m_ind_be_no,manifests.ind_be_date AS m_ind_be_date,
cargo_details.cargo_name,vatregs.NAME,vatregs.BIN AS m_vat_id,
vatregs.ADD1
 FROM manifests 
JOIN cargo_details ON manifests.goods_id = cargo_details.id
JOIN vatregs ON vatregs.id = manifests.vatreg_id
JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
WHERE DATE(manifests.manifest_created_time) BETWEEN ? AND ? AND manifests.port_id =?
 GROUP BY manifests.id", [$r->from_date_posting, $r->to_date_posting,$port_id]);//->toArray();
        $requestedDate = $r->from_date_posting.  ' to ' . $r->to_date_posting;
//
//        if ($todaysEntry == []) {
//            return view('Export.error');
//        }

        if ($mainData) {
            $pdf = PDF::loadView('default.posting.reports.month-wise-posting-report', [

                'mainData' => $mainData,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date_posting,
                'to_date' => $r->to_date_posting
            ])->setPaper([0, 0, 1200, 1200], 'landscape');
            return $pdf->stream('MonthWisePostingReportPdf.pdf');
        } else {
            return view('default.posting.not-found', compact('requestedDate'));
        }
    }

}
