<?php

namespace App\Http\Controllers\Charges;
use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Response;
use App\Role;
use Session;

class HandlingOtherchargesController extends Controller
{
    public  function  gethandlingOtherChargesView()
    {

        if(Auth::user()->role_id == 2){
            $adminArrayId = array();
            foreach(Auth::user()->ports as $k => $v) {
                $adminArrayId[] = $v->id;
            }

            $adminArrayName = array();
            foreach(Auth::user()->ports as $k => $v) {
                $adminArrayName[] = $v->port_name;
            }

            $portList = array_combine($adminArrayId, $adminArrayName);
        }else{
            $portList = (new Port())->all();
            $arrayShedYardId = array();
            foreach($portList as $k => $v) {
                $arrayShedYardId[] = $v->id;
            }
            $num = array_push($arrayShedYardId,-1);

            $arrayName = array();
            foreach($portList as $k => $v) {
                $arrayName[] = $v->port_name;
            }
            $num = array_push($arrayName,'All Port');
            $portList = array_combine($arrayShedYardId, $arrayName);
        }

        return view('default.admin.handling-other-charges.handling-and-other-charges', ['portList' => $portList]);
    }

    public function allHandlingOtherChargesDetails()
    {
        $year = date('Y');
        $Handiling_charges = DB::select("SELECT * FROM handling_and_othercharges AS HC WHERE HC.charges_year =?",[$year]);

        return json_encode($Handiling_charges);
    }




    public function saveHandlingCharge(Request $r)
    {
        $port_id = Session::get('PORT_ID');
 $CheckChargeId = DB::SELECT("SELECT * FROM handling_and_othercharges AS h 
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type,$r->charge_year]);

        //  $CheckTruck = DB::table('delivery_export')
        //      ->where('truck_bus_no', $r->truck_no)
        //      ->where('entry_datetime', $todayTime)
        //      ->where('truck_bus_flag', 1)
        //      ->get();
        //$test = '2017-11-30';

//        $file = fopen("Truckentry.txt","w");
//        echo fwrite($file,"Hello Bangladesh:".$CheckTruck);
//        fclose($file);
//        return;
        if($CheckChargeId == []){
            $year = date('Y');
            $year = $year - 1;
            //dd($year);
            $chargesOthers = DB::SELECT("SELECT h.type_of_charge, h.name_of_charge, h.description_of_charge FROM handling_and_othercharges AS h 
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type,$year]);
            $createdBy = Auth::user()->name;
            $createdTime = date('Y-m-d H:i:s');
            //  $entryUser = Auth::user()->username;
            $Charges = DB::table('handling_and_othercharges')
                ->insert([
                    'handling_and_othercharges.charge_id' => $r->Charge_type,
                    'handling_and_othercharges.type_of_charge' => $chargesOthers[0]->type_of_charge,
                    'handling_and_othercharges.name_of_charge' => $chargesOthers[0]->name_of_charge,
                    'handling_and_othercharges.description_of_charge' => $chargesOthers[0]->description_of_charge,
                    'handling_and_othercharges.rate_of_charges' => $r->charge_rate,
                    'handling_and_othercharges.charges_year' => $r->charge_year,
                    'handling_and_othercharges.port_id' => $r->port_id ? $r->port_id : $port_id,
                    'handling_and_othercharges.created_by' => $createdBy,
                    'handling_and_othercharges.created_at' => $createdTime

                ]);
//        $p = DB::table('delivery_export')
//            ->where('truck_bus_no', $r->truck_no)
//            ->where('truck_bus_flag', 1)
//            ->update([
//                'total_amount' => $grandTotal
//            ]);
            if ($Charges == true) {
                return "Inserted";
            }
        }else{
            return "Duplicate";
        }




    }




    public function getAllChargeDataDetails()
    {
       // $currentTime = date('Y-m-d');
//        $year = date('Y');
        $data_charge = DB::SELECT('SELECT handling_and_othercharges.*,ports.port_name FROM handling_and_othercharges
JOIN ports ON ports.id = handling_and_othercharges.port_id');

        RETURN json_encode($data_charge);
    }



    public function dateWiseAllChargeDetails(Request $req)
    {

        if($req->port_id_search == null && $req->tariff_year_search == null){
            $getData = DB::select("SELECT h.*,ports.port_name FROM handling_and_othercharges AS h
JOIN ports ON ports.id = h.port_id");
        }elseif ($req->port_id_search == null){
            $getData = DB::select("SELECT h.*,ports.port_name FROM handling_and_othercharges AS h
JOIN ports ON ports.id = h.port_id WHERE h.charges_year =?",[$req->tariff_year_search]);
        }elseif ($req->tariff_year_search == null){
            $getData = DB::select("SELECT h.*,ports.port_name FROM handling_and_othercharges AS h
JOIN ports ON ports.id = h.port_id WHERE h.port_id=?",[$req->port_id_search]);
        }else{
            $getData = DB::select("SELECT h.*,ports.port_name FROM handling_and_othercharges AS h
JOIN ports ON ports.id = h.port_id WHERE h.charges_year =? AND h.port_id=?",[$req->tariff_year_search,$req->port_id_search]);
        }

        return json_encode($getData);
    }


    public function updateHandilingOthersCharges(Request $r)
    {
        $port_id = Session::get('PORT_ID');
        $CheckChargeId = DB::SELECT("SELECT * FROM handling_and_othercharges AS h
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type_id,$r->charge_year]);

        if($CheckChargeId == []){

            $year = date('Y');
            $year = $year - 1;
            //dd($year);
            $chargesOthers = DB::SELECT("SELECT h.type_of_charge, h.name_of_charge, h.description_of_charge FROM handling_and_othercharges AS h 
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type_id,$year]);

            $createdBy = Auth::user()->name;
            $createdTime = date('Y-m-d H:i:s');
            $UpdateData = DB::table('handling_and_othercharges')
                ->where('id', $r->id)
                ->update([
                    'charge_id' => $r->Charge_type_id,
                    'type_of_charge' => $chargesOthers[0]->type_of_charge,
                    'name_of_charge' => $chargesOthers[0]->name_of_charge,
                    'description_of_charge' => $chargesOthers[0]->description_of_charge,
                    'rate_of_charges' => $r->charge_rate,
                    'charges_year' => $r->charge_year,
                    'port_id' =>  $r->port_id ? $r->port_id : $port_id,
                    'updated_by' => $createdBy,
                    'updated_at' => $createdTime
                ]);
            if ($UpdateData == true) {
                return "Updated";
            }

        }else{
            $Only_id = DB::SELECT("SELECT h.id AS table_id FROM handling_and_othercharges AS h
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type_id,$r->charge_year]);
            if ($Only_id[0]->table_id == $r->id ){

                $year = date('Y');
                $year = $year - 1;
                //dd($year);
                $chargesOthers = DB::SELECT("SELECT h.type_of_charge, h.name_of_charge, h.description_of_charge FROM handling_and_othercharges AS h 
WHERE h.charge_id = ? AND h.charges_year = ?",[$r->Charge_type_id,$year]);

                $createdBy = Auth::user()->name;
                $createdTime = date('Y-m-d H:i:s');
                $UpdateData = DB::table('handling_and_othercharges')
                    ->where('id', $r->id)
                    ->update([
                        'charge_id' => $r->Charge_type_id,
                        'type_of_charge' => $chargesOthers[0]->type_of_charge,
                        'name_of_charge' => $chargesOthers[0]->name_of_charge,
                        'description_of_charge' => $chargesOthers[0]->description_of_charge,
                        'rate_of_charges' => $r->charge_rate,
                        'charges_year' => $r->charge_year,
                        'port_id' =>  $r->port_id ? $r->port_id : $port_id,
                        'updated_by' => $createdBy,
                        'updated_at' => $createdTime
                    ]);
                if ($UpdateData == true) {
                    return "Updated";
                }

            }else{
                return "Duplicate";
            }



        }


    }


    public function deleteHandilingOthersChargesData($id)
    {
        DB::table('handling_and_othercharges')->where('id', $id)->delete();

        return 'success';
    }




}
