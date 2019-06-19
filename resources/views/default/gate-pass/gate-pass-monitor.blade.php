@extends('layouts.master')
@section('title', 'Gate Pass Monitor')
@section('script')
    <script >

        $(function () {
            $("#dateP").datepicker(
                {
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                }
            );
        });

    </script>
    {!! Html::script('js/customizedAngular/gate-pass/gate-pass.js') !!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="GatePassApp" ng-controller="GatePassCtrl">
        <div>
            <div class="form-inline">
                <form name="ChallanForm" action="{{ route('gate-pass-get-gate-pass-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date"
                      {{-- ng-change="getCompletedAssessment(date)"--}} >
                    <button class="btn btn-success" type="button" ng-click="getCompletedAssessment(date)">Gate Pass</button>
                    <button type="submit" class="btn btn-success" >Gate Pass Report</button>
                {{--<button class="btn btn-success" type="button" ng-click="getCompletedAssessment(date)">Report</button>--}}
                {{--<button class="btn btn-success" type="button" ng-click="getCompletedAssessment(date)">Approved</button>--}}
                </form>
            </div>
            <h5 class="text-center ok"><b style="color:  #b03e00">@{{ buttonMessage }}</b></h5>
        </div>
        <div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:20px">
                <img src="img/dataLoader.gif" width="250" height="15"/>
                <br/> Please wait!
            </span>
        </div>
        <div class="col-md-12 table-responsive" ng-show="dataDiv">
            <table   class="table table-bordered paddingStyleTable" >
                <caption><h4 class="text-center ok">Gate Pass List</h4><label class="form-inline">Search:<input
                                class="form-control" ng-model="searchTextCompletedAssessment"></label></caption>
                <thead>
                {{--<tr class="paddingStyleTable">--}}
                    {{--<th style=" padding: 0px;"  colspan="4">S/L</th>--}}
                    {{--<th style=" padding: 0px;"  colspan="2"><nobr> Total Port Charge: @{{ totalAssessmentValue|number:2 }}</nobr></th>--}}
                    {{--<th style=" padding: 0px;"  colspan="2">Total Vat:@{{ totalAssessmentVat |number:2}}</th>--}}
                    {{--<th style=" padding: 0px;"  colspan="5"></th>--}}

                {{--</tr>--}}

                <tr class="paddingStyleTable">
                    <th style=" /*padding: 0px; */text-align: center"  >S/L</th>
                    <th style="  text-align: center" >Manifest No</th>
                    <th style="  text-align: center" >Goods Description</th>
                    <th  style="  text-align: center" >Importer Name</th>
                    <th style="  text-align: center"  >Shed-Yard</th>
                    <th  style="  text-align: center" >CNF Name</th>
                    <th style=" width: 120px; text-align: center">Delivery Date</th>
                    <th style="  text-align: center" >Challan No</th>
                    <th  style="  text-align: center" >Gate Pass No</th>
                    <th  style="  text-align: center" >Created By</th>

                    {{--<th style=" padding: 0px;"  ng-show="done_at_show">Done By</th>--}}
                    {{--<th style=" padding: 0px;"   ng-show="created_at_show">Created At</th>--}}
                    {{--<th style=" padding: 0px;"  ng-show="done_at_show">Done At</th>--}}
                    <th style=" padding: 0px; text-align: center"  >Action</th>
                </tr>
                </thead>
                <tbody >
                <tr dir-paginate="getData in allGatePassData | filter:searchTextCompletedAssessment | itemsPerPage:itemPerPage"
                    pagination-id="getData">
                    <td style=" /*padding: 0px;*/" >@{{ $index+serial }}</td>
                    <td style=" /*padding: 0px;*/" >
                        <u>@{{ getData.manifest }}</u><br>
                        @{{ getData.manifest_date }}<br>
                        <span ng-if="getData.partial_status>1">(Partial)</span>
                    </td>
                    <td style=" " >@{{ getData.cargo_name }}</td>
                    <td style=" "  >@{{ getData.importer_name }}</td>
                    <td style=" " >@{{ getData.yard_shed_name }}</td>
                    <td style=" "  >@{{ getData.cnf_name }}</td>
                    <td style=" "  >@{{ getData.approximate_delivery_date }}</td>
                    <td style=" " >@{{ getData.challan_no }}</td>
                    <td style=" " >@{{ getData.gate_pass_no }}</td>
                    <td style=" " >@{{ getData.created_by }}</td>

                    {{--<td style=" padding: 0px;"  ng-show="done_at_show">@{{ getData.done_by }}</td>--}}
                    {{--<td style=" padding: 0px;"   ng-show="created_at_show">--}}
                        {{--<nobr> @{{ getData.created_at | stringToDate: "d-M-y" }}</nobr> <br>--}}
                        {{--@{{ getData.created_at | stringToDate: "HH:mm:ss" }}--}}
                    {{--</td>--}}
                    {{--<td style=" padding: 0px;"  ng-show="done_at_show">--}}
                        {{--<nobr> @{{ getData.done_at | stringToDate: "d-M-y" }}</nobr> <br>--}}
                        {{--@{{ getData.done_at | stringToDate: "HH:mm:ss" }}--}}
                    {{--</td>--}}

                    <td  style=" " >
                        <form action="{{ route('gateout-get-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">
                            {{ csrf_field() }}
                            <input ng-show="ff" class="form-control" value="@{{ getData.manifest }}" type="text" name="manifest"
                                   id="manifest"/>
                            <input type="hidden" name="partial_status_for_gatepass" value="@{{ getData.partial_status }}">
                            <button type="submit" {{--ng-disabled="!searchText"--}} class="btn btn-info">Report
                            </button>
                        </form>

                     {{--       <a class="btn btn-info"
                               href="/gate-pass/report/manifest-wise-get-gate-pass-report/@{{ getData.manifest }}"
                               target="_blank">Report</a>--}}

                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="13" class="text-center">
                        <dir-pagination-controls max-size="20" on-page-change='indexCount(newPageNumber)'
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="todaysCompletedAssessment">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>




        {{--<div class="col-md-6 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">--}}
            {{--<form name="ChallanForm" action="{{ route('gateout-get-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">--}}
                {{--{{ csrf_field() }}--}}
                {{--<div class="col-md-12">--}}
                    {{--<table>--}}
                        {{--<br>--}}
                        {{--<tr>--}}
                            {{--<th>Manifest:</th>--}}
                            {{--<td>--}}
                                {{--<input class="form-control" type="text" name="manifest" id="manifest" ng-change="getPartialList()"--}}
                                       {{--ng-pattern="/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/"--}}
                                       {{--ng-model="manifest"  ng-keydown="keyBoard($event)" ng-model-options="{allowInvalid : true}"--}}
                                       {{--required="required" ng-change="getPartialList()" placeholder="Enter Manifest No">--}}
                            {{--</td>--}}
                            {{--<td colspan="2">--}}
                                {{--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<select  ng-if="partial_number_list.length>0"  title="" style="width: 130px;" required="required"--}}
                                         {{--class="form-control"--}}
                                         {{--ng-change="get_partial(searchText,partial_status)"--}}
                                         {{--name="partial_status"--}}
                                         {{--ng-model="partial_status"--}}
                                         {{--ng-options="item as item for item in partial_number_list">--}}
                                    {{--<option value="">Select Partial</option>--}}
                                {{--</select>--}}
                                {{--<input type="hidden" name="partial_status_for_gatepass" value="@{{partial_status}}">--}}
                            {{--</td>--}}
                            {{--<td style="padding-left: 10px;">--}}
                                {{--<button type="submit" class="btn btn-primary center-block">Show</button>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td colspan="6" class="text-center">--}}
                                    {{--<span class="error" ng-show='ChallanForm.manifest.$error.pattern'>--}}
                                        {{--Input like: 256/12/2017 Or 256/A/2017--}}
                                    {{--</span>--}}
                                {{--<span class="error" id="errorMessage" ng-show="errorMessage">--}}
                                        {{--@{{errorMessage}}--}}
                                    {{--</span>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                    {{--<br>--}}
                {{--</div>--}}
            {{--</form>--}}
        {{--</div>--}}


    </div>
@endsection