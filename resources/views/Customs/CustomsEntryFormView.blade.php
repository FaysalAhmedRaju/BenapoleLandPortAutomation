@extends('layouts.master')
@section('title', 'Customs Entry Form')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

    {!!Html :: script('js/customizedAngular/CustomsEntryForm.js')!!}

@endsection



@section('content')
    <div class="col-md-12"  style="padding: 0;" ng-cloak=""  ng-app="cnfApp" ng-controller="CustomsPanelController">

        <div class="col-md-4 col-md-offset-4">
            {{--<br>--}}

            <form class="form-inline" ng-submit="search(ManifestNo)">
                <div class="form-group">
                    {{--ng-click="search(ManifestNo)"--}}
                    <input type="text" name="ManifestNo" ng-model="ManifestNo"  id="ManifestNo" class="form-control" placeholder="Search Manifest No" ng-keydown="keyBoard($event)">
                </div>
                <span class="ok">@{{ searchFound }}</span>
                <span class="error">@{{ searchNotFound }}</span>
                {{--<button type="submit" class="btn btn-primary"  hidden="hidden" ng-click="search(ManifestNo)"><span class="fa fa-search"></span> Search Manifest</button>--}}
            </form>


            <div class="clearfix"></div>
            <div class="col-md-4 col-md-offset-8" >
                <a href="{{ route('customs-get-todays-customs-posting-report') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Manifest Entry</button></a>
            </div>
            <br><br>

            <div ng-show="truckDivShow">
                <span ng-style="truckDivShowColor">@{{ totalTruck }} Trucks Entered Already.</span> <br>
                {{--<span class="error">@{{ manifestFull }}</span>--}}
            </div>
            {{--<p >Truck NO: @{{ t_truck_no }}</p>--}}
            {{--<span>@{{ truckNoEdit }} Is Selected.</span>--}}
            {{--<p>@{{ notFound }}</p>--}}

        </div>





        {{----------------------------------------------------------------------------------------------------------------------------------------}}

        <div class="col-md-11 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">

            {{--------------------------------------Complete Form Inside The DIV----------------------------------------------------}}

            <form name="customEntryForm" id="MonthlyDedudction" novalidate>
            <table>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Manifest NO<span class="mandatory">*</span>:</th>
                    <td>

                        <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required="required" ng-model="m_manifest"  name="m_manifest" id="m_manifest" ng-hide="hidemanifestWhenUpdatebtnClick" ng-keydown="keyBoard($event)">

                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Manifest Date<span class="mandatory">*</span>:</th>
                    <td>

                        <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" type="text" class="form-control datePicker"  ng-model="m_manifest_date" name="m_manifest_date" id="m_manifest_date" ng-hide="hidemanifestWhenUpdatebtnClick">

                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Marks & No<span class="mandatory">*</span>:</th>
                    <td>
                        <input class="form-control"  ng-model="m_marks_no" style="width: 190px;" name="m_marks_no" id="m_marks_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>

                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Description of Goods<span class="mandatory">*</span>:</th>
                    <td style="width: 175px;">
                        <select ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" name ="m_good_id" ng-model="m_good_id" id="m_good_id" ng-hide="hidemanifestWhenUpdatebtnClick" ng-options="good.id as good.cargo_name for good in allGoodsDataCnf ">
                            <option value="" selected="selected">Select Goods Name</option>
                        </select>

                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Gross Weight<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_gweight" name="m_gweight" id="m_gweight" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Net Weight<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text"  ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_nweight" name="m_nweight" id="m_nweight" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>

                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Package NO<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_package_no" name="m_package_no" id="m_package_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Package Type<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_package_type" name="m_package_type" id="m_package_type" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">CNF Value<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_cnf_value" name="m_cnf_value" id="m_cnf_value" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>

                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}
                <th ng-hide="hidemanifestWhenUpdatebtnClick">VAT No<span class="mandatory">*</span>:</th>
                <td>
                    <input style="width: 190px;" type="text" ng-disabled="manif_posted_btn_disable" class="form-control" ng-model="m_vat_id" name="m_vat_id" id="m_vat_id" ng-blur="getVatsData()" ng-hide="hidemanifestWhenUpdatebtnClick" >
                </td>

                <th ng-hide="hidemanifestWhenUpdatebtnClick ||!m_vat_name">Importer Name<span class="mandatory">*</span>:</th>
                <td colspan="3"  ng-hide="!m_vat_name">
                    <input type="text" ng-disabled="vatname" ng-disabled="manif_posted_btn_disable"  class="form-control" ng-model="m_vat_name" name="m_vat_name" id="m_vat_name" ng-hide="hidemanifestWhenUpdatebtnClick" >
                </td>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Name & Address of Exporter<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_exporter_name_addr" name="m_exporter_name_addr" id="m_exporter_name_addr" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>


                    <th ng-hide="hidemanifestWhenUpdatebtnClick">L.C No<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_lc_no" name="m_lc_no" id="m_lc_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">L.C Date<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control datePicker" ng-model="m_lc_date" name="m_lc_date" id="m_lc_date" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>


                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}

                <tr>


                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Indian Bill NO<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="m_ind_be_no" name="m_ind_be_no" id="m_ind_be_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Indian Bill Date<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control datePicker" ng-model="m_ind_be_date" name="m_ind_be_date" id="m_ind_be_date" ng-hide="hidemanifestWhenUpdatebtnClick" >
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck Gross Weight<span class="mandatory">*</span>:</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_gweight" name="t_gweight" id="t_gweight"  ng-hide="showWhenUpdatebtnClick" >
                    </td>

                </tr>


                <tr>


                    <th ng-hide="showWhenUpdatebtnClick">Truck Net Weight<span class="mandatory">*</span>:</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_nweight" name="t_nweight" id="t_nweight"  ng-hide="showWhenUpdatebtnClick" >
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck Type<span class="mandatory">*</span>:</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_truck_type" name="t_truck_type" id="t_truck_type"  ng-hide="showWhenUpdatebtnClick" >
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck NO<span class="mandatory">*</span>:</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_truck_no" name="t_truck_no" id="t_truck_no"  ng-hide="showWhenUpdatebtnClick" >
                    </td>

                </tr>

                <tr>

                    <th ng-hide="showWhenUpdatebtnClick">Driver Name<span class="mandatory">*</span>:</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_driver_name" name="t_driver_name" id="t_driver_name"  ng-hide="showWhenUpdatebtnClick" >
                    </td>

                    <th ng-hide="showWhenUpdatebtnClick">Driver Card NO<span class="mandatory">*</span>:</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_driver_card" name="t_driver_card" id="t_driver_card"  ng-hide="showWhenUpdatebtnClick" >
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Weight Bridge<span class="mandatory">*</span>:</th>
                    <td>
                        <label class="radio-inline">
                            <input type="radio" ng-init="t_weightment_flag=1"  ng-model="t_weightment_flag" ng-checked="true"  value="1">Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio"  ng-model="t_weightment_flag"  value="0" >No
                        </label>
                    </td>

                </tr>





                <tr>
                    <td colspan="6" class="text-center">
                        <br>
                        <button type="button" ng-click="save()" ng-if="!updateBtn" class="btn btn-primary">Save</button>
                        {{--ng-hide="updateBtn"--}}
                        <button type="button" ng-click="update(truckForm)" ng-if="updateBtn" class="btn btn-primary">Update</button>
                        {{--ng-show="updateBtn"--}}

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
        {{-----------------------------------------------------------------------------------------------------------------------------------------------}}
        {{--</div>--}}

        <div class="clearfix"></div>
        <div class="col-md-12 table-responsive">
            <h5 class="text-center" style="color: blue"><b>Manifest Information</b></h5>

            <table class="table table-bordered" style="color: #8A6343" ng-show="table" >


                {{--<caption><h3><b>Please Insert other fields of Manifest Id:  @{{ManifestNo}}<b></h3></caption>--}}
                <thead >
                <tr>
                    <th>Serial No.</th>
                    <th>Truck NO.</th>
                    <th>Gross Weight.</th>
                    <th>Net Weight.</th>
                    <th>Driver Name</th>
                    <th>Driver Card NO</th>
                    <th>Manifest NO</th>
                    <th>Action</th>

                </tr>
                </thead>

                <tbody>


                <tr ng-repeat="truck in allManifestData"   ng-style="{'background-color':(truck.t_id == selectedStyle?'#dbd3ff':'')}">

                    <td>@{{$index+1}}</td>
                    <td>@{{truck.t_truck_type}}-@{{truck.t_truck_no}} </td>
                    <td>@{{truck.t_gweight}}</td>
                    <td>@{{truck.t_nweight}}</td>
                    <td>@{{truck.t_driver_name}}</td>
                    <td>@{{truck.t_driver_card}}</td>
                    <td>@{{ truck.m_manifest }}</td>

                    <td>
                        <a class="btn btn-primary"  ng-click="edit(truck)" data-target="#editTrucEntryModal" data-toggle="modal">Edit</a>
                        <a class="btn btn-primary" ng-click="deteleConfirm(truck)" data-target="#deleteTrucEntryModal" data-toggle="modal">Delete</a>
                    </td>

                </tr>

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="6" class="text-center">

                        <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>


            </table>


            <!------------------------------------------------------------ Delete div start here...---------------------------------------- :) -->
            <div class="modal fade" id="deleteTrucEntryModal" role="dialog">

                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Are you sure to delete Manifest No: <b>@{{ m_manifest }}</b> </h4>
                        </div>
                        <div class="modal-body">

                            <h5 class="text-center">Truck No: <b>@{{ t_truck_type }}-@{{ t_truck_no }}</b> </h5>

                            <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteTruck()" >Yes</a>

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
            <!--------------------------------- Delete div end here :)---------------------------------------------------------- -->

        </div>


    </div>

    <script>
        $( function() {
            $( "#truckentry_datetime" ).datepicker(
                {

                    dateFormat: 'yy-mm-dd',
                }
            );

        } );
    </script>
@endsection


