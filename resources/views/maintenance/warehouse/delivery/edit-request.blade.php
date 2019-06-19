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
                    <i class="fa fa-info"></i> Delivery Req. Edit Form
                    <a href="{{route('maintenance-manifest-manifest-details',[$theManifest->id])}}" style="float: right;text-decoration: none">
                        <span><i class="fa fa-database"></i></span>
                        <span> Manifest Details</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">
                            <form role="form" method="POST" action="{{route('maintenance-warehouse-delivery-delivery-request-update',$theManifest->id)}}"
                                  enctype="multipart/form-data">

                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="hidden" value="{{$deliveryRequisitions->id}}" name="req_id" >
                                    <div class="col-sm-4">
                                        <label>B/E No. </label>
                                        <input type="text" value="{{$theManifest->be_no}}" name="be_no"  class="form-control input-sm"
                                               placeholder="B/E No." required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>B/E Date: </label>
                                        <input type="text" value="{{$theManifest->be_date}}" ng-model="be_date"  name="be_date"
                                               class="form-control datePicker input-sm" placeholder="B/E date" required>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>Custom Release Order No: <span class="error">*</span></label>
                                        <input type="text"  value="{{$theManifest->custom_release_order_no}}" name="custom_release_order_no" class="form-control input-sm" placeholder="Custom Release Order No" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Custom Release Order Date: <span class="error">*</span></label>
                                        <input type="text"  name="custom_release_order_date" class="form-control input-sm datePicker"
                                               value="{{$theManifest->custom_release_order_date}}" placeholder="Custom Release Order Date" required>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>AIN No:</label>
                                        <input type="text"  value="{{$theManifest->ain_no}}" name="ain_no" class="form-control input-sm" placeholder="AIN No" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>C&F Name:</label> <br>
                                        <input type="text" ng-model="cnf_name" value="{{$theManifest->cnf_name}}" name="cnf_name" class="form-control input-sm" placeholder="C&F Name" required>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>Packages No:</label> <br>
                                        <input type="number" value="{{$deliveryRequisitions->carpenter_packages}}"  name="carpenter_packages" class="form-control input-sm" placeholder="Packages No">
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Carpenter Repair Packages No:</label> <br>
                                        <input type="number" name="carpenter_repair_packages" class="form-control input-sm"
                                               value="{{$deliveryRequisitions->carpenter_repair_packages}}"  placeholder="Carpenter Repair Packages No">
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Delivery Date:</label> <br>
                                        <input type="text"  name="approximate_delivery_date" value="{{$deliveryRequisitions->approximate_delivery_date}}"
                                               class="form-control input-sm datePicker" placeholder="Delivery Date" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Loading Type:</label> <br>
                                        <select title=""  class="form-control input-sm" name="approximate_delivery_type" >
                                            <option value="0" @if($deliveryRequisitions->approximate_delivery_type==0) selected @endif>Labour</option>
                                            <option value="1" @if($deliveryRequisitions->approximate_delivery_type==1) selected @endif>Equipment</option>
                                            <option value="2" @if($deliveryRequisitions->approximate_delivery_type==2) selected @endif>Both</option>
                                            <option value="3" @if($deliveryRequisitions->approximate_delivery_type==3) selected @endif>Self</option>

                                        </select>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>Type Appx. Labour Weight:</label> <br>
                                        <input type="number"  name="approximate_labour_load" value="{{$deliveryRequisitions->approximate_labour_load}}"
                                               class="form-control input-sm" placeholder="Type Appx. Labour Weight"/>


                                    </div>
                                    <div class="col-sm-4">
                                        <label>Type Appx. Equipment Load:</label> <br>
                                        <input type="number"  name="approximate_equipment_load" value="{{$deliveryRequisitions->approximate_equipment_load}}"
                                               class="form-control input-sm" placeholder="Type Appx. Equipment Load"/>


                                    </div>
                                    <div class="col-sm-4">
                                        <label>Transport Type:</label> <br>
                                        <select title="" class="form-control input-sm" name="local_transport_type" >
                                            <option value="0"  @if($deliveryRequisitions->local_transport_type==0) selected @endif >Truck</option>
                                            <option value="1" @if($deliveryRequisitions->local_transport_type==1) selected @endif  >VAN</option>
                                            <option value="2" @if($deliveryRequisitions->local_transport_type==2) selected @endif >Self</option>
                                            <option value="3" @if($deliveryRequisitions->local_transport_type==3) selected @endif >Both</option>
                                        </select>

                                    </div>

                                    <div class="col-sm-4">
                                        <label>Transport Truck:</label> <br>
                                        <input type="number" name="transport_truck" value="{{$deliveryRequisitions->transport_truck}}"
                                               class="form-control input-sm" placeholder="Transport Truck" />

                                    </div>


                                    <div class="col-sm-4">
                                        <label>Transport VAN:</label> <br>
                                        <input type="number" value="{{$deliveryRequisitions->transport_van}}" name="transport_van" class="form-control input-sm" placeholder="Transport VAN" />


                                    </div>

                                    <div class="col-sm-4">
                                        <label>BD Weighment:</label> <br>
                                        <input type="number" value="{{$deliveryRequisitions->local_weighment}}" name="bd_weighment" class="form-control input-sm" placeholder="BD Weighment"/>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>Local Shifting:</label> <br>
                                        <label class="radio-inline">
                                            <input  type="radio" name="shifting_flag" @if($deliveryRequisitions->shifting_flag==1) checked @endif value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="shifting_flag"  @if($deliveryRequisitions->shifting_flag==0) checked @endif value="0">No
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Gate Pass No:</label> <br>
                                        <input type="text" name="gate_pass_no" value="{{$deliveryRequisitions->gate_pass_no}}"
                                               class="form-control input-sm" placeholder="Gate Pass No">
                                    </div>

                                    <div class="col-sm-4">
                                        <label>BD Haltage:</label> <br>
                                        <input type="text" name="local_haltage" value="{{$deliveryRequisitions->local_haltage}}"
                                               class="form-control input-sm" placeholder="Gate Pass No">
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

    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#parent_id').select2();
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