<?php

namespace App\Http\Controllers\DesktopApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;

class ManifestController extends Controller
{
 //   use ErrorTrait;

    private $manifests;
    private $truck;

    public function __construct(){
        $this->manifests = new Manifest();
        $this->truck = new TruckEntryReg();
    }

    public function store(Request $request){



        $manifests = $request->manifests;
        \Log::info($manifests);
        return response()->json(['data send succsee'],200);


        $manifests_not = $manifests_yes = [];
        $message = "";
        $truck_insert_count = $truck_already_count = $manifest_already_count = $manifest_insert_count = 0;
        foreach($manifests as $manifest_key => $manifest_value) {


            $manifestsExists = $this->manifests->where('manifest','=',$manifest_value['manifest'])->wherePortId(4)->first();



            $manifests_truck_regs = $manifests[$manifest_key]['truck_regs'];
            if(!is_null($manifestsExists)){//mani found
                unset($manifests[$manifest_key]);
                $manifests_not[] = $manifest_value['manifest'];
                $manifests_id = $manifestsExists->id;
                $manifest_already_count++;
            }else{
                $manifests_yes[] = $manifest_value['manifest'];
                unset($manifests[$manifest_key]['truck_regs']);
                $manifests_id = $this->manifests->insertGetId($manifests[$manifest_key]);
                $manifest_insert_count++;
            }

            if(count($manifests_truck_regs) && $manifests_id){
                foreach ($manifests_truck_regs as $truck_key => $truck_value) {
                    $truckExists = $this->truck->where(function($query) use ($manifests_id,$truck_value){
                        $query->where('manf_id','=',$manifests_id);
                        $query->where('truck_type','=',$truck_value['truck_type']);
                        $query->where('truck_no','=',$truck_value['truck_no']);
                        $query->where('goods_id','=',$truck_value['goods_id']);
                        $query->where('driver_card','=',$truck_value['driver_card']);
                    })->count();

                    if($truckExists) {
                        $truck_already_count++;
                        unset($manifests_truck_regs[$truck_key]);
                    }else{
                        $manifests_truck_regs[$truck_key]['manf_id'] = $manifests_id;
                    }
                }

                if(count($manifests_truck_regs)){
                    $truck_insert_count++;
                    $this->truck->insert($manifests_truck_regs);
                }
            }
        }

        if(count($manifests_not)) {
            $message .= "Those manifests id's ".implode($manifests_not, ", ")." Alreadey exists";
        }

        if(count($manifests_yes)) {
            $message .= " Those manifests are inserted ".implode($manifests_yes, ", ")." Inserterd Successfully.";
        }
        $data = array(
            "Manifests Exists " => $manifest_already_count,
            "Manifests Inserterd " => $manifest_insert_count,
            "Manifests Truck Inserterd " => $truck_insert_count,
            "Manifests Truck Exists " => $truck_already_count,
        );
        $response = array('code'=>200, 'message'=>$message,'data_response'=>true,'data'=>$data);
        return response()->json(array($response),200);
    }

    public function getManifests(){
        $manifests =  $this->manifests->with("trucks")->orderBy('id','desc')->take(20)->get();
        $response = array( 'code'=>200, 'message'=>'Data fetching Successfully','data_response'=>true, 'data'=>$manifests);
        return response()->json(array($response),200);
    }

    public function getManifest(Request $request){
        if($request->has('manifest')){
            $manifests = $this->manifests->with("trucks")->where("manifest",'=',$request->manifest)->first();
            $response = array( 'code'=>200, 'message'=>'Data fetching Successfully','data_response'=>true, 'data'=>$manifests);
        }else{
            $response = array( 'code'=>200, 'message'=>'Data fetching Successfully','data_response'=>false);
        }
        return response()->json(array($response),200);
    }

    public function saveAnotherIpData(Request $request)
    {
        // return '[{"message":"Successfull"}]';
//        return   csrf_token();

        //  $json = json_decode($request->data);

        $this->manifests->values =$request->data;
        $this->manifests->save();



        // foreach ($json as $k => $value) {

        //  $this->manifests->values =json_encode($value);
        // $this->manifests->save();
        // }
        return '[{"message":"Successfull"}]';
//        return \Response::json(['messge'=>'ok,s inser'],201);

    }




    // 		$rules = [
    // 'manifests.*.inserted_status'=>'required',
    // 'manifests.*.port_id'=>'required',
    // 'manifests.*.manifest'=>'required',
    // 'manifests.*.perishable_flag'=>'required',
    // 'manifests.*.self_flag'=>'required',
    // 'manifests.*.goods_id'=>'required',
    // 'manifests.*.created_by'=>'required',
    // 'manifests.*.created_at'=>'required',
    // 'manifests.*.updated_by'=>'required',
    // 'manifests.*.updated_at'=>'required',
    // 'manifests.*.gweight'=>'required',
    // 'manifests.*.manifest_date'=>'required',
    // 'manifests.*.marks_no'=>'required',
    // 'manifests.*.nweight'=>'required',
    // 'manifests.*.package_no'=>'required',
    // 'manifests.*.package_type'=>'required',
    // 'manifests.*.cnf_value'=>'required',
    // 'manifests.*.exporter_name_addr'=>'required',
    // 'manifests.*.vatreg_id'=>'required',
    // 'manifests.*.lc_no'=>'required',
    // 'manifests.*.lc_date'=>'required',
    // 'manifests.*.ind_be_no'=>'required',
    // 'manifests.*.ind_be_date'=>'required',
    // 'manifests.*.posted_yard_shed'=>'required',
    // 'manifests.*.chassis_flag'=>'required',
    // 'manifests.*.cnf_posted_flag'=>'required',
    // 'manifests.*.manifest_posted_done_flag'=>'required',
    // 'manifests.*.transshipment_flag'=>'required',
    // 'manifests.*.manifest_posted_by'=>'required',
    // 'manifests.*.manifest_created_time'=>'required',
    // 'manifests.*.manifest_update_by'=>'required',
    // 'manifests.*.manifest_update_at'=>'required',
    // 'manifests.*.be_no'=>'required',
    // 'manifests.*.be_date'=>'required',
    // 'manifests.*.paid_tax'=>'required',
    // 'manifests.*.paid_date'=>'required',
    // 'manifests.*.shifting_flag'=>'required',
    // 'manifests.*.bd_weighment'=>'required',
    // 'manifests.*.no_del_truck'=>'required',
    // 'manifests.*.transport_truck'=>'required',
    // 'manifests.*.transport_van'=>'required',
    // 'manifests.*.carpenter_packages'=>'required',
    // 'manifests.*.carpenter_repair_packages'=>'required',
    // 'manifests.*.carpenter_charge_id'=>'required',
    // 'manifests.*.carpenter_repair_id'=>'required',
    // 'manifests.*.ain_no'=>'required',
    // 'manifests.*.cnf_name'=>'required',
    // 'manifests.*.posting_remark'=>'required',
    // 'manifests.*.gate_pass_no'=>'required',
    // 'manifests.*.gate_pass_by'=>'required',
    // 'manifests.*.gate_pass_at'=>'required',
    // 'manifests.*.custom_release_order_no'=>'required',
    // 'manifests.*.custom_release_order_date'=>'required',
    // 'manifests.*.approximate_delivery_date'=>'required',
    // 'manifests.*.approximate_labour_load'=>'required',
    // 'manifests.*.approximate_equipment_load'=>'required',
    // 'manifests.*.approximate_delivery_type'=>'required',
    // 'manifests.*.old_approximate_delivery_date'=>'required',
    // 'manifests.*.custom_approved_by'=>'required',
    // 'manifests.*.custom_approved_date'=>'required',
    // 'manifests.*.custom_approved_updated_by'=>'required',
    // 'manifests.*.custom_approved_updated_at'=>'required',
    // 'manifests.*.cnf_id'=>'required',
    // 'manifests.*.vat_id'=>'required',
    // 'manifests.*.local_transport_type'=>'required',
    //   ];

    // $validation  = $this->commonValidator( $request, $rules);
    //    if ($validation) {
    //       $response = array( 'code'=>400, 'message'=>$validation,'data_response'=>false);
    //       return response()->json( $response );
    //    }


}
