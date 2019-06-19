<?php

namespace App\Http\Controllers\Cnf;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Image;
use File;
use Auth;
use Session;
use Input;
use PDF;


class CnfEmployeeController extends Controller
{
    public function createCnfEmployee() {
    	return view('default.cnf-employee.create-cnf-employee');

    }

    public function getAllCnfOrg() {
//    	$getAllCnfOrg = DB::table('organizations')
//    							->join('org_types','org_types.id','=','organizations.org_type_id')
//    							->where('org_types.org_type','C&F')
//    							->select('organizations.id','organizations.org_name')
//    							->get();
//    	return json_encode($getAllCnfOrg);
    }

    public  function cnfDetailsData(Request $req)
    {

        $port_id = Session::get('PORT_ID');
        $term = $req->term;//Input::get('term');

        $results = array();
        $queries = DB::table('cnf_details')
            ->join('cnf_port','cnf_port.cnf_id', '=', 'cnf_details.id')
            ->where('cnf_port.port_id','=', $port_id)
            ->Where('cnf_details.cnf_name', 'LIKE', '%'.$term.'%')
            ->orWhere('cnf_details.ain_no', 'LIKE', '%'.$term.'%')
            ->select('cnf_details.*')
            ->groupBy('cnf_details.id')
            ->take(10)->get();


        if(!$queries){
            $results[] =['value' => 'no'];
        }
        else{
            foreach ($queries as $query)
            {
                $results[] = ['cnf_name' => $query->cnf_name, 'cnf_details_id' => $query->id, 'ain_no' => $query->ain_no];
            }
        }

        return json_encode($queries);

    }

    // public function postCnfEmployee(Request $r) {
    // 	$postCnfEmployee = DB::table('organization_employes')
    // 							->insert([
    // 								'org_id' => $r->org_id,
    // 								'emp_id' => $r->emp_id,
    // 								'emp_name' => $r->emp_name,
    // 								'address' => $r->address,
    // 								'national_id' => $r->national_id,
    // 								'date_of_birth' => $r->date_of_birth,
    // 								'designation' => $r->designation,
    // 								'phone_no' => $r->phone_no,
    // 								'email' => $r->email,
    // 								'mobile' => $r->mobile
    // 								]);
    // 	if($postCnfEmployee == true) {
    // 		return "successs";
    // 	}
    // }
    public function saveCnfEmployeeData(Request $r) {
        $user_id = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        $postCnfEmployee = DB::table('cnf_employees')
                             ->insertGetId([
                                 'cnf_detail_id' => $r->cnf_detail_id,
                                 'name' => $r->name,
                                 'address' => $r->address,
                                 'national_id' => $r->national_id,
                                 'date_of_birth' => $r->date_of_birth,
                                 'designation' => $r->designation,
                                 'phone_no' => ($r->phone_no == 'undefined' || $r->phone_no == 'null') ? null: $r->phone_no,
                                 'email' => $r->email,
                                 'mobile' => $r->mobile,
                                 'created_by' => $user_id,
                                 'created_at' => $createdTime
                                 ]);


        if($r->hasFile('photo')) {
            $image = $r->file('photo');
            $imageName = $postCnfEmployee.'.jpg';   //time().'_'.$r->cnf_detail_id.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('img/cnf-employees');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($destinationPath.'/'.$imageName);
            $insertPhoto = DB::table('cnf_employees')->where('id', $postCnfEmployee)->update(['photo' => $imageName]);
        } else {
            $insertPhoto = DB::table('employees')->where('id', $postCnfEmployee)->update(['photo' => null]);
        }

        if($r->hasFile('national_id_photo')) {
            $nid_image = $r->file('national_id_photo');
            $nid_imageName = $postCnfEmployee.'_NID'.'.jpg'; //$image->getClientOriginalExtension();
            $nid_destinationPath = public_path('img/cnf-employees/nid');
            $nid_img = Image::make($nid_image->getRealPath());
            $nid_img->resize(100, 100, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($nid_destinationPath.'/'.$nid_imageName);
            $insertNidPhoto = DB::table('cnf_employees')->where('id', $postCnfEmployee)->update(['nid_photo' => $nid_imageName]);
        } else {
            $insertNidPhoto = DB::table('cnf_employees')->where('id', $postCnfEmployee)->update(['nid_photo' => null]);
        }



        if($postCnfEmployee == true && $insertPhoto == true && $insertNidPhoto== true ) {
            return "successs";
        }
    }
    
    public function getAllEmployeeByCnf($id) {
        $port_id = Session::get('PORT_ID');

//    \Log::info($port_id);
    	$getAllEmployeeByCnf = DB::table('cnf_employees')
    							 ->join('cnf_details','cnf_details.id', '=', 'cnf_employees.cnf_detail_id')
                                 ->join('cnf_port','cnf_port.cnf_id', '=', 'cnf_details.id')
                                ->where('cnf_employees.cnf_detail_id', $id)
                                ->where('cnf_port.port_id', $port_id)
    							->select('cnf_employees.*','cnf_details.*','cnf_employees.id as emp_id','cnf_details.id as details_id','cnf_employees.address as cnf_address','cnf_employees.mobile as cnf_mobile','cnf_employees.email as cnf_email' )
    							->get();
    	return json_encode($getAllEmployeeByCnf);
    }

    public function updateCnfEmployee(Request $r) {
        $user_id = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');

        if($r->hasFile('photo')) {
            if($r->photo_link != 'null') {
                File::delete('/img/cnf-employees/'.$r->photo_link);
            }
            $image = $r->file('photo');
            $imageName = $r->id.'.jpg'; //time().'_'.$r->org_id.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/cnf-employees');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($destinationPath.'/'.$imageName);
        } else {
            if($r->photo_link != 'null') {
                $imageName = $r->photo_link;
            } else {
                $imageName = null;
            }
        }

        if($r->hasFile('national_id_photo')) {
            if($r->national_id_photo_link != 'null') {
                File::delete('/img/cnf-employees/nid/'.$r->national_id_photo_link);
            }
            $image = $r->file('national_id_photo');
            $imageNameNid = $r->id.'_NID'.'.jpg'; //time().'_'.$r->org_id.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/cnf-employees/nid');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($destinationPath.'/'.$imageNameNid);
        } else {
            if($r->national_id_photo_link != 'null') {
                $imageNameNid = $r->national_id_photo_link;
            } else {
                $imageNameNid = null;
            }
        }

        $updateCnfEmployee = DB::table('cnf_employees')
                                ->where('id',$r->id)
                                ->update([
                                    'cnf_detail_id' => $r->cnf_detail_id,
                                    'name' => $r->name,
                                    'address' => $r->address,
                                    'national_id' => $r->national_id,
                                    'date_of_birth' => $r->date_of_birth,
                                    'designation' => $r->designation,
                                    'phone_no' => ($r->phone_no == 'undefined' || $r->phone_no == 'null') ? null : $r->phone_no ,
                                    'email' => $r->email,
                                    'mobile' => $r->mobile,
                                    'photo' => $imageName,
                                    'nid_photo' =>$imageNameNid,
                                    'updated_by' => $user_id,
                                    'updated_at' => $createdTime
                                    ]);

        $dataCnf = DB::table('users')
            ->where('cnf_employee_id',$r->id)
            ->count();


        if($dataCnf != 0){
            $updateEmployeeUser = DB::table('users')
                ->where('cnf_employee_id', '=', $r->id)
                ->update([
                    'name' => $r->name,
                    'mobile' => $r->mobile,
                    'email' => $r->email,
                    'photo' => $imageName == null ? null : 'img/cnf-employees/'.$r->id.'.jpg'
                ]);
        }

        if($updateCnfEmployee == true) {
            return "updated";
        }
    }

    public function deleteEmployeeData($id) {
        $checkUserData = DB::table('users')
            ->where('cnf_employee_id', $id)
            ->get();
        if(count($checkUserData)>0){
            return "deny";
        }else{
            $getImgName = DB::table('cnf_employees')
                ->where('id', $id)
                ->select('cnf_employees.photo','cnf_employees.nid_photo')
                ->get();
            //return $getImgName[0]->photo;
            if($getImgName[0]->photo != null) {
                File::delete('img/cnf-employees/'.$getImgName[0]->photo);
            }
            if($getImgName[0]->nid_photo != null) {
                File::delete('img/cnf-employees/nid/'.$getImgName[0]->nid_photo);
            }
            $deleteEmployee = DB::table('cnf_employees')
                ->where('id', $id)
                ->delete();
            if($deleteEmployee == true) {
                return "deleted";
            }
        }


    }
}
