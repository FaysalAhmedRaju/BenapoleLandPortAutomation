@extends('layouts.master')
@section('title', 'Manifest Branch Monitor')
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
    {!! Html::script('js/customizedAngular/manifest-branch/manifest-branch-monitor.js') !!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="ManifestBranchApp" ng-controller="ManifestBranchCtrl">
        <div>
            <div class="form-inline">
                <form action="{{ route('manifest-branch-date-shed-yard-wise-manifest-details-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}
                <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <select class="form-control" name ="shed_yard" ng-model="shed_yard" id="shed_yard" ng-change="shedYardName()"  {{--required--}}>
                    <option value="" selected="selected">Select Shed Yard:</option>
                    @if($shed_yards)
                        @foreach($shed_yards as $key=>$value)
                            <option value="{{$value->id}}">{{$value->shed_yard}}</option>
                        @endforeach
                    @endif
                </select>
                <select class="form-control" name ="shed_yard_type" ng-model="shed_yard_type" id="shed_yard_type"
                        ng-options="type.id as type.yard_shed_name for type in shed_yard_type_data"
                        {{--required--}}>
                    <option value="" selected="selected">Select Type:</option>
                    {{--@if($shed_yards)--}}
                        {{--@foreach($shed_yards as $key=>$value)--}}
                            {{--<option value="{{$value->id}}">{{$value->shed_yard}}</option>--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                </select>

                <button class="btn btn-success" ng-disabled="!shed_yard_type" type="button" ng-click="getCompletedAssessment(date,shed_yard,shed_yard_type)">Search</button>





                    <button type="submit" ng-disabled="!shed_yard_type" class="btn btn-primary">
                        {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                    </button>

                    {{--<div class="input-group" >--}}
                        {{--<div class="input-group-btn">--}}
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                           {{----}}
                        {{--</div>--}}
                    {{--</div>--}}
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
                <caption><h4 class="text-center ok">Manifest Details</h4><label class="form-inline">Search:<input
                                class="form-control" ng-model="searchTextCompletedAssessment"></label></caption>
                <thead>
                {{--<tr class="paddingStyleTable">--}}
                    {{--<th style=" padding: 0px;"  colspan="4">S/L</th>--}}
                    {{--<th style=" padding: 0px;"  colspan="2"><nobr> Total Port Charge: @{{ totalAssessmentValue|number:2 }}</nobr></th>--}}
                    {{--<th style=" padding: 0px;"  colspan="2">Total Vat:@{{ totalAssessmentVat |number:2}}</th>--}}
                    {{--<th style=" padding: 0px;"  colspan="5"></th>--}}
                {{--</tr>--}}

                <tr class="paddingStyleTable">
                    <th style=" padding: 0px;"  >S/L</th>
                    <th style="text-align: center; padding: 0px;" >Manifest No.<br>And Date</th>
                    <th  style="text-align: center; width: 100px; padding: 0px;" >Truck No.</th>
                    <th  style="text-align: center; padding: 0px;" >Description Goods</th>
                    <th  style="text-align: center; padding: 0px;" >Importer Name</th>
                    <th  style="text-align: center; padding: 0px;" >Receive Weight</th>
                    <th  style="text-align: center; padding: 0px;" >Receive Package</th>

                    {{--<th  style=" padding: 0px;" >Yard/Shed</th>--}}
                    <th style="text-align: center; padding: 0px;" >Receive Time</th>
                    <th style="text-align: center; padding: 0px;" >Labor Weight</th>
                    <th  style="text-align: center; padding: 0px;" >Labor Package</th>
                    <th  style="text-align: center; padding: 0px;" >Equipment Weight</th>
                    <th style="text-align: center; padding: 0px;" >Equipment Package</th>
                    <th style="text-align: center; padding: 0px;"  >Equipment Name</th>


                    {{--<th style=" padding: 0px;"  ng-show="created_at_show">Creator</th>--}}
                    {{--<th style=" padding: 0px;"  ng-show="done_at_show">Done By</th>--}}
                    {{--<th style=" padding: 0px;"   ng-show="created_at_show">Created At</th>--}}
                    {{--<th style=" padding: 0px;"  ng-show="done_at_show">Done At</th>--}}
                    {{--<th style=" padding: 0px;"  >Action</th>--}}
                </tr>
                </thead>
                <tbody >
                <tr dir-paginate="manifestShedYardDetails in allData | filter:searchTextCompletedAssessment | itemsPerPage:itemPerPage"
                    pagination-id="manifestShedYardDetails">
                    <td style=" padding: 0px;" >@{{ $index+serial }}</td>
                    <td style=" padding: 0px;" ><u>@{{ manifestShedYardDetails.manifest }}</u> <br> @{{ manifestShedYardDetails.manifest_date }}</td>
                    <td style=" padding: 0px;"  >@{{manifestShedYardDetails.truck_type +"-"+ manifestShedYardDetails.truck_no}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.cargo_name}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.NAME}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.receive_weight | number:2 }}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.receive_package}}</td>
                    {{--<td style=" padding: 0px;"  >@{{ manifestShedYardDetails.yard_shed_name }}</td>--}}
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.receive_time}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.unload_labor_weight | number:2 }}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.unload_labor_package}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.unload_equip_weight | number:2 }}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.unload_equipment_package}}</td>
                    <td style=" padding: 0px;" >@{{ manifestShedYardDetails.unload_equip_name }}</td>


                    {{--<td style=" padding: 0px;"  ng-show="created_at_show">@{{ manifestShedYardDetails.created_by }}</td>--}}
                    {{--<td style=" padding: 0px;"  ng-show="done_at_show">@{{ manifestShedYardDetails.done_by }}</td>--}}
                    {{--<td style=" padding: 0px;"   ng-show="created_at_show">--}}
                        {{--<nobr> @{{ manifestShedYardDetails.created_at | stringToDate: "d-M-y" }}</nobr> <br>--}}
                        {{--@{{ manifestShedYardDetails.created_at | stringToDate: "HH:mm:ss" }}--}}
                    {{--</td>--}}
                    {{--<td style=" padding: 0px;"  ng-show="done_at_show">--}}
                        {{--<nobr> @{{ manifestShedYardDetails.done_at | stringToDate: "d-M-y" }}</nobr> <br>--}}
                        {{--@{{ manifestShedYardDetails.done_at | stringToDate: "HH:mm:ss" }}--}}
                    {{--</td>--}}

                    {{--<td  style=" padding: 0px;" >--}}
                        {{--@if(Auth::user()->role_id==1)--}}
                            {{--<a href="/assessment/get-assessment-report/@{{ manifestShedYardDetails.manifest }}/@{{manifestShedYardDetails.partial_status}}"  target="_blank"--}}
                               {{--class="btn btn-primary btn-sm">--}}
                                {{--Details--}}
                            {{--</a>--}}
                        {{--@else--}}
                            {{--<a class="btn btn-info"--}}
                               {{--href="/assessment-admin/get-assessement-details/@{{ manifestShedYardDetails.manifest }}/@{{manifestShedYardDetails.id}}/@{{manifestShedYardDetails.partial_status}}"--}}
                               {{--target="_blank">Details</a>--}}
                        {{--@endif--}}
                    {{--</td>--}}
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="14" class="text-center">
                        <dir-pagination-controls max-size="6" on-page-change='indexCount(newPageNumber)'
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="manifestShedYardDetails">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
