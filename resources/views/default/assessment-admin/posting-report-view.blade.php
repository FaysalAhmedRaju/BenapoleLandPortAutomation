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
            <h4 class="ok headingTxt"><b>Posting:</b></h4>
            <form action="{{ route('posting-date-wise-manifest-report') }}" target="_blank" method="POST" class="form-inline">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                           name="from_date" id="date" placeholder="Select Date">
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






