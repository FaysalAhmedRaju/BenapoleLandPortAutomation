<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Session;
use App\Manifest;
class AccountsReportController extends Controller
{
	public function accountsReport() {
		$years = DB::select('SELECT DISTINCT YEAR(transactions.trans_dt) AS year FROM transactions');
		return view('default.accounts.accounts-report-view', compact('years'));
	}


    public function MonthlyRevenueReport(Request $r)
    {
    	$year = date("Y",strtotime($r->month_year));
        $month = date("m",strtotime($r->month_year));
       $MRdata = DB::select('
	select *,
	CEIL(Warehouse_Charge+Handling_Labour+Handling_Equipment+Truck_Entrance_fee+Haltage_Charges+
	Carpenter_Charges+Night_Charge+Holiday_Charge+Weighment_Charge+Removal_Charge+
	Document_Charges+Mobile_Crane+Fork_Kitt+TarPaulin+Miscellaneous_Charges) as total,
	CEIL((Warehouse_Charge+Handling_Labour+Handling_Equipment+Truck_Entrance_fee+Haltage_Charges+
	Carpenter_Charges+Night_Charge+Holiday_Charge+Weighment_Charge+Removal_Charge+
	Document_Charges+Mobile_Crane+Fork_Kitt+TarPaulin+Miscellaneous_Charges)*15/100) as total_Vat
	
	from 
	(
	select  DATE_FORMAT(create_dt,"%d/%m/%Y") AS create_dt,
	MONTHNAME(create_dt) as monthName, YEAR(create_dt) as yearName,
	Warehouse_Charge,(Offloading_Labour+Loading_Labour) as Handling_Labour,
	(Offloading_Equipment+Loading_Equipment) as Handling_Equipment,
	(Truck_Entrance_Foreign_fee+Truck_Entrance_Local_fee) as Truck_Entrance_fee,
	(Local_Truck_Haltage_Charges+Foreign_Truck_Haltage_Charges) as Haltage_Charges,
	(Carpenter_Opening_Closing+Carpenter_Repair) as Carpenter_Charges,
	(Local_Truck_Night_Charge+Foreign_Truck_Night_Charge) as Night_Charge,
	(Local_Truck_Holiday_Charge+Foreign_Truck_Holiday_Charge) as Holiday_Charge,
	(Local_Truck_Weighment+Foreign_Truck_Weighment) as Weighment_Charge,
	(Removal_Labour+Removal_Equipment) as Removal_Charge,Document_Charges,
	 Mobile_Crane,Fork_Kitt,TarPaulin,Miscellaneous_Charges,passenger_terminal_entry_fee
	from
	(
	 select 
	 
	 SUM(IF(sub_head_id=2,  credit, 0)) AS Warehouse_Charge,
	 SUM(IF(sub_head_id=4,  credit, 0)) AS Offloading_Labour,
	 SUM(IF(sub_head_id=6,  credit, 0)) AS Offloading_Equipment,
	 SUM(IF(sub_head_id=8,  credit, 0)) AS Loading_Labour,
	 SUM(IF(sub_head_id=10, credit, 0)) AS Loading_Equipment,
	 SUM(IF(sub_head_id=12, credit, 0)) AS Restacking_Labour,
	 SUM(IF(sub_head_id=16, credit, 0)) AS Restacking_Equipment,
	 SUM(IF(sub_head_id=18, credit, 0)) AS Removal_Labour,
	 SUM(IF(sub_head_id=20, credit, 0)) AS Removal_Equipment,
	 SUM(IF(sub_head_id=22, credit, 0)) AS Transshipment_Labour,
	 SUM(IF(sub_head_id=24, credit, 0)) AS Transshipment_Equipment,
	 SUM(IF(sub_head_id=26, credit, 0)) AS Truck_Entrance_Foreign_fee,
	 SUM(IF(sub_head_id=28, credit, 0)) AS Truck_Entrance_Local_fee,
	 SUM(IF(sub_head_id=30, credit, 0)) AS Carpenter_Opening_Closing,
	 SUM(IF(sub_head_id=32, credit, 0)) AS Carpenter_Repair,
	 SUM(IF(sub_head_id=36, credit, 0)) AS Local_Truck_Night_Charge,
	 SUM(IF(sub_head_id=38, credit, 0)) AS Foreign_Truck_Night_Charge,
	 SUM(IF(sub_head_id=40, credit, 0)) AS Local_Truck_Holiday_Charge,
	 SUM(IF(sub_head_id=42, credit, 0)) AS Foreign_Truck_Holiday_Charge,
	 SUM(IF(sub_head_id=44, credit, 0)) AS Local_Truck_Haltage_Charges,
	 SUM(IF(sub_head_id=46, credit, 0)) AS Foreign_Truck_Haltage_Charges,
	 SUM(IF(sub_head_id=48, credit, 0)) AS Local_Truck_Weighment,
	 SUM(IF(sub_head_id=50, credit, 0)) AS Foreign_Truck_Weighment,
	 SUM(IF(sub_head_id=52, credit, 0)) AS Document_Charges,
	 SUM(IF(sub_head_id=54, credit, 0)) AS Mobile_Crane,
	 SUM(IF(sub_head_id=56, credit, 0)) AS Fork_Kitt,
	 SUM(IF(sub_head_id=58, credit, 0)) AS TarPaulin,
	 SUM(IF(sub_head_id=60, credit, 0)) AS Miscellaneous_Charges,
	 SUM(IF(sub_head_id=220, credit, 0)) AS passenger_terminal_entry_fee,
	 date(transactions.trans_dt) as create_dt
	 from transactions
	 WHERE MONTH(transactions.trans_dt) = ? AND YEAR(transactions.trans_dt) = ?
	 group by date(transactions.trans_dt) WITH ROLLUP
	 )t)tt 
		 
	',[$month, $year]);

     $pdf=PDF::loadView('default.accounts.reports.monthly-revenue-report',['MRdata'=>$MRdata])
     ->setPaper([0, 0, 790.661, 1180.63], 'landscape');
    return $pdf->stream('customer.pdf');
    }
	
	public function dateWiseRevenue() {
        return view('default.accounts.date-wise-revenue');
    }
	 public function dateWiseRevenueReport(Request $r) {
        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $DWRDate = DB::select("select *,
	CEIL(Warehouse_Charge+Handling_Labour+Handling_Equipment+Truck_Entrance_fee+Haltage_Charges+
	Carpenter_Charges+Night_Charge+Holiday_Charge+Weighment_Charge+Removal_Charge+
	Document_Charges+Mobile_Crane+Fork_Kitt+TarPaulin+Miscellaneous_Charges) as total,
	CEIL((Warehouse_Charge+Handling_Labour+Handling_Equipment+Truck_Entrance_fee+Haltage_Charges+
	Carpenter_Charges+Night_Charge+Holiday_Charge+Weighment_Charge+Removal_Charge+
	Document_Charges+Mobile_Crane+Fork_Kitt+TarPaulin+Miscellaneous_Charges)*15/100) as total_Vat
	
	from 
	(
	select  
	(select manifests.manifest from manifests where manifests.id=manif_id) as manifest,
	create_dt,
	MONTHNAME(create_dt) as monthName, YEAR(create_dt) as yearName,
	Warehouse_Charge,(Offloading_Labour+Loading_Labour) as Handling_Labour,
	(Offloading_Equipment+Loading_Equipment) as Handling_Equipment,
	(Truck_Entrance_Foreign_fee+Truck_Entrance_Local_fee) as Truck_Entrance_fee,
	(Local_Truck_Haltage_Charges+Foreign_Truck_Haltage_Charges) as Haltage_Charges,
	(Carpenter_Opening_Closing+Carpenter_Repair) as Carpenter_Charges,
	(Local_Truck_Night_Charge+Foreign_Truck_Night_Charge) as Night_Charge,
	(Local_Truck_Holiday_Charge+Foreign_Truck_Holiday_Charge) as Holiday_Charge,
	(Local_Truck_Weighment+Foreign_Truck_Weighment) as Weighment_Charge,
	(Removal_Labour+Removal_Equipment) as Removal_Charge,Document_Charges,
	 Mobile_Crane,Fork_Kitt,TarPaulin,Miscellaneous_Charges,passenger_terminal_entry_fee
	from
	(
	 select 
	 manif_id,
	 SUM(IF(sub_head_id=2,  credit, 0)) AS Warehouse_Charge,
	 SUM(IF(sub_head_id=4,  credit, 0)) AS Offloading_Labour,
	 SUM(IF(sub_head_id=6,  credit, 0)) AS Offloading_Equipment,
	 SUM(IF(sub_head_id=8,  credit, 0)) AS Loading_Labour,
	 SUM(IF(sub_head_id=10, credit, 0)) AS Loading_Equipment,
	 SUM(IF(sub_head_id=12, credit, 0)) AS Restacking_Labour,
	 SUM(IF(sub_head_id=16, credit, 0)) AS Restacking_Equipment,
	 SUM(IF(sub_head_id=18, credit, 0)) AS Removal_Labour,
	 SUM(IF(sub_head_id=20, credit, 0)) AS Removal_Equipment,
	 SUM(IF(sub_head_id=22, credit, 0)) AS Transshipment_Labour,
	 SUM(IF(sub_head_id=24, credit, 0)) AS Transshipment_Equipment,
	 SUM(IF(sub_head_id=26, credit, 0)) AS Truck_Entrance_Foreign_fee,
	 SUM(IF(sub_head_id=28, credit, 0)) AS Truck_Entrance_Local_fee,
	 SUM(IF(sub_head_id=30, credit, 0)) AS Carpenter_Opening_Closing,
	 SUM(IF(sub_head_id=32, credit, 0)) AS Carpenter_Repair,
	 SUM(IF(sub_head_id=36, credit, 0)) AS Local_Truck_Night_Charge,
	 SUM(IF(sub_head_id=38, credit, 0)) AS Foreign_Truck_Night_Charge,
	 SUM(IF(sub_head_id=40, credit, 0)) AS Local_Truck_Holiday_Charge,
	 SUM(IF(sub_head_id=42, credit, 0)) AS Foreign_Truck_Holiday_Charge,
	 SUM(IF(sub_head_id=44, credit, 0)) AS Local_Truck_Haltage_Charges,
	 SUM(IF(sub_head_id=46, credit, 0)) AS Foreign_Truck_Haltage_Charges,
	 SUM(IF(sub_head_id=48, credit, 0)) AS Local_Truck_Weighment,
	 SUM(IF(sub_head_id=50, credit, 0)) AS Foreign_Truck_Weighment,
	 SUM(IF(sub_head_id=52, credit, 0)) AS Document_Charges,
	 SUM(IF(sub_head_id=54, credit, 0)) AS Mobile_Crane,
	 SUM(IF(sub_head_id=56, credit, 0)) AS Fork_Kitt,
	 SUM(IF(sub_head_id=58, credit, 0)) AS TarPaulin,
	 SUM(IF(sub_head_id=60, credit, 0)) AS Miscellaneous_Charges,
	   SUM(IF(sub_head_id=220, credit, 0)) AS passenger_terminal_entry_fee,
	 date(transactions.trans_dt) as create_dt
	 from transactions
	 where date(transactions.trans_dt) = ?
	 group by manif_id with rollup
	 )t)tt; 
		 
	",[$r->from_date,]);
        
        $pdf = PDF::loadView('default.accounts.reports.date-wise-revenue-report',[
			'DWRDate'=>$DWRDate,
            'from_date' => $r->from_date
          
        ])
            ->setPaper([0, 0, 790.661, 1080.63], 'landscape');
        
        return $pdf->stream('DateWisePDFReport.pdf');
    }

    public function subHeadWiseMonthlyIncomeReport(Request $r) {
    	$todayWithTime = date('Y-m-d h:i:s a');
    	$year = date("Y",strtotime($r->month_year_income));
        $month = date("m",strtotime($r->month_year_income));
        $port_id = Session::get('PORT_ID');

        $data = DB::select('SELECT acc_head.in_ex_status,acc_sub_head.id AS sub_head_id, acc_head.id AS head_id,
acc_head.acc_head, acc_sub_head.acc_sub_head, SUM(transactions.credit) AS total
FROM transactions
JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.in_ex_status !="1" AND transactions.port_id=? AND MONTH(transactions.trans_dt) = ? AND YEAR(transactions.trans_dt) = ?
GROUP BY acc_sub_head.id
ORDER BY acc_head.id',[$port_id,$month,$year]);
        // if($data == []) {
        //     return view('Accounts.error');
        // }
        $pdf = PDF::loadView('default.accounts.reports.sub-head-wise-monthly-income',[
			'data'=>$data,
            'month_year_income' => $r->month_year_income,
            'todayWithTime'=> $todayWithTime
          
        ])->setPaper(/*[0, 0, 790.661, 1080.63], */'A4');
        return $pdf->stream('SubheadWiseMonthlyIncome_'.$r->month_year_income.'_PrintTime_'.time().'_.pdf');
    }

    public function subHeadWiseYearlyIncome(Request $r) {
        $port_id = Session::get('PORT_ID');
    	$todayWithTime = date('Y-m-d h:i:s a');
    	$nextYear = $r->year+1;
        $firstDate = $r->year.'-07-01';
        $lastDate = $nextYear.'-06-30';
        $data = DB::select('SELECT base.head_id, base.sub_head_id, base.acc_head, base.acc_sub_head,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=7),0) AS july,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=8),0) AS august,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=9),0) AS september,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=10),0) AS october,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=11),0) AS november,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=12),0) AS december,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=1),0) AS january,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=2),0) AS february,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=3),0) AS march,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=4),0) AS april,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=5),0) AS may,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=6),0) AS june,
base.total
FROM
(SELECT acc_head.id AS head_id, acc_sub_head.id AS sub_head_id, 
acc_head.acc_head, acc_sub_head.acc_sub_head,SUM(transactions.credit) AS total
FROM transactions
JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.in_ex_status !="1" AND transactions.port_id=? AND DATE(transactions.trans_dt) BETWEEN ? AND ?
GROUP BY acc_sub_head.id
ORDER BY acc_head.id) AS base

UNION ALL

SELECT "","","Total","",SUM(final.july),SUM(final.august),SUM(final.september),SUM(final.october),
SUM(final.november),SUM(final.december),SUM(final.january),SUM(final.february),
SUM(final.march),SUM(final.april),SUM(final.may),SUM(final.june),SUM(final.total)
FROM (
SELECT base.head_id, base.sub_head_id, base.acc_head, base.acc_sub_head,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=7),0) AS july,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=8),0) AS august,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=9),0) AS september,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=10),0) AS october,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=11),0) AS november,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=12),0) AS december,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=1),0) AS january,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=2),0) AS february,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=3),0) AS march,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=4),0) AS april,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=5),0) AS may,
IFNULL((SELECT SUM(transactions.credit) AS amount FROM transactions WHERE transactions.sub_head_id=base.sub_head_id AND MONTH(transactions.trans_dt)=6),0) AS june,
base.total
FROM
(SELECT acc_head.id AS head_id, acc_sub_head.id AS sub_head_id, 
acc_head.acc_head, acc_sub_head.acc_sub_head,SUM(transactions.credit) AS total
FROM transactions
JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.in_ex_status !="1" AND transactions.port_id=? AND  DATE(transactions.trans_dt) BETWEEN ? AND ?
GROUP BY acc_sub_head.id
ORDER BY acc_head.id) AS base ) AS final',[$port_id,$firstDate,$lastDate,$port_id,$firstDate,$lastDate]);

        $pdf = PDF::loadView('default.accounts.reports.sub-head-wise-yearly-income',[
			'data'=>$data,
            'year' => $r->year,
            'todayWithTime'=> $todayWithTime
          
        ])->setPaper([0, 0, 790.661, 1080.63], 'landscape');
        return $pdf->stream('SubheadWiseYearlyIncome_'.$r->month_year_income.'_PrintTime_'.time().'_.pdf');
    }

    public function monthlyIncomeStatementReport(Request $r) {
        $port_id = Session::get('PORT_ID');
    	$todayWithTime = date('Y-m-d h:i:s A');
    	$year = date("Y",strtotime($r->month_year_income_statement));
        $month = date("m",strtotime($r->month_year_income_statement));

        $totalIncome = DB::select('	SELECT IFNULL(SUM(transactions.credit),0) AS total FROM transactions
JOIN acc_sub_head AS sh ON transactions.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE acc_h.in_ex_status="0" AND MONTH(transactions.trans_dt) = ?
AND YEAR(transactions.trans_dt) = ?',[$month, $year]);

        $totalIncomeOthers = DB::select('SELECT IFNULL(SUM(transactions.credit),0) AS total FROM transactions
JOIN acc_sub_head AS sh ON transactions.sub_head_id=sh.id
JOIN acc_head AS acc_h ON sh.head_id = acc_h.id
WHERE acc_h.in_ex_status="2" AND MONTH(transactions.trans_dt) = ?
AND YEAR(transactions.trans_dt) = ?',[$month, $year]);

        $income = DB::select('SELECT acc_sub_head.id AS sub_head_id, acc_sub_head.acc_sub_head,
IFNULL(SUM(transactions.credit),0) AS total
FROM transactions
JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.in_ex_status="0" AND MONTH(transactions.trans_dt) = ? AND YEAR(transactions.trans_dt) = ?
GROUP BY acc_sub_head.id',[$month, $year]);

        $incomeOthers = DB::select('SELECT acc_sub_head.id AS sub_head_id, acc_sub_head.acc_sub_head,
IFNULL(SUM(transactions.credit),0) AS total
FROM transactions
JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
JOIN acc_head ON acc_head.id = acc_sub_head.head_id
WHERE acc_head.in_ex_status="2" AND MONTH(transactions.trans_dt) = ? AND YEAR(transactions.trans_dt) = ?
GROUP BY acc_sub_head.id',[$month, $year]);

        $totalExpense = DB::select('SELECT IFNULL(SUM(transactions.debit),0) AS total
						FROM transactions
	JOIN acc_sub_head  ON acc_sub_head.id = transactions.sub_head_id
	JOIN acc_head ON acc_head.id = acc_sub_head.head_id
	WHERE acc_head.in_ex_status="1" AND transactions.port_id=? AND MONTH(transactions.trans_dt) = ? 
						AND YEAR(transactions.trans_dt) = ?',[$port_id,$month, $year]);

        $expense = DB::select('	SELECT acc_sub_head.id AS sub_head_id,
        acc_sub_head.acc_sub_head, IFNULL(SUM(transactions.debit),0) AS total
								FROM transactions
								JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
								JOIN acc_head ON acc_head.id = acc_sub_head.head_id
								WHERE acc_head.in_ex_status="1" AND transactions.port_id=? AND MONTH(transactions.trans_dt) = ?
								AND YEAR(transactions.trans_dt) = ?
								GROUP BY acc_sub_head.id	',[$port_id,$month, $year]);

        $fdrInfo = DB::select('SELECT fdr_accounts.id AS fdr_accout_id, fdr_accounts.fdr_no,
        					fdr_closings.create_date AS fdr_closing_date, fdr_closings.total_closing_ammount,
							(SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id = fdr_accounts.bank_detail_id) AS openning_bank_name_address,
							(SELECT fdr_actions.interest_rate FROM fdr_actions WHERE fdr_actions.fdr_account_id = fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS last_renew_interest_rate
							FROM fdr_closings
							JOIN fdr_accounts ON fdr_accounts.id = fdr_closings.fdr_account_id
							WHERE MONTH(fdr_closings.create_date) = ?
							AND YEAR(fdr_closings.create_date) = ?',[$month, $year]);

        $totalRevenueOneTwo = $totalIncome[0]->total + $totalIncomeOthers[0]->total;


        $pdf = PDF::loadView('default.accounts.reports.monthly-income-statement', [
        	'month_year_income_statement' => $r->month_year_income_statement,
        	'todayWithTime' => $todayWithTime,
        	'totalIncome' => $totalIncome,
            'totalIncomeOthers' => $totalIncomeOthers,
        	'income' => $income,
            'incomeOthers' => $incomeOthers,
            'totalRevenueOneTwo' => $totalRevenueOneTwo,
        	'totalExpense' => $totalExpense,
        	'expense' => $expense,
        	'fdrInfo' => $fdrInfo
        ])->setPaper([0, 0, 590.661, 780.63],'A4');
        return $pdf->stream('monthlyIncomeStatement_'.$r->month_year_income_statement.'_PrintTime_'.time().'_.pdf');
    }

    public function monthlyReceiptsAndPaymentReport(Request $r) {
        $port_id = Session::get('PORT_ID');
    	$todayWithTime = date('Y-m-d h:i:s A');
    	$year = date("Y",strtotime($r->month_year_receipts_and_payment));
        $month = date("m",strtotime($r->month_year_receipts_and_payment));
        $data = DB::select('SELECT acc_sub_head.id AS sub_head_id, acc_sub_head.acc_sub_head, 
        					IFNULL(SUM(transactions.debit),0) AS total
							FROM transactions
							JOIN acc_sub_head ON acc_sub_head.id = transactions.sub_head_id
							JOIN acc_head ON acc_head.id = acc_sub_head.head_id
							WHERE acc_head.in_ex_status="1" AND transactions.port_id=? AND
							  MONTH(transactions.trans_dt) = ?
							AND YEAR(transactions.trans_dt) = ?
							GROUP BY acc_sub_head.id',[$port_id,$month, $year]);
        $pdf = PDF::loadView('default.accounts.reports.monthly-receipts-and-payment', [
        	'month_year_receipts_and_payment' => $r->month_year_receipts_and_payment,
        	'todayWithTime' => $todayWithTime,
        	'data' => $data,
        ])->setPaper([0, 0, 700.661, 780.63],'A4');
        return $pdf->stream('monthlyReceiptsAndPayment_'.$r->month_year_receipts_and_payment.'_PrintTime_'.time().'_.pdf');
    }
}
