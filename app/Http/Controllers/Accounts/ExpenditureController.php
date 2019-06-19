<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use App\Http\Controllers\GlobalFunctionController;
use PDF;
use Session;
use App\Role;
use Illuminate\Http\Request;
use App\truck_entry_reg;
use DB;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use Exception;
use Response;

class ExpenditureController extends Controller
{
    public function expenditureEntryView()
    {
        $year = date('Y');
        $yearly_expenditure = DB::table('some_restrictions')
            ->where('restriction_code', 1)
            ->where('year', $year)
            ->select('amount', 'restriction_name')
            ->get();
        $current_expens = DB::select('SELECT SUM(debit) AS total_expense FROM transactions WHERE YEAR(trans_dt)=?', [$year]);

        return view('default.accounts.expenditure-entry', compact('yearly_expenditure', 'current_expens'));
    }

    public function showExpenseLimitAlert()
    {
        $fiscal_year = 0;
        $first_year = 0;
        $second_year = 0;
        $year = date('Y');
        $month = date('n');

        if ($month > 6){
            $first_year = $year;
            $second_year = $first_year + 1;
            $fiscal_year = $first_year.'-'.$second_year;
        }else{
            $first_year = $year;
            $second_year = $first_year - 1;
            $fiscal_year = $second_year.'-'.$first_year;
        }


        $expenditure_limit_details = DB::select('SELECT * FROM
(SELECT 
(SELECT SUM(ex.debit) FROM transactions AS ex WHERE  YEAR(ex.trans_dt)=? ) AS current_expense_year,
(SELECT SUM(ex.debit) FROM transactions AS ex WHERE  MONTH(ex.trans_dt)=? ) AS current_expense_month,
(SELECT SUM(budget_in_ex.amount) AS amount FROM budget_in_ex     JOIN acc_sub_head  ON acc_sub_head.id = budget_in_ex.subhead_id

JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE acc_head.in_ex_status = "1" AND budget_in_ex.monthly_yearly_flag = "1" AND budget_in_ex.fiscal_year =?) AS yearly_limit,
(

SELECT SUM(budget_in_ex.amount) AS amount FROM budget_in_ex JOIN acc_sub_head  ON acc_sub_head.id = budget_in_ex.subhead_id

JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE acc_head.in_ex_status = "1" AND budget_in_ex.monthly_yearly_flag = "0" AND budget_in_ex.fiscal_year =?) AS monthly_limit
) AS final', [$year, $month, $fiscal_year, $fiscal_year]);

        return json_encode($expenditure_limit_details);
    }

    public function headWiseMonthlyYearlyData($head_id)
    {
        $fiscal_year = 0;
        $first_year = 0;
        $second_year = 0;
        $year = date('Y');
        $month = date('n');

        if ($month > 6){
            $first_year = $year;
            $second_year = $first_year + 1;
            $fiscal_year = $first_year.'-'.$second_year;
        }else{
            $first_year = $year;
            $second_year = $first_year - 1;
            $fiscal_year = $second_year.'-'.$first_year;
        }

        $headID =  DB::select('SELECT acc_head.id AS h_id FROM acc_sub_head JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE acc_sub_head.id =?', [$head_id]);
        $id = $headID[0]->h_id;


        $expenditure_limit_details = DB::select('SELECT * FROM(

SELECT  (
SELECT SUM(ex.debit) AS totalAmountYearly FROM transactions AS ex JOIN acc_sub_head  ON acc_sub_head.id = ex.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE   acc_head.id =? AND YEAR(ex.trans_dt) =?) AS current_expense_yearly_head,

(SELECT SUM(ex.debit) AS totalAmountMonthly FROM transactions AS ex JOIN acc_sub_head  ON acc_sub_head.id = ex.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE   acc_head.id =? AND MONTH(ex.trans_dt) =?) AS current_expense_Monthly_head,

(SELECT SUM(budget_in_ex.amount) AS amount FROM budget_in_ex JOIN acc_sub_head  ON acc_sub_head.id = budget_in_ex.subhead_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE acc_head.id =? AND
budget_in_ex.monthly_yearly_flag = "1" AND budget_in_ex.fiscal_year =?) AS yearly_head_limit,

(SELECT SUM(budget_in_ex.amount) AS amount FROM budget_in_ex JOIN acc_sub_head  ON acc_sub_head.id = budget_in_ex.subhead_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id WHERE acc_head.id =? AND
budget_in_ex.monthly_yearly_flag = "0" AND budget_in_ex.fiscal_year =?) AS monthly_head_limit

) AS final', [$id,$year,$id,$month,$id,$fiscal_year,$id,$fiscal_year]);

        return json_encode($expenditure_limit_details);


    }


    public function getVoucherDetails($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

        $voucher = DB::select('SELECT v.id AS v_id,v.vouchar_no, v.created_at,v.vouchar_date, transactions.voucher_id
FROM  vouchers AS v 
LEFT JOIN  transactions  ON v.id = transactions.voucher_id
WHERE v.vouchar_no=? AND v.in_ex_status="1" LIMIT 1', [$finalVoucherNo]);

        if ($voucher == true) {
            return json_encode($voucher);
        } else {
            return Response::json(['k' => 'k'], 204);
        }


    }





    public function getYearFoundInExpenditure()
    {
        $year = DB::select('SELECT DISTINCT YEAR(ex.trans_dt) AS YEAR  FROM transactions ex');

        if ($year == true) {
            return json_encode($year);
        } else {
            return Response::json(['k' => 'k'], 204);
        }


    }


    public function expenditureReportsView()
    {

        $year = DB::select('SELECT DISTINCT YEAR(ex.trans_dt) AS year  FROM transactions ex');

        return view('default.accounts.expenditure-reports', compact('year'));
    }






    public function getAllExpenditureSubHead()
    {
        $check = DB::select('SELECT sh.acc_sub_head,sh.id FROM acc_sub_head AS sh JOIN acc_head AS h ON sh.head_id=h.id
        WHERE h.in_ex_status=1');
        return json_encode($check);
    }


    public function getAllExpenditures($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;
        $check = DB::select('SELECT v.id AS voucher_id,ex.trans_dt, ex.id AS ex_id,ex.sub_head_id,v.vouchar_date,ex.debit,v.vouchar_no, sh.acc_sub_head ,ex.entry_dt 
FROM transactions AS ex 
JOIN vouchers  AS v ON ex.voucher_id=v.id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
WHERE v.vouchar_no=? AND v.in_ex_status="1"', [$finalVoucherNo]);
        return json_encode($check);
    }

    public function saveExpenditure(Request $r)
    {
        $user_id = Auth::user()->id;
        $port_id = Session::get('PORT_ID');
        $voucher = DB::table('vouchers')
            ->where('vouchar_no', $r->vouchar_no)
            ->where('in_ex_status', 1)
            ->get();
        $new_voucher_id = 0;
        if ($voucher == '[]') {
            $new_voucher_id = DB::table('vouchers')->insertGetId([
                    'vouchar_no' => $r->vouchar_no,
                    'vouchar_date' => $r->vouchar_date,
                    'in_ex_status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $user_id
                ]);
        }
        $checkSubheadExist = DB::select('SELECT ex.id FROM transactions AS ex 
JOIN vouchers AS v ON  ex.voucher_id=v.id 
WHERE v.id=? AND ex.sub_head_id=?', [$new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id, $r->sub_head_id]);

        if ($checkSubheadExist == []) {
            DB::table('transactions')
                ->insert([
                    'voucher_id' => $new_voucher_id == 0 ? $voucher[0]->id : $new_voucher_id,
                    'sub_head_id' => $r->sub_head_id,
                    'debit' => $r->amount,
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


    public function updateExpenditureData(Request $r, $id)
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
                'debit' => $r->amount,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id
            ]);

        if ($expenditure == 0 || $expenditure == 1) {
            return Response::json(['updated' => 'updated'], 200);
        } else {
            return Response::json(['wrong' => 'wrong'], 202);
        }


    }


    public function deleteExpenditureData($id)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::table('transactions')
            ->where('id',$id)
            ->get()->first();
        $checkExpenditureTableBlankOrNot = DB::select('SELECT * FROM transactions WHERE transactions.voucher_id=?', [$data->voucher_id]);

       if(count($checkExpenditureTableBlankOrNot) > 1) {
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


    public function voucherReport($v_no, $year)
    {
        $finalVoucherNo = (string)$v_no . "/" . (string)$year;

        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select('SELECT users.username,ex.*, v.vouchar_date,(SELECT SUM(exx.debit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total, v.vouchar_no,sh.acc_sub_head 
FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
        JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
        JOIN users ON users.id = ex.userid
        WHERE v.vouchar_no=? AND v.in_ex_status="1" AND ex.port_id=?', [$finalVoucherNo,$port_id]);

        $pdf = PDF::loadView('default.accounts.reports.voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('VoucherReportPDF.pdf');

    }


    public function todaysVoucherReport()
    {
        $dates = date('Y-m-d');
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select('SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head,(SELECT SUM(exx.debit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total
                        FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
                        JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                         JOIN users ON users.id = ex.userid
                        WHERE DATE(ex.trans_dt)=? AND v.in_ex_status="1" AND ex.port_id=? ORDER BY v.id DESC', [$dates,$port_id]);
        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $total_expenditure_amount = DB::select('SELECT SUM(debit) AS total_amount_vou 
FROM vouchers AS vou
JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
WHERE  DATE(ex_p.trans_dt) =? AND vou.in_ex_status="1" AND ex_p.port_id=?', [$dates,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;




        $pdf = PDF::loadView('default.accounts.reports.todays-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount


        ])->setPaper([0, 0, 800.661, 800.63], 'a4');


        return $pdf->stream('TodaysVoucherPDFReportPDF.pdf');

    }

    public function dateWiseVoucherReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head,(SELECT SUM(exx.debit) FROM transactions AS exx WHERE exx.voucher_id=v.id ) AS total
                                  FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
                                  JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                                   JOIN users ON users.id = ex.userid
                                  WHERE ex.trans_dt=? AND v.in_ex_status='1' AND ex.port_id=? ORDER BY v.id DESC", [$r->from_date,$port_id]);

        $total_expenditure_amount = DB::select("SELECT SUM(debit) AS total_amount_vou FROM vouchers AS vou
                                                                                    JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
                                                             WHERE  trans_dt =? AND vou.in_ex_status='1' AND ex_p.port_id=?", [$r->from_date,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.date-wise-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'dateWise' => $r->from_date


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('DateWiseVoucherPDFReportPDF.pdf');

    }


    public function sourceWiseVoucherReport(Request $r)
    {
        $todayWithTime = date('Y-m-d h:i:s a');
        $voucher_id = (int) filter_var($r->voucher_report, FILTER_SANITIZE_NUMBER_INT);
        $port_id = Session::get('PORT_ID');
        $expenditure = DB::select("SELECT users.username,ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head, acc_h.*
                                    FROM vouchers AS v 
                                    JOIN transactions AS ex ON v.id=ex.voucher_id
                                    JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                                    JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
                                     JOIN users ON users.id = ex.userid
                                    WHERE acc_h.id =? AND v.in_ex_status='1' AND ex.port_id=? ORDER BY v.id DESC", [$voucher_id,$port_id]);
        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $source_name = $expenditure[0]->acc_head;

        $total_expenditure_amount = DB::select("SELECT SUM(debit) AS total_amount_vou
                                    FROM vouchers AS v 
                                    JOIN transactions AS ex ON v.id=ex.voucher_id
                                    JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
                                    JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
                                    WHERE acc_h.id = ? AND v.in_ex_status='1' AND ex.port_id=? ORDER BY v.id DESC", [$voucher_id,$port_id]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;



        $pdf = PDF::loadView('default.accounts.reports.source-wise-voucher', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'source_name' => $source_name


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('sourceWiseVoucherPDF.pdf');

    }


    public function monthWiseVoucherReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT users.username, ex.*,v.vouchar_date,  v.vouchar_no,sh.acc_sub_head
FROM vouchers AS v JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
   JOIN users ON users.id = ex.userid
WHERE v.in_ex_status='1' AND ex.port_id=? AND ex.trans_dt BETWEEN ? AND ? ORDER BY v.id DESC", [$port_id,$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select("   SELECT SUM(debit) AS total_amount_vou  FROM vouchers AS vou
JOIN transactions AS ex_p ON vou.id = ex_p.voucher_id
WHERE vou.in_ex_status='1' AND port_id=? AND trans_dt BETWEEN ? AND ?", [$port_id,$r->from_date_v, $r->to_date_v]);


        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $amount = $total_expenditure_amount[0]->total_amount_vou;
        $pdf = PDF::loadView('default.accounts.reports.month-wise-voucher-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('monthWiseVoucherReportPDF.pdf');

    }


    public function getSourceWiseReportData()
    {
        $getValues = DB::select("SELECT * FROM acc_head WHERE in_ex_status ='1'");
        return json_encode($getValues);
    }


    public function subHeadWiseReportData()
    {
        $getValues = DB::select("SELECT s_h.id AS sh_id, s_h.acc_sub_head FROM acc_sub_head AS s_h 
JOIN acc_head AS ac_h ON s_h.head_id = ac_h.id
WHERE ac_h.in_ex_status = 1");
        return json_encode($getValues);
    }


    public function monthlySubHeadWiseReportData()
    {
        $getValues = DB::select("SELECT s_h.id AS sh_id, s_h.acc_sub_head FROM acc_sub_head AS s_h 
JOIN acc_head AS ac_h ON s_h.head_id = ac_h.id
WHERE ac_h.in_ex_status = 1");
        return json_encode($getValues);
    }


    public function onlyMonthlySubHeadWiseReportData()
    {
        $getValues = DB::select("SELECT s_h.id AS sh_id, s_h.acc_sub_head FROM acc_sub_head AS s_h 
JOIN acc_head AS ac_h ON s_h.head_id = ac_h.id
WHERE ac_h.in_ex_status = 1");
        return json_encode($getValues);
    }


    public function yearlyFixedMaintenanceExpenditureReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $fiscal_year = $r->year.'-'.($r->year+1);

        $expenditure = DB::select('SELECT r.acc_sub_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,t.trans_dt,

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=?),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id,trans_dt FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
  INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=24 AND port_id=?)  t
   GROUP BY t.sub_head_id) r 

UNION ALL 

SELECT "Total",SUM(m.Budget),SUM(m.July),SUM(m.August),SUM(m.September),SUM(m.October),
SUM(m.November),SUM(m.December),SUM(m.January),SUM(m.February),
SUM(m.March),SUM(m.April),SUM(m.May),SUM(m.June),SUM(m.Total) FROM 
(SELECT r.acc_sub_head,r.Budget,r.January,r.February,r.March,r.April,r.May,r.June,r.July,r.August,r.September,r.October,r.November,r.December,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=?),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
 INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=24 AND port_id=?) t
   GROUP BY t.sub_head_id) r) m', [$fiscal_year,$firstDate,$lastDate,$port_id,$fiscal_year,$firstDate,$lastDate,$port_id]);
        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $pdf = PDF::loadView('default.accounts.reports.yearly-fixed-maintenance-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year
        ])->setPaper([0, 0, 820,920]);

        return $pdf->stream('YearlyFixedMaintenanceReport.pdf');
    }


    public function yearlyExpenditureFuelEnergyReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $fiscal_year=$r->year.'-'.($r->year+1);
        $expenditure = DB::select('SELECT r.acc_sub_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,t.trans_dt,

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id,trans_dt FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
  INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=26 AND port_id=?)  t
   GROUP BY t.sub_head_id) r 

UNION ALL 

SELECT "Total",SUM(m.Budget),SUM(m.July),SUM(m.August),SUM(m.September),SUM(m.October),
SUM(m.November),SUM(m.December),SUM(m.January),SUM(m.February),
SUM(m.March),SUM(m.April),SUM(m.May),SUM(m.June),SUM(m.Total) FROM 
(SELECT r.acc_sub_head,r.Budget,r.January,r.February,r.March,r.April,r.May,r.June,r.July,r.August,r.September,r.October,r.November,r.December,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
 INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=26 AND port_id=?) t
   GROUP BY t.sub_head_id) r) m', [$fiscal_year,$firstDate, $lastDate,$port_id,$fiscal_year, $firstDate, $lastDate,$port_id]);

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.yearly-expenditure-fuel-energy-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 820,920]);


        return $pdf->stream('FuelEnergyPDFReport.pdf');

    }

    public function yearlyHeadWiseExpenditureReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $fiscal_year=$r->year.'-'.($r->year+1);

        $expenditure = DB::select('SELECT r.acc_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(

SELECT  acc_head , h_id, trans_dt, 

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_head.id = t.h_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=7),0) AS July,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=8),0) AS August,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=9),0) AS September,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=10),0) AS October,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=11),0) AS November,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=12),0) AS December,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=1),0) AS January,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=2),0) AS February,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=3),0) AS March,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=4),0) AS April,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=5),0) AS May,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=6),0) AS June
FROM  
  (
SELECT head.id AS h_id, acc_head, shead.id AS shead_id, e.id AS e_id, trans_dt FROM transactions e
INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
INNER JOIN acc_head head ON shead.head_id=head.id
WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.in_ex_status=1 AND port_id=?
 
 ) t
   GROUP BY t.h_id 
   
   ) r 
   
   

UNION ALL    
   
   SELECT "Total",SUM(m.Budget),SUM(m.July),SUM(m.August),SUM(m.September),SUM(m.October),
SUM(m.November),SUM(m.December),SUM(m.January),SUM(m.February),
SUM(m.March),SUM(m.April),SUM(m.May),SUM(m.June),SUM(m.Total) FROM 
(
SELECT r.acc_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(

SELECT  acc_head , h_id, trans_dt, 

IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_head.id = t.h_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=7),0) AS July,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=8),0) AS August,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=9),0) AS September,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=10),0) AS October,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=11),0) AS November,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=12),0) AS December,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=1),0) AS January,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=2),0) AS February,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=3),0) AS March,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=4),0) AS April,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=5),0) AS May,

IFNULL((SELECT SUM(debit) AS amount FROM transactions 
INNER JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
INNER JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.id = t.h_id  AND MONTH(trans_dt)=6),0) AS June
FROM  
  (
SELECT head.id AS h_id, acc_head, shead.id AS shead_id, e.id AS e_id, trans_dt FROM transactions e
INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
INNER JOIN acc_head head ON shead.head_id=head.id
WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.in_ex_status=1 AND port_id=?
 
 ) t
   GROUP BY t.h_id 
   
   ) r 
   
   ) m', [$fiscal_year,$firstDate, $lastDate,$port_id,$fiscal_year, $firstDate, $lastDate,$port_id]);

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.yearly-expenditure-head-wise-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 800, 970], 'landscape');


        return $pdf->stream('YearlyExpenditureHeadWisePDFReport.pdf');

    }




    public function repairMaintenanceSectorReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $fiscal_year=$r->year.'-'.($r->year+1);
        $expenditure = DB::select('SELECT r.acc_sub_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,t.trans_dt,
IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id,trans_dt FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
  INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=28 AND port_id=?)  t
   GROUP BY t.sub_head_id) r 

UNION ALL 

SELECT "Total",SUM(m.Budget),SUM(m.July),SUM(m.August),SUM(m.September),SUM(m.October),
SUM(m.November),SUM(m.December),SUM(m.January),SUM(m.February),
SUM(m.March),SUM(m.April),SUM(m.May),SUM(m.June),SUM(m.Total) FROM 
(SELECT r.acc_sub_head,r.Budget,r.January,r.February,r.March,r.April,r.May,r.June,r.July,r.August,r.September,r.October,r.November,r.December,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,
IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
 INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=28 AND port_id=?) t
   GROUP BY t.sub_head_id) r) m', [$fiscal_year,$firstDate, $lastDate,$port_id,$fiscal_year,$firstDate, $lastDate,$port_id]);

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.yearly-repair-maintenance-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 950,1000]);


        return $pdf->stream('RepairMaintenancePDFReport.pdf');

    }

    public function othersVariableExpenseReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year+1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $fiscal_year=$r->year.'-'.($r->year+1);
        $expenditure = DB::select('SELECT r.acc_sub_head,r.Budget,r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,t.trans_dt,
IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id,trans_dt FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
  INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=30 AND port_id=?)  t
   GROUP BY t.sub_head_id) r 

UNION ALL 

SELECT "Total",SUM(m.Budget),SUM(m.July),SUM(m.August),SUM(m.September),SUM(m.October),
SUM(m.November),SUM(m.December),SUM(m.January),SUM(m.February),
SUM(m.March),SUM(m.April),SUM(m.May),SUM(m.June),SUM(m.Total) FROM 
(SELECT r.acc_sub_head,r.Budget,r.January,r.February,r.March,r.April,r.May,r.June,r.July,r.August,r.September,r.October,r.November,r.December,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total FROM(
SELECT acc_sub_head,t.sub_head_id,
IFNULL((SELECT SUM(amount) AS Budget FROM budget_in_ex AS ie 
LEFT JOIN acc_sub_head ON acc_sub_head.id = ie.subhead_id
LEFT JOIN acc_head ON acc_head.id = acc_sub_head.head_id  
WHERE acc_sub_head.id = t.sub_head_id AND ie.fiscal_year=? AND ie.monthly_yearly_flag=1),0 ) AS Budget,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
  (SELECT
    shead.acc_sub_head,sub_head_id FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
 INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE DATE(trans_dt) BETWEEN ? AND ? AND head.id=30 AND port_id=?) t
   GROUP BY t.sub_head_id) r) m', [$fiscal_year,$firstDate, $lastDate,$port_id,$fiscal_year,$firstDate, $lastDate,$port_id]);

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $pdf = PDF::loadView('default.accounts.reports.yearly-others-variable-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 920.661, 900.63], 'landscape');


        return $pdf->stream('OthersVariablePDFReport.pdf');

    }



    public function subHeadWiseYearlyReport(Request $r)
    {
        $emp_id = intval(preg_replace('/[^0-9]+/', '', $r->sub_h_id), 10);
        $todayWithTime = date('Y-m-d h:i:s a');
        $nextYear = $r->year+1;
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $expenditure = DB::select("SELECT r.acc_sub_head,acc_head,in_ex_status, r.July,r.August,r.September,r.October,r.November,r.December,r.January,r.February,r.March,r.April,r.May,r.June,
(r.January+r.February+r.March+r.April+r.May+r.June+r.July+r.August+r.September+r.October+r.November+r.December) AS Total
FROM(
SELECT acc_sub_head,t.sub_head_id,acc_head,in_ex_status,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=7),0) AS July,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=8),0) AS August,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=9),0) AS September,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=10),0) AS October,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=11),0) AS November,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=12),0) AS December,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=1),0) AS January,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=2),0) AS February,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=3),0) AS March,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=4),0) AS April,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=5),0) AS May,
IFNULL((SELECT SUM(debit) AS amount FROM transactions WHERE sub_head_id=t.sub_head_id AND MONTH(trans_dt)=6),0) AS June
FROM  
(
  SELECT
    shead.acc_sub_head,sub_head_id,head.acc_head,head.in_ex_status FROM transactions e
 INNER JOIN acc_sub_head shead ON e.sub_head_id=shead.id
 INNER JOIN acc_head head ON shead.head_id=head.id
 WHERE shead.id=? AND head.in_ex_status='1' AND DATE(trans_dt) BETWEEN ? AND ?
 )  t
   GROUP BY t.sub_head_id) r", [$emp_id, $firstDate, $lastDate]);


        if ($expenditure == []) {
            return view('default.accounts.error');
        }
        $acc_sub_head = $expenditure[0]->acc_sub_head;

        $pdf = PDF::loadView('default.accounts.reports.sub-head-wise-yearly-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'acc_sub_head' => $acc_sub_head,
            'year' => $r->year

        ])->setPaper([0, 0, 920.661, 1000.63], 'landscape');


        return $pdf->stream('subHeadWiseYearlyReportPDF.pdf');

    }


    public function dateRangeWiseSubHeadExpenditureReport(Request $r)
    {   $port_id = Session::get('PORT_ID');
        $sub_head_id = intval(preg_replace('/[^0-9]+/', '', $r->sub_h_id_monthly), 10);
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT ex.id AS ex_id,ex.voucher_id, ex.sub_head_id,ex.debit,ex.trans_dt,sh.id AS sh_id, sh.head_id, sh.acc_sub_head, acc_h.id AS a_h_id, acc_h.acc_head,acc_h.in_ex_status,
v.id AS v_id, v.vouchar_no, v.vouchar_date
FROM vouchers AS v 
JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE v.in_ex_status='1' AND ex.port_id=? AND sh.id = ?  AND ex.trans_dt BETWEEN ? AND ?
ORDER BY v.id DESC", [$port_id,$sub_head_id, $r->from_date_sub, $r->to_date_sub]);
        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $total_expenditure_amount = DB::select("SELECT ex.id AS ex_id,ex.voucher_id, ex.sub_head_id,ex.debit,ex.trans_dt,sh.id AS sh_id, sh.head_id, sh.acc_sub_head, acc_h.id AS a_h_id, acc_h.acc_head,acc_h.in_ex_status,
v.id AS v_id, v.vouchar_no, v.vouchar_date,SUM(ex.debit) AS total_amount_vou 
FROM vouchers AS v 
JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE v.in_ex_status='1' AND ex.port_id=? AND sh.id = ? AND ex.trans_dt BETWEEN ? AND ?
ORDER BY v.id DESC", [$port_id,$sub_head_id, $r->from_date_sub, $r->to_date_sub]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;

        $acc_sub_head = $expenditure[0]->acc_sub_head;

        $pdf = PDF::loadView('default.accounts.reports.sub-head-wise-monthly-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'acc_sub_head' => $acc_sub_head,
            'from_date_sub' => $r->from_date_sub,
            'to_date_sub' => $r->to_date_sub,
            'amount' => $amount


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('subHeadWiseMonthlyReportPDF.pdf');

    }


    public function subHeadWiseMonthlyReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $sub_head_id = intval(preg_replace('/[^0-9]+/', '', $r->sub_h_id_only_monthly), 10);
        $year = date("Y", strtotime($r->sub_head_only));
        $month = date("m", strtotime($r->sub_head_only));

        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT acc_h.in_ex_status,v.in_ex_status,ex.id AS ex_id,ex.voucher_id, ex.sub_head_id,ex.debit,ex.trans_dt,sh.id AS sh_id, sh.head_id, sh.acc_sub_head, acc_h.id AS a_h_id, acc_h.acc_head,
v.id AS v_id, v.vouchar_no, v.vouchar_date
FROM vouchers AS v 
JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE v.in_ex_status='1' AND ex.port_id=? AND sh.id = ?  AND MONTH(ex.trans_dt) = ? AND YEAR(ex.trans_dt) = ?
ORDER BY v.id DESC", [$port_id,$sub_head_id, $month, $year]);

        if ($expenditure == []) {
            return view('default.accounts.error');
        }

        $total_expenditure_amount = DB::select("SELECT ex.id AS ex_id,ex.voucher_id, ex.sub_head_id,ex.debit,ex.trans_dt,sh.id AS sh_id, sh.head_id, sh.acc_sub_head, acc_h.id AS a_h_id, 
acc_h.acc_head,acc_h.in_ex_status,
v.id AS v_id, v.vouchar_no, v.vouchar_date,SUM(ex.debit) AS total_amount_vou 
FROM vouchers AS v 
JOIN transactions AS ex ON v.id=ex.voucher_id
JOIN acc_sub_head AS sh ON ex.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE v.in_ex_status='1' AND ex.port_id=? AND sh.id = ? AND  MONTH(ex.trans_dt) = ? AND YEAR(ex.trans_dt) = ?
ORDER BY v.id DESC", [$port_id, $sub_head_id, $month, $year]);
        $amount = $total_expenditure_amount[0]->total_amount_vou;




        $acc_sub_head = $expenditure[0]->acc_sub_head;

        $pdf = PDF::loadView('default.accounts.reports.only-month-wise-sub-head', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'acc_sub_head' => $acc_sub_head,
            'sub_head_only' => $r->sub_head_only,
            'amount' => $amount
        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('subHeadWiseCompleteMonthReportPDF.pdf');

    }





}
