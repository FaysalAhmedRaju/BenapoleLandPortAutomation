@extends('layouts.master')

@section('title', $viewTitle)

@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}

    {{--{!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->--}}

@endsection

@section('content')

    <div class="col-md-12">


        @if (count($errors) > 0)
            <div class="alert alert-danger successOrErrorMsgDiv">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success successOrErrorMsgDiv">
                {{ session()->get('success') }}
            </div>
        @endif


        <div class="col-lg-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info"></i> Manifest Edit Form
                    <a href="{{route('maintenance-manifest-manifest-details',[$theManifest->id])}}"
                       style="float: right;text-decoration: none">
                        <span><i class="fa fa-database"></i></span>
                        <span> Back To Manifest Details</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">
                            <form role="form" method="POST"
                                  action="{{route('maintenance-manifest-update',$theManifest->id)}}"
                                  enctype="multipart/form-data">

                                {{csrf_field()}}
                                <div class="form-group">

                                    <div class="col-sm-4">
                                        <label>Manifest No.:</label>
                                        <input type="text" value="{{$theManifest->manifest}}" name="manifest"
                                               title="Manifest No."
                                               class="form-control input-sm" placeholder="Type Manifest No."/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Gross Weight:</label>
                                        <input type="text" value="{{$theManifest->gweight}}" name="gweight"
                                               title="Gross Weight"
                                               class="form-control input-sm" placeholder="Type Gross Weight"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Manifest Date: </label>
                                        <input type="text" title="manifest_date" value="{{$theManifest->manifest_date}}"
                                               name="manifest_date" class="form-control input-sm datePicker"
                                               placeholder="Select manifest Date"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Package No: </label>
                                        <input type="text" title="package_no" value="{{$theManifest->package_no}}"
                                               name="package_no" class="form-control input-sm"
                                               placeholder="Type Package No"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Goods: </label>
                                        <input type="text" title="goods_id" value="{{$theManifest->goods_id}}"
                                               name="goods_id" class="form-control input-sm"
                                               placeholder="Select Goods Id"/>
                                        <p style="font-size: 10px;color: green">
                                            @if($theManifest->goods)
                                                ({{$theManifest->goods->cargo_name}})
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Importer: </label>
                                        <input type="text" title="vatreg_id" value="{{$theManifest->vatreg_id}}"
                                               name="vatreg_id" class="form-control input-sm"
                                               placeholder="Select vatreg_id"/>
                                        <p style="font-size: 10px;color: green">
                                            @if($theManifest->importer)
                                                ( {{$theManifest->importer->NAME}})
                                            @endif
                                        </p>

                                    </div>


                                    <div class="col-sm-4">
                                        <label>Posted Yard Shed: </label>
                                        <select title="" style="" class="form-control input-sm" name="posted_yard_shed">
                                            @foreach($yards as $k=>$v)
                                                <option @if($theManifest->posted_yard_shed==$v->id) selected
                                                        @endif value="{{$v->id}}">{{$v->shed_yard}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Created At: <span class="error">*</span></label>
                                        <input type="text" value="{{$theManifest->created_at}}" title="created_at"
                                               name="created_at"
                                               class="form-control input-sm datetime_picker"
                                               placeholder="Select Created At"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Marks No: <span class="error">*</span></label>
                                        <input type="text" title="marks_no" name="marks_no"
                                               value="{{$theManifest->marks_no}}"
                                               class="form-control input-sm" placeholder="Type marks_no"/>
                                    </div>


                                </div>

                                <div class="clearfix"></div>


                                <div class="form-group">

                                    <div class="col-sm-6 col-sm-offset-2 col-md-4 col-md-offset-4 text-center">
                                        <br><br>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>





@endsection
@section('script')

    {{--    {!!Html :: script('js/bootstrap-select.min.js')!!} <!--3.3.7-->--}}

    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}

    <script type="text/javascript">

        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);


            $('.datetime_picker').datetimepicker({
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });

        });


    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection