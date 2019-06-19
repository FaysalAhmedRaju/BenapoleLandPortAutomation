<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\truck_entry_reg;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }


    public function Dashboard(){

        $trucks=truck_entry_reg::paginate(4);



        $goods_id = DB::table('cargo_details')->get();

       // dump($goods_id);return;

        return view('truck\TruckEntryForm',compact('trucks','goods_id'));
    }
}
