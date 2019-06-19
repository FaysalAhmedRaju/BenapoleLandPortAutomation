<?php

namespace App\Http\Controllers\Ticket;

use App\Models\Ticket\Reply;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReplyController extends Controller
{

    public function __construct(Ticket $tickets)
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\Ticket\IsAdminMiddleware', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('App\Http\Middleware\Ticket\AccessMiddleware', ['only' => 'store']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_id'   => 'required|exists:tickets,id',
            'content'     => 'required|min:6',
        ]);

        $comment = new Reply();

        $comment->ticket_id = $request->get('ticket_id');
        $comment->content = $request->get('content');
        $comment->user_id = \Auth::user()->id;
        $comment->save();

        $ticket = Ticket::findOrFail($request->get('ticket_id'));

       // dd($comment->created_at);
        $ticket->updated_at =  Carbon::now();//$comment->created_at;
        $ticket->save();

        return back()->withSuccess('Your Reply Successfully Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
