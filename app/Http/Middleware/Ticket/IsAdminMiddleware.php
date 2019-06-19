<?php

namespace App\Http\Middleware\Ticket;

use App\Models\Ticket\Agent;
use Closure;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { if (Agent::isAdmin()) {
        return $next($request);
    }
        return \Response::make(view('noPermission', [
            'message' => 'The Ticket is not Accessable',
            'title' => 'Unauthorized Access'
        ]), 401);
    }
}
