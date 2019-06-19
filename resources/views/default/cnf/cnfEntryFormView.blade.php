@extends('layouts.master')
@section('title', 'CNF Entry Form')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

    {!!Html :: script('js/customizedAngular/cnfEntryForm.js')!!}

@endsection

@section('content')
    <div class="col-md-12"  style="padding: 0;" ng-cloak=""  ng-app="cnfApp" ng-controller="CnfPanelController">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-inline" ng-submit="search(ManifestNo)">
                <div class="form-group">
                    <input type="text" name="ManifestNo" ng-model="ManifestNo"  id="ManifestNo" class="form-control" placeholder="Search Manifest No" ng-keydown="keyBoard($event)">
                </div>
                <span class="ok">@{{ searchFound }}</span>
                <span class="error">@{{ searchNotFound }}</span>
            </form>
            <div class="clearfix"></div>
            <div class="col-md-4 col-md-offset-8" >
                <a href="{{ route('c&f-get-todays-cnf-manifest-posting-report') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Manifest Entry</button></a>
            </div>
            <br><br>
            <div ng-show="truckDivShow">
                <span ng-style="truckDivShowColor">@{{ totalTruck }} Trucks Entered Already.</span> <br>
            </div>
        </div>

        {{----------------------------------------------------------------------------------------------------------------------------------------}}
        <div class="col-md-11 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
            {{--------------------------------------Complete Form Inside The DIV----------------------------------------------------}}
            <form  name="cnfform" id="cnfform" novalidate>
            <table>
                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Manifest NO</th>
                    <td>
                        <input   ng-disabled="manif_posted_btn_disable" required style="width: 190px;" class="form-control" required="required" ng-model="m_manifest"  name="m_manifest" id="m_manifest" ng-hide="hidemanifestWhenUpdatebtnClick" ng-keydown="keyBoard($event)">
                        <span class="error" ng-show="cnfform.m_manifest.$invalid && submittedCnfForm">Manifest No is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Manifest Date</th>
                    <td>
                        <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" required type="text" class="form-control datePicker"  ng-model="m_manifest_date" name="m_manifest_date" id="m_manifest_date" ng-hide="hidemanifestWhenUpdatebtnClick">
                        {{--<span ng-show="cnfform.m_manifest_date.$touched && !m_manifest_date" class="error">Date is required</span>--}}
                        <span class="error" ng-show="cnfform.m_manifest_date.$invalid && submittedCnfForm">Manifest Date is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Marks & No</th>
                    <td>
                        <input type="text" class="form-control"  ng-model="m_marks_no" style="width: 190px;" required name="m_marks_no" id="m_marks_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                        {{--<span ng-show="cnfform.m_marks_no.$touched && !m_marks_no" class="error">Marks No is required</span>--}}
                        <span class="error" ng-show="cnfform.m_marks_no.$invalid && submittedCnfForm">Marks No is required</span>
                    </td>

                </tr>
                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}
                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Description of Goods</th>
                    <td style="width: 175px;">
                        <select ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required name ="m_good_id" ng-model="m_good_id" id="m_good_id" ng-hide="hidemanifestWhenUpdatebtnClick" ng-options="good.id as good.cargo_name for good in allGoodsDataCnf ">
                            <option value="" selected="selected">Select Goods Name</option>
                        </select>
                        <span class="error" ng-show="cnfform.m_good_id.$invalid && submittedCnfForm">Description of Goods is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Gross Weight</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_gweight" name="m_gweight" id="m_gweight" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_gweight.$invalid && submittedCnfForm">Gross Weight is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Net Weight</th>
                    <td>
                        <input type="text"  ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_nweight" name="m_nweight" id="m_nweight" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_nweight.$invalid && submittedCnfForm">Net Weight is required</span>
                    </td>
                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Package NO</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_package_no" name="m_package_no" id="m_package_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_package_no.$invalid && submittedCnfForm">Package No is required</span>
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Package Type</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_package_type" name="m_package_type" id="m_package_type" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_package_type.$invalid && submittedCnfForm">Package Type is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">CNF Value</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_cnf_value" name="m_cnf_value" id="m_cnf_value" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_cnf_value.$invalid && submittedCnfForm">CNF Value is required</span>
                    </td>

                </tr>

                {{--<tr>--}}
                {{--<td colspan="4">&nbsp;</td>--}}
                {{--</tr>--}}
                <th ng-hide="hidemanifestWhenUpdatebtnClick">VAT No:</th>
                <td>
                    <input style="width: 190px;" type="text" ng-disabled="manif_posted_btn_disable" class="form-control" required ng-model="m_vat_id" name="m_vat_id" id="m_vat_id" ng-blur="getVatsData()" ng-hide="hidemanifestWhenUpdatebtnClick" >
                    <span class="error" ng-show="cnfform.m_vat_id.$invalid && submittedCnfForm">VAT No is required</span>
                </td>

                <th ng-hide="hidemanifestWhenUpdatebtnClick ||!m_vat_name">Importer Name:</th>
                <td colspan="3"  ng-hide="!m_vat_name">
                    <input type="text" ng-disabled="vatname" ng-disabled="manif_posted_btn_disable"  class="form-control" required ng-model="m_vat_name" name="m_vat_name" id="m_vat_name" ng-hide="hidemanifestWhenUpdatebtnClick" >
                </td>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Name & Address of Exporter</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_exporter_name_addr" name="m_exporter_name_addr" id="m_exporter_name_addr" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_exporter_name_addr.$invalid && submittedCnfForm">Name & Address is required</span>
                    </td>


                    <th ng-hide="hidemanifestWhenUpdatebtnClick">L.C No</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_lc_no" name="m_lc_no" id="m_lc_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_lc_no.$invalid && submittedCnfForm">L.C No is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">L.C Date</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control datePicker" required ng-model="m_lc_date" name="m_lc_date" id="m_lc_date" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_lc_date.$invalid && submittedCnfForm">L.C Date is required</span>
                    </td>
                </tr>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Indian Bill NO</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" required ng-model="m_ind_be_no" name="m_ind_be_no" id="m_ind_be_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="cnfform.m_ind_be_no.$invalid && submittedCnfForm">Indian Bill is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Indian Bill Date</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control datePicker" required ng-model="m_ind_be_date" name="m_ind_be_date" id="m_ind_be_date" ng-hide="hidemanifestWhenUpdatebtnClick" >
                        <span class="error" ng-show="cnfform.m_ind_be_date.$invalid && submittedCnfForm">Indian Bill Date is required</span>
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck Gross Weight</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control"   ng-model="t_gweight" name="t_gweight" id="t_gweight"  ng-hide="showWhenUpdatebtnClick" >
                        {{--<span class="error" ng-show="cnfform.t_gweight.$invalid && submittedCnfFormTruck">Truck Gross Weight is required</span>--}}
                    </td>
                </tr>


                <tr>
                    <th ng-hide="showWhenUpdatebtnClick">Truck Net Weight</th>
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck"  style="width: 190px;" class="form-control" ng-model="t_nweight" name="t_nweight" id="t_nweight"  ng-hide="showWhenUpdatebtnClick" >
                        {{--<span class="error" ng-show="cnfform.t_nweight.$invalid && submittedCnfFormTruck">Truck Net Weight is required</span>--}}
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck Type</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck"  style="width: 190px;" class="form-control" ng-model="t_truck_type" name="t_truck_type" id="t_truck_type"  ng-hide="showWhenUpdatebtnClick">
                        {{--<span class="error" ng-show="cnfform.t_truck_type.$invalid && submittedCnfFormTruck">Truck Type is required</span>--}}
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Truck NO</th >
                    <td>
                        <input type="number" ng-disabled="manif_posted_btn_disable_truck"  style="width: 190px;" class="form-control" ng-model="t_truck_no" name="t_truck_no" id="t_truck_no"  ng-hide="showWhenUpdatebtnClick">
                        {{--<span class="error" ng-show="cnfform.t_truck_no.$invalid && submittedCnfFormTruck">Truck No is required</span>--}}
                    </td>
                </tr>

                <tr>

                    <th ng-hide="showWhenUpdatebtnClick">Driver Name</th >
                    <td>
                        <input type="text" ng-disabled="manif_posted_btn_disable_truck"  style="width: 190px;" class="form-control" ng-model="t_driver_name" name="t_driver_name" id="t_driver_name"  ng-hide="showWhenUpdatebtnClick" >
                        {{--<span class="error" ng-show="cnfform.t_driver_name.$invalid && submittedCnfFormTruck">Driver Name is required</span>--}}
                    </td>

                    <th ng-hide="showWhenUpdatebtnClick">Driver Card NO</th >
                    <td>
                        <input type="number"  ng-disabled="manif_posted_btn_disable_truck" style="width: 190px;" class="form-control" ng-model="t_driver_card" name="t_driver_card" id="t_driver_card"  ng-hide="showWhenUpdatebtnClick" >
                        {{--<span class="error" ng-show="cnfform.t_driver_card.$invalid && submittedCnfFormTruck">Driver Card is required</span>--}}
                    </td>
                    <th ng-hide="showWhenUpdatebtnClick">Weight Bridge:</th>
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
                        <button type="button" ng-click="save()"  ng-if="!updateBtn" class="btn btn-primary">Save</button>
                        {{--ng-hide="updateBtn"--}}
                        {{--ng-disabled="!m_manifest||!m_manifest_date||!m_marks_no||!m_good_id||!m_nweight||!m_gweight||!m_package_no|--}}
                        {{--|!m_package_type||!m_cnf_value||!m_vat_id||!m_vat_name||!m_exporter_name_addr||!m_lc_no||!m_lc_date||!m_ind_be_no||!m_ind_be_date"--}}
                        <button type="button" ng-click="update(truckForm)"  ng-if="updateBtn" class="btn btn-primary">Update</button>
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
            <!--------------------------------- Delete div end here :)------------------------------------------------------------>




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


