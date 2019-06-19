<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;
use App\Models\Warehouse\ShedYardWeight;
use App\Models\Warehouse\YardDetail;


class ShedYardWeightController extends Controller
{

    private $manifest;
    private $truckEntryReg;
    private $shedYardWeight;
    private $yardDetail;


    public function __construct(Manifest $manifest, TruckEntryReg $truckEntryReg, ShedYardWeight $shedYardWeight, YardDetail $yardDetail)
    {
        $this->middleware('auth');
        $this->manifest = $manifest;
        $this->truckEntryReg = $truckEntryReg;
        $this->shedYardWeight = $shedYardWeight;
        $this->yardDetail = $yardDetail;
    }

    public function editShedYardWeight($id)
    {
        $viewTitle = 'Edit Shed Yard Weight Form';
        $shedYardWeight = $this->shedYardWeight->findOrFail($id);
        $yardShedList = $this->yardDetail->all();

        //   dd(count($theTruck->shedYardWeights)>0);
        return view('maintenance.shed-yard.edit', compact('viewTitle', 'shedYardWeight', 'yardShedList'));
    }

    public function updateShedYardWeight($id, Request $req)
    {

        // dd($id);
        $this->validate($req, [
            'unload_receive_datetime' => 'required',
            'unload_yard_shed' => 'required',

        ]);

        //   dd($req->get('is_displayable'));


        $shedYardWeight = $this->shedYardWeight->findOrFail($id);

        $shedYardWeight->unload_labor_package = $req->unload_labor_package;
        $shedYardWeight->unload_labor_weight = $req->unload_labor_weight;
        $shedYardWeight->unload_equipment_package = $req->unload_equipment_package;
        $shedYardWeight->unload_equip_weight = $req->unload_equip_weight;
        $shedYardWeight->unload_equip_name = $req->unload_equip_name;
        $shedYardWeight->unload_yard_shed = $req->unload_yard_shed;
        $shedYardWeight->unload_shifting_flag = $req->unload_shifting_flag;
        $shedYardWeight->unload_receive_datetime = $req->unload_receive_datetime;
        $shedYardWeight->unload_comment = $req->recive_comment;
//        $shedYardWeight->updated_at = date('Y-m-d H:i:s');
//        $shedYardWeight->updated_by = Auth::user()->id;


        $shedYardWeight->save();
//        $successData = 'The User ' . $userName . ' Successfully Deleted!';
        return \Redirect::route('maintenance-truck-details',[$shedYardWeight->truck_id])->withSuccess('Successfully Udated The Shed Yard Weight');
//        return back()->withSuccess('Successfully Udated The Shed Yard Weight');

    }


    public function deleteShedYardWeight($id)
    {
        $theShedYardWeight = $this->shedYardWeight->findOrFail($id);

        if($theShedYardWeight->delete()){
            return back()->withSuccess('Successfully Deleted The Shed Yard Weight');
        }
    }


}

