<?php

namespace App\Http\Controllers\Truck;


use App\User;
use PDF;
use Session;
use App\Role;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use Exception;
use Response;
use Log;
use App\Http\Controllers\Base\ProjectBaseController;
use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;

class TruckController extends ProjectBaseController {
    public function welcome() {
        $view_title = 'Welcome To Truck Panel';
        $currentDate = date('Y-m-d');

        $port_id = Session::get('PORT_ID');
        $todaysTruckTotal = DB::select('SELECT COUNT(tr.id) total_truck_entry  
                    FROM truck_entry_regs AS tr
                    JOIN manifests AS m ON tr.manf_id=m.id
                    WHERE DATE(tr.truckentry_datetime)=?  AND 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                    NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.vehicle_type_flag
                     BETWEEN "1" AND "10" AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);

        $todaysTotalChassisSelf = DB::select('SELECT COUNT(*) AS totalChassisSelf  
                        FROM truck_entry_regs AS tr
                        JOIN manifests AS m ON tr.manf_id=m.id
                        WHERE DATE(tr.truckentry_datetime)=?  AND 
                        SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                        NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.vehicle_type_flag BETWEEN "11" AND "20" AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);

        $todaysManifestTotal = DB::select('SELECT COUNT(DISTINCT tr.manf_id) total_manifest 
                        FROM truck_entry_regs AS tr
                        JOIN manifests AS m ON tr.manf_id=m.id
                        WHERE DATE(tr.truckentry_datetime)=?  AND 
                        SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                          NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);


        $todaysExitTruckTotal = DB::select('SELECT COUNT(id) AS  total_truck_exit 
                        FROM truck_entry_regs AS tr WHERE DATE(tr.out_date)=? 
                        AND tr.vehicle_type_flag BETWEEN "1" AND "10" AND tr.port_id=?', [$currentDate, $port_id]);

        $view = 'default.truck.welcome';
        return view($view, compact('view_title', 'todaysTruckTotal', 'todaysTotalChassisSelf', 'todaysManifestTotal', 'todaysExitTruckTotal'));
    }




    public function truckEntryForm() {
        $yard = DB::table('yard_details')
            ->where('yard_details.shed_yard_id', 44)
            ->select(
                'yard_details.id',
                'yard_details.yard_shed_name')
            ->get();
        $countryList=DB::table('countrys')->get();
//        $truck_serial = $this->truckEntryReg->entry_sl;
//        dd($truck_serial);



          /*  foreach($countryList as  $value){

                dd($value);

            }*/





        return view('default.truck.truck-entry-form',['yards'=>$yard,'countryList'=>$countryList]);
    }

    public function selfEntryForm()
    {

        $yard = DB::table('yard_details')
            ->where('yard_details.shed_yard_id', 44)
            ->select(
                'yard_details.id',
                'yard_details.yard_shed_name')
            ->get();

        // dd($yard);

        return view('default.truck.self-entry-form', ['yards' => $yard]);
    }


    public function otherReport()
    {
        return view('default.truck.other-reports-view');
    }

//This method is for testing
    public function TruckEntryFormAll()
    {
        $current_date = date('Y-m-d');
        $m = '2521/4/2018';
        // dd($m);
        $manifest = DB::table('manifests')
            ->where('manifest', $m)
            ->get();
        //$last_entry= $this->getLastTruckEntrySerialForTheDate($current_date);

        $portId = DB::table('users')->where('username', Auth::user()->username)->first();

        if ($manifest == '[]') { //This is for new manifest
            //check 947 from 947/4 is exist or not. if 947/2 exist, 947/3 can't be inserted.
            $split_manifest_no = explode('/', $m, 3); //258/2 or 258/ A |return 2 or A


            $checkManifest = DB::select('SELECT * FROM (
                SELECT SUBSTRING_INDEX(m.manifest,"/",1) ass FROM manifests m 
          ) AS f  WHERE ass=?', [$split_manifest_no[0]]);

            //Check Year Also
            $checkYear = DB::select('SELECT * FROM (
                SELECT SUBSTRING_INDEX(m.manifest,"/",-1) ass FROM manifests m 
          ) AS f  WHERE ass=?', [$split_manifest_no[2]]);


            $get_existing_manifest = DB::select('SELECT * FROM 
                                                (SELECT m.manifest, SUBSTRING_INDEX(m.manifest,"/",1) m_no ,SUBSTRING_INDEX(m.manifest,"/",-1) m_year FROM manifests m  ) AS f  
                                                 WHERE  f.m_no=? AND f.m_year=?', [$split_manifest_no[0], $split_manifest_no[2]]);
            dd($get_existing_manifest != []);

            if ($get_existing_manifest != [])//duplicate manifest 947 found
            {

                dd($get_existing_manifest);
                //return Response::json(['exist_manifest' => $get_existing_manifest], 206);
            }


        }
    }


    //return all truck for a manifest id


    public function getForeignTrucksDetailsData($id){
        $data=DB::select('SELECT tr.*,
                        (SELECT shed_yard_weights.unload_receive_datetime 
                        FROM shed_yard_weights
                        WHERE shed_yard_weights.truck_id = tr.id ORDER BY shed_yard_weights.unload_receive_datetime ASC LIMIT 1) AS unload_receive_datetime
                 FROM truck_entry_regs AS tr 
                JOIN manifests AS m ON m.id=tr.manf_id
                WHERE m.id=?', [$id]);

        return json_encode($data);
    }

    public function getForeignTrucks($id){
        $data=DB::select('SELECT tr.* FROM truck_entry_regs AS tr 
                JOIN manifests AS m ON m.id=tr.manf_id
                WHERE m.id=?',[$id]);

        return json_encode($data);
    }


    public function getGoodsDetails($g)
    {
        $param = $g . '%';
        $goods = DB::select('SELECT * FROM cargo_details AS c WHERE c.cargo_name LIKE ?', [$param]);
      //  return $goods;
        return json_encode($goods);
    }


    public function getGoodsIdForTags($manifest, $truck, $year)
    {
        $manifest = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;

        $goods = DB::select('SELECT  b.id,
                b.cargo_name
                FROM manifests a
                INNER JOIN cargo_details b
                ON FIND_IN_SET(b.id, a.goods_id) > 0
                WHERE a.manifest=?', [$manifest]);

        return json_encode($goods);
    }


    public function inCompleteManifestListReport()
    {
        $currentTime = date('Y-m-d H:i:s');
        $port_id = Session::get('PORT_ID');
        $InCompleteManifest = DB::select("SELECT Manifest_No, TotalForeignTruck,TruckInManifest,(TruckInManifest-TotalForeignTruck) 
                AS remaining
                FROM(
                SELECT m.manifest AS Manifest_No,
                SUBSTRING_INDEX(SUBSTRING_INDEX(m.manifest,'/',2),'/',-1) AS TruckInManifest,
                (SELECT COUNT(truck_entry_regs.id) FROM  truck_entry_regs 
                WHERE truck_entry_regs.manf_id=m.id AND DATE(truck_entry_regs.truckentry_datetime) = CURDATE() AND truck_entry_regs.port_id=?) AS TotalForeignTruck
                FROM manifests m
                WHERE m.port_id=?
                ) t
                WHERE TotalForeignTruck != TruckInManifest 
                AND TruckInManifest NOT REGEXP '^[A]{1}|([A-Z]{1}[\-]{1}[B-Z]{1})$' AND TotalForeignTruck > 0",[$port_id, $port_id]);

        if ($InCompleteManifest) {
            $pdf = PDF::loadView('default.truck.reports.incomplete-manifest-list-report',
                ['inCompleteManifestdata' => $InCompleteManifest,
                    'date' => $currentTime,
                ])->setPaper([0, 0, 651.3, 900]);

            return $pdf->stream('incomplete-manifest-list-report'.$currentTime.'.pdf');
        } else {
            return view('default.truck.not-found', ['noIncompleteManifest' => "There is no InComplete Manifest Found!"]);
        }
    }

    public function checkManifestFoundInNewArray($maifest_value, $manifest_array)
    {
        if (!empty($manifest_array)) {
            foreach ($manifest_array as $key => $newArray) {

                if ($newArray->manifes_no == $maifest_value->manifes_no) {

                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function getNewArrayManifestLastIndex($maifest_value, $manifest_array)
    {
        $index = 0;
        foreach ($manifest_array as $key => $newArray) {

            if ($maifest_value->manifes_no == $newArray->manifes_no) {

                $index = $key;
            } else {
                continue;
            }
        }
        return $index;
    }


    

    public function TruckEntryRegJson()
    {
        $result = $this->getLastTruckEntrySerialForTheDate('9000/3/20185', '2018-01-21');


        return dd($result);
    }

    public function TruckEntryYardJson()
    {

        $yard = DB::table('yard_details')
            ->select(
                'yard_details.id',
                'yard_details.yard_shed_name'
            )
            ->get();


        return json_encode($yard);
    }

    public function TruckModulecountCurrentDateYardNo(Request $req)
    {


//        $file = fopen("Truckentry.txt","w");
//              echo fwrite($file,"Faysal".$req->yard_no);
//              fclose($file);
//        return;

        $today = date('Y-m-d');


        $countYardNO = DB::select("SELECT COUNT(m.posted_yard_shed)+1 AS yard_level_no
           FROM manifests AS m WHERE DATE(m.manifest_created_time)='$today' AND m.posted_yard_shed=?", [$req->yard_no]);
        return json_encode($countYardNO);


    }

    private function saveGoodsAndGetIds($req) {
        $goods_id = $req->goods_id;
        $ids = array();
        if($req->new_goods) {
            //check if new goods name exist
            $exist_goods = array();
            foreach($req->new_goods as $good) {
                $good_exist = DB::select('SELECT c.id  FROM cargo_details c WHERE c.cargo_name=?', [$good]);
                if ($good_exist != []) {//exist
                    array_push($exist_goods, $good);
                } else {
                    continue;
                }
            }

            if($exist_goods) {
                return Response::json(['error' => 'New Goods Already Exist!'], 203);
            }
            //insert new goods name
            foreach ($req->new_goods as $good) {
                $id = DB::table('cargo_details')->insertGetId([
                    'cargo_name' => $good
                ]);
                array_push($ids, $id);
            }

            if ($req->goods_id) {
                $goods_id = $goods_id . ',' . implode(',', $ids);
            } else {
                $goods_id = implode(',', $ids);
            }
        }
        return $goods_id;        
    } 


    private function saveSelfDetails($req, $manifest_id, $truck_id) {

        $port_id = Session::get('PORT_ID');
        $current_datetime = date('Y-m-d H:i:s');
        $user_id = Auth::user()->id;

        $save_chasis = DB::table('chassis_details')
                        ->insert([
                            'manifest_id' => $manifest_id,
                            'truck_id' => $truck_id,
                            'chassis_type' => $req->truck_type,
                            'chassis_no' => $req->truck_no,
                            'port_id' => $port_id,
                            'created_by' => $user_id,
                            'created_at' => $current_datetime
                        ]);

        $save_weights = DB::table('shed_yard_weights')
                        ->insert([
                            'truck_id' => $truck_id,
                            'unload_labor_package' => 0,
                            'unload_labor_weight' => 0,
                            'unload_equipment_package' => 0,
                            'unload_equip_weight' => 0,
                            'unload_equip_name' => 0,
                            'unload_yard_shed' => $req->t_posted_yard_shed,
                            'unload_shifting_flag' => 0,
                            'unload_receive_datetime' => $current_datetime,
                            'port_id' => $port_id,
                            'created_at' => $current_datetime,
                            'created_by' => $user_id
                        ]);
        if($save_chasis == true && $save_weights == true) {
            return true;
        } else {
            return false;
        }
    }

    public function dateWiseTruckExitReport(Request $r)
    {
        $dates = $r->date;//date('Y-m-d');

        $currentTime = date('Y-m-d H:i:s');

        //  dd($dates);


        /*$todaysEntry = DB::table('truck_entry_regs AS t')
            ->where('t.out_date', 'LIKE', "%$dates%")
            ->join('manifests AS m', 't.manf_id', '=', 'm.id')
            ->join('users','t.out_by','=', 'users.id')
            ->join('roles','roles.id','=','users.role_id')
            ->select('t.id', 't.truck_no','users.name', 't.truck_type', 't.driver_card', 't.truckentry_datetime', 't.out_date', DB::raw('(SELECT yard_details.yard_shed_name FROM yard_details WHERE yard_details.id = m.posted_yard_shed) AS posted_yard_shed'), 't.out_comment', 't.created_by', 'm.manifest')
            ->get();*/

        $todaysEntry = DB::select("SELECT t.truck_no,t.truck_type,t.id AS truck_entry_sl,m.manifest, u.name,t.driver_card,t.driver_name,t.truckentry_datetime,t.out_date,
                (SELECT GROUP_CONCAT(DISTINCT shed_yards.shed_yard) FROM shed_yards WHERE FIND_IN_SET(shed_yards.id, m.posted_yard_shed) > 0) AS posted_yard_shed
                FROM truck_entry_regs AS t      
                JOIN users AS u ON t.out_by=u.id   
                JOIN manifests AS m ON m.id=t.manf_id
                WHERE DATE(t.out_date)=?", [$dates]);


        $todaysTotalCount = DB::table('truck_entry_regs')
            ->where('out_date', 'LIKE', "%$dates%")
            ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
            ->join('users', 'truck_entry_regs.out_by', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('truck_entry_regs.*', 'cargo_details.cargo_name', 'users.name')
            ->get()->count();
//dd($todaysEntry);
//return;
        $pdf = PDF::loadView('default.truck.reports.truck-exit-report', ['manifestdata' => $todaysEntry, 'date' => $currentTime, 'todaysTotalCount' => $todaysTotalCount])->setPaper([0, 0, 850, 900]);

        return $pdf->stream('todaysTruckExitReport.pdf');

    }

    public function dateWiseTruckReportPdf(Request $r)
    {
        $dates = $r->date;
        $currentTime = date('Y-m-d H:i:s');
        $roleId = Auth::user()->role->id;


        $flagValue = $r->vehile_type_flage_pdf;

        if ($roleId == 4 || $roleId == 22 || $roleId == 21 || $roleId == 23 || $roleId == 6) {//Truck and Export
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.vehicle_type_flag, truck_entry_regs.truck_type,truck_entry_regs.created_by,truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime 
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND vehicle_type_flag = ? AND m.transshipment_flag=0 AND( r.id=4 OR r.id=22)
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $flagValue]);//->toArray();

        } else if ($roleId == 7 || $roleId == 8) {//posting and warehouse

            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime 
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND m.transshipment_flag=0 AND( r.id=7 OR r.id=8)
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates]);//->toArray();


        } //        justManifest DESC  change in posting query  12=transhipment
        else if ($roleId == 12) {


            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime 
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                         WHERE DATE(truckentry_datetime)=? AND m.transshipment_flag=1 
                         AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates]);//->toArray();

            //   dd($date_wise);
        } else if ($roleId == 5) {//CnF
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime 
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN roles AS r ON r.id=u.role_id
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                         WHERE DATE(truckentry_datetime)=? AND(r.id=5)
                         AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates]);
        } else {// this is kept for super admin
            $date_wise = DB::select("SELECT u.name AS entryBy,CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX((SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id),'/',1)) AS UNSIGNED) AS justManifest,
                        (SELECT manifest FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS manifes_no,
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id) AS total_truck,
                        (SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id AND DATE(tr.truckentry_datetime)=?) AS total_truck_entered,
                        (
                        (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1) FROM manifests WHERE manifests.id=truck_entry_regs.manf_id)-(SELECT COUNT(id) FROM truck_entry_regs AS tr WHERE tr.manf_id=truck_entry_regs.manf_id)
                        ) AS remaining_truck,
                        (SELECT cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS cargo_name,
                        truck_entry_regs.truck_no,truck_entry_regs.truck_type,truck_entry_regs.created_by,truck_entry_regs.driver_card,truck_entry_regs.truckentry_datetime 
                        FROM truck_entry_regs 
                        JOIN users AS u ON truck_entry_regs.created_by=u.id 
                        JOIN manifests AS m ON m.id=truck_entry_regs.manf_id
                        WHERE DATE(truckentry_datetime)=? AND vehicle_type_flag = ?
                        AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,'/',2),'/',-1)  NOT REGEXP '^([B-Z]{1}[\-]{1}[B-Z]{1})$'
                        ORDER BY truckentry_datetime  ASC", [$dates, $dates, $flagValue]);
        }


//        if ($flagValue == 4){
//            $head_name = "Chassis(Self) Entry Report";
//        }
        // dd($date_wise);
        //below code for balnk seria in manifest report
        $manifest_array = array();
        $date_wiseEntry = (array)$date_wise;
        if ($date_wise) {
            foreach ($date_wiseEntry as $key => $maifest_value) {

                if ($this->checkManifestFoundInNewArray($maifest_value, $manifest_array)) {
                    $newArrayIndex = $this->getNewArrayManifestLastIndex($maifest_value, $manifest_array);
                    array_splice($manifest_array, $newArrayIndex + 1, 0, array($maifest_value));
                } else {
                    $manifest_array[] = $maifest_value;

                }
            }
        }

        if ($date_wise) {
            $pdf = PDF::loadView('default.truck.reports.truck-entry-report', [
                'data' => $manifest_array,
                'requestedDate' => $r->date,
                'flagValue' => $flagValue,
//                'head_name' => $head_name,
                'date' => $currentTime
            ])->setPaper([0, 0, 780, 940]);
            return $pdf->stream('TruckEntryReport.pdf');
        } else {
            return view('default.truck.not-found', ['requestedDate' => $r->date]);
        }

    }


    public function saveTruckEntryData(Request $req) {   
        $port_id = Session::get('PORT_ID');
        $current_date = date('Y-m-d');
        $current_datetime = date('Y-m-d H:i:s');
        $user_id = Auth::user()->id;

        $split_manifest_no = explode('/', $req->manifest, 3); //258/2 or 258/ A |return 2 or A
        $existManifest = $this->manifest->whereManifest($req->manifest)->wherePortId($port_id)->first();

        $goods_id = $this->saveGoodsAndGetIds($req);

        if(empty($existManifest)) { //This is for new manifest
            //check 947 from 947/4 is exist or not. if 947/2 exist, 947/3 can't be inserted.
            $get_existing_manifest = DB::select('SELECT * FROM 
                                                (SELECT m.manifest,m.port_id, SUBSTRING_INDEX(m.manifest,"/",1) m_no ,SUBSTRING_INDEX(m.manifest,"/",-1) m_year FROM manifests m  ) AS f  
                                                 WHERE  f.m_no=? AND f.m_year=? AND f.port_id=?', [$split_manifest_no[0], $split_manifest_no[2],$port_id]);

            if(count($get_existing_manifest) != 0) { //duplicate manifest 947/?/2017 found
                return Response::json(['error' => 'Manifest No. '.$get_existing_manifest[0]->manifest.' Is Already Present!'], 203);
            }

            if(isset($this->manifest->ownFields)) {
                foreach($this->manifest->ownFields as $ownField) {
                    if(isset($req->{$ownField})) {
                        $this->manifest->{$ownField} = $req->{$ownField};
                    }
                }

                $this->manifest->goods_id = $goods_id;
                $this->manifest->created_by = $user_id;
                $this->manifest->created_at = $current_datetime;
                $this->manifest->port_id = $port_id;
            }

            if(!empty($this->manifest)) {
                $save_manifest = $this->manifest->save();
            } else {
               return Response::json(['error' => 'Manifest Info Not Found'], 203);
            }

            if($save_manifest == true) {
                if(isset($this->truckEntryReg->ownFields)) {
                    foreach ($this->truckEntryReg->ownFields as $ownField) {
                        if(isset($req->{$ownField})) {
                            $this->truckEntryReg->{$ownField} = $req->{$ownField};
                        }
                    }
                    $this->truckEntryReg->entry_sl = $this->truckEntryReg->getTruckSerial($req->manifest, $current_date);
                    $this->truckEntryReg->goods_id = $goods_id;
                    $this->truckEntryReg->manf_id = $this->manifest->id;
                    $this->truckEntryReg->truckentry_datetime = Auth::user()->role->id == 5 || Auth::user()->role->id == 11 ? $req->truckentry_datetime : $current_datetime;
                    $this->truckEntryReg->created_by = $user_id;
                    $this->truckEntryReg->created_at = $current_datetime;
                    $this->truckEntryReg->port_id = $port_id;
                }

                if(!empty($this->truckEntryReg)) {
                    $save_truck = $this->truckEntryReg->save();
                } else {
                    return Response::json(['error' => 'Truck Info Not Found'], 203);
                }
            }

            //For Manifest 876/A-E/2017 Start From 876/B-E/2017 -------------------------------
            if(preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1]) && $req->vehicle_type_flag <= 10) {
                $getChar = explode('-', $split_manifest_no[1], 2);
                $firstChar = ord($getChar[0]);
                $lastChar = ord($getChar[1]);

                $truck_serial = $this->truckEntryReg->entry_sl;

                for ($i = $firstChar + 1; $i <= $lastChar; $i++) { // Insert From 876/B-E/2017 
                    $theManifest = new Manifest;
                    $theTruckEntryReg = new TruckEntryReg;

                    $newManifestNo = $split_manifest_no[0] . "/" . chr($i) . "-" . chr($lastChar) . "/" . $split_manifest_no[2];

                    if(isset($theManifest->ownFields)) {
                        foreach($theManifest->ownFields as $ownField) {
                            if(isset($req->{$ownField})) {
                                $theManifest->{$ownField} = $req->{$ownField};
                            }
                        }
                        $theManifest->manifest = $newManifestNo;
                        $theManifest->goods_id = $goods_id;
                        $theManifest->created_by = $user_id;
                        $theManifest->created_at = $current_datetime;
                        $theManifest->port_id = $port_id;
                    }

                    if(!empty($theManifest)) {
                        $save_manifest = $theManifest->save();
                    } else {
                       return Response::json(['error' => 'Manifest Info Not Found'], 203);
                    }

                    if($save_manifest == true) {
                        if(isset($theTruckEntryReg->ownFields)) {
                            foreach ($theTruckEntryReg->ownFields as $ownField) {
                                if(isset($req->{$ownField})) {
                                    $theTruckEntryReg->{$ownField} = $req->{$ownField};
                                }
                            }
                            $theTruckEntryReg->entry_sl = $truck_serial;
                            $theTruckEntryReg->goods_id = $goods_id;
                            $theTruckEntryReg->manf_id = $theManifest->id;
                            $theTruckEntryReg->truckentry_datetime = Auth::user()->role->name == 5 ? $req->truckentry_datetime : $current_datetime;
                            $theTruckEntryReg->created_by = $user_id;
                            $theTruckEntryReg->created_at = $current_datetime;
                            $theTruckEntryReg->port_id = $port_id;
                        }

                        if(!empty($theTruckEntryReg)) {
                            $save_truck = $theTruckEntryReg->save();
                        } else {
                            return Response::json(['error' => 'Truck Info Not Found'], 203);
                        }
                    }
                }
            }
            //self entered transport
            if($req->vehicle_type_flag >= 11 && !preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $save_self_details = $this->saveSelfDetails($req, $this->manifest->id, $this->truckEntryReg->id);
            }

            if($save_manifest == true && $save_truck == true) {
                return Response::json(['success' => 'Saved Successfully'], 200);
            } else {
               return Response::json(['error' => 'Something Went Wrong!'], 402); 
            }
        } else {    //====OLD Manifest->second truck //This is for old manifest

            $truck_count = $existManifest->trucks->count();


            if($truck_count == $split_manifest_no[1] || preg_match("/^[A-Za-z-]$/", $split_manifest_no[1]) || preg_match("/^[A-Z][\-][B-Z]$/", $split_manifest_no[1])) {
                return Response::json(['error' => 'Manifest Is Full!'], 402);
            }

            $existManifest->goods_id = $goods_id;
            $update_manifest = $existManifest->save();

            if(isset($this->truckEntryReg->ownFields)) {
                foreach ($this->truckEntryReg->ownFields as $ownField) {
                    if(isset($req->{$ownField})) {
                        $this->truckEntryReg->{$ownField} = $req->{$ownField};
                    }
                }
                $this->truckEntryReg->entry_sl = $this->truckEntryReg->getTruckSerial($req->manifest, $current_date);
                $this->truckEntryReg->goods_id = $goods_id;
                $this->truckEntryReg->manf_id = $existManifest->id;
                $this->truckEntryReg->truckentry_datetime = Auth::user()->role->id == 5 ? $req->truckentry_datetime : $current_datetime;
                $this->truckEntryReg->created_by = $user_id;
                $this->truckEntryReg->created_at = $current_datetime;
                $this->truckEntryReg->port_id = $port_id;
            }

            if(!empty($this->truckEntryReg)) {
                $save_truck = $this->truckEntryReg->save();
            } else {
                return Response::json(['error' => 'Truck Info Not Found'], 203);
            }
            //Self Type Entry
            if($req->vehicle_type_flag >= 11 && !preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $save_self_details = $this->saveSelfDetails($req, $existManifest->id, $this->truckEntryReg->id);
            }

            if($update_manifest == true && $save_truck == true) {
                return Response::json(['success' => 'Saved Successfully'], 200);
            } else {
               return Response::json(['error' => 'Something Went Wrong!'], 402); 
            }
        }
    }

    public function updateTruckEntryData(Request $req) {

        $theManifest = $this->manifest->find($req->manifest_id);
        $theTruck = $this->truckEntryReg->find($req->truck_id);

        $user_id = Auth::user()->id;
        $current_datetime = date('Y-m-d H:i:s');

        $split_requested_manifest = explode('/', $req->manifest, 3);
        $manifest_is_numeric = is_numeric($split_requested_manifest[1]);

        $goods_id = $this->saveGoodsAndGetIds($req);

        if($manifest_is_numeric) { //947/2/2018--all fields is editable
            $theManifest->manifest = $req->manifest;
            $message = " With Manifest No";
        } else {
            $message = " Without Manifest No";
        }
        $theManifest->country_id = $req->country_id;
        $theManifest->goods_id = $goods_id;
        $theManifest->updated_by = $user_id;
        $theManifest->updated_at = $current_datetime;
        $theManifest->save();

        if(isset($theTruck->ownFields)) {
            foreach ($theTruck->ownFields as $ownField) {
                if(isset($req->{$ownField})) {
                    $theTruck->{$ownField} = $req->{$ownField};
                }
            }
            $theTruck->goods_id = $goods_id;
            $theTruck->updated_by = $user_id;
            $theTruck->updated_at = $current_datetime;
            if(Auth::user()->role->id == 5 || Auth::user()->role->id == 11) {
                $theTruck->truckentry_datetime = $req->truckentry_datetime;
            }
        }
        $theTruck->save();

        if ($req->vehicle_type_flag >= 11) {
            DB::table('chassis_details')
                ->where('truck_id', $theTruck->id)
                ->update([
                    'chassis_type' => $req->truck_type,
                    'chassis_no' => $req->truck_no,
                    'updated_by' => $user_id,
                    'updated_at' => $current_datetime
                ]);

            DB::table('shed_yard_weights')
                ->where('truck_id', $theTruck->id)
                ->update([
                    'unload_yard_shed' => $req->t_posted_yard_shed,
                    'updated_at' => $current_datetime,
                    'updated_by' => $user_id
                ]);
        }
        return Response::json(['manifest_no_updated' => $theManifest->manifest , 'message' => ' Updated' . $message], 201);
    }

    public  function  truckEntryFormYardDetails()
    {

            $yard = DB::table('yard_details')
                ->select(
                    'yard_details.id',
                    'yard_details.yard_shed_name'
                )
                ->get();


        return json_encode($yard);
    }



    public function countCurrentDateYardNo(Request $req)
    {


//        $file = fopen("Truckentry.txt","w");
//              echo fwrite($file,"Faysal".$req->yard_no);
//              fclose($file);
//        return;

        $today = date('Y-m-d');


        $countYardNO = DB::select("SELECT COUNT(m.posted_yard_shed)+1 AS yard_level_no
           FROM manifests AS m WHERE DATE(m.manifest_created_time)='$today' AND m.posted_yard_shed=?",[$req->yard_no]);
        return json_encode($countYardNO);


    }



    public function getSingleManifestData(Request $req)
    {

        $port_id = Session::get('PORT_ID');
        if(Auth::user()->role->id == 5) {//CNf
            $chkManifest = DB::select('SELECT id FROM manifests WHERE manifests.manifest = ? And manifests.port_id = ?', [$req->mani_no, $port_id]);
            if($chkManifest != []) {
                $trucks = DB::select("SELECT m.id AS m_id,m.manifest,m.chassis_flag,ter.vehicle_type_flag, 
                        ter.id AS t_id,ter.truck_type,ter.truck_no, c.country_name,m.country_id,
                            ter.driver_card,ter.driver_name, ter.weightment_flag,ter.out_date,
                            ter.truckentry_datetime,ter.entry_sl, ter.truck_weight, ter.truck_package,
                            (SELECT GROUP_CONCAT(b.cargo_name SEPARATOR '?')
                            FROM manifests a
                            INNER JOIN cargo_details b
                            ON FIND_IN_SET(b.id, a.goods_id) > 0
                            WHERE a.id=ter.manf_id) AS cargo_name
                            FROM manifests AS m 
                             LEFT JOIN countrys AS c ON c.id=m.country_id
                            JOIN truck_entry_regs AS ter ON m.id=ter.manf_id
                            WHERE m.manifest=? AND m.port_id = ? AND ter.port_id = ? AND ter.created_by= ?", [$req->mani_no, $port_id, $port_id, Auth::user()->id]);
                if(count($trucks)) {
                    return $trucks;
                } else {
                    return Response::json(['notAuthorized' => 'You are not authorized to see this manifest'], 206);
                }
            } else {
                return;
            }
        }

        $trucks = DB::select("SELECT m.id AS m_id,m.manifest,m.chassis_flag,ter.vehicle_type_flag, 
                            ter.id AS t_id,ter.truck_type,ter.truck_no, c.country_name,m.country_id,
                            ter.driver_card,ter.driver_name, ter.weightment_flag,ter.out_date,
                            ter.truckentry_datetime,ter.entry_sl, ter.truck_package, ter.truck_weight,
                            (SELECT GROUP_CONCAT(b.cargo_name SEPARATOR '?')
                            FROM manifests a
                            INNER JOIN cargo_details b
                            ON FIND_IN_SET(b.id, a.goods_id) > 0
                            WHERE a.id=ter.manf_id) AS cargo_name
                            FROM manifests AS m 
                             LEFT JOIN countrys AS c ON c.id=m.country_id
                            JOIN truck_entry_regs AS ter ON m.id=ter.manf_id
                            WHERE m.manifest=? AND m.port_id = ? AND ter.port_id = ?", [$req->mani_no, $port_id, $port_id]);

        return $trucks;
    }
 
    public function deleteTruckEntry($id)
    { 
        $manifest_details = DB::select('SELECT m.* FROM manifests AS m 
                                JOIN truck_entry_regs AS tr ON tr.manf_id=m.id
                                WHERE tr.id=?', [$id]);

        $message = null;

//        dd($manifest_details[0]->manifest);

        if (count($manifest_details) > 0) {

            $mani_id = $manifest_details[0]->id;
            $manifest_no = $manifest_details[0]->manifest;

            $split_manifest_no = explode('/', $manifest_no, 3); //258/2 or 258/ A |return 2 or A
            $manifest_is_numeric = is_numeric($split_manifest_no[1]);
            $split_manifest_first_part = $split_manifest_no[0];
            $split_manifest_third_part = $split_manifest_no[2];

            if ($manifest_is_numeric) {//947/3/2018

                $total_truck = DB::table('truck_entry_regs')->where('manf_id', $mani_id)->count();
                if ($total_truck == 1) {//last truck
                    DB::transaction(function () use ($id, $mani_id) {
                        DB::table('chassis_details')->where('truck_id', $id)->delete();
                        DB::table('truck_entry_regs')->where('id', $id)->delete();
                        DB::table('manifests')->where('id', $mani_id)->delete();
                    });
                    $message = 'Successfully Deleted Manifest No. ' . $manifest_no;
                    return Response::json(['message' => $message], 200);
                } else {


                    DB::transaction(function () use ($id) {
                        DB::table('chassis_details')->where('truck_id', $id)->delete();
                        DB::table('shed_yard_weights')->where('truck_id', $id)->delete();
                        DB::table('truck_entry_regs')->where('id', $id)->delete();

                    });

                    $message = 'Successfully Deleted';
                    return Response::json(['message' => $message], 200);
                }


            } else {//947/A-E/2018 or 947/A/2018
                $split_manifest_first_part = $split_manifest_no[0] . '%';
                $split_manifest_third_part = '%' . $split_manifest_no[2];
                $get_manifest_list_for_delete = DB::select('SELECT id,manifest 
                                              FROM manifests WHERE manifest LIKE ?
                                              AND manifest LIKE ?', [$split_manifest_first_part, $split_manifest_third_part]);
                $deleted_manifests = [];

                foreach ($get_manifest_list_for_delete as $k => $v) {

                    DB::transaction(function () use ($v, $id) {
                        DB::table('shed_yard_weights')->where('truck_id', $id)->delete();//as A-E manifest  must contain 1 truck
                        DB::table('truck_entry_regs')->where('manf_id', $v->id)->delete();
                        DB::table('manifests')->where('id', $v->id)->delete();
                    });
                    array_push($deleted_manifests, $v->manifest);
                }


                $manifests_deleted = implode(", ", $deleted_manifests);
                $message = 'Successfully Deleted Manifest No. ' . $manifests_deleted;
                //  dd($message);
                return Response::json(['message' => $message], 200);
            }
        } else {
            return Response::json(['message' => 'The Truck Not Found!'], 200);
        }
    }


    public function todaysTruckEntryDetails()
    {


        $dates = date('Y-m-d');

        $todaysEntry = DB::table('truck_entry_regs')
            ->where('truckentry_datetime', 'LIKE', "%$dates%")
            ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
            ->select('truck_entry_regs.*', 'cargo_details.cargo_name')
            ->get();

        return $todaysEntry;
    }


    public function checkManifestReachLastTrurck(Request $req)
    {


        $manifest = DB::table('truck_entry_regs')
            ->where('manf_id', $req->mid)
            ->select('truck_entry_regs.manf_id')
            ->get()->count();


        return json_encode($manifest);
    }

    /* $file = fopen("Truckentry.txt","w");
           echo fwrite($file,"Hello ".$req->truck_type);
           fclose($file);*/
    //========================Exit=====================
    public function gateOutRecord(Request $r)
    {
        $out_date = date('Y-m-d H:i:s');
        $out_by = Auth::user()->id;
        $postGateOut = DB::table('truck_entry_regs')
            ->where('truck_entry_regs.id', $r->truck_id)
            ->update(['truck_entry_regs.out_date' => $out_date,
                'truck_entry_regs.out_by' => $out_by,
                'truck_entry_regs.out_comment' => $r->out_comment
            ]);
        if ($postGateOut == true) {
            return "Success";
        }
    }
    //========================Exit=====================
    //called from superAdmin role 
    public function cargoMonitorView() {
        return view('default.truck.cargo-monitor');

    }
    //called from superAdmin role which contains details entry and number of entry
    public function getCargoDetailsForMonitor($date,$vehicle) {
        $port_id = Session::get('PORT_ID');


        $vehicle_type_count = DB::select('SELECT  (
 SELECT COUNT(*) FROM truck_entry_regs AS ter 
 JOIN manifests AS m ON m.id = ter.manf_id
 WHERE (ter.vehicle_type_flag = 3 OR ter.vehicle_type_flag = 2 OR ter.vehicle_type_flag = 1 ) AND
 DATE(ter.truckentry_datetime) = ? AND m.port_id=? AND ter.port_id =? 
 AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
) AS total_goods,
 (
 SELECT COUNT(*) FROM truck_entry_regs AS ter
  JOIN manifests AS m ON m.id = ter.manf_id
   WHERE ter.vehicle_type_flag=12 AND  DATE(ter.truckentry_datetime) = ? AND m.port_id=? AND ter.port_id =? 
      AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
) AS total_trucktor,
 (
 SELECT COUNT(*) FROM truck_entry_regs AS ter
  JOIN manifests AS m ON m.id = ter.manf_id
   WHERE ter.vehicle_type_flag=16 AND  DATE(ter.truckentry_datetime) = ? AND m.port_id=? AND ter.port_id =? 
      AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
) AS total_car_self,
 (
 SELECT COUNT(*) FROM truck_entry_regs AS ter
  JOIN manifests AS m ON m.id = ter.manf_id
   WHERE ter.vehicle_type_flag=17 AND  DATE(ter.truckentry_datetime) = ? AND m.port_id=? AND ter.port_id =? 
      AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
) AS total_pick_up_self,
 (
 SELECT COUNT(*) FROM truck_entry_regs AS ter
  JOIN manifests AS m ON m.id = ter.manf_id
  WHERE ter.vehicle_type_flag=11 AND DATE(ter.truckentry_datetime) = ? AND m.port_id=? AND ter.port_id =? 
     AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
) AS total_chassis_self', [$date,$port_id,$port_id,$date,$port_id, $port_id, $date, $port_id, $port_id, $date, $port_id, $port_id,$date, $port_id, $port_id]);


        $data = DB::select('SELECT
 manifests.id AS manifest_id, manifests.manifest, truck_entry_regs.id AS truck_id,truck_entry_regs.vehicle_type_flag,
                truck_entry_regs.truck_type, truck_entry_regs.truck_no, truck_entry_regs.driver_card,
                (SELECT users.name FROM users WHERE users.id = truck_entry_regs.created_by) AS created_by,
                truck_entry_regs.truckentry_datetime,
                (SELECT users.name FROM users WHERE users.id = truck_entry_regs.updated_by) AS updated_by,
                truck_entry_regs.updated_at, truck_entry_regs.out_date,
                
                (SELECT users.name FROM users WHERE users.id = truck_entry_regs.out_by) AS out_by
                FROM manifests
                JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
                WHERE DATE(truck_entry_regs.truckentry_datetime) =? AND truck_entry_regs.vehicle_type_flag =? AND manifests.port_id = ? AND truck_entry_regs.port_id = ? 
                 AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\'
                ORDER BY TIME(truck_entry_regs.truckentry_datetime) DESC', [$date,$vehicle,$port_id, $port_id]);

        return json_encode(array($data,$vehicle_type_count));
    }
}

