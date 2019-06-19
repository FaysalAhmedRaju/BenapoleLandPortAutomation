<div class="panel panel-default">
    <div class="panel-body">
        <ul class="nav nav-pills">
            <li role="presentation" class="{{setActive(route('ticket-list'))}}">
                <a href="{{route('ticket-list')}}">Active
                    <span class="badge">
                       @if(\App\Models\Ticket\Agent::isAdmin())
                            {{ App\Models\Ticket\Ticket::active()->count()}}
                        @else
                            {{ App\Models\Ticket\Ticket::userTickets(Auth::user()->id)->active()->count()}}
                       @endif
                    </span>
                </a>
            </li>
            <li role="presentation" class="{{setActive(route('completed-ticket-list'))}}">
                <a href="{{route('completed-ticket-list')}}">Completed
                    <span class="badge">
                         @if(\App\Models\Ticket\Agent::isAdmin())
                            {{ App\Models\Ticket\Ticket::complete()->count()}}
                        {{--@elseif ($u->isAgent()) {
                        {{ App\Models\Ticket\Ticket::agentUserTickets(Auth::user()->id)->count()}}--}}
                        @else
                            {{ App\Models\Ticket\Ticket::userTickets(Auth::user()->id)->complete()->count()}}

                        @endif
                    </span>
                </a>
            </li>


            <li role="presentation" class="{{setActive('tickets/list')}}">
                <a href=" ">Dashboard</a>
            </li>


        </ul>
    </div>
</div>
