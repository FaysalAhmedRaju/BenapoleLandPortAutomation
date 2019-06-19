<?php

namespace App\Http\Controllers\Weighbridge;

use App\Http\Controllers\Base\ProjectBaseController;
use App\Models\Port;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use PDF;
use App\Models\Truck\TruckEntryReg;
use Response;
use Route;
use Alert;

class WeighBridgeController extends ProjectBaseController
{
    public function index()
    {
        $model = $this->weighbridge;
        $weights = $this->weighbridge->orderBy('id', 'DESC')->paginate(10);
        $viewType = 'Weighbridge List';

        return view('default.weighbridge.index', compact('weights', 'viewType', 'model'));
    }


    public function createWeighbridgeForm()
    {

        $viewType = 'Create Weightbridge';

        $portList = (new Port())->portList();
       // dd($portList);
        return view('default.weighbridge.create', compact('viewType','portList'));

    }

    public function saveWeighbridge(Request $req)
    {


        $this->validate($req, [
//            'icon_name' => 'required',
//            'menu_name' => 'required',
//            'route_name' => 'required|unique:menus',

        ]);

        if (isset($this->weighbridge->ownFields)) {
            foreach ($this->weighbridge->ownFields as $ownfield) {
                if ($req->{$ownfield}) {
                    $this->weighbridge->{$ownfield} = $req->{$ownfield};
                }
            }
        }

        $this->weighbridge->created_by = Auth::user()->id;
        $this->weighbridge->created_at = date('Y-m-d H:i:s');





        if ($this->weighbridge->save()) {
            return  redirect()->route('weighbridge-list')->withSuccess('Successfully Created Weithbridge!');

        } else {
            return back()->withError('Something Went Wrong!');
        }
    }


    public function editWeighbridgeForm($id)
    {
        $viewType = 'Weighbeidge Edit Form';
        $theWeighbridge = $this->weighbridge->findOrFail($id);
        $portList = (new Port())->portList();
        return view('default.weighbridge.edit', compact('viewType', 'theWeighbridge', 'portList'));

    }

    public function updateWeighbridge(Request $r,$id)
    {
        $datetime = date('Y-m-d H:i:s');
        $user_id = Auth::user()->id;
        $theWeighbridge = $this->weighbridge->findOrFail($id);
        if (isset($theWeighbridge->ownFields) && !empty($theWeighbridge->ownFields)) {
            foreach ($theWeighbridge->ownFields as $k => $ownField) {

                if ($r->{$ownField}) {
                    $theWeighbridge->{$ownField} = $r->{$ownField};
                }
            }
        }

        $theWeighbridge->updated_by = $user_id;
        $theWeighbridge->updated_at = $datetime;

        if ($theWeighbridge->save()) {
            return  redirect()->route('weighbridge-list')->withSuccess('Successfully Updated The Weighbridge!');
        }
        return back()->withError('Something Went Wrong!');

    }



    public function deleteWeighbridge($id)
    {
        return back()->withErrors('Delete is In Progress!');


        $weighbridgeDelete = $this->weighbridge->findOrFail($id);
        if ($weighbridgeDelete->delete()) {
            return back()->withSuccess('Successfully Deleted');
        }
        return back()->withError('Something Went Wrong!');

    }


    public function welcome()
    {
        $port_id = Session::get('PORT_ID');
        $currentDate = date('Y-m-d');
        $currentUser = Auth::user()->id;

        $todaysTruckInTotal = DB::select('SELECT COUNT(id) total_truck_entry FROM truck_entry_regs WHERE DATE(wbrdge_time1)=? AND truck_entry_regs.port_id = ?', [$currentDate, $port_id]);
        $todaysTruckOutTotal = DB::select('SELECT COUNT(id) total_truck_out FROM truck_entry_regs WHERE DATE(wbrdge_time2)=? AND truck_entry_regs.port_id = ?', [$currentDate, $port_id]);
        $upcomingTruckTotal = DB::select('SELECT COUNT(id) total_upcoming_truck FROM truck_entry_regs WHERE truckentry_datetime IS NOT NULL AND wbridg_user1 IS NULL AND weightment_flag=1 AND truck_entry_regs.port_id = ?', [$port_id]);
        $inOutTruckTotalByCurrentUser = DB::select('SELECT (SELECT COUNT(*) FROM truck_entry_regs WHERE  weightment_flag=1 AND  DATE(wbrdge_time1)=\'2017-07-24 17:00:40\' AND  truck_entry_regs.port_id =?) total_in_by_you,
(SELECT COUNT(*) FROM truck_entry_regs WHERE  weightment_flag=1 AND  DATE(wbrdge_time2)=\'2018-07-24 17:00:40\' AND  truck_entry_regs.port_id =?) total_out_by_you
FROM truck_entry_regs  LIMIT 1 ', [$currentUser, $currentDate, $port_id, $currentUser, $currentDate, $port_id]);

        return view('default.weighbridge.welcome', compact('todaysTruckInTotal', 'todaysTruckOutTotal', 'upcomingTruckTotal', 'inOutTruckTotalByCurrentUser'));
    }

    public function weighBridgeEntryForm()
    {
        return view('default.weighbridge.weighbridge-entry-form');
    }

    public function countTrucksTodaysEntryExit()
    {
        $port_id = Session::get('PORT_ID');
        //$user = Auth::user()->id;
        $today = date('Y-m-d');
        //$todayWithTime = date('Y-m-d h:i:s a');
        $roleId = Auth::user()->role->id;
        //dd(Auth::user()->weighbridges);
        if ($roleId == 6) {

            $countResult = DB::select('SELECT * FROM(SELECT
( SELECT  COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time1) =? AND manifests.transshipment_flag =0 AND truck_entry_regs.entry_scale=? AND truck_entry_regs.port_id = ? 
 AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS entry_truck ,
(  SELECT COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time2) =? AND manifests.transshipment_flag =0 AND truck_entry_regs.exit_scale = ? AND truck_entry_regs.port_id =?
  AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS exit_truck ) AS final ', [$today, Auth::user()->weighbridges->first()->id, $port_id, $today, Auth::user()->weighbridges->first()->id, $port_id]);
        } else if ($roleId == 12) {  //Transhipment

            $countResult = DB::select('SELECT * FROM(SELECT
( SELECT  COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time1) =? AND truck_entry_regs.port_id =?  AND manifests.transshipment_flag =1
 AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS entry_truck ,
(  SELECT COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time2) =? AND truck_entry_regs.port_id=?  AND manifests.transshipment_flag =1
  AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS exit_truck ) AS final ', [$today, $port_id, $today, $port_id]);

        } else { // superAdmin
            $countResult = DB::select('SELECT * FROM(
SELECT
( SELECT  COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time1) =? AND truck_entry_regs.port_id =?
 AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS entry_truck ,
(  SELECT COUNT(truck_entry_regs.id) FROM truck_entry_regs JOIN manifests ON manifests.id = truck_entry_regs.manf_id  
 WHERE DATE(truck_entry_regs.wbrdge_time2) =? AND truck_entry_regs.port_id = ?
  AND SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)  NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\') AS exit_truck 
  ) AS final', [$today, $port_id, $today, $port_id]);

        }
        return json_encode($countResult);
    }


    //====================Common=============================
    public function searchManifestOrTruck(Request $r)
    {
        $port_id = Session::get('PORT_ID');

        if ($r->searchKey == 'manifestNo') {
            $truckData = DB::table('manifests')
                ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
                ->where('manifests.manifest', $r->searchField)
                ->where('weightment_flag', '=', 1)
                ->where('truck_entry_regs.port_id', '=', $port_id)
                ->select('truck_entry_regs.id',
                    'truck_entry_regs.truck_type',
                    'truck_entry_regs.truck_no',
                    'truck_entry_regs.goods_id',
                    'truck_entry_regs.driver_name',
                    'truck_entry_regs.gweight_wbridge',
                    'truck_entry_regs.tweight_wbridge',
                    'truck_entry_regs.wbrdge_time1',
                    'truck_entry_regs.tr_weight',
                    'truck_entry_regs.wbrdge_time2',
                    'manifests.manifest',
                    'truck_entry_regs.wbridg_created_at1',
                    'truck_entry_regs.wbridg_created_at2')
                ->get();
            return json_encode($truckData);
        }
        if ($r->searchKey == 'truckTypeNo') {
            $string = $r->searchField;
            if (preg_match("/[a-z]/i", $string)) {
                $truckTypeAndNumber = explode('-', $string);
                $truckData = DB::table('manifests')
                    ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
                    ->where('truck_entry_regs.truck_no', $truckTypeAndNumber[1])
                    ->where('truck_entry_regs.truck_type', $truckTypeAndNumber[0])
                    ->where('weightment_flag', '=', 1)
                    ->where('truck_entry_regs.port_id', '=', $port_id)
                    ->select('truck_entry_regs.id',
                        'truck_entry_regs.truck_type',
                        'truck_entry_regs.truck_no',
                        'truck_entry_regs.goods_id',
                        'truck_entry_regs.driver_name',
                        'truck_entry_regs.gweight_wbridge',
                        'truck_entry_regs.tweight_wbridge',
                        'truck_entry_regs.wbrdge_time1',
                        'truck_entry_regs.tr_weight',
                        'truck_entry_regs.wbrdge_time2',
                        DB::raw('(SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS goods'),
                        'manifests.manifest',
                        'truck_entry_regs.wbridg_created_at1',
                        'truck_entry_regs.wbridg_created_at2')
                    ->orderBy('truck_entry_regs.truckentry_datetime', 'desc')
                    ->take(1)
                    ->get();
            } else {
                $truckData = DB::table('manifests')
                    ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
                    ->where('truck_entry_regs.truck_no', $r->searchField)
                    ->where('truck_entry_regs.port_id', '=', $port_id)
                    ->where('weightment_flag', '=', 1)
                    ->select('truck_entry_regs.id',
                        'truck_entry_regs.truck_type',
                        'truck_entry_regs.truck_no',
                        'truck_entry_regs.goods_id',
                        'truck_entry_regs.driver_name',
                        'truck_entry_regs.gweight_wbridge',
                        'truck_entry_regs.tweight_wbridge',
                        'truck_entry_regs.wbrdge_time1',
                        'truck_entry_regs.tr_weight',
                        'truck_entry_regs.wbrdge_time2',
                        DB::raw('(SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS goods'),
                        'manifests.manifest',
                        'truck_entry_regs.wbridg_created_at1',
                        'truck_entry_regs.wbridg_created_at2')
                    ->orderBy('truck_entry_regs.truckentry_datetime', 'desc')
                    ->take(1)
                    ->get();

            }
            $trWeight = DB::select("SELECT truck_entry_regs.tr_weight
                                FROM truck_entry_regs 
                                WHERE truck_entry_regs.truck_no=? AND truck_entry_regs.port_id=?  
                                AND wbrdge_time2 >= DATE_ADD(NOW(), INTERVAL -3 MONTH) 
                                ORDER BY id DESC LIMIT 1", [$r->searchField, $port_id]);
            return json_encode(array('truckData' => $truckData, 'trWeight' => $trWeight));
        }
    }

    public function getGoodsNameData(Request $r)
    {
        $goodsData = DB::table('cargo_details')
            ->where('id', '=', $r->goods_id)
            ->get();
        return json_encode($goodsData);
    }

    //================================WeightBridge Entry==============================

    public function saveEntryDataWithGrossWeight(Request $r)
    {
        $port_id = Session::get('PORT_ID');
//        $theTruck = $this->truckEntryReg->find($r->id);
        $split_manifest_no = explode('/', $r->manifest, 3); //258/2 or 258/ A |return 2 or A

//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"ok".$split_manifest_no[1]);
//        fclose($file);
//        return;

        $user = Auth::user()->id;
        if ($r->wbridg_created_at1 == null) {
            $weightBridgeEntry = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                    'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                    'truck_entry_regs.wbridg_user1' => $user,
                    'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                    'truck_entry_regs.entry_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                    'truck_entry_regs.wbridg_created_at1' => date('Y-m-d H:i:s')
                ]);


            //For Manifest 876/A-E/2017 Start-------------------------------
            if (preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $getChar = explode('-', $split_manifest_no[1], 2);
                $firstChar = ord($getChar[0]);
                $lastChar = ord($getChar[1]);

                for ($i = $firstChar + 1; $i <= $lastChar; $i++) {
                    $newManifestNo = $split_manifest_no[0] . "/" . chr($i) . "-" . chr($lastChar) . "/" . $split_manifest_no[2];
                    $get_truck_id = DB::select("SELECT truck_entry_regs.id AS t_id FROM manifests JOIN truck_entry_regs ON manifests.id= truck_entry_regs.manf_id WHERE manifests.manifest =? 
AND truck_entry_regs.port_id = ?", [$newManifestNo, $port_id]);

                    $weightBridgeEntry = DB::table('truck_entry_regs')
                        ->where('truck_entry_regs.id', $get_truck_id[0]->t_id)
                        ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                        ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                            'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                            'truck_entry_regs.wbridg_user1' => $user,
                            'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                            'truck_entry_regs.entry_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                            'truck_entry_regs.wbridg_created_at1' => date('Y-m-d H:i:s')
                        ]);
//                    $file = fopen("Truckentry.txt","w");
//                    echo fwrite($file,"hi".$get_truck_id[0]->t_id);
//                    fclose($file);
//                    return;
                }
            }

        } else {
            $weightBridgeEntry = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                    'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                    'truck_entry_regs.wbridg_updated_by1' => $user,
                    'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                    'truck_entry_regs.wbridg_updated_at1' => date('Y-m-d H:i:s'),
                ]);

            //For Manifest 876/A-E/2017 Start-------------------------------
            if (preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $getChar = explode('-', $split_manifest_no[1], 2);
                $firstChar = ord($getChar[0]);
                $lastChar = ord($getChar[1]);

                for ($i = $firstChar + 1; $i <= $lastChar; $i++) {
                    $newManifestNo = $split_manifest_no[0] . "/" . chr($i) . "-" . chr($lastChar) . "/" . $split_manifest_no[2];
                    $get_truck_id = DB::select("SELECT truck_entry_regs.id AS t_id FROM manifests JOIN truck_entry_regs ON manifests.id= truck_entry_regs.manf_id WHERE manifests.manifest =? 
AND truck_entry_regs.port_id = ?", [$newManifestNo, $port_id]);

                    $weightBridgeEntry = DB::table('truck_entry_regs')
                        ->where('truck_entry_regs.id', $get_truck_id[0]->t_id)
                        ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                        ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                            'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                            'truck_entry_regs.wbridg_updated_by1' => $user,
                            'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                            'truck_entry_regs.wbridg_updated_at1' => date('Y-m-d H:i:s'),
                        ]);

//                    $file = fopen("Truckentry.txt","w");
//                    echo fwrite($file,"hi".$get_truck_id[0]->t_id);
//                    fclose($file);
//                    return;
                }

            }
        }

        if ($weightBridgeEntry == true) {
            return 'Success';
        }
    }


    public function saveEntryDataWithTearWeightNetWeight(Request $r)
    {

        $user = Auth::user()->id;
        if ($r->wbridg_created_at1 == null) {


            $weightBridgeEntryWithTrAndNetWeight = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                    'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                    'truck_entry_regs.tr_weight' => $r->tr_weight,
                    'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                    'truck_entry_regs.wbridg_user1' => $user,
                    'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                    'truck_entry_regs.wbridg_user2' => $user,
                    'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIVE WEIGHT
                    'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                    'truck_entry_regs.entry_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                    'truck_entry_regs.wbridg_created_at2' => date('Y-m-d H:i:s')
                ]);


        } else {


            $weightBridgeEntryWithTrAndNetWeight = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.gweight_wbridge' => $r->gweight_wbridge,
                    'truck_entry_regs.wbrdge_time1' => $r->wbrdge_time1,
                    'truck_entry_regs.tr_weight' => $r->tr_weight,
                    'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                    'truck_entry_regs.wbridg_updated_by1' => $user,
                    'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                    'truck_entry_regs.wbridg_updated_by2' => $user,
                    'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIV
                    'manifests.transshipment_flag' => Auth::user()->role->id == 12 ? 1 : 0,
                    'truck_entry_regs.entry_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                    'truck_entry_regs.wbridg_updated_at2' => date('Y-m-d H:i:s'),
                    'truck_entry_regs.wbridg_updated_at1' => date('Y-m-d H:i:s')
                ]);


        }

        if ($weightBridgeEntryWithTrAndNetWeight == true) {
            return 'Success';
        }
        return 'Something Went Wrong!';
//
////         $file = fopen("Truckentry.txt","w");
////         echo fwrite($file,"hi faysal".$r->manifest);
////         fclose($file);
////         return;

    }

    public function getTearWeightData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $trWeight = DB::select("SELECT truck_entry_regs.tr_weight
                                FROM truck_entry_regs 
                                WHERE truck_entry_regs.truck_no=? AND truck_entry_regs.truck_type=? AND truck_entry_regs.port_id = ? 
                                AND wbrdge_time2 >= DATE_ADD(NOW(), INTERVAL -3 MONTH) 
                                ORDER BY id DESC LIMIT 1", [$r->truck_no, $r->truck_type, $port_id]);
        return $trWeight;
    }


    //=============WeightBridge Out=========================

    public function saveExitData(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $split_manifest_no = explode('/', $r->manifest, 3); //258/2 or 258/ A |return 2 or A
        $user = Auth::user()->id;
        if ($r->wbridg_created_at2 == null) {

            $weightBridgeExit = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.tr_weight' => $r->tr_weight,
                    'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                    'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                    'truck_entry_regs.wbridg_user2' => $user,
                    'truck_entry_regs.exit_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                    'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIVEWEIGHT
                    'truck_entry_regs.wbridg_created_at2' => date('Y-m-d H:i:s')
                    //'manifests.transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0
                ]);

            //For Manifest 876/A-E/2017 Start-------------------------------
            if (preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $getChar = explode('-', $split_manifest_no[1], 2);
                $firstChar = ord($getChar[0]);
                $lastChar = ord($getChar[1]);

                for ($i = $firstChar + 1; $i <= $lastChar; $i++) {
                    $newManifestNo = $split_manifest_no[0] . "/" . chr($i) . "-" . chr($lastChar) . "/" . $split_manifest_no[2];
                    $get_truck_id = DB::select("SELECT truck_entry_regs.id AS t_id FROM manifests JOIN truck_entry_regs ON manifests.id= truck_entry_regs.manf_id WHERE manifests.manifest =?
AND truck_entry_regs.port_id = ?", [$newManifestNo, $port_id]);
                    $weightBridgeExit = DB::table('truck_entry_regs')
                        ->where('truck_entry_regs.id', $get_truck_id[0]->t_id)
                        ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                        ->update(['truck_entry_regs.tr_weight' => $r->tr_weight,
                            'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                            'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                            'truck_entry_regs.wbridg_user2' => $user,
                            'truck_entry_regs.exit_scale' => Auth::user()->scale ? Auth::user()->scale->scale_id : null,
                            'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIVEWEIGHT
                            'truck_entry_regs.wbridg_created_at2' => date('Y-m-d H:i:s')
                            //'manifests.transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0
                        ]);


//                    $file = fopen("Truckentry.txt","w");
//                    echo fwrite($file,"hi".$get_truck_id[0]->t_id);
//                    fclose($file);
//                    return;


                }

            }

        } else {

            $weightBridgeExit = DB::table('truck_entry_regs')
                ->where('truck_entry_regs.id', $r->id)
                ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                ->update(['truck_entry_regs.tr_weight' => $r->tr_weight,
                    'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                    'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                    'truck_entry_regs.wbridg_updated_by2' => $user,
                    'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIVE WEIGHT
                    'truck_entry_regs.wbridg_updated_at2' => date('Y-m-d H:i:s')
                    //'manifests.transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0
                ]);

            //For Manifest 876/A-E/2017 Start-------------------------------
            if (preg_match("/^[A][\-][B-Z]$/", $split_manifest_no[1])) {
                $getChar = explode('-', $split_manifest_no[1], 2);
                $firstChar = ord($getChar[0]);
                $lastChar = ord($getChar[1]);

                for ($i = $firstChar + 1; $i <= $lastChar; $i++) {
                    $newManifestNo = $split_manifest_no[0] . "/" . chr($i) . "-" . chr($lastChar) . "/" . $split_manifest_no[2];
                    $get_truck_id = DB::select("
SELECT truck_entry_regs.id AS t_id FROM manifests JOIN truck_entry_regs ON manifests.id= truck_entry_regs.manf_id WHERE manifests.manifest =?
AND truck_entry_regs.port_id = ?", [$newManifestNo, $port_id]);

                    $weightBridgeExit = DB::table('truck_entry_regs')
                        ->where('truck_entry_regs.id', $get_truck_id[0]->t_id)
                        ->join('manifests', 'manifests.id', '=', 'truck_entry_regs.manf_id')
                        ->update(['truck_entry_regs.tr_weight' => $r->tr_weight,
                            'truck_entry_regs.tweight_wbridge' => $r->tweight_wbridge,
                            'truck_entry_regs.wbrdge_time2' => $r->wbrdge_time2,
                            'truck_entry_regs.wbridg_updated_by2' => $user,
                            'truck_entry_regs.receive_weight' => $r->tweight_wbridge, //WAREHOUSE RECEIVE WEIGHT
                            'truck_entry_regs.wbridg_updated_at2' => date('Y-m-d H:i:s')
                            //'manifests.transshipment_flag' =>  Auth::user()->role->id==12 ? 1 : 0
                        ]);


//                    $file = fopen("Truckentry.txt","w");
//                    echo fwrite($file,"hi".$get_truck_id[0]->t_id);
//                    fclose($file);
//                    return;


                }

            }
        }
        if ($weightBridgeExit == true) {
            return 'Success';
        }
    }


    //========================Report===========================
    public function weightReportView()
    {
        return view('default.weighbridge.weight-report-view');
    }

    public function getManifestDetailsData($manifest, $truck, $year)
    {
        $port_id = Session::get('PORT_ID');
        $totalFigure = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $truckData = DB::table('manifests')
            ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=', 'manifests.id')
            ->where('manifests.manifest', $totalFigure)
            ->where('truck_entry_regs.port_id', $port_id)
            ->where('truck_entry_regs.weightment_flag', '=', 1)
            ->select('truck_entry_regs.id',
                'truck_entry_regs.truck_type',
                'truck_entry_regs.truck_no',
                'truck_entry_regs.goods_id',
                DB::raw('(SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS goods'),
                'truck_entry_regs.driver_name',
                'truck_entry_regs.gweight_wbridge',
                'truck_entry_regs.tweight_wbridge',
                'truck_entry_regs.tr_weight',
                'manifests.manifest')
            ->get();
        if ($truckData == true) {
            return json_encode($truckData);
        } else {
            //return Response::json(['error_db' => 'Database Error'],505);
            return response()->json();
        }
    }

    public function getWeightReportPdf($id)
    {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date("Y-m-d"); //h:i:s a
        $user = Auth::user()->name;
        $weightData = DB::table('truck_entry_regs')
            ->where('truck_entry_regs.id', $id)
            ->where('truck_entry_regs.port_id', $port_id)
            ->select('truck_entry_regs.truck_type',
                'truck_entry_regs.truck_no',
                DB::raw('(SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=truck_entry_regs.goods_id) AS goods'),
                DB::raw('(SELECT manifests.manifest FROM manifests WHERE truck_entry_regs.manf_id=manifests.id) AS manifest'),
                'truck_entry_regs.driver_name',
                'truck_entry_regs.gweight_wbridge',
                'truck_entry_regs.tweight_wbridge',
                'truck_entry_regs.tr_weight',
                'truck_entry_regs.wbrdge_time1',
                'truck_entry_regs.wbrdge_time2',
                'truck_entry_regs.receive_package'
            )
            ->get();
        //return $weightData;

        $pdf = PDF::loadView('default.weighbridge.reports.weight-report', [
            'todayWithTime' => $todayWithTime,
            'weightData' => $weightData,
            'user' => $user
        ])->setPaper([0, 0, 550, 350]);
        // return $pdf->setPaper(500,500)->setOrientation('landscape')->stream('WeightReport.pdf');
        return $pdf->stream('WeightReport.pdf');
    }

    //Datewise WeightbridgeEntry Report
    // public function dateWiseWeightbridgeEntryReportView()
    // {
    //     return view('weightbridge.dateWiseWeightbridgeEntryReportView');
    // }

    //Other Reports
    public function otherReportsView()
    {
        return view('default.weighbridge.other-reports-view');
    }

    public function weighbridgeMonitorView() {
       //dd(Session::get('PORT_ID'));

        return view('default.weighbridge.weighbridge-monitor-view');

    }

    public function getWeighbridgeDetailsForMonitor($date) {
        $port_id = Session::get('PORT_ID');

        
        $data = DB::select('SELECT manifests.id AS manifest_id, manifests.manifest, truck_entry_regs.id AS truck_id, 
        truck_entry_regs.truck_type, truck_entry_regs.truck_no, truck_entry_regs.driver_card,
        (SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.entry_scale) AS entry_scale,
        (SELECT weighbridges.scale_name FROM weighbridges WHERE weighbridges.id = truck_entry_regs.exit_scale) AS exit_scale,
        truck_entry_regs.gweight_wbridge,truck_entry_regs.tr_weight,truck_entry_regs.tweight_wbridge,
        (SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_user1) AS entry_created_by,
        truck_entry_regs.wbrdge_time1 AS entry_created_at,
        (SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_updated_by1) AS entry_updated_by,
        truck_entry_regs.wbridg_updated_at1 AS entry_updated_at,
        (SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_user2) AS exit_created_by,
        truck_entry_regs.wbrdge_time2 AS exit_created_at,
        (SELECT users.name FROM users WHERE users.id = truck_entry_regs.wbridg_updated_by2) AS exit_updated_by,
        truck_entry_regs.wbridg_updated_at2 AS exit_updated_at
        FROM manifests
        JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
        WHERE date(truck_entry_regs.wbrdge_time1)=? AND truck_entry_regs.port_id=? 
        AND manifests.port_id=? 
        ORDER BY time(truck_entry_regs.wbrdge_time1) DESC', [$date, $port_id, $port_id]);


        return json_encode($data);
        

    }

}
