<?php

namespace App\Http\Controllers\Charges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Port;
use DB;
use Auth;
use Image;
use File;
use Input;
use PDF;
use Response;
use Cache;
use Session;

class TariffGoodsAndChargesController extends Controller
{

    public function tariffChargesView()
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


        return view('default.charges.tariff-charges', ['portList' => $portList]);
    }



    public function getYearCharge() {
        $getSelectYear = DB::select('SELECT DISTINCT tariff_goods.year AS tariff_goods_year  FROM tariff_goods ');
        return json_encode($getSelectYear);
    }

    public function getTariffGoodsData($port_id,$tariff_year) {

        if($port_id == "null"){
            $getData = DB::table('tariff_goods')
                ->where('year', $tariff_year)
                ->get();
        }else{
            $getData = DB::table('tariff_goods')
                ->where('port_id', $port_id)
                ->where('year', $tariff_year)
                ->get();
        }

        return json_encode($getData);
    }


    public function getAllTariffData($port_id,$tariff_year)
    {
        if($port_id == "null" && $tariff_year == "null"){
            $allData = DB::select("SELECT ports.port_name,tariff_goods.id AS tariff_goods_id,tariff_goods.year AS tariff_goods_year,tariff_goods.particulars AS tariff_goods_name,
 tariff_schedules_and_charges.* FROM  tariff_schedules_and_charges
JOIN tariff_goods ON tariff_schedules_and_charges.tariff_good_id = tariff_goods.id
LEFT JOIN ports ON tariff_schedules_and_charges.port_id = ports.id
ORDER BY tariff_schedules_and_charges.id DESC");
        }elseif ($port_id == "null"){
            $allData = DB::select("SELECT ports.port_name,tariff_goods.id AS tariff_goods_id,tariff_goods.year AS tariff_goods_year,tariff_goods.particulars AS tariff_goods_name,
 tariff_schedules_and_charges.* FROM  tariff_schedules_and_charges
JOIN tariff_goods ON tariff_schedules_and_charges.tariff_good_id = tariff_goods.id
LEFT JOIN ports ON tariff_schedules_and_charges.port_id = ports.id WHERE tariff_goods.year=?
ORDER BY tariff_schedules_and_charges.id DESC",[$tariff_year]);
        }elseif ($tariff_year == "null"){
            $allData = DB::select("SELECT ports.port_name,tariff_goods.id AS tariff_goods_id,tariff_goods.year AS tariff_goods_year,tariff_goods.particulars AS tariff_goods_name,
 tariff_schedules_and_charges.* FROM  tariff_schedules_and_charges
JOIN tariff_goods ON tariff_schedules_and_charges.tariff_good_id = tariff_goods.id
LEFT JOIN ports ON tariff_schedules_and_charges.port_id = ports.id WHERE tariff_schedules_and_charges.port_id =?
ORDER BY tariff_schedules_and_charges.id DESC",[$port_id]);
        }else{
            $allData = DB::select("SELECT ports.port_name,tariff_goods.id AS tariff_goods_id,tariff_goods.year AS tariff_goods_year,tariff_goods.particulars AS tariff_goods_name,
 tariff_schedules_and_charges.* FROM  tariff_schedules_and_charges
JOIN tariff_goods ON tariff_schedules_and_charges.tariff_good_id = tariff_goods.id
LEFT JOIN ports ON tariff_schedules_and_charges.port_id = ports.id WHERE tariff_goods.year=? AND tariff_schedules_and_charges.port_id = ?
ORDER BY tariff_schedules_and_charges.id DESC",[$tariff_year,$port_id]);
        }

        return json_encode($allData);
    }

    public function saveTariffData(Request $req)
    {
        $port_id = Session::get('PORT_ID');


        $checkDuplicate = DB::select("SELECT * FROM
                            tariff_schedules_and_charges AS tc
                           WHERE tc.slab IN ('$req->slab_position') AND tc.tariff_good_id =?", [$req->tariff_goods]);

        $slabData = DB::select("SELECT tc.slab AS slab_list FROM tariff_schedules_and_charges AS tc WHERE tc.tariff_good_id = ?", [$req->tariff_goods]);
        $exitingSlabArray = [];
        foreach ($slabData as $k => $v) {
            $exitingSlabArray[] = preg_replace('/[^0-9]/', '', $v->slab_list);
        }

        $exitingSlabSum = 0;
        foreach ($exitingSlabArray as $k => $v){
            $exitingSlabSum +=  $v;
        }

        $slab_position = preg_replace('/[^0-9]/', '', $req->slab_position);
        $updateSlabSum = $exitingSlabSum + $slab_position;


//        if($checkDuplicate){
//            return "Duplicate";
//
//
//        }else{

        if($exitingSlabSum == 0  && $updateSlabSum == 1){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }elseif ($exitingSlabSum == 1  && $updateSlabSum == 3){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }elseif ($exitingSlabSum == 3  && $updateSlabSum == 6){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }elseif ($exitingSlabSum == 6  && $updateSlabSum == 10){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }elseif ($exitingSlabSum == 10  && $updateSlabSum == 15){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }elseif ($exitingSlabSum == 15  && $updateSlabSum == 21){
            $saveTariffCharges =   DB::table('tariff_schedules_and_charges')->insert([
                'tariff_good_id' => $req->tariff_goods,
                'slab' => $req->slab_position,
                'from' => $req->start_day,
                'to' => $req->end_day,
                'shed_charge' => $req->shed_charge,
                'yard_charge' => $req->yard_charge,
                'port_id' => $port_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if($saveTariffCharges == true) {
                return "saved";
            }
        }else{
            return "Duplicate";
        }

    }

    public function deleteTariff($id)
    {

        $deleteDeg = DB::table('tariff_schedules_and_charges')->where('id', $id)->delete();

        if ($deleteDeg == true) {
            return "Deleted";
        }

    }



    public function updateTariff(Request $req)
    {
        $Update = DB::table('tariff_schedules_and_charges')
            ->where('id', $req->id)
            ->update(
                [
                    'tariff_good_id' => $req->tariff_goods,
                    'slab' => $req->slab_position,
                    'from' => $req->start_day,
                    'to' => $req->end_day,
                    'shed_charge' => $req->shed_charge,
                    'yard_charge' => $req->yard_charge,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        if ($Update == true) {
            return "Successfully Edited";
        }

    }

    public function tariffGoodsView()
    {
       // dd(Auth::user()->role_id);
        $port_id = Session::get('PORT_ID');
//        dd($port_id);
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
           // dd($portList);
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

        return view('default.charges.tariff-goods', ['portList' => $portList]);
    }




    public function getAllTariffGoodsData($port,$year)
    {
        if($port == "null" && $year == "null"){
            $allData = DB::select("SELECT tf.id AS free_id,tf.flag, ports.port_name,tariff_goods.* FROM  tariff_goods
LEFT JOIN ports ON ports.id = tariff_goods.port_id
JOIN tariff_goods_freetimes AS tf ON tf.tariff_good_id = tariff_goods.id
ORDER BY tariff_goods.id DESC ");
        }elseif ($port == "null"){
            $allData = DB::select("SELECT tf.id AS free_id,tf.flag, ports.port_name,tariff_goods.* FROM  tariff_goods
LEFT JOIN ports ON ports.id = tariff_goods.port_id
JOIN tariff_goods_freetimes AS tf ON tf.tariff_good_id = tariff_goods.id
WHERE tariff_goods.year = ?
ORDER BY tariff_goods.id DESC",[$year]);
        }elseif ($year == "null"){
            $allData = DB::select("SELECT tf.id AS free_id,tf.flag, ports.port_name,tariff_goods.* FROM  tariff_goods
LEFT JOIN ports ON ports.id = tariff_goods.port_id
JOIN tariff_goods_freetimes AS tf ON tf.tariff_good_id = tariff_goods.id
WHERE tariff_goods.port_id=?
ORDER BY tariff_goods.id DESC",[$port]);
        }else{
            $allData = DB::select("SELECT tf.id AS free_id,tf.flag, ports.port_name,tariff_goods.* FROM  tariff_goods
LEFT JOIN ports ON ports.id = tariff_goods.port_id
JOIN tariff_goods_freetimes AS tf ON tf.tariff_good_id = tariff_goods.id
WHERE tariff_goods.year =? AND tariff_goods.port_id=?
ORDER BY tariff_goods.id DESC",[$year,$port]);
        }

        return json_encode($allData);
    }

    public function saveTariffGoodsData(Request $req)
    {
        $port_id = Session::get('PORT_ID');
        $checkGoods = DB::select("SELECT * FROM tariff_goods AS tg WHERE tg.particulars = ? AND tg.year=? AND tg.port_id=?", [$req->goods_name,$req->goods_year,$req->port_id,]);

        if($checkGoods){

            return "Duplicate";

        }else{
            $saveTariffGoodsID =   DB::table('tariff_goods')->insertGetId([
                'port_id' => $req->port_id ? $req->port_id : $port_id,
                'particulars' => $req->goods_name,
                'basis_of_charges' => $req->basis_charge,
                'description' => $req->description,
                'year' => $req->goods_year,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

//            $checkFreeTime = DB::select("SELECT * FROM tariff_goods_freetimes AS tf WHERE tf.tariff_good_id =? AND tf.port_id=?", [$saveTariffGoodsID,$req->port_id]);
//
//            if($checkFreeTime){
//                              return "saved";
//            }else{

                $saveFreeTime =   DB::table('tariff_goods_freetimes')->insert([
                    'port_id' => $req->port_id ? $req->port_id : $port_id,
                    'tariff_good_id' => $saveTariffGoodsID,
                    'duration' => 3,
                    'flag' => $req->free_time_flag,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                if($saveFreeTime == true) {
                    return "saved";
                }

//            }

        }



    }


    public function updateTariffGoodsData(Request $req)
    {

        $checkGoodsUpdate = DB::select("SELECT * FROM tariff_goods AS tg WHERE tg.particulars = ? AND tg.year=? AND tg.port_id=?", [$req->goods_name,$req->goods_year,$req->port_id]);

        if($checkGoodsUpdate){
            if ($checkGoodsUpdate[0]->id == $req->id){
                $UpdateGoods = DB::table('tariff_goods')
                    ->where('id', $req->id)
                    ->update(
                        [
                            'year' => $req->goods_year,
                            'port_id' => $req->port_id,
                            'particulars' => $req->goods_name,
                            'basis_of_charges' => $req->basis_charge,
                            'description' => $req->description,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    );

                $UpdateFreeTime = DB::table('tariff_goods_freetimes')
                    ->where('id', $req->free_id)
                    ->update(
                        [
                            'flag' => $req->free_time_flag,
                            'port_id' => $req->port_id,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    );


                if ($UpdateFreeTime == true) {
                    return "Successfully Edited";
                }
            }else{
                return "Duplicate";
            }

        }else{
            $UpdateGoods = DB::table('tariff_goods')
                ->where('id', $req->id)
                ->update(
                    [
                        'year' => $req->goods_year,
                        'port_id' => $req->port_id,
                        'particulars' => $req->goods_name,
                        'basis_of_charges' => $req->basis_charge,
                        'description' => $req->description,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );

            $UpdateFreeTime = DB::table('tariff_goods_freetimes')
                ->where('id', $req->free_id)
                ->update(
                    [
                        'flag' => $req->free_time_flag,
                        'port_id' => $req->port_id,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );


            if ($UpdateFreeTime == true) {
                return "Successfully Edited";
            }

        }



    }

    public function deleteTariffGoods($id)
    {

        $deleteDeg = DB::table('tariff_goods')->where('id', $id)->delete();

        if ($deleteDeg == true) {
            return "Deleted";
        }

    }

}
