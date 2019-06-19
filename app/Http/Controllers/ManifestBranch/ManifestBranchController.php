<?php

namespace App\Http\Controllers\ManifestBranch;
use App\Models\Warehouse\ShedYard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use PDF;
use Response;
use App\Models\Warehouse\YardDetail;

class ManifestBranchController extends Controller
{

    public function welcome() {
//        return view('posting.welcome');
        $name = Auth::user()->name;
        $port_id = Session::get('PORT_ID');
        $currentDate=date('Y-m-d');

        $totalManifestPosting = DB::select("SELECT COUNT(*) AS total_posting_done, CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest
FROM manifests WHERE manifests.gweight  IS NOT NULL AND manifests.port_id=? ORDER BY justManifest DESC",[$port_id]);

        $totalTruckReceive = DB::select('SELECT  COUNT(*) AS total_truck_receive FROM manifests
JOIN truck_entry_regs AS tr ON tr.manf_id = manifests.id
JOIN shed_yard_weights AS sw ON sw.truck_id = tr.id
WHERE sw.port_id=?',[$port_id]);
        $todaysManifestTruckOutTotal=DB::select('SELECT COUNT(manifests.id) total_Truck_entry  FROM manifests JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
        WHERE DATE(manifest_created_time)=? AND manifests.port_id =?',[$currentDate,$port_id]);
        //  $upcomingTruckTotal=DB::select('SELECT COUNT(id) total_upcoming_truck FROM truck_entry_regs WHERE truckentry_datetime IS NOT NULL AND wbridg_user1 IS NULL AND weightment_flag=1');

        $receiveNotDone =DB::select("SELECT  COUNT(*) AS posting_done_but_receive_not_done FROM manifests
JOIN truck_entry_regs AS tr ON tr.manf_id = manifests.id
LEFT JOIN shed_yard_weights AS sw ON sw.truck_id = tr.id
WHERE sw.unload_receive_datetime IS NULL AND manifests.gweight  IS NOT NULL AND tr.port_id =? ",[$port_id]);

        $total_delivery_menifest =DB::select('SELECT COUNT(*) AS total_delivery_menifest FROM manifests
JOIN delivery_requisitions AS dr ON dr.manifest_id = manifests.id
WHERE dr.port_id=?',[$port_id]);



        return view('default.manifest-branch.welcome',compact('totalManifestPosting','totalTruckReceive','receiveNotDone','total_delivery_menifest'));
    }

    public function manifestBranchView() {
        $port_id = Session::get('PORT_ID');
        $shed_yards = ShedYard::where('port_id',$port_id)->get();
      // dd($shed_yards);
        //dd(Auth::user()->role->id);
        return view('default.manifest-branch.manifest-branch-monitor', ['shed_yards' => $shed_yards]);
    }




    public function shedYardSelectedName(Request $req)
    {
        $port_id = Session::get('PORT_ID');

        $data = DB::select("SELECT yard_details.id,yard_details.yard_shed_name  FROM yard_details 
JOIN shed_yards ON shed_yards.id = yard_details.shed_yard_id
WHERE shed_yards.id=? AND yard_details.port_id=? AND shed_yards.port_id=?", [$req->shed_yard,$port_id,$port_id]);
        return json_encode($data);
    }


    public function getShedYardWiseManifestDetails($date, $shed_yard, $shed_yard_type) {
                $port_id = Session::get('PORT_ID');
                $data = DB::select("SELECT cargo_details.cargo_name,TIME(sw.unload_receive_datetime) AS receive_time, DATE_FORMAT(DATE(m.manifest_date), '%d-%m-%Y') AS manifest_date,
sw.unload_yard_shed,sw.unload_receive_datetime,
 m.manifest,m.goods_id,m.vatreg_id,vatregs.NAME, yard_details.yard_shed_name, sw.*,tr.*
FROM shed_yard_weights AS sw 
JOIN yard_details ON yard_details.id = sw.unload_yard_shed
JOIN truck_entry_regs AS tr ON tr.id = sw.truck_id
JOIN manifests AS m ON m.id = tr.manf_id
JOIN vatregs ON vatregs.id = m.vatreg_id
JOIN cargo_details ON FIND_IN_SET(cargo_details.id, m.goods_id) > 0
WHERE sw.unload_yard_shed=? AND DATE(sw.unload_receive_datetime)=? AND sw.port_id=?", [$shed_yard_type,$date,$port_id]);
                return json_encode($data);

    }

    public function dateShedYardWiseManifestDetailsReport(Request $r) {

      //  dd($r->date.' '.$r->shed_yard.' '.$r->shed_yard_type);
        $str = 'In My Cart : 11 items';
        $int_shed_yard = (int) filter_var($r->shed_yard_type, FILTER_SANITIZE_NUMBER_INT);
//        return;
        $port_id = Session::get('PORT_ID');
       // dd($port_id);
//        $arrayShedYardId = array();
//        foreach (Auth::user()->shedYards as $k => $v) {
//
//            $arrayShedYardId[] = $v->id;
//        }
//        $yard_details_array = YardDetail::whereIn('shed_yard_id',$arrayShedYardId )->where('port_id', $port_id)->get();
      //  $array_name = array();
//        foreach ($yard_details_array  as $k => $v){
//            $array_name[]  = $v->id;
//        }
//        $shedYard = implode(',', $array_name);

//        $flagValue = $r->vehile_type_flage_pdf;
        $date = date('Y-m-d');

//        if ($r->date) {
//            $date = $r->date;
//        }
        $receive_date = $r->date;
        $todayWithTime = date('Y-m-d h:i:s a');

        //dd(Auth::user()->role->id);


            $todaysWareHouseEntry = DB::select("SELECT cargo_details.cargo_name,DATE_FORMAT(DATE(m.manifest_date), '%d-%m-%Y') AS manifest_date,
sw.unload_yard_shed,sw.unload_receive_datetime,
TIME(sw.unload_receive_datetime) AS receive_time, DATE(sw.unload_receive_datetime) AS receive_date,
 m.manifest,m.goods_id,m.vatreg_id,vatregs.NAME, yard_details.yard_shed_name, sw.*,tr.*
FROM shed_yard_weights AS sw 
JOIN yard_details ON yard_details.id = sw.unload_yard_shed
JOIN truck_entry_regs AS tr ON tr.id = sw.truck_id
JOIN manifests AS m ON m.id = tr.manf_id
JOIN vatregs ON vatregs.id = m.vatreg_id
JOIN cargo_details ON FIND_IN_SET(cargo_details.id, m.goods_id) > 0
WHERE sw.unload_yard_shed=? AND DATE(sw.unload_receive_datetime)=? AND sw.port_id=?", [$int_shed_yard,$r->date,$port_id]);




//        if ($flagValue == 1) {
//            $typeOfReports = 'Received Goods Report';
//        } elseif ($flagValue == 2) {
//            $typeOfReports = 'Received Chassis(Chassis On Truck) Report';
//        } elseif ($flagValue == 3) {
//            $typeOfReports = 'Received Trucktor(Trucktor On Truck) Report';
//        } elseif ($flagValue == 11) {
//            $typeOfReports = 'Received Chassis(Self) Report';
//        } elseif ($flagValue == 12) {
//            $typeOfReports = 'Received Trucktor(Self) Report';
//        } elseif ($flagValue == 13) {
//            $typeOfReports = 'Received Bus Report';
//        } elseif ($flagValue == 14) {
//            $typeOfReports = 'Received Three Wheller Report';
//        } elseif ($flagValue == 15) {
//            $typeOfReports = 'Received Rickshaw Report';
//        } elseif ($flagValue == 16) {
//            $typeOfReports = 'Received Car(Self) Report';
//        } elseif ($flagValue == 17) {
//            $typeOfReports = 'Received Pick Up(Self) Report';
//        } else {
//            $typeOfReports = 'Received Report';
//        }


        if(count($todaysWareHouseEntry)) {

            $pdf = PDF::loadView('default.manifest-branch.reports.date-shed-yard-wise-manifest-details-report',
                [
                    'todaysWareHouseEntry' => $todaysWareHouseEntry,
                    'todayWithTime' => $todayWithTime,
                    'receive_date' => $receive_date,
//                    'typeOfReports' => $typeOfReports,
                    'date' => $date
                ])/*->setPaper('a4', 'landscape');*/
            ->setPaper([0, 0, 900, 900]);
            //return $pdf->download('user.pdf');
            return $pdf->stream($date . '-date-shed-yard-wise-report.pdf');
        } else {
            return view('default.warehouse.not-found',['requestedDate' => $date]);
        }
    }

}
