<?php

namespace App\Http\Middleware\Ticket;

use App\Models\Ticket\Agent;
use Closure;
use Response;

class AccessMiddleware
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

        if (Agent::isAdmin()) {
            return $next($request);
        }
        //return $next($request);
        // All Agents have access in none restricted mode

            if (Agent::isAgent()) {
                return $next($request);
            }


        // if this is a new comment on a ticket
//            $ticket_id = $request->get('id');
            $ticket_id = $request->route()->parameter('id') | $request->get('ticket_id');

        //dd($ticket_id);
        // Assigned Agent has access in the restricted mode enabled
        if (Agent::isAgent() && Agent::isAssignedAgent($ticket_id)) {
            return $next($request);
        }

        // Ticket Owner has access
        if (Agent::isTicketOwner($ticket_id)) {
            return $next($request);
        }

        return Response::make(view('noPermission', [
            'message' => 'The Ticket is not Accessable',
            'title' => 'Unauthorized Access'
        ]), 401);

      /*  return view('noPermission',
            ['message' => 'The Ticket is not Accessable',
            'title' => 'Unauthorized Access']
        );*/
   /* return redirect()->action('\Kordy\Ticketit\Controllers\TicketsController@index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-access'));*/

    }
}
