<?php

function displayableMenus($model = null, $id_parent = 0, $display = true, $status = true)
{
    //$port_Id = Session::get('PORT_ID'); //Auth::user()->port_id;
    $role_id = request()->user()->role()->first()->id;

    $menusForGroup = request()->user()->role()->first()->groupAccess()->get();
   // dd($menusForGroup);
    $displayedMenus = $model->whereParentId($id_parent)
        ->whereIsDisplayable($display)
        ->whereStatus($status)
        ->orderBy('position','ASC')
        ->get();
   //dd($displayedMenus);
    $menus = [];
    $accessmenus = [];
    foreach ($menusForGroup as $mgssss) {
        if (($mgssss->role_id == (int)$role_id) && ($mgssss->view == 1)) {
//        if (($mgssss->role_id == (int)$role_id) && ($mgssss->view == 1) && ($mgssss->port_id == (int)$port_Id)) {
            $accessmenus[] = $mgssss->menu_id;
        }
    }
    foreach ($displayedMenus as $dm) {
        if (in_array($dm->id, $accessmenus)) {
            $menus[] = $dm;
        }
        if ($dm->is_common_access) {
            $menus[] = $dm;
        }
    }
    return $menus;
}


function isAccessable($model = null, $roleId = null, $menuId = null/*, $port_id = 1*/)
{

    $port_id = Auth::user()->port_id;

    $isAccessable = $model->whereRoleId($roleId)->whereMenuId($menuId)->first();

    return $isAccessable;
}


function getRoleDashboardRoute(){
    $result=null;
    $role=(new \App\Role())->findOrFail(Auth::user()->role_id);
    if ($role->dashboard_route){
        $menu=(new \App\Menu())->findOrFail($role->dashboard_route);
        if ($menu){
            $result= $menu->route_name;
        }
    }

    return $result;

}

/**
 * Return nav-here if current path begins with this path.
 *
 * @param string $path
 * @return string
 */
function setActive($path)
{
   // dd(Request::fullUrl());
   // dd($path);
    //dd(Request::is($path));
    //$currentRoute =  Request::route()->getName();
    return Request::fullUrl()== $path ? 'active' :  '';
//    return Request::is($path . '*') ? 'active' :  '';
}



function generateSideBar()
{


        if (!file_exists(base_path("resources/views/layouts/sidebars/". Auth::user()->role_id . '.blade.php'))) {

            $menus = new \App\Menu();
            $displayedMenus = displayableMenus($menus);
            $html = '<ul class="sidebar-menu"> <li class="header">Dashboard</li>';
            $hash = "#";
            $log = new Log();


            foreach ($displayedMenus as $K => $displayedMenu) {
                $displayedChildMenus = displayableMenus($menus, $displayedMenu->id);

                if (isset($displayedChildMenus) && count($displayedChildMenus) > 0) {
                    $html .= '<li class="parent_menu_class treeview">';
                } else {
                    $html .= '<li class="">';
                }
                if (Route::has($displayedMenu->route_name)) {
                    $html .= '<a href="' . route($displayedMenu->route_name) . '" >';
                } else {
                    $html .= '<a href="#">';
                }
                $html .= '<i class="';
                if (isset($displayedMenu->icon_name) && !empty($displayedMenu->icon_name)) {
                    $html .=$displayedMenu->icon_name;
                } else {
                    $html .= 'fa fa-users';
                }
                $html .= '"></i><span>' . $displayedMenu->menu_name . '</span>';
                if (isset($displayedChildMenus) && count($displayedChildMenus) > 0) {
                    $html .= '<i class="fa fa-angle-left pull-right"></i>';
                }
                $html .= '</a>';

                if (isset($displayedChildMenus) && count($displayedChildMenus) > 0) {
                    $html .= '<ul class="treeview-menu" >';
                    foreach ($displayedChildMenus as $displayedchildMenu) {
                        $displayedSubChildMenus = displayableMenus($menus, $displayedchildMenu->id);

                        if (isset($displayedSubChildMenus) && count($displayedSubChildMenus) > 0) {
                            $html .= '<li class="parent_menu_class treeview">';
                            $html .= '<a href="#">';

                            $html .= '<i class="fa fa-users"></i><span>' . $displayedchildMenu->menu_name . '</span>';
                            if (isset($displayedSubChildMenus) && count($displayedSubChildMenus) > 0) {
                                $html .= '<i class="fa fa-angle-left pull-right"></i>';
                            }
                            $html .= '</a>';
                            $html .= '<ul class="treeview-menu">';
                            foreach ($displayedSubChildMenus as $displayedSubChildMenu) {
                                $html .= '<li class="child_menu_class" >';
                                if (Route::has($displayedSubChildMenu->route_name)) {
                                    $html .= '<a href="' . route($displayedSubChildMenu->route_name) . '" >';
                                } else {
                                    $html .= '<a href="#">';
                                }
                                $html .= '<i class="';
                                if (isset($displayedSubChildMenu->icon_name) && !empty($displayedSubChildMenu->icon_name)) {
                                    $html .=$displayedSubChildMenu->icon_name;
                                } else {
                                    $html .= 'fa fa-users';
                                }
                                $html .= '"></i >' . $displayedSubChildMenu->menu_name . '</a >';
                                $html .= '</li >';

                            }
                            $html .= '</ul>';
                            $html .= '</li>';
                        } else {
                            $html .= '<li class="child_menu_class">';
                            if (Route::has($displayedchildMenu->route_name)) {
                                $html .= '<a href="' . route($displayedchildMenu->route_name) . '" >';
                            } else {
                                $html .= '<a href="#">';
                            }
                            $html .= '<i class="';
                            if (isset($displayedchildMenu->icon_name) && !empty($displayedchildMenu->icon_name)) {
                                $html .=$displayedchildMenu->icon_name;
                            } else {
                                $html .= 'fa fa-users';
                            }
                            $html .= '"></i>' . $displayedchildMenu->menu_name;
                            $html .= '</a></li>';

                        }
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';

            file_put_contents(base_path("resources/views/layouts/sidebars/" .Auth::user()->role_id) . '.blade.php', $html);
        }


    return null;
}
