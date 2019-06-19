<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use Session;
use Cache;
use Response;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $username = 'username';
    protected $redirectTo = '/dashboard';
    protected $guard = 'web';


    public function getLogin()
    {

        // dd(Auth::guard('web')->check());

        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return view('home.index');

    }


    /**
     *
     * @param Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $req)
    {

        $auth = Auth::guard('web')->attempt(['username' => $req->username, 'password' => $req->password, 'user_status' => 1]);
       // dd(Auth::user()->role_id);return;



        if ($auth) {
          //  $user = User::where('username', $req->username)->first();
            $role = Role::findOrFail(Auth::user()->role_id);

            if(Auth::user()->port_employee_id != null || Auth::user()->cnf_employee_id != null || Auth::user()->custom_employee_id != null){
                $userRoleName = $role->name;

                //dd($role->menu->route_name);

                if (\Route::has($role->menu->route_name)){
                    return redirect()->route($role->menu->route_name);
                }
                return view('noPermission', [
                    'message' => 'No Dashboard Link Found',
                    'title' => 'No Dashboard Link Found'
                ]);
            }else{
                Session::flash('loginFail', "Your Can Not Login Without Details Information, Please Contact With Admin.");
                return back();
            }



/*
            if ($userRoleName == 'Admin') {
                //return redirect()->route('dashboard');
                return redirect()->route('admin-welcome-view');
            } elseif ($userRoleName == 'Truck') {

                return redirect()->route('truck-welcome-view');

            } elseif ($userRoleName == 'WeighBridge') {

                return redirect()->route('weighbridge-welcome-view');

            } elseif ($userRoleName == 'Posting') {

                return redirect()->route('posting-branch-welcome-view');

            } elseif ($userRoleName == 'WareHouse') {

                return redirect()->route('wareHouse-welcome-view');

            } elseif ($userRoleName == 'C&F') {

                return redirect()->route('c&f-welcome-view');

            } elseif ($userRoleName == 'Assessment') {

                return redirect()->route('assessment-welcome-view');

            } elseif ($userRoleName == 'Bank') {

                return redirect()->route('bank-welcome-view');

            } elseif ($userRoleName == 'GateOut') {
                return redirect()->route('gateout-welcome-view');
            } elseif ($userRoleName == 'Passport') {

                return redirect()->route('passport-welcome-view');

            } elseif ($userRoleName == 'TransShipment') {
                return redirect()->route('transshipment-welcome-view');
            } elseif ($userRoleName == 'Accounts') {
                return redirect()->route('accounts-welcome-view');
            } elseif ($userRoleName == 'Customs') {
                return redirect()->route('customs-welcome-view');
            } elseif ($userRoleName == 'Assessment Admin') {
                return redirect()->route('assessment-admin-welcome-view');
            } elseif ($userRoleName == 'Export') {
                return redirect()->route('export-truck-welcome-view');

            } elseif ($userRoleName == 'Bus') {
                return redirect()->route('export-bus-welcome-view');

            } elseif ($userRoleName == 'TransShipment Assessment Admin') {
                return redirect()->route('trans-assessment-admin-welcome-view');
            } elseif ($userRoleName == 'Super Admin') {
                return redirect()->route('super-admin-welcome-view');
            } elseif ($userRoleName == 'Export Admin') {
                return redirect()->route('export-admin-welcome-view');
            } elseif ($userRoleName == 'Maintenance') {
                return redirect()->route('maintenance-welcome-view');
            }
            return redirect()->route('/');*/
        } else {//username or passwor or not active user
            $user = User::where('username', $req->username)->first();

            if ($user) {//user name matched
                if (\Hash::check($req->password,  $user->password)) {//password also correct

                    Session::flash('loginFail', "You are not active user");
                    return back();
                }else{
                    Session::flash('loginFail', "Your password is Wrong!");
                    return back();
                }
            }
            Session::flash('loginFail', "You are not registered!!");
            return back();
        }


    }


    public function getLogout()
    {
        Cache::forget('user-online-' . Auth::user()->id);
        Session::forget('PORT_ALIAS');
        Session::forget('PORT_ID');
        Session::forget('PORT_NAME');
        Auth::guard('web')->logout();
        return redirect()->route('/');

    }


    public function noPermission()
    {
        return view('noPermission');
    }


}
