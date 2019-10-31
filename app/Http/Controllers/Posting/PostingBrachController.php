<?php

namespace App\Http\Controllers\Posting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use PDF;
use Exception;
use Response;
use App\Role;
use App\truck_entry_reg;


class PostingBrachController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function welcome() {
//        return view('posting.welcome');
        $name = Auth::user()->name;
        $port_id = Session::get('PORT_ID');
        $currentDate=date('Y-m-d');

        $todaysTruckTotal=DB::select('SELECT COUNT(id) total_manifest_entry FROM manifests WHERE DATE(manifest_created_time)=? AND manifests.port_id = ?',[$currentDate,$port_id]);
        $todaysManifestTruckOutTotal=DB::select('SELECT COUNT(manifests.id) total_Truck_entry  FROM manifests JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
        WHERE DATE(manifest_created_time)=? AND manifests.port_id =?',[$currentDate,$port_id]);
        //  $upcomingTruckTotal=DB::select('SELECT COUNT(id) total_upcoming_truck FROM truck_entry_regs WHERE truckentry_datetime IS NOT NULL AND wbridg_user1 IS NULL AND weightment_flag=1');

        $postingNotDone=DB::select("SELECT COUNT(*) AS posting_not_done, CAST(TRIM(LEADING 'P' FROM SUBSTRING_INDEX(manifest,'/',1)) AS UNSIGNED) AS justManifest
FROM manifests WHERE manifests.gweight  IS NULL AND manifests.port_id=? ORDER BY justManifest DESC ",[$port_id]);

        $todaysManifestByUser=DB::select('SELECT COUNT(id) total_manifest_by_user FROM manifests WHERE manifest_posted_by=? AND  DATE(manifest_created_time) =? AND manifests.port_id = ?',[$name,$currentDate,$port_id]);



        return view('default.posting.welcome',compact('todaysTruckTotal','todaysManifestTruckOutTotal','postingNotDone','todaysManifestByUser'));
    }

//  ------------------------   this function will show manifestpostiong blade page-------------------------------//

    public function postingEntryForm()
    {
        $port_id = Session::get('PORT_ID');
        if(Auth::user()->role->id==1) {
            $yard = DB::table('shed_yards')
                ->where('shed_yards.port_id',$port_id)
                ->select('shed_yards.id',
                    'shed_yards.shed_yard')
                ->get();
        }  else if(Auth::user()->role->id==8) {

            $yard = Auth::user()->shedYards;
//            $yard = DB::table('shed_yards')
//                ->where('shed_yards.id', '=', 44)
//                ->where('shed_yards.port_id',$port_id)
//                ->select('shed_yards.id',
//                    'shed_yards.shed_yard')
//                ->get();

        } else {
            $yard = DB::table('shed_yards')
                ->where('shed_yards.id', '!=', 31)
                ->where('shed_yards.id', '!=', 44)
                ->where('shed_yards.id', '!=', 45)
                ->where('shed_yards.id', '!=', 46)
                ->where('shed_yards.port_id',$port_id)
                ->select('shed_yards.id',
                    'shed_yards.shed_yard')
                ->get();
        }

        return view('default.posting.posting-entry-form',['yards' => $yard]);
    }

    public  function  getgoodsID()
    {
        $yard = DB::table('cargo_details')
            //->select('cargo_details.id')
            ->get();
        // ->where('id',$id);
        return json_encode($yard);
    }

//    public function getAllVatsImpoerterNames()
//    {
//        $check = DB::select('SELECT v.NAME FROM vatregs AS v');
//
//        return json_encode($check);
//    }


    public  function  getGoodsName($id)
    {
        $goods = DB::table('cargo_details')
            //->select('cargo_details.id')
            ->where('id',$id)
            ->get();
        return json_encode($goods);
    }

    public  function getAllItemsGoodJson($mani_id)
    {

        $goodsItems = DB::select('SELECT  b.id,b.Description

                                                FROM    manifests a
        INNER JOIN item_codes b
            ON FIND_IN_SET(b.Code, a.goods_id) > 0
WHERE a.id=?',[$mani_id]);
        return json_encode($goodsItems);


//        $goodsItems = DB::table('item_codes')->get();
//        return json_encode($goodsItems);

    }





    public  function itemsInsertAll(Request $req)
    {


        DB::table('item_details')->insert([

            'manf_id'=>$req->manf_id,
            'item_package'=>$req->item_package,
            'item_weight'=>$req->item_weight,
            'item_code'=>$req->item_Code


        ]);

    }



    public  function  updateItemsInfo(Request $req)
    {
        DB::table('item_details')
            ->where('id', $req->it_id)
            ->update(
                [
                    'manf_id'=>$req->manf_id,
                    'item_package'=>$req->item_package,
                    'item_weight'=>$req->item_weight,
                    'item_code'=>$req->item_Code
                ]
            );

        return "successfully updated";
    }



    public  function getallItemsData($id)
    {

        $dataItems= DB::table('item_details AS it')
            ->where('it.manf_id',$id)
            ->join('item_codes AS c','it.item_code' , '=','c.id')
            ->select(

                'it.item_code',
                'it.item_package',
                'it.item_weight',
                'c.Description',
                'it.manf_id',
                'c.id',
                'it.id as it_id'

            )
            ->get();

        return json_encode($dataItems );

    }

    public function deleteItemsList($id)                                //function 9
    {
        DB::table('item_details')->where('item_code',$id)->delete();
        return'success';
    }

//    public  function  CheckPostingFormItemsJson($id)
//    {
//
////    $file = fopen("PostingForm.txt","w");
////             echo fwrite($file,"".$id);
////             //fclose($file);
////    return 'okk';
//        $itemsCheck = DB::table('item_details')
//            ->where('manf_id',$id)
//            ->get();
//        if($itemsCheck == '[]'){
////        return Response::json_encode($itemsCheck);
//            return Response::json(array($itemsCheck,'message' =>'success',201));
//        }else{
//            return Response::json(array($itemsCheck,'message' =>'Wrong',204));
//        }
//    }
    public function savePostingShedYardData(Request $req)
    {

        $currentTime=date('Y-m-d H:i:s');

        $posted_yard_shed = implode(",",$req->t_posted_yard_shed);

        $check_id_exists = DB::select("SELECT COUNT(shed_yards.id) AS id_exists FROM shed_yards WHERE shed_yards.id IN($posted_yard_shed)");
        if($check_id_exists[0]->id_exists == 0) {
            return Response::json(['yard_shed_error' => 'Please Select Posting Yard.'], 210);
        }

            DB::table('manifests')
                ->where('manifest', $req->m_manifest)
                ->update(
                    [
                        'posted_yard_shed'=> $posted_yard_shed

                    ]);

        return $req;
    }

    public function saveManifestPostingData(Request $req)
    {

        $currentTime=date('Y-m-d H:i:s');
        if($req->importerNameLabelinput != null) {
            $chkDuplicate = DB::table('vatregs')
                ->where('vatregs.BIN',$req->vatreg_id)
                ->count();
            if($chkDuplicate > 0) {
                return Response::json(['duplicate' => 'BIN Number Already Exist.'], 205);
            }
            $postImporter = DB::table('vatregs')
                ->insertGetId([
                    'BIN' => $req->vatreg_id,
                    'NAME' => $req->importerNameLabelinput
                ]);
            //return $postImporter;
        }

        //  insert new cargo_details------------
        $goods_id = $req->goods_id;
        $ids = array();
        if ($req->new_goods) {
            //check if new goods name exist
            $exist_goods = array();
            foreach ($req->new_goods as $good) {
                $good_exist = DB::select('SELECT c.id  FROM cargo_details c WHERE c.cargo_name=?', [$good]);
                if ($good_exist != [])//exist
                {
                    array_push($exist_goods, $good);
                } else {
                    continue;
                }
            }

            if ($exist_goods) {
                return Response::json(['duplicate' => 'duplicate'], 209);
            }
            //inseret new goods name
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
        $posted_yard_shed = implode(",",$req->t_posted_yard_shed);
        //return $posted_yard_shed;
//        if(Auth::user()->role->id==8) {
//            if(!in_array(44, explode(',', $posted_yard_shed))) {
//                return Response::json(['yard_shed_error' => 'Please Select Posting Yard For Self Type Manifest.'], 210);
//            }
//        }
        $check_id_exists = DB::select("SELECT COUNT(shed_yards.id) AS id_exists FROM shed_yards WHERE shed_yards.id IN($posted_yard_shed)");
        if($check_id_exists[0]->id_exists == 0) {
            return Response::json(['yard_shed_error' => 'Please Select Posting Yard.'], 210);
        }
        if($req->manifest_created_time == null) {
            DB::table('manifests')
                ->where('manifest', $req->m_manifest)
                ->update(
                    [
                        'gweight' => $req->m_gweight,
                        'marks_no' => $req->m_marks_no,
                        'manifest_date'=> $req->m_manifest_date,
                        'goods_id' => $goods_id,
                        'nweight' => $req->m_nweight,
                        'package_no' => $req->m_package_no,
                        'package_type' => $req->m_package_type,
                        'cnf_value' => $req->m_cnf_value,
                        'exporter_name_addr' => $req->m_exporter_name_addr,

                        'vatreg_id' => $req->importerNameLabelinput != null ? $postImporter : $req->vatreg_id,
                        //                     || $req->vat_id
                        //                      'vat_id' => $req->vat_id,

                        'lc_no' => $req->m_lc_no,
                        'lc_date' => $req->m_lc_date,
                        'ind_be_no' => $req->m_ind_be_no,
                        'ind_be_date' => $req->m_ind_be_date,
                        'posting_remark' => $req->posting_remark,
                        'posted_yard_shed'=> $posted_yard_shed,
                        'manifest_posted_by'=>Auth::user()->id,
                        'manifest_created_time'=>$currentTime,
                        'transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0,
                        'cnf_posted_flag' => Auth::user()->role->id == 5 ? 1 : 0,
                        'manifest_posted_done_flag' => Auth::user()->role->id == 5 ? 0 : 1
                    ]);
        } else {
            if(Auth::user()->role->id == 5 && $req->cnf_posted_flag == 0) {
                return Response::json(['not_allowed' => 'You Are Not Allowed To Save This Manifest'], 203);
            }
            DB::table('manifests')
                ->where('manifest', $req->m_manifest)
                ->update(
                    [
                        'gweight' => $req->m_gweight,
                        'marks_no' => $req->m_marks_no,
                        'manifest_date'=> $req->m_manifest_date,
                        'goods_id' => $goods_id,
                        'nweight' => $req->m_nweight,
                        'package_no' => $req->m_package_no,
                        'package_type' => $req->m_package_type,
                        'cnf_value' => $req->m_cnf_value,
                        'exporter_name_addr' => $req->m_exporter_name_addr,

                        'vatreg_id' => $req->vatreg_id,
                        //                     || $req->vat_id
                        //                      'vat_id' => $req->vat_id,

                        'lc_no' => $req->m_lc_no,
                        'lc_date' => $req->m_lc_date,
                        'ind_be_no' => $req->m_ind_be_no,
                        'ind_be_date' => $req->m_ind_be_date,
                        'posting_remark' => $req->posting_remark,
                        'posted_yard_shed'=> $posted_yard_shed,
                        'manifest_update_by'=>Auth::user()->id,
                        'manifest_update_at'=>$currentTime,
                        'transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0,
                        'manifest_posted_done_flag' => Auth::user()->role->id==5 ? 0 : 1
                    ]
                );
        }


//            $truckTbYardEntry =


//            if($manifesttable && $truckTbYardEntry == true)


//        }
//        else{
////            $truckTbYardEntry =
//
//                DB::table('truck_entry_regs')
//
//                ->where('truck_no', $req->truck_no)
//                ->update(
//                    [
//                        'posted_yard_shed' => $req->posted_yard_shed,
//                        'posted_by'=>Auth::user()->username
//                    ]
//                );
////            if($truckTbYardEntry == true)
////                return 'Successfully Updated';
//
//        }

        return $req;
        //return 'Successfully Updated';



    }

    public function JsonReturn()
    {

        $manifests =DB::table('manifest')->get();

        return json_encode($manifests);

    }

//------------------------------------------- Here joining three table SearchManifestJson-------------------------------------------------------

    public function searchSingleManifestData(Request $r) {
        $port_id = Session::get('PORT_ID');
//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello ".$r->ManifestNo);
//        fclose($file);
//return;

        // $mainData = DB::table('manifests')->where('manifest',$r->ManifestNo)
        //     ->join('truck_entry_regs', 'manifests.id', '=','truck_entry_regs.manf_id')
        //     ->join('cargo_details', 'manifests.goods_id', '=', 'cargo_details.id')
        //     ->leftjoin('yard_details', DB::raw('FIND_IN_SET(yard_details.id, manifests.posted_yard_shed)'), '>', DB::raw("'0'"))
        //     ->leftjoin('vatregs AS v','manifests.vatreg_id' , '=','v.id' )
        //     ->leftjoin('users' , 'manifests.manifest_posted_by', '=', 'users.id')
        //     ->leftjoin('organizations', 'users.org_id','=','organizations.id')
        //     ->select(
        //         'manifests.id as m_id',
        //         'manifests.manifest as m_manifest',
        //         'manifests.manifest_date as m_manifest_date',
        //         'manifests.marks_no as m_marks_no',

        //         'manifests.goods_id as m_good_id',
        //         'manifests.gweight as m_gweight',
        //         'manifests.nweight as m_nweight',
        //         'manifests.package_no as m_package_no',
        //         'manifests.package_type as m_package_type',
        //         'manifests.cnf_value as m_cnf_value',
        //         'manifests.exporter_name_addr as m_exporter_name_addr',
        //         'manifests.lc_no as m_lc_no',
        //         'manifests.lc_date as m_lc_date',
        //         'manifests.ind_be_no as m_ind_be_no',
        //         'manifests.ind_be_date as m_ind_be_date',
        //         'manifests.posting_remark as posting_remark',
        //         'manifests.posted_yard_shed as posted_yard_shed',
        //         'yard_details.id as yard_details_id',
        //         DB::raw('GROUP_CONCAT(yard_details.yard_shed_name) as yard_shed_name'),
        //         'truck_entry_regs.id as t_id',
        //         'truck_entry_regs.truck_type as t_truck_type',
        //         'truck_entry_regs.truck_no as t_truck_no',
        //         'truck_entry_regs.manf_id as t_manf_id',
        //         'truck_entry_regs.goods_id as t_goods_id',
        //         'truck_entry_regs.gweight as t_gweight',
        //         'truck_entry_regs.nweight as t_nweight',
        //         'truck_entry_regs.driver_card as driver_card',
        //         'truck_entry_regs.driver_name as driver_name',

        //         'truck_entry_regs.gweight_wbridge as t_gweight_wbridge',
        //         'cargo_details.cargo_name',
        //          'v.NAME AS importer',
        //         'v.BIN as m_vat_id',
        //         'v.id as vatreg_id',
        //         'manifests.manifest_created_time',
        //         'manifests.cnf_posted_flag',
        //         'manifests.manifest_posted_done_flag',
        //         'organizations.org_name'
        //     )
        //     ->get();

        $mainData = DB::select("SELECT manifests.id AS m_id, manifests.manifest AS m_manifest,manifests.manifest_date AS m_manifest_date,
manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight, manifests.nweight AS m_nweight,
manifests.package_no AS m_package_no, manifests.package_type AS m_package_type, manifests.cnf_value AS m_cnf_value,
manifests.exporter_name_addr AS m_exporter_name_addr, manifests.lc_no AS m_lc_no, manifests.lc_date AS m_lc_date,
manifests.ind_be_no AS m_ind_be_no, manifests.ind_be_date AS m_ind_be_date, manifests.posting_remark AS posting_remark,
manifests.posted_yard_shed AS posted_yard_shed, yard_details.id AS yard_details_id,
GROUP_CONCAT(DISTINCT yard_details.yard_shed_name) AS yard_shed_name,truck_entry_regs.id AS t_id, truck_entry_regs.truck_type AS t_truck_type,
truck_entry_regs.truck_no AS t_truck_no, truck_entry_regs.manf_id AS t_manf_id, truck_entry_regs.goods_id AS t_goods_id,
truck_entry_regs.driver_card AS driver_card,
truck_entry_regs.driver_name AS driver_name, truck_entry_regs.gweight_wbridge AS t_gweight_wbridge,
cargo_details.cargo_name, v.NAME AS importer, v.BIN AS m_vat_id, v.id AS vatreg_id, manifests.manifest_created_time,
manifests.cnf_posted_flag, manifests.manifest_posted_done_flag
FROM manifests
JOIN truck_entry_regs ON manifests.id = truck_entry_regs.manf_id
JOIN cargo_details ON manifests.goods_id = cargo_details.id
LEFT JOIN yard_details ON FIND_IN_SET(yard_details.shed_yard_id, manifests.posted_yard_shed) > 0
LEFT JOIN vatregs AS v ON manifests.vatreg_id = v.id
LEFT JOIN users ON manifests.manifest_posted_by = users.id
WHERE manifests.manifest = ? AND manifests.port_id = ?",[$r->ManifestNo,$port_id]);

        return json_encode($mainData);




    }
    //----------------------------------------------End SearchManifestJson--------------------------------------------------------------------------

//
//    public  function PortsJson()
//    {
//        $port = DB::table('ports')->get();
//        return json_encode($port);
//    }

    public function searchManifestDataForShedYardEntry(Request $r) {
        $port_id = Session::get('PORT_ID');
        $mainData = DB::select("SELECT manifests.manifest,manifests.id,GROUP_CONCAT(CONCAT(truck_entry_regs.truck_type,'-',truck_entry_regs.truck_no)) AS truck_type_no, manifests.posted_yard_shed  FROM manifests 
JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id
 WHERE manifests.manifest=? AND manifests.port_id = ?",[$r->ManifestNo,$port_id]);

        return json_encode($mainData);


    }



    public  function getVatsDataDetails()
    {
        $vat = DB::table('vatregs')
            ->select('vatregs.NAME','vatregs.BIN')->take(100)->get();
        return json_encode($vat);

    }


    public  function getVatDetails(Request $req)
    {
        $vat = DB::table('vatregs')
            ->where('BIN',$req->BIN)
            ->select('vatregs.NAME')->get();
        return json_encode($vat);
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


    public  function getVatNameDetails(Request $req)
    {
        $term = $req->term;//Input::get('term');
        $results = array();
        $queries = DB::table('vatregs')
            ->where('BIN', 'LIKE', $term.'%')
            // ->orWhere('last_name', 'LIKE', '%'.$term.'%')
            ->take(10)->get();
        if(!$queries){
            $results[] =['value' => 'no'];
        }
        else{
            foreach ($queries as $query)
            {
                $results[] = ['value' => $query->BIN, 'vatreg_id' => $query->id, 'desc' => $query->NAME];
            }
        }

        return json_encode($results);

    }
    public  function getPackageType()
    {
        $port_id = Session::get('PORT_ID');
        $packageType=DB::select('SELECT DISTINCT package_type  FROM manifests WHERE package_type IS NOT NULL AND manifests.port_id = ?',[$port_id]);

        return json_encode($packageType);

    }



    public  function  YardDetailsJson()
    {
        if(Auth::user()->role->name=="TransShipment") {
            $yard = DB::table('yard_details')
                ->where('yard_details.id', '=', 55)
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        } else if(Auth::user()->role->name=="Super Admin") {
            $yard = DB::table('yard_details')
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        } else {
            $yard = DB::table('yard_details')
                ->where('yard_details.id', '!=', 55)
                ->select('yard_details.id',
                    'yard_details.yard_shed_name')
                ->get();
        }

        return json_encode($yard);
    }



    public function getTodaysManifestPosting() {

        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $port_id = Session::get('PORT_ID');

//        $todaysManifestEntry = DB::table('truck_entry_regs')
//            ->where('wbrdge_time1','LIKE',"%$today%" )
//            ->join('manifests', 'manifests.id', '=','truck_entry_regs.manf_id')
//            ->select('truck_entry_regs.*','manifests..manifest')
//            ->get();



        $mainData = DB::select('SELECT manifests.id AS m_id, manifests.manifest AS m_manifest, manifests.manifest_date AS m_manifest_date,
                manifests.marks_no AS m_marks_no, manifests.goods_id AS m_good_id, manifests.gweight AS m_gweight,
                manifests.nweight AS m_nweight, manifests.package_no AS m_package_no, manifests.package_type AS m_package_type,
                manifests.cnf_value AS m_cnf_value, manifests.exporter_name_addr AS m_exporter_name_addr,
                manifests.lc_no AS m_lc_no, manifests.lc_date AS m_lc_date, manifests.ind_be_no AS m_ind_be_no,
                manifests.ind_be_date AS m_ind_be_date,GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed,
                cargo_details.cargo_name, vatregs.NAME, vatregs.BIN AS m_vat_id, vatregs.ADD1
                FROM manifests
                JOIN cargo_details ON manifests.goods_id = cargo_details.id
                JOIN vatregs ON vatregs.id = manifests.vatreg_id
                JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
                WHERE manifests.transshipment_flag = 0 AND DATE(manifests.manifest_created_time)=? AND manifests.port_id = ?
                GROUP BY manifests.id',[$today,$port_id]);


        $pdf = PDF::loadView('default.posting.reports.todays-posting-report',[
            'mainData' => $mainData,
            'todayWithTime' => $todayWithTime
        ])  ->setPaper([0, 0, 920.661, 1000.63], 'landscape');
        //->setPaper('a4', 'landscape');

        //return $pdf->download('user.pdf');
        return $pdf->stream('todaysManifestPostingReport.pdf');
    }




    public function postingManifestDetails($manifest,$truck,$year) {
        $port_id = Session::get('PORT_ID');
        $totalFigure = (string)$manifest."/".(string)$truck."/".(string)$year;
        //return $totalFigure;
        $todayWithTime = date('Y-m-d h:i:s a');
        $bdTruckData = DB::table('manifests')
            ->join('truck_deliverys', 'truck_deliverys.manf_id', '=','manifests.id')
            ->where('manifest',$totalFigure)
            ->where('manifests.port_id',$port_id)
            ->select('manifests.manifest','truck_deliverys.*')
            ->get();

        $indianTruckData = DB::table('manifests')
            ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=','manifests.id')
            ->where('manifest',$totalFigure)
            ->where('manifests.port_id',$port_id)
            ->select('manifests.manifest','truck_entry_regs.*')
            ->get();

        $manifestDetails = DB::table('manifests')
            ->join('cargo_details', 'cargo_details.id', '=','manifests.goods_id')
            ->leftjoin('vatregs', 'vatregs.id', '=','manifests.vatreg_id')
            ->where('manifest',$totalFigure)
            ->where('manifests.port_id',$port_id)
            ->select('manifests.*','cargo_details.*','vatregs.*')
            ->get();
//dd($bdTruckData);
        $pdf = PDF::loadView('default.posting.reports.manifest-details-report',[
            'bdTruckData' => $bdTruckData,
            'indianTruckData' => $indianTruckData,
            'todayWithTime' => $todayWithTime,
            'manifestDetails' => $manifestDetails,
            'manifestNo' => $totalFigure
        ])
            ->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('ManifestDetailsPDF.pdf');
    }

    //======================Add Importer
    public function saveImporterFromPosting(Request $r) {
        $chkDuplicate = DB::table('vatregs')
            ->where('vatregs.BIN',$r->BIN)
            ->count();
        if($chkDuplicate > 0) {
            return Response::json(['duplicate' => 'BIN Number Already Exist.'], 401);
        }
        //return;
        $createdBy = Auth::user()->id;
        $createDate = date('Y-m-d H:i:s');
        $postImporter = DB::table('vatregs')
            ->insert([
                'BIN' => $r->BIN,
                'NAME' => $r->NAME,
                'ADD1' => $r->ADD1,
                'ADD2' => $r->ADD2,
                'ADD3' => $r->ADD3,
                'ADD4' => $r->ADD4,
                'created_by' => $createdBy,
                'created_date' => $createDate
            ]);
        if($postImporter == true) {
            return "Success";
        }
    }


    public function postingEntryMonitorView()
    {
        return view('default.posting.posting-entry-monitor');
    }


    public function getPostingDetailsForMonitor($date)
    {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT manifests.id AS manifest_id, manifests.manifest,GROUP_CONCAT( shed_yards.shed_yard) AS posted_yard_shed,
					(SELECT GROUP_CONCAT(cargo_details.cargo_name SEPARATOR ",") FROM cargo_details
					WHERE FIND_IN_SET(cargo_details.id, manifests.goods_id) > 0
					) AS goods_name,
					(SELECT users.name FROM users WHERE users.id = manifests.manifest_posted_by) AS manifest_entry_by,
					manifests.manifest_created_time,
					(SELECT users.name FROM users WHERE users.id = manifests.manifest_update_by) AS manifest_updated_by,
					manifests.manifest_update_at
					FROM manifests
					 JOIN shed_yards ON FIND_IN_SET(shed_yards.id, manifests.posted_yard_shed) > 0
					WHERE DATE(manifests.manifest_created_time) = ? AND manifests.port_id=?
					GROUP BY manifests.id ORDER BY TIME(manifests.manifest_created_time) DESC', [$date,$port_id]);
        return json_encode($data);
    }







//    public function GoodsJson()
//{
//    $goods = DB::table('cargo_details')->get();
//    return json_encode($goods);
//}


    /*
    $file = fopen("Truckentry.txt","w");
    echo fwrite($file,"Hello ".$checkSubheadExist);
    fclose($file);
    return 'okkkk';
    */
    /* $file = fopen("Truckentry.txt","w");
              echo fwrite($file,"Hello ".$req->truck_type);
              fclose($file);*/


//--------------------------------------------------------------Very Important for check DATA----------------------------------------------------------------------


    public function shedYardEntryForm()
    {
      $port_id = Session::get('PORT_ID');
        if(Auth::user()->role->id==1) {
            $yard = DB::table('shed_yards')
                ->where('shed_yards.port_id',$port_id)
                ->select('shed_yards.id',
                    'shed_yards.shed_yard')
                ->get();
        }  else if(Auth::user()->role->id==8) {

            $yard = Auth::user()->shedYards;
//            $yard = DB::table('shed_yards')
//                ->where('shed_yards.id', '=', 44)
//                ->where('shed_yards.port_id',$port_id)
//                ->select('shed_yards.id',
//                    'shed_yards.shed_yard')
//                ->get();

        } else {
            $yard = DB::table('shed_yards')
                ->where('shed_yards.id', '!=', 31)
                ->where('shed_yards.id', '!=', 44)
                ->where('shed_yards.id', '!=', 45)
                ->where('shed_yards.id', '!=', 46)
                ->where('shed_yards.port_id',$port_id)
                ->select('shed_yards.id',
                    'shed_yards.shed_yard')
                ->get();
        }

        return view('default.posting.shed-yard-entry-form',['yards' => $yard]);
    }



}