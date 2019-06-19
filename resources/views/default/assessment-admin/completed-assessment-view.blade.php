@extends('layouts.master')
@section('title', 'Completed Assessment')
@section('script')
    <script>
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
    {!! Html::script('js/customizedAngular/assessment-admin/completed-assessment.js') !!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="CompletedAssessmentApp"
         ng-controller="CompletedAssessmentCtrl">
        <div>
            <div class="form-inline">
                <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getCompletedAssessment(date)">Created</button>
                <button class="btn btn-success" type="button" ng-click="getCompletedAssessment(date,1)">Approved</button>

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
            <table   class="table table-bordered paddingStyleTable">
                <caption><h4 class="text-center ok">Completed Assessment</h4><label class="form-inline">Search:<input
                                class="form-control" ng-model="searchTextCompletedAssessment" ng-change="searchFunction(searchTextCompletedAssessment)"></label></caption>
                <thead>
                <tr class="paddingStyleTable">
                    <th style=" padding: 0px;"  colspan="4">S/L</th>
                    <th style=" padding: 0px;"  colspan="2" ><nobr>Port Charge:&nbsp;&nbsp; @{{ totalAssessmentValue|number:2 }}</nobr></th>

                    <th style=" padding: 0px;"  >Vat:@{{ totalAssessmentVat |number:2}}</th>
                    <th style=" padding: 0px;" >Total:@{{ totalAssessmentValueVat |number:2}}</th>
                    <th style=" padding: 0px;"  colspan="5"></th>

                </tr>

                <tr class="paddingStyleTable">
                    <th style=" padding: 0px;"  >S/L</th>
                    <th style=" padding: 0px;" >Manifest No</th>
                    <th  style=" padding: 0px;" >CNF Name</th>
                    <th  style=" padding: 0px;" >Importer Name</th>
                    <th style=" padding: 0px;" >Goods Description</th>
                    <th style=" padding: 0px;" >Port Charge</th>
                    <th  style=" padding: 0px;" >VAT</th>
                    <th  style=" padding: 0px;" >Total</th>
                    <th style=" padding: 0px;" >Status</th>
                    <th style=" padding: 0px;"  >Shed-Yard</th>
                    <th style=" padding: 0px;"  ng-show="created_at_show">Creator</th>
                    <th style=" padding: 0px;"  ng-show="done_at_show">Done By</th>
                    <th style=" padding: 0px;"   ng-show="created_at_show">Created At</th>
                    <th style=" padding: 0px;"  ng-show="done_at_show">Done At</th>
                    <th style=" padding: 0px;"  >Action</th>
                </tr>
                </thead>
                <tbody >
                <tr dir-paginate="todaysCompletedAssessment in allTodaysCompletedAssessment | filter:searchTextCompletedAssessment | itemsPerPage:itemPerPage"
                    pagination-id="todaysCompletedAssessment" >
                    <td style=" padding: 0px;" >@{{ $index+serial }}</td>
                    <td style=" padding: 0px;" >
                        @{{ todaysCompletedAssessment.manifest }}<br>
                        <span ng-if="todaysCompletedAssessment.partial_status>1">(Partial)</span>
                    </td>
                    {{--<td>@{{ todaysCompletedAssessment.be_no }}</td>
                    <td>@{{ todaysCompletedAssessment.custom_release_order_no }}</td>--}}


                    <td style=" padding: 0px;"  >@{{ todaysCompletedAssessment.cnf_name }}</td>
                    <td style=" padding: 0px;"  >@{{ todaysCompletedAssessment.importerName }}</td>
                    <td style=" padding: 0px;" >@{{ todaysCompletedAssessment.assessment_values |  getValue: 'good_description' }}</td>
                    <td style=" padding: 0px;" >@{{ todaysCompletedAssessment.totalAssessmentValue | number:2 }}</td>
                    <td style=" padding: 0px;" >@{{ todaysCompletedAssessment.assessment_values |  getValue: 'vat' : todaysCompletedAssessment.totalAssessmentValue | number:2 }}</td>
                    <td style=" padding: 0px;" >@{{ todaysCompletedAssessment.assessment_values |  getValue: 'total' : todaysCompletedAssessment.totalAssessmentValue | number:2 }}</td>
                    <td  style="font-weight: bold; padding: 0px">
                        <span ng-if="todaysCompletedAssessment.done" class="text-success">Done</span>
                        <span ng-if="!todaysCompletedAssessment.done" class="text-danger">Created</span>
                    </td>
                    <td style=" padding: 0px;" >@{{ todaysCompletedAssessment.yard_shed_name }}</td>
                    <td style=" padding: 0px;"  ng-show="created_at_show">@{{ todaysCompletedAssessment.created_by }}</td>
                    <td style=" padding: 0px;"  ng-show="done_at_show">@{{ todaysCompletedAssessment.done_by }}</td>
                    <td style=" padding: 0px;"   ng-show="created_at_show">
                       <nobr> @{{ todaysCompletedAssessment.created_at | stringToDate: "d-M-y" }}</nobr> <br>
                        @{{ todaysCompletedAssessment.created_at | stringToDate: "HH:mm:ss" }}
                    </td>
                    <td style=" padding: 0px;"  ng-show="done_at_show">
                        <nobr> @{{ todaysCompletedAssessment.done_at | stringToDate: "d-M-y" }}</nobr> <br>
                        @{{ todaysCompletedAssessment.done_at | stringToDate: "HH:mm:ss" }}
                    </td>

                    <td  style=" padding: 0px;" >
                        @if(Auth::user()->role_id==1)
                            <a href="/assessment/get-assessment-report/@{{ todaysCompletedAssessment.manifest }}/@{{todaysCompletedAssessment.partial_status}}"  target="_blank"
                               class="btn btn-primary btn-sm">
                                Details
                            </a>
                        @else
                            <a class="btn btn-info"
                               href="/assessment-admin/get-assessement-details/@{{ todaysCompletedAssessment.manifest }}/@{{todaysCompletedAssessment.id}}/@{{todaysCompletedAssessment.partial_status}}"
                               target="_blank">Details</a>
                        @endif
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="13" class="text-center">
                        <dir-pagination-controls max-size="6" on-page-change='indexCount(newPageNumber)'
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="todaysCompletedAssessment">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
