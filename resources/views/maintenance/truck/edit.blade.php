@extends('layouts.master')

@section('title', $viewTitle)

@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}


    {{--{!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->--}}

@endsection



@section('script')


    <script type="text/javascript">
        var app = angular.module('testApp', []);
        app.controller('testController', function ($scope, $http, $filter) {
            $scope.saveTestData=function () {

                var data = {
                    manifest: $scope.manifest,
                    truck:$scope.truck
                };
                console.log(data)
                $http.post("/api/save-another-ip-data",data)
                        .then(function (data) {
                            console.log(data)

                        }).catch(function (r) {
                    console.log(r)


                }).finally(function () {


                })

                console.log($scope.manifest)

            }
        });
    </script>

@endsection
@section('content')


   {{-- <div class="col-md-12" ng-app="testApp" ng-controller="testController">
        <div class="form-group">
            <hr>
            <p class="ok" style="text-decoration: underline">Truck Entry Data:</p>


            <form action="">
                <div class="col-sm-4">
                    <input title="" type="text" ng-model="manifest" class="form-control">
                </div>

                <div class="col-sm-4">
                    <input title="" type="text" ng-model="truck" class="form-control">
                </div>


                <input value="Send" type="button" ng-click="saveTestData()">
            </form>


        </div>
    </div>--}}



    <div class="col-md-12">


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

        </div>

        <div class="col-lg-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info"></i> Truck Edit Form
                    <a href="{{route('maintenance-truck-details',[$theTruck->id])}}"
                       style="float: right;text-decoration: none">
                        <span><i class="fa fa-database"></i></span>
                        <span> Truck Details</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="panel-body">

                    <div class="col-md-12">
                        <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                    </div>

                    <div class="col-md-12" style="">
                        <form role="form" method="POST" action="{{route('maintenance-truck-update',$theTruck->id)}}"
                              enctype="multipart/form-data">

                            {{csrf_field()}}
                            <div class="form-group">
                                <hr>
                                <p class="ok" style="text-decoration: underline">Truck Entry Data:</p>

                                <div class="col-sm-4">
                                    <label>Vehicle Type: <span class="error">*</span></label>
                                    <select title="" name="vehicle_type_flag" class="form-control input-sm">
                                        <optgroup label="(1). Truck">
                                            <option @if($theTruck->vehicle_type_flag==1) selected
                                                    @endif   value="1">Goods
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==2) selected @endif  value="2">
                                                Chassis(on Truck)
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==3) selected @endif  value="3">
                                                Tractor(on Truck)
                                            </option>
                                        </optgroup>

                                        <optgroup label="(2). Self">
                                            <option @if($theTruck->vehicle_type_flag==11) selected
                                                    @endif value="11">Chassis(Self)
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==12) selected
                                                    @endif value="12">Trucktor(Self)
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==13) selected
                                                    @endif  value="13">Bus
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==14) selected
                                                    @endif  value="14">Three Wheller
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==15) selected
                                                    @endif  value="15">Rickshaw
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==16) selected
                                                    @endif value="16">Car(self)
                                            </option>
                                            <option @if($theTruck->vehicle_type_flag==17) selected
                                                    @endif value="17">Pick Up(self)
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label>Truck Type: </label>
                                    <input type="text" title="Truck Type" value="{{$theTruck->truck_type}}"
                                           name="truck_type" class="form-control input-sm"
                                           placeholder="Type Labour Package"/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Truck No: </label>
                                    <input type="text" title="Truck No" name="truck_no"
                                           value="{{$theTruck->truck_no}}" class="form-control input-sm"
                                           placeholder="Type Labour Weight"/>
                                </div>


                                <div class="col-sm-4">
                                    <label>Driver Name: <span class="error">*</span></label>
                                    <input type="text" value="{{$theTruck->driver_name}}" title="driver_name"
                                           name="driver_name"
                                           class="form-control input-sm" placeholder="Type driver_name"/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Driver Card: <span class="error">*</span></label>
                                    <input type="text" title="driver_card" name="driver_card"
                                           value="{{$theTruck->driver_card}}"
                                           class="form-control input-sm" placeholder="Type driver_card"/>
                                </div>


                                <div class="col-sm-4">
                                    <label>Entry At:</label>
                                    <input type="text" value="{{$theTruck->truckentry_datetime}}"
                                           name="truckentry_datetime" title="Entry At"
                                           class="form-control input-sm datetimepicker"
                                           placeholder="Select Entry Date"/>
                                </div>

                                <div class="col-sm-4">
                                    <label>Weightment Flag:</label> <br>
                                    <label class="radio-inline">
                                        <input type="radio" name="weightment_flag"
                                               @if($theTruck->weightment_flag==1) checked @endif value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="0" name="weightment_flag"
                                               @if($theTruck->weightment_flag==0) checked @endif>No
                                    </label>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="form-group">
                                <hr>
                                <p class="ok">Weighbridge Data:</p>

                                <div class="col-sm-4">
                                    <label>G. Weight:</label>
                                    <input type="text" value="{{$theTruck->gweight_wbridge}}" name="gweight_wbridge"
                                           title="gweight_wbridge"
                                           class="form-control input-sm" placeholder="Type Division Name"/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Tare Weight:</label>
                                    <input type="text" value="{{$theTruck->tr_weight}}" name="tr_weight"
                                           title="tr_weight"
                                           class="form-control input-sm" placeholder="Type Division Name"/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Net Weight:</label>
                                    <input type="text" value="{{$theTruck->tweight_wbridge}}" name="tweight_wbridge"
                                           title="tweight_wbridge"
                                           class="form-control input-sm" placeholder="Type tweight_wbridge"/>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            {{--  ==============================================     Truck Receive Data Start================================================--}}
                            <hr>
                            <p class="ok">Truck Receive Data:</p>

                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label>Recived Weight:</label>
                                    <input type="text" value="{{$theTruck->receive_weight}}" name="receive_weight"
                                           title="Receive Weight"
                                           class="form-control input-sm" placeholder="Type Division Name"/>
                                </div>


                                <div class="col-sm-4">
                                    <label>Receive Package:</label>
                                    <input type="text" value="{{$theTruck->receive_package}}" name="receive_package"
                                           title="Receive Package"
                                           class="form-control input-sm" placeholder="Receive Package"/>
                                </div>

                                <div class="col-sm-4">
                                    <label>Parking Charge:</label> <br>
                                    <label class="radio-inline">
                                        <input type="radio" name="$theTruck->holtage_charge_flag"
                                               @if($theTruck->holtage_charge_flag==1) checked @endif value="1">Paid
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="0" name="$theTruck->holtage_charge_flag"
                                               @if($theTruck->holtage_charge_flag==0) checked @endif>Unpaid
                                    </label>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>


                            @if(count($theShedYardData)>0)
                                @foreach($theShedYardData as $k=>$theData)


                                    <div class="form-group">

                                        <input type="hidden" name="shed_yard_weight[{{$k}}][id]"
                                               value="{{$theData->id}}">

                                        <div class="col-sm-4">
                                            <label>Received At:</label>
                                            <input type="text" value="{{$theData->unload_receive_datetime}}"
                                                   name="shed_yard_weight[{{$k}}][unload_receive_datetime]"
                                                   title="Received At"
                                                   class="form-control input-sm datetimepicker"
                                                   placeholder="Select Entry Date"/>

                                        </div>

                                        <div class="col-sm-4">
                                            <label>Received Yard:</label>
                                            <select title="" class="form-control"
                                                    name="shed_yard_weight[{{$k}}][unload_yard_shed]">
                                                <option value="0">Select Shed Yard</option>
                                                @foreach($yards as $i=>$v)
                                                    <option value="{{$v->id}}"
                                                            @if($v->id==$theData->unload_yard_shed ) selected
                                                            @endif>{{$v->yard_shed_name}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-sm-4">
                                            <label>Labor Package:</label>
                                            <input type="text" value="{{$theData->unload_labor_package}}"
                                                   name="shed_yard_weight[{{$k}}][unload_labor_package]"
                                                   title="Labor Package"
                                                   class="form-control input-sm" placeholder="Labor Package"/>

                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="col-sm-4">
                                            <label>Labor Weight:</label>
                                            <input type="text" value="{{$theData->unload_labor_weight}}"
                                                   name="shed_yard_weight[{{$k}}][unload_labor_weight]"
                                                   title="Labor Weight"
                                                   class="form-control input-sm" placeholder="Labor Weight"/>

                                        </div>

                                        <div class="col-sm-4">
                                            <label>Equ. Package:</label>
                                            <input type="text" value="{{$theData->unload_equipment_package}}"
                                                   name="shed_yard_weight[{{$k}}][unload_equipment_package]"
                                                   title="Equ. Package"
                                                   class="form-control input-sm" placeholder="Equ. Package"/>

                                        </div>

                                        <div class="col-sm-4">
                                            <label>Equ. Weight:</label>
                                            <input type="text" value="{{$theData->unload_equip_weight}}"
                                                   name="shed_yard_weight[{{$k}}][unload_equip_weight]"
                                                   title="Equ. Weight"
                                                   class="form-control input-sm" placeholder="Equ. Weight"/>

                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="col-sm-4">
                                            <label>Equ. Name:</label>
                                            <input type="text" value="{{$theData->unload_equip_name}}"
                                                   name="shed_yard_weight[{{$k}}][unload_equip_name]"
                                                   title="Equ. Name"
                                                   class="form-control input-sm" placeholder="Equ. Name"/>

                                        </div>


                                        <div class="col-sm-4">
                                            <label>Receive Comment:</label>
                                            <input type="text" value="{{$theData->unload_comment}}"
                                                   name="shed_yard_weight[{{$k}}][unload_comment]"
                                                   title="Receive Comment"
                                                   class="form-control input-sm" placeholder="Receive Comment"/>

                                        </div>


                                        <div class="col-sm-4">
                                            <label>Shifting:</label> <br>
                                            <label class="radio-inline">
                                                <input type="radio"
                                                       name="shed_yard_weight[{{$k}}][unload_shifting_flag]"
                                                       @if($theData->unload_shifting_flag==1) checked @endif value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="0"
                                                       name="shed_yard_weight[{{$k}}][unload_shifting_flag]"
                                                       @if($theData->unload_shifting_flag==0) checked @endif>No
                                            </label>

                                        </div>


                                        <div class="clearfix"></div>

                                    </div>


                                @endforeach
                            @endif

                            {{--  ==============================================     Truck Receive Data End  ================================================--}}
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
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>


@endsection
@section('script')

    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}

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