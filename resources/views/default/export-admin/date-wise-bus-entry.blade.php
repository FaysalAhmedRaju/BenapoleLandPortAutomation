@extends('layouts.master')
@section('title', 'Export Admin Reports')
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
        <h3 class="ok" style="font-weight: bold;">Export Panel Reports</h3>
        <hr>

        <div class="col-md-3 reportFormStyle">

            <h4 class="ok headingTxt">Bus Entry</h4>

            <form action="{{ route('export-bus-get-date-wise-bus-entry-report') }}" target="_blank" method="POST"
                  class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control datePicker" name="from_date_b" id="from_date_b" placeholder="Select Date" ng-model="from_date_b">
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

            <h4 class="ok headingTxt">Truck Entry</h4>

            <form action="{{route('export-truck-date-wise-entry-report')}}" target="_blank" method="POST" class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date" ng-model="from_date">
                    <div class="input-group-btn">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                            </button>
                        </div>
                    </div>

            </form>

        </div>

    </div>

@endsection