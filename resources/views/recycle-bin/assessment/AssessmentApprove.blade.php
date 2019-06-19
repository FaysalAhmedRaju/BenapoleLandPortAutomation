@extends('layouts.master')
@section('title', 'Assessment Approve')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    {{-- {!!Html :: script('js/customizedAngular/assessment.js')!!} --}}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!!Html :: script('js/customizedAngular/AssessmentApprove.js')!!}
    <script>
        var manifestNo = null;
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>

@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="AssessmentApproveApp" ng-controller="AssessmentApproveCtrl">
        <div class="col-md-7 col-md-offset-2" style="border:1px solid green">
            <form name="form" class="form-inline" novalidate ng-submit="manifestImporterBillSearch(searchText)">
                <div class="form-group">
                    <br>
                    Search By:
                    <select ng-change="selectField()" class="form-control" name="selectionField" ng-model="selectionField">
                        <option value="">---Please Select---</option>
                        <option value="manifestNo">Manifest No</option>
                        <option value="importerNo">Importer No</option>
                        <option value="billNo">Bill No</option>
                    </select>
                    <input type="text" required="required" ng-model="searchText" name="searchText" class="form-control input-sm" id="searchText" ng-disabled="serachField" placeholder="@{{ placeHolder }}" ng-change="clear()" ng-keydown="keyBoard($event)">
                </div>
            </form>
            <br>
        </div>


        <div class="col-md-3">
            <a href="GetAssessmentPdfReport/@{{ searchText }}"  target="_blank" class="btn btn-primary" >Get Assessment Sheet</a>

        </div>



        <div class="col-md-12">
            <div class="alert alert-danger" ng-hide="!ManifestNotFoundError">@{{ ManifestNotFoundError }}</div>
            <table class="table table-bordered" ng-show="table">
                <caption><h4 class="text-center"> Manifest Details:  @{{ searchText }}</h4></caption>
                <thead>
                <tr>
                    {{--<th>S/L</th>--}}
                    <th>Manifest</th>
                    <th>Manifest Date</th>
                    <th>Package Details</th>
                    <th>Importer Name</th>
                    <th>Exporter Name</th>
                    <th>Cargo Name</th>
                    <th>Approve Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr {{--ng-style="{'background-color':(manifest.id == selectedStyle?'#dbd3ff':'')}"--}} dir-paginate="manifest in allManifestData | orderBy:'manifest.id' | itemsPerPage:5">
                    {{--<td>@{{$index+1}}</td>--}}
                    <td>@{{manifest.manifest}}</td>
                    <td>@{{manifest.manifest_date}}</td>
                    <td>@{{manifest.package_no +" "+ manifest.package_type}}</td>
                    <td>@{{manifest.importerName}}</td>
                    <td>@{{manifest.exporter_name_addr}}</td>
                    <td>@{{manifest.cargoName}}</td>
                    <td style="font-weight: bold;" ng-style="getStyle(manifest.approved)">
                        {{--<button type="button" class="btn btn-info">@{{manifest.approved | approveFilter}}</button>--}}
                        @{{manifest.approved | approveFilter}}
                    </td>
                    <td>
                        {{--<a class="btn btn-primary" ng-click="selected(manifest)" href="DetailsOfAssessmentApprove/@{{ manifest.manifest }}/2" target="_blank">Details</a>--}}
                        <button type="button" class="btn btn-primary" ng-click="details(manifest.manifest)">Details</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        {{--After Press Details button--}}
        <div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                <img src="img/dataLoader.gif" width="250" height="15" />
                <br /> Please wait!
            </span>   
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" ng-show="manifestTable">
                <caption><h4 class="text-center"> Manifest Details:  @{{ manifestForCaption }}</h4></caption>
                    <thead>
                        <tr>
                            <th>Manifest Date</th>
                            <th>Description of Goods</th>
                            <th>Quantity</th>
                            <th>No Of Packages</th>
                            <th>C&F Value</th>
                            <th>Name & Address of Expoter</th>
                            <th>Name & Address of Importer</th>
                            <th>L.C No. & Date</th>
                            <th>B/E No. and Date</th>
                            <th>Indian B/E No. and Date</th>
                        </tr>
                    </thead>
                <tbody>
                    <tr ng-repeat="manifest in manifestDetails">
                        <td>@{{manifest.manifest_date}}</td>
                        <td>@{{manifest.cargo_name}}</td>
                        <td>Gr. Wt- @{{manifest.gweight}} <br> Nt. Wt-@{{manifest.nweight}}</td>
                        <td>@{{manifest.package_no+ " " + manifest.package_type}}</td>
                        <td>@{{manifest.cnf_value}}</td>
                        <td>@{{manifest.exporter_name_addr}}</td>
                        <td>@{{manifest.NAME +" "+ manifest.ADD1}}</td>
                        <td>@{{manifest.lc_no +" "+ manifest.lc_date}}</td>
                        <td>@{{manifest.be_no +" "+ manifest.be_date}}</td>
                        <td>@{{manifest.ind_be_no +" "+ manifest.ind_be_date}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" ng-show="foreignTruckTable">
                <caption><h4 class="text-center">Foreign Truck Details</h4></caption>
                <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Manifest No.</th>
                        <th>Truck No.</th>
                        <th>Driver Name</th>
                        <th>Net Weight</th>
                        <th>Receive Package</th>
                        <th>Receive Date</th>
                        {{--<th>Labor Unload</th>
                        <th>Equipment Name</th>
                        <th>Equipment Load</th>--}}
                        <th>Offloading</th>
                        <th>Equipment Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="foreignTruck in allForeignTruck | itemsPerPage:5" pagination-id="foreignTruck">
                        <td>@{{$index+1}}</td>
                        <td>@{{foreignTruck.manifest}}</td>
                        <td>@{{foreignTruck.truck_no}}</td>
                        <td>@{{foreignTruck.driver_name}}</td>
                        <td>@{{foreignTruck.nweight}}</td>
                        <td>@{{foreignTruck.receive_package}}</td>
                        <td>@{{foreignTruck.receive_datetime}}</td>
                        {{--<td>@{{foreignTruck.labor_unload}}</td>
                        <td>@{{foreignTruck.equip_name}}</td>
                        <td>@{{foreignTruck.equip_unload}}</td>--}}
                        <td>@{{foreignTruck.offloading_flag | offOrloadingFilter }}</td>
                        <td>@{{foreignTruck.equip_name}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="9" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                direction-links="true"
                                                boundary-links="true"
                                                pagination-id="foreignTruck">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" ng-show="localTruckTable">
                <caption><h4 class="text-center">Local Truck Details:</h4></caption>
                <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Manifest No.</th>
                        <th>Truck No.</th>
                        <th>Driver Name</th>
                        <th>Gross Weight</th>
                        <th>Package</th>
                        <th>Delivery Date</th>
                        <th>Approve Date</th>
                        {{--<th>Labor Load</th>
                        <th>Equipment Name</th>
                        <th>Equipment Load</th>--}}
                        <th>Loading Unit</th>
                        <th>Loading</th>
                        <th>Equipment Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="localTruck in allLocalTruck | itemsPerPage:5" pagination-id="localTruck">
                        <td>@{{$index+1}}</td>
                        <td>@{{localTruck.manifest}}</td>
                        <td>@{{localTruck.truck_no}}</td>
                        <td>@{{localTruck.driver_name}}</td>
                        <td>@{{localTruck.gweight}}</td>
                        <td>@{{localTruck.package}}</td>
                        <td>@{{localTruck.delivery_dt}}</td>
                        <td>@{{localTruck.approve_dt}}</td>
                        {{--<td>@{{bdtruck.labor_load}}</td>
                        <td>@{{localTruck.equip_name}}</td>
                        <td>@{{localTruck.equip_load}}</td>--}}
                        <td>@{{localTruck.loading_unit}}</td>
                        <td>@{{localTruck.loading_flag | offOrloadingFilter}}</td>
                        <td>@{{localTruck.equip_name}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="11" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="localTruck">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{----------------------------------------ASSESSMENT---------------------------------------}}

        <div class="col-md-12"  ng-show="assessment">

            @include('shared/assessment')

        </div>
        {{----------------------------------------ASSESSMENT----------------------------------------}}
        <div class="col-md-4 col-md-offset-4" style="background-color: #dbd3ff; border-radius: 20px;" ng-show="assessmentApproveForm">

            <h4 style="text-align: center;">Approve Panel</h4>
            <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <table>
                    <tr>
                        <th>Comment:</th>
                        <td>
                            <input class="form-control" type="text" name="approve_comment" id="approve_comment" ng-model="approve_comment" ng-disabled="show">
                            <span class="error" ng-show="verify_comm_required">Approve Comment is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr >
                        <td>
                            <button type="button" class="btn btn-danger center-block" ng-click="rejectmanifest()" ng-disabled="show">Reject</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary center-block" ng-click="approve()" ng-disabled="show">Approve</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection