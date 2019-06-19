@extends('layouts.master')
@section('title', 'Truck Entry/Exit Form')

@section('script')

    {!!Html :: script('js/customizedAngular/bdTruckEntry.js')!!}
    <script type="text/javascript">
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>

    {{-- <style>
         #manifestTblb td {
             background-color: #dbd3ff;
         }

         .selectedRow {
             background-color: #dbd3ff !important;
         }

         /*.invalid { border:2px solid red; box-shadow: 0 0 10px red; }*/
         /*.valid { border:2px solid  green; box-shadow: 0 0 10px green;}*/

     </style>--}}
@endsection
@section('content')
    <div class="col-md-12" style="padding: 0;" ng-cloak="" ng-app="BdtruckEntryApp" ng-controller="bdtruckEntryController">

        <div class="col-md-9 col-md-offset-4" style=" padding-left: 20px">


            {{--<div class="col-md-4" style="">--}}
                {{--<form action="{{ url('DateWiseCnfBDLoclTruckReportPdf') }}" class="form-inline" target="_blank" method="POST">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<div class="input-group">--}}
                        {{--<input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"--}}
                               {{--name="date" id="date" placeholder="Select Date">--}}
                        {{--<div class="input-group-btn">--}}
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            {{--<button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">--}}
                                {{-- <span class="fa fa-calendar-o"></span>--}}{{-- Get Report--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}


            <div class="col-md-4 col-md-offset-5"   style="/*background-color: yellow;*/ /*text-align: right; padding: 0px;*/"  >
                <form action="{{ route('c&f-bd-truck-date-wise-entry-report') }}" target="_blank" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date" ng-model="from_date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary" ng-disabled="!from_date">Date Wise Report </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
        <br><br> <br>

        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px;">

            <div class="col-md-12 text-center">
                <form class="form-inline" ng-submit="doSearch({{--manf_id--}})">
                    <div class="form-group">
                        <label for="ManifestNo">Manifest No: </label>
                        <input type="text" name="ManifestNo" ng-model="manf_id" id="ManifestNo" class="form-control"
                               placeholder="Manifest No." ng-model-options="{allowInvalid: true}"
                               ng-keydown="keyBoard($event)">
                    </div>
                    <br>
                    <span class="ok">@{{ searchFound }}</span>
                    <span class="error">@{{ searchNotFound }}</span>
                </form>
                <div ng-show="truckDivShow">
                    <span>@{{ totalTruck }} Trucks alreay entered with the manifest</span>
                </div>
            </div>

            <td colspan="4">&nbsp;

            </td>



            <form class="formBgColor"  name="bdTruckForm" id="bdTruckForm" style="padding: 10px 100px; margin: 0 auto;width: 711px;padding: 10px;">
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <td class="text-center" colspan="6">
                            <h4 class="ok">BD Truck Entry</h4>
                        </td>
                    </tr>
                    <thead>


                    <tbody>
                    <tr ng-hide="" >
                        <th>Truck No<span class="mandatory">*</span> :</th>
                        <td  class="input-group">
                            <select class="form-control" style=" height: auto; width: 100px;" name="truck_type"  id="truck_type" ng-model="truck_type"  ng-options="type.truck_id as type.type_name for type in truck_type_data" required>
                                <option value="" selected="selected">Type Name</option>
                            </select>

                            <input type="text"  style="height: auto; width: 100px;"  ng-model="bd_truck_no" name="bd_truck_no" id="bdtruck_no" class="form-control input-sm" placeholder="Truck No." required>

                            <span class="error" ng-show="bdTruckForm.bd_truck_no.$invalid && submitted">
                                      Truck No is required
                                    </span>
                        </td>


                        <th >&nbsp;&nbsp;&nbsp; Driver Name<span class="mandatory">*</span>: </th>
                        <td >
                            <input type="text" ng-model="bd_driver_name" name="bd_driver_name" id="bddriver_name" class="form-control input-sm" placeholder="Driver Name" required>

                            <span class="error" ng-show="bdTruckForm.bd_driver_name.$invalid && submittedBDTruck && !BDTruckFull">
                                      Driver name is required
                                    </span>


                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>



                    <tr>
                        <td colspan="6" class="text-center">


                            <button id="saveBdTruckData" type="button" ng-click="CnfBdLocalTrucksaveData(bdTruckForm)" class="btn btn-primary center-block" ng-if="buttonBdTruck"><span class="fa fa-file"></span> Save
                            </button>
                            <p id="saveDbTruckSuccessMsg" ng-show="saveBdTruckSuccess" class="ok">Successfully @{{saveBdTruckSuccessMsg}}</p>
                            <p ng-if="savingBdTruckError" class="error">Something went worng!</p>


                            <div id="bDTruckdeletesuccessmsg" class="col-md-12 alert alert-success"
                                 ng-show="bDTruckdeletesuccessmsg">
                                Successfully deleted!
                            </div>






                        </td>



                    </tr>
                    <tr>
                        <td colspan="6" class="text-center"  ng-if="manifestDataLoadingError" >
                        <span style="color:red; text-align:center; font-size:15px">
                            <p>@{{ searchTextNotFoundTxt }} Couldn't found</p>
                        </span>
                        </td>
                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center ok" colspan="6">

                        </td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>

        <div class="clearfix"></div>


        <div class="col-md-12 table-responsive">
            <div id="manifestDetails" ng-hide="todaysEntryDiv">
                <h4 class="text-center ok">Truck Details:</h4>

                <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                    <thead>

                    <tr>
                        <td colspan="8" class="text-center" ng-if="dataLoading">
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="img/dataLoader.gif" width="300" height="20"/>
                            <br/> Please wait!
                        </span>
                        </td>
                    </tr>

                    <tr>
                        <th>S/l</th>
                        <th>Manifest No.</th>
                        <th>Truck No.</th>
                        <th>Driver Name</th>
                        {{--<th>Labour Loading</th>--}}

                        {{--<th> Equipment Loading</th>--}}
                        {{--<th>Equipment Package</th>--}}
                        {{--<th ng-show="allBdTrucksData[0].equip_name!=null">Equipment Name</th>--}}
                        {{--<th>Date</th>--}}
                        <th>Action</th>

                    </tr>
                    </thead>

                    <tbody>

                    <tr dir-paginate="bdTruck in allBdLocalData|orderBy:'id':true|itemsPerPage:10">

                        <td>@{{$index +1}}</td>
                        <td>@{{bdTruck.manifest}}</td>
                        <td>@{{bdTruck.truck_no}}</td>

                        <td>@{{bdTruck.driver_name}}</td>

                        <td>
                            <button type="button" ng-click="editBdTruck(bdTruck)" class="btn btn-primary btn-xs">Edit</button>
                            <button type="button" ng-click="deleteBdTruck(bdTruck)" class="btn btn-primary btn-xs">Delete</button>
                        </td>
                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="8" class="text-center">

                            <dir-pagination-controls max-size="5"
                                                     direction-links="true"
                                                     boundary-links="true">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    <tr ng-if="loadingerror">
                        <td colspan="8">
                            <div class="alert alert-danger">
                                <p id="errorLoadData" style="color:green; text-align:center; font-size:20px"></p>
                                Error! The leave data was not loaded.
                            </div>
                        </td>
                    </tr>
                    </tfoot>


                </table>

            </div>

            <!--Modal for Delete confirm -->


            <div class="modal fade" id="deleteManifestConfirm" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>

                            <h5 class="text-center">Manifest No:<b>@{{ d.ManifestNo }} </b></h5>
                        </div>
                        <div class="modal-body">

                            <h4 class="modal-title text-center">Are you sure to delete Truck No: <b>@{{ d.truck_type }}
                                    -@{{ d.truck_no }}?</b></h4>

                            <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteTruck()">Yes</a>

                            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>

                        </div>
                        <div class="modal-footer">
                            <span ng-show="deleteFailMsg">Something wrong!</span>
                            <div id="deleteSuccess" class="alert alert-warning text-center" ng-show="deleteSuccessMsg">
                                @{{deleteSuccessMsgTxt  }}
                            </div>

                            <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close
                            </button>

                        </div>
                    </div>
                </div>
            </div>


            <!--Modal for Delete confirm  END -->


        </div>
        {{--------------------------------- Exit Model------------------------}}
        <div class="modal fade text-center" style="left:0px; " id="ExitModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Do you want to make gate out?</h4>
                        <span class="text-center">Manifest No: <b>@{{ exit_manifest }}</b>&nbsp;</span>
                        <span class="text-center">&nbsp;&nbsp;Truck No: <b>@{{ exit_truck_no }}</b></span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group form-inline">
                            <label for="out_comment">Out Comment:</label>
                            <input type="text" class="form-control " name="out_comment" ng-model="out_comment">
                        </div>
                        {{--<button type="button" class="btn btn-primary" ng-click="getOut()" ng-disabled="whenExitSuccessfull">Exit</button>--}}
                    </div>
                    <div class="modal-footer">
                        {{--<span class="error text-center" ng-show="exitError">Something wrong!</span>
                        <div class="alert alert-warning text-center" ng-show="exitSuccessfull">
                            Successfully Exited!
                        </div>--}}
                        <button type="button" class="btn btn-primary center-block" ng-click="getOutForeignTruck()"
                                data-dismiss="modal">Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------Exit Model----------------------------}}

    </div>
    {{--Main div end--}}
@endsection