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
        <h3 class="ok" style="font-weight: bold;">Cargo Panel Reports</h3>
        <hr>

        <div class="col-md-3 reportFormStyle">
            <h4 class="headingTxt">Truck Entry</h4>
            <form action="{{ route('truck-date-wise-truck-pdf-report') }}" class="form-inline" target="_blank" method="POST">
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
            <h4 class="headingTxt">Truck Exit</h4>
            <form action="{{  route('truck-exit-report-date-wise-pdf-report') }}" class="form-inline" target="_blank" method="POST">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text"  class="form-control datePicker" ng-model="exitDate"
                           name="date" id="exitDate" placeholder="Select Exit Date">
                    <div class="input-group-btn">
                        {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                        <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                            {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                        </button>
                    </div>
                </div>
            </form>

        </div>

    </div>

@endsection