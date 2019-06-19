<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\ProjectBaseController;
use App\Models\Assessment\Assessment;
use App\Models\Leave\LeaveApplication;
use App\Models\Truck\TruckEntryReg;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use PDF;

use App\Models\Manifest;

class HomeController extends Controller
{

    public function Index()
    {
        $from = date(Carbon::now());
        $leaveApp=(new LeaveApplication())
            ->whereDate('from','<=', $from)
            ->whereDate('to','>=', $from)
            ->whereStatus('Granted')
            ->get();

        //dd($leaveApp);

        return view('home.index',compact('leaveApp'));
    }

    public function redirectToHome()
    {
        return redirect()->route('/');
    }


    public function saveManifestTruckDataFromServiceLink(Request $request)
    {

        $returnData = array();


        if ($request->token != 'asfsdfgsgrgvfdbv') {
            return $returnData['auth_failed'] = "Authenthication Failed!";
        }


        $manifestData = json_decode($request->manifest);
        $truckData = json_decode($request->truck);


        foreach ($manifestData as $k => $data) {

            $manifest = (new Manifest())
                ->where('manifest', $data->manifest)
                ->where('port_id', $data->port_id)
                ->first();


            if ($manifest) {//manifest  exists

                $manifest->goods_id = $data->goods_id;
                $manifest->cnf_id = $data->cnf_id;
                $manifest->save();
                $returnData['manifest_updated'][$k] = $data->id;

            } else {//new manifest
                \DB::table('manifests')
                    ->insert([
                        'port_id' => $data->port_id,
                        'manifest' => $data->manifest,
                        'goods_id' => $data->goods_id,
                        'cnf_id' => $data->cnf_id,
                        'created_by' => $data->created_by,
                        'created_at' => $data->created_at
                    ]);
                $returnData['manifest_inserted'][$k] = $data->id;

            }
        }


        foreach ($truckData as $key => $value) {

            $manifestFound = DB::table('manifests')
                ->where('manifest', $value->manifest)
                ->where('port_id', $value->port_id)
                ->first();

         /*   $ass=new  Assessment();
            $ass->assessment_values=json_encode($value);*/

        //    \Log::info('new truck inserted and its manif id: '.$manifestFound->id);


            if ($manifestFound) {

                $truckExist = (new TruckEntryReg())
                    ->where('manf_id', $manifestFound->id)
                    ->where('port_id', $manifestFound->port_id)
                    ->where('truck_type', $value->truck_type)
                    ->where('truck_no', $value->truck_no)
                    ->first();


                if ($truckExist) {


                    $truckExist->goods_id = $value->goods_id;
                    $truckExist->truckentry_datetime = $value->truckentry_datetime;
                    $truckExist->created_by = $value->created_by;
                    $truckExist->created_at = $value->created_at;
                    $truckExist->port_id = $value->port_id;


                    //weighbridge
                    $truckExist->gweight_wbridge = $value->gweight_wbridge;
                    $truckExist->tweight_wbridge = $value->tweight_wbridge;
                    $truckExist->tr_weight = $value->tr_weight;
                    $truckExist->wbridg_user1 = $value->wbridg_user1;
                    $truckExist->wbrdge_time1 = $value->wbrdge_time1;


                    $truckExist->wbridg_user2 = $value->wbridg_user2;
                    $truckExist->wbrdge_time2 = $value->wbrdge_time2;
                    $truckExist->save();
                    $returnData['truck_updated'][$key] = $value->id;


                } else {// insert new truck


                    $truck = new TruckEntryReg();


                    //     $this->truckEntryReg->entry_sl = $truck->getTruckSerial($value->manifest, Carbon::now());
                    $truck->goods_id = $value->goods_id;
                    $truck->manf_id = $manifestFound->id;
                    $truck->truck_type = $value->truck_type;
                    $truck->truck_no = $value->truck_no;
                    $truck->truckentry_datetime = $value->truckentry_datetime;
                    $truck->created_by = $value->created_by;
                    $truck->created_at = $value->created_at;
                    $truck->port_id = $value->port_id;
                    $truck->gweight = $value->gweight;

                    //weighbridge
                    $truck->gweight_wbridge = $value->gweight_wbridge;
                    $truck->tweight_wbridge = $value->tweight_wbridge;
                    $truck->tr_weight = $value->tr_weight;
                    $truck->wbridg_user1 = $value->wbridg_user1;
                    $truck->wbrdge_time1 = $value->wbrdge_time1;
                    $truck->wbridg_user2 = $value->wbridg_user2;
                    $truck->wbrdge_time2 = $value->wbrdge_time2;
                    $truck->save();


                    $returnData['truck_inserted'][$key] = $value->id;

                }
            }


        }

        return $returnData;


    }

    public function getMnifestDetailsForPublic(Request $r)
    {

        $manifest_no = $r->manifest_no;
        $todayWithTime = date('Y-m-d h:i:s a');

        $bdTruckData = DB::select("SELECT manifests.manifest,truck_deliverys.* FROM manifests 
                JOIN truck_deliverys  ON truck_deliverys.manf_id = manifests.id
                WHERE manifests.manifest = ?", [$manifest_no]);

        $indianTruckData = DB::select("SELECT manifests.manifest,truck_entry_regs.*, SUM(IFNULL(shed_yard_weights.unload_labor_weight, 0)) AS unload_labor_weight,
                SUM(IFNULL(shed_yard_weights.unload_equip_weight, 0)) AS unload_equip_weight, GROUP_CONCAT(DISTINCT shed_yard_weights.unload_equip_name SEPARATOR ', ') AS unload_equip_name,
                GROUP_CONCAT(DISTINCT DATE(shed_yard_weights.unload_receive_datetime) SEPARATOR ', ') AS unload_receive_datetime
                FROM manifests 
                LEFT JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id
                LEFT JOIN  shed_yard_weights ON truck_entry_regs.id = shed_yard_weights.truck_id
                WHERE manifests.manifest =?", [$manifest_no]);
        $manifestDetails = DB::select("SELECT manifests.*,cargo_details.*,vatregs.*,truck_entry_regs.*,manifests.gweight AS manif_gweight,manifests.nweight AS manif_nweight,
                yard_details.yard_shed_name AS posted_yard_shed FROM manifests 
                LEFT JOIN cargo_details  ON cargo_details.id = manifests.goods_id
                LEFT JOIN truck_entry_regs  ON truck_entry_regs.manf_id = manifests.id
                LEFT JOIN vatregs  ON vatregs.id = manifests.vatreg_id
                LEFT JOIN yard_details  ON yard_details.id = manifests.posted_yard_shed
                WHERE manifests.manifest =?", [$manifest_no]);
        $manifestRequitionDetails = DB::select('SELECT dr.* FROM delivery_requisitions AS dr
                 JOIN manifests AS m ON m.id = dr.manifest_id
                 WHERE m.manifest = ?
                 ORDER BY dr.id DESC LIMIT 1',[$manifest_no]);


        if ($bdTruckData == [] && $indianTruckData == [] && $manifestDetails == []) {
            return view('home.notFoundHome');
        } else {
            return view('home.manifest-details-PDF', [
                'bdTruckData' => $bdTruckData,
                'indianTruckData' => $indianTruckData,
                'todayWithTime' => $todayWithTime,
                'manifestDetails' => $manifestDetails,
                'manifest' => $manifestDetails[0]->manifest,
                'cargo_name' => $manifestDetails[0]->cargo_name,
                'package_no' => $manifestDetails[0]->package_no,
                'exporter_name_addr' => $manifestDetails[0]->exporter_name_addr,
                'importer_name_addr' => $manifestDetails[0]->NAME,
                'cnf_name' => $manifestDetails[0]->cnf_name,
                'manifestGrossWeight' => $manifestDetails[0]->manif_gweight,
                'manifestNetWeight' => $manifestDetails[0]->manif_nweight,
                'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,
                'manifest_date' => $manifestDetails[0]->manifest_date,
                'gate_pass_no' => count($manifestRequitionDetails) > 0 ? $manifestRequitionDetails[0]->gate_pass_no : '',
                'be_no' => $manifestDetails[0]->be_no,
                'custom_release_order_no' => $manifestDetails[0]->custom_release_order_no,
                'manifestNo' => $manifest_no

            ]);

        }

        /* $pdf = PDF::loadView('home.manifest-details-PDF',[
             'bdTruckData' => $bdTruckData,
             'indianTruckData' => $indianTruckData,
             'todayWithTime' => $todayWithTime,
             'manifestDetails' => $manifestDetails,
             'manifest' => $manifestDetails[0]->manifest,
             'cargo_name' => $manifestDetails[0]->cargo_name,
             'package_no' => $manifestDetails[0]->package_no,
             'exporter_name_addr' => $manifestDetails[0]->exporter_name_addr,
             'importer_name_addr' => $manifestDetails[0]->NAME,
             'cnf_name' => $manifestDetails[0]->cnf_name,
             'manifestGrossWeight' => $manifestDetails[0]->manif_gweight,
             'manifestNetWeight' => $manifestDetails[0]->manif_nweight,
             'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,
             'manifest_date' => $manifestDetails[0]->manifest_date,
            'gate_pass_no' => $manifestDetails[0]->gate_pass_no,
             'be_no' => $manifestDetails[0]->be_no,
             'custom_release_order_no' => $manifestDetails[0]->custom_release_order_no,
             'manifestNo' => $totalFigure
         ])->setPaper([0, 0, 1000.661, 800.63], 'landscape');

         return $pdf->stream('manifestDetailsPDF.pdf');*/
    }

}
