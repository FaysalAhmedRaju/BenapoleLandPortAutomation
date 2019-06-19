@extends('layouts.master')

@section('title', $viewType)

@section('style')

    {!! Html::style('css/styles.css') !!}
    {!! Html::style('css/select2.min.css') !!}
    {!! Html::style('/css/jquery.growl.css') !!}

@endsection


@section('content')

    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success">
                <i class="fa fa-info-circle"></i> {{  session()->get('success') }}
            </div>
        @endif
    </div>
    @include('tickets.nav')
    <div class="col-md-12">

        <div>
            {{--<form class="form-inline" onkeypress="return event.keyCode != 13;">--}}
                <div class="form-group">

                    {!! Form::open(['route' => ['ticket-search-with-module'],'method' => 'POST','class' => 'form-inline']) !!}
                    <label for="">Complain Subject:</label>
                    {!! Form::text('ticket_subject',null,['placeholder'=>'Search By Name','class'=>'form-control','id'=>'ticket_search']) !!}

                    {{--{!! Form::select('roles',$roles,null,['class'=>'form-control']) !!}--}}


                    <input class="btn btn-primary" id="menu-search-btn" readonly="readonly" type="submit"
                           value="Search">

                    {!! Form::close() !!}
                </div>
            {{--</form>--}}
        </div>

        <div id="menu-search-div">

        </div>
    </div>

    <div class="col-md-12 table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th colspan="3">
                    <a href="{!! route('ticket-create-form') !!}"><i class="fa fa-plus"></i> Create New</a>
                </th>
                <th colspan="7">

                </th>

            </tr>
            <tr>
                <th>S/L</th>
                <th>Subject</th>
                <th>Module</th>
                <th>Status</th>
                <th>Last Updated</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Complainer</th>
                {{--<th class="text-center th_width_80">action</th>--}}

            </tr>
            </thead>
            <tbody>


            @foreach($tickets as $k=> $ticket)


                <tr>
                    <td>{{++$k}}</td>
                    <td>
                        <a href="{{route('ticket-details',[$ticket->id])}}">
                            {{ $ticket->subject ? $ticket->subject : 'Not Avaliable' }}
                        </a>
                    </td>

                    <td>
                        @if($ticket->user)
                            {{$ticket->user->role->name}}
                        @endif
                    </td>

                    <td>
                         <span style="color:@if($ticket->status=='Solved') green @elseif($ticket->status=='Open') #e69900 @endif ">
                        {{ $ticket->status or 'Not Available'}}
                        </span>
                    </td>
                    <td class="text-capitalize">{{ $ticket->updated_at->diffForHumans() }}</td>
                    <td class="text-capitalize">
                          <span style="color:@if($ticket->priority=='Low') green @elseif($ticket->priority=='Medium') #e69900 @elseif($ticket->priority=='High') red @endif ">

                        {{ $ticket->priority or 'Not Available'}}
                        </span>
                    </td>
                    <td>
                        @if($ticket->category)
                            {{$ticket->category->name }}
                        @else
                            No
                        @endif
                    </td>
                    <td>
                        @if($ticket->user)
                            {{ucwords($ticket->user->name) }}
                        @else
                            No
                        @endif
                    </td>


                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$tickets->render() ) !!}
        </div>

    </div>






@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}
    {!! Html::script('js/typeahead.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#ticket_search').on('keyup', function (e) {
                console.log('okk')
                if (e.which == 13) {
                    $('#main_search_form').submit();
                }
            });
            $.get("/ticket-search-with-module", function (data) {
                $("#ticket_search").typeahead({
                    "items": "all", // Number of Items
                    "source": data,
                    "autoSelect": false,
                    displayText: function (item) {
                        console.log('returning item: ' + item.task_title);
                        return item.task_title;
                    },

                    updater: function (item) {
                        // http://laratubedemo.test/admin/videos/search?video_search=Code+Geass+Op1
                        //  window.location.href = '{{ route('ticket-search-with-module') }}?task_search=' + item.task_title.split(' ').join('+') ;
                    }

                });
            }, 'json');
        });
    </script>


@endsection