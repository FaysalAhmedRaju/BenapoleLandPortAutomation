@extends('layouts.master')

@section('title', $viewTitle)

@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

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
               <i class="fa fa-info"></i> Edit Shed Yard Weight
                    <a href="{{route('maintenance-manifest-list-view')}}" style="float: right;text-decoration: none">
                        <span><i class="fa fa-database"></i></span>
                        <span> Manifest List</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">
                            <form role="form" method="POST"  action="{{route('maintenance-shed-yard-weight-update',$shedYardWeight->id)}}" enctype="multipart/form-data">

                                {{csrf_field()}}
                                <div class="form-group">

                                    <div class="col-sm-4">
                                        <label>Labour Package: </label>
                                        <input type="text" title="Labour Package" value="{{$shedYardWeight->unload_labor_package}}" name="unload_labor_package" class="form-control input-sm" placeholder="Type Labour Package" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Labour Weight: </label>
                                        <input type="text" title="Labour Weight" name="unload_labor_weight" value="{{$shedYardWeight->unload_labor_weight}}" class="form-control input-sm" placeholder="Type Labour Weight" />
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Equ Package: <span class="error">*</span></label>
                                        <input type="text" value="{{$shedYardWeight->unload_equipment_package}}" title="Equ Package" name="unload_equipment_package"
                                               class="form-control input-sm" placeholder="Type Equ Package"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Equ Weight: <span class="error">*</span></label>
                                        <input type="text" title="Equ Weight" name="unload_equip_weight" value="{{$shedYardWeight->unload_equip_weight}}"
                                               class="form-control input-sm" placeholder="Type Equ Weight"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Equ Name: <span class="error">*</span></label>
                                        <input type="text" title="Equ Name" name="unload_equip_name" value="{{$shedYardWeight->unload_equip_name}}"
                                               class="form-control input-sm" placeholder="Equ Name"/>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>Received At:</label>
                                        <input type="text"  value="{{$shedYardWeight->unload_receive_datetime}}" name="unload_receive_datetime" title="Division Name" id="unload_receive_datetime" maxlength="190"
                                               class="form-control input-sm" placeholder="Type Division Name"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Unloaded Shed Yard:</label>
                                        <select class="form-control" title="Unloaded Shed Yard" name="unload_yard_shed" id="">
                                            @if($yardShedList)
                                                <option value="0">Select Yard Shed</option>
                                                @foreach($yardShedList as $k=>$yardShed)
                                                    <option {{$yardShed->id == $shedYardWeight->unload_yard_shed ?'selected':''}} value="{{$yardShed->id}}">{{$yardShed->yard_shed_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>

                                </div>

                                <div class="clearfix"></div>


                                <div class="form-group">

                                    <div class="col-sm-6 col-sm-offset-2 col-md-4 col-md-offset-4 text-center">
                                        <br><br>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa fa-save"></i>   Update
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

    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#parent_id').select2();
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);


            $('#unload_receive_datetime').datetimepicker({
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });

        });


    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection