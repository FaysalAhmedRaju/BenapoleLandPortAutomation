<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use PDF;

class CustomsPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function welcome() {
        return view('Customs.welcome');
    }

    public function customsEntryFormView()
    {
        return view('Customs.CustomsEntryFormView');
    }

    
    

    public  function customsVatsDetailsData()
    {
        $vat = DB::table('vatregs')
            ->select('vatregs.NAME','vatregs.BIN')->take(100)->get();
        return json_encode($vat);
    }

    public function customsGoodsDetailsData()
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

    public function searchManifestDataDetails(Request $r) {


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
                'truck_entry_regs.gweight as t_gweight',
                'truck_entry_regs.nweight as t_nweight',
                'truck_entry_regs.driver_card as t_driver_card',
                'truck_entry_regs.driver_name as t_driver_name',

                'truck_entry_regs.weightment_flag as t_weightment_flag',

                'cargo_details.id as c_id',
                'cargo_details.cargo_name as c_cargo_name'
            )
            ->get();

        return json_encode($mainData);

    }
    public function getCustomsVatDetails(Request $req)
    {
        $vat = DB::table('vatregs')
            ->where('BIN',$req->BIN)
            ->select('vatregs.NAME')->get();
        return json_encode($vat);
    }



    public function saveCustomsPostingData(Request $req)
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

//        $currentTime=date('Y-m-d H:i:s');
//
//        DB::table('manifests')
//            ->where('manifest', $req->m_manifest)
//            ->update(
//                [
//                    'manifest' => $req->m_manifest,
//                    'manifest_date' => $req->m_manifest_date,
//                    'goods_id' => $req->m_good_id,
//
//                    'gweight' => $req->m_gweight,
//                    'nweight' => $req->m_nweight,
//                    'package_no' => $req->m_package_no,
//                    'package_type' => $req->m_package_type,
//
//                    'cnf_value' => $req->m_cnf_value,
//                    'exporter_name_addr' => $req->m_exporter_name_addr,
//                    'vat_id' => $req->m_vat_id,
//                    'lc_no' => $req->m_lc_no,
//                    'lc_date' => $req->m_lc_date,
//                    'ind_be_no' => $req->m_ind_be_no,
//                    'ind_be_date' => $req->m_ind_be_date,
//
//                    'created_by'=>Auth::user()->username,
//                    'created_time'=>$currentTime
//                ]
//            );
//
//        DB::table('truck_entry_regs')
//
//            ->where('sl', $req->t_sl)
//            ->update(
//                [
//                    'truck_type' => $req->t_truck_type,
//                    'truck_no' => $req->t_truck_no,
//                    'goods_id' => $req->m_good_id,
//
//                    'driver_card' => $req->t_driver_card,
//                    'driver_name' => $req->t_driver_name,
//
//                    'gweight' => $req->t_gweight,
//                    'nweight' => $req->t_nweight,
//
//                    'posted_by'=>Auth::user()->username,
//                    'posted_time'=>$currentTime
//                ]
//            );
//
//        return 'Successfully Updated';
    }


    public function updateCustomsManifestTruckEntryData(Request $req)
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



    public function deleteCustomsTruckEntryData($i)

    {

//        $file = fopen("Truckentry.txt","w");
//           echo fwrite($file,"Hello ".$i->id);
//           fclose($file);
//           return;


        DB::table('truck_entry_regs')->where('id',$i)->delete();
        return 'success';


    }


    public function getTodaysCustomsPostingReport() {
        $today = date('Y-m-d');
        $todayWithTime = date('Y-m-d h:i:s a');
        $mainData = DB::table('manifests')
            ->where('created_time','LIKE',"%$today%" )
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
                'cargo_details.cargo_name'
//                'vatregs.NAME',
//                'vatregs.ADD1'
            )
            ->get();
        $pdf = PDF::loadView('Customs.todaysCustomsfPostingPdf',[
            'mainData' => $mainData,
            'todayWithTime' => $todayWithTime
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('todaysCnfPosting.pdf');
    }

//    public  function  manifestReportForCnfOnlymanifest($manifest,$truck)
//    {
////        $today = date('Y-m-d');
////
////        $todayWithTime = date('Y-m-d h:i:s a');
//        $totalFigure = (string)$manifest."/".(string)$truck;
//        $mainDataManifest = DB::table('manifests')
//            ->where('manifest', $totalFigure)
//            ->join('cargo_details', 'manifests.goods_id', '=', 'cargo_details.id')
//            ->select(
//                'manifests.id as m_id',
//                'manifests.manifest as m_manifest',
//                'manifests.manifest_date as m_manifest_date',
//                'manifests.marks_no as m_marks_no',
//                'manifests.goods_id as m_good_id',
//                'manifests.gweight as m_gweight',
//                'manifests.nweight as m_nweight',
//                'manifests.package_no as m_package_no',
//                'manifests.package_type as m_package_type',
//                'manifests.cnf_value as m_cnf_value',
//                'manifests.exporter_name_addr as m_exporter_name_addr',
//                'manifests.vat_id as m_vat_id',
//                'manifests.lc_no as m_lc_no',
//                'manifests.lc_date as m_lc_date',
//                'manifests.ind_be_no as m_ind_be_no',
//                'manifests.ind_be_date as m_ind_be_date',
//                'cargo_details.cargo_name'
//            )
//            ->get();
//        $pdf = PDF::loadView('cnf.manifestReportForCnfPDF',[
//            'mainDataManifest' => $mainDataManifest
////            'todayWithTime' => $todayWithTime
//        ])
//        ->setPaper('a4', 'landscape');
//        return $pdf->stream('manifestReportForCnfPDF.blade.pdf');
//    }

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
            ->select('manifests.*','cargo_details.*'/*,'vatregs.*'*/)
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


//        $checkApproveVarification = DB::select(" SELECT
//        (SELECT COUNT(assesment_details.manif_id)
//FROM assesment_details WHERE assesment_details.manif_id=manifests.id  AND assesment_details.approved=0)
// delivery_status
//FROM
//manifests WHERE manifests.manifest=?",[$totalFigure]);

//        $checkApproveVarification = DB::table('manifests')
//            ->join('assesment_details', 'assesment_details.manif_id', '=', 'manifests.id')
//            ->where('manifest',$totalFigure)
//            ->select(
//                'manifests.id as m_id',
//                'manifests.manifest as m_manifest',
//
//                'assesment_details.id as a_id',
//                'assesment_details.manif_id as a_manif_id',
//
//                'assesment_details.verified as a_verified',
//                'assesment_details.verify_comm as a_verify_comm',
//                'assesment_details.approved as a_approved',
//                'assesment_details.approve_comment as a_approve_comment'
//            )
//            ->get();





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
                                         /*   (SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerName,
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
                                          /*  (SELECT vatregs.NAME FROM vatregs WHERE vatregs.BIN=manifests.vat_id ) importerName,
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


//--------------------------------------------------------------Very Important for check DATA----------------------------------------------------------------------

    /* $file = fopen("Truckentry.txt","w");
           echo fwrite($file,"Hello ".$req->truck_type);
           fclose($file);*/

}