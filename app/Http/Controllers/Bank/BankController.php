<?php

namespace App\Http\Controllers\Bank;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function welcome() {


        $currentDate=date('Y-m-d');
//        $name = Auth::user()->name;

        $name = "bank";
        $todaysTruckTotal=DB::select('SELECT COUNT(id) total_Manifest_entry FROM charge_receive_banks WHERE DATE(receive_dt)=?',[$currentDate]);

        $todaysManifestByUser = DB::select('SELECT COUNT(id) total_user FROM charge_receive_banks WHERE recived_by=? AND  DATE(receive_dt)=?',[$name,$currentDate]);



        $Trucks_of_manifest=DB::select('SELECT SUM(T_charge) AS total_amount_bank FROM charge_receive_banks WHERE DATE(receive_dt)=?',[$currentDate]);

        $todaysTruckByUser = DB::select('SELECT SUM(T_charge) AS total_amount_bank FROM charge_receive_banks WHERE recived_by=? AND DATE(receive_dt)=?',[$name,$currentDate]);




        return view('Bank.welcome',compact('todaysTruckTotal','todaysManifestByUser','Trucks_of_manifest','todaysTruckByUser'));

    }

    public function bankPayment() {
        return view('Bank.BankPayment');
    }

//JsonReturn=====================

    public function serachByManifestForBank(Request $r)
    {


        $manifest = DB::select("SELECT DISTINCT assesment_details.manif_id AS M_id, assesment_details.approved,					

	    
	    manifests.exporter_name_addr, manifests.package_no, manifests.manifest AS M_No, 
  manifests.package_no, manifests.package_type,
/*(SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id) AS importerName,*/
(SELECT SUM(assesment_details.tcharge)  FROM assesment_details WHERE assesment_details.manif_id=manifests.id) AS total,
(SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=manifests.goods_id) AS cargoName,
(SELECT MAX( assesment_details.create_dt )FROM assesment_details WHERE assesment_details.manif_id=manifests.id) AS assessment_date 
FROM manifests 
INNER JOIN assesment_details ON assesment_details.manif_id=manifests.id
	WHERE manifests.manifest=?",
            [$r->Mani_No]);

        return json_encode($manifest);

    }


    public function getPaidPaymentDetails($id)
    {
        $paidPayemnt=  DB::table('charge_receive_banks AS bank')
            ->where('bank.manif_id',$id)
            ->select('bank.*')
            ->get();

        return json_encode($paidPayemnt);
    }






        public function saveBankPayment(Request $r)
    {


        DB::table('charge_receive_banks')->insert(
            [
                'manif_id' => $r->manif_id,
                'T_charge' => $r->T_charge,
                'vat' =>(($r->T_charge)*15)/100 ,
                'paymode' =>$r->paymode ,
                 'comment' =>$r->comment,
               'payment_details' =>$r->payment_details,

                'receive_dt' => date('Y-m-d H:i:s'),
                'recived_by' => Auth::user()->username,
                'challan_no'=>$r->challan_no


            ]
        );

        return "saved";


    }




}
