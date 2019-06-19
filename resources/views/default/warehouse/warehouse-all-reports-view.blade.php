@extends('layouts.master')
@section('title', 'Warehouse Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray;
            padding: 5px 0;
        }
        .headingTxt{
            color: #00dd00;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
        #boxShadowUpper{
            margin: 20px 25px 20px 20px;
            /*// padding: 5px;*!*!*/
            /*box-shadow: 5px 10px;*/
            box-shadow:0px 0px 1px 1px grey;

            box-sizing: border-box;
            width: 1050px;
            height: 250px;
            padding: 10px;
            /*border: 10px solid;*/
        }
        #boxShadow {
            /*border: 1px solid;*/
            /*padding: 10px;*/
            margin: 20px 25px 20px 20px;
            padding: 5px;
            /*box-shadow: 5px 10px;*/
            box-shadow:0px 0px 1px 1px grey;

            box-sizing: border-box;
            width: 1050px;
            height: 250px;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
    <div 	 class="col-md-12 text-center" >
        <h3 class="ok" style="font-weight: bold;"> Warehouse Report</h3><hr>

        <div id="boxShadowUpper" class="col-md-12" >

            <h4 class="ok" style="font-weight: bold;">Details</h4><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise WareHouse Entry:</b></h4>
                <form action="{{ route('warehouse-receive-date-wise-entry-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker"
                               name="date" placeholder="Select Receive Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-1">

            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise Delivery Report:</b></h4>
                <form action="{{ route('warehouse-delivery-date-wise-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text"  class="form-control datePicker" ng-model="dateWiseReport"
                               name="date" id="date" placeholder="Select Delivery Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-1">

            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise WareHouse:</b></h4>
                <form action="{{ route('warehouse-date-and-yard-shed-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">

                        <select class="form-control" style="width: 130px"   name="item" >
                            <option value=""  selected="selected" >Yard/Shed</option>
                            @if($yard_shed_list)
                                @foreach($yard_shed_list as $key=>$value)
                                    <option value="{{$value->id}}">{{$value->yard_shed_name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" style="width: 120px"   class="form-control datePicker" ng-model="datewiseYardWereHouse"
                               name="date" id="yardHouseDateWise" placeholder="Select Delivery Date">
                        <button ng-disabled="!datewiseYardWereHouse" type="submit" class="btn btn-primary">
                            Get
                        </button>
                        
                    </div>
                </form>
            </div>
        </div> 
            <div class="col-md-1">

            </div>
            <div class="col-md-12">
                    <br><br>
            </div>


            <div class="col-md-1">

            </div>






        <div class="col-md-1">
            <br>
        </div>

        {{--<div class="col-md-9">--}}
            {{--<br><br><br><br><br>--}}
        {{--</div>--}}
        <div id="boxShadow" class="col-md-12" >
            <h4 class="ok" style="font-weight: bold;">Summary</h4><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Current Lying Report:</b></h4>
                <form class="form-inline" action="{{route('warehouse-lying-report')}}" target="_blank" method="get">
                    <table>
                        <tr>
                            <th></th>
                            <td>
                                <select title="" style="max-width: 150px" class="form-control" name="item">
                                    <option value="">Select Item</option>
                                    @if($item_list)
                                        @foreach($item_list as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->Description}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td style="text-align: center">
                                <button type="submit" class="btn btn-primary center-block">Get</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-1">

            </div>
        </div>



    </div>
@endsection