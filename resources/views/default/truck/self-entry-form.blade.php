@extends('layouts.master')
@section('title', 'Self Entry Form')

@section('script')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!!Html :: script('js/customizedAngular/truck/self-entry.js')!!}
    <script type="text/javascript">
        var role_name = {!!  json_encode(Auth::user()->role->name) !!};
    </script>
    {{--{!!Html :: script('js/lodash.js')!!}--}}
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
    <div class="col-md-12" style="padding: 0;" ng-cloak="" ng-app="selfOrTrucktorEntryApp" ng-controller="selfOrTrucktorEntryController">

        <div class="col-md-11 col-md-offset-1" style=" padding-left: 20px; /*background-color:  red*/ ">




            <div class="col-md-5" style="/*background-color:  yellow*/">
                <form action="{{ route('truck-date-wise-truck-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <select title=""  ng-init="vehile_type_flage_pdf = '11'" style="width: 150px"  name="vehile_type_flage_pdf"  ng-model="vehile_type_flage_pdf"  class="form-control input-sm" >

                        {{--<optgroup label="(2). Self">--}}
                            <option  value="11">Chassis(Self)</option>
                            <option  value="12">Trucktor(Self)</option>
                            <option   value="13">Bus</option>
                            <option   value="14">Three Wheller</option>
                            <option   value="15">Rickshaw</option>
                            <option value="16">Car(self)</option>
                            <option value="17">Pick Up(Self)</option>
                            <option value="18">Trailor(Self)</option>
                            <option value="18">Trailor(Self)</option>
                        {{--</optgroup>--}}
                    </select>
                    <div class="input-group">
                        <input type="text" style="width: 120px"  class="form-control datePicker" ng-model="dateWiseReport"
                               name="date" id="date" placeholder="Select Entry Date">
                        <div class="input-group-btn">

                            <button ng-disabled="!dateWiseReport"  style="width: 100px" type="submit" class="btn btn-primary">
                                Enrty Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
                <div class="col-md-3">
                    <a type="button" target="_blank" class="btn btn-primary" href={{route('truck-incomplete-manifest-list-report')}} >
                        <span class="fa fa-calendar-o"></span> Incomplete Manifest
                    </a>
                </div>

        </div>
        <br><br> <br>

        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px;">

            <div class="col-md-12 text-center" >


                <form class="form-inline" ng-submit="getSingleManifest(manf_id)">
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
                    <span>@{{ totalTruck }} self already entered with the manifest</span>
                </div>

            </div>


            <form name="truckform" id="truckform" novalidate >
                <table id="truckformTbl">

                    <tr>
                        <td class="text-center" colspan="6">
                            <h4 class="ok"> Self Entry Form</h4>
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <br>
                        </td>
                    </tr>

                    <tr>

                        <th>Vehicle Type:&nbsp;&nbsp;</th>
                        <td>
                            <select title="" required ng-init="vehile_type_flage = '11'" ng-change="vehicleTypeChange(vehile_type_flage)" ng-model="vehile_type_flage"  class="form-control input-sm" >

                                {{--<optgroup label="(2). Self">--}}
                                    <option  value="11">Chassis(Self)</option>
                                    <option  value="12">Tractor(Self)</option>
                                    <option   value="13">Bus</option>
                                    <option   value="14">Three Wheller</option>
                                    <option   value="15">Rickshaw</option>
                                    <option value="16">Car(self)</option>
                                    <option value="17">Pick Up(self)</option>
                                    <option value="18">Trailor(Self)</option>
                                {{--</optgroup>--}}
                            </select>

                            <span class="error" ng-show="submitted && !vehile_type_flage">
                            Vehicle Type is required
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>

                    <tr ng-hide="hideManifestDetailsInput"  >
                        <th>Manifest No<span class="mandatory">*</span> :</th>
                        <td>
                            <input ng-class="{'invalid':submitted && !ManifestNo,'valid':submitted && ManifestNo}"
                                   type="text"
                                   {{--ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1})[\/]{1}[0-9]{4}$/'--}}
                                   {{--ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A)]{1}|[(A-B-B-Z)]{3})[\/]{1}[0-9]{4}$/'--}}
                                   ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6}|[0-9CH]{2,6})[\/]{1}([0-9]{1,3}|[(A)]{1}|[A]{1}[\-]{1}[B-Z]{1})[\/]{1}[0-9]{4}$/'
                                   required="required" {{--ng-disabled="disbleManifestNoInpForEditMode"--}}
                                   ng-model="ManifestNo" name="ManifestNo" id="Manifest_no"
                                   class="form-control input-sm" placeholder="Manifest No."
                                   ng-model-options="{allowInvalid: true}">
                            <span class="error" ng-show='truckform.ManifestNo.$error.pattern'>
                                Input like: 256/12/2017 Or 256/A/2017 Or P256/2/2017 Or 256/A-E/2017
                            </span>
                            <span class="error"
                                  ng-show="submitted && !ManifestNo && !truckform.ManifestNo.$error.pattern">
                              Manifest No is required
                            </span>

                            <span class="error" ng-show="truckExceedInManifest">Manifest is full</span>
                        </td>
{{--------------------------------------------------------------------------------------------- Type No start ----------------------------------------------------}}
                        <th>&nbsp;Type <span class="mandatory">*</span> :</th>
                        <td>
                            <input type="text" ng-model="truck_type" name="truck_type" id="truck_type" class="form-control input-sm" placeholder="Type">
                            <span class="error" ng-show="submitted && !truck_type">
                              Type is required
                            </span>
                        </td>
                        <th>&nbsp;No <span class="mandatory">*</span> :</th>

                        <td>
                            <input type="text" min="1"   required="required" ng-model="truck_no" name="truck_no" id="truckNo"
                                   class="form-control input-sm" placeholder="No.">
                            <span class="error" ng-show="submitted && !truck_no">
                              No is required
                            </span>
                        </td>
 {{--------------------------------------------------------------------------------------------- Type No End ----------------------------------------------------}}
                        <th></th>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>


                    <tr>

                        <th>Driver Card No<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="text" required="required" ng-init="driver_card='--'" ng-model="driver_card" name="driver_card"
                                   id="driver_card" class="form-control input-sm" placeholder="Driver Card No.">
                            <span class="error" ng-show="submitted && !driver_card">
                             Driver Card No. is required
                            </span>
                        </td>


                        <th> &nbsp;Driver Name:</th>
                        <td style="width: 15em;">
                            <input type="text" ng-model="driver_name" name="DriverName" id="driverName"
                                   class="form-control input-sm" placeholder="Driver Name">
                            <span class="error" ng-show="submitted && !driver_name">
                             Driver Name is required
                            </span>
                        </td>


                        <th>&nbsp;Goods Name <span class="mandatory">*</span>:</th>

                        <td style="width: 15em; vertical-align: top" rowspan="5">


                            <tags-input ng-model="goods_id"
                                        max-tags="5" required="required"
                                        enable-editing-last-tag="true"
                                        display-property="cargo_name"
                                        placeholder="Type Goods Name"
                                        replace-spaces-with-dashes="false"
                                        add-from-autocomplete-only="false"
                                        on-tag-added="tagAdded($tag)"
                                        on-tag-removed="tagRemoved($tag)">

                                <auto-complete source="loadGoods($query)"
                                               min-length="0"
                                               debounce-delay="0"
                                               max-results-to-show="10">

                                </auto-complete>

                            </tags-input>
                            <span ng-show="submitted && (!goods_id || goods_id.length==0)" class="error">Choose at least one goods!</span>

                        </td>


                    </tr>

                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>


                    <tr {{-- style="background-color: red"--}}>

                        <th> &nbsp;Weight Bridge:</th>
                        <td  style="" {{--colspan="2"--}}>
                            <label class="radio-inline">
                                <input type="radio" ng-init="weightment_flag=1" ng-model="weightment_flag"
                                       ng-checked="true" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="weightment_flag" value="0">No
                            </label>
                        </td>

                        <th> &nbsp;Received Yard: </th>
                        <td>
                            <select title="" class="form-control"  ng-init="t_posted_yard_shed='105'"  name="t_posted_yard_shed" ng-model="t_posted_yard_shed">
                                @foreach($yards as $k=>$v)
                                    <option value="{{$v->id}}">{{$v->yard_shed_name}}</option>
                                @endforeach
                            </select>

                          <label>
                              <b style="color: green">@{{ message_1 }} @{{ message_2 }} @{{ yard_count_no }}</b>
                          </label>
                        </td>

                        @if(Auth::user()->role->name == 'C&F')
                            <th ng-if="role_name=='C&F'"> &nbsp;Entry Date:</th>
                            <td style="width: 15em;" ng-show="role_name=='C&F'">
                                <input type="text" ng-model="truckentry_datetime" name="truckentry_datetime"
                                       id="truckentry_datetime" class="form-control input-sm datePicker"
                                       placeholder="Choose Date" required="required">
                                <span class="error" ng-show="submitted && !truckentry_datetime">
                                    Entry Date is required
                                </span>
                            </td>
                        @endif








                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    {{--<tr --}}{{--style="background-color: bisque"--}}{{-->--}}
                        {{--<th ng-show="yard_shed" --}}{{--style="background-color: red"--}}{{-->Receive Datetime <span class="mandatory">*</span>:</th>--}}
                        {{--<td ng-show="yard_shed" --}}{{--style="background-color: yellow"--}}{{-->--}}
                            {{--<input class="form-control datePicker" type="text" name="receive_datetime"--}}
                                   {{--id="receive_datetime" ng-model="receive_datetime"  placeholder="Receive Datetime" --}}{{--ng-disabled="show"--}}{{-->--}}
                            {{--<span class="error" ng-show="datetimeIsRequired">--}}
                            {{--Receive Datetime is Required--}}
                            {{--</span>--}}
                        {{--</td>--}}

                    {{--</tr>--}}

                    <tr>
                        <td colspan="3"></td>

                        <td colspan="1" class="text-center">
                            <br>
                            {{--<input type="button" ng-click="saveData(truckform)" ng-hide="updateBtn" value="Save" class="btn btn-primary btn-block center-block">--}}
                            <button type="button" ng-click="saveData(truckform)" ng-if="!updateBtn"
                                    class="btn btn-primary">
                                <span class="fa fa-file"></span>
                                Save
                            </button>
                            <button type="button" ng-click="updateData(truckform)" ng-if="updateBtn"
                                    class="btn btn-success center-block">
                                <span class="fa fa-download"></span>
                                Update
                            </button>
                        </td>
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <td colspan="2"></td>
                        <td class="text-center" colspan="3">

                            <div id="success" class="col-md-12 alert alert-success" ng-show="successMsg">
                                Successfully @{{ successMsgTxt }}!
                            </div>

                            <div id="error" class="col-md-12 alert alert-danger" ng-show="errorMsg">
                                @{{ errorMsgTxt }}!
                            </div>
                        </td>
                        <td colspan="1"></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="clearfix"></div>


        <div class="col-md-12 table-responsive">
            <div id="manifestDetails" ng-hide="todaysEntryDiv">
                <h6 class="ok"><b>Manifest:</b> @{{ allTrucksData[0].manifest }}</h6>
                <h6>
                    <b>Goods:</b>  <span ng-repeat="cargo in  allTrucksData[0].cargo_name.split('?')">
                            <span class="label label-primary" style="margin-right:5px;">
                                @{{cargo}}
                            </span>
                        </span>
                </h6>

                <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                    <thead>

                    <tr>
                        <td colspan="7" class="text-center" ng-if="dataLoading">
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="/img/dataLoader.gif" width="300" height="20"/>
                            <br/> Please wait!
                        </span>
                        </td>
                    </tr>

                    <tr>
                        <th>S/L</th>
                        <th>Entry S/L</th>
                        <th>Entry Date</th>
                        <th>Truck No.</th>
                        <th>Driver Card</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr ng-class="{selectedRow : truck.t_id === idSelectedRow}"
                        dir-paginate="truck in allTrucksData|itemsPerPage:30">

                        <td>@{{$index+1}}</td>
                        <td>@{{ truck.entry_sl }}</td>
                        <td>@{{ truck.truckentry_datetime | stringToDate:'medium' }}</td>
                        <td>@{{truck.truck_type}}-@{{truck.truck_no}} </td>
                       {{-- <td style="width: 120px;"> --}}{{--@{{truck.cargo_name}}--}}{{--
                            <span ng-repeat="cargo in truck.cargo_name.split('?')">
                            <span class="label label-primary" style="margin-right:5px;">
                                @{{cargo}}
                            </span>
                        </span>


                        </td>--}}

                        <td>@{{truck.driver_card}}</td>
                        <td style="width: 150px;">
                            <a class="btn btn-primary btn-sm" ng-click="edit(truck)" data-target="#editTrucEntryModal"
                               data-toggle="modal">Edit</a>
                            <a class="btn btn-danger btn-sm" ng-click="deleteConfirm(truck)"
                               data-target="#deleteManifestConfirm" data-toggle="modal">Delete</a>

                        </td>
                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="7" class="text-center">

                            <dir-pagination-controls max-size="5"
                                                     direction-links="true"
                                                     boundary-links="true">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    <tr ng-if="loadingerror">
                        <td colspan="7">
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


    </div>
    {{--Main div end--}}
    <script type="text/javascript">
        $('#receive_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
    </script>
@endsection


