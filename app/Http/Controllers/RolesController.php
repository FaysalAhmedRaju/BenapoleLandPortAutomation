<?php

namespace App\Http\Controllers;

use App\GroupAccess;
use App\Menu;
use App\Role;
use DB;
use Auth;
use Illuminate\Http\Request;
use Response;
use Route;
use Alert;
use Session;

class RolesController extends Controller
{

    private $groupAccess;

    public function __construct(GroupAccess $groupAccess)
    {
        $this->middleware('auth');
        $this->groupAccess = $groupAccess;

    }

    public function assignPermissionForm(Menu $menu, Role $role, GroupAccess $groupAccess1)
    {

        $viewType = 'Assign Permission';
        $role_list = $role->all();
        $module_list = $menu->select('id', 'module_name')->groupBy('module_name')->get();

        return view('role.assign-permission', compact('viewType', 'role_list', 'module_list'));
    }

    public function getAssignedRouteList(GroupAccess $groupAccess, $id)
    {
        $viewType = 'Assigned Route List';
//        $role_list = $role->all();


        return view('role.group-access', compact('menus', 'groupId', 'roleToAssign', 'groupAccess'));
    }


    public function getGroupAccessTable(Menu $menu, Request $req, Role $role, GroupAccess $groupAccess1)
    {

        //return view('Bank.BankPayment');


        $groupId = $req->groupId;
        $module_name = $req->module_name;
        $groupAccess = $groupAccess1;
        $menus = null;

        $roleToAssign = $role->findOrFail($groupId);
        if ($module_name) {

            $menus = $menu->where('is_common_access', false)->where('module_name', $module_name)->orderBy('id', 'DESC')->get();

            if ($module_name == 'assigned') {

                $menus =  $menu->whereHas('groupAccess',  function($q) use ($groupId)
                {
                    $q->where('role_id',$groupId);
                    $q->where('view',true);

                })->get();

            }
        } else {
            $menus = $menu->where('is_common_access', false)->orderBy('id', 'DESC')->get();
        }
//\Log::info(request()->ajax());

        if (request()->ajax()) {
            return view('role.group-access', compact('menus', 'groupId', 'roleToAssign', 'groupAccess'));
        }
    }


    public function index(Role $role)
    {
       // $this->rol
//        $model = $this->role;
        $rolesData = $role->orderBy('id', 'DESC')->get();

      //  $rolesData = $role->all();
        $viewType = 'Roles List';

        return view('role.index-role', compact('rolesData', 'viewType'/*, 'model'*/));
    }



    public function createRole()
    {

        $viewType = 'Create Role';
        $dashboardRoutes=Menu::whereRouteType('view')->get();

        $dashboardRouteList=[];

        foreach ($dashboardRoutes as $k=>$v){
            $dashboardRouteList[$v->id]=$v->route_name;
        }

        //dd($dashboardRouteList);

        return view('role.create-role', compact('viewType','dashboardRouteList'));

    }


    public function saveRole(Role $role,Request $req)
    {
        $this->validate($req, [
//            'icon_name' => 'required',
//            'menu_name' => 'required',
            'name' => 'required|unique:roles',

        ]);


        if (isset($role->ownFields)) {
            foreach ($role->ownFields as $ownfield) {
                if ($req->{$ownfield}) {
                    $role->{$ownfield} = $req->{$ownfield};
                }
            }
        }
        $role->created_at = date('Y-m-d H:i:s');

        if ($role->save()) {
            return  redirect()->route('role-list')->withSuccess('Successfully Created Role!');

        } else {
            return back()->withError('Something Went Wrong!');
        }
    }


    public function editRole($id,Role $role)
    {
        $viewType = 'Role Edit Form';
       $theRoleData = $role->findOrFail($id);

        $dashboardRoutes=Menu::whereRouteType('view')->get();
        $dashboardRouteList=[];
        foreach ($dashboardRoutes as $k=>$v){
            $dashboardRouteList[$v->id]=$v->route_name;
        }


        return view('role.edit-role', compact('viewType', 'theRoleData','dashboardRouteList'));

    }


    public function updateRole(Role $role,Request $r,$id)
    {

        $this->validate($r, [
//            'icon_name' => 'required',
//            'menu_name' => 'required',
            'name' => 'required|unique:roles,name,' . $id,

        ]);




        $theRoleData = $role->findOrFail($id);
        if (isset($theRoleData->ownFields) && !empty($theRoleData->ownFields)) {
            foreach ($theRoleData->ownFields as $k => $ownField) {

                if ($r->{$ownField}) {
                    $theRoleData->{$ownField} = $r->{$ownField};
                }
            }
        }


        $theRoleData->updated_at = date('Y-m-d H:i:s');

        if ($theRoleData->save()) {
            return  redirect()->route('role-list')->withSuccess('Successfully Updated Role!');
        }
        return back()->withError('Something Went Wrong!');

    }


    public function deleteRole(Role $role,$id)
    {
       // return back()->withErrors('Delete is In Progress!');


        $roleDelete = $role->findOrFail($id);
        if ($roleDelete->delete()) {
            return back()->withSuccess('Successfully Deleted');
        }
        return back()->withError('Something Went Wrong!');

    }


}
