@extends('layouts.master')
<title>{{ $manifestNo }}</title>
@section('script')

    {!! Html::style('css/jquery-ui-timepicker-addon.css') !!}
    {!! Html::script('js/jquery-ui-timepicker-addon.js') !!}
    {!! Html::script('js/bootbox.min.js') !!}
    {!! Html::script('js/customizedAngular/assessment-admin/assessment-details.js') !!}

    <script>
        var manifest_id = {!! json_encode($manifest_id) !!};
        var assessment_id = {!! json_encode($assessment_id); !!}
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
        var role_id = {!! json_encode(Auth::user()->role->id) !!};
        var partial_status = {!! json_encode($partial_status) !!}
    </script>

    <style type="text/css">


        @page { margin: 5px 30px; }
        /*body { margin: 0px; }*/

        .center {
            text-align: center;
        }

        /*.tble-warehouse{*/
        /*width: 100%;*/
        /*}*/

        /*.tble-warehouse tr th{*/
        /*}*/
        /*.tble-warehouse tr th, td{*/
        /*border:1px solid black;*/

        /*}*/
        .amount-right {
            text-align: right !important;

        }

        .tble-warehouse tr td {
            border: 1px solid black;
        }
    </style>
@endsection
@section('content')

<div class="col-md-12 " style=" " ng-app="AssessmentDetailsApp" ng-controller="AssessmentDetailsCtrl">

    <h5 style="text-align: right;padding-right: 35px;"> Date:{{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}</h5>

    <br><br>

    <div class="col-md-12 ng-cloak" style="padding: 0">

        <input type="hidden" id="dd"   ng-model="searchTextAssAdmin"  value="{{$manifestNo}}"
               name="searchTextAssAdmin" class="form-control input-sm">

        @include('default/shared/assessment-details')


    </div>



    <div class="col-md-12 text-center " >
        <button class="btn btn-primary" type="button" ng-click="Done()" ng-show="showDoneButton">Approve</button>
        <div class="col-md-12 text-center">
            <span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                <img src="/img/dataLoader.gif" width="250" height="15"/>
                <br/> Saving...!
             </span>
            <div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Successfully Done!
            </div>

            <div id="saveError" class="col-md-12 alert alert-warning error" ng-show="insertErrorMsg">
               @{{ insertErrorMsgTxt }}
            </div>
        </div>
        <div class="col-md-12 text-center alert alert-danger" ng-show="showAlreadyDone">
            <i class="fa fa-check"></i>Already Done
        </div>
    </div>
</div>


@endsection