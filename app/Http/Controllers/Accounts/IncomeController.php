<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Auth;
use PDF;
use App\User;
use Exception;
use Response;

class IncomeController extends Controller
{
    public function incomeEntryView()
    {
//        $year = date('Y');
//        $yearly_expenditure = DB::table('some_restrictions')
//            ->where('restriction_code', 1)
//            ->where('year', $year)
//            ->select('amount', 'restriction_name')
//            ->get();
//
//        $current_expens = DB::select('SELECT SUM(debit) AS total_expense FROM transactions WHERE YEAR(trans_dt)=?	', [$year]);

        return view('default.accounts.income-entry'/*, compact('yearly_expenditure', 'current_expens')*/);
    }


    public function getAllIncomeSubHead()
    {
        $check = DB::select('SELECT sh.acc_sub_head,sh.id FROM acc_sub_head AS sh JOIN acc_head AS h ON sh.head_id=h.id
        WHERE h.in_ex_status=2');
        return json_encode($check);
    }

    public function saveIncome(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;

        $voucher = DB::table('vouchers')
            ->where('vouchar_no', $r->vouchar_no)
            ->where('in_ex_status', 2)
            ->get();
        $new_voucher_id = 0;
        if ($voucher == '[]') {
            $new_voucher_id = DB::table('vouchers')->insertGetId(
                [
                    'vouchar_no' => $r->vouchar_no,
                    'vouchar_date' => $r->vouchar_date,
                    'in_ex_status' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $user_id
                ]
            );
        }
        $checkSubheadExist = DB::select('SELECT i.id FROM transactions AS i
                JOIN vouchers AS v ON  i.voucher_id=v.id 
                WHERE v.id=? AND i.sub_head_id=?', [$new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id, $r->sub_head_id]);
        if ($checkSubheadExist == []) {
            DB::table('transactions')
                ->insert([
                    'voucher_id' => $new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id,
                    'sub_head_id' => $r->sub_head_id,
                    'credit' => $r->amount,
                    'trans_dt' => $r->vouchar_date,
                    'port_id' => $port_id,
                    'userid' => $user_id,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $user_id
                ]);
            return Response::json(['k' => 'k'], 201);
        } else {
            return Response::json(['k' => 'k'], 204);
        }
    }


    public function getIncomeVoucherDetails($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

        $voucher = DB::select('SELECT v.id AS v_id,v.vouchar_no, v.create_dt,v.vouchar_date, t.voucher_id
FROM  vouchers AS v 
LEFT JOIN  transactions AS t ON v.id= t.voucher_id
                  WHERE v.vouchar_no=? AND v.in_ex_status="2" LIMIT 1', [$finalVoucherNo]);

        if ($voucher == true) {
            return json_encode($voucher);
        } else {
            return Response::json(['k' => 'k'], 204);
        }


    }

    public function getAllIncome($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

        $check = DB::select('SELECT v.id AS voucher_id,t.trans_dt, t.id AS income_id,t.sub_head_id,v.vouchar_date,t.credit,v.vouchar_no, sh.acc_sub_head ,t.entry_dt 
FROM transactions AS t 
JOIN vouchers  AS v ON t.voucher_id=v.id
        JOIN acc_sub_head AS sh ON t.sub_head_id=sh.id
        WHERE v.vouchar_no=? AND v.in_ex_status="2"', [$finalVoucherNo]);

        return json_encode($check);
    }

    public function updateIncome(Request $r, $id)
    {
        $user_id = Auth::user()->id;
        $voucher = DB::table('vouchers')
            ->where('id', $r->voucher_id)
            ->update([
                'vouchar_no' => $r->vouchar_no,
                'vouchar_date' => $r->vouchar_date,
                'updated_at' =>date('Y-m-d H:i:s'),
                'updated_by' => $user_id
            ]);

        $expenditure = DB::table('transactions')
            ->where('id', $id)
            ->update([
                'sub_head_id' => $r->sub_head_id,
                'credit' => $r->amount,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id
            ]);

        if ($expenditure == 0 || $expenditure == 1) {
            return Response::json(['updated' => 'updated'], 200);
        } else {
            return Response::json(['wrong' => 'wrong'], 202);
        }
    }

    public function deleteIncomeData($id)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::table('transactions')
            ->where('id',$id)
            ->get()->first();

        $checkExpenditureTableBlankOrNot = DB::select('SELECT * FROM transactions WHERE transactions.voucher_id=?', [$data->voucher_id]);

        if(count($checkExpenditureTableBlankOrNot) > 1){
            $expenditure = DB::table('transactions')->where('id', $id)->where('port_id',$port_id)->delete();
        }else{
            $expenditure = DB::table('transactions')->where('id', $id)->where('port_id',$port_id)->delete();
            $expenditure = DB::table('vouchers')->where('id', $data->voucher_id)->delete();
        }

        if ($expenditure) {
            return Response::json(['deleted' => 'deleted'], 200);
        } else {
            return Response::json(['wrong' => 'wrong'], 202);
        }

    }

    public function dateWiseVoucherIncomeReport(Request $r)
    {

        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head,(SELECT SUM(exx.credit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total
FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN users ON users.id = ex.userid
WHERE ex.trans_dt=? and ex.port_id=? AND v.in_ex_status='2' ORDER BY v.id DESC", [$r->from_date,$port_id]);

        $total_expenditure_amount = DB::select("SELECT SUM(credit) AS total_amount_vou FROM vouchers AS vou
JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
 WHERE  trans_dt =? and port_id=? AND vou.in_ex_status='2'", [$r->from_date,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.date-wise-voucher-income-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'dateWise' => $r->from_date


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('DateWiseVoucherIncomeReport.pdf');

    }

    public function monthWiseVoucherIncomeReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select(" SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head
FROM vouchers AS v 
JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN users ON users.id = ex.userid
WHERE  v.in_ex_status='2' AND ex.port_id=? AND  ex.trans_dt BETWEEN ? AND ? ORDER BY v.id DESC", [$port_id,$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select(" SELECT SUM(credit) AS total_amount_vou  FROM vouchers AS vou
JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
WHERE vou.in_ex_status='2' AND port_id=? AND trans_dt BETWEEN ? AND ?", [$port_id,$r->from_date_v, $r->to_date_v]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.month-wise-voucher-income-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('monthWiseVoucherReport.pdf');

    }

    public function todaysVoucherIncomeReport()
    {
        $dates = date('Y-m-d');
        $port_id = Session::get('PORT_ID');

        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select('SELECT users.username,ex.*,v.vouchar_date,v.vouchar_no,sh.acc_sub_head,
(SELECT SUM(exx.credit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total
                        FROM vouchers AS v 
                        JOIN transactions AS ex ON v.id=ex.voucher_id
                        JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                        JOIN users ON users.id = ex.userid
                        WHERE v.in_ex_status="2" AND DATE(ex.trans_dt)=? AND ex.port_id=? ORDER BY v.id DESC', [$dates,$port_id]);

        $total_expenditure_amount = DB::select("SELECT SUM(credit) AS total_amount_vou FROM vouchers AS vou
        JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
          WHERE vou.in_ex_status='2' AND  DATE(ex_p.trans_dt) =? AND ex_p.port_id=? ", [$dates,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;


        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.todays-income-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount
        ])->setPaper([0, 0, 800.661, 800.63], 'a4');


        return $pdf->stream('todaysVoucherIncomeReport.pdf');
    }

    public function incomeVoucherReport($v_no, $year)
    {
        $port_id = Session::get('PORT_ID');
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;


        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select(' SELECT users.username,ex.*, v.vouchar_date,(SELECT SUM(exx.credit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total, 
 v.vouchar_no,sh.acc_sub_head FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
        JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
        JOIN users ON users.id = ex.userid
        WHERE v.in_ex_status="2" AND  v.vouchar_no=? AND ex.port_id=? ', [$finalVoucherNo,$port_id]);

        $pdf = PDF::loadView('default.accounts.reports.income-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('incomeVoucherReport.pdf');

    }

    public function getIncomeSourceWiseReportData()
    {
        $getValues = DB::select("SELECT * FROM acc_head WHERE in_ex_status ='2' ");
        return json_encode($getValues);
    }


    public function sourceWiseIncomeVoucherReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $voucher_id = (int) filter_var($r->voucher_report, FILTER_SANITIZE_NUMBER_INT);

        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head, acc_h.*
                                    FROM vouchers AS v 
                                    JOIN transactions AS ex ON v.id=ex.voucher_id
                                    JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                                    JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
                                     JOIN users ON users.id = ex.userid
                                    WHERE v.in_ex_status='2' AND  acc_h.id = ? AND ex.port_id=? ORDER BY v.id DESC", [$voucher_id,$port_id]);
        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $source_name = $expenditure[0]->acc_head;

        $total_expenditure_amount = DB::select("SELECT SUM(credit) AS total_amount_vou
                                    FROM vouchers AS v 
                                    JOIN transactions AS ex ON v.id=ex.voucher_id
                                    JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                                    JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
                                    WHERE v.in_ex_status='2' AND  acc_h.id = ? AND ex.port_id=? ORDER BY v.id DESC", [$voucher_id,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;



        $pdf = PDF::loadView('default.accounts.reports.source-wise-income-voucher', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'source_name' => $source_name


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('sourceWiseIncomeVoucherReport.pdf');

    }


}
