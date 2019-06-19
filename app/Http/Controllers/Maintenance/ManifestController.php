<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Assessment\Assessment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;
use App\Models\Warehouse\ShedYard;
use App\Models\Warehouse\Delivery\DeliveryRequisition;
use League\Flysystem\Exception;
use DB;
use Session;

class ManifestController extends Controller
{
    private $manifest;
    private $shedYard;
    private $truck_entry_reg;

    public function __construct(Assessment $assessment, Manifest $manifest, TruckEntryReg $truck_entry_reg, ShedYard $shedYard, DeliveryRequisition $deliveryRequisition)
    {
        $this->middleware('auth');
        $this->manifest = $manifest;
        $this->shedYard = $shedYard;
        $this->truck_entry_reg = $truck_entry_reg;
        $this->assessment = $assessment;
        $this->deliveryRequisition = $deliveryRequisition;
    }

    public function manifestList()
    {

       // dd($this->manifest->toSql());

        $viewType = 'Welcome To Manifest List';

//        $manifests = $this->manifest->orderBy('id', 'DESC')->paginate(2);
        $manifests = $this->manifest->with(['trucks.shedYardWeights.yardDetail','shedYard','port','assessments','deliveryRequisitions'])
            ->where('port_id', Session::get('PORT_ID'))->orderBy('id', 'DESC')->paginate(10);
      // dd($manifests);


        return view('maintenance.manifest.manifest-list', compact('viewType', 'manifests'));
    }

    public function searchByManifestNo(Request $req)
    {
        $manifests = $this->manifest->where('manifest', $req->manifest_no)->paginate(10);
       // dd(count($manifests[0]->trucks[0]->shedYardWeights));
        $viewType = 'Welcome To Manifest List';
        return view('maintenance.manifest.manifest-list', compact('viewType', 'manifests'));
    }

    public function manifestDetails($manifest_id)
    {
        $viewType = 'Manifest Related Data';
        $truckList = $this->truck_entry_reg->where('manf_id', $manifest_id)->paginate(20);
        $theManifest = $this->manifest->findOrFail($manifest_id);
     // dd($theManifest->deliveryRequisitions);
        return view('maintenance.manifest.manifest-details', compact('viewType', 'truckList', 'theManifest'));
    }

    public function editManifest($id)
    {
        try {
            $goods = DB::select('SELECT  b.id,
                b.cargo_name
                FROM manifests a
                INNER JOIN cargo_details b
                ON FIND_IN_SET(b.id, a.goods_id) > 0
                WHERE a.id=?', [$id]);
            $viewTitle = 'Manifest Edit Form';
            $theManifest = $this->manifest->findOrFail($id);
            $yards = $this->shedYard->all();
            return view('maintenance.manifest.edit', compact('viewTitle', 'theManifest', 'yards','goods'));
        } catch (Exception $exception) {
            return back()->withError('The Truck Not Found!');
        }

    }



    public function getGoodsIdForTagsAtMaintenance($manifest)
    {


        $goods = DB::select('SELECT  b.id,
                b.cargo_name
                FROM manifests a
                INNER JOIN cargo_details b
                ON FIND_IN_SET(b.id, a.goods_id) > 0
                WHERE a.id=?', [$manifest]);

        return json_encode($goods);
    }

    public function updateManifest($id, Request $req)
    {
     //  dd($req->goods_id);
        $this->validate($req, [
            'posted_yard_shed' => 'required',
            'manifest' => 'required|unique:manifests,manifest,'.$id
        ]);

        $theManifest = $this->manifest->findOrFail($id);

        if (isset($theManifest->ownFields)) {
            foreach ($theManifest->ownFields as $ownfield) {
                if ($req->{$ownfield}) {
                    $theManifest->{$ownfield} = $req->{$ownfield};
                }
            }
        }
//        $theManifest->manifest_update_by=\Auth::user()->id;
//        $theManifest->manifest_update_at=Carbon::now();


        if ($theManifest->save()) {
            return \Redirect::route('maintenance-manifest-manifest-details', [$id])->withSuccess('Successfully Updated The Manifest');
        }
        return back()->withError('Something Went Wrong To Update The Manifest');

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


    public function updateGoodsData(Request $req) {

        $theManifest = $this->manifest->find($req->manifest_id);


        $goods_id = $this->saveGoodsAndGetIds($req);


        $theManifest->goods_id = $goods_id;

        $theManifest->save();



        return Response::json(['manifest_no_updated' => $theManifest->manifest , 'message' => ' Updated'], 201);
    }



}




