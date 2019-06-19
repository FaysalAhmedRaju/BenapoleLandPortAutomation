<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class RequisitionController extends Controller
{
    public function Requisition() {
    	return view('WareHouse.Requisition');
    }

    public function getRequisitionPDF() {
    	$pdf = PDF::loadView('WareHouse.getRequisitionPDF',[])
    				->setPaper([0, 0, 610, 842], 'landscape');
    	return $pdf->stream('Requisition.pdf');
    }
}
