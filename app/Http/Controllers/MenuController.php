<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Route;
use Alert;

class MenuController extends Controller
{
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->middleware('auth');
        $this->menu = $menu;
    }

    public function index($parent_id = null)
    {
      /*  $routeCollection = Route::getRoutes();

        dd($routeCollection);

        foreach ($routeCollection as $value) {
            dd($value);
        }

        $value->getMethods();
        $value->getPath();
        $value->getName();
        $value->getActionName();*/


        $model = $this->menu;
        $menus = $this->menu->orderBy('id', 'DESC')->paginate(10);
        $viewType = 'Menu List';
        if ($parent_id) {
            $menus = $this->menu->whereParentId($parent_id)->orderBy('id', 'DESC')->paginate(5000);
        }

        return view('menu.index', compact('menus', 'viewType', 'model'));
    }

    public function createMenuForm()
    {

        $viewType = 'Create Menu';
        $menu_list = $this->menu->whereRouteType('parent')->get();

        return view('menu.create', compact('viewType', 'menu_list'));
    }


    public function getPositionListByParent(Request $request)
    {
        $parent_id = $request->parent_id;
        $list=Menu::whereParentId($parent_id)->pluck('position')->all();


        return response()->json($list);
    }



    public function saveMenu(Request $req)
    {
      $this->validate($req, [
            'icon_name' => 'required',
            'menu_name' => 'required',
            'route_name' => 'required|unique:menus',
            'position' => 'required|unique:menus,position,'. $req->get('parent_id').',parent_id',
        ]);

        $this->menu->menu_name = $req->get('menu_name');
        $this->menu->module_name = $req->get('module_name');
        $this->menu->icon_name = $req->get('icon_name');
        $this->menu->route_name = $req->get('route_name');
        $this->menu->route_type = $req->get('route_type');
        $this->menu->parent_id = $req->get('parent_id');
        $this->menu->is_displayable = $req->get('is_displayable');
        $this->menu->status = $req->get('status');
        $this->menu->is_common_access = $req->get('is_common_access');

        if ($this->menu->save()) {
            Alert::success('Successfully Created The Menu!', 'Success Message')->persistent("Ok");
            return redirect()->route('menu-list');
        } else {
            Alert::error('Something Went Wrong!', 'Error Message')->persistent("Ok");
            return back();
        }
    }

    public function editMenuForm($id)
    {
        // dd('jjj');
        //  dd( Route::has('truck-monitor-date-wise-entry-monitor-view'));

        $viewType = 'Edit Menu';
        $menu_list = $this->menu->whereRouteType('parent')->get();

        $theMenu = $this->menu->findOrFail($id);

        return view('menu.edit', compact('theMenu', 'viewType', 'menu_list'));

    }




    public function updateMenu($id, Request $req)
    {
        $this->validate($req, [
            'icon_name' => 'required',
            'menu_name' => 'required',
            'route_name' => 'required|unique:menus,route_name,' . $id,
            'position' => 'required|unique:menus,position,'.$id.',id,parent_id,'.$req->get('parent_id'),

        ]);

         //  dd($req->get('position'));


        $menuToEdit = $this->menu->findOrFail($id);

        $menuToEdit->menu_name = $req->get('menu_name');
        $menuToEdit->module_name = $req->get('module_name');
        $menuToEdit->icon_name = $req->get('icon_name');
        $menuToEdit->route_name = $req->get('route_name');
        $menuToEdit->route_type = $req->get('route_type');
        $menuToEdit->parent_id = $req->get('parent_id');
        $menuToEdit->is_displayable = $req->get('is_displayable');
        $menuToEdit->position = $req->get('position');
        $menuToEdit->status = $req->get('status');
        $menuToEdit->is_common_access = $req->get('is_common_access');


        if ($menuToEdit->save()) {
            //Alert::success('Successfully Updated The Menu!', 'Success Message')->persistent("Ok");
            return redirect()->route('menu-list')->withSuccess('Successfully Updated!');
        } else {
           // Alert::error('Something Went Wrong!', 'Error Message')->persistent("Ok");
            return back();
        }
    }

    public function deleteMenu($id)
    {
        $menuToDelete = $this->menu->findOrFail($id);
        if ($menuToDelete->delete()) {
            return back()->withSuccess('Successfully Deleted');
        }
    }


    public function searchMenu(Request $request)
    {
        $route_name = $request->route_name;

//        $menus = $this->menu->where('route_name', $route_name)->get();
        $menus = $this->menu->where('route_name', 'LIKE', '%' . $route_name . '%')->get();


        return view('menu.menu-search', compact('menus'));
    }

}
