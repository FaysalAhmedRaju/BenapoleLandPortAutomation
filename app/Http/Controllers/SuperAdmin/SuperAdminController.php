<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Session;
use App\Role;
use App\truck_entry_reg;
use Illuminate\Database\Eloquent;
use Exception;
use Response;

class SuperAdminController extends Controller
{
    public function welcome() {
        $template='19';
        return view('default.super-admin.welcome-super-admin');
    }

    public function superAdminYearlyReportsView()
    {
        return view('default.super-admin.super-admin-report');
    }

    public function bankVoucherEntryView()
    {

        $year = DB::select('SELECT DISTINCT YEAR(ex.ex_date) AS year  FROM expenditures ex');

        return view('default.super-admin.bank-voucher-super-admin', compact('year'));
    }


    public function getAllbankVouchersData($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

        $check = DB::select('SELECT v.id AS voucher_id,ex.created_at,ex.in_ex_date, ex.id AS ex_id,v.comment,
v.cheque_no,ex.sub_head_id,v.created_at,ex.amount,v.organization_name,
v.bank_vouchar_no, sh.acc_sub_head 
FROM head_office_in_ex AS ex JOIN head_office_bank_vouchers  AS v ON ex.bank_vouchar_id=v.id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
WHERE v.bank_vouchar_no=?', [$finalVoucherNo]);

        return json_encode($check);
    }


    public function saveBankVoucherData(Request $r)
    {

        $voucher = DB::table('head_office_bank_vouchers')
            ->where('bank_vouchar_no', $r->vouchar_no)
            ->get();
//        $currentTime = date('Y-m-d H:i:s');
//$file = fopen("Truckentry.txt","w");
//                echo fwrite($file,"Hello ".$r);
//                fclose($file);
//                return 'okkkk';

        $new_voucher_id = 0;
        if ($voucher == '[]') {//new vouvher

            $new_voucher_id = DB::table('head_office_bank_vouchers')->insertGetId(

                [
                    'bank_vouchar_no' => $r->vouchar_no,
                    'bank_vouchar_date' => $r->vouchar_date,
                    'organization_name' => $r->organization_name,
                    'comment' => $r->comment,
                    'cheque_no' => $r->cheque_no,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );

        }

        //$file = fopen("Truckentry.txt","w");
//                echo fwrite($file,"Hello ".$voucher);
//                fclose($file);
//                return 'okkkk';
        //  $new_voucher_id==0 ? $voucher[0]->id:$new_voucher_id;

        $checkSubheadExist = DB::select('SELECT ex.id FROM head_office_in_ex AS ex 
                JOIN head_office_bank_vouchers AS v ON  ex.bank_vouchar_id=v.id 
                WHERE v.id=? AND ex.sub_head_id=?', [$new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id, $r->sub_head_id]);

        /* $file = fopen("Truckentry.txt","w");
         echo fwrite($file,"Hello ".$checkSubheadExist);
         fclose($file);
         return 'okkkk';*/


        if ($checkSubheadExist == []) {
            DB::table('head_office_in_ex')
                ->insert([
                    'bank_vouchar_id' => $new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id,
                    'sub_head_id' => $r->sub_head_id,
                    'amount' => $r->amount,
                    'in_ex_date' => $r->in_ex_date,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            return Response::json(['k' => 'k'], 201);
        } else {//subhead already exists and return
            return Response::json(['k' => 'k'], 204);
        }


    }



    public function updateBankVoucherData(Request $r, $id)
    {
        $voucher = DB::table('head_office_bank_vouchers')
            ->where('id', $r->voucher_id)
            ->update([
                'bank_vouchar_no' => $r->vouchar_no,
                'bank_vouchar_date' => $r->vouchar_date,
                'organization_name' => $r->organization_name,
                'comment' => $r->comment,
                'cheque_no' => $r->cheque_no
            ]);

        $expenditure = DB::table('head_office_in_ex')
            ->where('id', $id)
            ->update([
                // 'vouchar_id' => $r->vouchar_id,
                'sub_head_id' => $r->sub_head_id,
                'amount' => $r->amount,

                'in_ex_date' => $r->in_ex_date,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        /*$file = fopen("Truckentry.txt","w");
         echo fwrite($file,"Hello ".$voucher);
         fclose($file);
         return;*/

        if ($expenditure == 0 || $expenditure == 1) {
            return Response::json(['updated' => 'updated'], 200);
        } else {
            return Response::json(['wrong' => 'wrong'], 202);
        }


    }



    public function getBankVoucherDetails($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

//        $file = fopen("Truckentry.txt","w");
//       echo fwrite($file,"Hello ".$finalVoucherNo);
//       fclose($file);
//       return 'okkkk';

        $voucher = DB::select('SELECT v.id AS v_id,v.bank_vouchar_no, v.created_at,v.bank_vouchar_date,v.comment,v.cheque_no,v.organization_name, ex.bank_vouchar_id FROM  head_office_bank_vouchers AS v LEFT JOIN  head_office_in_ex AS ex ON v.id=ex.bank_vouchar_id
WHERE v.bank_vouchar_no=? LIMIT 1', [$finalVoucherNo]);

        if ($voucher == true) {
            return json_encode($voucher);
        } else {
            return Response::json(['k' => 'k'], 204);
        }


    }

    public function deleteBankVouchersData($id)
    {
        $expenditure = DB::table('head_office_in_ex')->where('id', $id)->delete();

        if ($expenditure == 1) {
            return Response::json(['deleted' => 'deleted'], 200);
        } else {
            return Response::json(['wrong' => 'wrong'], 202);
        }

    }


    public function bankVoucherReport($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;


        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select('SELECT ex.*, v.bank_vouchar_date,(SELECT SUM(exx.amount) FROM head_office_in_ex AS exx WHERE exx.bank_vouchar_id=v.id ) AS total,
 v.bank_vouchar_no,sh.acc_sub_head,v.organization_name,v.cheque_no,v.comment
 FROM head_office_bank_vouchers AS v JOIN head_office_in_ex AS ex ON v.id=ex.bank_vouchar_id
        JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
        WHERE v.bank_vouchar_no=?', [$finalVoucherNo]);

        $pdf = PDF::loadView('default.super-admin.reports.bank-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure

        ])->setPaper([0, 0, 700.661, 700.63], 'landscape');


        return $pdf->stream('bankVoucherReport.pdf');

    }



    public function superAdminReportsView() {
        $year = DB::select('SELECT DISTINCT YEAR(manifests.manifest_created_time) AS year FROM manifests WHERE manifests.manifest_created_time IS NOT NULL');
        return view('default.super-admin.super-admin-reports-view',compact('year'));
    }

    public function importExportInfoReport(Request $r) {
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $data = DB::select('SELECT *, (SELECT count(truck_entry_regs.id) 
                            FROM truck_entry_regs
                            WHERE
                            MONTH(truck_entry_regs.truckentry_datetime) = base.manifest_month) AS total_foreign_truck_count,
                            (SELECT count(truck_deliverys.id) 
                            FROM truck_deliverys
                            WHERE
                            MONTH(truck_deliverys.delivery_dt) = base.manifest_month) AS total_local_truck_count,
                            (SELECT SUM(truck_entry_regs.receive_weight)/1000
                            FROM truck_entry_regs
                            WHERE
                            MONTH(truck_entry_regs.truckentry_datetime) = base.manifest_month) AS receive_weight_ton
                            FROM (
                            SELECT count(manifests.id) AS total_manifest_count, 
                            MONTH(manifests.manifest_created_time) AS manifest_month, 
                            MONTHNAME(manifests.manifest_created_time) AS month_name,
                            YEAR(manifests.manifest_created_time) AS year_name
                            FROM
                            manifests
                            WHERE DATE(manifests.manifest_created_time) BETWEEN ? AND ?
                            GROUP BY MONTH(manifests.manifest_created_time) ) AS base',[$firstDate, $lastDate]);
        if($data){
            $pdf = PDF::loadView('default.super-admin.reports.import-export-info-report', [

                'data' => $data,
                'todayWithTime' => $todayWithTime,
                'year' => $r->year
            ])->setPaper([0, 0, 1200, 1200], 'landscape');
            return $pdf->stream('importExportInfoReportPDF.pdf');
        }else{
            return view('default.super-admin.not-found',compact('requestedDate',$todayWithTime));
        }
    }





    //some yearly report for super admin





} 
