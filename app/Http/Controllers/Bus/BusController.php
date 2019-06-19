<?php

namespace App\Http\Controllers\Bus;
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



class BusController extends Controller
{

    public function welcome()
    {

        $currentDate = date('Y-m-d');
//      $name = Auth::user()->name;
        $name = "Bus";

        $todaysTruckTotal = DB::select('SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE delivery_export.truck_bus_flag=1 AND   delivery_export.entry_datetime=?', [$currentDate]);

        $todaysManifestByUser = DB::select('SELECT COUNT(id) total_Bus_entry_bus  FROM delivery_export WHERE delivery_export.truck_bus_flag=0 AND   delivery_export.entry_datetime=?', [$currentDate]);

        $Trucks_of_manifest = DB::select('SELECT COUNT(id) total_Bus_entry_user  FROM delivery_export WHERE delivery_export.truck_bus_flag=0 AND  entry_by=? AND entry_datetime=?', [$name, $currentDate]);

        $todaysTruckByUser = DB::select('SELECT COUNT(id) total_truck_entry  FROM delivery_export WHERE delivery_export.truck_bus_flag=1 AND  entry_by=? AND entry_datetime=?', [$name, $currentDate]);


        return view('Bus.welcome', compact('todaysTruckTotal', 'todaysManifestByUser', 'Trucks_of_manifest', 'todaysTruckByUser'));
    }

    //=============================================================== vehical type form code ================================

    public function busTypeEntryView()
    {
        return view('Bus.busTypeEntryForm');
    }

    public function saveBusTypeData(Request $r)
    {

        $Checktype = DB::table('vehicle_type_bd')
            ->where('type_name', $r->type_name)
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
                    'vehicle_type_bd.created_by' => $entryUser
                ]);
            if ($postbusTruckType == true) {
                return "Inserted";
            }

        } else {
            return "Duplicate";
        }


    }


    public function updateBusTypeData(Request $r)
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

    public function updateBusEntryData(Request $r)
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

    public function updateExitBusData(Request $r)
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


    public function busEntranceFeeData()
    {
        $year = date('Y');

        $entrance_fee = DB::select("SELECT h.name_of_charge, h.rate_of_charges FROM handling_and_othercharges AS h WHERE h.charge_id BETWEEN 2 AND 6 AND charges_year =?", [$year]);

        return json_encode($entrance_fee);
    }


    public function busModuleTruckTypedata()
    {
        $entrance_fee = DB::select("SELECT id AS truck_id,type_name FROM vehicle_type_bd  WHERE vehicle_type ='1'");

        return json_encode($entrance_fee);
    }


    public function deleteBusTypeData($id)
    {

//         $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello raju:".$id);
//        fclose($file);
//        return;

        DB::table('vehicle_type_bd')->where('id', $id)->delete();

        return 'success';
    }


    public function getBusAllDataDetails()
    {
        $getAllExTruck = DB::table('vehicle_type_bd')
            ->select(
                'vehicle_type_bd.*'
            )
            ->get();
        return json_encode($getAllExTruck);
    }

    //===================================================================== Bus Entry Code Below =============================

    public function busEntryFormView()
    {

        $year = DB::select('SELECT DISTINCT YEAR(ex.entry_datetime) AS year  FROM delivery_export ex');

        return view('Bus.busModuleEntryForm', compact('year'));


    }


    public function saveBusEntryData(Request $r)
    {

        $year = date('Y');
        $todayTime = date('Y-m-d');


        $CheckBus = DB::table('delivery_export')
            ->where('truck_bus_no', $r->bus_no)
            ->where('entry_datetime', $todayTime)
            ->where('truck_bus_flag', 0)
            ->get();


        if ($CheckBus == '[]') {


            $entryUser = Auth::user()->username;
            $postExTruckEntry = DB::table('delivery_export')
                ->insert([
                    'truck_bus_no' => $r->bus_no,
                    'entry_datetime' => $r->entry_datetime_bus,
                    'entrance_fee' => $r->rate_of_charges,
                    'haltage_day' => $r->haltage_day,
                    'truck_bus_type' => $r->bus_type,
                    'entry_by' => $entryUser,
                    'truck_bus_flag' => 0
                ]);


            $exportData = DB::select("SELECT delivery_export.truck_bus_no AS truck_no_de_ex, delivery_export.id AS bus_export_id, vehicle_type_bd.type_name,
delivery_export.haltage_day AS h_day,delivery_export.entrance_fee AS e_fee,delivery_export.entry_datetime AS entry_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.truck_bus_no=?", [$r->bus_no]);

            $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h  WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);





            $new_holtageDay = $exportData[0]->h_day;
            $new_holtCharge = $newHaltageCharge[0]->new_haltage_charge;


            $New_holtageTotalcharge = $new_holtCharge * $new_holtageDay;
            $entryFee = $exportData[0]->e_fee;
            $totalTaka = $New_holtageTotalcharge + $entryFee;

            $vat = number_format((($totalTaka * 15) / 100), 2, '.', '');
            $grandTotal = ceil($vat + $totalTaka);

//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"raju".$grandTotal);
//            fclose($file);
//            return;

            $p = DB::table('delivery_export')
                ->where('truck_bus_no', $r->bus_no)
                ->where('truck_bus_flag', 0)
                ->update([
                    'total_amount' => $grandTotal
                ]);
            if ($postExTruckEntry == true) {
                return "Inserted";
            }


        } else {
            return "Duplicate";


        }


    }




    public function updateBusData(Request $r)
    {
        $year = date('Y');
        $postExTruckUpdate = DB::table('delivery_export')
            ->where('id', $r->id)
            ->where('truck_bus_flag', 0)
            ->update([
                'truck_bus_no' => $r->bus_no,
                'entry_datetime' => $r->entry_datetime_bus,
                'entrance_fee' => $r->rate_of_charges,
                'truck_bus_type' => $r->bus_type,
                'haltage_day' => $r->haltage_day
            ]);


//        $exportData = DB::select("SELECT
//delivery_export_bus.bus_no AS truck_no_de_ex,
//delivery_export_bus.id AS bus_export_id,
//vehicle_type_bd.type_name,
//delivery_export_bus.haltage_day AS h_day,
//delivery_export_bus.entrance_fee AS e_fee,
//
//delivery_export_bus.entry_datetime AS entry_datetime_de_ex,
//DATE(`entry_datetime`) AS entry_date_only
//FROM delivery_export_bus JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export_bus.bus_type
//WHERE delivery_export_bus.bus_no=?", [$r->bus_no]);

        $exportData = DB::select("SELECT delivery_export.truck_bus_no AS truck_no_de_ex, delivery_export.id AS bus_export_id, vehicle_type_bd.type_name,
delivery_export.haltage_day AS h_day,delivery_export.entrance_fee AS e_fee,delivery_export.entry_datetime AS entry_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.truck_bus_no=?", [$r->bus_no]);

        $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h  WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);


        $new_holtageDay = $exportData[0]->h_day;
        $new_holtCharge = $newHaltageCharge[0]->new_haltage_charge;


        $New_holtageTotalcharge = $new_holtCharge * $new_holtageDay;
        $entryFee = $exportData[0]->e_fee;
        $totalTaka = $New_holtageTotalcharge + $entryFee;

        $vat = number_format((($totalTaka * 15) / 100), 2, '.', '');
        $grandTotal = ceil($vat + $totalTaka);


        $p = DB::table('delivery_export')
            ->where('truck_bus_no', $r->bus_no)
            ->where('truck_bus_flag', 0)
            ->update([
                'total_amount' => $grandTotal
            ]);
        if ($p == true) {
            return "Updated";
        }

    }






    public function deleteBusEntryData($id)
    {
        DB::table('delivery_export')->where('id', $id)->delete();

        return 'success';
    }



    public function getAllBusEntryData(Request $req)
    {
        $getDataBuses = DB::select(" SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export JOIN vehicle_type_bd
ON delivery_export.truck_bus_type = vehicle_type_bd.id WHERE delivery_export.truck_bus_flag = 0 AND delivery_export.entry_datetime =?", [$req->from_date_buses]);
        return json_encode($getDataBuses);
    }



    public function getAllExportBusData()
    {
        $currentTime = date('Y-m-d');
//        $getAllExBus = DB::table('delivery_export_bus')
//            ->select(
//                'delivery_export_bus.*'
////                                DB::raw('TIMESTAMPDIFF(DAY, delivery_export.entry_datetime, delivery_export.exit_datetime) AS haltage_day')
//            )
//            ->get();
//        return json_encode($getAllExBus);

        $bus_all_data = DB::SELECT('SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export JOIN vehicle_type_bd
ON delivery_export.truck_bus_type = vehicle_type_bd.id WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.entry_datetime =?', [$currentTime]);
        RETURN json_encode($bus_all_data);
    }



    public function busTypeDataDetails()
    {
        $entrance_fee = DB::select("SELECT id AS bus_id,type_name FROM vehicle_type_bd  WHERE vehicle_type ='0'");

        return json_encode($entrance_fee);
    }


    public function entranceFeeForBusEntry()
    {
        $year = date('Y');

        $entrance_fee = DB::select("SELECT h.name_of_charge, h.rate_of_charges FROM handling_and_othercharges AS h WHERE h.charge_id BETWEEN 2 AND 6 AND charges_year =?", [$year]);

        return json_encode($entrance_fee);
    }




    public function getBusEntryMoneyReceiptReport($id)
    {

        $year = date('Y');

      //  dd($id);

        $exportData = DB::select(" SELECT 
delivery_export.truck_bus_no AS truck_no_de_ex,
delivery_export.id AS bus_export_id,
vehicle_type_bd.type_name,
delivery_export.haltage_day AS h_day,
delivery_export.entrance_fee AS e_fee,
delivery_export.entry_datetime AS entry_datetime_de_ex,
DATE(`entry_datetime`) AS entry_date_only
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 0 AND delivery_export.id =?", [$id/*[0]->id*/]);

        $newHaltageCharge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

      //  dd($exportData);

        if ($exportData == []){
            return view('Export.error');
        }


//        if($exportData[0]->truck_type_de_ex == 0){
//            $entryFee = 10;
//            $holtage_charge = 20;
//        }else{
//            $entryFee = 20;
//            $holtage_charge = 30;
//        }


        //  $holtageDay = $exportData[0]->holtage_day;
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
        $pdf = PDF::loadView('Bus.reports.BusWiseMoneyReceiptBusModulePDF', [
//            'exportTruckData' => $exportData,
            'truckNO' => $exportData[0]->truck_no_de_ex,
            'type_name' => $exportData[0]->type_name,
            'bus_export_id' => $exportData[0]->bus_export_id,

//            'driverName'=>$exportData[0]->driver_name_de_ex,
            'entry_datetime' => $exportData[0]->entry_date_only,
//            'exit_datetime'=>$exportData[0]->exit_date_only,

            //  'entry_time_only'=>$exportData[0]->entry_time_only,
//            'exit_time_only'=>$exportData[0]->exit_time_only,
//            'holtage_day'=>$exportData[0]->holtage_day,
            'h_day' => $exportData[0]->h_day,
//            'entryFee'=>$entryFee,
            'e_fee' => $exportData[0]->e_fee,
//            'holtageTotalcharge'=>$holtageTotalcharge,
            'New_holtageTotalcharge' => $New_holtageTotalcharge,
            'totalTaka' => $totalTaka,
            'vat' => $vat,
            'grandTotal' => $grandTotal,

        ])
            ->setPaper([0, 0, 560, 800]);
//            ->setPaper([0, 0, 500, 800], 'landscape');
//            ->setPaper('a4', 'landscape');
        return $pdf->stream('BusWise.pdf');

    }



    public function monthWiseExportBusReport(Request $r)
    {
        $todayWithTime = date('Y-m-d h:i:s a');
      //  dd($r->from_date_v.$r->to_date_v);
        $expenditure = DB::select("SELECT delivery_export.id AS bus_id, vehicle_type_bd.type_name,delivery_export.truck_bus_no AS bus_no,
 delivery_export.total_amount AS total_amount,
delivery_export.haltage_day AS haltage_day, delivery_export.entrance_fee AS entrance_fee,
delivery_export.entry_datetime AS entry_datetime, 
delivery_export.entry_by AS created_by
FROM delivery_export JOIN vehicle_type_bd ON vehicle_type_bd.id = delivery_export.truck_bus_type
WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.entry_datetime  BETWEEN ? AND ?
 ORDER BY delivery_export.id DESC", [$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select("SELECT  SUM(delivery_export.total_amount) AS total_amount
FROM delivery_export 
WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.entry_datetime   BETWEEN ? AND ?", [$r->from_date_v, $r->to_date_v]);

        $amount = $total_expenditure_amount[0]->total_amount;

        if ($expenditure == []) {
            return view('Bus.reports.error');
        }
        $pdf = PDF::loadView('Bus.reports.monthWiseReport_busModulePDF', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');
        return $pdf->stream('monthWiseBusReportPDF.pdf');
    }



    public function getTodaysBusEntryReport()
    {

        $today = date('Y-m-d');
       // dd($today);
        $todayWithTime = date('Y-m-d h:i:s a');

        $mainData = DB::select("SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export
 JOIN vehicle_type_bd ON delivery_export.truck_bus_type = vehicle_type_bd.id  WHERE delivery_export.truck_bus_flag = 0 AND  delivery_export.entry_datetime=?", [$today]);

        //   dd($mainData);
        if ($mainData == []) {

            return view('Bus.reports.error');
        }

        $pdf = PDF::loadView('Bus.reports.todaysBusEntryReport_busModulePDF', [
            'mainData' => $mainData,
            'todayWithTime' => $todayWithTime
        ])->setPaper([0, 0, 808, 620.63], 'landscape');
        return $pdf->stream('todaysBusEntryReportPDF.pdf');

    }



    public function getDateWiseBusEntryReport(Request $r)
    {

        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $requestedDate = $r->from_date_b;
       // dd($requestedDate);
        $DWRDate = DB::select(" SELECT vehicle_type_bd.type_name,delivery_export.* FROM delivery_export
 JOIN vehicle_type_bd ON delivery_export.truck_bus_type = vehicle_type_bd.id  WHERE delivery_export.truck_bus_flag = 0 AND delivery_export.entry_datetime=?", [$r->from_date_b]);
//        dd($DWRDate);
//        if ($DWRDate == []){
//
//            return view('Export.error');
//        }

        if ($DWRDate) {
            $pdf = PDF::loadView('Bus.reports.datawiseBusEntryReport_busModulePDF', [
                'DWRDate' => $DWRDate,
                'todayWithTime' => $todayWithTime,
                'from_date' => $r->from_date_b
            ])->setPaper([0, 0, 808, 620.63], 'landscape');
            return $pdf->stream('DateWiseBusEntryReport.pdf');
        } else {
            //return view('posting.notFound',compact('requestedDate'));
            return view('Bus.reports.error');
        }
    }



    public function yearlyBusEntryReport(Request $r)
    {

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
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND  MONTH(entry_datetime)=7),0) AS July,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE  truck_bus_flag =0 AND MONTH(entry_datetime)=8),0) AS August,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=9),0) AS September,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=10),0) AS October,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=11),0) AS November,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=12),0) AS December,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=1),0) AS January,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=2),0) AS February,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=3),0) AS March,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=4),0) AS April,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=5),0) AS May,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export WHERE truck_bus_flag =0 AND MONTH(entry_datetime)=6),0) AS June
FROM  (SELECT entry_datetime,entry_by FROM delivery_export de
 WHERE truck_bus_flag =0 AND DATE(entry_datetime) BETWEEN ? AND ?)  t_de
  GROUP BY t_de.entry_datetime AND t_de.entry_by) r_de', [$firstDate, $lastDate]);

        if ($expenditure == []) {
            return view('Bus.reports.error');
        }

        $pdf = PDF::loadView('Bus.reports.YearlyBusEntryReport_busModulePdf', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('YearlyBusEntryReportPDF.pdf');

    }

  //========================================================================== Bus Challan Start from Below ========================

    public function exportBusChallanView()
    {


        $year = DB::select('SELECT DISTINCT YEAR(ex.create_datetime) AS year  FROM delivery_export_challan ex');

        return view('Bus.BusModuleChallanForm', compact('year'));


    }


    public function getAllBusListDataDetails(Request $req)
    {
        $getNetWeight = DB::select("SELECT * FROM delivery_export AS de WHERE de.truck_bus_flag = 0 AND de.entry_datetime = ?", [$req->export_challan_no]);

        return json_encode($getNetWeight);
    }


    public function getDetailsChallanWithMiscellaneous(Request $req)
    {

        $getNetWeight = DB::select("SELECT * FROM delivery_export_challan AS dbus WHERE dbus.truck_bus_flag = 0 AND dbus.challan_date =?", [$req->export_challan_no]);
        return json_encode($getNetWeight);
    }




    public function saveBusChallanData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y', strtotime($r->searchDate));

//        $getlastFdrSlNo = DB::select('SELECT MAX(CAST(SUBSTRING(delivery_export_challan_bus.export_challan_no,-8,3)  AS UNSIGNED)) AS challan_no   FROM delivery_export_challan_bus');
//
//        if (!is_null($getlastFdrSlNo[0]->challan_no)) {
//            $NewChallan = $getlastFdrSlNo[0]->challan_no + 1;
//        } else {
//            $NewChallan = 1;
//        }


        $dateOfYear_test = date('z', strtotime($r->searchDate));  // ok
        $dateOfYear_test = $dateOfYear_test + 1;    // ok
        $createdChallan = str_pad($dateOfYear_test, 3, '0', STR_PAD_LEFT);  // ok
        $only_year = $r->searchDate;
        $string = "/";
        $challan = $createdChallan . $string . $only_year;  // ok




//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"save : ".$challan);
//            fclose($file);
//            return;



        $entryUser = Auth::user()->username;
        $currentTime = date('Y-m-d H:i:s');
     //   $exist_challan = DB::select('SELECT de.id  FROM delivery_export_challan AS de WHERE de.truck_bus_flag = 0 AND  de.export_challan_no =?', [$r->export_challan_no]);

        $challanDate = $r->searchDate;


//        if ($exist_challan) {
//            return Response::json(['duplicate' => 'duplicate'], 209);
//        }

        $check_challan_no = DB::select("SELECT ch.id AS ch_id,ch.export_challan_no AS challan FROM delivery_export_challan AS ch WHERE ch.truck_bus_flag = 0 AND DATE(challan_date) =?", [$r->searchDate]);

        if ($check_challan_no == []) {



            $postExTruckEntry = DB::table('delivery_export_challan')
                ->insertGetId([
                    'delivery_export_challan.export_challan_no' => $challan,
//                    'delivery_export_challan_bus.delivery_export_bus_id' => $truck_list[0]->id,
                    'delivery_export_challan.delivery_export_id' => $r->delivery_export_id_bus_list,
                    'delivery_export_challan.miscellaneous_name' => $r->miscellaneous_name,
                    'delivery_export_challan.miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.create_datetime' => $currentTime,
                    'delivery_export_challan.create_by' => $entryUser,
                    'delivery_export_challan.challan_date' => $challanDate,
                    'delivery_export_challan.truck_bus_flag' => 0

                ]);

            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);

//            $file = fopen("voucher.txt","w");
//            echo fwrite($file,"save:".$typeCharge[0]->idTwoCharge);
//            fclose($file);
//            return;

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


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan]);



            //CHANGE Done
            $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
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
            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;

            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;

            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;

            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = ceil($vat);
            $grandTotal = ceil($cellVat + $totalTakaWithoutVat);
            $p = DB::TABLE('delivery_export_challan')
                ->WHERE('challan_date', $challanDate)
                ->WHERE('truck_bus_flag', 0)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);


//            $challan_array = DB::select("SELECT ch.id AS challan_id FROM delivery_export_challan AS ch WHERE ch.truck_bus_flag = 0 AND ch.export_challan_no =?", [$challan]);
//

            $t = DB::table('transactions')
                ->insert([
                    'transactions.export_challan_flag' => 1,
                    'transactions.challan_details_id' => $postExTruckEntry,
                    'transactions.credit' =>$grandTotal,
                    'transactions.sub_head_id' =>227,
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


//            $postExTruckUpdate = DB::table('delivery_export_challan_bus')
//                ->where('id', $check_challan_no[0]->ch_id)
//                ->update([
//                    'delivery_export_bus_id' => $truck_list[0]->id,
//                    'miscellaneous_name' => $r->miscellaneous_name,
//                    'miscellaneous_charge' => $r->miscellaneous_charge,
//                    'delivery_export_challan_bus.challan_date' => $challanDate
//                ]);
//            if ($postExTruckUpdate == true) {
//                return "Updated";
//            }
        }

    }



    public function updateBusChallanData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $year = date('Y', strtotime($r->searchDate));



        $challanDate = $r->searchDate;

//        $dateOfYear_test = date('z', strtotime($r->searchDate));
//        $createdChallan = str_pad($dateOfYear_test, 3, '0', STR_PAD_LEFT);
//        $only_year = date("Y");
//        $string = "/";
//        $challan = $createdChallan . $string . $only_year;


        $dateOfYear_test = date('z', strtotime($r->searchDate));
        $dateOfYear_test = $dateOfYear_test + 1;
        $createdChallan = str_pad($dateOfYear_test, 3, '0', STR_PAD_LEFT);
        $only_year = $r->searchDate;
        $string = "/";
        $challan = $createdChallan . $string . $only_year;


//            $file = fopen("Truckentry.txt","w");
//            echo fwrite($file,"update challan: ".$challan);
//            fclose($file);
//            return;



//        $truck_list = DB::select("SELECT GROUP_CONCAT(delivery_export_bus.id) AS id FROM delivery_export_bus WHERE entry_datetime =?", [$r->searchDate]);
        $entryUser = Auth::user()->username;
        $currentTime = date('Y-m-d H:i:s');
//        $exist_challan = DB::select('SELECT de.id  FROM delivery_export_challan_bus AS de WHERE de.export_challan_no=?', [$r->export_challan_no]);
//
//        if ($exist_challan) {
//            return Response::json(['duplicate' => 'duplicate'], 209);
//        }

        $check_challan_no = DB::select("SELECT ch.id AS ch_id,ch.export_challan_no AS challan FROM delivery_export_challan AS ch WHERE ch.truck_bus_flag = 0 AND DATE(challan_date)=?", [$r->searchDate]);

        if ($check_challan_no == []) {
            $postExTruckEntry = DB::table('delivery_export_challan')
                ->insert([
                    'delivery_export_challan.export_challan_no' => $challan,
                    'delivery_export_challan.delivery_export_id' => $r->delivery_export_id_list,
                    'delivery_export_challan.miscellaneous_name' => $r->miscellaneous_name,
                    'delivery_export_challan.miscellaneous_charge' => $r->miscellaneous_charge,
                    'delivery_export_challan.create_datetime' => $currentTime,
                    'delivery_export_challan.create_by' => $entryUser,
                    'delivery_export_challan.challan_date' => $challanDate
                ]);
            if ($postExTruckEntry == true) {
                return "Inserted";
            }
        } else {
            $postExTruckUpdate = DB::table('delivery_export_challan')
                ->where('id', $check_challan_no[0]->ch_id)
                ->where('truck_bus_flag', 0)
                ->update([
                    'delivery_export_id' => $r->delivery_export_id_list,
                    'miscellaneous_name' => $r->miscellaneous_name,
                    'miscellaneous_charge' => $r->miscellaneous_charge,
                    'challan_date' => $challanDate
                ]);

            //change Done
            $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =? ) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =? ) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =? ) AS idSixCharge

FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);

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


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$challan]);


            //CHANGE Done
            $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
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
            $Total_entrance_fee_all_truck = $t_first + $t_second + $t_third;
            $total_haltage_day_bd = $exportChallanData[0]->total_holtage_day;
            $haltage_charge = $holtage_charge[0]->new_haltage_charge;

            $total_haltage_charge_all_truck = $haltage_charge * $total_haltage_day_bd;
            $bebed = $exportChallanData[0]->miscellaneous_charge;

            $totalTakaWithoutVat = $total_haltage_charge_all_truck + $Total_entrance_fee_all_truck + $bebed;

            $vat = number_format((($totalTakaWithoutVat * 15) / 100), 2, '.', '');
            $cellVat = CEIL($vat);
            $grandTotal = CEIL($cellVat + $totalTakaWithoutVat);

            $p = DB::TABLE('delivery_export_challan')
                ->where('id', $check_challan_no[0]->ch_id)
                ->WHERE('truck_bus_flag', 0)
                ->UPDATE([
                    'total_amount' => $grandTotal
                ]);


            $t = DB::TABLE('transactions')
                ->WHERE('challan_details_id', $check_challan_no[0]->ch_id)
                ->WHERE('export_challan_flag', 1)
                ->UPDATE([
                    'transactions.credit' => $grandTotal,
                    'transactions.updated_at' => date('Y-m-d H:i:s'),
                    'transactions.updated_by' => Auth::user()->id
                ]);


            IF ($p == TRUE) {
                RETURN "Updated";
            }

        }

    }


    public function getChallanShowDetailsData()
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


    public function deleteBusChallanData($id)
    {
        $port_id = Session::get('PORT_ID');
        DB::table('delivery_export_challan')->where('id', $id)->delete();
        DB::table('transactions')->where('challan_details_id', $id)->where('export_challan_flag', 1)->where('port_id',$port_id)->delete();

        return 'success';
    }


    public function getAllBusChallanListData()
    {
        $getAllChallan = DB::table('delivery_export_challan')
            ->where('truck_bus_flag', 0)
            ->select(
                'delivery_export_challan.*'
            )
            ->get();
        return json_encode($getAllChallan);
    }


    public function monthWiseBusChallanReport(Request $r)
    {
//        $dates = date('Y-m-d');

        /*     $todaysEntry=  DB::table('truck_entry_regs')
              ->where('truckentry_datetime','LIKE',"%$dates%")
              ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
              ->select('truck_entry_regs.*', 'cargo_details.cargo_name')
              ->get();*/

        $todayWithTime = date('Y-m-d h:i:s a');
        $expenditure = DB::select("SELECT * FROM delivery_export_challan
WHERE truck_bus_flag = 0 AND delivery_export_challan.create_datetime  BETWEEN ? AND ? ORDER BY delivery_export_challan.id DESC", [$r->from_date_v, $r->to_date_v]);

        $total_expenditure_amount = DB::select("SELECT  SUM(delivery_export_challan.total_amount) AS total_amount
FROM delivery_export_challan 
WHERE truck_bus_flag = 0 AND delivery_export_challan.create_datetime  BETWEEN ? AND ?", [$r->from_date_v, $r->to_date_v]);
        $amount = $total_expenditure_amount[0]->total_amount;

        if ($expenditure == []) {
            return view('Bus.reports.error');
        }

        $pdf = PDF::loadView('Bus.reports.mothWiseChallanBus_busModulePDF', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'amount' => $amount,
            'from_date' => $r->from_date_v,
            'to_date' => $r->to_date_v


        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('monthWiseExportBusPDF.pdf');

    }


    public function getTodaysBusChallanReport()
    {

        $today = date('Y-m-d');
      //  dd($today);
        $challan_array = DB::select(" SELECT d.export_challan_no AS challan_no FROM delivery_export_challan AS d WHERE d.truck_bus_flag = 0 AND d.challan_date  =?",[$today]);
        if ($challan_array == []) {

            return view('Bus.reports.error');
        }
        $user_name = Auth::user()->name;
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

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);




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


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id]);




        if ($exportChallanData == []) {

            return view('Bus.reports.error');
        }

        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
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

        $pdf = PDF::loadView('Bus.reports.todaysBusChallanReportPdf_busModule', [

            'export_challan_no' => $exportChallanData[0]->export_challan_no,
            'create_datetime' => $exportChallanData[0]->create_datetime,

            'first_charge_4_55' => $first_charge[0]->first_ent,
            'first_c_truck_no' => $first_c_truck_no,
            't_first' => $t_first,

            'second_charge_21_59' => $second_charge[0]->second_ent,
            'second_c_truck_no' => $second_c_truck_no,
            't_second' => $t_second,

            'third_charge_53_92' => $third_charge[0]->third_ent,
            'third_c_truck_no' => $third_c_truck_no,
            't_third' => $t_third,


            'miscellaneous_name' => $exportChallanData[0]->miscellaneous_name,
            'miscellaneous_charge' => $exportChallanData[0]->miscellaneous_charge,

            'Total_truck_no' => $exportChallanData[0]->Total_truck_no,
            'indian_truck' => $exportChallanData[0]->indian_truck,
            'user_name' => $user_name,
            'challan_no' => $exportChallanData[0]->challan_no,

//'bd_haltage'=>$exportChallanData[0]->bd_haltage,
            'total_holtage_day' => $exportChallanData[0]->total_holtage_day,

            // 'indian_haltage'=>$exportChallanData[0]->indian_haltage,
            'haltage_charge' => $haltage_charge,
            // 'totalHaltageChargeBD'=>$totalHaltageChargeBD,
            //  'totalIndianEntryFee'=>$totalIndianEntryFee,
            // 'totalIndianHoltageCharge'=>$totalIndianHoltageCharge,
            'Total_entrance_fee_all_truck' => $Total_entrance_fee_all_truck,

            'total_haltage_charge_all_truck' => $total_haltage_charge_all_truck,

            'totalTakaWithoutVat' => $totalTakaWithoutVat,
            'cellVat' => $cellVat,
            'grandTotal' => $grandTotal


        ])->setPaper([0, 0, 651.3, 900]);/*->setPaper([0, 0, 750, 800], 'landscape');*/

        return $pdf->stream('todaysTruckEntryReportPDF.pdf');

    }


    public function dateWiseBusChallanReport(Request $r)
    {
        $requestedDate = $r->from_date_challan;  //2018-02-03
      //  dd($r->from_date_challan);

        //return $r->from_date." ".$r->to_date;
        //$todayWithTime = date('Y-m-d h:i:s a');

        $user_name = Auth::user()->name;



        $challan_array = DB::select("SELECT d.export_challan_no AS challan_no FROM delivery_export_challan AS d WHERE d.truck_bus_flag = 0 AND  d.challan_date =?",[$requestedDate]);


        $id = $challan_array[0]->challan_no;
        $year = date('Y', strtotime($requestedDate));

        // dd($id);


        //change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);




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


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id]);




        if ($exportChallanData == []) {

            return view('Bus.reports.error');
        }


        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
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

        $pdf = PDF::loadView('Bus.reports.dateWiseBusChallanReportPdf_busModule', [

            'export_challan_no' => $exportChallanData[0]->export_challan_no,
            'create_datetime' => $exportChallanData[0]->create_datetime,


            'first_charge_4_55' => $first_charge[0]->first_ent,
            'first_c_truck_no' => $first_c_truck_no,
            't_first' => $t_first,

            'second_charge_21_59' => $second_charge[0]->second_ent,
            'second_c_truck_no' => $second_c_truck_no,
            't_second' => $t_second,

            'third_charge_53_92' => $third_charge[0]->third_ent,
            'third_c_truck_no' => $third_c_truck_no,
            't_third' => $t_third,


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

            'totalTakaWithoutVat' => $totalTakaWithoutVat,
            'user_name' => $user_name,
            'challan_no' => $exportChallanData[0]->challan_no,
            'cellVat' => $cellVat,
            'grandTotal' => $grandTotal


        ])->setPaper([0, 0, 651.3, 900]);/*->setPaper([0, 0, 750, 800], 'landscape');*/

        return $pdf->stream('DateWiseBusChallanReport.pdf');


        //     $DWRDate = DB::select("SELECT * FROM delivery_export_challan WHERE DATE(create_datetime)=?",[$r->from_date_challan]);
//        if($DWRDate) {
//            $pdf = PDF::loadView('Export.dateWiseChallanReportPdf',[
//                'DWRDate'=>$DWRDate,
//                'from_date' => $r->from_date_challan
//            ])->setPaper([0, 0, 808.661, 1020.63], 'landscape');
//            return $pdf->stream('DateWiseChallanReport.pdf');
//        } else {
//            return view('posting.notFound',compact('requestedDate'));
//        }
    }




    public function getExportBusChallanReport($id_i, $year)
    {
        $id = $id_i . "/" . $year;


        $year = date('Y', strtotime($year));

      //  dd($year);
        $user_name = Auth::user()->name;

//                    $file = fopen("voucher.txt","w");
//                    echo fwrite($file,"allChallanNO:".$id);
//                    fclose($file);
//                    return;

        //change Done
        $typeCharge = DB::select("SELECT 
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 2 AND c.charges_year =?) AS idTwoCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 4 AND c.charges_year =?) AS idFourCharge,
(  SELECT c.rate_of_charges FROM handling_and_othercharges AS c WHERE  c.charge_id = 6 AND c.charges_year =?) AS idSixCharge

 FROM handling_and_othercharges  WHERE  handling_and_othercharges.charge_id = 2 AND handling_and_othercharges.charges_year = 2018",[$year,$year,$year]);


      //  dd($typeCharge);



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


FROM delivery_export_challan AS dc WHERE dc.truck_bus_flag =0 AND dc.export_challan_no=?", [$typeCharge[0]->idSixCharge,$typeCharge[0]->idFourCharge,$typeCharge[0]->idTwoCharge,$id]);


        if ($exportChallanData == []){
            return view('Bus.errorBus');
        }


       // dd($exportChallanData[0]);
//        $holtage_charge = DB::select("SELECT h.rate_of_charges AS new_haltage_charge FROM handling_and_othercharges AS h WHERE type_of_charge = 'haltage_charges'");
        //CHANGE Done
        $holtage_charge = DB::SELECT("SELECT h.rate_of_charges AS new_haltage_charge FROM
                            handling_and_othercharges AS h WHERE h.charge_id = 20 AND h.charges_year =?",[$year]);

        $first_c_truck_no = $exportChallanData[0]->first_entrance_t_no;
        $second_c_truck_no = $exportChallanData[0]->second_entrance_t_no;
        $third_c_truck_no = $exportChallanData[0]->third_entrance_t_no;

//        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.rate_of_charges = '4.55'");
//
//        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.rate_of_charges = '21.59'");
//
//        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent FROM handling_and_othercharges AS hc WHERE hc.rate_of_charges = '53.92'");


        //change Done
        $first_charge = DB::select("SELECT hc.rate_of_charges AS first_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=6 AND hc.charges_year=?",[$year]);
        $second_charge = DB::select("SELECT hc.rate_of_charges AS second_ent FROM handling_and_othercharges AS hc WHERE hc.charge_id=4 AND hc.charges_year=?",[$year]);
        $third_charge = DB::select("SELECT hc.rate_of_charges AS third_ent  FROM handling_and_othercharges AS hc WHERE hc.charge_id=2 AND hc.charges_year=?",[$year]);

//        dd($third_charge);
//        return;


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

        // dd($grandTotal);


        //  $postExTruckUpdate =

//        DB::table('delivery_export_challan_bus')
//            ->where('id', $id)
//            ->update([
//
//                'total_amount' => $grandTotal
//
//            ]);


        $pdf = PDF::loadView('Bus.reports.ExportBusChallanPDF_busModule', [

            'export_challan_no' => $exportChallanData[0]->export_challan_no,
            'create_datetime' => $exportChallanData[0]->create_datetime,

            'first_charge_4_55' => $first_charge[0]->first_ent,
            'first_c_truck_no' => $first_c_truck_no,
            't_first' => $t_first,

            'second_charge_21_59' => $second_charge[0]->second_ent,
            'second_c_truck_no' => $second_c_truck_no,
            't_second' => $t_second,

            'third_charge_53_92' => $third_charge[0]->third_ent,
            'third_c_truck_no' => $third_c_truck_no,
            't_third' => $t_third,


            'miscellaneous_name' => $exportChallanData[0]->miscellaneous_name,
            'miscellaneous_charge' => $exportChallanData[0]->miscellaneous_charge,

            'Total_truck_no' => $exportChallanData[0]->Total_truck_no,
            'indian_truck' => $exportChallanData[0]->indian_truck,
            'challan_no' => $exportChallanData[0]->challan_no,
//'bd_haltage'=>$exportChallanData[0]->bd_haltage,
            'total_holtage_day' => $exportChallanData[0]->total_holtage_day,

            // 'indian_haltage'=>$exportChallanData[0]->indian_haltage,
            'haltage_charge' => $haltage_charge,
            // 'totalHaltageChargeBD'=>$totalHaltageChargeBD,
            //  'totalIndianEntryFee'=>$totalIndianEntryFee,
            // 'totalIndianHoltageCharge'=>$totalIndianHoltageCharge,
            'Total_entrance_fee_all_truck' => $Total_entrance_fee_all_truck,

            'total_haltage_charge_all_truck' => $total_haltage_charge_all_truck,

            'totalTakaWithoutVat' => $totalTakaWithoutVat,
            'cellVat' => $cellVat,
            'user_name' => $user_name,
            'grandTotal' => $grandTotal


        ])->setPaper([0, 0, 651.3, 900]);
//        ->setPaper([0, 0, 750, 800], 'landscape');
//        ->setPaper([0, 0, 651.3,900]);
        return $pdf->stream('ExportBusChallanPdf.pdf');
    }




    public function yearlyBusChallanReport(Request $r)
    {

        $nextYear = $r->year + 1;
        $todayWithTime = date('Y-m-d h:i:s a');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        //return $firstDate. " " . $lastDate;
        $expenditure = DB::select('SELECT r_de.July,r_de.August,r_de.September,r_de.October,r_de.November,r_de.December,r_de.January,r_de.February,r_de.March,r_de.April,r_de.May,r_de.June,
(r_de.January+r_de.February+r_de.March+r_de.April+r_de.May+r_de.June+r_de.July+r_de.August+r_de.September+r_de.October+r_de.November+r_de.December) AS  Total
FROM(

SELECT t_de.create_datetime,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND   MONTH(create_datetime)=7),0) AS July,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=8),0) AS August,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=9),0) AS September,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=10),0) AS October,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=11),0) AS November,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=12),0) AS December,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=1),0) AS January,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=2),0) AS February,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=3),0) AS March,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=4),0) AS April,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=5),0) AS May,
IFNULL((SELECT SUM(total_amount) AS amount FROM delivery_export_challan WHERE truck_bus_flag = 0 AND  MONTH(create_datetime)=6),0) AS June

FROM  
  (
  SELECT create_datetime,create_by FROM delivery_export_challan de
 WHERE truck_bus_flag = 0 AND  DATE(create_datetime) BETWEEN ? AND ?)  t_de
 
 
  GROUP BY t_de.create_by AND t_de.create_datetime) r_de', [$firstDate, $lastDate]);

        if ($expenditure == []) {
            return view('Bus.reports.error');
        }

        $pdf = PDF::loadView('Bus.reports.YearlyBusChallanPDFReport_busModule', [
            'todayWithTime' => $todayWithTime,
            'expenditure' => $expenditure,
            'year' => $r->year

        ])->setPaper([0, 0, 800.661, 800.63], 'landscape');


        return $pdf->stream('YearlyBusChallanPDFReport.pdf');

    }






}
