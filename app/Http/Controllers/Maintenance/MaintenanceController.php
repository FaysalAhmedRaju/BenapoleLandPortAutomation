<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Manifest;
use App\Models\Truck\TruckEntryReg;
use DB;
use App\Http\Controllers\AssessmentBaseController;
use Auth;
use Session;

class MaintenanceController extends Controller
{
    private  $manifest;
    private  $truck_entry_reg;
    private $assessment_base_controller;

    public function __construct(Manifest $manifest, TruckEntryReg $truck_entry_reg, AssessmentBaseController $assessment_base_controller)
    {
        $this->middleware('auth');
        $this->manifest = $manifest;
        $this->truck_entry_reg = $truck_entry_reg;
        $this->assessment_base_controller = $assessment_base_controller;
    }

    public function welcome(){
        $view_title='Welcome To Maintenance';
        $currentDate = date('Y-m-d');
        $port_id = Session::get('PORT_ID');
        $todaysTruckTotal = DB::select('SELECT COUNT(tr.id) total_truck_entry
                    FROM truck_entry_regs AS tr
                    JOIN manifests AS m ON tr.manf_id=m.id
                    WHERE DATE(tr.truckentry_datetime)=?  AND
                    SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                    NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.vehicle_type_flag BETWEEN "1" AND "10" AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);
       // dd($port_id);
        $todaysTotalChassisSelf = DB::select('SELECT COUNT(*) AS totalChassisSelf
                        FROM truck_entry_regs AS tr
                        JOIN manifests AS m ON tr.manf_id=m.id
                        WHERE DATE(tr.truckentry_datetime)=?  AND
                        SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                        NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.vehicle_type_flag BETWEEN "11" AND "20" AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);

        $todaysManifestTotal = DB::select('SELECT COUNT(DISTINCT tr.manf_id) total_manifest
                        FROM truck_entry_regs AS tr
                        JOIN manifests AS m ON tr.manf_id=m.id
                        WHERE DATE(tr.truckentry_datetime)=?  AND
                        SUBSTRING_INDEX(SUBSTRING_INDEX(manifest,\'/\',2),\'/\',-1)
                          NOT REGEXP \'^([B-Z]{1}[\-]{1}[B-Z]{1})$\' AND tr.port_id=? AND m.port_id=?', [$currentDate, $port_id, $port_id]);


        $todaysExitTruckTotal = DB::select('SELECT COUNT(id) AS  total_truck_exit
                        FROM truck_entry_regs AS tr WHERE DATE(tr.out_date)=?
                        AND tr.vehicle_type_flag BETWEEN "1" AND "10" AND tr.port_id=?', [$currentDate, $port_id]);
        //return $this->restoreData();
        return view('maintenance.welcome',compact('view_title',/*'manifests',*/'todaysTruckTotal','todaysTotalChassisSelf','todaysManifestTotal','todaysExitTruckTotal'));
    }

    public function restoreData() {
        $assesments_table = DB::select('SELECT a.id, a.warehouse_details, a.partial_status, m.manifest 
                                FROM assessments a
                                JOIN manifests m ON m.id = a.manifest_id
                                WHERE a.id IN (SELECT MAX(assessments.id) 
                                FROM assessments 
                                GROUP BY assessments.manifest_id, a.partial_status) AND a.warehouse_details IS NOT NULL LIMIT 4500, 500');  //500, 500
                                                                                    //1000, 500 //1500, 500, //2000, 500
                                                                                    //2500, 500 //3000, 500 //3500, 500
                                                                                    //4000, 500 //4500, 500 //5000, 500
                                                                                    //5500, 400 //5900, 400 //6300, 400
                                                                                    //6700, 400 //7100, 300 //7400, 400
        //return $assesments_table;
        $count = 0;
        //return count($assesments_table);
        if(count($assesments_table)) {
            foreach ($assesments_table as $k => $v) {
                $count++;
                $warehouse_details = $this->assessment_base_controller->getWarehouseDetails($v->manifest, $v->partial_status);
                DB::table('assessments')
                    ->where('assessments.id', $v->id)
                    ->update([
                        'warehouse_details' => $warehouse_details,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
        }
        //return $count;
        return $count.' rows of assessments table successfully restored by '.Auth::user()->name;
    }

}
