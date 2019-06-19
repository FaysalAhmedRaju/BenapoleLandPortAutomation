<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use File;
use Session;
use Input;
use PDF;
use Response;

class PayrollController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //===============================Employee Part Start==============================
    public function employeeDetailsView() {
        return view('default.payroll.employee-details');
    }


    public function getAllEmployeeDetails() {
        $port_id = Session::get('PORT_ID');
        $employees = DB::select('SELECT e.id, e.emp_id, e.name, e.father_name, e.mother_name, 
                                e.spouse_name, e.mobile, e.telephone, e.email, e.national_id, 
                                e.national_id_photo, e.date_of_birth, e.date_join,
                                e.present_address, e.permanent_address, e.children, e.photo, e.status,e.allocate_home,e.house_area_flag
                                FROM employees AS e
                                JOIN employee_histories AS eh ON eh.employee_id = e.id
                                WHERE eh.id IN (SELECT MAX(employee_histories.id)
                                FROM employee_histories
                                GROUP BY employee_histories.employee_id
                                ) AND e.status = 1 AND eh.port_id=?',[$port_id]);
        return json_encode($employees);
    }

    public function saveEmployeeData(Request $r) {
        //return $r->spouse_name;
        $createdBy = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        $port_id = Session::get('PORT_ID');
        $stringForId = "BL";
        $getlastEmpID = DB::select("SELECT MAX(CAST((SUBSTRING(employees.emp_id , 3)) AS UNSIGNED)) AS last_emp_id FROM employees");
        if(!is_null($getlastEmpID[0]->last_emp_id)) {
            $numberOfEmpID = $getlastEmpID[0]->last_emp_id + 1;
        } else {
            $numberOfEmpID = 1;
        }
        $empID = $stringForId.sprintf("%04d",$numberOfEmpID);
        $insertEmployee = DB::table('employees')
                            ->insertGetId([
                                    'emp_id' => $empID,
                                    'name' => $r->name,
                                    'father_name' => $r->father_name,
                                    'mother_name' => $r->mother_name,
                                    'spouse_name' => ($r->spouse_name == 'undefined' || $r->spouse_name == 'null') ? null : $r->spouse_name,
                                    'mobile' => $r->mobile,
                                    'telephone' => ($r->telephone == 'undefined' || $r->telephone == 'null') ? null : $r->telephone,
                                    'email' => ($r->email == 'undefined' || $r->email == 'null') ? null : $r->email,
                                    'national_id' => $r->national_id,
                                    'date_of_birth' => $r->date_of_birth,
                                    'date_join' => $r->date_join,
                                    'present_address' => $r->present_address,
                                    'permanent_address' => $r->permanent_address,
                                    'allocate_home' => $r->allocate_home,
                                    'house_area_flag' => $r->house_area_flag,
                                    'children' => $r->children,
                                    'created_at' => $createdTime,
                                    'created_by' => $createdBy
                                    ]);

        $insertEmployeeHistory = DB::table('employee_histories')
            ->insertGetId([
                'employee_id' => $insertEmployee,
                'port_id' => $port_id,
                'transfer_date' => $r->date_join,
                'created_at' => $createdTime,
                'created_by' => $createdBy
            ]);



        if($r->hasFile('photo') || $r->hasFile('national_id_photo')) {
            if($r->hasFile('photo')) {
                $image = $r->file('photo');
                $imageName = $insertEmployee.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/employees');
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function($constraint){
                    $constraint->aspectRatio();
                })->encode('jpg')->save($destinationPath.'/'.$imageName);
                $insertPhoto = DB::table('employees')->where('id', $insertEmployee)->update(['photo' => $imageName]);
            } else {
                $insertPhoto = DB::table('employees')->where('id', $insertEmployee)->update(['photo' => null]);
            }

            if($r->hasFile('national_id_photo')) {
                $nid_image = $r->file('national_id_photo');
                $nid_imageName = $insertEmployee.'_NID'.'.jpg'; //$image->getClientOriginalExtension();
                $nid_destinationPath = public_path('img/employees/national_id');
                $nid_img = Image::make($nid_image->getRealPath());
                $nid_img->resize(100, 100, function($constraint){
                    $constraint->aspectRatio();
                })->encode('jpg')->save($nid_destinationPath.'/'.$nid_imageName);
                $insertNidPhoto = DB::table('employees')->where('id', $insertEmployee)->update(['national_id_photo' => $nid_imageName]);
            } else {
                $insertNidPhoto = DB::table('employees')->where('id', $insertEmployee)->update(['national_id_photo' => null]);
            }
        } else {
            return Response::json(['nidPhoto' => 'National ID Photo is required'], 401);
        }

        if($insertEmployee == true && $insertPhoto == true && $insertNidPhoto == true && $insertEmployeeHistory == true) {
            return 'Success';
        }
    }

    public function updateEmployeeData(Request $r) {
        $port_id = Session::get('PORT_ID');
        $user_id = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        $updateEmployee = DB::table('employees')
            ->where('id', '=', $r->id)
            ->update([
                'name' => $r->name,
                'father_name' => $r->father_name,
                'mother_name' => $r->mother_name,
                'spouse_name' => ($r->spouse_name == 'undefined' || $r->spouse_name == 'null') ? null : $r->spouse_name,
                'mobile' => $r->mobile,
                'telephone' => ($r->telephone == 'undefined' ||  $r->telephone == 'null') ? null : $r->telephone,
                'email' => ($r->email == 'undefined' || $r->email == 'null') ? null : $r->email,
                'national_id' => $r->national_id,
                'date_of_birth' => $r->date_of_birth,
                'date_join' => $r->date_join,
                'present_address' => $r->present_address,
                'permanent_address' => $r->permanent_address,
                'children' => $r->children,
                'allocate_home' => $r->allocate_home,
                'house_area_flag' => $r->house_area_flag,
                'updated_at' => $createdTime,
                'updated_by' => $user_id,
                'national_id_photo' => $r->id.'_NID'.'.jpg',
                'photo' => $r->hasFile('photo') ? (string)$r->id.'.jpg' : ($r->photo_link != 'null' ? $r->photo_link : null)
            ]);
        if($r->hasFile('photo') || $r->hasFile('national_id_photo')) {
            if($r->hasFile('photo')) {
                $image = $r->file('photo');
                $imageName = $r->id.'.jpg'; //$image->getClientOriginalExtension();
                $destinationPath = public_path('img/employees');
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function($constraint){
                    $constraint->aspectRatio();
                })->encode('jpg')->save($destinationPath.'/'.$imageName);
                $updatePhoto = true;
            } else {
                $updatePhoto = true;
            }
            if($r->hasFile('national_id_photo')) {
                $nid_image = $r->file('national_id_photo');
                $nid_imageName = $r->id.'_NID'.'.jpg'; //$image->getClientOriginalExtension();
                $nid_destinationPath = public_path('img/employees/national_id');
                $nid_img = Image::make($nid_image->getRealPath());
                $nid_img->resize(100, 100, function($constraint){
                    $constraint->aspectRatio();
                })->encode('jpg')->save($nid_destinationPath.'/'.$nid_imageName);
                $updateNidPhoto = true;
            } else {
                $updateNidPhoto = true;
            }
        } else {
            $updatePhoto = true;
            $updateNidPhoto = true;
        }

        $data = DB::table('employee_histories')
            ->where('employee_id',$r->id)
            ->first();

        if($data){

            $emp_id = DB::select('SELECT MIN(id) AS  e_id  FROM employee_histories WHERE employee_id=? ', [$r->id]);

            $updateEmployeeHistories =  DB::table('employee_histories')
                ->where('id', $emp_id[0]->e_id)
                ->update([
                    'port_id' => $port_id,
                    'transfer_date' => $r->date_join,
                    'updated_at' => $createdTime,
                    'updated_by' => $user_id
                ]);
        }else{
            $insertEmployeeHistory = DB::table('employee_histories')
                ->insertGetId([
                    'employee_id' => $r->id,
                    'port_id' => $port_id,
                    'transfer_date' => $r->date_join,
                    'created_at' => $createdTime,
                    'created_by' => $user_id

                ]);
        }

        $dataUsers = DB::table('users')
            ->where('port_employee_id',$r->id)
            ->count();

        if($dataUsers != 0){
            $updateEmployeeUser = DB::table('users')
                ->where('port_employee_id', '=', $r->id)
                ->update([
                    'name' => $r->name,
                    'mobile' => $r->mobile,
                    'email' => $r->email,
                    'photo' => $r->hasFile('photo') ? 'img/employees/'.$r->id.'.jpg' : ($r->photo_link != 'null' ? 'img/employees/'.$r->id.'.jpg' : null)
                ]);
        }

        if($updateEmployee == true && $updatePhoto == true && $updateNidPhoto == true) {
            return 'Updated';
        }
    }

    public function updateEmployeeTransferData(Request $r) {
        $user_id = Auth::user()->id;
        $port_id = Session::get('PORT_ID');
        $updatedTime = date('Y-m-d H:i:s');
        $updateEmployeeTransferData = DB::table('employee_histories')
            ->where('id', '=', $r->id)
            ->update([
                'port_id' => $r->port_id,
                'transfer_date' => $r->transfer_date,
                'updated_at' => $updatedTime,
                'updated_by' => $user_id
            ]);

        $dataUsers = DB::table('users')
            ->where('port_employee_id',$r->employee_id)
            ->count();

        if($dataUsers != 0 && ($port_id != $r->port_id)){
            $updateEmployeeUser = DB::table('users')
                ->where('port_employee_id', '=', $r->id)
                ->update([
                    'user_status' => 0,
                ]);
        }

        if($updateEmployeeTransferData == true) {
            return 'Updated';
        }
    }

    public function suspendEmployeeData($id) {
        $suspendEmployee = DB::table('employees')
                            ->where('id',$id)
                            ->update([
                                'status' => 0
                                ]);
        if($suspendEmployee == true) {
            return "Suspended";
        }
    }

    public function getAllSuspendedEmployee() {
        $port_id = Session::get('PORT_ID');
        $suspendedEmployees = DB::select('SELECT e.id, e.emp_id, e.name, e.father_name, e.mother_name, 
                        e.spouse_name, e.mobile, e.telephone, e.email, e.national_id, e.national_id_photo, e.date_of_birth, 
                        e.date_join,
                        e.present_address, e.children, e.photo, e.status
                        FROM employees AS e
                        JOIN employee_histories AS eh ON eh.employee_id = e.id
                        WHERE eh.id IN (SELECT MAX(employee_histories.id)
                        FROM employee_histories
                        GROUP BY employee_histories.employee_id
                        ) AND e.status = 0 AND eh.port_id=?',[$port_id]);
        return json_encode($suspendedEmployees);
    }

    public function reassignEmployeeData($id) {
        $reassignEmployee = DB::table('employees')
                            ->where('id',$id)
                            ->update([
                                'status' => 1
                                ]);
        if($reassignEmployee == true) {
            return "Reassigned";
        }
    }

    public function getPortDataDetails() {
        $ports = DB::table('ports')->get();
        return json_encode($ports);
    }

    public function getEmployeeTransferDetails($id) {
        $employeesData = DB::select('SELECT eh.id, eh.port_id, eh.transfer_date, 
            p.port_name
            FROM employee_histories AS eh
            JOIN ports AS p ON p.id = eh.port_id
            WHERE eh.employee_id = ?',[$id]);
        return json_encode($employeesData);
    }


    public function saveEmployeeTransferData(Request $r) {
        $createdTime = date('Y-m-d H:i:s');
        $user_id = Auth::user()->id;
        $port_id = Session::get('PORT_ID');

        $insertEmployeeTransferData = DB::table('employee_histories')
            ->insertGetId([
                'employee_id' => $r->employee_id,
                'transfer_date' => $r->transfer_date,
                'port_id' => $r->port_id,
                'created_at' => $createdTime,
                'created_by' => $user_id
            ]);

        $dataUsers = DB::table('users')
            ->where('port_employee_id',$r->employee_id)
            ->count();

        if($dataUsers != 0 && ($port_id != $r->port_id)){
            $updateEmployeeUser = DB::table('users')
                ->where('port_employee_id', '=', $r->id)
                ->update([
                    'user_status' => 0,
                ]);
        }

        if($insertEmployeeTransferData == true) {
            return 'Success';
        }
    }
    //===============================Employee Part End==============================
    //===============================FacilitiesAndDeduction Part Start============================
    public function facilitiesAndDeductionView() {
        return view('default.payroll.facilities-and-deduction');
    }

    public function saveFixedFacilitiesAndDeductionData(Request $r) {
        $createdBy = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        $port_id = Session::get('PORT_ID');
        $postFixedFacilitiesAndDeduction = DB::table('fixed_facilities_and_deductions')
                                            ->insert([
                                                'education' => $r->education,
                                                'medical' => $r->medical,
                                                'tiffin' => $r->tiffin,
                                                'washing' => $r->washing,
                                                'transport' => $r->transport,
                                                'gpf' => $r->gpf,
                                                'revenue' => $r->revenue,
                                                'scale_year' => $r->scale_year,
                                                'created_at' => $createdTime,
                                                'created_by' => $createdBy,
                                                'port_id' => $port_id
                                                ]);
        if($postFixedFacilitiesAndDeduction == true) {
            return "Success";
        }
    }

    public function getFixedFacilitiedAndDeductionData() {
        $port_id = Session::get('PORT_ID');
        $getFixedFacilitiedAndDeduction = DB::table('fixed_facilities_and_deductions')
                                            ->where('port_id', $port_id)
                                            ->get();
        return json_encode($getFixedFacilitiedAndDeduction);
    }

    public function updateFixedFacilitiesAndDeductionData(Request $r) {
        $updated_by = Auth::user()->id;
        $updated_time = date('Y-m-d H:i:s');
        $updateFixedFacilitiesAndDeduction = DB::table('fixed_facilities_and_deductions')
                                            ->where('id', $r->id)
                                            ->update([
                                                'education' => $r->education,
                                                'medical' => $r->medical,
                                                'tiffin' => $r->tiffin,
                                                'washing' => $r->washing,
                                                'transport' => $r->transport,
                                                'gpf' => $r->gpf,
                                                'revenue' => $r->revenue,
                                                'scale_year' => $r->scale_year,
                                                'updated_by' => $updated_by,
                                                'updated_at' => $updated_time
                                                ]);
        if($updateFixedFacilitiesAndDeduction == true) {
            return "Updated";
        }
    }

    public function deleteFixedFacilitiesAndDeductions($id) {
        $deleteFixed = DB::table('fixed_facilities_and_deductions')
                            ->where('id',$id)
                            ->delete();
        if($deleteFixed == true) {
            return "Deleted";
        }
    } 
    //Monthly Deduction Start
    public function getAllValidEmployees() {
        $port_id = Session::get('PORT_ID');
        $getAllValidEmployee = DB::select("SELECT * FROM(
                SELECT*,
                (SELECT designations.designation FROM designations WHERE designations.id=desig_id) AS designation
                FROM
                (
                SELECT employees.id,employees.emp_id,employees.name,employees.mobile,employees.photo,
                (SELECT desig_id FROM employee_designations WHERE employee_designations.employee_id=(SELECT employees.id) 
                ORDER BY employee_designations.id  DESC LIMIT 1) AS desig_id
                FROM employees
                JOIN employee_histories ON employee_histories.employee_id = employees.id
                WHERE employee_histories.port_id = ? AND employee_histories.id IN (SELECT MAX(eh.id) FROM employee_histories AS eh WHERE eh.port_id = ? GROUP BY employee_id)
                ) AS t ) AS tt WHERE designation IS NOT NULL",[$port_id, $port_id]);
        return json_encode($getAllValidEmployee);
    }

    public function saveMonthlyDeduction(Request $r) {
      $checkYearMonth = DB::select("SELECT deductions.month_year, YEAR(deductions.month_year) AS year_data, MONTH(deductions.month_year) AS month_data 
FROM deductions WHERE deductions.employee_id=? 
ORDER BY deductions.id DESC
LIMIT 1",[$r->employee_id]);

        $month = date("m",strtotime($r->month_year));
        $year = date("Y",strtotime($r->month_year));

        if($checkYearMonth[0]->year_data  > $year){

            return "restriction";
        }elseif (($checkYearMonth[0]->year_data  == $year) && ($checkYearMonth[0]->month_data >  $month)){

            return "restriction";
        }elseif (($checkYearMonth[0]->year_data  == $year) && ($checkYearMonth[0]->month_data == $month)){

            return "restriction";
        }else {

            $port_id = Session::get('PORT_ID');
            $createdBy = Auth::user()->id;
            $createdTime = date('Y-m-d H:i:s');
            $postMonthlyDeduction = DB::table('deductions')
                ->insert([
                    'employee_id' => $r->employee_id,
                    'water' => $r->water,
                    'generator' => $r->generator,
                    'electricity' => $r->electricity,
                    'previous_due' => $r->previous_due,
                    'transport' => $r->transport_month,
                    'month_year' => $r->month_year,
                    'created_at' => $createdTime,
                    'created_by' => $createdBy,
                    'port_id' => $port_id
                ]);
            if($postMonthlyDeduction == true) {
                return "Success";
            }
        }

    }

    public function getEmployeeMonthlyDeduction($employee_id) {
        $port_id = Session::get('PORT_ID');
        $getEmployeeMonthlyDeduction = DB::table('deductions')
                                        ->where('employee_id', $employee_id)
                                        ->where('port_id', $port_id)
                                        ->get();
        return json_encode($getEmployeeMonthlyDeduction);
    }

    public function updateMonthlyDeduction(Request $r) {

        $checkYearMonth = DB::select("SELECT deductions.id,deductions.month_year, YEAR(deductions.month_year) AS year_data, MONTH(deductions.month_year) AS month_data 
FROM deductions WHERE deductions.employee_id=? 
ORDER BY deductions.id DESC
LIMIT 1",[$r->employee_id]);
        $month = date("m",strtotime($r->month_year));
        $year = date("Y",strtotime($r->month_year));

        if($checkYearMonth[0]->year_data  > $year){

            return "restriction";
        }elseif (($checkYearMonth[0]->year_data  == $year) && ($checkYearMonth[0]->month_data >  $month)){

            return "restriction";
        }elseif (($checkYearMonth[0]->year_data  == $year) && ($checkYearMonth[0]->month_data == $month) && ($checkYearMonth[0]->id != $r->id)){

            return "restriction";

        }else{
            $updated_by = Auth::user()->id;
            $updated_time = date('Y-m-d H:i:s');
            $updateMonthlyDeduction = DB::table('deductions')
                ->where('id',$r->id)
                ->update([
                    'employee_id' => $r->employee_id,
                    'water' => $r->water,
                    'generator' => $r->generator,
                    'electricity' => $r->electricity,
                    'previous_due' => $r->previous_due,
                    'transport' => $r->transport_month,
                    'month_year' => $r->month_year,
                    'updated_by' => $updated_by,
                    'updated_at' => $updated_time
                ]);
            if($updateMonthlyDeduction == true) {
                return "Updated";
            }

        }




    }

    public function deleteMonthlyDeduction($id) {
        $deleteMonthlyDeduction = DB::table('deductions')
                            ->where('id',$id)
                            ->delete();
        if($deleteMonthlyDeduction == true) {
            return "Deleted";
        }
    }
    //Monthly Deduction End
    //===============================FacilitiesAndDeduction Part End==============================

    //===============================Home Rental Allowance Rates Start ==========================

    public function homeRentalAllowanceView() {
        return view('default.payroll.home-rental-allowance-rates');
    }

    public function saveHomeRentalAllowance(Request $r) {

        $checkDuplicateAllowance = DB::select('SELECT fh.id  FROM fixed_houserents AS fh 
        WHERE fh.first_range=? AND fh.last_range=? AND YEAR(fh.scale_year)=?', [$r->salary_first_range,$r->salary_last_range,$r->scale_year]);

        $valueBetweenFirstRange = DB::select('SELECT * FROM fixed_houserents WHERE ? BETWEEN  first_range AND last_range AND scale_year=?', [$r->salary_first_range,$r->scale_year]);
        $valueBetweenLastRange = DB::select('SELECT * FROM fixed_houserents WHERE ? BETWEEN  first_range AND last_range AND scale_year=? ', [$r->salary_last_range,$r->scale_year]);

            if($r->homeAllowanceId){

//                $checkDuplicateAllowanceUpdate = DB::select('SELECT fh.first_range AS firstRange,fh.last_range AS lastRange,fh.scale_year AS scaleYear  FROM fixed_houserents AS fh
//        WHERE fh.id', [$r->homeAllowanceId]);
            // if(($checkDuplicateAllowanceUpdate[0]->firstRange == $r->salary_first_range)){
//             \Log::info((count($valueBetweenFirstRange) && count($valueBetweenLastRange)) == 0);
//                if ((count($valueBetweenFirstRange) || count($valueBetweenLastRange)) == 0){
//                    return "check condition";
//                }

                if(count($valueBetweenFirstRange) == 0){

                    $updated_by = Auth::user()->id;
                    $updated_time = date('Y-m-d H:i:s');
                    $updateHomeAllowance = DB::table('fixed_houserents')
                        ->where('id',$r->homeAllowanceId)
                        ->update([
                            'first_range' => $r->salary_first_range,
                            'last_range' => $r->salary_last_range,
                            'dhaka_metro_politon_area_rate' => $r->dhaka_metro_politon_area_rate,
                            'dhaka_metro_politon_area_limit' => $r->dhaka_metro_politon_area_limit,
                            'expensive_area_rate' => $r->expensive_area_rate,
                            'expensive_area_limit' => $r->expensive_area_limit,
                            'other_area_rate' => $r->other_area_rate,
                            'other_area_limit' => $r->other_area_limit,
                            'scale_year' => $r->scale_year,
                            'updated_by' => $updated_by,
                            'updated_at' => $updated_time
                        ]);
                    if($updateHomeAllowance == true) {
                        return "Updated";
                    }


                }else{
                    if($valueBetweenFirstRange[0]->id == $r->homeAllowanceId){

                        if ($valueBetweenFirstRange[0]->first_range = $r->salary_first_range){
                            if($valueBetweenLastRange[0]->last_range = $r->salary_last_range) {
                                $updated_by = Auth::user()->id;
                                $updated_time = date('Y-m-d H:i:s');
                                $updateHomeAllowance = DB::table('fixed_houserents')
                                    ->where('id', $r->homeAllowanceId)
                                    ->update([
                                        'first_range' => $r->salary_first_range,
                                        'last_range' => $r->salary_last_range,
                                        'dhaka_metro_politon_area_rate' => $r->dhaka_metro_politon_area_rate,
                                        'dhaka_metro_politon_area_limit' => $r->dhaka_metro_politon_area_limit,
                                        'expensive_area_rate' => $r->expensive_area_rate,
                                        'expensive_area_limit' => $r->expensive_area_limit,
                                        'other_area_rate' => $r->other_area_rate,
                                        'other_area_limit' => $r->other_area_limit,
                                        'scale_year' => $r->scale_year,
                                        'updated_by' => $updated_by,
                                        'updated_at' => $updated_time
                                    ]);
                                if ($updateHomeAllowance == true) {
                                    return "Updated";
                                }
                            }else{
                                return "Duplicate";
                            }
                        }else{
                            return "Duplicate";
                        }


                    }else{
                        return "Duplicate";
                    }

                }

                if(count($valueBetweenLastRange) == 0){

                    $updated_by = Auth::user()->id;
                    $updated_time = date('Y-m-d H:i:s');
                    $updateHomeAllowance = DB::table('fixed_houserents')
                        ->where('id',$r->homeAllowanceId)
                        ->update([
                            'first_range' => $r->salary_first_range,
                            'last_range' => $r->salary_last_range,
                            'dhaka_metro_politon_area_rate' => $r->dhaka_metro_politon_area_rate,
                            'dhaka_metro_politon_area_limit' => $r->dhaka_metro_politon_area_limit,
                            'expensive_area_rate' => $r->expensive_area_rate,
                            'expensive_area_limit' => $r->expensive_area_limit,
                            'other_area_rate' => $r->other_area_rate,
                            'other_area_limit' => $r->other_area_limit,
                            'scale_year' => $r->scale_year,
                            'updated_by' => $updated_by,
                            'updated_at' => $updated_time
                        ]);
                    if($updateHomeAllowance == true) {
                        return "Updated";
                    }

                }else{
                    if ($valueBetweenLastRange[0]->id == $r->homeAllowanceId){

                        if($valueBetweenLastRange[0]->last_range = $r->salary_last_range){
                            if ($valueBetweenFirstRange[0]->first_range = $r->salary_first_range) {
                                $updated_by = Auth::user()->id;
                                $updated_time = date('Y-m-d H:i:s');
                                $updateHomeAllowance = DB::table('fixed_houserents')
                                    ->where('id', $r->homeAllowanceId)
                                    ->update([
                                        'first_range' => $r->salary_first_range,
                                        'last_range' => $r->salary_last_range,
                                        'dhaka_metro_politon_area_rate' => $r->dhaka_metro_politon_area_rate,
                                        'dhaka_metro_politon_area_limit' => $r->dhaka_metro_politon_area_limit,
                                        'expensive_area_rate' => $r->expensive_area_rate,
                                        'expensive_area_limit' => $r->expensive_area_limit,
                                        'other_area_rate' => $r->other_area_rate,
                                        'other_area_limit' => $r->other_area_limit,
                                        'scale_year' => $r->scale_year,
                                        'updated_by' => $updated_by,
                                        'updated_at' => $updated_time
                                    ]);
                                if ($updateHomeAllowance == true) {
                                    return "Updated";
                                }
                            }else{
                                return "Duplicate";
                            }

                        }else{
                            return "Duplicate";
                        }


                    }else{
                        return "Duplicate";
                    }

                }




            }else{

                if(count($checkDuplicateAllowance) == 0 ){
                    if(count($valueBetweenFirstRange) == 0){
                        if(count($valueBetweenLastRange) == 0){
                            $createdBy = Auth::user()->id;
                            $createdTime = date('Y-m-d H:i:s');
                            $postHomeAllowance = DB::table('fixed_houserents')
                                ->insert([
                                    'first_range' => $r->salary_first_range,
                                    'last_range' => $r->salary_last_range,
                                    'dhaka_metro_politon_area_rate' => $r->dhaka_metro_politon_area_rate,
                                    'dhaka_metro_politon_area_limit' => $r->dhaka_metro_politon_area_limit,
                                    'expensive_area_rate' => $r->expensive_area_rate,
                                    'expensive_area_limit' => $r->expensive_area_limit,
                                    'other_area_rate' => $r->other_area_rate,
                                    'other_area_limit' => $r->other_area_limit,
                                    'scale_year' => $r->scale_year,
                                    'created_at' => $createdTime,
                                    'created_by' => $createdBy
                                ]);
                            if($postHomeAllowance == true) {
                                return "Success";
                            }
                        }else{
                            return "DuplicateSave";
                        }
                    }else{
                        return "DuplicateSave";
                    }
                }else{

                    return "DuplicateSave";
                }
            }
    }


    public function getHomeRentalAllowanceData() {

        $getHomeRentalData = DB::table('fixed_houserents')
            ->get();
        return json_encode($getHomeRentalData);
    }


    public function deleteHomeRentalAllowanceRates($id) {
        $deleteHomeRent = DB::table('fixed_houserents')
            ->where('id',$id)
            ->delete();
        if($deleteHomeRent == true) {
            return "Deleted";
        }
    }

    //===============================Home Rental Allowance Rates End ============================

    //=========================================Employee Basic Start==============================

    public function employeeBasicView() {

        $grade_list = DB::table('grades')
            ->select('grades.id', 'grades.grade_name')
            ->get();

        return view('default.payroll.employee-basic',compact('grade_list'));
    }




    //=========================================Employee Basic End==============================

    //===============================  Generate Salary Start  ========================================
    public  function getEmployeeNameDetails(Request $req)
    {
        $term = $req->term;//Input::get('term');
        $results = array();
        $queries = DB::table('employees')
            ->where('name', 'LIKE', '%'.$term.'%')
            // ->orWhere('last_name', 'LIKE', '%'.$term.'%')
            ->take(10)->get();
        if(!$queries){
            $results[] =['name' => 'no'];
        }
        else{
            foreach ($queries as $query)
            {
                $results[] = ['name' => $query->name, 'emp_id_name' => $query->emp_id, 'employee_id' => $query->id];
//                $results[] = ['value' => $query->BIN, 'vatreg_id' => $query->id, 'desc' => $query->NAME];
            }
        }

        return json_encode($results);

    }

    public function GenerateSalaryView() {
        //$employees = json_decode($this->getEmployeesInformation());
        $port_id = Session::get('PORT_ID');
        $employees = DB::table('employees')
            ->select('employees.id','employees.emp_id','employees.name')
            ->join('employee_histories', 'employee_histories.employee_id', '=', 'employees.id')
            ->join('employee_designations', 'employees.id','=', 'employee_designations.employee_id')
            ->join('employee_basic', 'employee_basic.employee_id','=', 'employees.id')
            //->where('employee_histories','employee_histories.port_id', $port_id)
            ->where('employees.status', 1)
            ->whereIn('employee_designations.id', function($query) {
                $query->select(DB::raw('MAX(ed.id)'))->from('employee_designations AS ed')
                ->groupBy('ed.employee_id')->get();
            })
            ->whereIn('employee_histories.id', function($query) use ($port_id) {
                $query->select(DB::raw('MAX(eh.id)'))->from('employee_histories AS eh')
                ->where('eh.port_id', $port_id)->groupBy('eh.employee_id')->get();
            })
            ->whereIn('employee_basic.id', function($query) {
                $query->select(DB::raw('MAX(eb.id)'))->from('employee_basic AS eb')
                    ->groupBy('eb.employee_id')->get();
            })
            ->orderBy('employees.id','ASC')
            ->get();
        return view('default.payroll.generate-salary', compact('employees'));
    }

    public function getEmployeesSalary(Request $r) {
        $port_id = Session::get('PORT_ID');
        $month_year = $r->month_year;
        $emp_ids = implode(",",$r->emp_ids);
        $getEmployeesSalary = DB::select("SELECT *, (total_in - total_de) total_payment
FROM (SELECT id,
             allocate_home,
             emp_id,
             NAME,
             designation,
             grade,
             grade_id,
              grade_in_number,
             scale_year,
             house_rent,
             house_rent_deduction,
             basic,
             education_allow,
             medical,
             tiffin,
             washing,
             transport,
             bonus,
             (basic + house_rent + education_allow + medical + tiffin + washing + transport)                     AS total_in,
             gpf,
             water,
             generator,
             electricity,
             previous_due,
             transport_deductions,
             deductions_month_year,
             revenue,
             (gpf + water + generator + electricity + previous_due + transport_deductions + revenue + house_rent_deduction) AS total_de
      FROM (SELECT id,
                   allocate_home,
                   emp_id,
                   NAME,
                   designation,
                   grade,
                   grade_id,
                    grade_in_number,
                   scale_year,
                   (basic + bonus)                            AS basic,
                   bonus,
                   house_rent,
                   house_rent_deduction,
                   (ed_allowanc * children)                   AS education_allow,
                   medical,
                   tiffin,
                   washing,
		   transport,
                   CAST((basic * gpf / 100) AS DECIMAL(14, 2)) AS gpf,
                   water,
                   generator,
                   electricity,
                   previous_due,
                   transport_deductions,
                   deductions_month_year,
                   revenue
            FROM (SELECT id,
                         allocate_home,
                         emp_id,
                         NAME,
                         children,
                         education,
                         grade,
                         grade_id,
                        grade_in_number,
                         basic,
                         scale_year,
                         house_rent,
                         medical,
                         (CASE WHEN grade_in_number >= 11 THEN tiffin ELSE 0 END) AS tiffin,
                         washing,
                        (CASE WHEN grade_in_number >= 11 THEN transport ELSE 0 END) AS transport,
                         gpf,
                         IFNULL(bonus, 0)                                          AS bonus,
                         IFNULL(water, 0)                                          AS water,
                         IFNULL(generator, 0)                                      AS generator,
                         IFNULL(electricity, 0)                                    AS electricity,
                         IFNULL(previous_due, 0)                                   AS previous_due,
                         IFNULL(transport_deductions, 0)			   AS transport_deductions,
                         deductions_month_year,
                         IFNULL(revenue, 0)                                        AS revenue,
                         (SELECT designations.designation
                          FROM designations
                          WHERE designations.id = desig_id
                          ORDER BY designations.id DESC
                          LIMIT 1)                                                 AS designation,
                         (CASE WHEN children >= 1 THEN education ELSE 0 END)       AS ed_allowanc,
                         (CASE WHEN allocate_home != 0 THEN house_rent ELSE 0 END) AS house_rent_deduction
                  FROM (SELECT employees.id,
                               employees.allocate_home,
                               employees.emp_id,
                               employees.name,
                               employees.children,
                               mobile,
                               (SELECT desig_id
                                FROM employee_designations
                                WHERE employee_designations.employee_id = (SELECT employees.id)
                                ORDER BY employee_designations.id DESC
                                LIMIT 1) AS desig_id,
                               (SELECT grade_name
                                FROM employee_basic
                                       JOIN grades ON grades.id = employee_basic.grade_id
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS grade,
                               (SELECT grades.id
                                FROM employee_basic
                                       JOIN grades ON grades.id = employee_basic.grade_id
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS grade_id,
                               (SELECT SUBSTRING_INDEX(grade_name,' ',-1)
                                FROM employee_basic
                                       JOIN grades ON grades.id = employee_basic.grade_id
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS grade_in_number,
                               (SELECT basic
                                FROM employee_basic
                                       JOIN grade_basics ON grade_basics.id = employee_basic.grade_basic_id
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS basic,
                               (SELECT house_rent
                                FROM employee_basic
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS house_rent,
                               (SELECT scale_year
                                FROM employee_basic
                                WHERE employee_basic.employee_id = (SELECT employees.id)
                                  AND employee_basic.port_id = ?
                                ORDER BY employee_basic.id DESC
                                LIMIT 1) AS scale_year,
                               (SELECT fixed_facilities_and_deductions.education
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS education,
                               (SELECT fixed_facilities_and_deductions.medical
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS medical,
                               (SELECT fixed_facilities_and_deductions.washing
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS washing,
                                (SELECT fixed_facilities_and_deductions.transport
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS transport,
                               (SELECT fixed_facilities_and_deductions.tiffin
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS tiffin,
                               (SELECT fixed_facilities_and_deductions.gpf
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS gpf,
                               (SELECT fixed_facilities_and_deductions.revenue
                                FROM fixed_facilities_and_deductions
                                WHERE fixed_facilities_and_deductions.port_id = ?
                                ORDER BY id DESC
                                LIMIT 1) AS revenue,
                               (SELECT water
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                 AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id))
                                LIMIT 1) AS water,
                               (SELECT generator
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id))
                                LIMIT 1) AS generator,
                               (SELECT electricity
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                 AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id))
                                LIMIT 1) AS electricity,
                               (SELECT previous_due
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                 AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id))
                                LIMIT 1) AS previous_due,
                                (SELECT transport
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                  AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id)) 
                                LIMIT 1) AS transport_deductions,
                                     (SELECT month_year
                                FROM deductions
                                WHERE deductions.employee_id = (SELECT employees.id)
                                  AND deductions.port_id = ?
                                  AND deductions.id = ( SELECT MAX(id) FROM deductions WHERE deductions.employee_id = (SELECT employees.id)) 
                                LIMIT 1) AS deductions_month_year,
                               (SELECT bonus.amount
                                FROM bonus
                                WHERE bonus.employee_id = (SELECT employees.id)
                                  AND bonus.port_id = ?
                                  AND DATE(bonus.date) = ?
                                LIMIT 1) AS bonus
                        FROM employees
                        WHERE employees.status = 1
                          AND employees.id IN ($emp_ids)) AS t) AS tt
            WHERE designation IS NOT NULL) AS ttt) AS tttt
ORDER BY grade_id ASC",[$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$port_id,$month_year]);
        return json_encode($getEmployeesSalary);
    }

    public function saveEmployeeSalaryData(Request $r) {
        $formatedMonthYear = date("Y-m-d",strtotime($r->payable_month_year));
        $user = Auth::user()->id;
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        $checkMonthYearSalary = DB::table('salarys')
                                    ->where('salarys.payable_month_year', $formatedMonthYear)
                                    ->where('salarys.port_id', $port_id)
                                    ->select('salarys.employee_id', 'salarys.payable_month_year')
                                    ->get();
        // if($checkMonthYearSalary) {
        //     $deletePreviousSalary = DB::table('salarys')
        //                                 ->where('payable_month_year', $formatedMonthYear)
        //                                 ->delete();
        // }
        $salaryRows = $r->salaryRows;
        $exitingEmp_Ids = [];
        foreach ($checkMonthYearSalary as $k => $v) {
           $exitingEmp_Ids[] = $v->employee_id; 
        }
        foreach($salaryRows as $key => $salary) {
            if(in_array($salary["id"], $exitingEmp_Ids, TRUE)) {
                $postEmployeeSalary = DB::table('salarys')
                                ->where('salarys.employee_id', $salary["id"])
                                ->where('salarys.payable_month_year', $formatedMonthYear)
                                ->where('salarys.port_id', $port_id)
                                ->update([
                                        'emp_id' => $salary["emp_id"],
                                        'emp_name' => $salary["name"],
                                        'emp_designation' => $salary["designation"],
                                        'emp_grade' => $salary["grade"],
                                        'new_salary' => $salary["basic"],
                                        //'old_salary' => 
                                        'house_rent' => $salary["house_rent"],
                                        'edu_allowance' => $salary["education_allow"],
                                        'medi_allowance' => $salary["medical"],
                                        //'due_edu_allowance' =>
                                        'washing' => $salary["washing"],
                                        'grade_id' => $salary["grade_id"],
                                        'scale_year' => $salary["scale_year"],
                                        'tiffin' => $salary["tiffin"],
                                        'total_in' => $salary["total_in"],
                                        'gpf' => $salary["gpf"],
                                        //'house_rent_deduction' => $salary["house_rent_d"],
                                        'water' => $salary["water"],
                                        'generator' => $salary["generator"],
                                        'previous_due' => $salary["previous_due"],
                                        'transport' => $salary["transport_deductions"],
                                        'electricity' => $salary["electricity"],
                                        'revenue' => $salary["revenue"],
                                        'total_deduction' => $salary["total_de"],
                                        'total_payable' => $salary["total_payment"],
                                        //'payable_month_year' => $formatedMonthYear,
                                        'updated_at' => $currentTime,
                                        'updated_by' => $user
                                    ]);
            } else {
                $postEmployeeSalary = DB::table('salarys')
                                        ->insert([
                                            'employee_id' => $salary["id"],
                                            'emp_id' => $salary["emp_id"],
                                            'emp_name' => $salary["name"],
                                            'emp_designation' => $salary["designation"],
                                            'emp_grade' => $salary["grade"],
                                            'new_salary' => $salary["basic"],
                                            //'old_salary' => 
                                            'house_rent' => $salary["house_rent"],
                                            'edu_allowance' => $salary["education_allow"],
                                            'medi_allowance' => $salary["medical"],
                                            //'due_edu_allowance' =>
                                            'washing' => $salary["washing"],
                                            'grade_id' => $salary["grade_id"],
                                            'scale_year' => $salary["scale_year"],
                                            'tiffin' => $salary["tiffin"],
                                            'total_in' => $salary["total_in"],
                                            'gpf' => $salary["gpf"],
                                            //'house_rent_deduction' => $salary["house_rent_d"],
                                            'water' => $salary["water"],
                                            'generator' => $salary["generator"],
                                            'previous_due' => $salary["previous_due"],
                                            'transport' => $salary["transport_deductions"],
                                            'electricity' => $salary["electricity"],
                                            'revenue' => $salary["revenue"],
                                            'total_deduction' => $salary["total_de"],
                                            'total_payable' => $salary["total_payment"],
                                            'payable_month_year' => $formatedMonthYear,
                                            'created_at' => $currentTime,
                                            'created_by' => $user,
                                            'port_id' => $port_id
                                        ]);
            }
            
        }

        
        if($postEmployeeSalary == true) {
            return "Success";
        }

    }

    public function getSalaryReport($month_year, $grade=null, $designation=null) {
        $port_id = Session::get('PORT_ID');
        $todayWithTime = date("Y-m-d h:i a");
        $formatedMonthYear = date("Y-m-d",strtotime($month_year));

        $checkSalary = DB::select("SELECT COUNT(salarys.id) AS salary_status 
                                FROM salarys
                                WHERE salarys.payable_month_year =? AND salarys.port_id =? ",[$formatedMonthYear, $port_id]);
//        $delete = DB::table('salarys')
//                    ->where('payable_month_year', $formatedMonthYear)
//                    ->delete();
        if (is_null($designation) && !is_null($grade)){
            $getSalaryPDF = DB::table('salarys')
                ->where('salarys.payable_month_year',$formatedMonthYear)
                ->whereEmpGrade($grade)
                ->wherePortId($port_id)
                ->orderBy('salarys.emp_grade', 'ASC')
                ->get();


        }else if (is_null($grade) && !is_null($designation)){

            $getSalaryPDF = DB::table('salarys')
                ->where('salarys.payable_month_year',$formatedMonthYear)
                ->whereEmpGrade($grade)
                ->wherePortId($port_id)
                ->orderBy('salarys.emp_grade', 'ASC')
                ->get();
        }

        else if(!is_null($grade) && !is_null($designation)){
            $getSalaryPDF = DB::table('salarys')
                ->where('salarys.payable_month_year',$formatedMonthYear)
                ->whereEmpGrade($grade)
                ->wherePortId($port_id)
                ->whereEmpDesignation($designation)
                ->orderBy('salarys.emp_grade', 'ASC')
                ->get();
        }
        else{
        $getSalaryPDF = DB::table('salarys')
                            ->where('salarys.payable_month_year',$formatedMonthYear)
                            ->wherePortId($port_id)
                            ->orderBy('salarys.emp_grade', 'ASC')
                            ->get();
    }

  //dd($formatedMonthYear);



        $pdf = PDF::loadview('default.payroll.reports.salary-sheet-report',[
                            'todayWithTime' => $todayWithTime,
                            'month_year' => $month_year,
                            'salaries' => $getSalaryPDF
                        ])
                        ->setPaper([0, 0,  800.661, 1180.63], 'landscape');
        return $pdf->stream('SalarySheet'."-".$todayWithTime.".pdf");
    }
    //===============================Generate Salary End==========================================

    //==========================================Employee Designation start===============================================//
    public function designationEmployeeView() {
        $port_id = Session::get('PORT_ID');
        return view('default.payroll.designation-employee');
    }


    public function saveDesignationData(Request $req) {
        $currentTime=date('Y-m-d H:i:s');
        $postDeg =DB::table('designations')
            ->insert([
                'designation' => $req->deg_name,
                'created_at' => $currentTime,
                'created_by'=>Auth::user()->id
            ]);
        if($postDeg == true) {
            return "Success";
        }
    }

    public function getAllDesignationDetails() {
        $getDeg = DB::table('designations')
                    ->get();
        return json_encode($getDeg);
    }




    public function getDesignationEmployeeInformation() {
        $port_id = Session::get('PORT_ID');
        $getDeg = DB::table('employee_designations')
            ->leftJoin('employees', 'employee_designations.employee_id', '=','employees.id')
            ->leftJoin('employee_histories', 'employee_histories.employee_id', '=', 'employees.id')
            ->leftJoin('designations', 'employee_designations.desig_id', '=', 'designations.id')
            ->where('employee_histories.port_id', $port_id)
            ->whereIn('employee_histories.id', function($query) use ($port_id) {
                $query->select(DB::raw('MAX(eh.id)'))->from('employee_histories AS eh')
                ->where('eh.port_id', $port_id)->groupBy('eh.employee_id')->get();
            })
            ->whereIn('employee_designations.id', function($query) use ($port_id) {
                $query->select(DB::raw('MAX(ed.id)'))->from('employee_designations AS ed')
                ->groupBy('employee_id')->get();
            })
            ->select(
                'employee_designations.id as emp_deg_id',
                'employee_designations.employee_id as employee_id',
                'employee_designations.desig_id as desig_id',
                'employees.id as e_id',
                'employees.emp_id as emp_id',
                'employees.name as name',
            'employees.photo',
                'designations.id as d_id',
                'designations.designation as designation'
            )
            ->orderBy('employee_designations.id', /*'DESC'*/'ASC')
            ->get();
        return json_encode($getDeg);
    }


    public function updateDesignationData(Request $r) {
        $updated_by = Auth::user()->id;
        $edit = DB::table('designations')
            ->where('id', $r->id)
            ->update([
                    'designation' => $r->designation,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        if($edit == true) {
            return "Successfully Edited";
        }
    }
    public function updateEmployeeDesignationData(Request $req) {
        $updatedTime= date('Y-m-d H:i:s');
        $updated_by = Auth::user()->id; 
        $Update = DB::table('employee_designations')
            ->where('id', $req->emp_deg_id)
            ->update([
                        'employee_id' => $req->Employee,
                        'desig_id' => $req->designation,
                        'updated_by' => $updated_by,
                        'updated_at' => $updatedTime
                ]);
        if($Update == true) {
            return "Successfully Edited";
        }
    }


    public function getEmployeesInformation() {
        $port_id = Session::get('PORT_ID');
        $Employees = DB::table('employees')
            ->select('employees.id','employees.emp_id','employees.name','employees.house_area_flag','employees.photo')
            ->join('employee_histories', 'employee_histories.employee_id', '=', 'employees.id')
            ->where('employee_histories.port_id', $port_id)
            ->where('employees.status', 1)
            ->whereIn('employee_histories.id', function($query) use ($port_id) {
                $query->select(DB::raw('MAX(eh.id)'))->from('employee_histories AS eh')
                ->where('eh.port_id', $port_id)->groupBy('eh.employee_id')->get();
            })
            ->get();
        return json_encode($Employees);
    }


    public  function  saveEmployeeDesignationData(Request $req)
    {

        $currentTime=date('Y-m-d H:i:s');
        DB::table('employee_designations')
            ->insert([
                'employee_id' => $req->Employee,
                'desig_id' => $req->designation,
                'created_at'=>$currentTime,
                'created_by'=>Auth::user()->username
            ]);
        return "successfully Insert";
    }





    public function deleteEmployeeDesignation($id)
    {
       $EmployDeg= DB::table('employee_designations')->where('id',$id)->delete();
        if($EmployDeg == true) {
            return "Deleted";
        }
    }

    public function deleteDesignation($id)
    {

        $checkSubhead = DB::table('employee_designations')
            ->where('desig_id', $id)
            ->get();
        if(count($checkSubhead)) {
            return "DesignationExist";

        } else {

        $deleteDeg = DB::table('designations')->where('id',$id)->delete();

            if($deleteDeg == true) {
                return "Deleted";
            }

        }
    }
    //==========================================Employee Designation End===============================================//


    //==================================================Bonus & Increment===============================================//

    public function bonusAndIncrementView() {
        return view('default.payroll.bonus-and-increment');
    }

    public function getEmployeesInfoForBonusTable() {
        return $this->getEmployeesInformation();
    }

    public function getEmployeeIncrementInformation() {
        return $this->getEmployeesInformation();
    }


    public function saveBonusData(Request $req) {
        $port_id = Session::get('PORT_ID');
        $currentTime = date('Y-m-d H:i:s');
        $currentUser = Auth::user()->id;
        DB::table('bonus')->insert(
            [
                'employee_id' => $req->Employee_bonus,
                'type' => $req->type_name,
                'date' => $req->bonus_data,
                'amount' => $req->Amount_salary,
                'created_at'=> $currentTime,
                'created_by'=> $currentUser,
                'port_id' => $port_id
            ]
        );
        return "successfully Insert";
    }

    public function updateBonousData(Request $req) {
        $currentTime = date('Y-m-d H:i:s');
        $currentUser = Auth::user()->id;
        $Update = DB::table('bonus')
            ->where('id', $req->id)
            ->update(
                [
                    'employee_id' => $req->Employee_bonus,
                    'type' => $req->type_name,
                    'date' => $req->bonus_data,
                    'amount' => $req->Amount_salary,
                    'updated_by' => $currentUser,
                    'updated_at' => $currentTime
                ]
            );
        if($Update == true) {
            return "Successfully Edited";
        }

    }


    public function getBonusData() {
        $port_id = Session::get('PORT_ID');
        $getDeg = DB::table('bonus')
            ->join('employees', 'bonus.employee_id', '=','employees.id')
            ->join('employee_histories', 'employee_histories.employee_id', '=', 'employees.id')
            ->whereIn('employee_histories.id', function($query) use ($port_id) {
                $query->select(DB::raw('MAX(eh.id)'))->from('employee_histories AS eh')
                ->where('eh.port_id', $port_id)->groupBy('eh.employee_id')->get();
            })
            ->where('employee_histories.port_id', $port_id)
            ->where('bonus.port_id', $port_id)
            ->select(
                'bonus.id as bonus_id',
                'bonus.employee_id as b_emp_id',
                'bonus.amount as amount',
                'bonus.type as type',
                'bonus.date as date',
                'employees.id as e_id',
                'employees.emp_id as emp_id',
                'employees.name as name'
            )
            ->orderBy('bonus.id', 'DESC')
            ->get();
        return json_encode($getDeg);
    }

    public function deleteBonusData($id)
    {



            $deleteDeg = DB::table('bonus')->where('id',$id)->delete();

            if($deleteDeg == true) {
                return "Deleted";
            }


    }



    //==================================================Bonus & Increment End===========================================//

    //=============================Report Stant================

    public function salaryReportView() {

        $designation_list=DB::table('designations')->get();
        $grade_list = DB::table('grades')->get();
       // dd($grade_list);
        return view('default.payroll.salary-report-view',['designation_list'=>$designation_list,'grade_list'=>$grade_list]);
    }

    public function perPersonWiseMonthlyReport(Request $r) {
        $todayWithTime = date('d-m-Y h:i a');
        $emp_id = intval(preg_replace('/[^0-9]+/', '', $r->emp_id), 10);
        $year = date("Y",strtotime($r->month_year));
        $month = date("m",strtotime($r->month_year));
        //return $emp_id." ".$year. " ". $month; 
        $perPersonWiseMonthlyReport = DB::select('SELECT *
                                                FROM salarys
                                                WHERE salarys.employee_id=? AND YEAR(salarys.payable_month_year)=? AND MONTH(salarys.payable_month_year) =?',[$emp_id, $year, $month]);
        $pdf = PDF::loadview('default.payroll.reports.per-person-wise-monthly-report',[
                            'todayWithTime' => $todayWithTime,
                            'month_year' => $r->month_year,
                            'perPersonWiseMonthlyReport' => $perPersonWiseMonthlyReport
                        ])
                        ->setPaper([0, 0,  800.661, 1080.63], 'landscape');
        return $pdf->stream(isset($perPersonWiseMonthlyReport[0]->emp_id) ? $perPersonWiseMonthlyReport[0]->emp_id.':MonthlyReport'."-".$todayWithTime.".pdf" : "");
        
    }

    public function perPersonWiseYearlyReport(Request $r) {
        $todayWithTime = date('d-m-Y h:i a');
        $emp_id = intval(preg_replace('/[^0-9]+/', '', $r->emp_id), 10);

        $from = date("Y-m-d",strtotime($r->from));

        $to = date("Y-m-d",strtotime($r->to));

        $perPersonWiseYearlyReport = DB::select('SELECT *, MONTHNAME(salarys.payable_month_year) 
                                    AS month_name, YEAR(salarys.payable_month_year) AS year_name
                                    FROM salarys
                                    WHERE salarys.employee_id=? AND salarys.payable_month_year 
                                    BETWEEN ? AND ?',[$emp_id, $from, $to]);
        //return $perPersonWiseYearlyReport;
        $pdf = PDF::loadview('default.payroll.reports.per-person-wise-yearly-report',[
                            'todayWithTime' => $todayWithTime,
                            'from' => $r->from,
                            'to' => $r->to,
                            'perPersonWiseYearlyReport' => $perPersonWiseYearlyReport
                        ])
                        ->setPaper([0, 0,  800.661, 1080.63], 'landscape');
        return $pdf->stream(isset($perPersonWiseYearlyReport[0]->emp_id) ? $perPersonWiseYearlyReport[0]->emp_id.':YearlyReport'."-".$todayWithTime.".pdf" : "");
    }

    //=============================Report End==================


    public function getGradeBasic($grade,$scaleYear) {
        $getData = DB::table('grade_basics')
            ->where('grade_id', $grade)
            ->where('scale_year', $scaleYear)
            ->get();
        return json_encode($getData);
    }

    public function getEmployeeWiseHomeRentArea($value) {

        $data = DB::select(' SELECT house_area_flag FROM employees WHERE employees.id=?', [$value]);

        return json_encode($data);
    }

    public function getHouseRent($houseRent) {

        $basic = DB::select('SELECT grade_basics.basic AS basic,grade_basics.scale_year FROM grade_basics WHERE grade_basics.id=?', [$houseRent]);

        if($basic[0]->basic >= 35501.00){
             $getData = DB::select(" SELECT * FROM fixed_houserents WHERE ( ( ? BETWEEN  first_range  AND last_range ) OR first_range >= 35501)   AND scale_year=?",[$basic[0]->basic,$basic[0]->scale_year]);
        }else{
            $getData = DB::select("SELECT * FROM fixed_houserents WHERE ? BETWEEN  first_range AND last_range AND scale_year=?",[$basic[0]->basic,$basic[0]->scale_year]);
        }



        return json_encode(array($getData,$basic));
    }

    public function saveEmployeeBasic(Request $r) {

        $userBy = Auth::user()->id;
        $currentTime = date('Y-m-d H:i:s');
        $port_id = Session::get('PORT_ID');

        if($r->employeeBasicID){ //Update

            $updateBasicData = DB::table('employee_basic')
                ->where('id',$r->employeeBasicID)
                ->update([
                    'grade_id' => $r->grade,
                    'grade_basic_id' => $r->grade_basic,
                    'employee_id' => $r->Employee,
                    'scale_year' => $r->scale_year,
                    'house_rent' => $r->home_rent,
                    'house_rent_id' => $r->houseRentID,
                    'port_id' => $port_id,
                    'updated_by' => $userBy,
                    'updated_at' => $currentTime
                ]);
            if($updateBasicData == true) {
                return "Updated";
            }

        }else{//Save

            $postBasicData = DB::table('employee_basic')
                ->insert([
                    'grade_id' => $r->grade,
                    'grade_basic_id' => $r->grade_basic,
                    'employee_id' => $r->Employee,
                    'scale_year' => $r->scale_year,
                    'house_rent' => $r->home_rent,
                    'house_rent_id' => $r->houseRentID,
                    'port_id' => $port_id,
                    'created_at' => $currentTime,
                    'created_by' => $userBy
                ]);
            if($postBasicData == true) {
                return "Success";
            }
        }


    }

    public function getAllEmployeeBasicData() {
        $getData = DB::select("SELECT eb.id,eb.scale_year, g.grade_name, g.id AS grade_id, gb.basic,eb.house_rent, gb.id AS grade_basics_id, e.emp_id, e.name, 
        e.id AS employee_id FROM employee_basic AS eb
        JOIN grades AS g ON g.id = eb.grade_id
        JOIN grade_basics AS gb ON gb.id = eb.grade_basic_id
        JOIN employees AS e ON e.id = eb.employee_id
    ");

        return json_encode($getData);
    }

    public function deleteEmployeeBasicData($id) {
        $deleteData = DB::table('employee_basic')
            ->where('id',$id)
            ->delete();
        if($deleteData == true) {
            return "Deleted";
        }
    }

    //========================================================Grede and Grade Basic Start=============================
    public function gradeAndGradeBasicView() {
        $port_id = Session::get('PORT_ID');
        $grade_list = DB::table('grades')
            ->select('grades.id', 'grades.grade_name')
            ->get();
        return view('default.payroll.grade-and-grade-basic',compact('grade_list'));
    }

    public function saveUpdateGradeData(Request $req) {
        $currentTime=date('Y-m-d H:i:s');
        $userBy = Auth::user()->id;

        if($req->grade_id){ //update

            $updateData = DB::table('grades')
                ->where('id', $req->grade_id)
                ->update([
                        'grade_name' => $req->grade_name,
                        'updated_by' => $userBy,
                        'updated_at' => $currentTime
                    ]
                );
            if($updateData == true) {
                return "Update";
            }

        }else{  //save

            $postGrade =DB::table('grades')
                ->insert([
                    'grade_name' => $req->grade_name,
                    'created_at' => $currentTime,
                    'created_by'=>  $userBy
                ]);
            if($postGrade == true) {
                return "Success";
            }
        }

    }

    public function getGradeDataDetails() {
        $getGrade = DB::table('grades')
            ->get();
        return json_encode($getGrade);
    }

    public function deleteGradeData($id)
    {

        $deleteGrade = DB::table('grades')->where('id',$id)->delete();

        if($deleteGrade == true) {
            return "Deleted";
        }
    }

    public function deleteGradeBasicData($id)
    {

        $deleteGradeBasic = DB::table('grade_basics')->where('id',$id)->delete();

        if($deleteGradeBasic == true) {
            return "Deleted";
        }
    }


    public  function  saveUpdateGradeBasic(Request $req)
    {

        $currentTime = date('Y-m-d H:i:s');
        $userBy = Auth::user()->id;

        if($req->grade_basic_id){ //update

            $updateData = DB::table('grade_basics')
                ->where('id', $req->grade_basic_id)
                ->update([
                        'grade_id' => $req->grade,
                        'basic' => $req->basic_salary,
                        'level' => $req->basic_level,
                        'scale_year' => $req->scale_year,
                        'updated_at'=>$currentTime,
                        'updated_by'=>$userBy
                    ]
                );
            if($updateData == true) {
                return "Update";
            }

        }else{ // save

            $postGradeBasic = DB::table('grade_basics')
                ->insert([
                    'grade_id' => $req->grade,
                    'basic' => $req->basic_salary,
                    'level' => $req->basic_level,
                    'scale_year' => $req->scale_year,
                    'created_at'=>$currentTime,
                    'created_by'=>$userBy
                ]);
            if($postGradeBasic == true) {
                return "Success";
            }

        }



        return "Success";
    }


    public function getAllGradeBasicData() {
        $getData = DB::select("SELECT gb.id AS g_basic_id, gb.basic, gb.level, gb.scale_year,grades.id AS g_id, grades.grade_name FROM grade_basics AS gb
JOIN grades ON grades.id = gb.grade_id");

        return json_encode($getData);
    }



    public function getSelectYear() {
        $getSelectYear = DB::select('SELECT DISTINCT scale_year FROM grade_basics');
        return json_encode($getSelectYear);
    }

    //========================================================Grede and Grade Basic End=============================

}
