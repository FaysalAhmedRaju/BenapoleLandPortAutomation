<?php
namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use PDF;
use Response;
use App\Role;
use Session;

class ExportController extends Controller
{
    public function welcome()
    {

        $port_id = Session::get('PORT_ID');
        $currentDate = date('Y-m-d');
//        $name = Auth::user()->name;
        $name = "Export";

        $todaysTruckTotal = DB::select('SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE truck_bus_flag=1 AND entry_datetime=? AND port_id=?', [$currentDate,$port_id]);

        $todaysManifestByUser = DB::select('SELECT COUNT(id) total_Bus_entry FROM delivery_export WHERE truck_bus_flag=0 AND entry_datetime=? AND port_id=?', [$currentDate,$port_id]);

        $Trucks_of_manifest = DB::select('SELECT COUNT(id) total_Bus_entry_user FROM delivery_export WHERE
 delivery_export.truck_bus_flag=0 AND entry_by=? AND entry_datetime=? AND port_id=? ', [$name, $currentDate,$port_id]);

        $todaysTruckByUser = DB::select(' SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE delivery_export.truck_bus_flag=1 AND entry_by=? AND entry_datetime=? AND port_id=?', [$name, $currentDate,$port_id]);


        return view('default.export.welcome', compact('todaysTruckTotal', 'todaysManifestByUser', 'Trucks_of_manifest', 'todaysTruckByUser'));
    }

    public function exportTruckEntryExitView()
    {


        $year = DB::select('SELECT DISTINCT YEAR(ex.entry_datetime) AS year  FROM delivery_export ex');

        return view('default.export.export-truck-entry-exit', compact('year'));


    }

    public function saveExportTruckEntryData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');

        $todayTime = date('Y-m-d');

        $CheckTruck = DB::table('delivery_export')
            ->where('truck_bus_no', $r->truck_no)
            ->where('entry_datetime', $todayTime)
            ->where('truck_bus_flag', 1)
            ->where('port_id', $port_id)
            ->get();

        //$test = '2017-11-30';


        //  $CheckTruck = DB::SELECT("SELECT * FROM delivery_export WHERE delivery_export.entry_datetime =? AND delivery_export.truck_no =?",[$currentTime,$r->truck_no]);
        // $CheckTruck = DB::SELECT('SELECT delivery_export.entry_datetime FROM delivery_export WHERE DATE(delivery_export.entry_datetime) = ? GROUP BY delivery_export.entry_datetime',[$todayTime]);


//        $Checkdate = DB::table('delivery_export')
//            ->where('entry_datetime',$test)
//            ->get();

//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello Bangladesh:".$CheckTruck);
//        fclose($file);
//        return;

        if ($CheckTruck == '[]') {

            $entryUser = Auth::user()->username;
            $postExTruckEntry = DB::table('delivery_export')
                ->insert([
                    'delivery_export.truck_bus_no' => $r->truck_no,
//    								'delivery_export.driver_name' => $r->driver_name,
                    'delivery_export.entry_datetime' => $r->entry_datetime,
                    'delivery_export.entrance_fee' => $r->entrance_fee,
                    'delivery_export.truck_bus_type' => $r->truck_type,
                    'delivery_export.haltage_day' => $r->haltage_day,
                    'delivery_export.entry_by' => $entryUser,
                    'delivery_export.truck_bus_flag' => 1,
                    'delivery_export.port_id' => $port_id
                ]);

            $exportData = DB::select("SELECT delivery_export.id AS truck_id, vehicle_type_bd.type_name,delivery_export.truck_bus_no AS truck_no_de_ex,
delivery_export.haltage_day AS h_day, delivery_export.entrance_fee AS e_fee,
delivery_export.truck_bus_type AS truck_type_de_ex,  delivery_export.driver_name AS driver_name_de_ex,
delivery_export.entry_datetime AS entry_datetime_de_ex, delivery_export.exit_datetime AS exit_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only,
DATE(`exit_datetime`) AS exit_date_only,
TIME(`entry_datetime`) AS entry_time_only,
TIME(`exit_datetime`) AS exit_time_only,
TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS holtage_day  
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 1 AND delivery_export.truck_bus_no=? AND delivery_export.port_id=?", [$r->truck_no,$port_id]);
            $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"Hello ".$newHaltageCharge[0]->new_haltage_charge);
//            fclose($file);
//            return;


            $new_holtageDay = $exportData[0]->h_day;
            $new_holtCharge = $newHaltageCharge[0]->new_haltage_charge;
            $New_holtageTotalcharge = $new_holtCharge * $new_holtageDay;
            $entryFee = $exportData[0]->e_fee;
            $totalTaka = $New_holtageTotalcharge + $entryFee;
            $vat = number_format((($totalTaka * 15) / 100), 2, '.', '');
            $grandTotal = ceil($vat + $totalTaka);


//            $entrance_fee = DB::select("SELECT h.name_of_charge, h.rate_of_charges FROM handling_and_othercharges AS h WHERE type_of_charge ='entrance_fee'");
            //     $getNetWeight = DB::select("SELECT * FROM delivery_export WHERE entry_datetime = ?", [$req->export_challan_no]);


            $p = DB::table('delivery_export')
                ->where('truck_bus_no', $r->truck_no)
                ->where('truck_bus_flag', 1)
                ->where('port_id', $port_id)
                ->update([
                    'total_amount' => $grandTotal
                ]);
            if ($p == true) {
                return "Inserted";
            }


        } else {

            return "Duplicate";

        }

    }

    public function updateExportTruckEntryData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');

        $postExTruckUpdate = DB::table('delivery_export')
            ->where('id', $r->id)
            ->where('truck_bus_flag', 1)
            ->update([
                'truck_bus_no' => $r->truck_no,
//                'driver_name' => $r->driver_name,
                'entry_datetime' => $r->entry_datetime,
                'haltage_day' => $r->haltage_day,
                'entrance_fee' => $r->entrance_fee,
                'truck_bus_type' => $r->truck_type

            ]);


        $exportData = DB::select("SELECT delivery_export.id AS truck_id, vehicle_type_bd.type_name,delivery_export.truck_bus_no AS truck_no_de_ex,
delivery_export.haltage_day AS h_day, delivery_export.entrance_fee AS e_fee,
delivery_export.truck_bus_type AS truck_type_de_ex,  delivery_export.driver_name AS driver_name_de_ex,
delivery_export.entry_datetime AS entry_datetime_de_ex, delivery_export.exit_datetime AS exit_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only,
DATE(`exit_datetime`) AS exit_date_only,
TIME(`entry_datetime`) AS entry_time_only,
TIME(`exit_datetime`) AS exit_time_only,
TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS holtage_day  
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 1 AND delivery_export.truck_bus_no=? AND delivery_export.port_id=?", [$r->truck_no,$port_id]);
        $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);


        $new_holtageDay = $exportData[0]->h_day;
        $new_holtCharge = $newHaltageCharge[0]->new_haltage_charge;
        $New_holtageTotalcharge = $new_holtCharge * $new_holtageDay;
        $entryFee = $exportData[0]->e_fee;
        $totalTaka = $New_holtageTotalcharge + $entryFee;
        $vat = number_format((($totalTaka * 15) / 100), 2, '.', '');
        $grandTotal = ceil($vat + $totalTaka);


//            $entrance_fee = DB::select("SELECT h.name_of_charge, h.rate_of_charges FROM handling_and_othercharges AS h WHERE type_of_charge ='entrance_fee'");
        //     $getNetWeight = DB::select("SELECT * FROM delivery_export WHERE entry_datetime = ?", [$req->export_challan_no]);


        $p = DB::table('delivery_export')
            ->where('truck_bus_no', $r->truck_no)
            ->where('truck_bus_flag', 1)
            ->where('port_id', $port_id)
            ->update([
                'total_amount' => $grandTotal
            ]);
//        if ($p == true){
//            return "Inserted";
//        }


        if ($p == true) {
            return "Updated";
        }
    }

    public function deleteExportTruckEntryData($id)
    {
        $port_id = Session::get('PORT_ID');
        DB::table('delivery_export')->where('id', $id)->where('port_id', $port_id)->delete();

        return 'success';
    }


    public function deleteVehicleTypeData($id)
    {
        $port_id = Session::get('PORT_ID');
//         $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello raju:".$id);
//        fclose($file);
//        return;

        DB::table('vehicle_type_bd')->where('id', $id)->where('port_id', $port_id)->delete();

        return 'success';
    }


    public function dateWiseAllTrucksData(Request $req)
    {
        $getData = DB::select("SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export JOIN vehicle_type_bd
ON delivery_export.truck_bus_type = vehicle_type_bd.id WHERE delivery_export.truck_bus_flag = 1 AND  delivery_export.entry_datetime =?", [$req->from_date_Truck]);
        return json_encode($getData);
    }

    public function DateWiseAllBuses(Request $req)
    {
        $getDataBuses = DB::select("SELECT vehicle_type_bd.type_name,delivery_export_bus.* FROM delivery_export_bus JOIN vehicle_type_bd
                                    ON delivery_export_bus.bus_type = vehicle_type_bd.id WHERE delivery_export_bus.entry_datetime =?", [$req->from_date_buses]);
        return json_encode($getDataBuses);
    }


    public function getAllTruckDetails()
    {
        $currentTime = date('Y-m-d');
//    	$getAllExTruck = DB::table('delivery_export')
//                            ->select(
//                                'delivery_export.*'
////                                DB::raw('TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS haltage_day')
//                                )
//    						->get();
//    	return json_encode($getAllExTruck);
        $truck_all_data = DB::SELECT('SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export JOIN vehicle_type_bd
ON delivery_export.truck_bus_type = vehicle_type_bd.id WHERE delivery_export.truck_bus_flag = 1 AND delivery_export.entry_datetime =?', [$currentTime]);

//        $truck_all_data = DB::SELECT('SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export JOIN vehicle_type_bd
//ON delivery_export.truck_type = vehicle_type_bd.id');
        RETURN json_encode($truck_all_data);
    }

    public function entranceFeeData()
    {
        $year = date('Y');

        $entrance_fee = DB::select("SELECT h.name_of_charge, h.rate_of_charges FROM handling_and_othercharges AS h WHERE h.charge_id BETWEEN 2 AND 6 AND charges_year =?", [$year]);

        return json_encode($entrance_fee);
    }


    public function truckTypeData()
    {
        $entrance_fee = DB::select("SELECT id AS truck_id,type_name FROM vehicle_type_bd  WHERE vehicle_type ='1'");

        return json_encode($entrance_fee);
    }


    public function getAllChallanListData()
    {
        $getAllChallan = DB::table('delivery_export_challan')
            ->where('truck_bus_flag', 1)
            ->select(
                'delivery_export_challan.*'
            )
            ->get();
        return json_encode($getAllChallan);
    }

    public function exitTruckData(Request $r)
    {


        $out_by = Auth::user()->name;

        $postGateOut = DB::table('delivery_export')
            ->where('delivery_export.id', $r->id)
            ->update([
                'delivery_export.exit_by' => $out_by,
                'delivery_export.exit_datetime' => $r->exit_datetime
            ]);
        if ($postGateOut == true) {
            return "Success";
        }


    }

    public function truckWiseMoneyReceiptReport($id)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');
//        dd($id);

        $exportData = DB::select("SELECT 
delivery_export.id AS truck_id,
vehicle_type_bd.type_name,
delivery_export.truck_bus_no AS truck_no_de_ex,
delivery_export.haltage_day AS h_day,
delivery_export.entrance_fee AS e_fee,
delivery_export.truck_bus_type AS truck_type_de_ex,  delivery_export.driver_name AS driver_name_de_ex,
delivery_export.entry_datetime AS entry_datetime_de_ex, delivery_export.exit_datetime AS exit_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only,
DATE(`exit_datetime`) AS exit_date_only,
TIME(`entry_datetime`) AS entry_time_only,
TIME(`exit_datetime`) AS exit_time_only,
TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS holtage_day  
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.port_id=? AND delivery_export.truck_bus_flag = 1 AND delivery_export.id=?", [$port_id,$id/*[0]->id*/]);

       // dd($exportData);

        if ($exportData == []){


            return view('default.export.error');

        }


        $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

//        if($exportData[0]->truck_type_de_ex == 0){
//            $entryFee = 10;
//            $holtage_charge = 20;
//        }else{
//            $entryFee = 20;
//            $holtage_charge = 30;
//        }


        $holtageDay = $exportData[0]->holtage_day;
        $new_holtageDay = $exportData[0]->h_day;
        $new_holtCharge = $newHaltageCharge[0]->new_haltage_charge;
//$holtageTotalcharge= $holtage_charge *$holtageDay;

        $New_holtageTotalcharge = $new_holtCharge * $new_holtageDay;

//$Vat = number_format((($TotalAssessmentValue * 15) / 100), 2, '.', '');

//        $totalTaka = $holtageTotalcharge + $entryFee;
        $entryFee = $exportData[0]->e_fee;
        $totalTaka = $New_holtageTotalcharge + $entryFee;

        $vat = number_format((($totalTaka * 15) / 100), 2, '.', '');
        $grandTotal = ceil($vat + $totalTaka);
        //dd($grandTotal);
        $pdf = PDF::loadView('default.export.reports.truck-wise-money-receipt-report', [
//            'exportTruckData' => $exportData,

            'truck_id' => $exportData[0]->truck_id,
            'type_name' => $exportData[0]->type_name,
            'truckNO' => $exportData[0]->truck_no_de_ex,
            'driverName' => $exportData[0]->driver_name_de_ex,
            'entry_datetime' => $exportData[0]->entry_date_only,
            'exit_datetime' => $exportData[0]->exit_date_only,

            'entry_time_only' => $exportData[0]->entry_time_only,
            'exit_time_only' => $exportData[0]->exit_time_only,
//            'holtage_day'=>$exportData[0]->holtage_day,
            'h_day' => $exportData[0]->h_day,
//            'entryFee'=>$entryFee,
            'e_fee' => $exportData[0]->e_fee,
//            'holtageTotalcharge'=>$holtageTotalcharge,
            'New_holtageTotalcharge' => $New_holtageTotalcharge,
            'totalTaka' => $totalTaka,
            'vat' => $vat,
            'grandTotal' => $grandTotal,

        ])/*->setPaper([0, 0,507,560, 500,370], 'landscape'); //previous value:  0, 0, 560,340 new 560,478 */
        ->setPaper([0, 0, 560, 800]);

//            ->setPaper('a4', 'landscape');
        return $pdf->stream('ExportTruckWise.pdf');


    }

    public function todaysTruckChallanReport()
    {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');
//dd($today);

        $challan_array = DB::select("SELECT d.export_challan_no AS challan_no FROM delivery_export_challan AS d WHERE d.port_id=? AND  d.truck_bus_flag = 1 AND d.challan_date = ?",[$port_id,$today]);
      //  dd($challan_array[0]->challan_no);
        $user_name = Auth::user()->name;

        if ($challan_array == []) {

            return view('default.export.error');
        }


        $id = $challan_array[0]->challan_no;
        $year = date('Y', strtotime($today));

//        $todayWithTime = date('Y-m-d h:i:s a');
//
//        $mainData = DB::select("SELECT * FROM delivery_export_challan WHERE DATE(create_datetime)=?",[$today]);
//
//        $pdf = PDF::loadView('Export.todaysChallanReportPDF',[
//            'mainData' => $mainData,
//            'todayWithTime' => $todayWithTime
//        ])  ->setPaper([0, 0, 808.661, 1020.63], 'landscape');
//        return $pdf->stream('todaysTruckEntryReportPDF.pdf');

//change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = ?",[$year,$year,$year,$year]);

//        dd($typeCharge[0]);

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


        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

        if ($exportChallanData == []) {

            return view('default.export.error');
        }


//        $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE type_of_charge = 'haltage_charges'");


        $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
        $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
        $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;


        //change Done
        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);

//        dd($first_charge);
        $a = $first_charge[0]->first_ent;
        $b = $second_charge[0]->second_ent;
        $c = $third_charge[0]->third_ent;

        $t_first = $first_c_truck_no * $a;
        $t_second = $second_c_truck_no * $b;
        $t_third = $third_c_truck_no * $c;

        $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;


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


        //   dd($grandTotal);

        if ($exportChallanData) {

            $pdf = PDF::loadView('default.export.reports.todays-challan-report', [

                'export_challan_no' => $exportChallanData[0]->export_challan_no,


                'first_charge_4_55' => $first_charge[0]->first_ent,
                'first_c_truck_no' => $first_c_truck_no,
                't_first' => $t_first,

                'second_charge_21_59' => $second_charge[0]->second_ent,
                'second_c_truck_no' => $second_c_truck_no,
                't_second' => $t_second,

                'third_charge_53_92' => $third_charge[0]->third_ent,
                'third_c_truck_no' => $third_c_truck_no,
                't_third' => $t_third,


                'create_datetime' => $exportChallanData[0]->create_datetime,
                'miscellaneous_name' => $exportChallanData[0]->miscellaneous_name,
                'miscellaneous_charge' => $exportChallanData[0]->miscellaneous_charge,
                'challan_no' => $exportChallanData[0]->challan_no,
                'Total_truck_no' => $exportChallanData[0]->Total_truck_no,
                'indian_truck' => $exportChallanData[0]->indian_truck,
                //'bd_haltage'=>$exportChallanData[0]->bd_haltage,
                'total_holtage_day' => $exportChallanData[0]->total_holtage_day,
                // 'indian_haltage'=>$exportChallanData[0]->indian_haltage,
                'haltage_charge' => $haltage_charge,
                'Total_entrance_fee_all_truck' => $Total_entrance_fee_all_truck,
                'total_haltage_charge_all_truck' => $total_haltage_charge_all_truck,
                'totalTakaWithoutVat' => $totalTakaWithoutVat,
                'cellVat' => $cellVat,
                'user_name' => $user_name,
                'grandTotal' => $grandTotal


            ])->setPaper([0, 0, 651.3, 900]);/*->setPaper([0, 0, 750, 800], 'landscape');*/

            return $pdf->stream('todaysTruckEntryReport.pdf');

        } else {
            return view('default.export.not-found-export', compact('today'));
        }
    }


    public function challanWiseExportTruckReport($id_i, $year)
    {
        $port_id = Session::get('PORT_ID');
       // dd($year);

        $id = $id_i . "/" . $year;


       // dd($id);      // 317/2017 , 314/2017  , 044/2018-02-13


      //  $year = date('Y', strtotime($year));    // for few minits

     //   dd($year);    // 2018,   2018 , 2018

      // dd($year);
//        $name = Auth::user()->name;
        $user_name = Auth::user()->name;

        //change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);

        //dd($typeCharge);

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

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=? ", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id,$port_id]);


        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

       // dd($holtage_charge);
        $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
        $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
        $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;


        //change Done
        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);

     //  dd($first_charge[0]->first_ent.$second_charge[0]->second_ent.$third_charge[0]->third_ent);



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

        $pdf = PDF::loadView('default.export.reports.challan-wise-export-report', [
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


    public function exportTruckChallanView()
    {


//        $r = '2017-12-28';
//        $truck_list = DB::select("SELECT GROUP_CONCAT(delivery_export.id) AS id FROM delivery_export WHERE entry_datetime =?", [$r]);
//        dd($truck_list[0]->id);

        $year = DB::select('SELECT DISTINCT YEAR(ex.create_datetime) AS year  FROM delivery_export_challan ex');

        return view('default.export.export-challan-entry-form', compact('year'));


    }

    public function getAllExTruckForChallan()
    {

        //$skipValues = DB::select('SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL');
        //$skipArray  = array_map('intval', explode(",",$skipValues[0]->res));
        $getAllExTruck = DB::select('SELECT *
                                    FROM delivery_export
                                    WHERE FIND_IN_SET(delivery_export.id, (SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL)) = 0');
        return json_encode($getAllExTruck);
        // $getAllExTruck = DB::table('delivery_export')
        //     ->select(
        //         'delivery_export.*',
        //         DB::raw('TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS haltage_day')
        //     )
        //     ->get();

    }

    public function getChallanShowData()
    {


//        $check_id_challan = DB::select('SELECT ch.delivery_export_id FROM delivery_export_challan AS ch');
//
//        if ($check_id_challan == null){

        $getAllExTruck = DB::select('SELECT *
FROM delivery_export
WHERE  FIND_IN_SET(
delivery_export.id,
 (SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL)) = 0
 OR (SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL) IS NULL;');
        return json_encode($getAllExTruck);

//        }else{
//
//            $getAllExTruck = DB::select('SELECT *
//        FROM delivery_export
//WHERE FIND_IN_SET(
//            delivery_export.id,(SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL)
//) = 0');
//            return json_encode($getAllExTruck);
//
//        }

        //$skipValues = DB::select('SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL');
        //$skipArray  = array_map('intval', explode(",",$skipValues[0]->res));


//        SELECT *
//        FROM delivery_export
//                                    WHERE FIND_IN_SET(delivery_export.id, (SELECT GROUP_CONCAT(delivery_export_id) AS res FROM delivery_export_challan WHERE delivery_export_id IS NOT NULL)) = 0

        // $getAllExTruck = DB::table('delivery_export')
        //     ->select(
        //         'delivery_export.*',
        //         DB::raw('TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS haltage_day')
        //     )
        //     ->get();

    }

    public function challanDetailsData(Request $req)
    {

//          $file = fopen("voucher.txt","w");
//              echo fwrite($file,"voucher:".$req->export_challan_no);
//              fclose($file);
//              return;

//        $getNetWeight = DB::select("SELECT de.id,de.truck_no, de.haltage_day, de.entrance_fee, de.driver_name,de.truck_type,de.entry_datetime,de.exit_datetime, des.id AS ch_id, des.export_challan_no, des.delivery_export_id,
//des.miscellaneous_name, des.miscellaneous_charge,des.create_datetime FROM delivery_export de,delivery_export_challan AS des
//WHERE des.export_challan_no=? AND FIND_IN_SET(de.id, des.delivery_export_id)", [$req->export_challan_no]);

        $getNetWeight = DB::select("SELECT * FROM delivery_export WHERE  truck_bus_flag = 1 AND entry_datetime=?", [$req->export_challan_no]);


        return json_encode($getNetWeight);

//        $allChallan = DB::table('delivery_export_challan AS m')
//            ->where('m.export_challan_no', $req->export_challan_no)
//
//            ->select(
//                'm.*'
//            )
//            ->get();
//        return $allChallan;
    }

    public function Update_Bus_Miscellaneous(Request $req)
    {

        $getNetWeight = DB::select("SELECT * FROM delivery_export_challan_bus WHERE DATE(create_datetime) =?", [$req->export_challan_no]);
        return json_encode($getNetWeight);
    }

    public function challanDetailsDataWithMiscellaneous(Request $req)
    {
//        dd('ddd');

        $getNetWeight = DB::select("SELECT * FROM delivery_export_challan 
                        WHERE truck_bus_flag = 1 AND  challan_date =?", [$req->export_challan_no]);

        return json_encode($getNetWeight);

    }

    public function updateChallanData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
             $year = date('Y', strtotime($r->searchDate));  //2018
//        $getlastFdrSlNo = DB::select('SELECT MAX(CAST(SUBSTRING(delivery_export_challan.export_challan_no,-8,3)  AS UNSIGNED)) AS challan_no   FROM delivery_export_challan');
//
//        if (!is_null($getlastFdrSlNo[0]->challan_no)) {
//            $NewChallan = $getlastFdrSlNo[0]->challan_no + 1;
//        } else {
//            $NewChallan = 1;
//        }

        $getlastFdrSlNo = DB::select('SELECT MAX(CAST(SUBSTRING(delivery_export_challan.export_challan_no,-8,3)  AS UNSIGNED)) AS challan_no   FROM delivery_export_challan');
        if(!is_null($getlastFdrSlNo[0]->challan_no)){
            $NewChallan = $getlastFdrSlNo[0]->challan_no + 1;
        }else{
            $NewChallan = 1;
        }

        $dateOfYear_test = date('z', strtotime($r->searchDate));
        $dateOfYear_test = $dateOfYear_test + 1;
        $challan_date = $r->searchDate;
        $createdChallan = str_pad($dateOfYear_test, 3, '0', STR_PAD_LEFT);
        $only_year = $r->searchDate;
       // $only_year = date("d-m-Y");
        $string = "/";
        $challan = $createdChallan . $string . $only_year;




      //  $truck_list = DB::select("SELECT GROUP_CONCAT(delivery_export.id) AS id FROM delivery_export WHERE entry_datetime =?", [$r->searchDate]);


        $entryUser = Auth::user()->username;
        $currentTime = date('Y-m-d H:i:s');

//        $exist_challan = DB::select('SELECT de.id  FROM delivery_export_challan AS de WHERE  de.truck_bus_flag =1 AND de.export_challan_no=?', [$challan]);
//        if ($exist_challan) {
//            return Response::json(['duplicate' => 'duplicate'], 209);
//        }


        $check_challan_no = DB::select("SELECT ch.id AS ch_id,ch.export_challan_no AS challan FROM delivery_export_challan AS ch WHERE
	   ch.truck_bus_flag =1 AND  DATE(challan_date) =? AND ch.port_id=?", [$r->searchDate,$port_id]);
//        $entryUser = Auth::user()->username;
//        $currentTime = date('Y-m-d H:i:s');
//
//        $exist_challan = DB::select('SELECT de.id  FROM delivery_export_challan AS de WHERE de.challan_date=?',[$r->searchDate]);
//        if ($exist_challan) {
//            return Response::json(['duplicate' => 'duplicate'], 209);
//        }
//
//        $check_challan_no = DB::select("SELECT ch.id AS ch_id,ch.export_challan_no AS challan FROM delivery_export_challan AS ch WHERE DATE(challan_date) =?", [$r->searchDate]);

     //   $challan_date = $r->searchDate;

//        //change Done
//        $typeCharge = DB::select("SELECT (SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
//(SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
//(SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge
//FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year ='2018'",[$year,$year,$year]);
//





        if ($check_challan_no == []) {

//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"if");
//            fclose($file);
//            return;

            $postExTruckEntry = DB::table('delivery_export_challan')
                ->insert([
                    'delivery_export_challan.export_challan_no' => $challan,
                    'delivery_export_challan.delivery_export_id' => $r->delivery_export_id_truck_list,
                    'delivery_export_challan.miscellaneous_name' => $r->miscellaneous_name,
                    'delivery_export_challan.miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.create_datetime' => $currentTime,
                    'delivery_export_challan.create_by' => $entryUser,
                    'delivery_export_challan.challan_date' => $challan_date,
                    'delivery_export_challan.truck_bus_flag' => 1,
                    'delivery_export_challan.port_id' => $port_id

                ]);
            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);





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

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan,$port_id]);

//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"save:".$exportChallanData[0]->third_entrance_t_no);
//            fclose($file);
//            return;

            //change Done
            $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

            $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
            $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
            $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;



            //change Done
            $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
            $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
            $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);
            $a = $first_charge[0]->first_ent;
            $b = $second_charge[0]->second_ent;
            $c = $third_charge[0]->third_ent;
            $t_first = $first_c_truck_no * $a;
            $t_second = $second_c_truck_no * $b;
            $t_third = $third_c_truck_no * $c;
            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;   //for 305/2017 entrance free is 317.33
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;
            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;
            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;
            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = ceil($vat);
            $grandTotal = ceil($cellVat + $totalTakaWithoutVat);

//
//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"hi:".$grandTotal);
//            fclose($file);
//            return;


            $p = DB::TABLE('delivery_export_challan')
                ->WHERE('challan_date', $challan_date)
                ->WHERE('truck_bus_flag', 1)
                ->WHERE('port_id',  $port_id)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);

//            $challan_array = DB::select("SELECT ch.id AS challan_id FROM delivery_export_challan AS ch WHERE ch.truck_bus_flag =1 AND  ch.export_challan_no =?", [$challan]);
//
//            $t = DB::table('transactions')
//                ->insert([
//                    'transactions.export_challan_flag' => 1,
//                    'transactions.challan_details_id' => $challan_array[0]->challan_id,
//                    'transactions.credit' =>$grandTotal,
//                    'transactions.sub_head_id' =>226,
//                    'transactions.debit' =>0,
//                    'transactions.comments' =>0,
//                    'transactions.entry_dt' =>date('Y-m-d H:i:s'),
//                    'transactions.trans_dt' =>date('Y-m-d H:i:s'),
//                    'transactions.userid' => Auth::user()->id
//                ]);

            IF ($p == TRUE) {
                RETURN "Inserted";
            }


        } else {

            $postExTruckUpdate = DB::table('delivery_export_challan')
                ->where('id', $check_challan_no[0]->ch_id)
                ->where('truck_bus_flag', 1)
                ->update([
                    'delivery_export_id' =>$r->delivery_export_id_truck_list,
                    'miscellaneous_name' => $r->miscellaneous_name,
                    'miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.challan_date' => $challan_date
                ]);


            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);



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

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan,$port_id]);




            //change Done
            $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);


            $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
            $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
            $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;

            //change Done
            $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
            $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
            $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);

            $a = $first_charge[0]->first_ent;
            $b = $second_charge[0]->second_ent;
            $c = $third_charge[0]->third_ent;
            $t_first = $first_c_truck_no * $a;
            $t_second = $second_c_truck_no * $b;
            $t_third = $third_c_truck_no * $c;
            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;   //for 305/2017 entrance free is 317.33
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;
            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;
            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;
            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = ceil($vat);
            $grandTotal = ceil($cellVat + $totalTakaWithoutVat);


//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"hi update:".$grandTotal);
//            fclose($file);
//            return;
//
//


            $p = DB::TABLE('delivery_export_challan')
                ->WHERE('challan_date', $challan_date)
                ->WHERE('truck_bus_flag', 1)
                ->WHERE('port_id',  $port_id)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);




            $t = DB::TABLE('transactions')
                ->WHERE('challan_details_id', $check_challan_no[0]->ch_id)
                ->WHERE('export_challan_flag', 0)
                ->UPDATE([
                    'transactions.credit' => $grandTotal,
                    'transactions.updated_at' => date('Y-m-d H:i:s'),
                    'transactions.updated_by' => Auth::user()->id
                ]);


            IF ($p == TRUE) {
                RETURN "Updated";
            }


        }

//        $postExTruckUpdate = DB::table('delivery_export_challan')
//            ->where('id', $r->id)
//            ->update([
//
//                'miscellaneous_name' => $r->miscellaneous_name,
//                'miscellaneous_charge' => $r->miscellaneous_charge,
//                'delivery_export_id' => $r->challan_update_id
//
//            ]);
//        if ($postExTruckUpdate == true){
//            return "Updated";
//        }
    }


    public function saveChallanData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y', strtotime($r->searchDate));

//        $random_number = str_pad(rand(0,365), 3, "0", STR_PAD_LEFT);
//        $only_year = date("Y");
//        $string = "/";
//        $challan = $random_number.$string.$only_year;

        $getlastFdrSlNo = DB::select('SELECT MAX(CAST(SUBSTRING(delivery_export_challan.export_challan_no,-8,3)  AS UNSIGNED)) AS challan_no   FROM delivery_export_challan');
        if(!is_null($getlastFdrSlNo[0]->challan_no)){
            $NewChallan = $getlastFdrSlNo[0]->challan_no + 1;
        }else{
            $NewChallan = 1;
        }
        $dateOfYear_test = date('z', strtotime($r->searchDate));
        $dateOfYear_test = $dateOfYear_test + 1;
        $challan_date = $r->searchDate;
        $createdChallan = str_pad($dateOfYear_test, 3, '0', STR_PAD_LEFT);
       // $only_year = date("d-m-Y");
        $only_year = $r->searchDate;

        $string = "/";
        $challan = $createdChallan . $string . $only_year;



//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"save challan: ".$challan);
//            fclose($file);
//            return;



//              $dateTruckSerch = date('Y-m-d');
//              $truck_list = DB::select("SELECT GROUP_CONCAT(delivery_export.id) AS id FROM delivery_export WHERE entry_datetime =?", [$r->searchDate]);



//$truck_list = DB::select("  SET SESSION group_concat_max_len = 1000000;
//SELECT GROUP_CONCAT(delivery_export.id)   AS id FROM delivery_export WHERE entry_datetime BETWEEN '2017-01-01' AND '2017-12-31'");

        $entryUser = Auth::user()->username;
        $currentTime = date('Y-m-d H:i:s');
        $exist_challan = DB::select('SELECT de.id  FROM delivery_export_challan AS de WHERE  de.truck_bus_flag =1 AND de.export_challan_no=? AND de.port_id=?', [$r->export_challan_no,$port_id]);
        if ($exist_challan) {
            return Response::json(['duplicate' => 'duplicate'], 209);
        }

        $check_challan_no = DB::select("SELECT ch.id AS ch_id,ch.export_challan_no AS challan FROM delivery_export_challan AS ch WHERE ch.port_id=? AND
 ch.truck_bus_flag =1 AND  DATE(challan_date) =? ", [$port_id,$r->searchDate]);


        if ($check_challan_no == []) {


            $postExTruckEntry = DB::table('delivery_export_challan')
                ->insertGetId([
                    'delivery_export_challan.export_challan_no' => $challan,
                    'delivery_export_challan.delivery_export_id' => $r->delivery_export_id_truck_list,
                    'delivery_export_challan.miscellaneous_name' => $r->miscellaneous_name,
                    'delivery_export_challan.miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.create_datetime' => $currentTime,
                    'delivery_export_challan.create_by' => $entryUser,
                    'delivery_export_challan.challan_date' => $challan_date,
                    'delivery_export_challan.truck_bus_flag' => 1,
                    'delivery_export_challan.port_id' => $port_id
                ]);
            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);





            //change Done
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

FROM delivery_export_challan AS dc WHERE  dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan,$port_id]);

//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"save:".$exportChallanData[0]->third_entrance_t_no);
//            fclose($file);
//            return;

            //change Done
            $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

            $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
            $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
            $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;



            //change Done
            $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
            $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
            $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);
            $a = $first_charge[0]->first_ent;
            $b = $second_charge[0]->second_ent;
            $c = $third_charge[0]->third_ent;
            $t_first = $first_c_truck_no * $a;
            $t_second = $second_c_truck_no * $b;
            $t_third = $third_c_truck_no * $c;
            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;   //for 305/2017 entrance free is 317.33
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;
            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;
            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;
            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = ceil($vat);
            $grandTotal = ceil($cellVat + $totalTakaWithoutVat);

//
//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"hi:".$grandTotal);
//            fclose($file);
//            return;


            $p = DB::TABLE('delivery_export_challan')
                ->WHERE('challan_date', $challan_date)
                ->WHERE('truck_bus_flag', 1)
                ->WHERE('port_id', $port_id)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);

            //$challan_array = DB::select("SELECT ch.id AS challan_id FROM delivery_export_challan AS ch WHERE ch.truck_bus_flag =1 AND  ch.export_challan_no =?", [$challan]);

            $t = DB::table('transactions')
                ->insert([
                    'transactions.export_challan_flag' => 0,
                    'transactions.challan_details_id' => $postExTruckEntry,
                    'transactions.credit' =>$grandTotal,
                    'transactions.sub_head_id' =>226,
                    'transactions.debit' =>0,
                    'transactions.comments' =>0,
                    'transactions.port_id' =>$port_id,
                    'transactions.entry_dt' =>date('Y-m-d H:i:s'),
                    'transactions.trans_dt' =>date('Y-m-d H:i:s'),
                    'transactions.userid' => Auth::user()->id,
                    'transactions.updated_at' => date('Y-m-d H:i:s'),
                    'transactions.updated_by' => Auth::user()->id
                ]);

            IF ($p == TRUE) {
                RETURN "Inserted";
            }


        } else {


            $postExTruckUpdate = DB::table('delivery_export_challan')
                ->where('id', $check_challan_no[0]->ch_id)
                ->where('truck_bus_flag', 1)
                ->update([
                    'delivery_export_challan.delivery_export_id' =>$r->delivery_export_id_truck_list,
                    'delivery_export_challan.miscellaneous_name' => $r->miscellaneous_name,
                    'delivery_export_challan.miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.challan_date' => $challan_date
                ]);


            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);



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

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan,$port_id]);




            //change Done
            $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);


            $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
            $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
            $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;

            //change Done
            $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
            $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
            $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);


            $a = $first_charge[0]->first_ent;
            $b = $second_charge[0]->second_ent;
            $c = $third_charge[0]->third_ent;

            $t_first = $first_c_truck_no * $a;
            $t_second = $second_c_truck_no * $b;
            $t_third = $third_c_truck_no * $c;

            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;   //for 305/2017 entrance free is 317.33
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;
            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;
            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;
            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = ceil($vat);
            $grandTotal = ceil($cellVat + $totalTakaWithoutVat);




            $p = DB::TABLE('delivery_export_challan')
                ->WHERE('challan_date', $challan_date)
                ->WHERE('truck_bus_flag', 1)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);
            IF ($p == TRUE) {
                RETURN "Updated";
            }

        }


    }
    //-----------------------------------------------------end save function--------------------------------------------


    public function deleteChallanData($id)
    {
        $port_id = Session::get('PORT_ID');
        DB::table('delivery_export_challan')->where('id', $id)->delete();
        DB::table('transactions')->where('challan_details_id', $id)->where('export_challan_flag', 0)->where('port_id',$port_id)->delete();

        return 'success';
    }


    public function getChallanUpdate($id)
    {
        $getNetWeight = DB::select("SELECT de.id,de.truck_no,de.truck_type,de.entry_datetime,de.exit_datetime, des.id AS ch_id, des.export_challan_no, des.delivery_export_id,
 des.miscellaneous_name, des.miscellaneous_charge,des.create_datetime FROM delivery_export de,delivery_export_challan AS des 
  WHERE des.export_challan_no=? AND FIND_IN_SET(
        de.id, des.delivery_export_id
    )", [$id]);

        return json_encode($getNetWeight);
    }


    public function dateWiseEntryReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');

        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $requestedDate = $r->from_date;
      //  dd($r->from_date);
        $DWRDate = DB::select(" SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export
 JOIN vehicle_type_bd ON delivery_export.truck_bus_type = vehicle_type_bd.id  WHERE delivery_export.port_id=? AND
  delivery_export.truck_bus_flag = 1 AND  delivery_export.entry_datetime=?", [$port_id,$r->from_date]);
        //dd($DWRDate);

        if ($DWRDate == []){

            return view('default.export.error');
        }

        if ($DWRDate) {
            $pdf = PDF::loadView('default.export.reports.date-wise-truck-entry-report', [
                'DWRDate' => $DWRDate,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date
            ])->setPaper([0, 0, 808, 620.63], 'landscape');
            // ->setPaper([0, 0, 808.661, 1020.63], 'landscape');
            // ->setPaper([0, 0, 808, 620.63], 'landscape');
            return $pdf->stream('DateWiseTruckEntryReport.pdf');
        } else {
            //  return view('posting.notFound',compact(''/*'requestedDate'*/));
            return view('default.export.error');
        }
    }


    public function getTodaysTruckEntryReport()
    {
        $port_id = Session::get('PORT_ID');

        $today = date('Y-m-d');
     //   dd($today);
        $todayWithTime = date('Y-m-d h:i:s a');

        $mainData = DB::select("SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export
 JOIN vehicle_type_bd ON delivery_export.truck_bus_type = vehicle_type_bd.id  WHERE delivery_export.port_id=? AND
 delivery_export.truck_bus_flag = 1 AND delivery_export.entry_datetime =?", [$port_id,$today]);

       //  dd($mainData);
        if ($mainData == []) {

            return view('default.export.error');
        }

        $pdf = PDF::loadView('default.export.reports.todays-truck-entry-report', [
            'mainData' => $mainData,
            'todayWithTime' => $todayWithTime
        ])->setPaper([0, 0, 808, 620.63], 'landscape');
        return $pdf->stream('todaysTruckEntryReport.pdf');

    }


    public function dateWiseTruckChallanReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $requestedDate = $r->from_date_challan;
       // dd($requestedDate);
        $user_name = Auth::user()->name;

        $challan_array = DB::select("SELECT d.export_challan_no AS challan_no FROM delivery_export_challan AS d WHERE d.truck_bus_flag AND d.challan_date =? AND d.port_id=?",[$requestedDate,$port_id]);
        //  dd($challan_array[0]->challan_no);



        $id = $challan_array[0]->challan_no;
        $year = date('Y', strtotime($requestedDate));



//change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);

//        dd($typeCharge[0]);

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

FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =1 AND dc.export_challan_no=? AND dc.port_id=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id, $port_id]);


        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

        if ($exportChallanData == []) {

            return view('default.export.error');
        }


//        $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE type_of_charge = 'haltage_charges'");


        $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
        $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
        $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;


        //change Done
        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);


        $a = $first_charge[0]->first_ent;
        $b = $second_charge[0]->second_ent;
        $c = $third_charge[0]->third_ent;

        $t_first = $first_c_truck_no * $a;
        $t_second = $second_c_truck_no * $b;
        $t_third = $third_c_truck_no * $c;

        $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;


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

        if ($exportChallanData == '[]') {

//            return view('Export.notFoundExport',compact('requestedDate'));

            return view('default.export.error', compact('requestedDate'));

        } else {

            $pdf = PDF::loadView('default.export.reports.date-wise-challan-report', [

                'export_challan_no' => $exportChallanData[0]->export_challan_no,

                'first_charge_4_55' => $first_charge[0]->first_ent,
                'first_c_truck_no' => $first_c_truck_no,
                't_first' => $t_first,

                'second_charge_21_59' => $second_charge[0]->second_ent,
                'second_c_truck_no' => $second_c_truck_no,
                't_second' => $t_second,

                'third_charge_53_92' => $third_charge[0]->third_ent,
                'third_c_truck_no' => $third_c_truck_no,
                't_third' => $t_third,


                'create_datetime' => $exportChallanData[0]->create_datetime,
                'miscellaneous_name' => $exportChallanData[0]->miscellaneous_name,
                'miscellaneous_charge' => $exportChallanData[0]->miscellaneous_charge,
                'Total_truck_no' => $exportChallanData[0]->Total_truck_no,
                'indian_truck' => $exportChallanData[0]->indian_truck,
                'total_holtage_day' => $exportChallanData[0]->total_holtage_day,
                'haltage_charge' => $haltage_charge,
                'Total_entrance_fee_all_truck' => $Total_entrance_fee_all_truck,
                'user_name' => $user_name,
                'challan_no' => $exportChallanData[0]->challan_no,
                'total_haltage_charge_all_truck' => $total_haltage_charge_all_truck,
                'totalTakaWithoutVat' => $totalTakaWithoutVat,
                'cellVat' => $cellVat,
                'grandTotal' => $grandTotal


            ])->setPaper([0, 0, 651.3, 900]);/*->setPaper([0, 0, 750, 800], 'landscape');*/

            return $pdf->stream('DateWiseChallanReport.pdf');
        }


//        if($exportChallanData) {
//
//        }else{
//
//
//        }
    }





//    ========= Bus End ===============


//===================Bus Challan=========================

    public function ExportBusChallan()   // remove it
    {


        $year = DB::select('SELECT DISTINCT YEAR(ex.create_datetime) AS year  FROM delivery_export_challan_bus ex');

        return view('Export.ExportChallanBus', compact('year'));


    }



//====================Bus Challan End========================
//=============================================================================Truck/Bus=================================
    public function truckBusTypeEntryFormView()
    {
        return view('default.export.truck-bus-type-entry-form');
    }


    public function truckBusSaveData(Request $r)
    {
        $port_id = Session::get('PORT_ID');

        $Checktype = DB::table('vehicle_type_bd')
            ->where('type_name', $r->type_name)
            ->where('port_id', $port_id)
            ->get();


        //  $all_data = DB::SELECT('SELECT type_name FROM vehicle_type_bd WHERE vehicle_type_bd.type_name =?',[$r->type_name]);

//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello".$Checktype);
//        fclose($file);
//        return ;

        if ($Checktype == '[]') {

            $entryUser = Auth::user()->username;
            $currentTime = date('Y-m-d H:i:s');
            $postbusTruckType = DB::table('vehicle_type_bd')
                ->insert([
                    'vehicle_type_bd.type_name' => $r->type_name,
                    'vehicle_type_bd.vehicle_type' => $r->vehicle_type,
                    'vehicle_type_bd.created_at' => $currentTime,
                    'vehicle_type_bd.created_by' => $entryUser,
                    'vehicle_type_bd.port_id' => $port_id
                ]);
            if ($postbusTruckType == true) {
                return "Inserted";
            }

        } else {
            return "Duplicate";
        }


    }


    public function getAllVehicleTypeData()
    {
        $getAllExTruck = DB::table('vehicle_type_bd')
            ->select(
                'vehicle_type_bd.*'
            )
            ->get();
        return json_encode($getAllExTruck);
    }

    public function updateTruckBusTypeData(Request $r)
    {
        $entryUser = Auth::user()->username;
        $currentTime = date('Y-m-d H:i:s');
        $postExTruckUpdate = DB::table('vehicle_type_bd')
            ->where('id', $r->id)
            ->update([
                'vehicle_type' => $r->vehicle_type,
                'type_name' => $r->type_name,
                'updated_by' => $entryUser,
                'updated_dt' => $currentTime

            ]);
        if ($postExTruckUpdate == true) {
            return "Updated";
        }
    }




    public function monthWiseTruckChallanReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
//        $dates = date('Y-m-d');

        /*     $todaysEntry=  DB::table('truck_entry_regs')
              ->where('truckentry_datetime','LIKE',"%$dates%")
              ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
              ->select('truck_entry_regs.*', 'cargo_details.cargo_name')
              ->get();*/

        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT * FROM delivery_export_challan
WHERE delivery_export_challan.port_id=? AND delivery_export_challan.truck_bus_flag =1 AND delivery_export_challan.create_datetime  BETWEEN ? AND ? 
ORDER BY delivery_export_challan.id DESC", [$port_id,$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select("SELECT  SUM(delivery_export_challan.total_amount) AS total_amount
FROM delivery_export_challan 
WHERE delivery_export_challan.port_id=? AND delivery_export_challan.truck_bus_flag =1 AND delivery_export_challan.create_datetime  BETWEEN ? AND ? ", [$port_id,$r->from_date_v, $r->to_date_v]);
        $amount = $total_expenditure_amount[0]->total_amount;

        if ($expenditure == []) {
            return view('default.export.error');
        }

        $pdf = PDF::loadView('default.export.reports.month-wise-challan-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('monthWiseExportReport.pdf');

    }


    public function monthWiseExportTruckReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
//        $dates = date('Y-m-d');

        /*     $todaysEntry=  DB::table('truck_entry_regs')
              ->where('truckentry_datetime','LIKE',"%$dates%")
              ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
              ->select('truck_entry_regs.*', 'cargo_details.cargo_name')
              ->get();*/
//dd($r->from_date_v.$r->to_date_v);
        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT delivery_export.id AS truck_id, vehicle_type_bd.type_name,delivery_export.truck_bus_no AS truck_no, delivery_export.total_amount AS total_amount,
delivery_export.haltage_day AS haltage_day, delivery_export.entrance_fee AS entrance_fee,
delivery_export.entry_datetime AS entry_datetime, 
delivery_export.entry_by AS created_by
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.port_id=? AND delivery_export.truck_bus_flag=1 AND delivery_export.entry_datetime  BETWEEN ? AND ? 
ORDER BY delivery_export.id DESC", [$port_id,$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select("SELECT  SUM(delivery_export.total_amount) AS total_amount
FROM delivery_export 
WHERE delivery_export.port_id=? AND delivery_export.truck_bus_flag=1 AND delivery_export.entry_datetime  BETWEEN ? AND ?", [$port_id,$r->from_date_v, $r->to_date_v]);
        $amount = $total_expenditure_amount[0]->total_amount;

        if ($expenditure == []) {
            return view('default.export.error');
        }

        $pdf = PDF::loadView('default.export.reports.month-wise-truck-entry-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('monthWiseExportTruckEntryReport.pdf');

    }

    public function yearlyTruckEntryReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
       // dd($firstDate.$lastDate);
        //return $firstDate. " " . $lastDate;
        $expenditure = DB::select('SELECT r_de.July,r_de.August,r_de.September,r_de.October,r_de.November,r_de.December,r_de.January,r_de.February,r_de.March,r_de.April,r_de.May,r_de.June,
(r_de.January+r_de.February+r_de.March+r_de.April+r_de.May+r_de.June+r_de.July+r_de.August+r_de.September+r_de.October+r_de.November+r_de.December) AS  Total
FROM(
SELECT t_de.entry_datetime,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag = 1 AND  MONTH(entry_datetime)=7),0) AS July,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=8),0) AS August,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=9),0) AS September,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=10),0) AS October,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=11),0) AS November,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=12),0) AS December,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=1),0) AS January,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=2),0) AS February,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=3),0) AS March,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=4),0) AS April,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=5),0) AS May,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag = 1 AND MONTH(entry_datetime)=6),0) AS June
FROM  (SELECT entry_datetime,entry_by FROM delivery_export de
 WHERE port_id=? AND truck_bus_flag = 1 AND DATE(entry_datetime) BETWEEN ? AND ?)  t_de
  GROUP BY t_de.entry_by AND t_de.entry_datetime) r_de', [$port_id,$firstDate, $lastDate]);

        if ($expenditure == []) {
            return view('default.export.error');
        }

        $pdf = PDF::loadView('default.export.reports.yearly-truck-entry-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('YearlyTruckEntryReport.pdf');

    }




    public function yearlyTruckChallanReport(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        //return $firstDate. " " . $lastDate;
        $expenditure = DB::select('SELECT r_de.July,r_de.August,r_de.September,r_de.October,r_de.November,r_de.December,r_de.January,r_de.February,r_de.March,r_de.April,r_de.May,r_de.June,
(r_de.January+r_de.February+r_de.March+r_de.April+r_de.May+r_de.June+r_de.July+r_de.August+r_de.September+r_de.October+r_de.November+r_de.December) AS  Total
FROM(

SELECT t_de.create_datetime,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND  MONTH(create_datetime)=7),0) AS July,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=8),0) AS August,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=9),0) AS September,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=10),0) AS October,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=11),0) AS November,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=12),0) AS December,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=1),0) AS January,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=2),0) AS February,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=3),0) AS March,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=4),0) AS April,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=5),0) AS May,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 1 AND MONTH(create_datetime)=6),0) AS June

FROM  
  (
  SELECT create_datetime,create_by FROM delivery_export_challan de
 WHERE de.port_id=? AND  truck_bus_flag = 1 AND DATE(create_datetime) BETWEEN ? AND ?)  t_de
 
 
  GROUP BY t_de.create_by AND t_de.create_datetime) r_de', [$port_id,$firstDate, $lastDate]);

        if ($expenditure == []) {
            return view('default.export.error');
        }

        $pdf = PDF::loadView('default.export.reports.yearly-challan-report', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('YearlyTruckChallanReport.pdf');

    }





//=============================================================================Truck/Bus End=================================
}
