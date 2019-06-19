<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class MyPortMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */

    //  const PORT_ALIAS=null;


    public function handle($request, Closure $next)
    {

        $currentRoute = $request->route()->getName();

      // $rr= Auth::user()->ports()->findOrFail(Session::get('PORT_ID'))->port_name;

     // dd($rr)  ;
//dd(Session::get('PORT_ID'));
        if (Auth::check()) {//if user logged in
            if (Auth::user()->ports()->first()) {//if user has por id

                if (!Session::get('PORT_ID')) {
                   if (Auth::user()->current_port){
                      // dd (Auth::user()->current_port);
                       session()->put('PORT_ID', Auth::user()->current_port);
                   }else{
                       session()->put('PORT_ID', Auth::user()->ports()->first()->id);
                   }

                } else {
                    // Session::put(SITE_ID,0);
                    //session()->put('PORT_ALIAS', 1);
                }

                if (!Session::get('PORT_ALIAS')) {
                    session()->put('PORT_ALIAS', Auth::user()->ports()->findOrFail(Session::get('PORT_ID'))->port_alias);

                } else {
                    // Session::put(PORT_ID,0);
                    //session()->put('PORT_ALIAS', 1);
                }
                if (!Session::get('PORT_NAME')) {
                    session()->put('PORT_NAME', Auth::user()->ports()->findOrFail(Session::get('PORT_ID'))->port_name);

                } else {
                    // Session::put(PORT_ID,0);
                    //session()->put('PORT_ALIAS', 1);
                }

                return $next($request);
            } else {

                if ($currentRoute == 'user-update'| $currentRoute == 'logout'  ) {
                    return $next($request);
                }
                return \Response::make(view('port-session'), 401);
            }

        }else {

            return $next($request);
//          \Redirect::route('/');
        }


    }
}
