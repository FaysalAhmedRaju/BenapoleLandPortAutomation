@extends('layouts.master')
@section('title', 'Truck Entry Form')

@section('script')

    {!!Html :: script('js/customizedAngular/truckEntry.js')!!}



    <style>
        #manifestTblb td{
            background-color: #dbd3ff;
        }

        .selectedRow{
            background-color:#dbd3ff!important;
        }
    </style>

@endsection


    @section('content')


        <div class="col-md-12" style="padding: 0;" ng-cloak=""  ng-app="truckEntryApp" ng-controller="truckEntryController" >

                <div class="col-md-4 col-md-offset-4">

                    <form class="form-inline" ng-submit="getSingleManifest(manf_id)">
                        <div class="form-group">

                            <input type="text" name="ManifestNo"  ng-model="manf_id" id="ManifestNo" class="form-control"
                                   placeholder="Manifest No.">

                        </div>

                       <span class="ok">@{{ searchFound }}</span>
                        <span class="error">@{{ searchNotFound }}</span>



                    </form>


                    <div ng-show="truckDivShow">
                        <span>@{{ totalTruck }} Trucks alreay entered with the manifest</span>
                    </div>
                    <br> <br>
                </div>

            <div class="col-md-4">
               {{-- <button type="button" class="btn btn-primary"  ng-click="todaysTruckEntryDetails()"><span
                            class="fa fa-calendar-o"></span> Todays' Entry
                </button>--}}

                 <a type="button" target="_blank" class="btn btn-primary"  href={{url('dailyTruckReportPdf')}} ><span
                            class="fa fa-calendar-o"></span> Todays' Entry Report
                </a>

            </div>


            <div class="col-md-12">

                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif


                <label class="alert-warning hidden">Something went wrong!</label>


            </div>


            <div class="col-md-9 col-md-offset-1" style="background-color: #dbd3ff; border-radius: 5px; padding: 10px 0 5px 10px;">

           <form  name="truckform">
               <table>
                    <tr>
                        <th>Manifest No : </th>
                        <td>
                            <input type="text" ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A|a)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/' required="required"  ng-model="ManifestNo" ng-change="checkManifest(ManifestNo)" name="ManifestNo" id="Manifest_no" class="form-control input-sm" placeholder="Manifest No.">
                            <span class="error" ng-show='truckform.ManifestNo.$error.pattern'>
                                Input like: 256/12 Or 256/A
                            </span>
                            <span class="error" ng-show="truckform.ManifestNo.$touched && !ManifestNo">
                              ManifestID is required
                            </span>

                            <span class="error" ng-show="truckExceedInManifest">Manifest is full</span>
                        </td>
                        <th>&nbsp; Manifest Date :</th>
                        <td>
                            <input type="text" ng-model="manifest_date" required="required" name="manifest_date" id="manifest_date" class="form-control datePicker input-sm" placeholder="Manifest date">

                            <span ng-show="truckform.manifest_date.$touched && !manifest_date" class="error">Select a date</span>
                        </td>
                        <th>&nbsp;Goods Name :</th>

                        <td style="width: 15em;">
                            <select class="form-control input-sm" name="goods_id" ng-model="goods_id"
                                    ng-options="good.id as good.cargo_name for good in allGoodsData ">

                                <option value="" selected="selected">Select Goods Name</option>
                            </select>
                            <span class="error" ng-show="truckform.goods_id.$dirty && truckform.goods_id.$touched && !goods_id ">
                             Select at least one goods
                            </span>

                        </td>
                    </tr>

<tr>
    <td colspan="6">&nbsp;</td>
</tr>


                    <tr>
                        <th>Truck Type :</th>
                        <td>
                            <input type="text" ng-model="truck_type" name="truck_type" id="truck_type" class="form-control input-sm" value="{{old('truck_type')}}"  placeholder="Truck Type">
                            <span class="error" ng-show="truckform.truck_type.$touched && !truck_type">
                             Truck Type is required
                            </span>
                        </td>

                        <th>&nbsp; Truck No :</th>
                        <td>
                            <input type="number" min="1" ng-model="truck_no" name="truck_no" id="truckNo" class="form-control input-sm" placeholder="Truck No" value="{{old('truck_no')}}">
                            <span class="error" ng-show="truckform.truck_no.$touched && !truck_no">
                             Truck No is required
                            </span>
                        </td>
                        <th> &nbsp;Driver Name :</th>
                        <td style="width: 15em;">
                            <input type="text" ng-model="driver_name" name="DriverName" id="driverName" class="form-control input-sm" placeholder="Driver Name" value="{{old('DriverName')}}">
                            <span class="error" ng-show="truckform.DriverName.$touched && !driver_name">
                             Driver Name is required
                            </span>
                        </td>


                    </tr>

                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>



                    <tr>


                        <th>Driver Card No :</th>
                        <td>
                            <input type="number" ng-model="driver_card" name="DriverCardNo" id="DriverCardNo" class="form-control input-sm" placeholder="Driver Card No." value="{{old('DriverCardNo')}}">

                            <span class="error" ng-show="truckform.DriverCardNo.$touched && !driver_card">
                             Driver Card No. is required
                            </span>
                        </td>


                        <th> &nbsp;Weight Bridge:</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" ng-init="weightment_flag=1"  ng-model="weightment_flag" ng-checked="true"  value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio"  ng-model="weightment_flag"  value="0" >No
                            </label>
                        </td>



                    </tr>
                    <tr>
                        <td colspan="3"></td>

                        <td colspan="1" class="text-center">
                            <br>
                            <button type="button" ng-click="saveData(truckform)" ng-hide="updateBtn" class="btn btn-primary btn-block center-block" ng-disabled="!ManifestNo||!truck_no||!truck_type||!weightment_flag||!manifest_date||!goods_id||!driver_card||!driver_name||truckExceedInManifest"><span class="fa fa-download"></span> Save</button>

                            <button type="button" ng-click="updateData(truckform)" ng-show="updateBtn"  class="btn btn-primary center-block"><span class="fa fa-download"></span> Update</button>

                        </td>
                        <td colspan="2">

                        </td>
                    </tr>

                   <tr>
                       <td class="text-center ok" colspan="6">

                           @{{savingSuccess }}
                           @{{ savingErro }}
                           @{{ updateSuccessMsg }}
                       </td>
                   </tr>
                </table>
                </form>
            </div>

<div class="clearfix"></div>


                 
        <div class="col-md-12 table-responsive">
                      <div id="manifestDetails" ng-hide="todaysEntryDiv">
                          <h5  class="text-center ok">Manifest Details:</h5>

                          <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                              <thead>

                              <tr>
                                  <td colspan="8" class="text-center" ng-if="dataLoading" >
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="{{URL::asset('/images/dataLoader.gif')}}" width="300" height="20" />
                            <br /> Please wait!
                        </span>
                                  </td>
                              </tr>

                              <tr>
                                  <th>Serial No.</th>
                                  <th>Truck No.</th>
                                  <th>Goods Name</th>
                                  <th>Mnifest No.</th>
                                  <th>Driver Card</th>
                                  <th>Action</th>


                              </tr>
                              </thead>

                              <tbody>

                              <tr ng-class="{selectedRow : truck.sl === idSelectedRow}"  dir-paginate="truck in allTrucksData|itemsPerPage:10">

                                  <td>@{{$index+1}}</td>
                                  <td>@{{truck.truck_type}}-@{{truck.truck_no}} </td>
                                  <td>@{{truck.cargo_name}}</td>
                                  <td>@{{truck.manifest}}</td>

                                  <td>@{{truck.driver_card}}</td>
                                  <td>
                                      <a class="btn btn-primary" ng-click="edit(truck)" data-target="#editTrucEntryModal" data-toggle="modal">Edit</a>
                                      <a class="btn btn-danger" ng-click="deleteConfirm(truck)" data-target="#deleteManifestConfirm" data-toggle="modal">Delete</a>

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


                          {{--<div ng-show="todaysEntryDiv">


                              <a href="" ng-click="generatePDF()" class="btn btn-primary pull-right">Today's Truck Repot</a>
                              <span class="clear-fix"><br></span>
                              <div id="todaysTruckEntry">

                                  <div class="text-center">
                                      <br>
                                      <h4 class="text-center black">Benapole Landport Authority</h4>
                                      <h6 class="black">Benapole, Jessor</h6>
                                      <h6 class="black">Truck Entry Registered</h6>


                                      <h5 class="text-center black">Todays' Trucks' Details:</h5>


                                      <p class="pull-right">Time: <b>@{{ ReportTime|date:'d/M/yy  -h:mm a' }}</b></p>

                                  </div>

                              <table class="table table-bordered">
                                  <thead>

                                 --}}{{-- <tr>
                                      <td colspan="8" class="text-center" ng-if="dataLoading" >
                                <span style="color:green; text-align:center; font-size:15px">
                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="300" height="20" />
                                    <br /> Please wait!
                                </span>
                                      </td>
                                  </tr>--}}{{--

                                 <tr>
                                     <th>Serial No.</th>
                                     <th>Truck No.</th>
                                     <th>Goods Name</th>
                                     <th>Mnifest No.</th>
                                     <th>Driver Card</th>
                                     <th>Weigh Bridge</th>



                                 </tr>
                                  </thead>

                                  <tbody>

                                  <tr dir-paginate="truck in allTrucksData |filter:manf_id|orderBy:true:'sl'|itemsPerPage:2" >

                                      <td>@{{$index+1}}</td>
                                      <td>@{{truck.truck_type}}-@{{truck.truck_no}} </td>
                                      <td>@{{truck.cargo_name}}</td>
                                      <td>@{{truck.manf_id}}</td>
                                      <td>@{{truck.driver_card}}</td>
                                      <td>@{{truck.weightment_flag|weightmentFilter}}</td>

                                  </tr>

                                  </tbody>

                                  <tfoot>

                                  <tr>
                                      <td colspan="6">
                                          <div class="">
                                             <p>Total: @{{ ReportTotal }}</p>
                                          </div>
                                      </td>
                                  </tr>
                                  </tfoot>


                              </table>
                              </div>
                            <div class="text-center">
                                <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true">
                                </dir-pagination-controls>

                            </div>


                          </div>--}}

                </div>

            <!--Modal for Delete confirm -->


            <div class="modal fade" id="deleteManifestConfirm" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Are you sure to delete Manifest No: <b>@{{ d.ManifestNo }}</b> </h4>
                        </div>
                        <div class="modal-body">

                            <h5 class="text-center">Truck No: <b>@{{ d.truck_type }}-@{{ d.truck_no }}</b> </h5>

                            <a href=""class="btn btn-primary center-block pull-right" ng-click="deleteTruck()" >Yes</a>

                            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>

                        </div>
                        <div class="modal-footer">
                            <span ng-show="deleteFailMsg">Something wrong!</span>
                            <div id="deleteSuccess" class="alert alert-warning text-center" ng-show="deleteSuccessMsg">
                                Successfully deleted!
                            </div>

                            <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>


            <!--Modal for Delete confirm  END -->




        </div>

        </div>
        {{--Main div end--}}
@endsection


