@extends('layouts.master')
@section('title', 'Assessment Admin Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray;
            padding: 5px 0;
        }

        .headingTxt {
            color: #00dd00;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12 text-center">
        <h3 class="ok" style="font-weight: bold;">Todays' Reports</h3>
        <hr>

        <div class="col-md-3 reportFormStyle">

            {{-- <a type="button" target="_blank" class="btn btn-primary" href={{url('dailyTruckReportPdf')}} >
                 <span class="fa fa-calendar-o"></span> Todays' Entry </a>--}}
            {{--   <h4 class="ok headingTxt"><b>
                       <a href="{{ url('dailyTruckReportPdf') }}" target="_blank">Todays Truck:</a></b>
               </h4>--}}
            <h4 class="headingTxt">Truck Entry</h4>

            <form action="{{ url('DateWiseTruckReportPdf') }}" class="form-inline" target="_blank" method="POST">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="date" id="date" placeholder="Select Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-1">

        </div>


        <div class="col-md-3 reportFormStyle">

            <h4 class="ok headingTxt">WeighBridge</h4>

            <form action="{{ url('getDateWiseWeightbridgeEntryReportPDF') }}" target="_blank" method="POST"
                  class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="date" id="date" placeholder="Select Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        <button type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-md-1">

        </div>
        <div class="col-md-3 reportFormStyle">
            <h4 class="ok headingTxt"><b>Posting:</b></h4>
            <form action="{{ url('reportPostingPDF') }}" target="_blank" method="POST" class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="date" id="date" placeholder="Select Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        <button type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>
            </form>

        </div>

        <div class="clear-fix">
            <br><br>
        </div>

        <div class="col-md-3 reportFormStyle">
            <h4 class="ok headingTxt">WareHouse:</h4>

            <form action="{{ url('DateWiseWarehouseReceiveReportPdf') }}" target="_blank" method="POST"
                  class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="date" id="date" placeholder="Select Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        <button type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-1">

        </div>


        <div class="col-md-3 reportFormStyle">
            <h4 class="ok headingTxt">
                <b>Delivery:</b>
            </h4>
            <form action="{{ route('warehouse-delivery-date-wise-report') }}" target="_blank" method="post"
                  class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="date" id="date" placeholder="Select Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        {{--<a href="{{ url('todaysDeliveryRequestPDF',['date']) }}" class="btn btn-primary"
                           target="_blank">
                            Delivery
                        </a>--}}
                        <button type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-1">

        </div>
    </div>

@endsection






