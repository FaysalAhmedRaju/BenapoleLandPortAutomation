<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use PDF;

class FDRController extends Controller
{
    public function FDROpenning()
    {
        return view('default.fdr.fdr-openning');
    }

    public function postFDRDetails(Request $r)
    {
        $createdBy = Auth::user()->name;
        $createdTime = date('Y-m-d H:i:s');
        $postFDRDetails = DB::table('fdr_details')
            ->insert([
                'sl_no' => $r->sl_no,
                'bank_name' => $r->bank_name,
                'fdr_no' => $r->fdr_no,
                'main_amount' => $r->main_amount,
                'opening_dt' => $r->opening_dt,
                'duration' => $r->duration,
                'expire_dt' => $r->expire_dt,
                'interest_rate' => $r->interest_rate,
                'total_interest' => $r->total_interest,
                'income_tax' => $r->income_tax,
                'excavator_tariff' => $r->excavator_tariff,
                'net_interest' => $r->net_interest,
                'total_with_interest' => $r->total_with_interest,
                'comments' => $r->comments,
                'create_dt' => $createdTime,
                'creator' => $createdBy
            ]);
        if ($postFDRDetails == true) {
            return "Success";
        }
    }

    public function getFDRDetails()
    {
        $getFDRDetails = DB::table('fdr_details')
            ->get();
        return json_encode($getFDRDetails);
    }

    public function updateFDRDetails(Request $r)
    {
        $updateFDRDetails = DB::table('fdr_details')
            ->where('id', $r->id)
            ->update([
                'sl_no' => $r->sl_no,
                'bank_name' => $r->bank_name,
                'fdr_no' => $r->fdr_no,
                'main_amount' => $r->main_amount,
                'opening_dt' => $r->opening_dt,
                'duration' => $r->duration,
                'expire_dt' => $r->expire_dt,
                'interest_rate' => $r->interest_rate,
                'total_interest' => $r->total_interest,
                'income_tax' => $r->income_tax,
                'excavator_tariff' => $r->excavator_tariff,
                'net_interest' => $r->net_interest,
                'total_with_interest' => $r->total_with_interest,
                'comments' => $r->comments
            ]);
        if ($updateFDRDetails == true) {
            return "Updated";
        }
    }

    public function deleteFDR($id)
    {
        $deleteFDR = DB::table('fdr_details')
            ->where('id', $id)
            ->delete();
        if ($deleteFDR == true) {
            return "Deleted.";
        }
    }

    //FDR Details
    public function detailsFDRAccountView()
    {
        return view('default.fdr.fdr-details');
    }

    public function getAllBankDetails()
    {
        $allBankDetails = DB::table('bank_details')
            ->select('bank_details.id',
                'bank_details.name_and_address',
                'bank_details.type')
            ->get();
        return json_encode($allBankDetails);
    }

    public function saveFDRAccountData(Request $r)
    {
        $user = Auth::user()->username;
        $today = date('Y-m-d');
        $stringForId = "B-";
        $getlastFdrSlNo = DB::select('SELECT MAX(CAST((SUBSTRING(fdr_accounts.sl_no , 3)) AS UNSIGNED)) 
                                AS last_fdr_sl_no FROM fdr_accounts');
        if (!is_null($getlastFdrSlNo[0]->last_fdr_sl_no)) {
            $numberOfFdrSlNo = $getlastFdrSlNo[0]->last_fdr_sl_no + 1;
        } else {
            $numberOfFdrSlNo = 1;
        }
        $sl_no = $stringForId . $numberOfFdrSlNo;
        $postFDRDetails = DB::table('fdr_accounts')
            ->insert([
                'fdr_accounts.bank_detail_id' => $r->bank_detail_id,
                'fdr_accounts.sl_no' => $sl_no,
                'fdr_accounts.fdr_no' => $r->fdr_no,
                'fdr_accounts.create_by' => $user,
                'fdr_accounts.create_date' => $today
            ]);
        if ($postFDRDetails == true) {
            return Response::json(['FDRDetailSaved' => 'FDR Account successfully saved'], 201);
        }
    }

    public function getAllFDRAccountsData()
    {
        $allFDRaccounts = DB::table('fdr_accounts')
            ->select(
                'fdr_accounts.id',
                'fdr_accounts.bank_detail_id',
                'fdr_accounts.sl_no',
                'fdr_accounts.fdr_no',
                'fdr_accounts.status',
                DB::raw('(SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id=fdr_accounts.bank_detail_id) AS bank_name_and_address'),
                DB::raw('(SELECT COUNT(fdr_actions.id) FROM fdr_actions WHERE fdr_actions.fdr_account_id = fdr_accounts.id) AS fdr_actions_count'),
                DB::raw('(SELECT TIMESTAMPDIFF(DAY, fdr_actions.opening_date, fdr_actions.expire_date) AS total_day_difference
                                        FROM fdr_actions WHERE fdr_actions.fdr_account_id = fdr_accounts.id
                                        ORDER BY fdr_actions.id DESC LIMIT 1) AS total_day_difference'),
                DB::raw('(SELECT TIMESTAMPDIFF(DAY, fdr_actions.opening_date, CURDATE()) AS diff_from_today
                                        FROM fdr_actions WHERE fdr_actions.fdr_account_id = fdr_accounts.id
                                        ORDER BY fdr_actions.id DESC LIMIT 1) AS diff_from_today')
            )
            ->get();
        return json_encode($allFDRaccounts);
    }

    public function updateFDRAccountData(Request $r)
    {
        $updateFDRAccountDetails = DB::table('fdr_accounts')
            ->where('fdr_accounts.id', $r->id)
            ->update([
                'fdr_accounts.bank_detail_id' => $r->bank_detail_id,
                //'fdr_accounts.sl_no' => $r->sl_no,
                'fdr_accounts.fdr_no' => $r->fdr_no,
            ]);
        if ($updateFDRAccountDetails == true) {
            return Response::json(['FDRDetailUpdated' => 'FDR Account successfully updated'], 201);
        }
    }

    public function deleteFDRAccountData($fdr_account_id)
    {
        $chkFDRDetails = DB::table('fdr_actions')
            ->where('fdr_actions.fdr_account_id', $fdr_account_id)
            ->get();
        if (count($chkFDRDetails)) {
            return Response::json(['FDRAccountDeletedWarning' => 'FDR Account have Open/Reopen Details, Delete Them First'], 202);
        }
        $deleteFDR = DB::table('fdr_accounts')
            ->where('id', $fdr_account_id)
            ->delete();
        if ($deleteFDR == true) {
            return Response::json(['FDRAccountDeleted' => 'FDR Account successfully deleted'], 201);
        }
    }

    public function reopenFDRAccount($fdr_account_id)
    {
        $chkFDRCLose = DB::table('fdr_closings')
            ->where('fdr_closings.fdr_account_id', $fdr_account_id)
            ->get();

        if ($chkFDRCLose) {
            $deletedFDRCLose = DB::table('fdr_closings')
                ->where('fdr_closings.fdr_account_id', $fdr_account_id)
                ->delete();
        }

        $ReopenFDRAccount = DB::table('fdr_accounts')
            ->where('fdr_accounts.id', $fdr_account_id)
            ->update([
                'fdr_accounts.status' => 1
            ]);
        if ($deletedFDRCLose == true && $ReopenFDRAccount == true) {
            return Response::json(['FDRReopened' => 'Successfully Re-Open'], 201);
        }
    }

    //Bank Details
    public function saveBankDetails(Request $r)
    {
        $user = Auth::user()->username;
        $today = date('Y-m-d');
        $postBankDetails = DB::table('bank_details')
            ->insert([
                'bank_details.name_and_address' => $r->name_and_address,
                'bank_details.type' => $r->type,
                'bank_details.create_by' => $user,
                'bank_details.create_date' => $today
            ]);
        if ($postBankDetails == true) {
            return Response::json(['BankDetailSaved' => 'Bank successfully added'], 201);
        }
    }

    public function updateBankDetails(Request $r)
    {
        $updateBankDetails = DB::table('bank_details')
            ->where('bank_details.id', $r->id)
            ->update([
                'bank_details.name_and_address' => $r->name_and_address,
                'bank_details.type' => $r->type,
            ]);
        if ($updateBankDetails == true) {
            return Response::json(['BankDetailUpdate' => 'Bank successfully updated'], 201);
        }
    }

    public function deleteBankDetails($bank_id)
    {
        $chkFDRAccounts = DB::table('fdr_accounts')
            ->where('fdr_accounts.bank_detail_id', $bank_id)
            ->get();
        $chkFDRClose = DB::table('fdr_closings')
            ->where('fdr_closings.bank_detail_id', $bank_id)
            ->get();
        if (count($chkFDRAccounts) || count($chkFDRClose)) {
            return Response::json(['BankDeletedWarning' => 'Bank Used in FDR Accounts, Delete Them First'], 202);
        }

        $deleteBankDetail = DB::table('bank_details')
            ->where('bank_details.id', $bank_id)
            ->delete();
        if ($deleteBankDetail == true) {
            return Response::json(['BankDetailDeleted' => 'Bank successfully deleted'], 201);
        }
    }

    //FDR Openning Or Renew
    public function saveFDROpenOrRenewData(Request $r)
    {
        $user = Auth::user()->username;
        $today = date('Y-m-d');
        $getMaxFdrSlNo = DB::select('SELECT 
                                MAX(CAST((SUBSTRING(fdr_actions.sl_no, LENGTH(?)+1, 3)) AS UNSIGNED)) 
                                AS max_fdr_sl_no FROM fdr_actions WHERE fdr_account_id=?',
            [$r->fdr_account_sl_no . '-', $r->fdr_account_id]);
        if (is_null($getMaxFdrSlNo[0]->max_fdr_sl_no)) {
            $status = 0;
            $slNo = $r->fdr_account_sl_no . "-1";
        } else {
            $status = 1;
            $slNo = $r->fdr_account_sl_no . "-" . ($getMaxFdrSlNo[0]->max_fdr_sl_no + 1);
        }
        $postFDRDetailsData = DB::table('fdr_actions')
            ->insert([
                'fdr_actions.fdr_account_id' => $r->fdr_account_id,
                'fdr_actions.sl_no' => $slNo,
                'fdr_actions.main_amount' => $r->main_amount,
                'fdr_actions.opening_date' => $r->opening_date,
                'fdr_actions.duration' => $r->duration,
                'fdr_actions.expire_date' => $r->expire_date,
                'fdr_actions.interest_rate' => $r->interest_rate,
                'fdr_actions.total_interest' => $r->total_interest,
                'fdr_actions.income_tax' => $r->income_tax,
                'fdr_actions.excavator_tariff' => $r->excavator_tariff,
                'fdr_actions.net_interest' => $r->net_interest,
                'fdr_actions.bank_charge' => $r->bank_charge,
                'fdr_actions.vat' => $r->vat,
                'fdr_actions.total_balance' => $r->total_balance,
                'fdr_actions.comments' => $r->comments,
                'fdr_actions.status' => $status,
                'fdr_actions.create_by' => $user,
                'fdr_actions.create_date' => $today
            ]);
        if ($postFDRDetailsData == true) {
            return Response::json(['FDROpenningOrClosingSaved' => 'FDR successfully Opened/Renewed'], 201);
        }
    }

    public function getFDROpenOrRenew($fdr_account_id)
    {
        $getFDROpenOrRenew = DB::table('fdr_actions')
            ->where('fdr_actions.fdr_account_id', $fdr_account_id)
            ->get();
        return json_encode($getFDROpenOrRenew);
    }

    public function updateFDROpenOrRenewData(Request $r)
    {
        $updateFDRDetailsData = DB::table('fdr_actions')
            ->where('fdr_actions.id', $r->id)
            ->update([
                'fdr_actions.main_amount' => $r->main_amount,
                'fdr_actions.opening_date' => $r->opening_date,
                'fdr_actions.duration' => $r->duration,
                'fdr_actions.expire_date' => $r->expire_date,
                'fdr_actions.interest_rate' => $r->interest_rate,
                'fdr_actions.total_interest' => $r->total_interest,
                'fdr_actions.income_tax' => $r->income_tax,
                'fdr_actions.excavator_tariff' => $r->excavator_tariff,
                'fdr_actions.net_interest' => $r->net_interest,
                'fdr_actions.bank_charge' => $r->bank_charge,
                'fdr_actions.vat' => $r->vat,
                'fdr_actions.total_balance' => $r->total_balance,
                'fdr_actions.comments' => $r->comments,
            ]);
        if ($updateFDRDetailsData == true) {
            return Response::json(['FDROpenningOrClosingUpdate' => 'FDR successfully Updated'], 201);
        }
    }

    public function deleteFDROpenningOrRenew($fdr_action_id)
    {
        $deleteFDROpenningOrRenew = DB::table('fdr_actions')
            ->where('fdr_actions.id', $fdr_action_id)
            ->delete();
        if ($deleteFDROpenningOrRenew == true) {
            return Response::json(['FDROpenningOrClosingDeleted' => 'Successfully deleted'], 201);
        }
    }

    //FDR CLOSE

    public function getTotalAmmountForFDRClose($account_id)
    {
        $getFDRClose = DB::select('SELECT fdr_actions.id, fdr_actions.sl_no, 
                                fdr_actions.total_balance, fdr_actions.expire_date
                                FROM fdr_actions
                                WHERE fdr_actions.fdr_account_id = ?
                                ORDER BY fdr_actions.id DESC LIMIT 1', [$account_id]);
        return json_encode($getFDRClose);
    }

    public function saveFdrClose(Request $r)
    {
        $user = Auth::user()->username;
        $today = date('Y-m-d');
        $chkFDRCLose = DB::table('fdr_closings')
            ->where('fdr_closings.fdr_account_id', $r->fdr_account_id)
            ->get();

        if ($chkFDRCLose) {
            DB::table('fdr_closings')->where('fdr_closings.fdr_account_id', $r->fdr_account_id)
                ->delete();
        }
        $saveFdrCLose = DB::table('fdr_closings')
            ->insert([
                'fdr_closings.fdr_account_id' => $r->fdr_account_id,
                'fdr_closings.bank_detail_id' => $r->bank_detail_id,
                'fdr_closings.payorder_cheque_payslip_no' => $r->payorder_cheque_payslip_no,
                'fdr_closings.transaction_acc_no' => $r->transaction_acc_no,
                'fdr_closings.official_order_no' => $r->official_order_no,
                'fdr_closings.bank_charge' => $r->bank_charge,
                'fdr_closings.vat' => $r->vat,
                'fdr_closings.total_closing_ammount' => $r->total_closing_ammount,
                'fdr_closings.create_by' => $user,
                'fdr_closings.create_date' => $today
            ]);
        $updateFDRAccount = DB::table('fdr_accounts')
            ->where('fdr_accounts.id', $r->fdr_account_id)
            ->update([
                'fdr_accounts.status' => 0
            ]);
        if ($saveFdrCLose == true && $updateFDRAccount == true) {
            return Response::json(['FDRCLosed' => 'Successfully closed'], 201);
        }
    }

    public function getFdrClose($fdr_account_id)
    {
        $getFDRClose = DB::table('fdr_closings')
            ->where('fdr_closings.fdr_account_id', $fdr_account_id)
            ->select('fdr_closings.*',
                DB::raw('(SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id=fdr_closings.bank_detail_id) AS bank_name_and_address')
            )
            ->get();
        return json_encode($getFDRClose);
    }

    public function updateFdrClose(Request $r)
    {
        $updateFdrCLose = DB::table('fdr_closings')
            ->where('fdr_closings.id', $r->id)
            ->update([
                'fdr_closings.fdr_account_id' => $r->fdr_account_id,
                'fdr_closings.bank_detail_id' => $r->bank_detail_id,
                'fdr_closings.payorder_cheque_payslip_no' => $r->payorder_cheque_payslip_no,
                'fdr_closings.transaction_acc_no' => $r->transaction_acc_no,
                'fdr_closings.official_order_no' => $r->official_order_no,
                'fdr_closings.bank_charge' => $r->bank_charge,
                'fdr_closings.vat' => $r->vat,
                'fdr_closings.total_closing_ammount' => $r->total_closing_ammount
            ]);
        if ($updateFdrCLose == true) {
            return Response::json(['FDRUpdate' => 'Successfully updated'], 201);
        }
    }

    //Report

    public function getTotalFundPostionReport()
    {
        $today = date('d-m-Y');
        $totalFundPostion = DB::select('SELECT fdr_accounts.id AS fdr_account_id,bank_details.id AS bank_id, bank_details.type AS bank_type, bank_details.name_and_address, fdr_accounts.fdr_no, fdr_accounts.sl_no,
            (SELECT COUNT(fdr_accounts.bank_detail_id) FROM fdr_accounts WHERE fdr_accounts.bank_detail_id=bank_id AND fdr_accounts.status = 1 GROUP BY fdr_accounts.bank_detail_id) AS bank_wise_account_count,
            (SELECT fdr_actions.main_amount FROM fdr_actions 
            WHERE fdr_actions.fdr_account_id =  fdr_accounts.id AND fdr_actions.status=0 ORDER BY fdr_actions.id ASC LIMIT 1) AS openning_amount,
            (SELECT fdr_actions.opening_date FROM fdr_actions 
                WHERE fdr_actions.fdr_account_id =  fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS openning_or_renew_date,
            (SELECT fdr_actions.duration FROM fdr_actions 
                WHERE fdr_actions.fdr_account_id =  fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS term,
            (SELECT fdr_actions.expire_date FROM fdr_actions 
                WHERE fdr_actions.fdr_account_id =  fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS maturity_date,
            (SELECT fdr_actions.interest_rate FROM fdr_actions 
                WHERE fdr_actions.fdr_account_id =  fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS open_or_renew_interest_rate,
            (SELECT fdr_actions.main_amount FROM fdr_actions 
                WHERE fdr_actions.fdr_account_id =  fdr_accounts.id ORDER BY fdr_actions.id DESC LIMIT 1) AS renewed_amount
            FROM fdr_accounts
            INNER JOIN bank_details ON bank_details.id = fdr_accounts.bank_detail_id
            WHERE fdr_accounts.status = 1 ORDER BY bank_type,bank_id ASC');
        // renewed_amount changed to main_amount DESC And bank_wise_total to sum(main_amount)
        $pdf = PDF::loadView('default.fdr.reports.total-fund-position', [
            'today' => $today,
            'totalFundPostion' => $totalFundPostion,
        ])->setPaper([0, 0, 500, 910], 'landscape');
        return $pdf->stream('TotalFundPosition.pdf');
    }

    public function getFDRWiseReport($fdr_account_id)
    {
        $today = date('d-m-y');

        $check_fdr_already_closed = DB::select('SELECT * FROM fdr_closings WHERE fdr_account_id=?', [$fdr_account_id]);
        if (count($check_fdr_already_closed) > 0) {
            $FDRWiseReport = DB::select('SELECT fdr_accounts.fdr_no, fdr_actions.total_interest, fdr_actions.sl_no,fdr_actions.main_amount,fdr_actions.opening_date,fdr_actions.duration, fdr_actions.expire_date,
fdr_actions.interest_rate, fdr_actions.income_tax, fdr_actions.excavator_tariff, 
fdr_actions.net_interest,fdr_actions.bank_charge, fdr_actions.vat, fdr_actions.total_balance, fdr_actions.comments,
 (SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id = fdr_accounts.bank_detail_id) AS bank_name
FROM fdr_actions 
INNER JOIN fdr_accounts ON fdr_actions.fdr_account_id = fdr_accounts.id 
WHERE fdr_actions.fdr_account_id=?
UNION ALL
SELECT 0,0, "Closing",0,0,0,0,0,0,0,0,fdr_closings.bank_charge, fdr_closings.vat, fdr_closings.total_closing_ammount,0,
(SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id = fdr_closings.bank_detail_id) AS bank_name
FROM fdr_closings
INNER JOIN fdr_accounts ON fdr_closings.fdr_account_id = fdr_accounts.id 
WHERE fdr_closings.fdr_account_id=?', [$fdr_account_id, $fdr_account_id]);
             //dd($FDRWiseReport);
        } else {
            $FDRWiseReport = DB::select('SELECT fdr_actions.*, fdr_accounts.fdr_no, 
                                    fdr_accounts.bank_detail_id,
                                    (SELECT bank_details.name_and_address FROM bank_details WHERE bank_details.id = fdr_accounts.bank_detail_id) AS bank_name
                                    FROM fdr_actions 
                                    INNER JOIN fdr_accounts ON fdr_actions.fdr_account_id = fdr_accounts.id 
                                    WHERE fdr_actions.fdr_account_id=?', [$fdr_account_id]);
       }


       

        $pdf = PDF::loadView('default.fdr.reports.fdr-wise-report', [
            'today' => $today,
            'FDRWiseReport' => $FDRWiseReport,
        ])->setPaper([0, 0, 500, 1000], 'landscape');
        return $pdf->stream('FDRWiseReport.pdf');
    }
}
