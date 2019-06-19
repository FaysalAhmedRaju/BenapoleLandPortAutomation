<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use Session;
use App\Role;
use App\truck_entry_reg;
use Illuminate\Database\Eloquent;
use Exception;
use Response;

class SuperAdminReportController extends Controller
{

//Truck Related Report
    public function superAdminTransportYearlyReport(Request $r)
    {

        //  dd('ljkj');

        $dates = date('Y-m-d');
        $nextYear = $r->year + 1;
        $currentTime = date('Y-m-d H:i:s');
        $firstDate = $r->year . '-07-01';
        $lastDate = $nextYear . '-06-30';
        $fiscal_year = $r->year . '-' . ($r->year + 1);

     //   dd($firstDate.' '.$lastDate);

        $major_goods=DB::select('SELECT * FROM item_codes LIMIT 10');

        $data = DB::select('SELECT Month_name,year_name,t.truckentry_datetime,
                                      SUM(total_foreign) AS total_foreign,
                                      SUM(total_local) AS total_local,
                                      SUM(total_local+total_foreign) AS total_truck,
                                      SUM(foreign_weight)/1000 AS foreign_weight,
                                      SUM(total_export_amount) AS total_export_amount
                                    FROM
                                    (
                                      SELECT  a.truckentry_datetime, 0 AS total_export_amount,
                                        MONTHNAME(a.truckentry_datetime) AS Month_name,
                                        DATE_FORMAT(a.truckentry_datetime, \'%y\') AS year_name,
                                        COUNT(a.id) AS total_foreign,
                                        0 AS total_local,
                                        SUM(a.receive_weight) AS foreign_weight
                                      FROM truck_entry_regs AS a WHERE truckentry_datetime BETWEEN ? AND ? 
                                      GROUP BY MONTHNAME(a.truckentry_datetime)
                                      UNION ALL
                                      SELECT  0 AS truckentry_datetime,0 AS total_export_amount,
                                        MONTHNAME(b.entry_datetime) AS Month_name,
                                           DATE_FORMAT(b.entry_datetime, \'%y\') AS year_name,
                                        0 AS total_foreign,
                                        COUNT(b.id) AS total_local,
                                        0 AS foreign_weight
                                      FROM delivery_export AS b  WHERE b.entry_datetime BETWEEN ? AND ? 
                                      GROUP BY MONTHNAME(b.entry_datetime)
                                      
                                      UNION ALL
                                      
                                       SELECT 0 AS truckentry_datetime, SUM(dch.total_amount) AS total_export_amount,
                                        MONTHNAME(dch.create_datetime) AS Month_name,
                                        DATE_FORMAT(dch.create_datetime, \'%y\') AS year_name,
                                         0 AS total_foreign,
                                        0 AS total_local,
                                        0 AS foreign_weight
                                       FROM delivery_export_challan AS dch 
                                      WHERE dch.create_datetime BETWEEN ? AND ?
                                      GROUP BY MONTHNAME(dch.create_datetime)
                                    ) AS t
                                    GROUP BY Month_Name 
                                    ORDER BY  truckentry_datetime', [$firstDate, $lastDate, $firstDate, $lastDate, $firstDate, $lastDate]);


        $pdf = PDF::loadView('default.super-admin.reports.super-admin-transport-yearly-report', [

            'data' => $data,
            'major_goods'=>$major_goods,
            'firstDate' => $firstDate,
            'lastDate' => $lastDate,
            'date' => $currentTime

        ])->setPaper([0, 0, 700, 800]);
        return $pdf->stream('super-admin-transport-yearly-report.pdf');

    }

    public function allLandPortTrasnportReport()
    {
        $dates = date('Y-m-d');
        //$nextYear = $r->year + 1;
        $currentTime = date('Y-m-d H:i:s');
        // $firstDate = $r->year . '-07-01';
        // $lastDate = $nextYear . '-06-30';
        // $fiscal_year = $r->year . '-' . ($r->year + 1);

        $data = DB::select('SELECT \'Benapole Land Port\' AS port_name,
                                COUNT(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2017-07-01\' AND
                                                t.truckentry_datetime < \'2018-07-01\' THEN 1 END) AS foreign_2017_18,
                                COUNT(CASE WHEN TYPE = 1 AND t.entry_datetime >= \'2017-07-01\' AND
                                               t.entry_datetime < \'2018-07-01\' THEN 1 END) AS local_2017_18,
                                                COUNT(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2018-07-01\' AND
                                                t.truckentry_datetime < \'2019-07-01\' THEN 1 END) AS foreign_2018_19,
                                COUNT(CASE WHEN TYPE = 1 AND t.entry_datetime >= \'2018-07-01\' AND
                                               t.entry_datetime < \'2019-07-01\' THEN 1 END) AS local_2018_19
                            
                                -- add more counts for other fiscal years here
                            FROM
                            (
                                SELECT id, tr.truckentry_datetime,0 AS entry_datetime, 0 AS TYPE    -- 0 is for foreign
                                FROM truck_entry_regs AS tr
                                UNION ALL
                                SELECT id,0 AS truckentry_datetime,de.entry_datetime, 1            -- 1 is for local
                                FROM delivery_export AS de
                            ) t;', []);


        $pdf = PDF::loadView('default.super-admin.reports.all-land-port-transport-yearly-report', [

            'data' => $data,
            // 'firstDate' => $firstDate,
            //'lastDate'=>$lastDate,
            'date' => $currentTime

        ])->setPaper([0, 0, 700, 800]);
        return $pdf->stream('super-admin-transport-yearly-report.pdf');
    }


    public function allLandportExportImportReport()
    {

        $currentTime = date('Y-m-d H:i:s');


        $data = DB::select('SELECT \'Benapole Land Port\' AS port_name,
                    SUM(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2017-07-01\' AND
                                    t.truckentry_datetime < \'2018-07-01\' THEN t.receive_weight/1000 END) AS import_2017_18,
                    SUM(CASE WHEN TYPE = 1 AND t.entry_datetime >= \'2017-07-01\' AND
                                   t.entry_datetime < \'2018-07-01\' THEN 1 END) AS export_2017_18,
                    COUNT(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2018-07-01\' AND
                                   t.truckentry_datetime < \'2019-07-01\' THEN  t.receive_weight END) AS import_2018_19,
                    COUNT(CASE WHEN TYPE = 1 AND t.entry_datetime >= \'2018-07-01\' AND
                                  t.entry_datetime < \'2019-07-01\' THEN 1 END) AS  export_2018_19,
                    SUM(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2017-07-01\' AND
                                    t.truckentry_datetime < \'2018-07-01\' THEN t.cnf_value END) AS cnF_value_2017_18,
                    SUM(CASE WHEN TYPE = 0 AND t.truckentry_datetime >= \'2018-07-01\' AND
                                    t.truckentry_datetime < \'2019-07-01\' THEN t.cnf_value END) AS cnF_value_2018_19
                
                FROM
                (
                    SELECT m1.cnf_value,tr.receive_weight,  tr.truckentry_datetime,0 AS entry_datetime, 0 AS TYPE    -- 0 is for import
                    FROM truck_entry_regs AS tr
                     JOIN manifests AS m1 ON tr.manf_id=m1.id
                    UNION ALL
                    SELECT 0 AS cnf_value,0 AS export_weight, 0 AS truckentry_datetime,de.entry_datetime, 1            -- 1 is for Export
                    FROM delivery_export AS de
                ) t;');

        //dd($data);

        $pdf = PDF::loadView('default.super-admin.reports.all-land-port-export-import-yearly-report', [

            'data' => $data,

            'date' => $currentTime

        ])->setPaper([0, 0, 700, 800]);
        return $pdf->stream('all-landport-export-import-yearly-report.pdf');

    }

    //Warehouse Report-----------------------

    










}
