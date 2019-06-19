<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Auth;
use Cache;

class Activity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $expireAt = Carbon::now()->addMinutes(5);
            Cache::put('user-online-'.Auth::user()->id, true, $expireAt);
        }
        return $next($request);
    }
}
