<?php

namespace App\Http\Controllers\ExportAdmin;
use App\Http\Controllers\Controller;
use PDF;
use Session;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use DB;
use Auth;
use Response;
use Exception;

class ExportAdminController extends Controller
{

    public function welcomeExportAdminView()
    {
        $port_id = Session::get('PORT_ID');

        $currentDate = date('Y-m-d');
//      $name = Auth::user()->name;
        $name = "Export Admin";

        $todaysTruckTotal = DB::select('SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE delivery_export.truck_bus_flag=1 AND   delivery_export.entry_datetime=? AND
 delivery_export.port_id=?', [$currentDate,$port_id]);

        $todaysManifestByUser = DB::select(' SELECT COUNT(id) total_Bus_entry_bus  FROM delivery_export WHERE delivery_export.truck_bus_flag=0 AND   delivery_export.entry_datetime=?
 AND delivery_export.port_id=?', [$currentDate,$port_id]);

        $Trucks_of_manifest = DB::select(' SELECT COUNT(id) total_Bus_entry_user  FROM delivery_export WHERE delivery_export.truck_bus_flag=0 AND  entry_by=? AND entry_datetime=? AND delivery_export.port_id=?', [$name, $currentDate,$port_id]);

        $todaysTruckByUser = DB::select(' SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE delivery_export.truck_bus_flag=1 AND  entry_by=? AND entry_datetime=? AND delivery_export.port_id=?', [$name, $currentDate,$port_id]);


        return view('default.export-admin.welcome', compact('todaysTruckTotal', 'todaysManifestByUser', 'Trucks_of_manifest', 'todaysTruckByUser'));
    }


    public function allCompletedChallanExportView() {
        return view('default.export-admin.all-completed-export-challan');
    }

    public function getAllInCompleteChallanList() {
        $port_id = Session::get('PORT_ID');

        $getAllChallanList = DB::select('SELECT ch.id, ch.export_challan_no, ch.delivery_export_id, ch.miscellaneous_charge, ch.miscellaneous_name, 
ch.total_amount,ch.create_by,ch.create_datetime,ch.challan_date,ch.truck_bus_flag FROM delivery_export_challan AS ch WHERE ch.status = 0 AND ch.port_id=?',[$port_id]);

        return json_encode($getAllChallanList);

    }


    public function getExportAdminChallanReport($id_i, $year,$ch_id)
    {
        $port_id = Session::get('PORT_ID');

        $id = $id_i . "/" . $year;

       // dd($id.$ch_id);
        $flage_no = DB::select("SELECT d_c.truck_bus_flag AS flag FROM delivery_export_challan AS d_c WHERE d_c.export_challan_no =? AND d_c.id =? AND d_c.port_id=?",[$id,$ch_id,$port_id]);

        // dd($flage_no[0]->flag);

       // $year = date('Y', strtotime($year));

        // dd($year);
//        $name = Auth::user()->name;
        $user_name = Auth::user()->name;

        //change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018 
 AND handling_and_othercharges.port_id=?",[$year,$year,$year,$port_id]);

        if($flage_no[0]->flag == 1){

            $exportChallanData = DB::select("SELECT dc.id AS challan_no, dc.truck_bus_flag AS flagValue, dc.miscellaneous_name, dc.miscellaneous_charge,dc.export_challan_no,DATE(`create_datetime`) AS create_datetime,
( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 1 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS first_entrance_t_no,  

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 1 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS second_entrance_t_no,

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 1 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS third_entrance_t_no,

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 1 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id )
) AS Total_truck_no, 
       
(
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.truck_bus_type=1
) AS indian_truck,
       
(
SELECT SUM(TIMESTAMPDIFF(DAY, de.entry_datetime, de.exit_datetime)) AS holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.truck_bus_no=0
) AS bd_haltage,

(
SELECT SUM(de.haltage_day) AS total_holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.truck_bus_flag = 1 AND  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id)
) AS total_holtage_day,

       
(
SELECT SUM(TIMESTAMPDIFF(DAY, de.entry_datetime, de.exit_datetime)) AS holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.truck_bus_flag = 1 AND  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id) AND de.truck_bus_type=1 
) AS indian_haltage

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id,$port_id]);
        }else{

            $exportChallanData = DB::select("SELECT dc.id AS challan_no,dc.truck_bus_flag,dc.miscellaneous_name, dc.miscellaneous_charge,dc.export_challan_no,DATE(`create_datetime`) AS create_datetime,
( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 0 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS first_entrance_t_no,  

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 0 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS second_entrance_t_no,

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 0 AND dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.entrance_fee=?
) AS third_entrance_t_no,

( 
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE de.truck_bus_flag = 0 AND  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id )
) AS Total_truck_no, 
(
SELECT COUNT(de.id) FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.truck_bus_type=1
) AS indian_truck,
       
(
SELECT SUM(TIMESTAMPDIFF(DAY, de.entry_datetime, de.exit_datetime)) AS holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id ) AND de.truck_bus_type=0
) AS bd_haltage,
       

(
SELECT SUM(de.haltage_day) AS total_holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.truck_bus_flag = 0 AND  dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id)
) AS total_holtage_day,
(
SELECT SUM(TIMESTAMPDIFF(DAY, de.entry_datetime, de.exit_datetime)) AS holtage_day  FROM delivery_export de,delivery_export_challan AS dech  WHERE dech.export_challan_no=dc.export_challan_no AND FIND_IN_SET(de.id, dech.delivery_export_id) AND de.truck_bus_type=1 
) AS indian_haltage


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id,$port_id]);




        }




        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =? AND h.port_id=?",[$year,$port_id]);

        // dd($holtage_charge);
        $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
        $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
        $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;


        //change Done
        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=? AND hc.port_id=?",[$year,$port_id]);
        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=? AND hc.port_id=?",[$year,$port_id]);
        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=? AND hc.port_id=?",[$year,$port_id]);

//        dd($first_charge);



        $a = $first_charge[0]->first_ent;
        $b = $second_charge[0]->second_ent;
        $c = $third_charge[0]->third_ent;

        $t_first = $first_c_truck_no * $a;
        $t_second = $second_c_truck_no * $b;
        $t_third = $third_c_truck_no * $c;

        $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;   //for 305/2017 entrance free is 317.33


        // $totalTruckNO = $exportChallanData[0]->Total_truck_no;

        $BDentryFee = 10;
        //   $bdtruckno = $exportChallanData[0]->bd_truck;
        //    $totalEntryFeeBD = $BDentryFee * $bdtruckno;

        $BDhaltageCharge = 20;
        // $bdhaltageCharge = $exportChallanData[0]->bd_haltage;
        $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;

        //  $totalHaltageChargeBD =  $BDhaltageCharge * $bdhaltageCharge ;

//        $indianEntryFee = 20;
//        $inTruckNo = $exportChallanData[0]->indian_truck;
//        $totalIndianEntryFee = $indianEntryFee * $inTruckNo;

        $haltage_charge = $holtage_charge[0]->new_haltage_charge;

        $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;

//        $indHaltageCharge = 30;
//        $indHaltageDay = $exportChallanData[0]->indian_haltage;
//        $totalIndianHoltageCharge = $indHaltageCharge * $indHaltageDay;
        $bebed = $exportChallanData[0]->miscellaneous_charge;

        $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;

        $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
        $cellVat = ceil($vat);
        $grandTotal = ceil($cellVat + $totalTakaWithoutVat);

//        dd($grandTotal);

        $pdf = PDF::loadView('default.export-admin.reports.export-admin-challan-report', [
            'challan_no' => $exportChallanData[0]->challan_no,


            'first_charge_4_55' => $first_charge[0]->first_ent,
            'first_c_truck_no' => $first_c_truck_no,
            't_first' => $t_first,

            'second_charge_21_59' => $second_charge[0]->second_ent,
            'second_c_truck_no' => $second_c_truck_no,
            't_second' => $t_second,

            'third_charge_53_92' => $third_charge[0]->third_ent,
            'third_c_truck_no' => $third_c_truck_no,
            't_third' => $t_third,


            'export_challan_no' => $exportChallanData[0]->export_challan_no,
            'create_datetime' => $exportChallanData[0]->create_datetime,
            'miscellaneous_name' => $exportChallanData[0]->miscellaneous_name,
            'miscellaneous_charge' => $exportChallanData[0]->miscellaneous_charge,

            'Total_truck_no' => $exportChallanData[0]->Total_truck_no,
            'indian_truck' => $exportChallanData[0]->indian_truck,
            //'bd_haltage'=>$exportChallanData[0]->bd_haltage,
            'total_holtage_day' => $exportChallanData[0]->total_holtage_day,

            // 'indian_haltage'=>$exportChallanData[0]->indian_haltage,
            'haltage_charge' => $haltage_charge,
            // 'totalHaltageChargeBD'=>$totalHaltageChargeBD,
            //  'totalIndianEntryFee'=>$totalIndianEntryFee,
            // 'totalIndianHoltageCharge'=>$totalIndianHoltageCharge,
            'Total_entrance_fee_all_truck' => $Total_entrance_fee_all_truck,

            'total_haltage_charge_all_truck' => $total_haltage_charge_all_truck,
            'user_name' => $user_name,
            'totalTakaWithoutVat' => $totalTakaWithoutVat,
            'cellVat' => $cellVat,
            'grandTotal' => $grandTotal


        ])->setPaper([0, 0, 651.3, 900]);
        /*->setPaper([0, 0, 750, 800]541.3,468.3, 'landscape');*/

        return $pdf->stream('ExportChallanReport.pdf');


    }


    public function saveExportAdminChallan(Request $r)
    {
        $port_id = Session::get('PORT_ID');


       if ($r->VehicleType_flag == 1){

           $t = DB::table('transactions')
               ->insert([
                   'transactions.export_challan_flag' => 1,   //for Export Truck and Bus export_challan_flag is 1
                   'transactions.challan_details_id' => $r->Challan_id,
                   'transactions.credit' =>$r->Total_Amount,
                   'transactions.sub_head_id' =>226,  //Export Truck
                   'transactions.debit' =>0,
                   'transactions.comments' =>0,
                   'transactions.entry_dt' =>date('Y-m-d H:i:s'),
                   'transactions.trans_dt' =>date('Y-m-d H:i:s'),
                   'transactions.userid' => Auth::user()->id,
                   'transactions.port_id' => $port_id

               ]);


           $p = DB::table('delivery_export_challan')
               ->where('id',$r->Challan_id)
               ->where('port_id',$port_id)
               ->update([
                   'status' => 1   // 1 for challan done
               ]);

       }else{
           $t = DB::table('transactions')
               ->insert([
                   'transactions.export_challan_flag' => 1,   //for Export Truck and Bus export_challan_flag is 1
                   'transactions.challan_details_id' => $r->Challan_id,
                   'transactions.credit' =>$r->Total_Amount,
                   'transactions.sub_head_id' =>227,  //Export bus
                   'transactions.debit' =>0,
                   'transactions.comments' =>0,
                   'transactions.entry_dt' =>date('Y-m-d H:i:s'),
                   'transactions.trans_dt' =>date('Y-m-d H:i:s'),
                   'transactions.userid' => Auth::user()->id,
                   'transactions.port_id' => $port_id
               ]);


           $p = DB::table('delivery_export_challan')
               ->where('id',$r->Challan_id)
               ->where('port_id',$port_id)
               ->update([
                   'status' => 1   // 1 for challan done
               ]);


       }



        if ($p == true) {
            return "Inserted";
        }


    }


    public  function dateWiseTruckEntryReportExportAdminView(){
        return view('default.export-admin.date-wise-truck-entry');
    }



    public  function dateWiseWeighbridgeEntryReportExportAdminView(){
        return view('default.export-admin.date-wise-weighbridge-entry');
    }





    public  function dateWiseBusEntryReportExportAdminView(){
        return view('default.export-admin.date-wise-bus-entry');
    }






}
