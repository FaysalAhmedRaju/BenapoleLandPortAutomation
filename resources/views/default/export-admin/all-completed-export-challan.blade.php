@extends('layouts.master')
@section('title', 'Completed Challan')
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
    {!!Html :: script('js/customizedAngular/export-admin/completed-challan-export.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="CompletedChallanApp" ng-controller="CompletedChallanCtrl">


        <div class="col-md-12 table-responsive" >
            <table class="table table-bordered text-center">
                <caption><h4 class="text-center ok">Completed Challan</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchTextCompletedChallan"></label></caption>
                <thead>
                <tr>
                    <th>S/L</th>

                    <th>Challan No</th>
                    <th>Total Amount</th>
                    <th>Challan Date</th>
                    <th>Created DateTime</th>
                    <th>Vehicle Type</th>

                    <th>Challan Report</th>

                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="challanList in allInCompletedList | orderBy: 'challanList.id':true  | filter:searchTextCompletedChallan | itemsPerPage:15" pagination-id="challanList">
                    <td>@{{ $index+serial }}</td>
                    <td>@{{ challanList.export_challan_no }}</td>
                    <td>@{{ challanList.total_amount }}</td>
                    <td>@{{ challanList.challan_date }}</td>
                    <td>@{{ challanList.create_datetime }}</td>
                    <td>@{{ challanList.truck_bus_flag | vehicleFilter }}</td>
                    <td>  <a  class="btn btn-success" href="/export-admin/report/get-challan-report/@{{ challanList.export_challan_no }}/@{{challanList.id}}" target="_blank">Challan Report</a></td>

                    <td>
                        <a class="btn btn-info" data-target="#DoneChallanFormModal" ng-click="ChallanDetails(challanList)" data-toggle="modal" >Details</a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="8" class="text-center">
                        <dir-pagination-controls max-size="8"
                                                 on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="challanList">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

 {{--============================================================================ Modal of Challan Details ====================================================--}}
        <div  class="modal fade" id="DoneChallanFormModal"  role="dialog">
            <div class="modal-dialog" >
                <div class="modal-content largeModal">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" style="color: #000;">Challan Details</h4>
                        <div>
                    <div class="modal-body">
                        <table style="width: 100%;">
                            <thead></thead>

                            <tbody>


                            <tr>

                                <th>Challan No: </th>
                                <td>


                                    @{{ChallanNo}}

                                </td>


                                <th>Challan Date: </th>
                                <td>
                                    @{{ChallanDate}}
                                </td>


                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;

                                </td>
                            </tr>
                            <tr>

                                <th>Vehicle Type: </th>
                                <td>
                                    @{{VehicleType | vehicleFilter}}

                                </td>


                                <th>Total Amount: </th>
                                <td>
                                    @{{Total_Amount}}
                                </td>


                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;

                                </td>
                            </tr>
                            <tr>

                                <th>Created DateTime: </th>
                                <td>

                                    @{{CreatedDateTime}}
                                </td>


                                <th>Created By: </th>
                                <td>
                                    @{{CreatedBy}}
                                </td>


                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;

                                </td>
                            </tr>
                            <tr>

                                <th>Miscellaneous Charge: </th>
                                <td>
                                    @{{miscellaneous_name}}

                                </td>


                                <th>Miscellaneous Name: </th>
                                <td>
                                    @{{miscellaneous_charge}}
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




                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary center-block" ng-click="doneChallan()" >Done</button>
                        </div>
                        <div id="SuccessdoneChallan" class="alert alert-success text-center" ng-show="SuccessMsg">
                            Successfully Saved!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

{{--============================================================================ Modal of Challan Details  End ====================================================--}}

    </div>
@endsection