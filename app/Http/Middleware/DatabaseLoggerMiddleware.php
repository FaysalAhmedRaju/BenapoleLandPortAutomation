<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Log;

class DatabaseLoggerMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next) {
        DB::connection()->enableQueryLog();
        //  dd('dd');
        return $next($request);
    }

    public function terminate($request, $response) {
        $queries = DB::getQueryLog();

        // dd($queries);
        //$user= DB::table("users")->find(Auth::user()->id);

        //  Log::info(print_r($user, true));

        //Log::info('Truck Entry: From :'.Auth::user()->id);


        $id = Auth::check()?Auth::id():null;
        collect($queries)->each(function ($query) use ($id) {
           // dd($query);
              //DB::table("log_tables")->insert(["loggable_id"=>$id,"message"=>$query['query']]);
        });
    }
}
