<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class YardGraphController extends Controller
{

    public  function  YardGraphicalView()
    {
        return view('manifest.yardGraph');
    }


    public  function GetYardList(){
        $yard= DB::select("SELECT * FROM yard_details");
        return json_encode($yard);

    }

    public  function  getYardGraphDetails($posted_yard_shed)
    {
       $graph= DB::select("SELECT * FROM yard_graphs WHERE yard_id=?",[$posted_yard_shed]);
        return json_encode($graph);
    }


    public  function  GetYardShedCellDetails(Request $r)
    {

        $graph= DB::select("SELECT * FROM yard_graphs AS Y WHERE y.row=? AND y.column=?",[$r->row,$r->column]);
        return json_encode($graph);
    }


    public  function  SaveGraphWeight(Request $r)
    {

        DB::table('yard_graphs')->insert(
            [
                'yard_id' =>  1,
                'row' => $r->row,
                'column' => $r->column,
                'weight' => $r->weight

            ]
        );
    }

}


/* $file = fopen("Truckentry.txt","w");
         echo fwrite($file,"Hello ".$req->truck_type);
         fclose($file);*/