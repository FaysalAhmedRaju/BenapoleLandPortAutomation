<?php

namespace App\Http\Middleware;

use App\Models\Manifest;
use App\Providers\RouteServiceProvider;
use Closure;
use App\Menu;
use App\GroupAccess;
use Illuminate\Contracts\Auth\Guard;
use Response;
use Auth;
use Route;

class GrantAccessMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    private $groupAccess;
    private $auth;
    private $menu;


    public function __construct(GroupAccess $groupAccess, Menu $menu, Guard $auth)
    {
        $this->groupAccess = $groupAccess;
        $this->menu = $menu;
        $this->auth = $auth;

    }

    private function getCommonMenus(Menu $menu)
    {
        return $menu->where('is_common_access', true)->get();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next) {

        $currentRoute = $request->route()->getName();
        $accessibleMenu = $this->menu->where('route_name', $currentRoute)->first();
       // dd($currentRoute);
        $routesForAll = $this->getCommonMenus($this->menu)->pluck('route_name')->toArray();

        if (is_null($accessibleMenu) && $this->auth->check()) {//no Auth check here as it is checked in every controller
            if($request->isJson()) {
              return response('The Route Not Inserted Yet!', 401);
            }

            return Response::make(view('noPermission', [
                'message' => 'The Route Not Inserted Yet',
                'title' => 'Route Not In DB'
            ]), 401);

        }

        if (!is_null($accessibleMenu) && in_array($accessibleMenu->route_name, $routesForAll)) {//no Auth check here as it is checked in every controller
            return $next($request);

        }


        if ($this->auth->check() && !is_null($accessibleMenu)) {

            $userRole = $request->user()->role()->first()->id;
            // dd($userRole);
            $groupAccess = $this->groupAccess->where(['menu_id' => $accessibleMenu->id, 'role_id' => $userRole])->first();

            if (!is_null($groupAccess) && $groupAccess->view) {

                return $next($request);
            }
        }
        if($request->isJson() || $accessibleMenu->route_type == 'api') {
            return response('The Route Is Not Assigned For You!', 401);
        }

        return Response::make(view('noPermission',
            [
                'message' => 'You are not authorized to see the page!',
                'title' => 'Have No Permission'
            ]), 401);
        //return view('noPermission');

    }
}
