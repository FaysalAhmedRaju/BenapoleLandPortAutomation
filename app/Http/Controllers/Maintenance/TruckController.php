<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Warehouse\ShedYard;
use App\Models\Warehouse\YardDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;

use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Warehouse\ShedYardWeight;


class TruckController extends Controller
{

    private $manifest;
    private $truckEntryReg;
    private $shedYardWeight;

    public function __construct(Manifest $manifest, TruckEntryReg $truckEntryReg, ShedYardWeight $shedYardWeight)
    {
        $this->middleware('auth');
        $this->manifest = $manifest;
        $this->truckEntryReg = $truckEntryReg;
        $this->shedYardWeight = $shedYardWeight;
    }

    public function truckDetails($id)
    {
        $viewTitle = 'Truck Details';
        $theTruck = $this->truckEntryReg->findOrFail($id);

        //   dd(count($theTruck->shedYardWeights)>0);


        return view('maintenance.truck.truck-details', compact('viewTitle', 'theTruck'));
    }

    public function editTruck($id)
    {
        try {
            $viewTitle = 'Truck Edit Form';
            $theTruck = $this->truckEntryReg->findOrFail($id);
            $theShedYardData = $this->truckEntryReg->findOrFail($id)->shedYardWeights()->whereTruckId($id)->get();
            //dd($theShedYardData);
            $yards = YardDetail::all();


            return view('maintenance.truck.edit', compact('viewTitle', 'theTruck','yards','theShedYardData'));
        } catch (Exception $exception) {
            return back()->withError('The Truck Not Found!');
        }

    }

    public function updateTruck($id, Request $req)
    {
        $this->validate($req, [
            'truckentry_datetime' => 'required'
        ]);

        $theTruck = $this->truckEntryReg->findOrFail($id);

//dd($req);
        if ($theTruck) {
            if (isset($theTruck->ownFields)) {
                foreach ($theTruck->ownFields as $ownfield) {
                    if ($req->{$ownfield}) {
                        $theTruck->{$ownfield} = $req->{$ownfield};

                    }

                }

            }


            if (isset($req->shed_yard_weight)){
                foreach ($req->shed_yard_weight as $k => $req_data ){
                    $theShedYardWeight = $this->shedYardWeight->findOrFail($req_data['id']);
                    if($theShedYardWeight) {
                        if (isset($theShedYardWeight->ownFields)) {
                            foreach ($theShedYardWeight->ownFields as $ownfield) {
                                if(isset($req_data[$ownfield])) {
                                    $theShedYardWeight->{$ownfield} = $req_data[$ownfield];
                                }
                            }
                        }
                        $theShedYardWeight->save();
                    }
                }

            }

            \DB::table('shed_yard_weights')
                ->where('id', $req->id)
                ->update([
                    'unload_yard_shed' => $req->unload_yard_shed,
                    'unload_receive_datetime' => $req->unload_receive_datetime,
                ]);

            //save chassis data

            if($req->vehicle_type_flag >= 1100) {//self flag =>11
                $checkShedYardWeightsData=$theTruck->shedYardWeights;

                if (count($checkShedYardWeightsData)>0 ){//shed yard table has data already for the truck
                   \DB::table('shed_yard_weights')
                        ->where('truck_id', $theTruck->id)
                        ->update([
                            'unload_yard_shed' => $req->t_posted_yard_shed,
                            'unload_receive_datetime' => $req->unload_receive_datetime,
                        ]);
//i am i

//$checkChassisDetailsDataExist=
                     \DB::table('chassis_details')
                      ->where('truck_id', $theTruck->id)
                      ->update([
                          'chassis_type' => $req->truck_type,
                          'chassis_no' => $req->truck_no,

                      ]);




                }else{
                    $this->saveSelfDetails($req,$theTruck->manf_id,$theTruck->id);
                }
            }

            if ($theTruck->save()) {
                return \Redirect::route('maintenance-truck-details', [$id])->withSuccess('Successfully Updated The Truck');
            }
            return back()->withError('Something Went Wrong To Update The Truck');
        }
        return back()->withError('The Truck Not Found!');
    }

    public function deleteTruck($id)//truck id
    {
        $theTruck = $this->truckEntryReg->findOrFail($id);

        return \Redirect::route('maintenance-manifest-manifest-details', [$theTruck->manf_id])->withSuccess('Truck Delete is in Progress');

//        return back()->withError('This is in Progress!');

    }


    private function saveSelfDetails($req, $manifest_id, $truck_id) {

        $port_id = \Session::get('PORT_ID');
        $current_datetime = date('Y-m-d H:i:s');
        $user_id = \Auth::user()->id;

        $save_chasis = \DB::table('chassis_details')
            ->insert([
                'manifest_id' => $manifest_id,
                'truck_id' => $truck_id,
                'chassis_type' => $req->truck_type,
                'chassis_no' => $req->truck_no,
                'port_id' => $port_id,
                'created_by' => $user_id,
                'created_at' => $current_datetime
            ]);

        $save_weights = \DB::table('shed_yard_weights')
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


}

