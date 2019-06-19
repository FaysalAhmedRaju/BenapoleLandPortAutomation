<?php

namespace App\Http\Controllers;

use App\GroupAccess;
use App\Role;
use Illuminate\Http\Request;
use Auth;
use DB;

class GroupAccessController extends Controller
{

    private $groupAccess;

    public function __construct(GroupAccess $groupAccess)
    {
        $this->middleware('auth');
        $this->groupAccess = $groupAccess;
    }


    public function assignAccess(Request $request)
    {

        $accessType =$request->access_type;
        $accessRole =$request->access_role_id;
        $accessRoleName = Role::findOrfail($request->access_role_id);
        $accessMenu =$request->access_menu_id;
        $isAccessed = $request->access_checked;
        //$siteid= Auth::user()->port_id;
//dd($accessRoleName->id);

        if($isAccessed== "true"){
            $isAccessed = true;
        }elseif($isAccessed == "false"){
            $isAccessed = false;
        }

        $groupAccess = $this->groupAccess;


        $insertedRow = $this->groupAccess->whereMenuId($accessMenu)->whereRoleId($accessRole)->first();

        if (file_exists(base_path("resources/views/layouts/sidebars/". $accessRoleName->id . '.blade.php'))) {
            unlink(base_path("resources/views/layouts/sidebars/" . $accessRoleName->id . '.blade.php'));
        }

        if(is_null($insertedRow)){

            $this->groupAccess->create([
                'menu_id'=>$accessMenu,
                'role_id'=>$accessRole,
                $accessType=>$isAccessed
            ]);

        }else{
            $insertedRow->update([
                'menu_id'=>$accessMenu,
                'role_id'=>$accessRole,
                $accessType=>$isAccessed
            ]);
        }
        return response()->json(['success'=>'successfully updated']);
    }

}
