@extends('layouts.master')
@section('title', 'Welcome Assessment Verification')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')

    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
{!!Html :: script('js/customizedAngular/AssessmentVerification.js')!!}



    <script>
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>


@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="AssessmentVerificationApp"
         ng-controller="AssessmentVerificationCtrl">
        <div class="col-md-6 col-md-offset-3" style="border:1px solid green">
            <form name="form" class="form-inline" novalidate ng-submit="manifestOrImporterOrBillSearch(searchText)">
                <div class="form-group">
                    <br>
                    Search By:
                    <select ng-change="select()" class="form-control" name="selection" ng-model="selection">
                        <option value="">---Please Select---</option>
                        <option value="manifestNo">Manifest No</option>
                        <option value="importerNo">Importer No</option>
                        <option value="billNo">Bill No</option>
                    </select>
                    <input type="text" required="required" ng-model="searchText" name="searchText"
                           class="form-control input-sm" id="searchText" ng-disabled="serachField"
                           placeholder="@{{ placeHolder }}" ng-change="clear()" ng-keydown="keyBoard($event)">
                </div>
            </form>
            <br>
        </div>

        <div class="col-md-3">
            <a href="GetAssessmentPdfReport/@{{ searchText }}" target="_blank" class="btn btn-primary">Get Assessment
                Sheet</a>

        </div>


        <div class="col-md-12">
            <div class="alert alert-danger" ng-hide="!notFoundError">@{{ notFoundError }}</div>
            <table class="table table-bordered" ng-show="table">
                <caption><h4 class="text-center">Manifest Details: @{{ searchText }}</h4></caption>
                <thead>
                <tr>
                    {{--<th>S/L</th>--}}
                    <th>Manifest</th>
                    <th>Manifest Date</th>
                    <th>Package Details</th>
                    <th>Importer Name</th>
                    <th>Exporter Name</th>
                    <th>Goods</th>
                    <th>Verification Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="manifest in allManifestData">
                    {{--<td>@{{$index+1}}</td>--}}
                    <td>@{{manifest.manifest}}</td>
                    <td>@{{manifest.manifest_date}}</td>
                    <td>@{{manifest.package_no +" "+ manifest.package_type}}</td>
                    <td>@{{manifest.importerName}}</td>
                    <td>@{{manifest.exporter_name_addr}}</td>
                    <td>@{{manifest.cargoName}}</td>
                    <td style="font-weight: bold;" ng-style="getStyle(manifest.verified)">
                        {{--<button type="button" class="btn btn-info">@{{manifest.verified | verificationFilter}}</button>--}}
                        @{{manifest.verified | verificationFilter}}
                    </td>
                    <td>
                        {{--<a class="btn btn-primary" ng-click="selected(manifest)" href="ManifestDetailsForAssessmentVerification/@{{ manifest.manifest }}" target="_blank">Details</a>--}}
                        <button type="button" class="btn btn-primary" ng-click="details(manifest.manifest)">Details
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        {{--After Press Details button--}}
        <div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                <img src="img/dataLoader.gif" width="250" height="15"/>
                <br/> Please wait!
            </span>
        </div>


        {{----------------------------------------ASSESSMENT---------------------------------------}}

        <div class="col-md-12" ng-show="assessment">

            @include('shared/assessment')

        </div>
        {{----------------------------------------ASSESSMENT---------------------------------------}}
        <div class="col-md-5 col-md-offset-3" style="left:40px; background-color: #dbd3ff; border-radius: 20px;"
             ng-show="assessmentVerificationForm">
            <h4 style="text-align: center;">Assessment Verification</h4>
            <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <table>
                    <tr>
                        <th>Verified Comment:</th>
                        <td>
                            <input class="form-control" type="text" name="verify_comm" id="verify_comm"
                                   ng-model="verify_comm" ng-disabled="show">
                            <span class="error" ng-show="verify_comm_required">Verified Comment is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-danger center-block" ng-click="reject()"
                                    ng-disabled="show">Reject
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary center-block" ng-click="verify()"
                                    ng-disabled="show">Verify
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection