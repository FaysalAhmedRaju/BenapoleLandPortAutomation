<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\GlobalFunctionController;
use Auth;

class AccountsInvoiceController extends Controller
{
    public function Invoice()
    {
        return view('Accounts.InvoiceAccounts');
    }


    public function getManifestDetailsForAccounts($manifestNo, $truck, $year)
    {

        $manifest = (string)$manifestNo . "/" . (string)$truck . "/" . (string)$year;
        //return $manifest;


        $manifestReport = DB::select("SELECT manifests.id, manifests.manifest, 
manifests.manifest_date, manifests.be_no AS bill_of_entry_no, manifests.be_date AS bill_of_entry_date,
manifests.exporter_name_addr AS consigner, /*(SELECT vatregs.NAME FROM  vatregs WHERE vatregs.BIN=manifests.vat_id) AS consignee,*/
(SELECT truck_entry_regs.posted_yard_shed FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=manifests.id  
ORDER BY truck_entry_regs.id DESC LIMIT 1) AS posted_yard_shed,
(SELECT challan_details.challan_no FROM challan_details WHERE challan_details.manf_id = manifests.id ) AS challan_no
 FROM manifests WHERE manifests.manifest=?", [$manifest]);


        $globalfunctionCtrl = new GlobalFunctionController();
        $w = DB::select('SELECT ReceiveWeight,receive_date,deliver_date,goods_id,posted_yard_shed,package_no
             FROM(SELECT m.goods_id,m.package_no,
            (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id DESC LIMIT 1)AS truckentry_datetime,
            (SELECT truck_entry_regs.posted_yard_shed FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id DESC LIMIT 1)AS posted_yard_shed,
//            (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1)AS receive_date,
            (SELECT truck_deliverys.delivery_dt FROM  truck_deliverys WHERE truck_deliverys.manf_id=m.id ORDER BY truck_deliverys.id DESC LIMIT 1)AS deliver_date,
            (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id ORDER BY truck_entry_regs.id DESC LIMIT 1)AS ReceiveWeight
            FROM manifests m  WHERE m.manifest=?)t', [$manifest]);
        //return $w;
        $receive_date = $w[0]->receive_date;
        $deliver_date = $w[0]->deliver_date;
        $goods_id = $w[0]->goods_id;
        $posted_yard_shed = $w[0]->posted_yard_shed;
        $package_no = $w[0]->package_no;
        $ReceiveWeight = ceil(($w[0]->ReceiveWeight / 1000));
        $wareHouseRentDay = $globalfunctionCtrl->number_of_working_days($receive_date, $deliver_date);

        $freeEndDay = $globalfunctionCtrl->GetFreedayEndForWarehouseRent($receive_date);//return $receive_date + 3 days including holidays
        $ChargeStartDay = $globalfunctionCtrl->ChargeStartDay($freeEndDay);
        $wareHouseRentDay = $globalfunctionCtrl->number_of_working_days($ChargeStartDay, $deliver_date);

        //get slab charge variable globaly
        $firstSlabCharge = 0;
        $secondSlabCharge = 0;
        $thirdSlabCharge = 0;
        $firstSlabDay = 0;
        $secondSlabDay = 0;
        $thirdSlabDay = 0;


        if ($wareHouseRentDay >= 1 && $wareHouseRentDay <= 21) {//1 slab will be calculated------------------1
            $firstSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 1);
            // $secondSlabCharge=  0;
            // $thirdSlabCharge=  0;
            $firstSlabDay = $wareHouseRentDay;
        } else if ($wareHouseRentDay >= 22 && $wareHouseRentDay <= 50) {//2 slab will be calculated------------------2
            $firstSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 1);
            $secondSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 2);
            // $thirdSlabCharge =  0;
            $firstSlabDay = 21;
            $secondSlabDay = ($wareHouseRentDay - 21);
        } else if ($wareHouseRentDay >= 51) {//3 slab will be calculated---------------------------------3
            $firstSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 1);
            $secondSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 2);
            $thirdSlabCharge = $globalfunctionCtrl->SlabCharge($goods_id, $posted_yard_shed, 3);
            $firstSlabDay = 21;
            $secondSlabDay = 29;
            $thirdSlabDay = ($wareHouseRentDay - 21 - 29);

        } else {
            $firstSlabCharge = 0;
            $secondSlabCharge = 0;
            $thirdSlabCharge = 0;
            $firstSlabDay = 0;
            $secondSlabDay = 0;
            $thirdSlabDay = 0;
        }

        $warehouse = array(
            'WareHouseRent' => $wareHouseRentDay,
            'FreeEndDate' => $freeEndDay,
            'ChargeStartDay' => $ChargeStartDay,
            'FirstSlabDay' => $firstSlabDay,
            'SecondSlabDay' => $secondSlabDay,
            'thirdSlabDay' => $thirdSlabDay,
            'FirstSlabCharge' => $firstSlabCharge,
            'SecondSlabCharge' => $secondSlabCharge,
            'ThirdSlabCharge' => $thirdSlabCharge,
            'receive_date' => $receive_date,
            'deliver_date' => $deliver_date,
            'goods_id' => $goods_id,
            'posted_yard_shed' => $posted_yard_shed,
            'ReceiveWeight' => $ReceiveWeight,
        );

        $goodsNameTotalPkgMaxNet = DB::select("SELECT *
                                                FROM( 
                                                SELECT
                                                     m.package_no,
                                                (SELECT cargo.cargo_name FROM  cargo_details AS cargo WHERE cargo.id=m.goods_id) AS description_of_goods,
                                                (
                                                    CASE
                                                    WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                                                    ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id)
                                                    END
                                                ) AS max_Net_Weight
                                                FROM manifests m  
                                                WHERE m.manifest=? ) AS final", [$manifest]);

        $foreignTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 26 AND 
                                    manifests.manifest = ?", [$manifest]);

        $localTruck = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 28 AND 
                                    manifests.manifest = ?", [$manifest]);

        $carpenterChargesOpenningOrClosing = DB::select("SELECT manifests.manifest,
                                    assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 30 AND 
                                    manifests.manifest = ?", [$manifest]);

        $carpenterChargesRepair = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 32 AND 
                                    manifests.manifest = ?", [$manifest]);

        $holidayChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 42 AND 
                                    manifests.manifest = ?", [$manifest]);

        $holidayChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 40 AND 
                                    manifests.manifest = ?", [$manifest]);

        $nightChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 38 AND 
                                    manifests.manifest = ?", [$manifest]);

        $nightChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 36 AND 
                                    manifests.manifest = ?", [$manifest]);

        $holtageChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 46 AND 
                                    manifests.manifest = ?", [$manifest]);
        /*$holtageChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit,
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 44 AND 
                                    manifests.manifest = ?",[$manifest]);*/

        $documentationCharges = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 52 AND 
                                    manifests.manifest = ?", [$manifest]);

        $weighmentChargesFT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 50 AND 
                                    manifests.manifest = ?", [$manifest]);

        $weighmentChargesLT = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 48 AND 
                                    manifests.manifest = ?", [$manifest]);

        $offLoadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 4 AND 
                                    manifests.manifest =?", [$manifest]);

        $offLoadingEquipment = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 6 AND 
                                    manifests.manifest =?", [$manifest]);

        $loadingLabour = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 8 AND 
                                    manifests.manifest =?", [$manifest]);

        $loadingEquip = DB::select("SELECT manifests.manifest,assesment_details.unit, 
                                    assesment_details.other_unit,assesment_details.charge_per_unit, assesment_details.tcharge, (SELECT acc_sub_head.acc_sub_head  FROM acc_sub_head WHERE acc_sub_head.id=assesment_details.sub_head_id) AS acc_sub_head, assesment_details.sub_head_id
                                    FROM assesment_details 
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE assesment_details.sub_head_id = 10 AND 
                                    manifests.manifest =?", [$manifest]);

        $totalAmount = DB::select("SELECT SUM(assesment_details.tcharge) AS totalAmount
                                    FROM assesment_details
                                    INNER JOIN manifests ON assesment_details.manif_id=manifests.id 
                                    WHERE manifests.manifest=?", [$manifest]);

        $challan = array(

            'manifestReport' => $manifestReport,
            'warehouse' => $warehouse,
            'goodsNameTotalPkgMaxNet' => $goodsNameTotalPkgMaxNet,
            'foreignTruck' => $foreignTruck,
            'localTruck' => $localTruck,
            'carpenterChargesOpenningOrClosing' => $carpenterChargesOpenningOrClosing,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            'holidayChargesFT' => $holidayChargesFT,
            'holidayChargesLT' => $holidayChargesLT,
            'nightChargesFT' => $nightChargesFT,
            'nightChargesLT' => $nightChargesLT,
            'holtageChargesFT' => $holtageChargesFT,
            // 'holtageChargesLT' => $holtageChargesLT,
            'documentationCharges' => $documentationCharges,
            'weighmentChargesFT' => $weighmentChargesFT,
            'weighmentChargesLT' => $weighmentChargesLT,
            'offLoadingLabour' => $offLoadingLabour,
            'offLoadingEquipment' => $offLoadingEquipment,
            'loadingLabour' => $loadingLabour,
            'loadingEquip' => $loadingEquip,
            'totalAmount' => $totalAmount
        );
        return json_encode($challan);


    }


    public function saveChallan($manif_id)
    {
        //return $manif_id;
        $Mani_id = $manif_id;

        $checkAssDone = DB::table('transaction AS t')
            ->where('t.manif_id', $Mani_id)
            ->get()->first();
        if ($checkAssDone) {//old transaction / old challan
            DB::table('transaction')->where('manif_id', $Mani_id)->delete();
        } else {
           // DB::table('transaction')->where('manif_id', $Mani_id)->delete();
           // DB::table('challan_details')->where('manf_id', $Mani_id)->delete();
        }
        $challan_id = null;
        //Challan No generate===============
        $CallanNoCheck = DB::select("SELECT ch.id FROM challan_details AS ch WHERE ch.manf_id = ?", [$Mani_id]);
        if (!count($CallanNoCheck)) {//challan not found

            $createdBy = Auth::user()->name;
            $createdTime = date('Y-m-d H:i:s');
            $getChallValue = "CH";
            $getMaxIdChalan = DB::select("SELECT MAX(CAST((SUBSTRING(challan_details.challan_no, 3)) AS UNSIGNED)) 
                                                      AS challan_no FROM challan_details");

            if (!is_null($getMaxIdChalan[0]->challan_no)) {
                $challanNumber = $getMaxIdChalan[0]->challan_no + 1;
            } else {
                $challanNumber = 1;
            }
            $CallanNo = $getChallValue . sprintf("%05d", $challanNumber);

//        if(!count($CallanNoCheck)){
//            $randomNumber = mt_rand(100000,999999);
            $challan_id = DB::table('challan_details')
                ->insertGetId([
                    'manf_id' => $Mani_id,
                    'challan_no' => $CallanNo,
                    'challan_dt' => $createdTime,
                    'creator' => $createdBy
                ]);
//        }
        }

        else{
            $challan_id=$CallanNoCheck[0]->id;
        }

         //return;
//Warehouse  fee==========
        $warehouseRent = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 2)
            ->select('a.tcharge')
            ->get();
        if ($warehouseRent != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 2,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $warehouseRent[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //Handling Charge==============
        $handlingOLabour = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 4)
            ->select('a.tcharge')
            ->get();
        //---Offload-Labour
        if ($handlingOLabour != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 4,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingOLabour[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );

        }
        //----OffLoad-Equip
        $handlingoffEq = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 6)
            ->select('a.tcharge')
            ->get();
        if ($handlingoffEq != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 6,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingoffEq[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }

        //----load-Labour
        $handlingLoadLabour = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 8)
            ->select('a.tcharge')
            ->get();
        if ($handlingLoadLabour != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 8,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingLoadLabour[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //----load-Equip
        $handlingLoadEq = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 10)
            ->select('a.tcharge')
            ->get();
        if ($handlingLoadEq != '[]') {
            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 10,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $handlingLoadEq[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //return 'ok';
//Entrance fee==========
        $IndTruckEntrance = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 26)
            ->select('a.tcharge')
            ->get();

        $BdTruckEntrance = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 28)
            ->select('a.tcharge')
            ->get();
        //Foreign_Truck-----
        if ($IndTruckEntrance != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 26,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $IndTruckEntrance[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        if ($BdTruckEntrance != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 28,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $BdTruckEntrance[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //carpenter charge==================
        //opening / closing----
        $carpenterOpening = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 30)
            ->select('a.tcharge')
            ->get();

        if ($carpenterOpening != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 30,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $carpenterOpening[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
            //Repair----
            $carpenterRepair = DB::table('assesment_details AS a')
                ->where('a.manif_id', $Mani_id)
                ->where('a.sub_head_id', 32)
                ->select('a.tcharge')
                ->get();
        }
        if ($carpenterRepair != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 32,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $carpenterRepair[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }

        //Holiday charge==================

        $holiday_Charge_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 42)
            ->select('a.tcharge')
            ->get();
        if ($holiday_Charge_foreign != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 42,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $holiday_Charge_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
//----local---
        $holiday_Charge_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 40)
            ->select('a.tcharge')
            ->get();
        if ($holiday_Charge_local != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 40,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $holiday_Charge_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //Night charge==================
        $Night_charges_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 38)
            ->select('a.tcharge')
            ->get();
        if ($Night_charges_foreign != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 38,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $Night_charges_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
        //Night_charges==========
//----local---
        $Night_charges_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 36)
            ->select('a.tcharge')
            ->get();
        if ($Night_charges_local != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 36,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $Night_charges_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
//Haltage Charge==============

        //----foreign
        $HaltageCharge_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 46)
            ->select('a.tcharge')
            ->get();
        if ($HaltageCharge_foreign != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 46,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $HaltageCharge_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
//----local---
        $HaltageCharge_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 44)
            ->select('a.tcharge')
            ->get();


        if ($HaltageCharge_local != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 44,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $HaltageCharge_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
//weighbridge charge


        //----foreign
        $weighment_foreign = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 50)
            ->select('a.tcharge')
            ->get();
        if ($weighment_foreign != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 50,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $weighment_foreign[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }
//----local---
        $weighment_local = DB::table('assesment_details AS a')
            ->where('a.manif_id', $Mani_id)
            ->where('a.sub_head_id', 48)
            ->select('a.tcharge')
            ->get();
        if ($weighment_local != '[]') {


            DB::table('transaction')->insert(

                [
                    'manif_id' => $Mani_id,
                    'sub_head_id' => 48,
                    'challan_details_id' => $challan_id,
                    'debit' => 0,
                    'credit' => $weighment_local[0]->tcharge,
                    'comments' => 0,
                    'entry_dt' => date('Y-m-d H:i:s'),
                    'trans_dt' => date('Y-m-d H:i:s'),
                    'userid' => Auth::user()->username
                ]
            );
        }

        return "success";

    }
}


/*
$file = fopen("Truckentry.txt","w");
echo fwrite($file,"Hello ".$HaltageCharge_local);
fclose($file);
return;*/