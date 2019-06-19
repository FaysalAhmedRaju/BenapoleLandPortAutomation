<?php

namespace App\Http\Controllers\Transshipment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use PDF;
use Response;

class TransshipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    public function welcome() {
        $port_id = Session::get('PORT_ID');
        $today = date('Y-m-d');
        $countTranshipmentUser = DB::table('users')
                                ->where('users.role_id', 12)
                                // ->where('users.port_id', $port_id)
                                ->count('users.id');
        //dd($countTranshipmentUser);
        $countTodaysTruckEntry = DB::select('SELECT COUNT(truck_entry_regs.id) AS todaysTruckentryByTranshipment
FROM truck_entry_regs
JOIN manifests ON manifests.id = truck_entry_regs.manf_id
JOIN shed_yard_weights AS syw ON syw.truck_id = truck_entry_regs.id
                                    WHERE DATE(syw.unload_receive_datetime)=?
                                    AND truck_entry_regs.port_id=? AND manifests.port_id=?
                                    AND manifests.transshipment_flag = 1',[$today, $port_id, $port_id]);

        $countTodaysTruckExit = DB::select('SELECT COUNT(truck_deliverys.id) 
                                        AS todaysTruckexitByTranshipment
                                        FROM truck_deliverys
                                        JOIN manifests ON manifests.id = truck_deliverys.manf_id  
                                        WHERE DATE(truck_deliverys.exit_dt)=? AND manifests.transshipment_flag=1 AND manifests.port_id=? AND truck_deliverys.port_id=?',[$today, $port_id, $port_id]);

        $countTodaysAssessmentDone = DB::select('SELECT COUNT(DISTINCT assessments.manifest_id) 
                                                AS todaysAssessmentTranshipment
                                                FROM assessments
                                                WHERE DATE(assessments.created_at) = ? AND assessments.transshipment_flag = 1 AND assessments.done=1 AND assessments.port_id=?',[$today, $port_id]);
        return view('default.transshipment.welcome',compact('countTranshipmentUser', 'countTodaysTruckEntry','countTodaysTruckExit', 'countTodaysAssessmentDone'));
    }

    public function TransShipEntryForm()
    {
        return view('transshipment.TransShipEntryForm');
    }


    public  function TransVatsJson()
    {
        $vat = DB::table('vatregs')
            ->select('vatregs.NAME','vatregs.BIN')->take(100)->get();
        return json_encode($vat);
    }

    public function TransGoodsJson()
    {
        $goods = DB::table('cargo_details')
            ->select('cargo_details.id','cargo_details.cargo_name')->get();
        return json_encode($goods);
    }


    public  function  expoterName()
    {
        $expotersName = DB::table('manifests')
            ->select('manifests.id','manifests.exporter_name_addr')->get();
        return json_encode($expotersName);
    }

    public function searchManifestJsonTrans(Request $r) {

        $mainData = DB::table('manifests')->where('manifest',$r->ManifestNo)
            ->join('truck_entry_regs', 'manifests.id', '=','truck_entry_regs.manf_id')
            ->join('cargo_details', 'truck_entry_regs.goods_id', '=', 'cargo_details.id')
            ->select(
                'manifests.id as m_id',
                'manifests.manifest as m_manifest',
                'manifests.manifest_date as m_manifest_date',

                'manifests.marks_no as m_marks_no',

                'manifests.goods_id as m_good_id',
                'manifests.gweight as m_gweight',
                'manifests.nweight as m_nweight',
                'manifests.package_no as m_package_no',
                'manifests.package_type as m_package_type',
                'manifests.cnf_value as m_cnf_value',
                'manifests.exporter_name_addr as m_exporter_name_addr',
//                'manifests.vat_id as m_vat_id',
                'manifests.lc_no as m_lc_no',
                'manifests.lc_date as m_lc_date',
                'manifests.ind_be_no as m_ind_be_no',
                'manifests.ind_be_date as m_ind_be_date',

                'truck_entry_regs.id as t_id',
                'truck_entry_regs.truck_type as t_truck_type',
                'truck_entry_regs.truck_no as t_truck_no',
                'truck_entry_regs.manf_id as t_manf_id',
                'truck_entry_regs.goods_id as t_goods_id',
//                'truck_entry_regs.gweight as t_gweight',
//                'truck_entry_regs.nweight as t_nweight',
                'truck_entry_regs.driver_card as t_driver_card',
                'truck_entry_regs.driver_name as t_driver_name',

                'truck_entry_regs.weightment_flag as t_weightment_flag',

                'cargo_details.id as c_id',
                'cargo_details.cargo_name as c_cargo_name'
            )
            ->get();
        return json_encode($mainData);

    }
    public function TransVatDetails(Request $req)
    {
        $vat = DB::table('vatregs')
            ->where('BIN',$req->BIN)
            ->select('vatregs.NAME')->get();
        return json_encode($vat);
    }



    public function TransPostingJson(Request $req)
    {

        $manifestsTb = DB::table('manifests')
            ->where('manifest',$req->m_manifest)
            ->get();

        $portId = DB::table('users')->where('username', Auth::user()->username)->first();

        if($manifestsTb == '[]') {

            $currentTime = date('Y-m-d H:i:s');

            $maniId = DB::table('manifests')->insertGetId(
                [
                    'manifest' => $req->m_manifest,
                    'manifest_date' => $req->m_manifest_date,

                    'marks_no' => $req->m_marks_no,

                    'goods_id' => $req->m_good_id,
                    'gweight' => $req->m_gweight,
                    'nweight' => $req->m_nweight,
                    'package_no' => $req->m_package_no,
                    'package_type' => $req->m_package_type,
                    'cnf_value' => $req->m_cnf_value,
                    'exporter_name_addr' => $req->m_exporter_name_addr,
                    'vat_id' => $req->m_vat_id,
                    'lc_no' => $req->m_lc_no,
                    'lc_date' => $req->m_lc_date,
                    'ind_be_no' => $req->m_ind_be_no,
                    'ind_be_date' => $req->m_ind_be_date,
                    'port_id'=>$portId->port_id,
                    'created_by' => Auth::user()->username,
                    'created_time' => $currentTime


                ]
            );
            DB::table('truck_entry_regs')->insert(
                [
                    'truck_type' => $req->t_truck_type,
                    'truck_no' => $req->t_truck_no,
                    'goods_id' => $req->m_good_id,

                    'manf_id' => $maniId,

                    'driver_card' => $req->t_driver_card,
                    'driver_name' => $req->t_driver_name,
                    'gweight' => $req->t_gweight,
                    'nweight' => $req->t_nweight,
                    'weightment_flag' => $req->t_weightment_flag,
                    'created_by' => Auth::user()->username,
                    'truckentry_datetime' => date('Y-m-d H:i:s')

//            'weightment_flag'=>$req->weightment_flag,


                ]

            );


            return "new manifest";

        }
        else
        {

            $manifest = DB::table('manifests')->where('manifest',$req->m_manifest)->first();

            DB::table('truck_entry_regs')->insert(
                [
                    'truck_type' => $req->t_truck_type,
                    'truck_no' => $req->t_truck_no,
                    'goods_id' => $req->m_good_id,

                    'manf_id' => $manifest->id,


                    'driver_card' => $req->t_driver_card,
                    'driver_name' => $req->t_driver_name,
                    'gweight' => $req->t_gweight,
                    'nweight' => $req->t_nweight,
                    'weightment_flag' => $req->t_weightment_flag,
                    'created_by' => Auth::user()->username,
                    'truckentry_datetime' => date('Y-m-d H:i:s')

//            'weightment_flag'=>$req->weightment_flag,


                ]

            );


            return "old manifest";


        }

        return response()->json();

    }


    public function TransManifestTruckEntry(Request $req)
    {

        DB::table('manifests')
            ->where('manifest', $req->m_manifest)
            ->update(
                [
                    'manifest' => $req->m_manifest,
                    'manifest_date' => $req->m_manifest_date,
                    'marks_no' => $req->m_marks_no,
                    'goods_id' => $req->m_good_id,
                    'gweight' => $req->m_gweight,
                    'nweight' => $req->m_nweight,
                    'package_no' => $req->m_package_no,
                    'package_type' => $req->m_package_type,
                    'cnf_value' => $req->m_cnf_value,
                    'exporter_name_addr' => $req->m_exporter_name_addr,
                    'vat_id' => $req->m_vat_id,
                    'lc_no' => $req->m_lc_no,
                    'lc_date' => $req->m_lc_date,
                    'ind_be_no' => $req->m_ind_be_no,
                    'ind_be_date' => $req->m_ind_be_date
                ]
            );

        DB::table('truck_entry_regs')
            ->where('id', $req->t_id)
            ->update(
                [
                    'truck_type' => $req->t_truck_type,
                    'truck_no' => $req->t_truck_no,
//                    'goods_id' => $req->m_good_id,

                    'driver_card' => $req->t_driver_card,
                    'driver_name' => $req->t_driver_name,

                    'gweight' => $req->t_gweight,
                    'nweight' => $req->t_nweight,
                    'weightment_flag' => $req->t_weightment_flag
                ]
            );

        return 'Successfully Updated Manifest';


    }



    public function TransTruckEntry($i)

    {

//        $file = fopen("Truckentry.txt","w");
//           echo fwrite($file,"Hello ".$i->id);
//           fclose($file);
//           return;


        DB::table('truck_entry_regs')->where('id',$i)->delete();
        return 'success';


    }


    public function todaysTransshipPostingReport() {
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $user = Auth::user()->username;
        $mainData = DB::table('manifests')
            ->where('created_time','LIKE',"%$today%" )
            ->where('manifests.created_by','LIKE',"%$user%" )
           // ->where('created_by',$user)
            ->join('cargo_details', 'manifests.goods_id', '=', 'cargo_details.id')
//            ->join('vatregs', 'vatregs.BIN', '=','manifests.vat_id')
            ->select(
                'manifests.id as m_id',
                'manifests.manifest as m_manifest',
                'manifests.manifest_date as m_manifest_date',
                'manifests.marks_no as m_marks_no',
                'manifests.goods_id as m_good_id',
                'manifests.gweight as m_gweight',
                'manifests.nweight as m_nweight',
                'manifests.package_no as m_package_no',
                'manifests.package_type as m_package_type',
                'manifests.cnf_value as m_cnf_value',
                'manifests.exporter_name_addr as m_exporter_name_addr',
//                'manifests.vat_id as m_vat_id',
                'manifests.lc_no as m_lc_no',
                'manifests.lc_date as m_lc_date',
                'manifests.ind_be_no as m_ind_be_no',
                'manifests.ind_be_date as m_ind_be_date',
                'cargo_details.cargo_name',
                'vatregs.NAME',
                'vatregs.ADD1'
            )
            ->get();
        $pdf = PDF::loadView('transshipment.todaysTransshipPosting',[
            'mainData' => $mainData,
            'todayWithTime' => $todayWithTime
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('todaysTransshipPosting.pdf');
    }


    public function manifestReportForCnf($manifest,$truck) {

        $totalFigure = (string)$manifest."/".(string)$truck;
        $todayWithTime = date('Y-m-d h:i:s a');
        $bdTruckData = DB::table('manifests')
            ->join('truck_deliverys', 'truck_deliverys.manf_id', '=','manifests.id')
            ->where('manifest',$totalFigure)
            ->select('manifests.manifest','truck_deliverys.*')
            ->get();
        $indianTruckData = DB::table('manifests')
            ->join('truck_entry_regs', 'truck_entry_regs.manf_id', '=','manifests.id')
            ->where('manifest',$totalFigure)
            ->select('manifests.manifest','truck_entry_regs.*')
            ->get();
        $manifestDetails = DB::table('manifests')
            ->join('cargo_details', 'cargo_details.id', '=','manifests.goods_id')
//            ->join('vatregs', 'vatregs.BIN', '=','manifests.vat_id')
            ->where('manifest',$totalFigure)
            ->select('manifests.*','cargo_details.*')
            ->get();

        $checkApproveVarification = DB::select(" SELECT DISTINCT assesment_details.manif_id,
                (
                CASE 
                WHEN verified='0'  THEN 'Assessment Pending'
                WHEN verified='1'  AND  approved='0' THEN 'Assessment Processing'
                WHEN approved='1'  THEN 'Assessment Done'
                ELSE NULL
                END
                ) AS assessmet_status
                
                FROM manifests 
                INNER JOIN assesment_details ON assesment_details.manif_id=manifests.id
                WHERE manifests.manifest=?",[$totalFigure]);






        $pdf = PDF::loadView('cnf.manifestReportForCnfPDF',
            [
                'manifestDetails' => $manifestDetails,
                'todayWithTime' => $todayWithTime,
                'manifestNo' => $totalFigure,
                'bdTruckData' => $bdTruckData,
                'indianTruckData' => $indianTruckData,
                'checkApproveVarification' => $checkApproveVarification

            ])
            ->setPaper('a4', 'landscape');

        return $pdf->stream('manifestReportForCnfPDF.blade.pdf');
    }





    public function ImporterWiseReport() {
        return view('cnf.ImporterWiseReport');
    }

//    public function getImporterWiseReportPDF(Request $r) {
//        //return $r->importerNo;
//        $todayWithTime = date('Y-m-d h:i:s a');
//        $importerWiseManifestReport = DB::select("SELECT manifests.manifest, manifests.manifest_date,
//                                                manifests.gweight, manifests.nweight, manifests.package_no,manifests.package_type, manifests.cnf_value, manifests.exporter_name_addr, manifests.lc_no, manifests.lc_date,manifests.be_no, manifests.be_date, manifests.ind_be_no, manifests.ind_be_date,
//                                            (SELECT count(truck_entry_regs.truck_no) FROM truck_entry_regs WHERE truck_entry_regs.manf_id=manifests.id ) foreignTruck,
//                                            (SELECT count(truck_deliverys.truck_no) FROM truck_deliverys WHERE truck_deliverys.manf_id=manifests.id ) localTruck,
//                                            (SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerName,
//                                            (SELECT vatregs.ADD1 FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerAddress,
//                                            (SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=manifests.goods_id ) goodsName
//                                            FROM manifests
//                                            WHERE manifests.vat_id=?
//                                            GROUP BY manifest",[$r->importerNo]);
//        //return $importerWiseManifestReport;
//        $pdf = PDF::loadView('cnf.ImporterWisePDFReport',[
//            'todayWithTime' => $todayWithTime,
//            'importerWiseManifestReport' => $importerWiseManifestReport,
//            'importerNo' => $r->importerNo
//        ])
//            ->setPaper('a4', 'landscape');
//        //return $pdf->download('user.pdf');
//        return $pdf->stream('ImporterWisePDFReport.pdf');
//    }

    public function CargoWiseReport() {
        return view('cnf.CargoWiseReport');
    }

    public function getCargoWiseReportPDF(Request $r) {
        $goods_id = filter_var( $r->goods_id, FILTER_SANITIZE_NUMBER_INT);
        //return $goods_id;
        $todayWithTime = date('Y-m-d h:i:s a');
        $cargoWiseManifestReport = DB::select("SELECT manifests.manifest, manifests.manifest_date, 
                                                manifests.gweight, manifests.nweight, manifests.package_no,manifests.package_type, manifests.cnf_value, manifests.exporter_name_addr, manifests.lc_no, manifests.lc_date,manifests.be_no, manifests.be_date, manifests.ind_be_no, manifests.ind_be_date, 
                                            (SELECT count(truck_entry_regs.truck_no) FROM truck_entry_regs WHERE truck_entry_regs.manf_id=manifests.id ) foreignTruck,
                                            (SELECT count(truck_deliverys.truck_no) FROM truck_deliverys WHERE truck_deliverys.manf_id=manifests.id ) localTruck
                                       /*  (SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerName,
                                           (SELECT vatregs.ADD1 FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerAddress*/
                                            FROM manifests 
                                            WHERE manifests.goods_id=?
                                            GROUP BY manifest",[$goods_id]);
        //return $cargoWiseManifestReport;
        $cargo_description = DB::select("SELECT cargo_details.cargo_name goodsName
                                        FROM cargo_details 
                                        WHERE cargo_details.id=?",[$goods_id]);

        $pdf = PDF::loadView('cnf.CargoWisePDFReport',[
            'todayWithTime' => $todayWithTime,
            'cargoWiseManifestReport' => $cargoWiseManifestReport,
            'cargo_description' => $cargo_description
        ])
            ->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('CargoWisePDFReport.pdf');
    }

    public function dateWiseReport() {
        return view('cnf.DateWiseReport');
    }

    public function getDateWiseReportPDF(Request $r) {
        //return $r->from_date." ".$r->to_date;
        $todayWithTime = date('Y-m-d h:i:s a');
        $dateWiseManifestReport = DB::select("SELECT manifests.manifest, manifests.manifest_date, 
                                                manifests.gweight, manifests.nweight, manifests.package_no,manifests.package_type, manifests.cnf_value, manifests.exporter_name_addr, manifests.lc_no, manifests.lc_date,manifests.be_no, manifests.be_date, manifests.ind_be_no, manifests.ind_be_date, 
                                            (SELECT count(truck_entry_regs.truck_no) FROM truck_entry_regs WHERE truck_entry_regs.manf_id=manifests.id ) foreignTruck,
                                            (SELECT count(truck_deliverys.truck_no) FROM truck_deliverys WHERE truck_deliverys.manf_id=manifests.id ) localTruck,
                                      /*      (SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerName,
                                            (SELECT vatregs.ADD1 FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerAddress,*/
                                            (SELECT cargo_details.cargo_name FROM cargo_details WHERE cargo_details.id=manifests.goods_id ) goodsName
                                            FROM manifests 
                                            WHERE manifests.manifest_date BETWEEN ? AND ?
                                            GROUP BY manifest",[$r->from_date, $r->to_date]);
        //return $dateWiseManifestReport;
        $pdf = PDF::loadView('cnf.DateWisePDFReport',[
            'todayWithTime' => $todayWithTime,
            'dateWiseManifestReport' => $dateWiseManifestReport,
            'from_date' => $r->from_date,
            'to_date' => $r->to_date
        ])
            ->setPaper('a4', 'landscape');
        //return $pdf->download('user.pdf');
        return $pdf->stream('DateWisePDFReport.pdf');
    }

    //Perishable Items Start
    public function PerishableItemsView() {
        return view('transshipment.perishableItemsView');
    }

    public function getAllItems() {
        $getAllItems = DB::table('item_codes')
                            ->select('item_codes.id',
                                    'item_codes.Description'
//                                    'item_codes.perishable_flag',
                                    /*'item_codes.perishable_flag_created_at'*/)
                            ->get();
        return json_encode($getAllItems);
    }
    public function savePerishableItem(Request $r) {
        $user = Auth::user()->id;
        $time = date('Y-m-d H:i:s');
        if($r->perishable_flag_created_at == "") {
            $savePerishableItem = DB::table('item_codes')
                                    ->where('item_codes.id', $r->id)
                                    ->update([
                                        'item_codes.perishable_flag' => $r->perishable_flag
//                                        'item_codes.perishable_flag_created_by' => $user,
//                                      /*  'item_codes.perishable_flag_created_at' => $time*/
                                        ]);
            if($savePerishableItem) {
                return Response::json(['massage' => 'Successfully Inserted.'], 200); 
            }

        } else {
           $savePerishableItem = DB::table('item_codes')
                                    ->where('item_codes.id', $r->id)
                                    ->update([
                                        'item_codes.perishable_flag' => $r->perishable_flag
//                                        'item_codes.perishable_flag_updated_by' => $user,
//                                        'item_codes.perishable_flag_updated_at' => $time
                                        ]);
            if($savePerishableItem) {
               return Response::json(['massage' => 'Successfully Updated.'], 201); 
            }
            
        }
    }
    ////Perishable Items End


}
