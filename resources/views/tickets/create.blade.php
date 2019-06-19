@extends('layouts.master')
@section('title', $viewType)


@section('style')

@endsection

@section('content')
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">New Complain
                    <span class="pull-right"><a href="{{route('ticket-list')}}">
                            <i class="fa fa-backward"></i> Back To List
                        </a>
                    </span>
                </div>

                <div class="panel-body">
                    {{--@include('includes.flash')--}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        {!! Form::open(['route' => ['ticket-create'],'method' => 'POST','class' => 'form-horizontal']) !!}

                        <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-1 control-label">Subject</label>

                            <div class="col-md-3">

                                {!! Form::text('subject',null,['class' => 'form-control','placeholder'=>'Type Complain Subject']) !!}

                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            {!! Form::label('category_id','Category',['class' => 'col-md-1 control-label']) !!}
                            <div class="col-md-3">

                                {!! Form::select('category_id', $categories, null, ['class' => 'form-control', 'required' => 'required']) !!}

                                @if ($errors->has('category_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
                            <label for="priority" class="col-md-1 control-label">Priority</label>

                            <div class="col-md-3">
                                <select id="priority" type="" class="form-control" name="priority">
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>

                                @if ($errors->has('priority'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('priority') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            {!! Form::label('content',null,['class'=>'col-md-1 control-label']) !!}

                            <div class="col-md-11">
                                {{--<textarea rows="10" id="message" class="form-control" name="content"></textarea>--}}

                                {!! Form::textarea('content',null,['class'=>'form-control my-editor']) !!}

                                @if ($errors->has('content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    <div class="clearfix"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-ticket"></i> Open Complain
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


@endsection

@section('script')
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);
            $('#parent_id').select2();
        })

    </script>

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>


    {!! Html::script('/tinymce/tinymce.min.js') !!}
    {!! Html::style('/css/skin.min.css') !!}

    <script>
        jQuery(document).ready(function() {

            jQuery(function() {
                jQuery('#datetimepicker1').datetimepicker( {
                    defaultDate:'now',  // defaults to today
                    format: 'YYYY-MM-DD hh:mm:ss',  // YEAR-MONTH-DAY hour:minute:seconds
                    minDate:new Date()  // Disable previous dates, minimum is todays date
                });
            });
        });
    </script>

    <script>
        console.log(" {{ url('/') }}" ) ;
        var editor_config = {
            //path_absolute : "/",
            path_absolute:"{{ url('/') }}/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
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

