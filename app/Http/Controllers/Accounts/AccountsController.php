<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use App\User;
class AccountsController extends Controller
{
    public function welcome() {
        $currentDate=date('Y-m-d');
        /*$totalAccountUser= DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.id', Auth::User()->role_id)
            ->count();*/
//dd(date('m')-1);
        $totalIncomeOFTheDay=DB::select('SELECT SUM(credit) AS todays_income FROM transactions WHERE DATE(trans_dt)=?',[$currentDate]);
        $totalExpenseOFTheDay=DB::select('SELECT SUM(debit) AS todays_expense FROM transactions WHERE DATE(trans_dt)=?',[$currentDate]);
        $totalSalaryPaidOFTheMonth=DB::select('SELECT SUM(total_payable) total_pay FROM salarys WHERE MONTH(payable_month_year)=?',[date('m')-1]);
        $totalFdrBalance=DB::select('SELECT SUM(total_balance) total_fdr_balance FROM fdr_actions');

     //  dd($totalSalaryPaidOFTheMonth[0]->total_pay);

        return view('default.accounts.welcome',compact('totalFdrBalance','totalIncomeOFTheDay','totalExpenseOFTheDay','totalSalaryPaidOFTheMonth'));
    }

    public function Invoice() {
    	return view('Accounts.Invoice');
    }
}
