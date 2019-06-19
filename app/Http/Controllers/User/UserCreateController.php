<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\ProjectBaseController;
use App\Http\Controllers\Controller;

use App\Models\Designation\Designation;
use App\Models\Port;
use App\Models\Warehouse\ShedYard;
use App\Models\Weighbridge\Weighbridge;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;

//use Input;
//use Validator;
// use Crypt;
//use Storage;
use File;
use Illuminate\Validation\Rule;
use Image;
use Response;
use Session;

class UserCreateController extends ProjectBaseController
{


    public function userPortSession()
    {
//        dd('no port func');
        $theUser = User::findOrFail(Auth::user()->id);
        $userPorts = $this->user->FindOrFail(Auth::user()->id)->ports()->get();
        return view('port-session', compact('userPorts', 'theUser'));
    }

    //==============User Start======================
    public function userEntryForm() {
        $port_id = \Session::get('PORT_ID');
        $yard = (new ShedYard)->where('port_id',$port_id)->get();
        $weighbridgeList = (new Weighbridge())->wherePortId($port_id)->get();
        $portList = (new Port())->all();
        return view('default.user.user-entry-form', ['yards' => $yard, 'portList' => $portList, 'weighbridgeList' => $weighbridgeList]);
    }

    public function getEmployeeDetails(Request $r) {
        $port_id = Session::get('PORT_ID');
        $user_type = $r->user_type;
        $employee_name_or_id = '%'.$r->employee_name_or_id.'%';
        $data = [];
        if($user_type == 'port') {
            if($r->port_employee_id != null) {
                //return $r->port_employee_id;
                $data = DB::select('SELECT e.id, e.name, e.emp_id, e.father_name, e.mother_name, 
                            e.mobile, e.email, e.date_of_birth, e.national_id,
                            (SELECT d.designation FROM designations AS d
                            JOIN employee_designations AS ed ON ed.desig_id=d.id
                            WHERE ed.employee_id = e.id
                            ORDER BY ed.id DESC LIMIT 1) AS designation,
                            (select p.port_name from ports AS p where p.id = eh.id) AS organization,
                            CASE 
                            WHEN e.photo IS NOT NULL THEN CONCAT("img/employees","/",e.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            employees AS e
                            JOIN employee_histories AS eh ON eh.employee_id = e.id
                            WHERE eh.id IN 
                            (SELECT MAX(employee_histories.id)
                            FROM employee_histories
                            WHERE employee_histories.port_id=?
                            GROUP BY employee_histories.employee_id
                            ) 
                            AND e.id=? AND eh.port_id=?',[$port_id, $r->port_employee_id, $port_id]);
            } else if($r->employee_name_or_id != null){
                $data = DB::select('SELECT e.id, e.name, e.emp_id, e.father_name, e.mother_name, 
                            e.mobile, e.email, e.date_of_birth, e.national_id,
                            (SELECT d.designation FROM designations AS d
                            JOIN employee_designations AS ed ON ed.desig_id=d.id
                            WHERE ed.employee_id = e.id
                            ORDER BY ed.id DESC LIMIT 1) AS designation,
                            (select p.port_name from ports AS p where p.id = eh.id) AS organization,
                            CASE 
                            WHEN e.photo IS NOT NULL THEN CONCAT("img/employees","/",e.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            employees AS e
                            JOIN employee_histories AS eh ON eh.employee_id = e.id
                            WHERE eh.id IN 
                            (SELECT MAX(employee_histories.id)
                            FROM employee_histories
                            WHERE employee_histories.port_id=?
                            GROUP BY employee_histories.employee_id
                            ) 
                            AND (e.name LIKE ? OR e.emp_id LIKE ?) AND eh.port_id=?
                            GROUP BY e.id',[$port_id, $employee_name_or_id, $employee_name_or_id, $port_id]); 
            }
        } else if($user_type == 'c&f') {
            if($r->cnf_employee_id != null) {
                $data = DB::select('SELECT ce.id, ce.name, cd.cnf_name AS organization, ce.designation, 
                            ce.mobile, ce.email, ce.date_of_birth, ce.national_id,
                            CASE 
                            WHEN ce.photo IS NOT NULL THEN CONCAT("img/cnf-employees","/",ce.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            cnf_employees AS ce
                            INNER JOIN cnf_details AS cd ON cd.id = ce.cnf_detail_id
                            INNER JOIN cnf_port AS cp ON cp.cnf_id = cd.id
                            WHERE cp.port_id = ? AND ce.id = ?',[$port_id, $r->cnf_employee_id]);
            } else if($r->employee_name_or_id != null) {
                $data = DB::select('SELECT ce.id, ce.name, cd.cnf_name AS organization, ce.designation, 
                            ce.mobile, ce.email, ce.date_of_birth, ce.national_id,
                            CASE 
                            WHEN ce.photo IS NOT NULL THEN CONCAT("img/cnf-employees","/",ce.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            cnf_employees AS ce
                            INNER JOIN cnf_details AS cd ON cd.id = ce.cnf_detail_id
                            INNER JOIN cnf_port AS cp ON cp.cnf_id = cd.id
                            WHERE cp.port_id = ? AND ce.name LIKE ?',[$port_id, $employee_name_or_id]);
            }
        } else if($user_type == 'custom') {
            if($r->custom_employee_id != null) {
                $data = DB::select('SELECT cue.id, cue.name, cue.organization, cue.designation,
                            cue.mobile, cue.email, cue.date_of_birth, cue.national_id,
                            CASE 
                            WHEN cue.photo IS NOT NULL THEN CONCAT("img/custom-employees","/",cue.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            custom_employees AS cue
                            WHERE cue.id=?',[$r->custom_employee_id]);
            } else if($r->employee_name_or_id != null) {
                $data = DB::select('SELECT cue.id, cue.name, cue.organization, cue.designation,
                            cue.mobile, cue.email, cue.date_of_birth, cue.national_id,
                            CASE 
                            WHEN cue.photo IS NOT NULL THEN CONCAT("img/custom-employees","/",cue.photo)
                            ELSE NULL
                            END AS photo
                            FROM
                            custom_employees AS cue
                            WHERE cue.name LIKE ?',[$employee_name_or_id]);
            }
        }
        return json_encode($data);
    }

    public function checkUsername($username) {
        $checkUsername = DB::select("SELECT COUNT(users.username) AS exist  
                                    FROM users 
                                    WHERE users.username=?", [$username]);
        return json_encode($checkUsername);
    }

    public function saveUserData(Request $r) {
        $userNameDuplication = json_decode($this->checkUsername($r->username));
        if ($userNameDuplication[0]->exist > 0) {
            return Response::json(['userNameExist' => 'Username Exist.'], 201);
        }
        $createdBy = Auth::user()->id;
        $createdTime = date('Y-m-d H:i:s');
        $port_ids = explode(",", $r->port_id);

        \Log::info($r);
        $insertUser = DB::table('users')
            ->insertGetId([
                'current_port' => $port_ids[0],
                'user_type' => $r->user_type,
                'port_employee_id' => $r->user_type == 'port' ? $r->emp_id : null,
                'cnf_employee_id' => $r->user_type == 'c&f' ? $r->emp_id : null,
                'custom_employee_id' => $r->user_type == 'custom' ? $r->emp_id : null, 
                'role_id' => $r->role_id,
                'name' => $r->name == 'null' ? null : $r->name,
                'email' => $r->email == 'null' ? "no" : $r->email,
                'mobile' => $r->mobile == 'null' ? null : $r->mobile,
                'username' => $r->username,
                'password' => bcrypt($r->password),
                'password1' => sha1($r->password),
                'user_status' => $r->user_status,
                'remember_token' => str_random(100),
                'photo' => $r->photo == 'null' ? null : $r->photo,
                'created_at' => $createdTime,
                'created_by' => $createdBy,
            ]);


        DB::table('user_office_order')
            ->insert([
                'user_id' => $insertUser,
                'office_order' =>  $r->office_order,
                'created_by' =>$createdBy,
            ]);

        $user = User::findOrFail($insertUser);
        $user->ports()->attach($port_ids);

        if($r->role_id == 6) {
            $weighbrides_array = explode(",", $r->scale);
            $user->weighbridges()->attach($weighbrides_array);
        }

        if($r->role_id == 8 || $r->role_id == 12) {
            $shedYards_array = explode(",", $r->shedYards);
            $user->shedYards()->attach($shedYards_array);
        }
        
        if ($insertUser == true) {
            return 'Success';
        }
    }

    public function getAllUser(Request $r) {
        $port_id = Session::get('PORT_ID');
        $allUser = DB::select("SELECT u.id, u.user_type, u.port_employee_id, u.cnf_employee_id, 
                            u.custom_employee_id, u.name, u.mobile, u.email, u.role_id, u.username,
                            u.user_status, u.photo,
                            (SELECT GROUP_CONCAT(pus.port_id) FROM port_user AS pus 
                            WHERE pus.user_id=u.id) AS port_ids,
                            (SELECT GROUP_CONCAT(syu.shed_yard_id) FROM shed_yard_user 
                            AS syu WHERE syu.user_id=u.id) AS shed_yard_ids,
                            (SELECT GROUP_CONCAT(uofo.office_order) FROM user_office_order AS uofo 
                             WHERE uofo.user_id=u.id) AS office_orders,
                            r.name AS rolename, wu.scale_id 
                            FROM users AS u 
                            LEFT JOIN roles AS r ON r.id=u.role_id
                            LEFT JOIN weighbridge_users AS wu ON wu.user_id=u.id
                            JOIN port_user AS pu ON pu.user_id = u.id
                            WHERE u.user_status != '2' AND pu.port_id = ? AND u.user_type = ?
                            ORDER BY u.id DESC",[$port_id, $r->user_type]);
        return json_encode($allUser);
    }

    public function updateUserData(Request $r) {
        $currentUsername = DB::table('users')
            ->where('id', '=', $r->id)
            ->select('users.username')
            ->get();
        if ($currentUsername[0]->username != $r->username) {
            $userNameDuplication = json_decode($this->checkUsername($r->username));
            if ($userNameDuplication[0]->exist > 0) {
                return Response::json(['userNameExist' => 'Username Exist.'], 201);
            }
        }
        $updatedBy = Auth::user()->id;
        $updatedTime = date('Y-m-d H:i:s');
        $updateUser = DB::table('users')
            ->where('id', '=', $r->id)
            ->update([
                'current_port' => null,
                'user_type' => $r->user_type,
                'port_employee_id' => $r->user_type == 'port' ? $r->emp_id : null,
                'cnf_employee_id' => $r->user_type == 'c&f' ? $r->emp_id : null,
                'custom_employee_id' => $r->user_type == 'custom' ? $r->emp_id : null, 
                'role_id' => $r->role_id,
                'name' => $r->name == 'null' ? null : $r->name,
                'email' => $r->email == 'null' ? 'no' : $r->email,
                'mobile' => $r->mobile == 'null' ? null : $r->mobile,
                'username' => $r->username,
                'user_status' => $r->user_status,
                'photo' => $r->photo == 'null' ? null : $r->photo,
                'updated_at' => $updatedTime,
                'updated_by' => $updatedBy,
            ]);
        DB::table('user_office_order')
            ->insert([
                'user_id' => $r->id,
                'office_order' =>  $r->office_order,
                'created_by' =>$updatedBy,
            ]);

        if($r->password != 'null') {
            $passwordUpdate = DB::table('users')
                            ->where('id', '=', $r->id)
                            ->update([
                                'password' => bcrypt($r->password)
                            ]);
        }
        $user = User::findOrFail($r->id);

        $port_ids = [];
        $shed_yard_ids = [];
        $weighbridge_ids = [];

        $port_ids = explode(",", $r->port_id);

        if($r->shedYards != 'undefined') {
            $shed_yard_ids = explode(",",$r->shedYards);
        }

        if($r->scale != null) {
            $weighbridge_ids = explode(",",$r->scale);
        }


        if(count($user->ports) > 0) {
            $user->ports()->sync($port_ids);
        } else {
            $user->ports()->attach($port_ids);
        }

        if(count($user->shedYards) > 0) {
            $user->shedYards()->sync($shed_yard_ids);
        } else {
            $user->shedYards()->attach($shed_yard_ids);
        }

        if(count($user->weighbridges) > 0) {
            $user->weighbridges()->sync($weighbridge_ids);
        } else {
            $user->weighbridges()->attach($weighbridge_ids);
        }

        if($updateUser == true) {
            if(isset($passwordUpdate)) {
                return 'Updated with password';
            }
            return 'Updated';
        }
    }

    public function deleteUserData(Request $r) {

        $user = User::findOrFail($r->id);

        $port_ids = [];
        $shed_yard_ids = [];
        $weighbridge_ids = [];

      //  dd(count($user->ports));

        if(count($user->ports) > 0) {
            $user->ports()->sync($port_ids);
        }

        if(count($user->shedYards) > 0) {
            $user->shedYards()->sync($shed_yard_ids);
        }

        if(count($user->weighbridges) > 0) {
            $user->weighbridges()->sync($weighbridge_ids);
        }

        $deleteUser = DB::table('users')
            ->where('id', $r->id)
            ->delete();

        if ($deleteUser == true) {
            return "deleted";
        }
    }

    public function userEditForm($id)
    {
        $theUser = User::findOrFail($id);
        $userPorts = $theUser->ports()->pluck('port_id')->toArray();


        //$designationList = (new Designation())->designationList();
        //$roleList = (new Role())->roleList();
        $portList = (new Port())->all();
        //$shedYardList = (new ShedYard())->all();
        //$userShedYard = $theUser->shedYards()->pluck('shed_yard_id')->toArray();


        //$weighbridgeList = (new Weighbridge())->wherePortId(\Session::get('PORT_ID'))->get();
        //$userWeighbridge = $theUser->weighbridges()->pluck('scale_id')->toArray();

//        dd($userWeighbridg);


        return view('default.user.edit', compact(
            'theUser', /*'designationList', 'roleList',*/ 'portList',
            'userPorts'/*, 'shedYardList', 'userShedYard','weighbridgeList','userWeighbridge'*/
        ));
    }

    public function userUpdate($id, Request $r)
    {
        $theUser = $this->user->findOrFail($id);
        if (isset($theUser->ownFields)) {
            // foreach ($theUser->ownFields as $ownField) {
            //     if ($r->{$ownField} || $r->{$ownField} == 0 && $r->{$ownField} != null) {
            //         $theUser->{$ownField} = $r->{$ownField};
            //     }
            // }



            //attach or sync Weighbridge to the user
            // if (count(User::findOrFail($id)->weighbridges) > 0) {
            //     $theUser->weighbridges()->sync($r->shed_yard_ids);
            // } else {
            //     $theUser->weighbridges()->attach($r->shed_yard_ids);
            // }
            //attach or sync port to the user
            if (count(User::findOrFail($id)->ports) > 0) {
                $theUser->ports()->sync($r->port_ids);
            } else {
                $theUser->ports()->attach($r->port_ids);
            }

            //attach or sync shedyard to the user
            // if(count(User::findOrFail($id)->shedYards) > 0) {
            //     $theUser->shedYards()->sync($r->shed_yard_ids);
            // } else {
            //     $theUser->shedYards()->attach($r->shed_yard_ids);
            // }



            $theUser->save();

            // if ($r->hasFile('photo') || $r->hasFile('nid_photo')) {


            //     if ($r->hasFile('photo')) {
            //         $image = $r->file('photo');
            //         $imageName = $theUser->id . '.jpg'; //$image->getClientOriginalExtension();


            //         $destinationPath = public_path('img/users');

            //         $image->move($destinationPath, $imageName);
            //         $img = Image::make($destinationPath . '/' . $imageName);

            //         $img->resize(100, 100, function ($constraint) {
            //             $constraint->aspectRatio();
            //         })->encode('jpg')->save($destinationPath . '/' . $imageName);
            //         DB::table('users')->where('id', $theUser->id)->update([
            //             'photo' => $imageName
            //         ]);
            //     }

            //     //nid photo
            //     if ($r->hasFile('nid_photo')) {

            //         $nid_photo = $r->file('nid_photo');
            //         $nid_photoName = (string)$theUser->id . '_nid.jpg'; //$image->getClientOriginalExtension();
            //         $destinationPath_nid_photo = public_path('img/users/nid');
            //         $nid_photo->move($destinationPath_nid_photo, $nid_photoName);
            //         $img_nid_photo = Image::make($destinationPath_nid_photo . '/' . $nid_photoName);
            //         $img_nid_photo->resize(100, 100)->encode('jpg')->save($destinationPath_nid_photo . '/' . $nid_photoName);
            //         DB::table('users')->where('id', $theUser->id)->update([
            //             'nid_photo' => $nid_photoName
            //         ]);
            //     }

            //     return back()->withSuccess('Successfully Updated With Photo Or NID Copy');
            // }

            return back()->withSuccess('Successfully Updated User Port');
        }


    }

    public function updateUserPortSession(Request $r) {

        $this->validate($r, [
            'port_id' => 'required'
        ]);
        $user = auth()->user();
        $user->current_port = $r->port_id;
        $user->save();
        session()->put('PORT_ID', $r->port_id);
        session()->put('PORT_ALIAS', Auth::user()->ports()->findOrFail($r->port_id)->port_alias);
        session()->put('PORT_NAME', Auth::user()->ports()->findOrFail($r->port_id)->port_name);
        return back()->withSuccess('Successfully Updated Your Port!');
    }

    // public function getPortForUserJson() {
    // 	$ports = DB::table('ports')->get();
    // 	return json_encode($ports);
    // }

    public function getRoleForUser()
    {
        $roles = DB::table('roles')->get();
        return json_encode($roles);
    }

    // public function getOrgTypeForUserJson() {
    // 	$orgType = DB::table('org_types')
    //                     ->where('id', '!=', 1)
    //                     ->Where('id', '!=', 2)
    //                     ->get();
    // 	return json_encode($orgType);
    // }

    // public function getOrgForUser()
    // {
    //     $org = DB::table('organizations')
    //         ->get();
    //     return json_encode($org);
    // }

    

    // public function checkPassword(Request $r) {
    //     $DbPassword = DB::table('users')
    //                         ->where('id', '=', $r->id)
    //                         ->select('users.password')
    //                         ->get();
    //     $encryptedDbPassword = $DbPassword[0]->password;
    //     // $file = fopen("password.txt","w");
    //     // echo fwrite($file, $pass);
    //     // fclose($file);
    //     //$encryptedUserPassword = bcrypt(trim($r->password));
    //     // $file = fopen("password.txt","w");
    //     // echo fwrite($file, $encryptedDbPassword." ".$encryptedUserPassword);
    //     // fclose($file);
    //     if(Hash::check($r->password, $encryptedDbPassword)) {
    //         return 'match';
    //     } else {
    //         return 'notMatch';
    //     }
    // }

    



    // public function updateUserDataWithNewPassword(Request $r) {
    //     $portId = DB::table('organizations')
    //                 ->where('id','=',$r->org_id)
    //                 ->select('organizations.port_id')
    //                 ->get();
    //     $createdBy = Auth::user()->name;
    //     $updatedTime = date('Y-m-d H:i:s');
    //     $updateUser = DB::table('users')
    //                         ->where('id', '=', $r->id)
    //                         ->update([
    //                                 'port_id' => $portId[0]->port_id,
    //                                 'role_id' => $r->role_id,
    //                                 'org_type_id' => $r->org_type_id,
    //                                 'org_id' => $r->org_id,
    //                                 'name' => $r->name,
    //                                 'username' => $r->username,
    //                                 'email' => $r->email,
    //                                 'password' => bcrypt($r->password),
    //                                 'updated_at' => $updatedTime,
    //                                 'created_by' => $createdBy,
    //                                 'photo' => $r->photo
    //                                 ]);
    //     if($updateUser == true) {
    //         return 'Success';
    //     }
    // }
    //==============User End======================

    //==============Change Password Start===============
    public function changePasswordView()
    {
        return view('default.user.change-password');
    }

    public function saveChangePassword(Request $r)
    {
        if ($r->new_password != $r->confirm_password) {
            return Response::json(['notMatch' => 'Password do not match.'], 401);
        }
        if ($r->new_password == $r->old_password) {
            return Response::json(['noChange' => 'Old Password And New Password are same. Please try New Password'], 403);
        }
        $currentPassword = Auth::User()->password;
        if (Hash::check($r->old_password, $currentPassword)) {
            $changePassword = DB::table('users')
                ->where('id', Auth::user()->id)
                ->update([
                    'password' => bcrypt($r->new_password),
                    'password1' => sha1($r->new_password)
                ]);
            if ($changePassword == true) {
                Auth::logout();
                return Response::json(['changed' => 'Password successfully changed.'], 202);
            }
        } else {
            return Response::json(['wrongPassword' => 'Please enter Old Password correctly.'], 402);
        }
    }
    //==============Change Password End=================
    //=================Online Users====================
    public function onlineUsersView()
    {
        $users = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('ports', 'ports.id', '=', 'users.current_port')
            ->select('users.*', 'roles.name AS rolename','ports.port_name AS current_port_name')
            ->orderBy('users.id')
            ->get();
        return view('default.user.online-users', compact('users'));
    }
    //=================Online Users====================

    //------------------------------ Designation Autocomplete ----------------------

    public function getDesignation()
    {
        $designation = DB::select('SELECT DISTINCT designation FROM 
users WHERE designation IS NOT NULL');

        return json_encode($designation);

    }

    //------------------------------ Designation End -------------------------------
}