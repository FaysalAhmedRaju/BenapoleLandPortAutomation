@extends('layouts.master')
@section('title', 'Export Challan')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
@endsection
@section('script')
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!! Html::script('js/customizedAngular/BusModuleChallanForm.js') !!}

@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="busModuleChallanFormApp" ng-controller="busModuleChallanFormCtrl">



        <div class="col-md-12{{--col-md-offset-1--}}">
            <div class="col-md-4" style="/*background-color: red;*/ text-align: left; padding: 0px;">
                <form action="{{ route('export-bus-month-wise-bus-challan-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        {{--  <table>
                              <tr>
                                  <th>From Date:</th>
                                  <td>--}}



                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_v" id="from_date_v">
                        {{--     </td>
                             <th style="padding-left: 40px;">To Date:</th>
                             <td>--}}
                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_v" id="to_date_v">
                        {{--</td>
                        <td style="padding-left: 10px;">--}}
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary center-block">
                                Month Wise Report
                            </button>
                        </div>
                        {{--  </td>
                      </tr>
                  </table>--}}
                    </div>
                </form>
            </div>

            <div class="col-md-2" style="/*background-color: red;*/ text-align: right; padding: 0px;" >
                <a href="{{ route('export-bus-get-todays-bus-challan-report') }}" target="_blank">
                    <button type="button" class="btn btn-primary">
                        <span class="fa fa-search"></span>Today's Challan
                    </button>
                </a>
            </div>

            <div class="col-md-3 {{--col-md-offset-0--}}"   style="/*background-color: yellow;*/ text-align: right; padding: 0px;"  >
                <form action="{{ route('export-bus-date-wise-bus-challan-report') }}" target="_blank" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker" name="from_date_challan" id="from_date_challan" placeholder="Select Date" ng-model="from_date_challan">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ng-disabled="!from_date_challan">Date Wise Report</button>
                        </div>


                    </div>

                </form>
            </div>





            <div class="col-md-3 {{--col-md-offset-1--}}">

                <form action="{{ route('export-bus-yearly-bus-challan-report') }}" target="_blank" method="get">
                    <table>
                        <tr >

                            <td style=" border-right: 200px; border-left: 200px; width: 150px">
                                <select class="form-control" name="year">
                                    @foreach($year as $item)
                                        <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="">
                                <button type="submit" class="btn btn-primary center-block">Yearly Report</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>



        </div>




        <div class="breakDiv" style="border: 10px;  margin-top: 50px; margin-bottom: 50px">
            {{--   <p>dfdfd</p>--}}
        </div>





        <div class="col-md-9 col-md-offset-1">
            <div class="col-md-5  col-md-offset-4">
                <div ng-show="existedChallanNoShow">
                    <span  class="error">@{{ export_cha }} Challan Created!</span>
                </div>
                <div class="form-group" style="width: 200px">
                    <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date For Challan" ng-model="from_date" ng-change="searchChallan(from_date)" >
                </div>


                <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                            <img src="img/dataLoader.gif" width="250" height="15"/>
                            <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please wait!
                        </span>


            </div>
        </div>


        <div class="col-md-12 table-responsive" >
            <table class="table table-bordered text-center"  ng-hide="Truck_List">
                <caption><h4 class="text-center ok">Bus List</h4></caption>
                <thead>
                <tr>
                    <th>  <input type="checkbox"
                                 ng-model="selectAll"
                                 {{--ng-change="togglecheck()"--}}
                                 {{--value="1"--}}
                                 {{--ng-checked="checkAll == 1"--}}
                                 ng-click="checkAll()" {{--ng-change="sync(selectAll,ChTruck)"--}}>Select All
                    </th>
                    <th>S/L</th>
                    <th>Bus No</th>
                    <th>Entry Date</th>
                    <th>Holtage Time(Day)</th>
                    <th>Entrance Fee</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="ChTruck in newTruckSearch | orderBy: 'ChTruck.id':true | itemsPerPage:10 ">
                    {{--<td ng-model="selection[ChTruck.id]">--}}
                        {{--@{{ $index+1 }}--}}
                    {{--</td>--}}
                    <td >
                        {{--<input type="checkbox" style="text-align:center;" name="number_list[@{{@ChTruck.id}}]"  ng-model="number_list[@{{@ChTruck.id}}]"--}}
                        {{--ng-init="number_list[@{{@ChTruck.id}}] = 0" value="1" ng-true-value="1" ng-fale-value="0"--}}
                        {{--ng-checked="number_list[@{{@ChTruck.id}}] == 1" ng-change="singleCheck()"--}}
                        {{-->--}}
                        <input type="checkbox" style="text-align:center;"
                               ng-model="ChTruck.clicked" ng-checked="isChecked(ChTruck.id)" ng-click="sync(ChTruck.clicked, ChTruck)" {{--ng-change="sync(ChTruck.clicked, ChTruck)"--}} {{--ng-model="selection[ChTruck.id]"--}}
                                {{--ng-checked="selection[ChTruck.id] = 'true'"ng-checked="truckIdChecked()"--}}
                                {{-- ng-model="challan.TruckList"--}} >
                        {{--ng-model="ChTruck.select"  ng-change="sync(ChTruck.select, ChTruck)" ng-checked="isChecked(ChTruck.id)"--}}
                    </td>
                    <td {{--ng-model="selection[ChTruck.id]"--}}>

                        {{--<input type="text" style="text-align:center;" >--}}
                        @{{ $index + serial }}
                    </td>
                    <td>@{{ ChTruck.bus_no }}</td>
                    <td>@{{ ChTruck.entry_datetime }}</td>
                    <td>@{{ ChTruck.haltage_day }}</td>
                    <td>@{{ ChTruck.entrance_fee }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7" class="text-center">
                        <dir-pagination-controls max-size="7"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>

            <table class="table table-bordered text-center" ng-hide="New_Added_Truck_List">
                <caption><h4 class="text-center ok">New Added Truck List</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Truck No</th>
                    <th>Entry Date</th>
                    <th>Holtage Time(Day)</th>
                    <th>Entrance Fee</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="ChTruck in newTruck | orderBy: 'ChTruck.id':true | itemsPerPage:10 ">
                    <td ng-model="selection[ChTruck.id]">
                        @{{ $index+1 }}
                    </td>
                    <td>@{{ ChTruck.truck_no }}</td>
                    <td>@{{ ChTruck.entry_datetime }}</td>
                    <td>@{{ ChTruck.haltage_day }}</td>
                    <td>@{{ ChTruck.entrance_fee }}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" data-target="#deleteManifestConfirm" data-toggle="modal" ng-click="delete(ChTruck)">Delete</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7" class="text-center">
                        <dir-pagination-controls max-size="7"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-md-7 col-md-offset-2" style="background-color: #f8f9f9; /*border-radius: 10px; padding: 5px 5px 5px 5px;*/">
            <h4 class="text-center ok">Bus Challan Form</h4>
            <form class="form-inline" name="callan_Form" id="callan_Form" novalidate >
                <table>
                    <tr>
                        <th>Miscellaneous:&nbsp;</th>
                        <td>
                            <input type="text" style="width: 190px;" class="form-control input-sm" ng-model="miscellaneous_name" name="miscellaneous_name"  id="miscellaneous_name" placeholder="Miscellaneous" >
                        </td>
                        &nbsp; &nbsp; &nbsp; &nbsp;
                        <th>&nbsp;&nbsp;Charge:&nbsp;</th>
                        <td>
                            <input type="text" style="width: 190px;" class="form-control input-sm" ng-model="miscellaneous_charge" name="miscellaneous_charge"  id="miscellaneous_charge" placeholder="Charge" >
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="text-center">
                            <br>
                            <button type="button" class="btn btn-primary center-block" ng-click="SaveChallan()" ng-hide="SaveBtn" ng-disabled="!from_date" >Save</button>
                            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                            <div class="alert alert-danger" id="savingError"  ng-hide="!savingError" >@{{ savingError }}</div>
                            <button type="button" class="btn btn-primary center-block" ng-click="UpdateChallan()" ng-hide="updateBtn" >Update</button>
                            <div class="alert alert-success" id="savingSuccessUpdate" ng-hide="!savingSuccessUpdate">@{{ savingSuccessUpdate }}</div>
                            <div class="alert alert-danger" id="savingUpdateError" ng-hide="!savingUpdateError">@{{ savingUpdateError }}</div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        {{--------------------------------Created Challan List Show Model----------------------------}}
        <div class="col-md-12 table-responsive" ng-hide="Created_challan_list_show">
            <table class="table table-bordered text-center">
                <caption><h4 class="text-center ok">Challan List</h4><label class="form-inline">Search : <input class="form-control" ng-model="searchText" placeholder="Search"></label></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Challan NO</th>
                    <th>Created DateTime</th>
                    <th>Challan Report</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="challan in allExChallanList | orderBy: 'challan.id':true | filter:searchText | itemsPerPage:itemPerpage ">
                    <td>@{{ $index + serial }}</td>
                    <td>@{{ challan.export_challan_no }}</td>
                    <td>@{{ challan.create_datetime }}</td>
                    <td>
                        <a  href="/export/bus/report/get-export-bus-challan-report/@{{challan.export_challan_no}}" class="btn btn-success" target="_blank">Challan Report</a>
                    </td>
                    <td>
                        <a class="btn btn-danger btn-md" ng-click="delete(challan)"
                           data-target="#deleteBusModuleChallan" data-toggle="modal">Delete</a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        {{--------------------------------Created Challan List Show Model End Here----------------------------}}


        {{-- ------------------------Delete Model----------------------------}}
        <div class="modal fade" id="deleteBusModuleChallan" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title text-center">Are you sure to delete Challan No: <b>@{{ export_challan_no }}?</b></h4>
                        <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteTruck()">Yes</a>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    </div>
                    <div class="modal-footer">
                        <span ng-show="deleteFailMsg">Something wrong!</span>
                        <div id="deleteSuccess" class="alert alert-success text-center" ng-show="deleteSuccessMsg">
                            Successfully deleted!
                        </div>
                        <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------Delete Model End----------------------------}}


    <!-- addTruckFormModal Modal START=====================================================================================================MODAL========== -->
        <div id="addTruckFormModal" class="modal fade" role="dialog" >
            <div class="modal-dialog" >
                <div class="modal-content largeModal" id="">
                    <div class="modal-body">
                        <table class="table table-bordered text-center">
                            <caption><h4 class="text-center ok">Truck List</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
                            <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Truck No</th>
                                <th>Entry Date</th>
                                <th>Holtage Time(Day)</th>
                                <th>Entrance Fee</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="ChTruckList in challanIncomTruckList | orderBy: 'ChTruckList.id':true | filter:searchText| itemsPerPage:10 ">
                                <td>
                                    <input type="checkbox" style="text-align:center;" ng-model="selection[ChTruckList.id]">
                                </td>
                                <td>@{{ ChTruckList.truck_no }}</td>
                                <td>@{{ ChTruckList.entry_datetime }}</td>
                                <td>@{{ ChTruckList.haltage_day }}</td>
                                <td>@{{ ChTruckList.entrance_fee }}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="10" class="text-center">
                                    <dir-pagination-controls max-size="10"
                                                             direction-links="true"
                                                             boundary-links="true">
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary center-block" ng-click="addTruck()" data-dismiss="modal">ADD</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        {{-- ------------------------ End Exit Model----------------------------}}
    </div>
    <script type="text/javascript">
        $('#entry_datetime, #exit_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
    </script>
@endsection