<?php

namespace App\Http\Controllers\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Auth;
use PDF;
use App\Manifest;
use App\Http\Controllers\GlobalFunctionController;
use Response;
use DateTime;
use DateInterval;
use DatePeriod;
use Symfony\Component\VarDumper\Cloner\Data;
use App\Http\Controllers\AssessmentBaseController;
use Session;

class AssessmentController extends Controller
{

    private $globalFunctionController;
    private $assessment_base_controller;


    public function __construct(GlobalFunctionController $globalFunctionController, AssessmentBaseController $assessment_base_controller) {
        $this->middleware('auth');
        $this->globalFunctionController = $globalFunctionController;
        $this->assessment_base_controller = $assessment_base_controller;
    }

    public function welcome() {
        $currentDate = date('Y-m-d');
        $currentUser = Auth::user()->id;
        $port_id = Session::get('PORT_ID');
        $todaysAssessmentDetails = DB::select('SELECT (SELECT COUNT(a.total_ass) 
                FROM ( SELECT COUNT(ass.id) AS total_ass FROM assessments ass 
                WHERE DATE(ass.created_at)=? AND ass.transshipment_flag=0 AND ass.port_id=? 
                GROUP BY ass.manifest_id) a) AS total_assessment,
                (SELECT SUM(b.total_charge) FROM (SELECT SUM(asd.tcharge) AS total_charge 
                FROM assesment_details asd 
                JOIN assessments AS ass ON ass.manifest_id = asd.manif_id
                WHERE DATE(ass.created_at)=? AND ass.transshipment_flag=0 AND ass.port_id=? AND asd.port_id=? AND ass.id IN ( SELECT MAX(id) FROM assessments WHERE port_id=? GROUP BY manifest_id) ) b ) AS total_assessment_value,
                (SELECT COUNT(c.completed_assessment) FROM ( SELECT COUNT(ass.id) AS completed_assessment 
                FROM assessments ass WHERE DATE(ass.created_at)=? AND ass.done = 1 
                AND ass.transshipment_flag=0 AND ass.port_id=? GROUP BY ass.manifest_id) c) AS total_assessment_done,
                (SELECT COUNT(d.assessment_created_by_you) FROM (SELECT COUNT(ass.id) AS assessment_created_by_you 
                FROM assessments ass WHERE DATE(ass.created_at) = ? AND ass.created_by=? AND ass.port_id=?
                GROUP BY ass.manifest_id) d ) AS total_assessment_created_by_you', [$currentDate, $port_id,$currentDate, $port_id, $port_id, $port_id, $currentDate, $port_id, $currentDate, $currentUser, $port_id]);

        $TotalAssessmentValue = ceil($todaysAssessmentDetails[0]->total_assessment_value);//number_format(, 2, '.', '');
        // $Vat = number_format((($TotalAssessmentValue * 15) / 100), 2, '.', '');
        $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);


        return view('default.assessment.welcome', compact('todaysAssessmentDetails', 'TotalAssessmentWithVat'));
    }

    public function assessmentSheet() {
        //$data = $this->assessment_base_controller->getWarehouseDetails('46356/1/2018', 1);
        //return $data;
        return view('default.assessment.assessment-sheet');
    }

    public function checkManifestForAssessmentAndAllChargesPartialList(Request $r) {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');
        $check_manifest = DB::select('SELECT m.id FROM manifests AS m WHERE m.manifest = ? AND m.port_id = ?',[$r->mani_no, $port_id]);
        if(count($check_manifest) == 0) {
            return Response::json(['message' => 'manifest not found!'], 203);
        }

        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $partial_number = $this->assessment_base_controller->getLastPartialStatus($r->mani_no);
        if(is_null($partial_number[0]->max_partial_number)) {
            return Response::json(['message' => 'Delivery Request not done!'], 203);
        }

        if(is_null($r->partial_status)) {
            $partial_status = $partial_number[0]->max_partial_number;
        } else {
            $partial_status = $r->partial_status;
        }
        $manifest_details = $this->assessment_base_controller->manifestDetailsForAssessment($r->mani_no, $partial_status);
        if(count($manifest_details) == 0 || $manifest_details == false) {
            return Response::json(['message' => 'manifest not found in our record!'], 203);
        }
        $getAllCharges = $this->globalFunctionController->getAllCharges($year);
        $transshipment = $manifest_details[0]->transshipment_flag ? true : false;
        if(!$transshipment) {
            return json_encode(array($manifest_details, $getAllCharges, $partial_number));
        } else {
            return Response::json(['message' => 'You are not permitted to see this manifest.'], 203);
        }
    }

    public function getWarehouseForAssessment(Request $r) {
        $warehouse_details = $this->assessment_base_controller->getWarehouseDetails($r->mani_no, $r->partial_status);
        return $warehouse_details;
    }

    public function getHandlingChargeForAssesment(Request $r) {
        $handling = $this->assessment_base_controller->getHandlingCharge($r->mani_no, $r->partial_status);
        return json_encode($handling);
    }

    public function getOtherDuesForAssessment(Request $r) {
        $port_id = Session::get('PORT_ID');
        $get_entrance_carpenter_weighment_charge = $this->assessment_base_controller->getEntranceCarpenterWeighmentCharge($r->mani_no, $r->partial_status);

        $get_foreign_haltage_charge = $this->assessment_base_controller->getForeignTruckHaltageDetails($r->mani_no);

        $get_local_truck_haltage_charge = $this->assessment_base_controller->getLocalTruckHaltageDetails($r->mani_no, $r->partial_status);

        $get_foreign_night_charge = [];//$this->assessment_base_controller->getForeignNightDetails($r->mani_no);

        $docunemt_details = $this->assessment_base_controller->getDocumentDetails($r->mani_no, $r->partial_status);
        $foreign_final_truck = [];
        $holidayLocal = [];

//        $holidayForeign = $this->assessment_base_controller->getForeignHolidayDetails($r->mani_no);
//        $holiday_f_t = [];
//        $foreign_final_truck = [];
//        $total_foreign_truck_times_for_holiday = 0;
//
//        if(isset($holidayForeign) && !empty($holidayForeign)) {
//            foreach ($holidayForeign as $holidayF) {
//                if (!in_array($holidayF->receive_date, $holiday_f_t)) {
//                    $holiday_f_t[] = $holidayF->receive_date;
//                    $foreign_final_truck[] = $holidayF;
//                    $total_foreign_truck_times_for_holiday++;
//                }
//            }
//        }

//        $holidayLocal = $this->assessment_base_controller->getLocalHolidayDetails($r->mani_no, $r->partial_status);
//
//
//        if(count($holidayLocal) > 0 && count($foreign_final_truck) > 0) {//if ulnolad and load is same holiday then get one holiday charge/ and it will be shown in local holiday
//
//            foreach ($foreign_final_truck as $kf=>$foreign) {
//
//                foreach ($holidayLocal as $kl=>$local) {
//                    if ($foreign->receive_date == $local->delivery_date) {
//                        array_splice($foreign_final_truck, $kf,1);
//                        break;
//                    }
//                }
//            }
//        }

        return json_encode(array($get_entrance_carpenter_weighment_charge, $get_foreign_haltage_charge, $get_local_truck_haltage_charge, $get_foreign_night_charge, $docunemt_details, $foreign_final_truck, $holidayLocal));
    }

    public function getForeignTrucksDetailsData($id) {
        $port_id = Session::get('PORT_ID');
        $data=DB::select('SELECT tr.*,
                        (SELECT shed_yard_weights.unload_receive_datetime 
                        FROM shed_yard_weights
                        WHERE shed_yard_weights.truck_id = tr.id ORDER BY shed_yard_weights.unload_receive_datetime ASC LIMIT 1) AS unload_receive_datetime
                 FROM truck_entry_regs AS tr 
                JOIN manifests AS m ON m.id=tr.manf_id
                WHERE m.id=? AND m.port_id=? AND tr.port_id=?', [$id, $port_id, $port_id]);

        return json_encode($data);
    }

    public function changesHaltageChargeFlagForForeignTruck(Request $req) {

        DB::table('truck_entry_regs')
            ->where('id', $req->truck_id)
            ->update([
                'holtage_charge_flag' => $req->status
            ]);
        return Response::json(['updated' => 'Successfully Updated the Charge Status!'], 200);
    }

    public function saveAssesmentData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $checkItem = DB::select(' SELECT COUNT(ide.id) AS total_item FROM item_details AS ide
                                  WHERE ide.manf_id = ? AND ide.port_id = ?',[$r->Mani_id, $port_id]);

        if(count($checkItem) > 0 && $checkItem[0]->total_item == 0) {
            return Response::json(['errorText' => 'Item Not Selected !'], 403);
        }

        $checkAssDone = DB::table('assesment_details AS a')
                            ->where('a.manif_id', $r->Mani_id)
                            ->where('a.port_id', $port_id)
                            ->where('a.partial_status', $r->partial_status)
                            ->get();

        //restrick assment for normal user except ass admins
        if((Auth::user()->role_id != 11 && Auth::user()->role_id != 21) && count($checkAssDone) > 0) {//assment admins and maintenance
            $assessments = DB::select('SELECT * FROM assessments AS ass
                                            WHERE ass.manifest_id=?
                                            AND ass.partial_status=? AND ass.port_id=?
                                            ORDER BY ass.id DESC LIMIT 1', [$r->Mani_id, $r->partial_status, $port_id]);
            return Response::json(['errorText' => 'Assessemt Has Already Been Saved At ' . date('d-m-Y h:i:s A', strtotime($assessments[0]->created_at)) . ',Please Contact With Admin'], 403);
        }

        $getMaxSerialNumber = DB::select('SELECT MAX(assessments.yearly_serial) AS max_serial_number 
                                    FROM assessments WHERE assessments.transshipment_flag = 0 
                                    AND assessments.partial_status = ? AND YEAR(assessments.created_at) = ? AND assessments.port_id=?',[$r->partial_status, $year, $port_id]);

        if($getMaxSerialNumber[0]->max_serial_number == null) {
            $yearly_serial = 1;
        } else {
            $yearly_serial = $getMaxSerialNumber[0]->max_serial_number + 1;
        }


        $get_warehouse_details = $this->assessment_base_controller->getWarehouseDetails($r->mani_no, $r->partial_status);
        $foreign_truck_haltage =  $r->partial_status == 1 ? $this->assessment_base_controller->getForeignTruckHaltageDetails($r->mani_no) : null;
        $assessment_values = [ 
            'manifest_id' => $r->Mani_id,
            'vat' => $r->vat_flag,
            'self_flag' => $r->self_flag,
            'perishable_flag' => null,
            //'free_period' => $r->freePeriod,
            //'rent_due_period' => $r->rentDuePeriod,
            'weight' => $r->weight,
            'good_description' => $r->goodDescription,
            'transport_truck' => $r->entranceTotalLocalTruck,
            'transport_van' => $r->entranceTotalLocalVan,
            'handling_charge_customized' => $r->customomizedHandlingCharge,
            'foreign_truck_entrance_charge_customized' => $r->entranceFeeChangeFlag,
            'truck_to_truck_flag' => $r->truck_to_truck_flag,
            'foreign_truck_haltage_details' => $foreign_truck_haltage
        ];

        if(!count($checkAssDone)) { //new assessment 
            DB::table('assessments')
                ->insert(
                    [
                        'manifest_id' => $r->Mani_id,
                        'port_id' => $port_id,
                        'transshipment_flag' => 0,
                        'assessment_values' => json_encode($assessment_values),
                        'warehouse_details' => $get_warehouse_details,
                        'partial_status' => $r->partial_status,
                        'yearly_serial' => $yearly_serial,
                        'charge_year' => $year,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );

            if($r->WareHouseRentDay) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 2,
                        'unit' => $r->chargableTonForWarehouse,
                        'other_unit' => $r->WareHouseRentDay,
                        'charge_per_unit' => null,
                        'tcharge' => $r->TotalWarehouseCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }
            //Offload Labour
            if($r->OffloadLabour) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 4,
                        'unit' => $r->OffloadLabour,
                        'charge_per_unit' => $r->OffloadLabourCharge,
                        'tcharge' => $r->TotalForOffloadLabour,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }
            //OffLoad Equip
            if ($r->OffLoadingEquip) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 6,
                        'unit' => $r->OffLoadingEquip,
                        'other_unit' => $r->offloadShifting,
                        'charge_per_unit' => $r->OffLoadingEquipCharge,
                        'tcharge' => $r->TotalForOffloadEquip,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //----load-Labour
            if ($r->loadLabour) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 8,
                        'unit' => $r->loadLabour,
                        'charge_per_unit' => $r->loadLabourCharge,
                        'tcharge' => $r->TotalForloadLabour,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id

                    ]
                );
            }

            //----load-Equip
            if ($r->loadingEquip) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 10,
                        'unit' => $r->loadingEquip,
                        'charge_per_unit' => $r->loadingEquipCharge,
                        'tcharge' => $r->TotalForloadEquip,
                        'other_unit' => $r->loadShifting ? 1 : '',
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            if ($r->totalForeignTruckEntranceFee) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 26,
                        'unit' => $r->entranceTotalForeignTruck,
                        'charge_per_unit' => $r->entrance_fee_foreign,
                        'tcharge' => $r->totalForeignTruckEntranceFee,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }
            if ($r->totalLocalTruckEntranceFee) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 28,
                        'unit' => $r->entranceTotalLocalTruck,
                        'charge_per_unit' => $r->entrance_fee_local,
                        'tcharge' => $r->totalLocalTruckEntranceFee,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            if ($r->totalLocalVanEntranceFee) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 228,
                        'unit' => $r->entranceTotalLocalVan,
                        'charge_per_unit' => $r->entrance_fee_van,
                        'tcharge' => $r->totalLocalVanEntranceFee,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //carpenter charge==================
            //opening/ closing----
            if (isset($r->carpenterPackages)) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 30,
                        'unit' => $r->carpenterPackages,
                        'charge_per_unit' => $r->carpenterChargesOpenClose,
                        'tcharge' => $r->totalcarpenterChargesOpenClose,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //Repair----
            if ($r->carpenterRepairPackages) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 32,
                        'unit' => $r->carpenterRepairPackages,
                        'charge_per_unit' => $r->carpenterChargesRepair,
                        'tcharge' => $r->totalcarpenterChargesRepair,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //Holiday charge==================
            //----foreign
            if ($r->holidayTotalForeignTruck) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 42,
                        'unit' => $r->holidayTotalForeignTruck,
                        'charge_per_unit' => $r->foreign_holiday_charge,
                        'tcharge' => $r->TotalForeignHolidayCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //----local---
            if ($r->holidayTotalLocalTruck) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 40,
                        'unit' => $r->holidayTotalLocalTruck,
                        'charge_per_unit' => $r->local_holiday_charge,
                        'tcharge' => $r->TotalLocalHolidayCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //----foreign
            if ($r->TotalForeignNightCharge) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 38,
                        'unit' => $r->nightTotalForeignTruck,
                        'charge_per_unit' => $r->rate_of_night_charge,
                        'tcharge' => $r->TotalForeignNightCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //Haltage Charge==============

            //----foreign
            if ($r->haltagesTotalForeignTruck) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 46,
                        'unit' => $r->haltagesTotalForeignTruck,
                        'other_unit' => $r->haltagesTotalDayForeignTruck,
                        'charge_per_unit' => $r->foreign_haltage_charge,
                        'tcharge' => $r->TotalHaltageForeignCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //----Local
            if ($r->haltagesTotalLocalTruck) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 44,
                        'unit' => $r->haltagesTotalLocalTruck,
                        'other_unit' => $r->haltagesTotalDayLocalTruck,
                        'charge_per_unit' => $r->local_haltage_charge,
                        'tcharge' => $r->TotalHaltageLocalCharge,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //weighbridge charge
            //----foreign
            if ($r->weightmentChargesForeign) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 50,
                        'unit' => $r->totalForeignTruck,
                        'other_unit' => 2,
                        'charge_per_unit' => $r->weightment_measurement_charges,
                        'tcharge' => $r->weightmentChargesForeign,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            //----Local
            if ($r->local_truck_weighment) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 48,
                        'unit' => $r->local_truck_weighment,
                        'other_unit' => 2,
                        'charge_per_unit' => $r->weightment_measurement_charges,
                        'tcharge' => $r->weightmentChargesLocal,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }

            if ($r->numberOfDocuments > 0) {
                DB::table('assesment_details')->insert(
                    [
                        'manif_id' => $r->Mani_id,
                        'sub_head_id' => 52,
                        'unit' => $r->numberOfDocuments,
                        'charge_per_unit' => $r->documentCharges,
                        'tcharge' => $r->totalDocumentCharges,
                        'partial_status' => $r->partial_status,
                        'port_id' => $port_id
                    ]
                );
            }
        } else {
            $getManifestSerialNumber = DB::select('SELECT ass.yearly_serial FROM assessments AS ass
                                                    WHERE ass.manifest_id=? AND ass.transshipment_flag = 0
                                                    AND ass.partial_status = ? AND ass.port_id=?
                                                    ORDER BY ass.id DESC LIMIT 1', [$r->Mani_id, $r->partial_status, $port_id]);
            DB::table('assessments')
                ->where('manifest_id', $r->Mani_id)
                ->where('partial_status', $r->partial_status)
                ->update(
                    [
                        'port_id' => $port_id,
                        'transshipment_flag' => 0,
                        'assessment_values' => json_encode($assessment_values),
                        'warehouse_details' => $get_warehouse_details,
                        'yearly_serial' => $getManifestSerialNumber[0]->yearly_serial,
                        'charge_year' => $year,
                        'done' => 0,
                        'done_at' => null,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            $exitingSubheadIds = [];
            foreach ($checkAssDone as $k => $v) {
               $exitingSubheadIds[] = $v->sub_head_id; 
            }
            if(in_array(2, $exitingSubheadIds, TRUE)) { //Warhouse Start
                if($r->WareHouseRentDay) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 2)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->chargableTonForWarehouse,
                                'other_unit' => $r->WareHouseRentDay,
                                'tcharge' => $r->TotalWarehouseCharge
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 2)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->WareHouseRentDay) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 2,
                                'unit' => $r->chargableTonForWarehouse,
                                'other_unit' => $r->WareHouseRentDay,
                                'tcharge' => $r->TotalWarehouseCharge,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } // Warehouse End

            if(in_array(4, $exitingSubheadIds, TRUE)) { //Offload-Labour Start
                if($r->OffloadLabour) { 
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 4)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->OffloadLabour,
                                'charge_per_unit' => $r->OffloadLabourCharge,
                                'tcharge' => $r->TotalForOffloadLabour
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 4)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if($r->OffloadLabour) {
                    DB::table('assesment_details')
                        ->where('sub_head_id', $v->sub_head_id)
                        ->insert(
                            [   'manif_id' => $r->Mani_id,
                                'partial_status' => $r->partial_status,
                                'sub_head_id' => 4,
                                'unit' => $r->OffloadLabour,
                                'charge_per_unit' => $r->OffloadLabourCharge,
                                'tcharge' => $r->TotalForOffloadLabour,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Offload-Labour End

            if(in_array(6, $exitingSubheadIds, TRUE)) { //OffLoad Equip Start
                if($r->OffLoadingEquip) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 6)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->OffLoadingEquip,
                                'other_unit' => $r->offloadShifting,
                                'charge_per_unit' => $r->OffLoadingEquipCharge,
                                'tcharge' => $r->TotalForOffloadEquip
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 6)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->OffLoadingEquip) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 6,
                                'unit' => $r->OffLoadingEquip,
                                'other_unit' => $r->offloadShifting,
                                'charge_per_unit' => $r->OffLoadingEquipCharge,
                                'tcharge' => $r->TotalForOffloadEquip,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                } 
            } //OffLoad Equip End
            
            if(in_array(8, $exitingSubheadIds, TRUE)) { //----load-Labour Start
                if($r->loadLabour) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 8)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->loadLabour,
                                'charge_per_unit' => $r->loadLabourCharge,
                                'tcharge' => $r->TotalForloadLabour,
                                'partial_status' => $r->partial_status
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 8)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->loadLabour) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 8,
                                'unit' => $r->loadLabour,
                                'charge_per_unit' => $r->loadLabourCharge,
                                'tcharge' => $r->TotalForloadLabour,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id

                            ]
                        );
                }
            } //----load-Labour End

            if(in_array(10, $exitingSubheadIds, TRUE)) { //--load-Equip Start
                if($r->loadingEquip) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 10)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->loadingEquip,
                                'charge_per_unit' => $r->loadingEquipCharge,
                                'tcharge' => $r->TotalForloadEquip,
                                'other_unit' => $r->loadShifting ? 1 : ''
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 10)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->loadingEquip) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 10,
                                'unit' => $r->loadingEquip,
                                'charge_per_unit' => $r->loadingEquipCharge,
                                'tcharge' => $r->TotalForloadEquip,
                                'other_unit' => $r->loadShifting ? 1 : '',
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //--load-Equip End
            
            if(in_array(26, $exitingSubheadIds, TRUE)) { //Foreign Truck Entrance Start
                if($r->totalForeignTruckEntranceFee) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 26)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->entranceTotalForeignTruck,
                                'charge_per_unit' => $r->entrance_fee_foreign,
                                'tcharge' => $r->totalForeignTruckEntranceFee
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 26)
                        ->where('port_id', $port_id)
                        ->delete();
                } 
            } else {
                if($r->totalForeignTruckEntranceFee) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 26,
                                'unit' => $r->entranceTotalForeignTruck,
                                'charge_per_unit' => $r->entrance_fee_foreign,
                                'tcharge' => $r->totalForeignTruckEntranceFee,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Foreign Truck Entrance End

            if(in_array(28, $exitingSubheadIds, TRUE)) { //Local Truck Entrance Start
                if($r->totalLocalTruckEntranceFee) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 28)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->entranceTotalLocalTruck,
                                'charge_per_unit' => $r->entrance_fee_local,
                                'tcharge' => $r->totalLocalTruckEntranceFee
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 28)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->totalLocalTruckEntranceFee) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 28,
                                'unit' => $r->entranceTotalLocalTruck,
                                'charge_per_unit' => $r->entrance_fee_local,
                                'tcharge' => $r->totalLocalTruckEntranceFee,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Local Truck Entrance End

            if(in_array(228, $exitingSubheadIds, TRUE)) { //Local Van Entrance Start
                if($r->totalLocalVanEntranceFee) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 228)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->entranceTotalLocalVan,
                                'charge_per_unit' => $r->entrance_fee_van,
                                'tcharge' => $r->totalLocalVanEntranceFee
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 228)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if($r->totalLocalVanEntranceFee) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 228,
                                'unit' => $r->entranceTotalLocalVan,
                                'charge_per_unit' => $r->entrance_fee_van,
                                'tcharge' => $r->totalLocalVanEntranceFee,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Local Van Entrance End

            if(in_array(30, $exitingSubheadIds, TRUE)) { //Carpenter opening/ closing Start
                if(isset($r->carpenterPackages)) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 30)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->carpenterPackages,
                                'charge_per_unit' => $r->carpenterChargesOpenClose,
                                'tcharge' => $r->totalcarpenterChargesOpenClose
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 30)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if (isset($r->carpenterPackages)) {
                    DB::table('assesment_details')->insert(
                        [
                            'manif_id' => $r->Mani_id,
                            'sub_head_id' => 30,
                            'unit' => $r->carpenterPackages,
                            'charge_per_unit' => $r->carpenterChargesOpenClose,
                            'tcharge' => $r->totalcarpenterChargesOpenClose,
                            'partial_status' => $r->partial_status,
                            'port_id' => $port_id
                        ]
                    );
                }
            } //Carpenter opening/closing End

            if(in_array(32, $exitingSubheadIds, TRUE)) { //Carpenter Repair Charge Start
                if($r->carpenterRepairPackages) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 32)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->carpenterRepairPackages,
                                'charge_per_unit' => $r->carpenterChargesRepair,
                                'tcharge' => $r->totalcarpenterChargesRepair
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 32)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if($r->carpenterRepairPackages) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 32,
                                'unit' => $r->carpenterRepairPackages,
                                'charge_per_unit' => $r->carpenterChargesRepair,
                                'tcharge' => $r->totalcarpenterChargesRepair,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Carpenter Repair Charge End

            if(in_array(42, $exitingSubheadIds, TRUE)) { //Holiday Foreign Charge Start
               if($r->holidayTotalForeignTruck) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 42)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->holidayTotalForeignTruck,
                                'charge_per_unit' => $r->foreign_holiday_charge,
                                'tcharge' => $r->TotalForeignHolidayCharge
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 42)
                        ->where('port_id', $port_id)
                        ->delete();
                } 
            } else {
                if($r->holidayTotalForeignTruck) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 42,
                                'unit' => $r->holidayTotalForeignTruck,
                                'charge_per_unit' => $r->foreign_holiday_charge,
                                'tcharge' => $r->TotalForeignHolidayCharge,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Holiday Foreign Charge End

            if(in_array(40, $exitingSubheadIds, TRUE)) {
                if ($r->holidayTotalLocalTruck) { //Holiday Local Truck Start
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 40)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->holidayTotalLocalTruck,
                                'charge_per_unit' => $r->local_holiday_charge,
                                'tcharge' => $r->TotalLocalHolidayCharge
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 40)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if ($r->holidayTotalLocalTruck) {
                    DB::table('assesment_details')->insert(
                        [
                            'manif_id' => $r->Mani_id,
                            'sub_head_id' => 40,
                            'unit' => $r->holidayTotalLocalTruck,
                            'charge_per_unit' => $r->local_holiday_charge,
                            'tcharge' => $r->TotalLocalHolidayCharge,
                            'partial_status' => $r->partial_status,
                            'port_id' => $port_id
                        ]
                    );
                }
            } //Holiday Local Truck End

            if(in_array(38, $exitingSubheadIds, TRUE)) { // Foreign Night Charge Start
                if($r->TotalForeignNightCharge) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 38)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->nightTotalForeignTruck,
                                'charge_per_unit' => $r->rate_of_night_charge,
                                'tcharge' => $r->TotalForeignNightCharge
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 38)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }  
            } else {
                if($r->TotalForeignNightCharge) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 38,
                                'unit' => $r->nightTotalForeignTruck,
                                'charge_per_unit' => $r->rate_of_night_charge,
                                'tcharge' => $r->TotalForeignNightCharge,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } // Foreign Night Charge End
            
            if(in_array(46, $exitingSubheadIds, TRUE)) { //Foreign Haltage Charge Start
                if($r->haltagesTotalForeignTruck) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 46)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->haltagesTotalForeignTruck,
                                'other_unit' => $r->haltagesTotalDayForeignTruck,
                                'charge_per_unit' => $r->foreign_haltage_charge,
                                'tcharge' => $r->TotalHaltageForeignCharge
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 46)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if($r->haltagesTotalForeignTruck) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 46,
                                'unit' => $r->haltagesTotalForeignTruck,
                                'other_unit' => $r->haltagesTotalDayForeignTruck,
                                'charge_per_unit' => $r->foreign_haltage_charge,
                                'tcharge' => $r->TotalHaltageForeignCharge,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Foreign Haltage Charge End

            if(in_array(44, $exitingSubheadIds, TRUE)) { //Local Haltage Charge Start
                if($r->haltagesTotalLocalTruck) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 44)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'other_unit' => $r->haltagesTotalDayLocalTruck,
                                'charge_per_unit' => $r->local_haltage_charge,
                                'tcharge' => $r->TotalHaltageLocalCharge
                            ]
                        );
                } else {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 44)
                        ->where('port_id', $port_id)
                        ->delete();
                }
            } else {
                if($r->haltagesTotalLocalTruck) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 44,
                                'unit' => $r->haltagesTotalLocalTruck,
                                'other_unit' => $r->haltagesTotalDayLocalTruck,
                                'charge_per_unit' => $r->local_haltage_charge,
                                'tcharge' => $r->TotalHaltageLocalCharge,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            }   //Local Haltage Charge End

            if(in_array(50, $exitingSubheadIds, TRUE)) { // Foreign Weighment Charge Start
                if($r->weightmentChargesForeign) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 50)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->totalForeignTruck,
                                'other_unit' => 2,
                                'charge_per_unit' => $r->weightment_measurement_charges,
                                'tcharge' => $r->weightmentChargesForeign
                            ]
                        );
                } else {
                  DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 50)
                        ->where('port_id', $port_id)
                        ->delete();  
                }
            } else {
               if($r->weightmentChargesForeign) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 50,
                                'unit' => $r->totalForeignTruck,
                                'other_unit' => 2,
                                'charge_per_unit' => $r->weightment_measurement_charges,
                                'tcharge' => $r->weightmentChargesForeign,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                } 
            } // Foreign Weighment Charge End
            
            if(in_array(48, $exitingSubheadIds, TRUE)) { // Local Weighment Charge Start
                if($r->local_truck_weighment) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 48)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->local_truck_weighment,
                                'other_unit' => 2,
                                'charge_per_unit' => $r->weightment_measurement_charges,
                                'tcharge' => $r->weightmentChargesLocal
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 48)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if($r->local_truck_weighment) {
                    DB::table('assesment_details')->insert(
                        [
                            'manif_id' => $r->Mani_id,
                            'sub_head_id' => 48,
                            'unit' => $r->local_truck_weighment,
                            'other_unit' => 2,
                            'charge_per_unit' => $r->weightment_measurement_charges,
                            'tcharge' => $r->weightmentChargesLocal,
                            'partial_status' => $r->partial_status,
                            'port_id' => $port_id
                        ]
                    );
                }
            } // Local Weighment Charge End

            if(in_array(52, $exitingSubheadIds, TRUE)) { //Document Charges Start
                if($r->numberOfDocuments > 0) {
                    DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 52)
                        ->where('port_id', $port_id)
                        ->update(
                            [
                                'unit' => $r->numberOfDocuments,
                                'charge_per_unit' => $r->documentCharges,
                                'tcharge' => $r->totalDocumentCharges
                            ]
                        );
                } else {
                   DB::table('assesment_details')
                        ->where('manif_id', $r->Mani_id)
                        ->where('partial_status', $r->partial_status)
                        ->where('sub_head_id', 52)
                        ->where('port_id', $port_id)
                        ->delete(); 
                }
            } else {
                if ($r->numberOfDocuments > 0) {
                    DB::table('assesment_details')
                        ->insert(
                            [
                                'manif_id' => $r->Mani_id,
                                'sub_head_id' => 52,
                                'unit' => $r->numberOfDocuments,
                                'charge_per_unit' => $r->documentCharges,
                                'tcharge' => $r->totalDocumentCharges,
                                'partial_status' => $r->partial_status,
                                'port_id' => $port_id
                            ]
                        );
                }
            } //Document Charges End   
        } 
    }

    public function getItemListData($item) {
        $param = $item . '%';
        $itemList = DB::select('SELECT * FROM item_codes AS i WHERE i.Description LIKE ?', [$param]);
        return json_encode($itemList);
    }

    public function getItemData($id) {
        $port_id = Session::get('PORT_ID');
        $itemList = DB::select('SELECT id.yard_shed, id.dangerous,id.tariff_good_id, id.item_Code_id, id.item_quantity, id.item_type,
                  ic.Description, tg.particulars AS tariff_good, id.id AS it_id, tg.id AS tarrif_good_id 
                  FROM item_details AS id
                  JOIN tariff_goods tg ON id.tariff_good_id = tg.id 
                  JOIN item_codes ic ON id.item_Code_id = ic.id 
                  WHERE id.manf_id=? AND id.port_id=?', [$id, $port_id]);

        if (!$itemList) {
            $itemList = DB::select('SELECT  c.id,c.cargo_name,m.gweight,
              (CASE WHEN m.gweight >(SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) THEN m.gweight
                          ELSE (SELECT SUM(truck_entry_regs.tweight_wbridge) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) END) AS chargeable_weight
                FROM manifests m
                INNER JOIN cargo_details c
                ON FIND_IN_SET(c.id, m.goods_id) > 0
                WHERE m.id=? AND m.port_id=? LIMIT 1', [$id, $port_id]);

            return Response::json($itemList, 203);
        } else {
            return json_encode($itemList);
        }
    }

    public function getCargoDetailsData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $year = date('Y');

        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->manifest_no);

        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $check_the_port_has_tariff = DB::select('SELECT COUNT(tg.id) AS found_tariff FROM tariff_goods AS tg 
                                                      JOIN tariff_schedules_and_charges AS tsac ON tsac.tariff_good_id = tg.id
                                                      WHERE tg.port_id=? AND tg.year=?', [$port_id, $year]);
        if($check_the_port_has_tariff[0]->found_tariff > 0) {
            $port_id_for_tariff = $port_id;
        } else {
            $port_id_for_tariff = -1;
        }
        $tariff_goods_details = DB::select('SELECT tg.id AS tariff_id,
                      tg.particulars AS tariff_good_name, tg.year AS tariff_year, tgf.duration AS free_time_duration, tgf.flag AS free_flag,
                      (SELECT GROUP_CONCAT(tsac.slab,\'-> Duration:\',tsac.from,\' to \', 
                      CASE WHEN tsac.to = -1 THEN \'Onward\' ELSE tsac.to END ,\', Charge:\', tsac.shed_charge 
                      SEPARATOR \' | \')FROM tariff_schedules_and_charges AS tsac WHERE tsac.tariff_good_id = tg.id) AS shed_charge,
                      (SELECT GROUP_CONCAT(tsac.slab,\'-> Duration:\',tsac.from,\' to \', 
                      CASE WHEN tsac.to = -1 THEN \'Onward\' ELSE tsac.to END ,\', Charge:\', tsac.yard_charge 
                      SEPARATOR \' | \')FROM tariff_schedules_and_charges AS tsac WHERE tsac.tariff_good_id = tg.id) AS yard_charge
                      FROM tariff_goods AS tg 
                      JOIN tariff_goods_freetimes AS tgf ON tg.id = tgf.tariff_good_id
                      WHERE tg.year=? AND tg.port_id=?', [$year, $port_id_for_tariff]);
        return $tariff_goods_details;
    }

    public function getManifestAllweights($manifest_id) {
        $port_id = Session::get('PORT_ID');
        $data = DB::select('SELECT m.gweight, 
                            (SELECT SUM(truck_entry_regs.tweight_wbridge) 
                            FROM truck_entry_regs WHERE truck_entry_regs.manf_id=m.id) AS net_weight,
                            (SELECT SUM(IFNULL(syw.unload_labor_weight,0) + IFNULL(syw.unload_equip_weight,0)) 
                            FROM shed_yard_weights AS syw 
                            JOIN yard_details AS yd ON yd.id = syw.unload_yard_shed
                            JOIN truck_entry_regs AS t ON t.id =  syw.truck_id
                            WHERE yd.yard_shed = 1 AND t.manf_id = m.id ) AS shed_weight,
                            (SELECT SUM(IFNULL(syw.unload_labor_weight,0) + IFNULL(syw.unload_equip_weight,0)) 
                            FROM shed_yard_weights AS syw 
                            JOIN yard_details AS yd ON yd.id = syw.unload_yard_shed
                            JOIN truck_entry_regs AS t ON t.id =  syw.truck_id
                            WHERE yd.yard_shed = 0 AND t.manf_id = m.id ) AS yard_weight
                            FROM manifests AS m
                            WHERE m.id=? AND m.port_id=?',[$manifest_id, $port_id]);
        return json_encode($data);
    }

    public function saveItemsData(Request $req) {
        $port_id = Session::get('PORT_ID');
        if($req->yard_shed == 1) { //shed slection
            $shed_count = DB::select('SELECT COUNT(shed_yard_weights.id) AS shed_count, manifests.self_flag
                            FROM manifests
                            INNER JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
                            LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
                            LEFT JOIN yard_details ON yard_details.id =  shed_yard_weights.unload_yard_shed
                            WHERE manifests.id = ? AND yard_details.yard_shed = 1 AND manifests.port_id=? 
                            AND yard_details.port_id=? AND truck_entry_regs.port_id=? 
                            AND shed_yard_weights.port_id=?',[$req->manf_id, $port_id, $port_id, $port_id, $port_id]);
            if($shed_count[0]->shed_count == 0 && $shed_count[0]->self_flag == 0) {
                return Response::json(['receive_error' => 'manifest is not received at shed.'], 207);
            }
        } else if($req->yard_shed == 0) { //yard selection
            $yard_count = DB::select('SELECT COUNT(shed_yard_weights.id) AS yard_count, manifests.self_flag
                            FROM manifests
                            INNER JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
                            LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
                            LEFT JOIN yard_details ON yard_details.id =  shed_yard_weights.unload_yard_shed
                            WHERE manifests.id = ? AND yard_details.yard_shed = 0 AND manifests.port_id=? 
                            AND yard_details.port_id=? AND truck_entry_regs.port_id=? 
                            AND shed_yard_weights.port_id=?',[$req->manf_id, $port_id, $port_id, $port_id, $port_id]);
            if($yard_count[0]->yard_count == 0 && $yard_count[0]->self_flag == 0) {
                return Response::json(['receive_error' => 'manifest is not received at yard.'], 207);
            }
        }

        $item_Code_id = $req->item_Code_id;

        if ($req->new_item) {
            $item_exist = DB::select('SELECT ic.id  FROM item_codes ic WHERE ic.Description=?', [$req->new_item]);

            if ($item_exist != [])//exist
            {
                //return Response::json(['duplicate' => 'duplicate'], 209);
                $item_Code_id = $item_exist[0]->id;

            } else {
                $item_Code_id = DB::table('item_codes')->insertGetId([
                    'Description' => $req->new_item,
                    'Code' => $req->tariff_good_id
                ]);
            }
        }


        DB::table('item_details')->insert([

            'manf_id' => $req->manf_id,
            'item_type' => $req->item_type,
            'yard_shed' => $req->yard_shed,
            'item_quantity' => $req->item_quantity,
            'tariff_good_id' => $req->tariff_good_id,
            'item_Code_id' => $item_Code_id,
            'dangerous' => $req->dangerous,
            'port_id'=> $port_id
        ]);

        return Response::json(['success' => 'success'], 200);

    }

    public function updateItemsInformation(Request $req) {
        $port_id = Session::get('PORT_ID');
        if($req->yard_shed == 1) { //shed slection
            $shed_count = DB::select('SELECT COUNT(shed_yard_weights.id) AS shed_count, manifests.self_flag
                            FROM manifests
                            INNER JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
                            LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
                            LEFT JOIN yard_details ON yard_details.id =  shed_yard_weights.unload_yard_shed
                            WHERE manifests.id = ? AND yard_details.yard_shed = 1 AND manifests.port_id=? AND yard_details.port_id=? AND truck_entry_regs.port_id=? AND shed_yard_weights.port_id=?',[$req->manf_id, $port_id, $port_id, $port_id, $port_id]);
            if($shed_count[0]->shed_count == 0 && $shed_count[0]->self_flag == 0) {
                return Response::json(['receive_error' => 'manifest is not received at shed.'], 207);
            }
        } else if($req->yard_shed == 0) { //yard selection
            $yard_count = DB::select('SELECT COUNT(shed_yard_weights.id) AS yard_count, manifests.self_flag
                            FROM manifests
                            INNER JOIN truck_entry_regs ON truck_entry_regs.manf_id = manifests.id
                            LEFT JOIN shed_yard_weights ON shed_yard_weights.truck_id = truck_entry_regs.id
                            LEFT JOIN yard_details ON yard_details.id =  shed_yard_weights.unload_yard_shed
                            WHERE manifests.id = ? AND yard_details.yard_shed = 0 AND manifests.port_id=? AND yard_details.port_id=? AND truck_entry_regs.port_id=? AND shed_yard_weights.port_id=?',[$req->manf_id, $port_id, $port_id, $port_id, $port_id]);
            if($yard_count[0]->yard_count == 0 && $yard_count[0]->self_flag == 0) {
                return Response::json(['receive_error' => 'manifest is not received at yard.'], 207);
            }
        }

        $item_Code_id = $req->item_Code_id;

        if ($req->new_item) {
            $item_exist = DB::select('SELECT ic.id  FROM item_codes ic WHERE ic.Description=?', [$req->new_item]);

            if ($item_exist != [])//exist
            {
                return Response::json(['duplicate' => 'duplicate'], 209);
            } else {
                $item_Code_id = DB::table('item_codes')->insertGetId([
                    'Description' => $req->new_item,
                    'Code' => $req->tariff_good_id
                ]);
            }
        }


        DB::table('item_details')
            ->where('id', $req->it_id)
            ->update(
                [
                    'manf_id' => $req->manf_id,
                    'item_type' => $req->item_type,
                    'yard_shed' => $req->yard_shed,
                    'item_quantity' => $req->item_quantity,
                    'tariff_good_id' => $req->tariff_good_id,
                    'item_Code_id' => $item_Code_id,
                    'dangerous' => $req->dangerous,
                    'port_id'=> $port_id
                ]
            );

        return Response::json(['success' => 'success'], 200);

    }


    public function deleteItemFromAssessment(Request $r) {
        DB::table('item_details')->where('id', $r->item_details_id)->delete();
        DB::table('item_codes')->where('id', $r->item_Code_id)->delete();
        return Response::json(['success' => 'success'], 200);

    }

    public function changeBassisOfChargeOption(Request $r) {
        $port_id = Session::get('PORT_ID');
        DB::table('manifests as m')
            ->where('m.id', $r->manifest_id)
            ->where('m.port_id', $port_id)
            ->update(['m.gweight' => $r->bassisOfCharge
            ]);

        return Response::json(['k' => 'k'], 200);
    }

    public function getPreviousDocumentDetails(Request $r) {
        $port_id = Session::get('PORT_ID');
        $getPreviousDocumentDetails = DB::select('SELECT *
                                                  FROM assessment_documents dc WHERE dc.manifest_id=? AND dc.partial_status=? AND dc.port_id=?
                                                  ORDER BY dc.id DESC LIMIT 1', [$r->manifese_id, $r->partial_status, $port_id]);
        return json_encode($getPreviousDocumentDetails);
    }

    public function saveDocumentData(Request $r) {
        $user = Auth::user()->id;
        $port_id = Session::get('PORT_ID');
        $time = date('Y-m-d H:i:s');
        if ($r->document_id == null) {
            $saveDocumentData = DB::table('assessment_documents')
                ->insert([
                    'assessment_documents.manifest_id' => $r->manifest_id,
                    'assessment_documents.document_name' => $r->document_name,
                    'assessment_documents.number_of_document' => $r->number_of_document,
                    'partial_status' => $r->partial_status ? $r->partial_status : 0,
                    'assessment_documents.document_charge' => $r->document_charge,
                    'assessment_documents.created_by' => $user,
                    'assessment_documents.created_at' => $time,
                    'assessment_documents.port_id' => $port_id
                ]);
        } else {
            $saveDocumentData = DB::table('assessment_documents')
                ->where('assessment_documents.id', $r->document_id)
                ->update([
                    'assessment_documents.manifest_id' => $r->manifest_id,
                    'assessment_documents.document_name' => $r->document_name,
                    'assessment_documents.number_of_document' => $r->number_of_document,
                    'partial_status' => $r->partial_status ? $r->partial_status : 0,
                    'assessment_documents.document_charge' => $r->document_charge,
                    'assessment_documents.updated_by' => $user,
                    'assessment_documents.updated_at' => $time
                ]);
        }

        if ($saveDocumentData) {
            return "Success";
        }
    }

    public function getAssessmentSheetReport($manifest, $truck, $year, $partial_status) {
        //dd();
        //return $partial_status;
        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $port_id = Session::get('PORT_ID');
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }
        $todayWithTime = date('Y-m-d h:i:s a');
        $TotalAssessmentValue = 0;
        $permitted = false;
        $userRoleId = Auth::user()->role->id;

        $manifest_id = DB::table('manifests AS m')//check if assessment done for the manifest
            ->where('m.manifest', $mani_no)
            ->where('m.port_id', $port_id)
            ->select('m.id', 'm.transshipment_flag')
            ->get();

        if ($manifest_id == '[]') {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not found in our record!']);
        }

        $checkAssSave = DB::table('assesment_details as assd')
            ->where('assd.manif_id', $manifest_id[0]->id)
            ->where('assd.port_id', $port_id)
            ->where('assd.partial_status', $partial_status)
            ->select('assd.id')
            ->get();
        if($partial_status == 1) {
            $errorMessage = 'sorry! manifest no. ' . $mani_no . ' is not saved yet!';
        } else {
            $errorMessage = 'sorry! manifest no. ' . $mani_no . ' with '.($partial_status-1).' Partial is not saved yet!';
        }
        if ($checkAssSave == '[]') {
            return view('default.assessment.assessment-not-done', ['errorMessage' => $errorMessage]);
        }
//Permission related logic----
        $transshipmentFlag = $manifest_id[0]->transshipment_flag;
        if ($transshipmentFlag && ($userRoleId == 12 || $userRoleId == 23)) {
            $permitted = false;
        } else if (!$transshipmentFlag && ($userRoleId == 9 || $userRoleId == 21 || $userRoleId == 11)) {
            $permitted = true;
        }
        if ($userRoleId == 1) {
            $permitted = true;
        }


        //=====================Global Variable=======================
        $vat_flag = null;
        //WareHouse Rent
        $totalWarehouseRent = 0;
        //offload------------------
        $OffloadLabour = 0;
        $OffLoadingEquip = 0;
        $offloadEquipShiftingFlag = null;
        $OffloadLabourCharge = 0;
        $OffLoadingEquipCharge = 0;
        $TotalForOffloadLabour = 0;
        $TotalForOffloadEquip = 0;

        //Load------------------
        $loadLabour = 0;
        $loadLabourCharge = 0;
        $TotalForloadLabour = 0;

        $loadEquip = 0;
        $loadingEquipCharge = 0;
        $TotalForloadEquip = 0;
        $approximate_delivery_type = null;
        $loading_shifting = null;


        //Handling and other Due===================
        //entrace fee----------
        $entranceTotalForeignTruck = 0;
        $entranceFeeForeign = 0;
        $totalEntranceFeeForeign = 0;

        $entranceTotalLocalTruck = 0;
        $entranceFeeLocalTruck = 0;
        $totalEntranceFeeLocal = 0;

        $totalLocalVan = 0;
        $entranceFeeVan = 0;
        $totalLocalVanEntranceFee = 0;

        //Carpanter Charge------------
        $carpenterOPPackages = null;
        $carpenterChargesOpenClose = null;
        $totalCarpenterChargesOpenClose = 0;

        $carpenterRepairPackages = null;
        $carpenterChargesRepair = null;
        $totalCarpenterChargesRepair = 0;

        //Holiday Charge-----------
        $holidayCharge = 0;
        $holidayTotalTruck = 0;
        $totalHolidayCharge = 0;
        $holiday = null;

        //Haltage  Charge -------------
        $haltage_truck_foreign = 0;
        $haltage_day_foreign = 0;
        $haltage_charge_foreign = 0;
        $haltage_total_foreign = 0;

        $haltage_truck_local = 0;
        $haltage_day_local = 0;
        $haltage_charge_local = 0;
        $haltage_total_local = 0;


        //weighment------------

        $weighmentChargeForeign = 0;
        $totalForeignTruckForWeighment = 0;
        $totalWeighmentChargeForeign = 0;

        $totalLocalTruckForWeighment = 0;
        $weighmentChargeLocal = 0;
        $totalweightmentChargesLocal = 0;

        //Document Charge-----------
        $totalDocumentCharge = 0;
        $number_of_documents = 0;
        $document_charge = 0;

        //Night Charge
        $NightTotalTruck = 0;
        $NightCharge = 0;
        $TotalNightCharge = 0;

//==================================ASSESSMENT DETAILS==========================================
        $assessmentDetails = DB::select('SELECT * FROM assesment_details AS ad 
                                        WHERE ad.manif_id=? AND ad.partial_status=? AND ad.port_id=?', [$manifest_id[0]->id, $partial_status, $port_id]);

        $getAssessmentTblData = DB::select("SELECT a.yearly_serial, a.assessment_values, 
                                    a.warehouse_details, a.charge_year, a.updated_at AS prepared_at, 
                                    u.name AS prepared_by,
                                    CASE TRIM(u.user_type) WHEN 'port' THEN
                                       (SELECT d.designation FROM designations AS d
                                       JOIN employee_designations AS ed ON ed.desig_id=d.id
                                       WHERE ed.employee_id = u.port_employee_id
                                       ORDER BY ed.id DESC LIMIT 1)
                                    WHEN 'c&f' THEN
                                       (SELECT ce.designation FROM cnf_employees AS ce WHERE ce.id = u.cnf_employee_id)
                                    WHEN 'custom' THEN
                                       (SELECT cue.designation FROM custom_employees AS cue WHERE cue.id = u.custom_employee_id)
                                    ELSE NULL
                                    END AS prepared_by_designation,
                                    CASE TRIM(u.user_type) WHEN 'port' THEN
                                       (SELECT p.port_name FROM ports AS p
                                       JOIN employee_histories AS eh ON eh.port_id = p.id
                                       WHERE eh.employee_id = u.port_employee_id
                                       ORDER BY eh.id DESC LIMIT 1)
                                    WHEN 'c&f' THEN
                                       (SELECT cd.cnf_name FROM cnf_details AS cd
                                        JOIN cnf_employees AS ce ON ce.cnf_detail_id = cd.id 
                                        WHERE ce.id = u.cnf_employee_id
                                        ORDER BY ce.id DESC LIMIT 1)
                                    WHEN 'custom' THEN
                                       (SELECT cue.organization FROM custom_employees AS cue WHERE cue.id = u.custom_employee_id)
                                    ELSE NULL
                                    END AS prepared_by_organization
                                    FROM assessments AS a 
                                    JOIN users AS u ON u.id = a.created_by
                                    WHERE a.manifest_id = ?
                                    AND a.partial_status = ? AND a.port_id = ?
                                    ORDER BY a.id DESC LIMIT 1", [$manifest_id[0]->id, $partial_status, $port_id]);
        $manifestDetails = $this->assessment_base_controller->manifestDetailsForAssessment($mani_no, $partial_status);
        $transshipmentFlag = $manifestDetails[0]->transshipment_flag ? true : false;

        if($assessmentDetails) {
            foreach ($assessmentDetails as $k => $v) {
                $subHeadId = $v->sub_head_id;

                if ($subHeadId == 2) {//WareHouse Rent
                    // $OffloadLabour = $v->unit;
                    // $OffloadLabourCharge = $v->charge_per_unit;
                    $totalWarehouseRent = $v->tcharge;
                }

                if ($subHeadId == 4) {//offload labour
                    $OffloadLabour = $v->unit;
                    $OffloadLabourCharge = $v->charge_per_unit;
                    $TotalForOffloadLabour = $v->tcharge;
                }

                if ($subHeadId == 6) {//offload equip
                    $OffLoadingEquip = $v->unit;
                    $offloadEquipShiftingFlag = $v->other_unit;//if shifting 1 otherwise null
                    $OffLoadingEquipCharge = $v->charge_per_unit;
                    $TotalForOffloadEquip = $v->tcharge;
                }
                //return $offloadEquipShiftingFlag;
                if ($subHeadId == 8) {//load labour
                    $loadLabour = $v->unit;
                    $loadLabourCharge = $v->charge_per_unit;
                    $TotalForloadLabour = $v->tcharge;
                }

                if ($subHeadId == 10) {//loading equip
                    $loadEquip = $v->unit;
                    $loading_shifting = $v->other_unit;//if shifting or not
                    $loadingEquipCharge = $v->charge_per_unit;
                    $TotalForloadEquip = $v->tcharge;
                }

                if ($subHeadId == 26) {//Entrance Fee-Foreign
                    $entranceTotalForeignTruck = $v->unit;
                    $entranceFeeForeign = $v->charge_per_unit;
                    $totalEntranceFeeForeign = $v->tcharge;
                }

                if ($subHeadId == 28) {//Entrance Fee-local truck
                    $entranceTotalLocalTruck = $v->unit;
                    $entranceFeeLocalTruck = $v->charge_per_unit;
                    $totalEntranceFeeLocal = $v->tcharge;
                }
                if ($subHeadId == 228) {//Entrance Fee-local truck
                    $totalLocalVan = $v->unit;
                    $entranceFeeVan = $v->charge_per_unit;
                    $totalLocalVanEntranceFee = $v->tcharge;
                }
                if ($subHeadId == 30) {//Carpanter Charge-open/close
                    $carpenterOPPackages = $v->unit;
                    $carpenterChargesOpenClose = $v->charge_per_unit;
                    $totalCarpenterChargesOpenClose = $v->tcharge;
                }

                if ($subHeadId == 32) {//Carpanter Charge-repair
                    $carpenterRepairPackages = $v->unit;
                    $carpenterChargesRepair = $v->charge_per_unit;
                    $totalCarpenterChargesRepair = $v->tcharge;
                }
                if ($subHeadId == 46) {// Haltage Charge-foreign
                    $haltage_truck_foreign = $v->unit;
                    $haltage_day_foreign = $v->other_unit;
                    $haltage_charge_foreign = $v->charge_per_unit;
                    $haltage_total_foreign = $v->tcharge;
                }

                if ($subHeadId == 44) {// Haltage Charge-Local
                    $haltage_truck_local = $v->unit;
                    $haltage_day_local = $v->other_unit;
                    $haltage_charge_local = $v->charge_per_unit;
                    $haltage_total_local = $v->tcharge;
                }

                if ($subHeadId == 48) {//local-weighment
                    $totalLocalTruckForWeighment = $v->unit;
                    $weighmentChargeLocal = $v->charge_per_unit;
                    $totalweightmentChargesLocal = $v->tcharge;
                }
                if ($subHeadId == 50) {//foreign-weighment
                    $weighmentChargeForeign = $v->charge_per_unit;
                    $totalForeignTruckForWeighment = $v->unit;
                    $totalWeighmentChargeForeign = $v->tcharge;
                }
                if($subHeadId == 38) { //Nightcharge-Foreign
                    $NightTotalTruck = $v->unit;
                    $NightCharge = $v->charge_per_unit;
                    $TotalNightCharge = $v->tcharge;
                }
                if($subHeadId == 52) { //Document Charge
                    $totalDocumentCharge = $v->tcharge;
                    $number_of_documents = $v->unit;
                    $document_charge = $v->charge_per_unit;
                }
            }
           // return $totalWarehouseRent;

            // //Night Charge-both foreign and local truck
            // $night = DB::select('SELECT SUM(a.tcharge) TotalNightCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
            //          FROM assesment_details a 
            //         JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
            //         JOIN acc_head AS h ON sh.head_id =h.id
            //         WHERE h.id=14 AND a.manif_id=? AND a.port_id=? AND a.partial_status=?', [$manifest_id[0]->id, $port_id, 0]);


            //Holiday Charge-both foreign and local truck
            $holiday = DB::select('SELECT SUM(a.tcharge) TotalHolidayCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=16 AND a.manif_id=? AND a.port_id=? AND a.partial_status=?', [$manifest_id[0]->id, $port_id, $partial_status]);



            // $documentCharge = DB::select('SELECT * FROM assesment_details WHERE sub_head_id=52 AND partial_status=? AND manif_id=? AND port_id=?', [0, $manifest_id[0]->id, $port_id]);
            // if ($documentCharge) {
            //     $totalDocumentCharge = $documentCharge[0]->tcharge;
            //     $number_of_documents = $documentCharge[0]->unit;
            //     $document_charge = $documentCharge[0]->charge_per_unit;
            // }

        }

        if($getAssessmentTblData[0]->warehouse_details == null) {
            $warehouse_details = json_decode($this->assessment_base_controller->getWarehouseDetails($mani_no, $partial_status));
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        } else {
            $warehouse_details = json_decode($getAssessmentTblData[0]->warehouse_details);
            $warehouse_rent_for_items = $warehouse_details->warehouse_rent_for_items;
            $item_wise_yard_details = $warehouse_details->item_wise_yard_details;
            $item_wise_shed_details = $warehouse_details->item_wise_shed_details;
            $free_items = $warehouse_details->free_items;
            $receive_date = $warehouse_details->receive_date;
            $delivery_date = $warehouse_details->delivery_date;
        }

        if($getAssessmentTblData[0]->assessment_values != null) {
           $assessment_values = json_decode($getAssessmentTblData[0]->assessment_values);
           $weight = $assessment_values->weight;
           $good_description = $assessment_values->good_description;
           $self_flag = $assessment_values->self_flag;
           //$rent_due_period = $assessment_values->rent_due_period;
           //$free_period = $assessment_values->free_period;
           $vat_flag = $assessment_values->vat;
        } else {
            return 'Please Contact to Maintanance Team!';
        }

        if($getAssessmentTblData[0]->prepared_by != null) {
            $prepared_by = $getAssessmentTblData[0]->prepared_by;
            $prepared_at = $getAssessmentTblData[0]->prepared_at;
            $prepared_by_designation = $getAssessmentTblData[0]->prepared_by_designation;
            $prepared_by_organization = $getAssessmentTblData[0]->prepared_by_organization;
        } else {
            $prepared_by = null;
            $prepared_at = null;
            $prepared_by_designation = null;
            $prepared_by_organization = null;
        }

        
        

//=================Add to Assessment Vlue==============================

//WareHouse Charge
        $TotalAssessmentValue += $totalWarehouseRent;

//handling charge --offload labour
        $TotalAssessmentValue += $TotalForOffloadLabour;
        //handling charge-- offload equipment
        $TotalAssessmentValue += $TotalForOffloadEquip;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadLabour;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadEquip;


//Entrance fee

        $TotalAssessmentValue += $totalEntranceFeeForeign;
        $TotalAssessmentValue += $totalEntranceFeeLocal;
        $TotalAssessmentValue += $totalLocalVanEntranceFee;

//Carpenter Charges------
        $TotalAssessmentValue += $totalCarpenterChargesOpenClose;
        $TotalAssessmentValue += $totalCarpenterChargesRepair;

//Weighment measurement  Charges
        $TotalAssessmentValue += $totalWeighmentChargeForeign;
        $TotalAssessmentValue += $totalweightmentChargesLocal;

        //holiday charge
        $TotalAssessmentValue += $holiday[0]->TotalHolidayCharge;

        //Night Charge
        $TotalAssessmentValue += $TotalNightCharge;

        //Haltage Charge
        $TotalAssessmentValue += $haltage_total_foreign;
        $TotalAssessmentValue += $haltage_total_local;

        //
        //Document Charge
        $TotalAssessmentValue += $totalDocumentCharge;

        //dd($totalWarehouseRent);
        //Tatal Calculation
        $TotalAssessmentValue = ceil($TotalAssessmentValue);//number_format(, 2, '.', '');
        if($vat_flag == 1 || is_null($vat_flag)) {
            $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        } else {
           $Vat = 0; 
        }
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);

        //   dd($getAssessmentTblData[0]->free_period);
        // dd($warehouseDetail['item_wise_shed_charge']);
        $pdf = PDF::loadView('default.assessment.reports.assessment-sheet-report', [
            'todayWithTime' => $todayWithTime,
            'permitted' => $permitted,
            'partial_status' => $partial_status,
            //manifest details
            'ManifestDate' => $manifestDetails[0]->manifest_date != null ? $manifestDetails[0]->manifest_date : '',
            'manifestNo' => $manifestDetails[0]->manifest_no,
            'transshipment' => $manifestDetails[0]->transshipment_flag ? true : false,

            'bill_entry_no' => $manifestDetails[0]->bill_entry_no,
            'bill_entry_date' => $manifestDetails[0]->bill_entry_date,
            'importer' => $manifestDetails[0]->importer,
            'exporter' => $manifestDetails[0]->exporter,
            'cnf_name' => $manifestDetails[0]->cnf_name,
            'package_type' => $manifestDetails[0]->package_type,
            'package_no' => $manifestDetails[0]->package_no,
            'custom_realise_order_No' => $manifestDetails[0]->custom_realise_order_No,
            'custom_realise_order_date' => $manifestDetails[0]->custom_realise_order_date,
            'description_of_goods' => $good_description,
            'totalItems' => $manifestDetails[0]->totalItems,
            'chargeable_ton' => $weight,
            'self_flag' => $self_flag,

            //Warehouse Rent Details--------------------
            "warehouse_rent_for_items" => $warehouse_rent_for_items,
            'free_items' => $free_items,
            'item_wise_shed_details' => $item_wise_shed_details,
            'item_wise_yard_details' => $item_wise_yard_details,
            'TotalSlabCharge' => $totalWarehouseRent,


            //'receive_date' => $warehouseDetail['receive_date'],
            'receive_date' => $receive_date,
            'delivery_date' => $delivery_date,
            'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,

//Handling ====Charge and other due==================

            //Offload--------------
            'OffloadLabour' => $OffloadLabour,
            'OffloadLabourCharge' => $OffloadLabourCharge,
            'TotalForOffloadLabour' => $TotalForOffloadLabour,

            'OffLoadingEquip' => $OffLoadingEquip,
            'OffLoadingEquipCharge' => $OffLoadingEquipCharge,
            'offloadEquipShiftingFlag' => $offloadEquipShiftingFlag > 0 ? true : false,
            'TotalForOffloadEquip' => $TotalForOffloadEquip,

            //Load-----------
            'loadLabour' => $loadLabour,
            'loadLabourCharge' => $loadLabourCharge,
            'TotalForloadLabour' => $TotalForloadLabour,

            'loadEquip' => $loadEquip,
            'loadingEquipCharge' => $loadingEquipCharge,
            'TotalForloadEquip' => $TotalForloadEquip,
            'loading_shifting' => $loading_shifting > 0 ? true : false,

//Entrance fee-------
            'entranceFeeForeign' => $entranceFeeForeign,
            'totalForeignTruckEntranceFee' => $totalEntranceFeeForeign,
            'entranceTotalForeignTruck' => $entranceTotalForeignTruck,

            'entranceTotalLocalTruck' => $entranceTotalLocalTruck,//  $getAssessmentTblData[0]->local_truck ? $getAssessmentTblData[0]->local_truck : $getAssessmentTblData[0]->transport_truck,
            'entranceFeeLocalTruck' => $entranceFeeLocalTruck,
            'totalLocalTruckEntranceFee' => $totalEntranceFeeLocal,

            'entranceTotalLocalVan' => $totalLocalVan,
            'entranceFeeVan' => $entranceFeeVan,
            'totalLocalVanEntranceFee' => $totalLocalVanEntranceFee,
//carpenter charge-open/close and repair

            'carpenterOPPackages' => $carpenterOPPackages,
            'carpenterChargesOpenClose' => $carpenterChargesOpenClose,
            'totalCarpenterChargesOpenClose' => $totalCarpenterChargesOpenClose,

            'carpenterRepairPackages' => $carpenterRepairPackages,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            'totalCarpenterChargesRepair' => $totalCarpenterChargesRepair,

//Weighment measurement  Charges
            'weighmentChargeForeign' => $weighmentChargeForeign,
            'totalForeignTruckForWeighment' => $totalForeignTruckForWeighment,
            'totalweightmentChargesForeign' => $totalWeighmentChargeForeign,

            'weighmentChargeLocal' => $weighmentChargeLocal,
            'totalLocalTruckForWeighment' => $totalLocalTruckForWeighment,
            'totalweightmentChargesLocal' => $totalweightmentChargesLocal,

//Night Charge
            'NightTotalTruck' => $NightTotalTruck,
            'NightCharge' => $NightCharge,
            'TotalNightCharge' => $TotalNightCharge,

//Holiday Charge
            'HolidayTotalTruck' => $holiday[0]->TotalTruck,
            'HolidayCharge' => $holiday[0]->charge_per_unit,
            'TotalHolidayCharge' => $holiday[0]->TotalHolidayCharge,

//Haltage Charge

            'haltage_truck_foreign' => $haltage_truck_foreign,
            'haltage_charge_foreign' => $haltage_charge_foreign,
            'haltage_total_foreign' => $haltage_total_foreign,
            'haltage_day_foreign' => $haltage_day_foreign,
            'haltage_truck_local' => $haltage_truck_local,
            'haltage_day_local' => $haltage_day_local,
            'haltage_charge_local' => $haltage_charge_local,
            'haltage_total_local' => $haltage_total_local,

            //$documentCharge
            'totalDocumentCharge' => $totalDocumentCharge,
            'number_of_documents' => $number_of_documents,
            'document_charge' => $document_charge,


//Total Calculation
            'TotalAssessmentValue' => $TotalAssessmentValue,
            'Vat' => $Vat,
            'vat_flag' => $vat_flag,
            'TotalAssessmentWithVat' => $TotalAssessmentWithVat,
            'role' => Auth::user()->role->name,
            'yearly_serial' => $getAssessmentTblData[0]->yearly_serial,
            'prepared_by' => $prepared_by,
            'prepared_at' => $prepared_at,
            'prepared_by_designation' => $prepared_by_designation,
            'prepared_by_organization' => $prepared_by_organization


        ])->setPaper([0, 0, 1100.661, 800.63], 'landscape');


        return $pdf->stream('Assessment Report' . '-' . $mani_no . '- ' . $todayWithTime . '.pdf');
    }


    public function AssessmentDebug()//this fuction is used to debug
    {
        $mani_no = '50681/3/2017';
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }
        $holidayForeign = DB::select('SELECT * FROM (
                      SELECT 
                      truck_no,DATE(receive_datetime) AS holiday,truck_type,
                      IF(DATE(receive_datetime) IN (SELECT DATE(hday) FROM holydays), charges, 0) AS holiday_Charge
                      FROM (
                      SELECT 
                      (SELECT rate_of_charges FROM handling_and_othercharges WHERE  charge_id=14 AND charges_year=?) AS charges,
                      truck_type,truck_no,receive_datetime
                      FROM manifests m 
                      INNER JOIN truck_entry_regs t ON t.manf_id=m.id
                      WHERE m.manifest=?
                      ) AS t
                      ) AS tm WHERE holiday_Charge !=0.00', [$year, $mani_no]);

        $holiday_f_t = [];
        $foreign_final_truck = [];
        $total_foreign_truck_times_for_holiday = 0;

        if (isset($holidayForeign) && !empty($holidayForeign)) {
            foreach ($holidayForeign as $holidayF) {
                if (!in_array($holidayF->holiday, $holiday_f_t)) {
                    $holiday_f_t[] = $holidayF->holiday;
                    $foreign_final_truck[] = $holidayF;
                    $total_foreign_truck_times_for_holiday++;
                }
            }
        }

//for local truck
        $holiday_l_t = [];
        $local_final_truck = [];
        $total_local_truck_times_for_holiday = 0;

        //  if (!$holidayForeign) {


        $holidayLocal = DB::select("SELECT * FROM (
                      SELECT 
                      DATE(approximate_delivery_date) AS holiday,
                      IF(DAYNAME(approximate_delivery_date) IN ('Saturday', 'Friday'), charges, 0) AS holyday_Charge,
                      IF(DATE(approximate_delivery_date) IN (SELECT DATE(hday) FROM holidays), charges, 0) AS holiday_Charge
                      FROM (
                      SELECT 
                      (SELECT rate_of_charges FROM handling_and_othercharges WHERE  charge_id=14 AND charges_year=?) AS charges,
                      approximate_delivery_date
                      FROM manifests m 
                      WHERE m.manifest=?
                      ) AS t
                      ) AS tm WHERE holyday_Charge !=0.00 OR holiday_Charge != 0.00", [$year, $mani_no]);


        // if (isset($holidayLocal) && !empty($holidayLocal)) {
        //     foreach ($holidayLocal as $holidayL) {
        //         if (!in_array($holidayL->holiday, $holiday_l_t)) {
        //             $holiday_l_t[] = $holidayL->holiday;
        //             $local_final_truck[] = $holidayL;
        //             $total_local_truck_times_for_holiday++;
        //         }
        //     }
        // }
        //  }


        if (count($holidayLocal) > 0 && count($foreign_final_truck) > 0) {//if ulnolad and load is same holiday then get one holiday charge/ and it will be shown in local holiday

            foreach ($foreign_final_truck as $kf=>$foreign) {

                foreach ($holidayLocal as $kl=>$local) {
                    //dd($foreign->holiday == $local->holiday);
                    if ($foreign->holiday == $local->holiday) {

                        array_splice($foreign_final_truck, $kf,1);
                        break;
                    }
                }
            }
        }

        // dd($foreign_final_truck);

        return json_encode(array($foreign_final_truck, $holidayLocal));
    }

    //Other Reports
    public function assessmentOtherReports()
    {

        $assessment_users = DB::select("SELECT u.name, u.id FROM users AS u
                    JOIN roles AS r ON r.id=u.role_id
                    WHERE r.id IN(9,12,23,21) AND u.user_status !=0");
        // dd($assessment_users);
        return view('default.assessment.others-reports-assessment', ['assessment_users' => $assessment_users]);
    }

    public function GetOtherDuesForAssesment(Request $r)
    {

        $results = DB::select('SELECT m.id AS m_id,m.manifest,m.manifest_date,m.package_no,
            (SELECT vatregs.NAME FROM vatregs WHERE vatregs.id=m.vatreg_id ) AS Importer,
            m.be_no AS c_no,m.be_date,t.posted_yard_shed, 
            COUNT(DISTINCT  t.id)  AS Foreign_Truck,COUNT(DISTINCT  d.id) AS Local_Truck,
            (( UNIX_TIMESTAMP(t.receive_datetime)- UNIX_TIMESTAMP(t.truckentry_datetime))/60/60/24) AS haltage
            

           FROM manifests m
           JOIN truck_entry_regs t ON m.id = t.manf_id
        
           LEFT JOIN truck_deliverys d ON m.id = d.manf_id
          
           WHERE m.manifest=?
           GROUP BY m.id', [$r->mani_No]);
        return json_encode($results);

    }


    /* public function GetHaltageforAssesment(Request $r)
     {
         $haltageForeign = DB::select('SELECT TotalForeignTruck,
                 (( UNIX_TIMESTAMP(truck_receivedate)- UNIX_TIMESTAMP(truck_entrydate))/60/60/24) AS HoltageDay
                  FROM(
                 SELECT
                 (SELECT truck_entry_regs.truckentry_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id ASC LIMIT 1)AS truck_entrydate,
                 (SELECT truck_entry_regs.receive_datetime FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id  ORDER BY truck_entry_regs.id DESC LIMIT 1)AS truck_receivedate,
                 (SELECT COUNT(truck_entry_regs.id) FROM  truck_entry_regs WHERE truck_entry_regs.manf_id=m.id )AS TotalForeignTruck

                 FROM manifests m
                 WHERE m.manifest=? ) t', [$r->mani_No]);




         return json_encode($haltageForeign);

     }*/

    public function getHandlingAndSomeOtherDues(Request $r)
    {
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }
        $getAllCharges = $this->globalFunctionController->getAllCharges($year);//get all charges from handling and other charge table
        $handling = $this->globalFunctionController->GetHandlingAndSomeOtherDuesForAssesment($r->mani_no);
        $parishableItem = null;//$this->globalFunctionController->CheckItemIsParishable($r->mani_no);
        $docunemtCharge = $this->globalFunctionController->GetDocumentChargeForAssessment($r->mani_no, $r->partial_status);
        $partial_number = DB::select('SELECT COUNT(DISTINCT DATE(delivery_dt)) AS dt
                FROM truck_deliverys trd
                INNER JOIN manifests m ON trd.manf_id=m.id
                WHERE manifest=?', [$r->mani_no]);

        return json_encode(array($handling, $parishableItem, $docunemtCharge, $getAllCharges, $partial_number));
    }


    public function getNightChargesForAssesment(Request $r)
    {
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $nightChargeForeign = DB::select('SELECT count(Shift=\'Night\') AS total_foreign_truck_night,Shift,
                              sum((CASE WHEN Shift=\'Night\' THEN charges  ELSE 0 END )) AS rate_of_night_charge
                              FROM(SELECT DISTINCT date(receive_datetime),
                              (CASE 
                               WHEN DATE_FORMAT(receive_datetime, \'%H:%i\')>=\'18:00\' OR  DATE_FORMAT(receive_datetime, \'%H:%i\')<=\'09:00\' 
                               THEN \'Night\' ELSE \'Day\'  END) AS Shift,
                              (SELECT rate_of_charges FROM handling_and_othercharges WHERE charge_id=16 AND charges_year=?) AS charges
                              
                              FROM manifests m 
                              INNER JOIN truck_entry_regs t ON t.manf_id=m.id 
                              WHERE m.manifest=? ) t WHERE Shift!=\'Day\'', [$year, $r->mani_no]);

        /*
                $nightChargeLocal = DB::select('SELECT truck_no,delivery_dt AS charges_time,Shift,
          (CASE WHEN Shift=\'Night\' THEN charges  ELSE 0 END ) AS Night_charges
          FROM(
          SELECT
          (CASE
           WHEN TIME(delivery_dt) BETWEEN \'18:00:00\' AND \'23:59:59\' THEN \'Night\' ELSE NULL END) AS Shift,
          (SELECT rate_of_charges FROM handling_and_othercharges WHERE id=16) AS charges,
           truck_no,delivery_dt
          FROM manifests m
          INNER JOIN truck_deliverys d ON d.manf_id=m.id
          WHERE m.manifest=?
          ) AS t WHERE Shift IS NOT NULL', [$r->mani_No]);
        */

        return json_encode($nightChargeForeign);

    }


    public function getHaltageChargesForAssessment(Request $r)
    {
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }

        $haltageForeign = DB::select('SELECT truck_no,tweight_wbridge,receive_weight,truckentry_datetime,receive_datetime,t.holtage_charge_flag,
                      (DATEDIFF(receive_datetime,truckentry_datetime)) AS haltage_days,
                      (SELECT rate_of_charges FROM handling_and_othercharges WHERE  charge_id=20 AND charges_year=?) AS rate_of_charges
                      FROM(
                      SELECT t.holtage_charge_flag,t.tweight_wbridge,t.receive_weight,
                      CONCAT(truck_type,\'-\',truck_no ) AS truck_no,truckentry_datetime,receive_datetime
                      FROM manifests m 
                      INNER JOIN truck_entry_regs t ON t.manf_id=m.id 
                      WHERE m.manifest=?
                      ) AS t', [$year, $r->mani_no]);

        /* $haltageLocal = DB::select('SELECT truck_no,delivery_req_dt,delivery_dt,entry_dt,
                       (DATEDIFF(delivery_dt,entry_dt)+1) AS haltage_days,
                       (SELECT rate_of_charges FROM handling_and_othercharges WHERE id=20) AS rate_of_charges
                       FROM(
                       SELECT
                       truck_no,delivery_req_dt,delivery_dt,entry_dt
                       FROM manifests m
                       INNER JOIN truck_deliverys d ON d.manf_id=m.id
                       WHERE m.manifest=?
                       ) AS t', [$r->mani_No]);*/
        $haltageLocal = DB::select('SELECT *, (SELECT rate_of_charges FROM handling_and_othercharges WHERE charge_id=20 AND charges_year=?) AS rate_of_charges
                                    FROM truck_deliverys AS td JOIN manifests AS m ON td.manf_id=m.id  
                                    WHERE m.manifest=? AND td.haltage_day!=0', [$year, $r->mani_no]);


        return json_encode(array($haltageForeign, $haltageLocal));

    }


    public function getHolidayChargesForAssesment(Request $r)
    {
        $year = date('Y');
        $assessmentCreatedYear = $this->globalFunctionController->getAassessmentCreatedYear($r->mani_no);
        if ($assessmentCreatedYear) {
            $year = $assessmentCreatedYear;
        }
        $holidayForeign = DB::select('SELECT * FROM (
                      SELECT 
                      truck_no,DATE(receive_datetime) AS holiday,truck_type,
                      IF(DATE(receive_datetime) IN (SELECT DATE(hday) FROM holydays), charges, 0) AS holiday_Charge
                      FROM (
                      SELECT 
                      (SELECT rate_of_charges FROM handling_and_othercharges WHERE  charge_id=14 AND charges_year=?) AS charges,
                      truck_type,truck_no,receive_datetime
                      FROM manifests m 
                      INNER JOIN truck_entry_regs t ON t.manf_id=m.id
                      WHERE m.manifest=?
                      ) AS t
                      ) AS tm WHERE holiday_Charge !=0.00', [$year, $r->mani_No]);

        $holiday_f_t = [];
        $foreign_final_truck = [];
        $total_foreign_truck_times_for_holiday = 0;

        if (isset($holidayForeign) && !empty($holidayForeign)) {
            foreach ($holidayForeign as $holidayF) {
                if (!in_array($holidayF->holiday, $holiday_f_t)) {
                    $holiday_f_t[] = $holidayF->holiday;
                    $foreign_final_truck[] = $holidayF;
                    $total_foreign_truck_times_for_holiday++;
                }
            }
        }

//for local truck
        $holiday_l_t = [];
        $local_final_truck = [];
        $total_local_truck_times_for_holiday = 0;

        //  if (!$holidayForeign) {


        $holidayLocal = DB::select("SELECT * FROM (
                      SELECT 
                      DATE(approximate_delivery_date) AS holiday,
                      IF(DAYNAME(approximate_delivery_date) IN ('Saturday', 'Friday'), charges, 0) AS holyday_Charge,
                      IF(DATE(approximate_delivery_date) IN (SELECT DATE(hday) FROM holidays), charges, 0) AS holiday_Charge
                      FROM (
                      SELECT 
                      (SELECT rate_of_charges FROM handling_and_othercharges WHERE  charge_id=14 AND charges_year=?) AS charges,
                      approximate_delivery_date
                      FROM manifests m 
                      WHERE m.manifest=?
                      ) AS t
                      ) AS tm WHERE holyday_Charge !=0.00 OR holiday_Charge != 0.00", [$year, $r->mani_No]);


        // if (isset($holidayLocal) && !empty($holidayLocal)) {
        //     foreach ($holidayLocal as $holidayL) {
        //         if (!in_array($holidayL->holiday, $holiday_l_t)) {
        //             $holiday_l_t[] = $holidayL->holiday;
        //             $local_final_truck[] = $holidayL;
        //             $total_local_truck_times_for_holiday++;
        //         }
        //     }
        // }
        //  }


        if (count($holidayLocal) > 0 && count($foreign_final_truck) > 0) {//if ulnolad and load is same holiday then get one holiday charge/ and it will be shown in local holiday

            foreach ($foreign_final_truck as $kf=>$foreign) {

                foreach ($holidayLocal as $kl=>$local) {
                    if ($foreign->holiday == $local->holiday) {
                        array_splice($foreign_final_truck, $kf,1);
                        break;
                    }
                }
            }
        }

        return json_encode(array($foreign_final_truck, $holidayLocal));

    }


    

    //Assessment Option Changing Function START=============================================================Option==================

    // public function changeReceiveDayOption(Request $r)
    // {

    //     DB::table('truck_entry_regs')
    //         ->where('truck_entry_regs.manf_id', $r->Manifest_id)
    //         ->update(['truck_entry_regs.receive_datetime' => $r->receive_date
    //         ]);

    //     return Response::json(['k' => 'k'], 200);
    // }



    //Assessment Option Changing Function END=================================================PDF start


    

    //======================Partial PDF Report=====================================================================

    public function partialAssessmentReport($manifest, $truck, $year, $nth)
    {

        $mani_no = (string)$manifest . "/" . (string)$truck . "/" . (string)$year;
        $partial_status = $nth;
        $todayWithTime = date('Y-m-d h:i:s a');
        $TotalAssessmentValue = 0;
        $permitted = false;
        $userRoleId = Auth::user()->role->id;


        $manifest_id = DB::table('manifests AS m')//check if assessment done for the manifest
        ->where('m.manifest', $mani_no)
            ->select('m.id', 'm.transshipment_flag')
            ->get();

        if ($manifest_id == '[]') {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not found in our record!']);
        }

        $checkAssSave = DB::table('assesment_details as assd')
            ->where('assd.manif_id', $manifest_id[0]->id)
            ->where('assd.partial_status', $partial_status)
            ->select('assd.id')
            ->get();

        if ($checkAssSave == '[]') {
            return view('default.assessment.assessment-not-done', ['errorMessage' => 'sorry! manifest no. ' . $mani_no . ' is not saved yet!']);
        }
//Permission related logic----
        $transshipmentFlag = $manifest_id[0]->transshipment_flag;
        if ($transshipmentFlag && ($userRoleId == 12 || $userRoleId == 23)) {
            $permitted = true;
        } else if (!$transshipmentFlag && ($userRoleId == 9 || $userRoleId == 21)) {
            $permitted = true;
        }
        if ($userRoleId == 1) {
            $permitted = true;
        }


        //=====================Global Variable=======================

        //WareHouse Rent
        $totalWarehouseRent = 0;
        //offload------------------
        $OffloadLabour = 0;
        $OffLoadingEquip = 0;
        $offloadEquipShiftingFlag = null;
        $OffloadLabourCharge = 0;
        $OffLoadingEquipCharge = 0;
        $TotalForOffloadLabour = 0;
        $TotalForOffloadEquip = 0;

        //Load------------------
        $loadLabour = 0;
        $loadLabourCharge = 0;
        $TotalForloadLabour = 0;

        $loadEquip = 0;
        $loadingEquipCharge = 0;
        $TotalForloadEquip = 0;
        $approximate_delivery_type = null;


        //Handling and other Due===================
        //entrace fee----------
        $entranceTotalForeignTruck = 0;
        $entranceFeeForeign = 0;
        $totalEntranceFeeForeign = 0;

        $entranceTotalLocalTruck = 0;
        $entranceFeeLocal = 0;
        $totalEntranceFeeLocal = 0;

        //Carpanter Charge------------
        $carpenterOPPackages = null;
        $carpenterChargesOpenClose = null;
        $totalCarpenterChargesOpenClose = 0;

        $carpenterRepairPackages = null;
        $carpenterChargesRepair = null;
        $totalCarpenterChargesRepair = 0;

        //Night Charge----------
        $night = null;

        //Holiday Charge-----------
        $holidayCharge = 0;
        $holidayTotalTruck = 0;
        $totalHolidayCharge = 0;
        $holiday = null;

        //Haltage  Charge -------------
        $haltage_truck_foreign = 0;
        $haltage_day_foreign = 0;
        $haltage_charge_foreign = 0;
        $haltage_total_foreign = 0;


        //weighment------------

        $weighmentChargeForeign = 0;
        $totalForeignTruckForWeighment = 0;
        $totalWeighmentChargeForeign = 0;

        $totalLocalTruckForWeighment = 0;
        $weighmentChargeLocal = 0;
        $totalweightmentChargesLocal = 0;

        //Document Charge-----------
        $totalDocumentCharge = 0;
        $number_of_documents = 0;
        $document_charge = 0;

        //Partial Truck Charge -------
        $countLocalTruck = 0;
        $truckEntranceFee = 0;
        $totalTruckEntranceFee = 0;

//==================================ASSESSMENT DETAILS==========================================

        $assessmentDetails = DB::select('SELECT * 
                                        FROM assesment_details AS ad 
                                        WHERE ad.manif_id=? AND ad.partial_status=?',
            [$manifest_id[0]->id, $partial_status]);


        $getAssessmentTblData = DB::select('SELECT date_of_unloading,free_period,rent_due_period,
                                        weight,good_description,no_of_pkg,local_truck 
                                        FROM assessments 
                                        WHERE manifest_id=? AND partial_status=? 
                                        ORDER BY id DESC LIMIT 1',
            [$manifest_id[0]->id, $partial_status]);

        if ($assessmentDetails) {
            foreach ($assessmentDetails as $k => $v) {
                $subHeadId = $v->sub_head_id;

                if ($subHeadId == 2) {//WareHouse Rent
                    // $OffloadLabour = $v->unit;
                    // $OffloadLabourCharge = $v->charge_per_unit;
                    $totalWarehouseRent = $v->tcharge;
                }

                if ($subHeadId == 4) {//offload labour
                    $OffloadLabour = $v->unit;
                    $OffloadLabourCharge = $v->charge_per_unit;
                    $TotalForOffloadLabour = $v->tcharge;
                }

                if ($subHeadId == 6) {//offload equip
                    $OffLoadingEquip = $v->unit;
                    $offloadEquipShiftingFlag = $v->other_unit;//if shifting 1 otherwise null
                    $OffLoadingEquipCharge = $v->charge_per_unit;
                    $TotalForOffloadEquip = $v->tcharge;
                }
                if ($subHeadId == 8) {//load labour
                    $loadLabour = $v->unit;
                    $loadLabourCharge = $v->charge_per_unit;
                    $TotalForloadLabour = $v->tcharge;
                }

                if ($subHeadId == 10) {//loading equip
                    $loadEquip = $v->unit;
                    $loadingEquipCharge = $v->charge_per_unit;
                    $TotalForloadEquip = $v->tcharge;
                }

                if ($subHeadId == 26) {//Entrance Fee-Foreign
                    $entranceTotalForeignTruck = $v->unit;
                    $entranceFeeForeign = $v->charge_per_unit;
                    $totalEntranceFeeForeign = $v->tcharge;
                }

                if ($subHeadId == 28) {//Entrance Fee-local
                    $entranceTotalLocalTruck = $v->unit;
                    $entranceFeeLocal = $v->charge_per_unit;
                    $totalEntranceFeeLocal = $v->tcharge;
                }
                if ($subHeadId == 30) {//Carpanter Charge-open/close
                    $carpenterOPPackages = $v->unit;
                    $carpenterChargesOpenClose = $v->charge_per_unit;
                    $totalCarpenterChargesOpenClose = $v->tcharge;
                }

                if ($subHeadId == 32) {//Carpanter Charge-repair
                    $carpenterRepairPackages = $v->unit;
                    $carpenterChargesRepair = $v->charge_per_unit;
                    $totalCarpenterChargesRepair = $v->tcharge;
                }
                if ($subHeadId == 46) {// Haltage Charge-foreign
                    $haltage_truck_foreign = $v->unit;
                    $haltage_day_foreign = $v->other_unit;
                    $haltage_charge_foreign = $v->charge_per_unit;
                    $haltage_total_foreign = $v->tcharge;
                }
                if ($subHeadId == 48) {//local-weighment
                    $totalLocalTruckForWeighment = $v->unit;
                    $weighmentChargeLocal = $v->charge_per_unit;
                    $totalweightmentChargesLocal = $v->tcharge;
                }
                if ($subHeadId == 50) {//foreign-weighment
                    $weighmentChargeForeign = $v->charge_per_unit;
                    $totalForeignTruckForWeighment = $v->unit;
                    $totalWeighmentChargeForeign = $v->tcharge;
                }


            }

            //Night Charge-both foreign and local truck
            $night = DB::select('SELECT SUM(a.tcharge) TotalNightCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=14 AND a.manif_id=? AND a.partial_status=?', [$manifest_id[0]->id, $partial_status]);


            //Holiday Charge-both foreign and local truck
            $holiday = DB::select('SELECT SUM(a.tcharge) TotalHolidayCharge,SUM(a.unit) AS TotalTruck,a.charge_per_unit
                     FROM assesment_details a 
                    JOIN acc_sub_head AS sh ON a.sub_head_id =sh.id
                    JOIN acc_head AS h ON sh.head_id =h.id
                    WHERE h.id=16 AND a.manif_id=? AND a.partial_status=?', [$manifest_id[0]->id, $partial_status]);


            $documentCharge = DB::select('SELECT * FROM assesment_details WHERE sub_head_id=52 AND partial_status=? AND manif_id=?', [$partial_status, $manifest_id[0]->id]);
            if ($documentCharge) {
                $totalDocumentCharge = $documentCharge[0]->tcharge;
                $number_of_documents = $documentCharge[0]->unit;
                $document_charge = $documentCharge[0]->charge_per_unit;
            }

        }

        $manifestDetails = $this->globalFunctionController->ManifestDetailsForAssessmentAss($mani_no);
        $warehouseDetail = $this->globalFunctionController->getWarehouseRentDetailsPartial($mani_no, $partial_status);

        $get_assessments_data = DB::select('SELECT * FROM assessments AS ass WHERE ass.manifest_id=? AND ass.partial_status=? ORDER BY ass.id DESC LIMIT 1', [$manifest_id[0]->id, $partial_status]);


        // Info About Partial Truck START
        $partialTruckInfo = DB::select('SELECT unit,charge_per_unit,tcharge FROM assesment_details WHERE manif_id=? AND partial_status=? AND sub_head_id=28', [$manifest_id[0]->id, $partial_status]);
        if ($partialTruckInfo) {
            $countLocalTruck = $partialTruckInfo[0]->unit;
            $truckEntranceFee = $partialTruckInfo[0]->charge_per_unit;
            $totalTruckEntranceFee = $partialTruckInfo[0]->tcharge;
        }
        //return $partialTruckInfo;
        // Info About Partial Truck END

        //  dd( $warehouseDetail['WareHouseRentDay']);

        $transshipmentFlag = $manifestDetails[0]->transshipment_flag ? true : false;


//=================Add to Assessment Vlue==============================

//WareHouse Charge
        $TotalAssessmentValue += $totalWarehouseRent;

//handling charge --offload labour
        $TotalAssessmentValue += $TotalForOffloadLabour;
        //handling charge-- offload equipment
        $TotalAssessmentValue += $TotalForOffloadEquip;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadLabour;
        //handling charge --load labour
        $TotalAssessmentValue += $TotalForloadEquip;

//Entrance fee
        /* $entranceFee = $HandlingOtherDue[0]->entrance_fee;
         $totalForeignTruck = $HandlingOtherDue[0]->foreign_truck;*/

        // if ($transshipment) {//============TRANSSHIPMENT
        //$totalLocalTruck = $HandlingOtherDue[0]->local_truck;
        // } else {
        // $totalLocalTruck = $HandlingOtherDue[0]->no_del_truck;
        // }


        $TotalAssessmentValue += $totalEntranceFeeForeign;
        $TotalAssessmentValue += $totalEntranceFeeLocal;

//Carpenter Charges------
        $TotalAssessmentValue += $totalCarpenterChargesOpenClose;
        $TotalAssessmentValue += $totalCarpenterChargesRepair;

//Weighment measurement  Charges
        $TotalAssessmentValue += $totalWeighmentChargeForeign;
        $TotalAssessmentValue += $totalweightmentChargesLocal;

        //holiday charge
        $TotalAssessmentValue += $holiday[0]->TotalHolidayCharge;

        //Night Charge
        $TotalAssessmentValue += $night[0]->TotalNightCharge;

        //Haltage Charge
        $TotalAssessmentValue += $haltage_total_foreign;

        //Document Charge
        $TotalAssessmentValue += $totalDocumentCharge;


        //Tatal Calculation
        $TotalAssessmentValue = ceil($TotalAssessmentValue);//number_format(, 2, '.', '');
        $Vat = ceil((($TotalAssessmentValue * 15) / 100));
        // $Vat = number_format((($TotalAssessmentValue * 15) / 100), 2, '.', '');
        $TotalAssessmentWithVat = ceil($TotalAssessmentValue + $Vat);

        //dd($get_assessments_data[0]->weight);
        $pdf = PDF::loadView('default.assessment.reports.assessment-sheet-report', [
            'todayWithTime' => $todayWithTime,
            'permitted' => $permitted,
            'partial_status' => $partial_status,
            //manifest details
            'ManifestDate' => $manifestDetails[0]->manifest_date != null ? $manifestDetails[0]->manifest_date : '',
            'manifestNo' => $manifestDetails[0]->manifest_no,
            'transshipment' => $manifestDetails[0]->transshipment_flag ? true : false,

            'bill_entry_no' => $manifestDetails[0]->bill_entry_no,
            'bill_entry_date' => $manifestDetails[0]->bill_entry_date,
            'importer' => $manifestDetails[0]->importer,
            'exporter' => $manifestDetails[0]->exporter,
            'cnf_name' => $manifestDetails[0]->cnf_name,
            'package_type' => $manifestDetails[0]->package_type,
            'package_no' => $getAssessmentTblData[0]->no_of_pkg,

            'custom_realise_order_No' => $manifestDetails[0]->custom_realise_order_No,
            'custom_realise_order_date' => $manifestDetails[0]->custom_realise_order_date,
            'description_of_goods' => $manifestDetails[0]->description_of_goods,
            'totalItems' => $manifestDetails[0]->totalItems,
            'chargeable_ton' => $get_assessments_data[0]->weight,

            //Warehouse Rent Details--------------------
            "RentDay" => $getAssessmentTblData[0]->rent_due_period,
            "WareHouseRentDay" => $warehouseDetail['WareHouseRentDay'],
            //"WareHouseRentDay" => $warehouseDetail['WareHouseRentDay'],
            'FreeEndDate' => null,
            'item_wise_charge' => $warehouseDetail['item_wise_charge'],
            'ChargeStartDay' => $warehouseDetail['ChargeStartDay'],

            'firstSlabDay' => $warehouseDetail['FirstSlabDay'],
            'secondSlabDay' => $warehouseDetail['SecondSlabDay'],
            'thirdSlabDay' => $warehouseDetail['thirdSlabDay'],

            "firsrSlabTotalCharge" => $warehouseDetail['FirstSlabCharge'],
            "SecondSlabTotalCharge" => $warehouseDetail['SecondSlabCharge'],
            'ThirdSlabTotalCharge' => $warehouseDetail['ThirdSlabCharge'],
            'TotalSlabCharge' => $warehouseDetail['TotalSlabCharge'],


            // 'receive_date' => $warehouseDetail['receive_date'],
            'receive_date' => $getAssessmentTblData[0]->date_of_unloading,
            'deliver_date' => $warehouseDetail['deliver_date'],
            'goods_id' => $warehouseDetail['goods_id'],
            'posted_yard_shed' => $manifestDetails[0]->posted_yard_shed,

//Handling ====Charge and other due==================

            //Offload--------------
            'OffloadLabour' => $OffloadLabour,
            'OffloadLabourCharge' => $OffloadLabourCharge,
            'TotalForOffloadLabour' => $TotalForOffloadLabour,

            'OffLoadingEquip' => $OffLoadingEquip,
            'OffLoadingEquipCharge' => $OffLoadingEquipCharge,
            'offloadEquipShiftingFlag' => $offloadEquipShiftingFlag,
            'TotalForOffloadEquip' => $TotalForOffloadEquip,

            //Load-----------
            'loadLabour' => $loadLabour,
            'loadLabourCharge' => $loadLabourCharge,
            'TotalForloadLabour' => $TotalForloadLabour,

            'loadEquip' => $loadEquip,
            'loadingEquipCharge' => $loadingEquipCharge,
            'TotalForloadEquip' => $TotalForloadEquip,

//Entrance fee-------
            'entranceFeeForeign' => $entranceFeeForeign,
            //'entranceFeeLocal' => $entranceFeeLocal,
            'entranceFeeLocal' => $truckEntranceFee,
            'entranceTotalForeignTruck' => $entranceTotalForeignTruck,
            'entranceTotalLocalTruck' => $countLocalTruck,
            //'entranceTotalLocalTruck' => $getAssessmentTblData[0]->local_truck,
            //'entranceTotalLocalTruck' => $entranceTotalLocalTruck,
            'totalForeignTruckEntranceFee' => $totalEntranceFeeForeign,
            //'totalLocalTruckEntranceFee' => $totalEntranceFeeLocal,
            'totalLocalTruckEntranceFee' => $totalTruckEntranceFee,

//carpenter charge-open/close and repair

            'carpenterOPPackages' => $carpenterOPPackages,
            'carpenterChargesOpenClose' => $carpenterChargesOpenClose,
            'totalCarpenterChargesOpenClose' => $totalCarpenterChargesOpenClose,

            'carpenterRepairPackages' => $carpenterRepairPackages,
            'carpenterChargesRepair' => $carpenterChargesRepair,
            'totalCarpenterChargesRepair' => $totalCarpenterChargesRepair,

//Weighment measurement  Charges
            'weighmentChargeForeign' => $weighmentChargeForeign,
            'totalForeignTruckForWeighment' => $totalForeignTruckForWeighment,
            'totalweightmentChargesForeign' => $totalWeighmentChargeForeign,

            'weighmentChargeLocal' => $weighmentChargeLocal,
            'totalLocalTruckForWeighment' => $totalLocalTruckForWeighment,
            'totalweightmentChargesLocal' => $totalweightmentChargesLocal,

//Night Charge
            'NightTotalTruck' => $night[0]->TotalTruck,
            'NightCharge' => $night[0]->charge_per_unit,
            'TotalNightCharge' => $night[0]->TotalNightCharge,

//Holiday Charge
            'HolidayTotalTruck' => $holiday[0]->TotalTruck,
            'HolidayCharge' => $holiday[0]->charge_per_unit,
            'TotalHolidayCharge' => $holiday[0]->TotalHolidayCharge,

//Haltage Charge
            /*'HaltageTotalTruck' => $haltage[0]->TotalTruck,
            'HaltageCharge' => $haltage[0]->charge_per_unit,
            'TotalHaltageCharge' => $haltage[0]->TotalHaltageCharge,
            'TotalHaltageDay' => $haltage[0]->TotalHaltage,*/
            'haltage_truck_foreign' => $haltage_truck_foreign,
            'haltage_charge_foreign' => $haltage_charge_foreign,
            'haltage_total_foreign' => $haltage_total_foreign,
            'haltage_day_foreign' => $haltage_day_foreign,


            //$documentCharge
            'totalDocumentCharge' => $totalDocumentCharge,
            'number_of_documents' => $number_of_documents,
            'document_charge' => $document_charge,


//Total Calculation
            'TotalAssessmentValue' => $TotalAssessmentValue,
            'Vat' => $Vat,
            'TotalAssessmentWithVat' => $TotalAssessmentWithVat,
            // 'TotalAssessmentInWord'=>$TotalAssessmentInWord

            'role' => Auth::user()->role->name


        ])->setPaper([0, 0, 1000.661, 800.63], 'landscape');


        return $pdf->stream('Assessment' . '-' . $mani_no . '.pdf');

    }










    //Document End

    /*
    $file = fopen("Truckentry.txt","w");
    echo fwrite($file,"Hello ".$IndTruck);
    fclose($file);
    */

//
//$requestData =  DB::table('manifests')
//->selectRaw('count(manifests.id) As c')
//->get();;

    //  return (iterator_count($periods));echo json_encode($period);
}