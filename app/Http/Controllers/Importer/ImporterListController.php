<?php

namespace App\Http\Controllers\Importer;
use App\Http\Controllers\Controller;
use PDF;
//use Session;
//use App\Role;
use Illuminate\Http\Request;
//use App\vatreg;
use DB;
use Auth;


class ImporterListController extends Controller
{
    public function __construct() {
        $this->middleware('web');
    }

    public function index(){
        return view('default.importer.importerList');
    }

    public function getImporterList($itemsPerPage, $pagenumber) {
        $firstLimit = ($pagenumber-1)*$itemsPerPage;
        $lastLimit = $itemsPerPage;
        $data = DB::select("SELECT *,(SELECT COUNT(vatregs.id) FROM vatregs) 
                        AS total FROM vatregs LIMIT ?,?",[$firstLimit, $lastLimit]);
        return json_encode($data);

    }

    public function saveImporterData(Request $r) {
        $createdBy = Auth::user()->id;
        $createDate = date('Y-m-d H:i:s');
        $postImporter = DB::table('vatregs')
                        ->insert([
                            'BIN' => $r->BIN,
                            'vat' => $r->vat,
                            'NAME' => $r->NAME,
                            'ADD1' => $r->ADD1,
                            'ADD2' => $r->ADD2,
                            'ADD3' => $r->ADD3,
                            'ADD4' => $r->ADD4,
                            'created_by' => $createdBy,
                            'created_date' => $createDate
                            ]);
        if($postImporter == true) {
            return "Success";
        }
    }

    public function getSingleImporter($bin_no) {
        $getSingleImporter = DB::table('vatregs')
                            ->where('vatregs.BIN', $bin_no)
                            ->get();
        return json_encode($getSingleImporter);
    }

    public function updateImporterData(Request $r) {
        $updateImporter = DB::table('vatregs')
                        ->where('vatregs.id',$r->id)
                        ->update([
                            'BIN' => $r->BIN,
                            'vat' => $r->vat,
                            'NAME' => $r->NAME,
                            'ADD1' => $r->ADD1,
                            'ADD2' => $r->ADD2,
                            'ADD3' => $r->ADD3,
                            'ADD4' => $r->ADD4
                            ]);
        if($updateImporter == true) {
            return "Updated";
        }
    }

    public function deleteImporterData($id) {
        $deleteImporter = DB::table('vatregs')
                            ->where('vatregs.id',$id)
                            ->delete();
        if($deleteImporter == true) {
            return "Deleted";
        }
    }

    public function checkBinNumber($bin_no) {
        $checkBinNumber = DB::select("SELECT COUNT(vatregs.BIN) AS exist, id  
                                    FROM vatregs 
                                    WHERE  vatregs.BIN=?",[$bin_no]);
        return json_encode($checkBinNumber);

    }


}
