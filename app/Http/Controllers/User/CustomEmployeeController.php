<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use File;
use Auth;
use Session;
use Input;
use PDF;

class CustomEmployeeController extends Controller
{


    public function createCustomEmployeeView() {
        return view('default.custom-employee.create-custom-employee');

    }

    public function saveCustomEmployeeData(Request $r) {
        //return $r->file('photo');


        $user_id = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');

        $postCnfEmployee = DB::table('custom_employees')
            ->insertGetId([
                'employee_type' => $r->employee_type,
                'organization' => $r->organization_name,
                'name' => $r->name,
                'address' => $r->address,
                'national_id' => $r->national_id,
                'date_of_birth' => $r->date_of_birth,
                'designation' => $r->designation,
                'phone_no' => ($r->phone_no == 'undefined' || $r->phone_no == 'null') ? null : $r->phone_no,
                'email' => $r->email,
                'mobile' =>  $r->mobile ,
                'created_by' => $user_id,
                'created_at' => $createdTime
            ]);

        if($r->hasFile('photo')) {
            $image = $r->file('photo');
            $imageName = $postCnfEmployee.'.jpg';
            $destinationPath = public_path('img/custom-employees');
            $img = Image::make($image->getRealPath());
            //return $image->getRealPath();
            $img->resize(150, 150, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($destinationPath.'/'.$imageName);
            $insertPhoto = DB::table('custom_employees')->where('id', $postCnfEmployee)->update(['photo' => $imageName]);
        } else {
            $insertPhoto = DB::table('custom_employees')->where('id', $postCnfEmployee)->update(['photo' => null]);

        }

        if($r->hasFile('national_id_photo')) {
            $nid_image = $r->file('national_id_photo');
            $nid_imageName = $postCnfEmployee.'_NID'.'.jpg';
            $nid_destinationPath = public_path('img/custom-employees/nid');
            $nid_img = Image::make($nid_image->getRealPath());
            $nid_img->resize(100, 100, function($constraint){
                $constraint->aspectRatio();
            })->encode('jpg')->save($nid_destinationPath.'/'.$nid_imageName);
            $insertNidPhoto = DB::table('custom_employees')->where('id', $postCnfEmployee)->update(['nid_photo' => $nid_imageName]);
        } else {
            $insertNidPhoto = DB::table('custom_employees')->where('id', $postCnfEmployee)->update(['nid_photo' => null]);
        }



        if($postCnfEmployee == true && $insertPhoto == true && $insertNidPhoto== true ) {
            return "successs";
        }
    }


    public function getAllCustomEmployee(Request $r) {

        $allEmployee = DB::select("SELECT * FROM custom_employees WHERE custom_employees.employee_type=?",[$r->emp_type]);
        return json_encode($allEmployee);
    }


    public function updateCustomEmployeeData(Request $r) {
        $user_id = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');

        if($r->hasFile('photo')) {
            if($r->photo_link != 'null') {
                File::delete('/img/custom-employees/'.$r->photo_link);
            }
            $image = $r->file('photo');
            $imageName = $r->id.'.jpg';
            $destinationPath = public_path('/img/custom-employees');
            $img = Image::make($image->getRealPath());
            $img->resize(150, 150, function($constraint){
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
                File::delete('/img/custom-employees/nid/'.$r->national_id_photo_link);
            }
            $image = $r->file('national_id_photo');
            $imageNameNid = $r->id.'_NID'.'.jpg';
            $destinationPath = public_path('/img/custom-employees/nid');
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


        $updateCustomEmployee = DB::table('custom_employees')
            ->where('id',$r->id)
            ->update([
                'employee_type' => $r->employee_type,
                'organization' => $r->organization,
                'name' => $r->name,
                'designation' => $r->designation,
                'date_of_birth' => $r->date_of_birth,
                'national_id' => $r->national_id,
                'mobile' => $r->mobile,
                'phone_no' => ($r->phone_no == 'undefined' || $r->phone_no == 'null') ? null : $r->phone_no,
                'email' => $r->email,
                'address' => $r->address,
                'photo' => $imageName,
                'nid_photo' =>$imageNameNid,
                'updated_by' => $user_id,
                'updated_at' => $createdTime
            ]);

        $dataCustom = DB::table('users')
            ->where('custom_employee_id',$r->id)
            ->count();


        if($dataCustom != 0){
            $updateEmployeeUser = DB::table('users')
                ->where('custom_employee_id', '=', $r->id)
                ->update([
                    'name' => $r->name,
                    'mobile' => $r->mobile,
                    'email' => $r->email,
                    'photo' => $imageName == null ? null : 'img/custom-employees/'.$r->id.'.jpg'
                ]);
        }


        if($updateCustomEmployee == true) {
            return "updated";
        }
    }



    public function deleteCustomEmployeeData($id) {
        $checkUserData = DB::table('users')
        ->where('custom_employee_id', $id)
        ->get();
        if(count($checkUserData)>0){
            return "deny";
        }
        $getImgName = DB::table('custom_employees')
            ->where('id', $id)
            ->select('custom_employees.photo','custom_employees.nid_photo')
            ->get();
        //return $getImgName[0]->photo;
        if($getImgName[0]->photo != null) {
            File::delete('img/custom-employees/'.$getImgName[0]->photo);
        }
        if($getImgName[0]->nid_photo != null) {
            File::delete('img/custom-employees/nid/'.$getImgName[0]->nid_photo);
        }
        $deleteEmployee = DB::table('custom_employees')
            ->where('id', $id)
            ->delete();
        if($deleteEmployee == true) {
            return "deleted";
        }

    }

}
