@extends('layouts.master')
@section('title', 'Vehicle Entry/Exit Form')

@section('script')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    {!!Html :: script('js/customizedAngular/truck/truck-entry.js')!!}
    <script type="text/javascript">
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>
    <script src="/js/lodash.js"></script>

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
    <div class="col-md-12" style="padding: 0;" ng-cloak="" ng-app="truckEntryApp" ng-controller="truckEntryController">

        <div class="col-md-11 col-md-offset-1" style=" padding-left: 20px; /*background-color:  red*/ ">


            <div class="col-md-5" style="/*background-color:  yellow*/">
                <form action="{{ route('truck-date-wise-truck-entry-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}

                    <select title="" ng-init="vehile_type_flage_pdf = '1'" style="width: 150px"
                            name="vehile_type_flage_pdf" ng-model="vehile_type_flage_pdf" class="form-control input-sm">
                        {{--<optgroup label="(1). Truck">--}}
                            <option value="1" selected>Truck</option>
                            <option value="2">Chassis(on Trailer)</option>
                            <option value="3">Tractor(on Trailer)</option>
                            <option value="4">Covered Van</option>
                            <option value="5">Lorry</option>
                            <option value="6">Mini Pickup</option>
                            <option value="7">Prime Mover</option>
                            <option value="8">Tanker</option>
                            <option value="9">Vehicle in CBU</option>
                       {{-- </optgroup>--}}
                        {{--<optgroup label="(2). Self">--}}
                            <option value="11">Chassis(Self)</option>
                            <option value="12">Trucktor(Self)</option>
                            <option value="13">Bus</option>
                            <option value="14">Three Wheller</option>
                            <option value="15">Rickshaw</option>
                            <option value="16">Car(Self)</option>
                            <option value="17">Pick Up(Self)</option>
                            <option value="18">Trailor(Self)</option>
                        {{--</optgroup>--}}
                    </select>
                    <div class="input-group">
                        <input type="text" style="width: 120px" class="form-control datePicker"
                               ng-model="dateWiseReport"
                               name="date" id="date" placeholder="Select Entry Date">
                        <div class="input-group-btn">

                            <button ng-disabled="!dateWiseReport" style="width: 100px" type="submit"
                                    class="btn btn-primary">
                                Enrty Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4" style="">
                <form action="{{ route('truck-date-wise-truck-exit-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker" ng-model="exitDate"
                               name="date" id="exitDate" placeholder="Select Exit Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button ng-disabled="!exitDate" type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Exit Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if(Auth::user()->role->id ==4 || Auth::user()->role->id == 12)
                <div class="col-md-3">
                    <a type="button" target="_blank" class="btn btn-primary"
                       href={{route('truck-incomplete-manifest-list-report')}} >
                        <span class="fa fa-calendar-o"></span> Incomplete Manifest
                    </a>

                </div>
            @endif
        </div>
        <br><br> <br>

        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px;">

            <div class="col-md-12 text-center">


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
                    <span>@{{ totalTruck }} Trucks alreay entered with the manifest</span>
                </div>

            </div>


            <form name="truckform" id="truckform" novalidate>
                <table id="truckformTbl">

                    <tr>
                        <td class="text-center" colspan="6">
                            <h4 class="ok">Vehicle Entry/Exit Form</h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                        </td>
                    </tr>

                    <tr>

                        <th>Vehicle Type:&nbsp;&nbsp;</th>
                        <td>
                            <select title="" ng-init="vehile_type_flage = '1'" ng-model="vehile_type_flage"
                                    class="form-control input-sm" onkeydown="EnterToTab(Manifest_no)">
                                {{--<optgroup label="(1). Truck">--}}
                                    <option value="1" selected>Truck</option>
                                    <option value="2">Chassis(on Trailer)</option>
                                    <option value="3">Tractor(on Trailer)</option>
                                    <option value="4">Covered Van</option>
                                    <option value="5">Lorry</option>
                                    <option value="6">Mini Pickup</option>
                                    <option value="7">Prime Mover</option>
                                    <option value="8">Tanker</option>
                                    <option value="9">Vehicle in CBU</option>
                                {{--</optgroup>--}}
                            </select>
                        </td>

                        @if(Session::get('PORT_ID')!=1)

                            <th>Country:&nbsp;&nbsp;</th>
                            <td>
                                <select title="" ng-init="country_id = '1'" ng-model="country_id"
                                        class="form-control input-sm">
                                    @if(count($countryList)>0)
                                        <option value="0" selected>Selecet Country</option>
                                        @foreach($countryList as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->country_name}}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>

                    <tr ng-hide="hideManifestDetailsInput">
                        <th>Manifest No<span class="mandatory">*</span> :</th>
                        <td>
                            <input ng-class="{'invalid':submitted && !ManifestNo,'valid':submitted && ManifestNo}"
                                   type="text"
                                   {{--ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1})[\/]{1}[0-9]{4}$/'--}}
                                   {{--ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A)]{1}|[(A-B-B-Z)]{3})[\/]{1}[0-9]{4}$/'--}}
                                   ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6}|[0-9CH]{2,6})[\/]{1}([0-9]{1,3}|[(A)]{1}|[A]{1}[\-]{1}[B-Z]{1})[\/]{1}[0-9]{4}$/'
                                   required="required"
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
                            <input type="text" ng-model="truck_type" name="truck_type" id="truck_type"
                                   class="form-control input-sm" placeholder="Type">
                            <span class="error" ng-show="submitted && !truck_type">
                              Type is required
                            </span>
                        </td>
                        <th>&nbsp;No <span class="mandatory">*</span> :</th>

                        <td>
                            <input type="text" min="1" required="required" ng-model="truck_no" name="truck_no"
                                   id="truckNo"
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
                        <td colspan="6">&nbsp;</td>
                    </tr>


                    <tr>
                        <th>Weight:<span class="mandatory">*</span> :</th>
                        <td style="width: 15em;">
                            <input type="number" ng-model="truck_weight" name="truck_weight" id="truck_weight"
                                   class="form-control input-sm" placeholder="Truck Weight" required="required">
                            <span class="error" ng-show="submitted && !truck_weight">
                             Weight is required
                            </span>
                        </td>

                        <th> &nbsp;Package:<span class="mandatory">*</span> :</th>
                        <td style="width: 15em;">
                            <input type="text" ng-model="truck_package" name="truck_package" id="truck_package"
                                   class="form-control input-sm" placeholder="Truck Package" required="required">
                            <span class="error" ng-show="submitted && !truck_package">
                             Package is required
                            </span>
                        </td>

                        <th>&nbsp;Driver Card No<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="text" required="required" ng-model="driver_card" name="driver_card"
                                   id="driver_card" class="form-control input-sm" placeholder="Driver Card No.">
                            <span class="error" ng-show="submitted && !driver_card">
                             Driver Card No. is required
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>


                    <tr>
                        <th>Driver Name:</th>
                        <td style="width: 15em;">
                            <input type="text" ng-model="driver_name" name="DriverName" id="driverName"
                                   class="form-control input-sm" placeholder="Driver Name">
                            <span class="error" ng-show="submitted && !driver_name">
                             Driver Name is required
                            </span>
                        </td>


                        <th>&nbsp;Goods Name <span class="mandatory">*</span>:</th>
                        <td style="width: 15em; vertical-align: top" rowspan="">
                            <tags-input ng-model="goods_id"
                                        max-tags="5" required="required"
                                        enable-editing-last-tag="true"
                                        display-property="cargo_name"
                                        placeholder="Type Goods Name"
                                        replace-spaces-with-dashes="false"
                                        add-from-autocomplete-only="false"
                                        on-tag-added="tagAdded($tag)"
                                        on-tag-removed="tagRemoved($tag)"
                                        onkeydown="EnterToTab(weightment_flag)">

                                <auto-complete source="loadGoods($query)"
                                               min-length="0"
                                               debounce-delay="0"
                                               max-results-to-show="10">

                                </auto-complete>

                            </tags-input>
                            <span ng-show="submitted && (!goods_id || goods_id.length==0)" class="error">Choose at least one goods!</span>
                        </td>
                        <th> &nbsp;Weight Bridge:</th>
                        <td style="">
                            <label class="radio-inline">
                                <input type="radio" ng-init="weightment_flag=1" ng-model="weightment_flag"
                                       ng-checked="true" value="1" id="weightment_flag" weightment_flag onkeydown="EnterToTab(weightment_flag1)">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="weightment_flag" value="0" id="weightment_flag1" onkeydown="EnterToTab(saveBtn)">No
                            </label>
                        </td>




                    </tr>

                        @if(Auth::user()->role->name == 'C&F')
                        <tr>
                            <th ng-if="role_name=='C&F'"> &nbsp;Entry Date:</th>
                            <td style="width: 15em;" ng-show="role_name=='C&F'">
                                <input type="text" ng-model="truckentry_datetime" name="truckentry_datetime"
                                       id="truckentry_datetime" class="form-control input-sm datePicker"
                                       placeholder="Choose Date" required="required">
                                <span class="error" ng-show="submitted && !truckentry_datetime">
                                    Entry Date is required
                                </span>
                            </td>
                        </tr>
                        @endif

                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    @if(Auth::user()->role_id==11)

                        <hr> For Maintencnce
                        <tr>

                            <th> Entry Date:</th>
                            <td>
                                <input type="text" ng-model="truckentry_datetime" name="truckentry_datetime"
                                       class="form-control input-sm datetimepicker" placeholder="Choose Date"
                                       required="required">
                            </td>

                        </tr>
                    @endif


                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>

                        <td colspan="1" class="text-center">
                            <br>
                            {{--<input type="button" ng-click="saveData(truckform)" ng-hide="updateBtn" value="Save" class="btn btn-primary btn-block center-block">--}}
                            <button type="button" ng-click="saveData(truckform)" ng-if="!updateBtn"
                                    class="btn btn-primary" id="saveBtn">
                                <span class="fa fa-file"></span>
                                Save
                            </button>
                            <button type="button" ng-click="updateData(truckform)" ng-if="updateBtn"
                                    class="btn btn-success center-block" id="saveBtn">
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
                    <b>Goods:</b> <span ng-repeat="cargo in  allTrucksData[0].cargo_name.split('?')">
                            <span class="label label-primary" style="margin-right:5px;">
                                @{{cargo}}
                            </span>
                        </span>
                </h6>

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
                        <th>S/L</th>
                        <th>Entry S/L</th>
                        <th>Entry Date</th>
                        <th>Truck No.</th>
                        <th>Driver Card</th>
                        <th>Weight</th>
                        <th>Package</th>
                        <th>Action</th>
                        {{--Exit--}}

                        @if(Auth::user()->role->name == 'Truck')
                            <th>Exit</th>
                            {{--Exit--}}
                        @endif

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
                        <td>@{{ truck.truck_weight }}</td>
                        <td>@{{ truck.truck_package }}</td>
                        <td style="width: 150px;">
                            <a class="btn btn-primary btn-sm" ng-click="edit(truck)" data-target="#editTrucEntryModal"
                               data-toggle="modal">Edit</a>
                            <a class="btn btn-danger btn-sm" ng-click="deleteConfirm(truck)"
                               data-target="#deleteManifestConfirm" data-toggle="modal">Delete</a>

                        </td>
                        {{--Exit--}}
                        <td ng-if="!truck.out_date">
                            <a class="btn btn-success btn-sm" ng-click="exitDetails(truck)" data-target="#ExitModal"
                               data-toggle="modal">Exit</a>
                        </td>
                        <td ng-if="truck.out_date" style="color: red;">
                            <br>@{{truck.out_date  | stringToDate:'medium'}}
                        </td>
                        {{--Exit--}}
                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="9" class="text-center">

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
    <script type="text/javascript">
        $('.datetimepicker').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });

        $(document).on('keyup keydown', 'select', function (e) {
            shifted = e.shiftKey;
        });

        function EnterToTab(next_input){
            console.log('asdasd');
            if (event.keyCode==13) {
                event.preventDefault();
                var nextInput = next_input;
                if (nextInput) {
                    nextInput.focus();
                }
                console.log(nextInput);
                if(nextInput == saveBtn) {
                    document.getElementById("saveBtn").click();
                }
            }

        }
    </script>
@endsection


