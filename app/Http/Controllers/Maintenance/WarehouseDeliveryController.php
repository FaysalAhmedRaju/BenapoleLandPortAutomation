<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Warehouse\Delivery\DeliveryRequisition;

use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;
use App\Models\Warehouse\ShedYardWeight;
use App\Models\Warehouse\YardDetail;
use League\Flysystem\Exception;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;


class WarehouseDeliveryController extends Controller
{

    private $manifest;
    private $truckEntryReg;
    private $shedYardWeight;
    private $yardDetail;


    public function __construct(Manifest $manifest, TruckEntryReg $truckEntryReg, ShedYardWeight $shedYardWeight, YardDetail $yardDetail,DeliveryRequisition $deliveryRequisition)
    {
        $this->middleware('auth');
        $this->manifest = $manifest;
        $this->truckEntryReg = $truckEntryReg;
        $this->shedYardWeight = $shedYardWeight;
        $this->yardDetail = $yardDetail;
        $this->deliveryRequisition = $deliveryRequisition;
    }

    public function editDeliveryRequest($id,$status)
    {
        //dd($status);
       // $status = 2;
        //$this->deliveryRequisition->whereManifestId($id)->get();


//        $this->assessmentDetails->whereManifId($id)->wherePartialStatus($status)->get();
        $viewTitle = 'Edit Delivery Request Form';
        $theManifest = $this->manifest->findOrFail($id);
        $deliveryRequisitions = $this->deliveryRequisition->whereManifestId($id)->wherePartialStatus($status)->get()->first();
       // dd($deliveryRequisitions);
    //  dd($this->deliveryRequisition->whereManifestId($id)->wherePartialStatus($status)->get());
       // dd($theManifest->deliveryRequisitions[0]->whereManifestId($id)->wherePartialStatus($status)->get());
        return view('maintenance.warehouse.delivery.edit-request', compact('viewTitle', 'theManifest','deliveryRequisitions'));
    }

    public function updateDeliveryRequest($id, Request $req)
    {
        try {

           // dd($req);
            //dd($id);  18289
            $this->validate($req, [
                'local_transport_type' => 'required',

            ]);

            $theDeliryReq = $this->manifest->findOrFail($id);

            if (isset($theDeliryReq->ownFields)) {
                foreach ($theDeliryReq->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $theDeliryReq->{$ownfield} = $req->{$ownfield};
                    }
                }
            }
           $theRequisitionData = $this->deliveryRequisition->findOrFail($req->req_id);
            //dd($theRequisitionData);

            $theRequisitionData->carpenter_packages = $req->carpenter_packages;
            $theRequisitionData->carpenter_repair_packages = $req->carpenter_repair_packages;
            $theRequisitionData->approximate_delivery_date = $req->approximate_delivery_date;
            $theRequisitionData->approximate_delivery_type = $req->approximate_delivery_type;

            $theRequisitionData->approximate_labour_load = $req->approximate_labour_load;
            $theRequisitionData->approximate_equipment_load = $req->approximate_equipment_load;
            $theRequisitionData->local_transport_type = $req->local_transport_type;
            $theRequisitionData->transport_truck = $req->transport_truck;

            $theRequisitionData->transport_van = $req->transport_van;
            $theRequisitionData->local_weighment = $req->local_weighment;
            $theRequisitionData->shifting_flag = $req->shifting_flag;
            $theRequisitionData->gate_pass_no = $req->gate_pass_no;
            $theRequisitionData->local_haltage = $req->local_haltage;
            $theRequisitionData->save();
//            $theRequisitionData->updated_by = $req->gate_pass_no;
////        $theManifest->manifest_update_by=\Auth::user()->id;
////        $theManifest->manifest_update_at=Carbon::now();
////            $theDeliryReq->custom_approved_updated_by=Auth::user()->id;
////            $theDeliryReq->custom_approved_updated_at=Carbon::now();

            if ($theDeliryReq->save()) {
                return \Redirect::route('maintenance-manifest-manifest-details',[$id])->withSuccess('Successfully Udated Dlivery Req Data');
            }else{
                return \Redirect::route('maintenance-manifest-manifest-details',[$id])->withError('Something Went Wrong!');

            }


        } catch (ModelNotFoundException $exception) {
            return response()->view('notFound');
        } catch (Exception $exception) {
            return response()->view('notFound');
        }

    }


}

