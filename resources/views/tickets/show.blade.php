@extends('layouts.master')

@section('title', $viewType)

@section('style')


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



    <div class="col-md-12 table-responsive">
        @include('tickets.nav')
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="content">
                    <h2 class="header">
                        {{ $ticket->subject }}

                        <span class="pull-right">
                    @if(! $ticket->completed_at)
                                <a href="{{route('ticket-complete',[$ticket->id])}}"
                                   class="btn btn-success">Complete</a>
                            @elseif($ticket->completed_at)
                                <a href="{{route('ticket-reopen',[$ticket->id])}}"
                                   class="btn btn-success">Reopen</a>
                            @endif
                            @if(\App\Models\Ticket\Agent::isAgent() || \App\Models\Ticket\Agent::isAdmin())
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#ticket-edit-modal">
                            Edit
                        </button>
                            @endif
                            @if(\App\Models\Ticket\Agent::isAdmin())

                                <a href="{{route('ticket-delete',[$ticket->id])}}"
                                   onclick="return confirm('Are you sure?')"
                                   class="btn btn-warning">
                              Delete
                            </a>

                            @endif
                </span>
                    </h2>
                    <div class="clearfix"></div>
                    <div class="panel well well-sm">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <p><strong>Complainer: </strong>{{  $ticket->user->name }}</p>
                                    <p>
                                        <strong>Status: </strong>
                                        @if( $ticket->isComplete())
                                            <span style="color: blue">Complete</span>
                                        @else
                                            <span style="color: #15a000">{{ $ticket->status }}</span>
                                        @endif

                                    </p>
                                    <p>
                                        <strong>Priority: </strong>
                                        <span style="color: #15a000;">
                                    {{ $ticket->priority }}
                                </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong>Category: </strong>
                                        <span style="color: #15a000">
                                    {{ $ticket->category->name }}
                                </span>
                                    </p>
                                    <p><strong>Created: </strong>{{ $ticket->created_at->diffForHumans() }}</p>
                                    <p><strong>last Update: </strong> {{ $ticket->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! $ticket->content !!}
                    </div>
                </div>
                {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['ticket-delete',$ticket->id]
                                ])
                !!}
                {!! Form::close() !!}
            </div>
        </div>

        <br>
        <h2>Replies</h2>
        @if(!$replies->isEmpty())
            @foreach($replies as $reply)
                <div class="panel {!! $reply->user_id ==Auth::user()->id ? "panel-info" : "panel-danger" !!}">
                    <div class="panel-heading" style="min-height: 36px">

                        <img src="{{$reply->user->photo==null?'/img/noImg.jpg':'/'.$reply->user->photo}}" height="20"
                             width="20"
                             class="img-circle {!! $reply->user_id ==Auth::user()->id ? "pull-left" : "pull-right" !!}"
                             alt="User Image">

                        <h3 class="panel-title">
                            <span class="{!! $reply->user_id ==Auth::user()->id ? "pull-left" : "pull-right" !!}">
                            {!! $reply->user->name !!}
                            </span>
                            <span class="{!! $reply->user_id ==Auth::user()->id ? "pull-right" : "pull-left" !!}">
                                {!! $reply->created_at->diffForHumans() !!}
                            </span>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <p> {!! $reply->content !!} </p>
                        </div>
                    </div>

                    <div class="panel-footer">

                    </div>
                </div>
            @endforeach
        @endif

        @if(! $ticket->completed_at)
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['method' => 'POST', 'route' => ['reply-create'], 'class' => 'form-horizontal']) !!}
                    {!!  Form::hidden('ticket_id', $ticket->id ) !!}
                    <fieldset>
                        <legend>Reply:</legend>
                        <div class="form-group">
                            <div class="col-lg-12">
                                {!!  Form::textarea('content', null, ['class' => 'form-control my-editor', 'rows' => "3"]) !!}
                            </div>
                        </div>

                        <div class="text-right col-md-12">
                            {!!  Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                        </div>

                    </fieldset>
                    {!!  Form::close() !!}
                </div>
            </div>
        @endif

    </div>

@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#menu-search-btno').click(function () {
                var routeName = $('#route_name').val();
                console.log(routeName == '');

                if (routeName == '') {
                    $.growl.warning({title: "Warning!", message: 'Please Input Value First'});
                    return false;
                }

                $.ajax({
                    url: '{{ route("menu-search") }}',
                    data: {
                        'route_name': routeName
                    },
                    type: "GET", // not POST, laravel won't allow it
                    success: function (data) {
//                        alert(data);
                        $data = $(data); // the HTML content your controller has produced

                        $('#menu-search-div').html($data);
                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status = 401) {
                            $.growl.error({message: data.statusText + " !"});
                        }
                        $.growl.error({message: data.statusText + " !"});

                    }
                });
            });
        });
    </script>


    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>


    {!! Html::script('/tinymce/tinymce.min.js') !!}
    {!! Html::style('/css/skin.min.css') !!}

    <script>
        console.log(" {{ url('/') }}");
        var editor_config = {
            //path_absolute : "/",
            path_absolute: "{{ url('/') }}/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback: function (field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                });
            },
            //  Add Bootstrap Image Responsive class for inserted images
            image_class_list: [
                {title: 'None', value: ''},
                {title: 'Bootstrap responsive image', value: 'img-responsive'},
            ]


        };

        tinymce.init(editor_config);
    </script>


@endsection