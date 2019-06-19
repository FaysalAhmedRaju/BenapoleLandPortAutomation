<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\AssessmentBaseController;
use App\Http\Controllers\GlobalFunctionController;
use App\Models\Assessment\Assessment;
use App\Models\Goods;
use App\Models\Item\ItemCode;
use App\Models\Item\ItemDetail;
use App\Models\SubHead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Port;

use App\Models\Manifest;
use App\User;
use App\Models\Truck\TruckEntryReg;
use App\Models\Assessment\AssessmentDetails;
use  App\Models\Assessment\AssessmentDocument;


use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DB;

class AssessmentController extends Controller
{

    private $assessment;
    private $manifest;
    private $truckEntryReg;
    private $assessmentDetails;
    private $subHead;
    private $itemDetail;
    private $itemCode;
    private $goods;
    private $globalFunctionController;
    private $assessmentBaseController;
    private $assessmentDocument;
    private $user;



    public function __construct(GlobalFunctionController $globalFunctionController, Manifest $manifest,
                                TruckEntryReg $truckEntryReg, AssessmentBaseController $assessmentBaseController,
                                AssessmentDetails $assessmentDetails, SubHead $subHead,
                                ItemDetail $itemDetail, Goods $goods, ItemCode $itemCode,
                                Assessment $assessment,AssessmentDocument $assessmentDocument, User $user)
    {
        $this->middleware('auth');
        $this->globalFunctionController = $globalFunctionController;
        $this->assessmentBaseController = $assessmentBaseController;


        $this->manifest = $manifest;
        $this->truckEntryReg = $truckEntryReg;
        $this->assessmentDetails = $assessmentDetails;
        $this->subHead = $subHead;
        $this->itemDetail = $itemDetail;
        $this->goods = $goods;
        $this->itemCode = $itemCode;

        $this->assessmentDocument = $assessmentDocument;
        $this->user = $user;

        $this->assessment = $assessment;
    }


    public function assessmentEdit($id, $status)
    {
       // dd($status);//id=18289  status=0
        try {
            $viewTitle = 'Assessment Edit Form';
            $partial_status = $status;
            $manifest = $this->manifest->findOrFail($id);
            $theAssessment = $this->assessmentDetails->whereManifId($id)->wherePartialStatus($status)->get();
            $assessmentDocumentCharge = $this->assessmentDocument->whereManifestId($id)->wherePartialStatus($status)->get();
            $subHeadList = $this->subHead->all();
            $itemDetails = $this->itemDetail->whereManfId($id)->get();
           // dd($itemDetails);
            $itemCodes = $this->itemCode->all();
            $userData = $this->user->all();
            $goods = $this->goods->where('id', '<=', 11)->get();

            $get_warehouse_details = $this->assessment->wherePartialStatus($status)->whereManifestId($id)->orderBy('id', 'DESC')->select('warehouse_details')->first();
            $warehouseCharge = json_decode($get_warehouse_details['warehouse_details'], true);
            $totalWarehouseCharge = $this->assessmentDetails->whereManifId($id)->whereSubHeadId(2)->wherePartialStatus($status)->select('tcharge')->get();
            $assessmentOnly = $this->assessment->whereManifestId($id)->wherePartialStatus($status)->OrderBy('id','DESC')->first();


            return view('maintenance.assessment.assessment-edit', compact(
                'viewTitle', 'theAssessment', 'subHeadList', 'warehouseCharge',
                'totalWarehouseCharge', 'itemDetails', 'goods', 'itemCodes',
                'partial_status', 'assessmentDocumentCharge', 'userData','assessmentOnly'));

        } catch (Exception $exception) {
            return back()->withError('The Truck Not Found!');
        }
    }



    public function updateAssessmment($id, Request $req)
    {
        $this->validate($req, [
            'charge_per_unit' => 'required',
            'unit' => 'required',
            'sub_head_id' => 'required'
        ]);

        $theAssessment = $this->assessmentDetails->findOrFail($id);

        if ($theAssessment) {

            if (isset($theAssessment->ownFields)) {
                foreach ($theAssessment->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $theAssessment->{$ownfield} = $req->{$ownfield};
                    }
                }
            }

            if ($theAssessment->save()) {
                return \Redirect::route('maintenance-assessment-edit-form', [$theAssessment->manif_id, $req->partial_status,])->withSuccess('Successfully Updated Assessment Details');
            }
            return back()->withError('Something Went Wrong To Update Assessment');
        }
        return back()->withError('The Assessment Not Found!');
//        return view('maintenance.truck.edit', compact('viewTitle', 'theTruck'));
    }

    public  function updateAssessmmentsData($id,Request $req){

        $this->validate($req, [
            'created_by' => 'required',
            'created_at' => 'required'
        ]);

        $assessmentsDetailsData = $this->assessment->findOrFail($id);
      // dd($assessmentsDetailsData->created_by);

        if($assessmentsDetailsData){

            if (isset($assessmentsDetailsData->ownFields)) {
                foreach ($assessmentsDetailsData->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $assessmentsDetailsData->{$ownfield} = $req->{$ownfield};
                    }
                }
            }

                if ($assessmentsDetailsData->save()) {
                    return \Redirect::route('maintenance-assessment-edit-form', [$assessmentsDetailsData->manifest_id, $req->partial_status,])->withSuccess('Successfully Updated Assessment');
                }
//            }
            return back()->withError('Something Went Wrong To Update Assessment');

        }

        return back()->withError('The Assessment Not Found!');

    }


    public  function updateAssessmmentDocumentationCharge($id, Request $req){

        $this->validate($req, [
            'document_name' => 'required',
            'number_of_document' => 'required',
            'document_charge' => 'required'
        ]);

        $documentCharge = $this->assessmentDocument->findOrFail($id);
           // dd($documentCharge);
        if($documentCharge){
            $user = Auth::user()->id;
            $time = date('Y-m-d H:i:s');
            $documentCharge->created_by = $user;
            $documentCharge->created_at = $time;

            if (isset($documentCharge->ownFields)) {
                foreach ($documentCharge->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $documentCharge->{$ownfield} = $req->{$ownfield};
                    }
                }
            }




            if ($documentCharge->save()) {

                $document_no = $documentCharge->number_of_document;
                $document_charge = $documentCharge->document_charge;
               $manifId = $documentCharge->manifest_id;
                $totalCharge = ceil($document_no * $document_charge);

                //dd($req->partial_status);


                $assessmentDetails = $this->assessmentDetails->whereSubHeadId(52)
                    ->whereManifId($manifId)
                    ->wherePartialStatus($req->partial_status)->first();

               // dd($assessmentDetails);

                $assessmentDetails->tcharge = $totalCharge;
                $assessmentDetails->charge_per_unit = $documentCharge->document_charge;
                $assessmentDetails->unit = $documentCharge->number_of_document;



                if ($assessmentDetails->save()) {
                    return \Redirect::route('maintenance-assessment-edit-form', [$documentCharge->manifest_id, $req->partial_status,])->withSuccess('Successfully Updated Document Charge');
                }
            }
            return back()->withError('Something Went Wrong To Update Assessment');

        }

        return back()->withError('The Assessment Not Found!');

    }




    public  function deleteAssessmmentDocumentationCharge($id){

//        $this->validate($req, [
//            'document_name' => 'required',
//            'number_of_document' => 'required',
//            'document_charge' => 'required'
//        ]);

        $documentCharge = $this->assessmentDocument->findOrFail($id);
        $manifId = $documentCharge->manifest_id;

        $assessmentDetails = $this->assessmentDetails->whereSubHeadId(52)
            ->whereManifId($manifId)
            ->wherePartialStatus('0')->first();

        if($documentCharge->delete()){


                if ($assessmentDetails->delete()) {
                    return \Redirect::route('maintenance-assessment-edit-form', [$documentCharge->manifest_id, 0,])->withSuccess('Successfully Deleted Document Charge');
                }

            return back()->withError('Something Went Wrong To Delete Document Charge');

        }

        return back()->withError('The Assessment Not Found!');

    }
    public  function saveAssessmmentDocumentationCharge(Request $req){

//        $this->validate($req, [
//
//        ]);

            if (isset($this->assessmentDocument->ownFields)) {
                foreach ($this->assessmentDocument->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $this->assessmentDocument->{$ownfield} = $req->{$ownfield};
                    }
                }

                $user = Auth::user()->id;
                $time = date('Y-m-d H:i:s');
                $this->assessmentDocument->created_by = $user;
                $this->assessmentDocument->created_at = $time;
            }


            if ($this->assessmentDocument->save()) {

                $document_no = $this->assessmentDocument->number_of_document;
                $document_charge = $this->assessmentDocument->document_charge;
                $manifId = $this->assessmentDocument->manifest_id;
                $totalCharge = ceil($document_no * $document_charge);


                $this->assessmentDetails->manif_id = $manifId;
                $this->assessmentDetails->sub_head_id = 52;
                $this->assessmentDetails->tcharge = $totalCharge;
                $this->assessmentDetails->charge_per_unit = $this->assessmentDocument->document_charge;
                $this->assessmentDetails->unit = $this->assessmentDocument->number_of_document;
                $this->assessmentDetails->partial_status = $req->partial_status;


                if ($this->assessmentDetails->save()) {
                    return \Redirect::route('maintenance-assessment-edit-form', [$this->assessmentDocument->manifest_id, $req->partial_status,])->withSuccess('Successfully Saved Document Charge');
                }
            }
            return back()->withError('Something Went Wrong To Saved Document');



    }
    public function saveWarehouseCharge(Request $req) {
        $this->validate($req, [

        ]);

        $port_id= Session::get('PORT_ID');
        if(isset($this->itemDetail->ownFields)) {
            foreach ($this->itemDetail->ownFields as $ownfield) {
                if ($req->{$ownfield}) {
                    $this->itemDetail->{$ownfield} = $req->{$ownfield};
                }
            }
            $this->itemDetail->dangerous = $req->dangerous;
            $this->itemDetail->yard_shed = $req->yard_shed;
            $this->itemDetail->port_id = $port_id;
        }
        if(!empty($this->itemDetail)) {
            $save_item = $this->itemDetail->save();
        } else {
            return back()->withError('Something Went Wrong To Update Assessment');
        }

        $assessmentWarehouse = $this->assessmentDetails->whereSubHeadId(2)
            ->whereManifId($this->itemDetail->manf_id)
            ->wherePartialStatus($req->partial_status)->first();

        $assessment = $this->assessment->whereManifestId($this->itemDetail->manf_id)->wherePartialStatus($req->partial_status)->OrderBy('id','DESC')->first();
        $totalCharge = 0;

        if($save_item) {
            if($assessmentWarehouse) {//assessment did already then update

                $warehouse_details = $this->assessmentBaseController->getWarehouseDetails($this->itemDetail->manifest->manifest);

                $warehouseCharge = json_decode($warehouse_details, true);
                $wareHouseRentDay = $warehouseCharge['warehouse_rent_day'];
                $assessment->warehouse_details = $warehouse_details;

                if ($warehouseCharge['item_wise_shed_details_charge']) {
                    $danger = 1;
                    foreach ($warehouseCharge['item_wise_shed_details_charge'] as $k => $shed) {
                        if ($shed['dangerous'] == '1') {
                            $danger = 2;
                        }
                        if ($warehouseCharge['first_slab_day']) {
                            $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $shed['first_slab']);
                        }
                        if ($warehouseCharge['second_slab_day']) {
                            $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $shed['second_slab']);
                        }
                        if ($warehouseCharge['third_slab_day']) {
                            $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $shed['third_slab']);
                        }
                    }
                }
                if($warehouseCharge['item_wise_yard_details_charge']) {
                    $danger = 1;
                    foreach ($warehouseCharge['item_wise_yard_details_charge'] as $k => $yard) {

                        if ($yard['dangerous'] == '1') {
                            $danger = 2;
                        }
                        if ($warehouseCharge['first_slab_day']) {
                            $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $yard['first_slab']);
                        }
                        if ($warehouseCharge['second_slab_day']) {
                            $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $yard['second_slab']);
                        }
                        if ($warehouseCharge['third_slab_day']) {
                            $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $yard['third_slab']);
                        }

                    }
                }

                $assessmentWarehouse->other_unit = $wareHouseRentDay;
                $assessmentWarehouse->tcharge = $totalCharge;
                if ($assessmentWarehouse->save() && $assessment->save()) {
                    return \Redirect::route('maintenance-assessment-edit-form', [$this->itemDetail->manf_id, 0,])->withSuccess('Successfully Updated The Warehouse');
                }
            }
            return \Redirect::route('maintenance-assessment-edit-form', [$this->itemDetail->manf_id, 0,])->withSuccess('Successfully Updated Only Item Details');
        }
        return back()->withError('Something Went Wrong To Update Assessment');
    }


    public function updateWarehouseCharge($id, Request $req)
    {
        $this->validate($req, [

        ]);
        $theItemDetails = $this->itemDetail->findOrFail($id);
        $assessmentWarehouse = $this->assessmentDetails->whereSubHeadId(2)
            ->whereManifId($theItemDetails->manf_id)
            ->wherePartialStatus($req->partial_status)->first();
        //dd($assessmentWarehouse);

        $assessment = $this->assessment->whereManifestId($theItemDetails->manf_id)->wherePartialStatus($req->partial_status)->OrderBy('id','DESC')->first();

        $totalCharge = 0;

        if ($theItemDetails) {
            if (isset($theItemDetails->ownFields)) {
                foreach ($theItemDetails->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $theItemDetails->{$ownfield} = $req->{$ownfield};
                    }
                }
                //as 0 value is not inserted
                $theItemDetails->dangerous = $req->dangerous;
                $theItemDetails->yard_shed = $req->yard_shed;
            }


            if ($theItemDetails->save()) {    //update assessment_details table
                //after item details update then the assessmnet's warehouse charge will be save in assessmen_details table
                if ($assessmentWarehouse) {//assessment did already then update

                    //dd($theItemDetails->manifest->manifest);
                    $warehouse_details = $this->assessmentBaseController->getWarehouseDetails($theItemDetails->manifest->manifest); //manifest like manifest: 909050/3/2018
                    $warehouseCharge = json_decode($warehouse_details, true);
                    $wareHouseRentDay = $warehouseCharge['warehouse_rent_day'];
                    $assessment->warehouse_details = $warehouse_details;

                    if ($warehouseCharge['item_wise_shed_details_charge']) {
                        $danger = 1;
                        foreach ($warehouseCharge['item_wise_shed_details_charge'] as $k => $shed) {
                            if ($shed['dangerous'] == '1') {
                                $danger = 2;
                            }
                            if ($warehouseCharge['first_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $shed['first_slab']);
                            }
                            if ($warehouseCharge['second_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $shed['second_slab']);
                            }
                            if ($warehouseCharge['third_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $shed['third_slab']);
                            }
                        }
                    }
                    if($warehouseCharge['item_wise_yard_details_charge']) {
                        $danger = 1;
                        foreach ($warehouseCharge['item_wise_yard_details_charge'] as $k => $yard) {

                            if ($yard['dangerous'] == '1') {
                                $danger = 2;
                            }
                            if ($warehouseCharge['first_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $yard['first_slab']);
                            }
                            if ($warehouseCharge['second_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $yard['second_slab']);
                            }
                            if ($warehouseCharge['third_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $yard['third_slab']);
                            }

                        }
                    }

                    $assessmentWarehouse->other_unit = $wareHouseRentDay;
                    $assessmentWarehouse->tcharge = $totalCharge;
                    if ($assessmentWarehouse->save() && $assessment->save()) {
                        return \Redirect::route('maintenance-assessment-edit-form', [$theItemDetails->manf_id, 0,])->withSuccess('Successfully Updated The Warehouse');
                    }
                }
                return \Redirect::route('maintenance-assessment-edit-form', [$theItemDetails->manf_id, 0,])->withSuccess('Successfully Updated Only Item Details');


            }
            return back()->withError('Something Went Wrong To Update Assessment');
        }
        return back()->withError('The Assessment Not Found!');
//        return view('maintenance.truck.edit', compact('viewTitle', 'theTruck'));
    }


    public function deleteWarehouseCharge($id)
    {
//        $this->validate($req, [
//
//        ]);

        $deleteItemDetails = $this->itemDetail->findOrFail($id);


        $assessmentWarehouse = $this->assessmentDetails->whereSubHeadId(2)
            ->whereManifId($deleteItemDetails->manf_id)
            ->wherePartialStatus(0)->first();


        $assessment = $this->assessment->whereManifestId($deleteItemDetails->manf_id)
                    ->wherePartialStatus(0)->OrderBy('id','DESC')->first();

        $totalCharge = 0;

        if ($deleteItemDetails) {

            if ($deleteItemDetails->delete()) {

                if ($assessmentWarehouse) {

                    $warehouse_details = $this->assessmentBaseController->getWarehouseDetails($deleteItemDetails->manifest->manifest);
                    $warehouseCharge = json_decode($warehouse_details, true);
                    $wareHouseRentDay = $warehouseCharge['warehouse_rent_day'];
                    $assessment->warehouse_details = $warehouse_details;

                    if ($warehouseCharge['item_wise_shed_details_charge']) {
                        $danger = 1;
                        foreach ($warehouseCharge['item_wise_shed_details_charge'] as $k => $shed) {
                            if ($shed['dangerous'] == '1') {
                                $danger = 2;
                            }
                            if ($warehouseCharge['first_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $shed['first_slab']);
                            }
                            if ($warehouseCharge['second_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $shed['second_slab']);
                            }
                            if ($warehouseCharge['third_slab_day']) {
                                $totalCharge += ceil($shed['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $shed['third_slab']);
                            }
                        }
                    }
                    if($warehouseCharge['item_wise_yard_details_charge']) {
                        $danger = 1;
                        foreach ($warehouseCharge['item_wise_yard_details_charge'] as $k => $yard) {

                            if ($yard['dangerous'] == '1') {
                                $danger = 2;
                            }
                            if ($warehouseCharge['first_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $yard['first_slab']);
                            }
                            if ($warehouseCharge['second_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $yard['second_slab']);
                            }
                            if ($warehouseCharge['third_slab_day']) {
                                $totalCharge += ceil($yard['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $yard['third_slab']);
                            }

                        }
                    }

                    $assessmentWarehouse->other_unit = $wareHouseRentDay;
                    $assessmentWarehouse->tcharge = $totalCharge;
                    if ($assessmentWarehouse->save() && $assessment->save()) {
                        return \Redirect::route('maintenance-assessment-edit-form', [$deleteItemDetails->manf_id, 0,])->withSuccess('Successfully Deleted Warehouse Charge');
                    }
                }
                return \Redirect::route('maintenance-assessment-edit-form', [$deleteItemDetails->manf_id, 0,])->withSuccess('Successfully Deleted');

            }
            return back()->withError('Something Went Wrong To Update Assessment');
        }
        return back()->withError('The Assessment Not Found!');

    }


    public function deleteAssessmment($id)
    {


        $theItemDetail = $this->itemDetail->findOrFail($id);

        if ($theItemDetail) {
            $manifestId = $theItemDetail->manif_id;

            if ($theItemDetail->delete()) {
                return \Redirect::route('maintenance-assessment-edit-form', [$manifestId])->withSuccess('Successfully Deleted Warehouse Charge');
            }
            return back()->withError('Something Went Wrong To Delete The Warehouse Charge');
        }
        return back()->withError('The Assessment Not Found!');
//        return view('maintenance.truck.edit', compact('viewTitle', 'theTruck'));
    }







    public function deleteTruck($id)//truck id
    {
        $theTruck = $this->truckEntryReg->findOrFail($id);

        return \Redirect::route('maintenance-manifest-manifest-details', [$theTruck->manf_id])->withSuccess('Truck Delete is in Progress');

//        return back()->withError('This is in Progress!');

    }


}


