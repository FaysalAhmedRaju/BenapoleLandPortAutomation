<?php

namespace App\Http\Controllers\Ticket;

use App\Models\Ticket\Agent;
use App\Models\Ticket\Category;
use App\Models\Ticket\Ticket;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{

    protected $tickets;
    protected $agent;

    public function __construct(Ticket $tickets)
    {

        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\Ticket\AccessMiddleware', ['only' => ['show', 'complete']]);
        //  $this->middleware('App\Http\Middleware\Ticket\IsAgentMiddleware', ['only' => ['edit', 'update']]);
        $this->middleware('App\Http\Middleware\Ticket\IsAdminMiddleware', ['only' => ['destroy']]);

        $this->tickets = $tickets;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  $tickets = $this->tickets->active()->orderBy('updated_at', 'DESC')->toSql();
//dd($tickets);
        $viewType = 'Complain List';
         list($categories, $roles) = $this->getListFromArray();

        //dd($roles);

        if (Agent::isAdmin()) {
            $tickets = $this->tickets->active()->orderBy('updated_at', 'DESC')->paginate(10);
        } else {
            $tickets = $this->tickets->userTickets(Auth::user()->id)->active()->orderBy('updated_at', 'DESC')->paginate(10);
        }


        return view('tickets.index', compact('viewType', 'tickets','roles'));
    }

    public function indexComplete()//Ticket::complete()
    {
        $complete = true;
        $viewType = 'Completed Complain List';
        list($categories, $roles) = $this->getListFromArray();
        if (Agent::isAdmin()) {
            $tickets = $this->tickets->complete()->orderBy('completed_at', 'DESC')->paginate(10);
        } else {
            $tickets = $this->tickets->userTickets(Auth::user()->id)->complete()->orderBy('completed_at', 'DESC')->paginate(10);
        }


        return view('tickets.index', compact('viewType', 'tickets','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewType = 'Complain Create Form';
        $categories = Category::all();
        list($categories) = [$categories->pluck('name', 'id')];


        return view('tickets.create', compact('viewType', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'category_id' => 'required',
            'priority' => 'required',
            'content' => 'required'
        ]);

        $ticket = new Ticket([
            'subject' => $request->input('subject'),
            'user_id' => \Auth::user()->id,
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
            'content' => $request->input('content'),
            'status' => "Open",
            'created_at' => "",
            'upated_at' => "",
        ]);

        $ticket->save();

        //   $mailer->sendTicketInformation(Auth::user(), $ticket);

        return redirect()->route('ticket-list')->with("success", "A ticket with ID: #$ticket->id has been opened.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $viewType = 'Ticket Show';
        $ticket = $this->tickets->findOrFail($id);

        // dd(Agent::isTicketOwner($id));

        list($category_lists) = $this->getListFromArray();


        $replies = $ticket->replies()->paginate(10);

        return view('tickets.show', compact(
            'ticket', 'status_lists', 'priority_lists',
            'category_lists', 'replies', 'viewType'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $subject = $ticket->subject;
        $ticket->replies()->delete();
        $ticket->delete();




        return redirect()->route('ticket-list')
            ->withSuccess('Thicket #' . $subject . ' Successfully Deleted With All Replies');
    }

    public function complete($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $ticket->completed_at = Carbon::now();
        $subject = $ticket->subject;
        $ticket->status = 'Solved';
        $ticket->save();

        return redirect()->route('ticket-list')
            ->withSuccess('Thicket #' . $subject . ' Successfully Marked as Completed');
    }

    public function reopen($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $ticket->completed_at = null;
        $subject = $ticket->subject;
        $ticket->save();

        return redirect()->route('ticket-list')
            ->withSuccess('Thicket #' . $subject . ' Successfully Reopened');
    }

    //========================================custome function=======================

    public function ticketSearchWithModule(Request $request){
        $viewType = 'Searched Complain List';
        list($categories, $roles) = $this->getListFromArray();
        if ($request->roles){

            if (Agent::isAdmin()) {
                $tickets = $this->tickets->Where('subject', 'like', '%' . $request->ticket_subject . '%')->orderBy('updated_at', 'DESC')->paginate(10);
            } else {
                $tickets = $this->tickets->userTickets(Auth::user()->id)->orderBy('updated_at', 'DESC')->paginate(10);
            }

        }else{
            if (Agent::isAdmin()) {
                $tickets = $this->tickets->Where('subject', 'like', '%' . $request->ticket_subject . '%')->orderBy('updated_at', 'DESC')->paginate(10);
            } else {
                $tickets = $this->tickets->userTickets(Auth::user()->id)->orderBy('updated_at', 'DESC')->paginate(10);
            }
        }
        //redirect()->route('ticket-list')->with(['viewType','tickets','roles']);
        return view('tickets.index', compact('viewType', 'tickets','roles'));
    }



    protected function getListFromArray()
    {

        $categories = Category::all();
        $roles = Role::all();;

        return [$categories->pluck('name', 'id'),$roles->pluck('name', 'id')];
      //  return [$priorities->pluck('name', 'id'), $categories->pluck('name', 'id'), $statuses->pluck('name', 'id')];

    }
}
