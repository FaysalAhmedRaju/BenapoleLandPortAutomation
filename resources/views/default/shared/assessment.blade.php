<style type="text/css">
    #one td {
        border: 1px solid black;
    }

    #one th {
        border: 1px solid black;
    }

    .tbl-td-center tr td {
        text-align: center !important;

    }

    td.td-width {
        width: 5px !important;
    }

    .td-color-blue {
        color: #0000cc;
    }

    .verticle-middle {
        vertical-align: middle !important;

    }

    /*body {
         font-size: 12px;
         font-family: Tahoma, Helvetica, sans-serif;
     }

    table {
        border: 1px solid #555;
        border-width: 0 0 1px 1px;
    }
    table td {
        border: 1px solid #555;
        border-width: 1px 1px 0 0;
    }
*/

    span.slab-period {
        /*margin-left: 8px;*/
        display: block;
        /*color: #b1b1b1;*/
        font-size: 12px;
        font-style: italic;
        font-weight: bold;

    }

</style>
<div id="aa" class="col-md-12 text-center" ng-animate="'animate'">
    {{--START PDF Div--}}

    <div class="col-md-12 ForPdf" ng-show="ForPdf">

        <img src="img/Logo_BSBK.gif" style="float: left; width: 120px">
        <p class="center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            <span style="font-size: 19px;">Assessment Report</span> <br>
        </p>
        <h5 style="text-align: right;padding-right: 35px;">
            Date: <?php
            $timezone = 6; //(GMT -6:00) EST (Dhaka)
            echo gmdate("F j, Y, g:i a", time() + 3600 * ($timezone + date("I")));
            ?>
        </h5>
    </div>


    <div class="col-md-12" style="padding: 0">

        <table class="text-center-td-th" id="assessment" style="box-shadow: 0px 0px 5px 1px darkgrey; width: 100%">


            <tr>
                <td>
                    <b> Manifest No & Date:</b>
                </td>

                <td>
                    <u> <span ng-if="ManifestNo || Mani_date">@{{ ManifestNo  +' & '+ Mani_date }}</span></u>
                </td>


                <td>
                    <b>Consignee:</b>
                </td>

                <td>
                    <span ng-if="Consignee"
                          style="text-transform: capitalize">@{{ Consignee ?  Consignee :'No  Consignee' }}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>

            <tr>
                <td>
                    <b>Bill Of Entry No & Date:</b>
                </td>

                <td><span ng-show="m.be_date"></span>
                    <u> <span ng-if="Bill_No && Bill_date">@{{ Bill_No +' & '+ Bill_date}}</span></u>
                </td>


                <td class="2">
                    <b> Consignor:</b>
                </td>

                <td class="2">
                    <span ng-if="Consignor"> @{{ Consignor ? Consignor :'No Consignor' }}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>

            <tr>
                <td rowspan="2">
                    <b>Custom's Release Order No & Date:</b>
                </td>

                <td rowspan="2"><span ng-show="m.be_date">C-</span>
                    <u> <span ng-if="Custome_release_No">@{{ Custome_release_No +' & '+ Custome_release_Date}}</span>
                    </u>
                </td>


                <td>
                    <b> C & F Agent:</b>
                </td>

                <td>
                    <span ng-if="CnF_Agent"> @{{ CnF_Agent ? CnF_Agent :'No CnF' }}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>


        </table>

        <br> <br>
    </div>


    <div class="col-md-12" style=" text-align: left;padding: 0">

        <p style="padding: 3px ;float: left; box-shadow: 1px 0px 5px 2px #969696; width: 250px">
            <b>PARTICULARS OF CHARGES DUE</b>
        </p>

        <p style="padding: 3px ;float: left;margin: 0 30px; box-shadow: 1px 0px 5px 2px #969696; width: 450px">
            <b>Shed / Yard No.</b> @{{ posted_yard_shed }}

        </p>
        {{--<b><p style="padding: 3px ;box-shadow: 1px 0px 5px 2px #969696; width: 250px">PARTICULARS OF CHARGES DUE</p></b>--}}

    </div>


    <div class="col-md-12 td-left" id="assessment" style="padding: 0;">

        {{--==========================Warehouse Rent====START=========================--}}

        <div class="col-md-12" style="padding: 0;">

            <table border="1" class="table table-bordered td-bordered"
                   style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">
                <tr>

                    <td></td>
                    <th colspan="2" style="width: 37%"></th>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr id="one">
                    <td><b>1.</b></td>
                    <th> Warehouse Rent</th>
                    <td></td>
                    <th colspan="5">
                        Assessment:

                    </th>
                    {{--<th>Basis of Charge</th>
                    <th>Day</th>
                    <th>Rate</th>
                    <th>Amount</th>--}}
                </tr>

                <tr id="one">
                    <td></td>
                    <td> Description of goods</td>
                    <td>
                        <span ng-show="totalItems">{{--Items= <b>@{{ totalItems }}</b> --}}
                        </span> @{{description_of_goods }}
                    </td>
                    <td colspan="5" rowspan="5" style="padding: 0">

                        <table class="tbl-td-center" border="1" ng-if="item_wise_yard_details.length>0 || item_wise_shed_details.length>0" width="100%">
                            <tr>
                                <td width="16%"><b>Item</b></td>
                                <td width="18%"><b>Basis Of Charge</b></td>
                                <td width="6%"><b>Slab</b></td>
                                <td width="10%"><b>Period</b></td>
                                <td width="7%" style="text-align: center"><b>Day</b></td>
                                <td width="13%"><b>Quantity</b></td>
                                <td width="12%"><b>Rate</b></td>
                                <td width="32%" class="amount-right"><b>Amount</b></td>
                            </tr>
                            {{--Shed Start--}}
                            <tbody ng-show="item_wise_shed_details.length>0">

                            <tr>
                                <th colspan="8" class="ok">Shed</th>
                            </tr>

                            <tr ng-repeat="item in item_wise_shed_details{{-- | unique : 'item_name'--}}">
                                <td ng-class="{'error':item.dangerous=='1'}">@{{ item.item_name }} <span
                                            ng-if="item.dangerous==1"><b>(@{{ item.dangerous|dangerous}}</b>)</span>
                                </td>
                                <td style="vertical-align: middle;text-align: center">@{{ item.item_type|item_type}}</td>
                                <td colspan="6">
                                    <table class="tbl-td-center" border="0" width="100%">
                                        <tr>
                                            <td style="width: 9%; text-align: center">
                                                <b>@{{ item.slab }}</b><br>
                                            </td>
                                            <td style="width: 16%">
                                                <span class="slab-period">
                                                    @{{ item.start_day }}
                                                <br> to <br>
                                                @{{ item.end_day }}
                                                </span>
                                            </td>
                                            <td style="width: 10%">@{{ item.slab_duration_day }}</td>
                                            <td style="width: 20%"> @{{ item.item_quantity }}</td>
                                            <td style="width: 19%">
                                                @{{ item.charge }}
                                                <span ng-show="item.dangerous=='1'">
                                                    X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}
                                                </span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{ item.total_charge |number:2}}</b>
                                            </td>
                                        </tr>

                                    </table>
                                </td>

                            </tr>

                            </tbody>

                            {{--Yard Start--}}
                            <tbody ng-if="item_wise_yard_details.length>0">
                            <tr>
                                <th class="ok" colspan="8">Yard</th>
                            </tr>

                            <tr ng-repeat="item in item_wise_yard_details{{-- | unique : 'item_name'--}}">
                                <td ng-class="{'error':item.dangerous=='1'}">@{{ item.item_name }} <span
                                            ng-if="item.dangerous==1"><b>(@{{ item.dangerous|dangerous}}</b>)</span>
                                </td>
                                <td style="vertical-align: middle;text-align: center">@{{ item.item_type|item_type}}</td>
                                <td colspan="6">
                                    <table class="tbl-td-center" border="0" width="100%">
                                        <tr>
                                            <td style="width: 9%; text-align: center">
                                                <b>@{{ item.slab }}</b><br>
                                            </td>
                                            <td style="width: 16%">
                                            <span class="slab-period">
                                                    @{{ item.start_day }}
                                                <br> to <br>
                                                @{{ item.end_day }}
                                                </span>
                                            </td>
                                            <td style="width: 10%">@{{ item.slab_duration_day }}</td>
                                            <td style="width: 20%"> @{{ item.item_quantity }}</td>
                                            <td style="width: 19%">
                                                @{{ item.charge }}
                                                <span ng-show="item.dangerous=='1'">
                                                    X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}
                                                </span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{ item.total_charge |number:2}}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tbody>
                        </table>

                        <h3 style=" transform: rotate(-35deg);vertical-align: middle; text-align: center"
                            ng-if="item_wise_yard_details.length==0 && item_wise_shed_details.length==0"> FT </h3>


                    </td>

                </tr>
                <tr id="one">
                    <td></td>
                    <td> No Of Package</td>
                    <td><span ng-show="package_no">@{{ package_no }}</span> <span
                                ng-if="package_type">@{{ package_type }}</span></td>
                </tr>

                <tr id="one">
                    <td></td>
                    <td> Weight</td>
                    <td>

                        <form class="form-inline" ng-submit="changeBassisOfCharge(bassisOfCharge)" role="form">
                            <div class="form-group">
                                <label ng-show="bassisOfCharge">@{{ bassisOfCharge}} KGs</label>
                                <input style="width: 80px; height: 22px;" title="" class="form-control input-xs"
                                       type="text"
                                       name="bassisOfCharge"
                                       id="bassisOfCharge" ng-model="bassisOfCharge"
                                       ng-show="role_id != 5">

                            </div>
                        </form>

                        {{--<form class="form-inline" ng-submit="changeBassisOfCharge(bassisOfCharge)">
                            <label ng-show="bassisOfCharge">@{{ bassisOfCharge}} KGs</label>
                            <div class="form-group">
                                <input title="" class="form-control" type="text" name="bassisOfCharge"
                                       id="bassisOfCharge" ng-model="bassisOfCharge"
                                       ng-show="role_id != 5">
                                <span ng-if="role_id == 5">@{{bassisOfCharge}}</span>
                            </div>
                        </form>--}}


                        <span style="color: #b92c28;" id="changeBassisOfChargeError"
                              ng-show="changeBassisOfChargeError">@{{changebassisOfChargeErrorMsgTxt}}</span>

                        <span style="color: #3e8f3e;" id="changeBassisOfChargeSuccMsg"
                              ng-show="changeBassisOfChargeSuccMsg">@{{ bassisOfChargeSuccMsgTxt}}</span>
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    {{-- <td ng-click="changeReceivedayOption(receive_date)">
                         <span ng-show="receive_date">@{{ receive_date |dateShort:'medium'}}</span>

                     </td>--}}
                    <td> Date of Unloading<br/><span ng-if="receive_date && partial_status != 1">(Partial)</span>
                    </td>

                    <td>

                        <span ng-if="receive_date && partial_status == 1">@{{receive_date | dateShort:"dd-MM-yyyy HH:mm:ss"}}</span>
                        <span ng-if="receive_date && partial_status != 1">@{{receive_date | dateShort:"dd-MM-yyyy"}}</span>
                        {{--   <form ng-submit="changeReceivedayOption(receive_date)">
                            <input class="form-control datePicker" type="text" name="receive_datetime" disabled
                                     id="receive_datetime" ng-model="receive_date"
                                     ng-show="role_id != 5 && assessmentSavePage">
                           <span ng-if="role_id == 5 && receive_date != null || assVarificationOrApprove ">@{{receive_date |dateShort:'mediumDate' }}</span>

                        </form>
  --}}

                        <span style="color: #b92c28;" id="changeReceivedayError" ng-show="changeReceivedayError">@{{changeReceivedayErrorMsg}}
                            !</span>

                        <span style="color: #3e8f3e;" id="changeReceivedaySucc" ng-show="changeReceivedaySucc">Date Changed Successfully!</span>

                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td> Free Period</td>
                    <td ng-model="freePeriod">
                                <span ng-show="free_items.length > 0 && truckToTruckFlag==0">
                                    <span ng-repeat="ft in free_items">
                                        {{--<span style="font-style: oblique;">@{{ ft.item_name }} -></span>--}}
                                        <span ng-if="$index==0">@{{ receive_date | dateShort:"dd-MM-yyyy"}}  - @{{  ft.free_day_end  }} = FT<br></span>
                                    </span>
                                    {{--@{{ receive_date |dateShort:"dd-MM-yyyy"}}--}}
                                    {{--- @{{freeEndDay|dateShort:"dd-MM-yyyy"  }} = FT--}}
                                </span>
                                <span ng-show="{{--freeEndDay && --}}truckToTruckFlag==1" class="error">
                                    Truck To Truck
                                </span>
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td> Rent due Period</td>
                    <td ng-model="rentDuePeriod">
                        <span ng-if="items_warehouse_rent.length<1" class="error">No Rent</span>
                        <span ng-if="items_warehouse_rent.length>0">
                                    <span ng-repeat="iwr in items_warehouse_rent">
                                        {{--<span style="font-style: oblique;">@{{ iwr.item_name }} -></span>--}}
                                        <span ng-if="$index==0"> @{{ iwr.rent_start_day }} - @{{ deliver_date | dateShort:"dd-MM-yyyy"}} = @{{ iwr.rent_day }}<br></span>
                                    </span>
                                    {{--@{{ WarehouseChargeStartDay |dateShort:"dd-MM-yyyy"}}--}}
                            {{--- @{{deliver_date|dateShort:"dd-MM-yyyy" }}= @{{ WareHouseRentDay }}--}}
                                </span>
                    </td>
                    <!--td colspan="2"></td>


                    <td colspan="2">

                    </td-->
                    <td colspan="6" style="" class="amount-right">
                        <b>W.R Total:</b> <span ng-if="TotalWarehouseCharge>0" style="width: 200px;">
                            @{{ TotalWarehouseCharge |number:2}}</span>
                        {{--text-align: center!important;--}}

                    </td>

                </tr>

            </table>


        </div>

        {{--==========================Warehouse Rent====END=========================--}}

        {{--==========================Handling Operation ====START=========================--}}

        <div class="col-md-12 td-bordered" style="padding: 0">
            {{-- <p class="col-md-5">
              <button class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Gross</button>
              <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Net</button>
              <button class="btn btn-success" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Unload</button>
              <button class="btn btn-danger" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Load</button>
              <button class="btn btn-warning" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Maximum</button>
            </p>
            <p class="col-md-2">
                <input type="number" class="form-control" name="weight_ton">
            </p> --}}


            <table class="table table-bordered" style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">


                <tr>
                    <td></td>
                    <th colspan="3" style="width: 50%"></th>
                    <th colspan="4" style="width: 50%"></th>
                    <td></td>


                </tr>

                <tr>
                    <td><b>2.</b></td>
                    <th style="width:590px">Handling Operation
                        <button ng-if="manifestFound && partial_status == 1" type="button" ng-click="getHandlingWeight()" data-toggle="modal" data-target="#handlingWeightChangeModal" class="btn btn-success btn-xs" data-backdrop="static"
                        data-keyboard="false">Change</button>
                    </th>
                    <th colspan="2">Handling Mode</th>
                    <td>
                        <b>Ton</b><br/>
                    </td>
                    <td><b>Rate</b></td>
                    <th colspan="2"></th>
                    <th class="amount-right">Amount</th>
                </tr>

                <tr>
                    <td></td>
                    <th rowspan="2" style="vertical-align: middle"> Unloading:</th>

                    <td colspan="2"> Manual</td>
                    <td>
                        <span ng-if="OffloadLabour">
                            @{{ OffloadLabour }}
                        </span>
                    </td>
                    <td>
                        <span ng-if="OffloadLabour">
                            @{{ OffloadLabourCharge }}
                        </span>
                    </td>
                    <td colspan="2"></td>
                    <td class="amount-right">

                        <span ng-if="OffloadLabour">@{{ TotalForOffloadLabour |number:2}}</span>


                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td colspan="2">
                        Equipment
                    </td>
                    <td>
                        <span ng-if="OffLoadingEquip">
                            @{{ OffLoadingEquip }}
                            <span ng-if="unloadShifting"> X 2</span>
                        </span>
                    </td>
                    <td>
                        <span ng-if="OffLoadingEquip">
                          @{{ OffLoadingEquipCharge }}
                            <span ng-if="unloadingShifting"> X 2</span>
                        </span>
                    </td>
                    <td colspan="2">
                        <span ng-if="self_flag==1 && OffLoadingEquip<=0">  (Self)   </span>
                    </td>
                    <td class="amount-right">
                        <span ng-if="OffLoadingEquip">
                            @{{ TotalForOffloadEquip|number:2 }}
                        </span>
                    </td>
                </tr>

                {{--Loading--}}


                <tr>
                    <td></td>
                    <th rowspan="2" class="verticle-middle"> Loading:</th>
                    <td colspan="2"> Manual</td>


                    <td>
                      <span ng-if="loadLabour>0">
                            @{{ loadLabour }}
                        </span>
                    </td>
                    <td>
                        <span ng-if="loadLabour>0">
                             @{{ loadLabourCharge }}
                        </span>
                    </td>
                    <td colspan="2"></td>
                    <td class="amount-right">
                        <span ng-if="TotalForloadLabour>0">@{{ TotalForloadLabour|number:2 }}</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        Equipment
                    </td>

                    <td>
                         <span ng-if="loadingEquip">
                            @{{ loadingEquip }} <span ng-if="loadShifting">X 2</span>
                        </span>
                    </td>
                    <td>
                         <span ng-if="loadingEquip">
                             @{{ loadingEquipCharge }}
                        </span>
                    </td>
                    <td colspan="2">
                        <span ng-if="self_flag==1 && loadingEquip<=0">  (Self)   </span>
                    </td>

                    <td class="amount-right">
                        <span ng-if="TotalForloadEquip">@{{ TotalForloadEquip|number:2 }}</span>
                    </td>
                </tr>

            </table>
        </div>


        {{--==========================Handling Operation ====END=========================--}}

        {{--========================== Other Dues ====START=========================--}}

        <div class="col-md-12 td-bordered" style="padding: 0">
            <br>

            <table class="table table-bordered" style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">


                <tr>
                    <td></td>
                    <th colspan="4" style="width: 50%"></th>

                    <th colspan="4" style="width: 50%"></th>
                    <td></td>

                </tr>

                <tr>
                    <td><b>3.</b></td>
                    <th colspan="2" style="width:590px"> Other Dues</th>
                    <th colspan="2"><b>Truck</b></th>
                    <td><b>Number</b></td>
                    <td><b>Rate</b></td>
                    <th colspan="2"></th>
                    <th class="amount-right">Amount</th>
                </tr>


                {{--Truck Entrance fee--}}

                <tr>
                    <td></td>
                    <th colspan="2" rowspan="3" class="verticle-middle"> Entrance Fee:</th>
                    <td colspan="2"> Foreign</td>
                    <td class="td-center">
                        <span ng-if="partial_status == 1">
                            <input class="form-control input-xs" style="text-align:center;" type="number"
                                   ng-model="totalForeignTruck" name="totalForeignTruck"
                                   ng-change="changeNumberOfForeignTruckFee(totalForeignTruck)">
                            {{--@{{ totalForeignTruck }}--}}
                        </span>
                    </td>
                    <td>
                        <span style="" ng-if="partial_status == 1">@{{ entrance_fee_foreign }}</span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span ng-if="partial_status == 1"> @{{ totalForeignTruckEntranceFee|number:2 }}</span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="2"> Local Truck</td>
                    <td class="td-center">
                        <span ng-if="totalLocalTruck>0">@{{ totalLocalTruck }}</span>
                    </td>
                    <td>
                        <span ng-if="totalLocalTruck>0">@{{ entrance_fee_local }}</span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span ng-if="totalLocalTruckEntranceFee>0">
                            @{{ totalLocalTruckEntranceFee|number:2 }}
                        </span>
                    </td>

                </tr>


                <tr>
                    <td></td>
                    <td colspan="2"> Local Van</td>
                    <td class="td-center">
                        <span ng-if="totalLocalVan>0">@{{ totalLocalVan }}</span>
                    </td>
                    <td>
                        <span ng-if="totalLocalVan>0">@{{ entrance_fee_van }}</span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span ng-if="totalLocalVan>0">
                            @{{ totalLocalVanEntranceFee|number:2 }}
                        </span>
                    </td>

                </tr>

                {{--Carpenter Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2" rowspan="2" class="verticle-middle"> Carpenter charge:</th>
                    <td colspan="2"><b>Type</b></td>
                    <td><b>Packs</b></td>
                    <td><b>Rate</b></td>
                    <td colspan="2"></td>
                    <td></td>

                </tr>


                <tr>
                    <td></td>
                    <td colspan="2">
                        <nobr> Opening/Closing</nobr>
                    </td>
                    <td class="td-center">
                        <span ng-if="carpenterPackages">@{{ carpenterPackages }}</span>
                    </td>

                    <td>
                        <span ng-if="carpenterPackages">@{{ carpenterChargesOpenClose }}</span>
                    </td>

                    <td colspan="2"></td>

                    <td class="amount-right"><span
                                ng-if="totalcarpenterChargesOpenClose>0">@{{ totalcarpenterChargesOpenClose|number:2 }}</span>
                    </td>
                </tr>

                 {{-- <tr ng-if="carpenterRepairPackages > 0">
                     <td></td>
                     <td colspan="2" ng-if="carpenterRepairPackages > 0">Repair</td>
                     <td colspan="4">
                         <span style="padding-left: 10%;" ng-if="carpenterRepairPackages > 0">@{{ carpenterRepairPackages }}</span>
                         <span style="padding-left: 6%;" ng-if="carpenterRepairPackages > 0"> X </span>
                         <span style="padding-left: 3%;" ng-if="carpenterRepairPackages > 0">@{{ carpenterChargesRepair }}</span>
                     </td>
                     <td class="amount-right">
                         <span ng-if="totalcarpenterChargesRepair>0"> @{{ totalcarpenterChargesRepair |number:2}}</span>
                     </td>
                 </tr> --}}



                {{--Weighment Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="3" colspan="2" style="vertical-align:middle"> Weighment Charge:</th>
                    <th><b>Truck</b>
                    </th>
                    <td>
                        <b>
                            <nobr>Total Truck</nobr>
                        </b>
                    </td>
                    <td><b>Twice</b></td>
                    <td><b>Charge</b></td>
                    <td colspan="2">

                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        Foreign
                    </td>
                    <td class="td-center">
                        <span ng-if="totalForeignTruckForWeighment>0"> @{{ totalForeignTruckForWeighment }}</span>
                    </td>
                    <td class="td-center">
                        <span ng-if="totalForeignTruckForWeighment>0">2</span>
                    </td>
                    <td>
                        <span ng-if="totalForeignTruckForWeighment>0">@{{ weightment_measurement_charges }}</span>
                    </td>
                    <td colspan="2">

                    </td>
                    <td class="amount-right">
                        <span ng-show="totalForeignTruckForWeighment"> @{{ weightmentChargesForeign | number:2 }}</span>
                    </td>

                </tr>

                <tr>
                    <td></td>
                    <td>
                        Local
                    </td>
                    <td class="td-center">
                        <span ng-if="local_truck_weighment > 0">  @{{ local_truck_weighment }} </span>
                    </td>
                    <td class="td-center">
                        <span ng-show="weightmentChargesLocal">2</span>
                    </td>
                    <td>
                        <span ng-show="weightmentChargesLocal">
                            @{{ weightment_measurement_charges }}
                        </span>
                    </td>
                    <td colspan="2">

                    </td>

                    <td class="amount-right">
                        <span ng-show="weightmentChargesLocal">
                        @{{ weightmentChargesLocal|number:2 }}
                    </span>
                    </td>
                </tr>

                {{--Haltage Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2" data-toggle="modal"
                        data-target="#haltageChargeChangeModal" ng-click="getTrucksForHaltageChargeChange()"
                        data-backdrop="static"
                        data-keyboard="false">
                        Haltage Charge:
                    </th>
                    <td colspan="6"></td>

                    <td></td>
                </tr>


                <tr data-toggle="modal"
                    data-target="#haltageChargeChangeModal" ng-click="getTrucksForHaltageChargeChange()"
                    data-backdrop="static"
                    data-keyboard="false">
                    <td></td>
                    <td colspan="9" style="padding: 0;">

                        <table class="table table-bordered">
                            <tbody>

                            <tr>
                                <th style="">Truck</th>
                                <th>Truck No.</th>
                                <th>Scale Weight</th>
                                <th>Receive Weight</th>
                                <th colspan="2">Entry Date</th>
                                <th colspan="2">Unloading Date</th>
                                <td><b>Day</b></td>
                                <td><b>Rate</b></td>
                                <td></td>

                            </tr>
                            <tr ng-repeat="f in haltagesForeignTruck">
                                <td style="vertical-align: middle;padding: 0" ng-if="$index==0"
                                    rowspan="@{{haltagesForeignTruck.length}}">
                                    <b> &nbsp;&nbsp;&nbsp; Foreign Truck</b>
                                </td>
                                <td>@{{ f.truck_no }}</td>
                                <td>@{{ f.tweight_wbridge }}</td>
                                <td>@{{ f.receive_weight }}</td>
                                <td colspan="2"><b>@{{ f.truckentry_datetime | stringToDate:"dd.MM.y HH:mm:ss" }}</b>
                                </td>
                                <td colspan="2"><b>@{{ f.receive_datetime != null ? (f.receive_datetime | stringToDate:"dd.MM.y HH:mm:ss") : 'Truck To Truck' }}</b></td>
                                <td>
                                    <span ng-show="f.haltage_days>0">@{{ f.haltage_days}}</span>
                                </td>
                                <td>
                                    <span ng-show="f.haltage_days>0">@{{  foreign_haltage_charge }}</span>
                                </td>
                                <td class="amount-right" width="68px">
                                    <span ng-show="f.haltage_days>0 && f.holtage_charge_flag==0">
                                    @{{ (f.haltage_days *  foreign_haltage_charge) |number:2 }}
                                    </span>

                                    <span class="ok" ng-if="f.holtage_charge_flag==1 && f.haltage_days>0">
                                        Paid
                                    </span>
                                </td>
                            </tr>
                            {{-- <tr ng-repeat="f in haltagesLocalTruck">
                                <td style="vertical-align: middle;padding: 0; " ng-if="$index==0"
                                    rowspan="@{{haltagesLocalTruck.length}}"><b> &nbsp;&nbsp;&nbsp; Local Truck</b>
                                </td>
                                <td></td>

                                <td colspan="2"><b></b></td>
                                <td colspan="2"><b></b></td>


                                <td>
                                    <span style="padding-left: 3%;" ng-show="f.haltage_day>0">@{{ f.haltage_day}}</span>
                                    <span style="padding-left: 3%;" ng-show="f.haltage_day>0"> X </span>
                                    <span style="padding-left: 3%;"
                                          ng-show="f.haltage_day>0">@{{  f.rate_of_charges }}</span>
                                </td>
                                <td>@{{ f.haltage_day}} X @{{  f.rate_of_charges }}</td>

                                <td></td>
                                <td></td>
                                <td class="amount-right"
                                    style="width:20px; ">@{{ f.haltage_day *  f.rate_of_charges |number:2  }}</td>

                            </tr> --}}
                            <tr ng-show="haltagesTotalLocalTruck > 0">
                                <td style="vertical-align: middle;padding: 0; " ng-show="haltagesTotalLocalTruck > 0"
                                    rowspan="1"><b> &nbsp;&nbsp;&nbsp; Local Truck</b>
                                </td>
                                <td ng-show="haltagesTotalLocalTruck > 0">
                                    @{{ haltagesTotalLocalTruck }} <span style="font-size: 12px;"><i>(Number of Truck)</i></span>
                                </td>
                                <td colspan="6"></td>
                                <td ng-show="haltagesTotalLocalTruck > 0">
                                    @{{ haltagesTotalDayLocalTruck }}
                                </td>
                                <td ng-show="haltagesTotalLocalTruck > 0">
                                    @{{ local_haltage_charge }}
                                </td>
                                <td class="amount-right"
                                    style="width:20px; ">@{{ TotalHaltageLocalCharge |number:2  }}</td>
                            </tr>
                            <tr ng-if="haltagesForeignScaleWeight || haltagesForeignReceiveWeight || TotalHaltageForeignCharge || TotalHaltageLocalCharge">
                                <td></td>
                                <td></td>
                                <td class="amount-right">@{{  haltagesForeignScaleWeight|number:2 }}</td>
                                <td class="amount-right">@{{ haltagesForeignReceiveWeight|number:2 }}</td>
                                <td class="amount-right" colspan="6"></td>
                                <td class="amount-right" style="width:20px;">
                                    <span ng-show="TotalHaltageForeignCharge || TotalHaltageLocalCharge">@{{ (TotalHaltageForeignCharge + TotalHaltageLocalCharge) |number:2}}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>


                {{--    <tr ng-repeat="f in haltagesForeignTruck">
                        <td></td>
                        <td rowspan="f.length"></td>
                        <td>@{{ f.haltage_days }}</td>
                        <td>Period</td>
                        <th>@{{ f.rate_of_charges }} </th>
                        <td>@{{ f.length }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>--}}




                {{--============================Night Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2">Charge:</th>
                    <td><b>Number</b></td>
                    <td><b>Rate</b></td>
                    <th colspan="4"></th>
                    <td></td>
                </tr>

                {{--<tr>--}}
                    {{--<td></td>--}}
                    {{--<th colspan="2"> Night Charge:</th>--}}
                    {{--<td class="td-center">--}}
                        {{--<span ng-if="nightTotalForeignTruck>0">@{{ nightTotalForeignTruck }}</span>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<span ng-if="nightTotalForeignTruck>0">@{{ rate_of_night_charge }}</span>--}}
                    {{--</td>--}}
                    {{--<td colspan="4">--}}
                    {{--</td>--}}
                    {{--<td class="amount-right">--}}
                        {{--<span ng-if="nightTotalForeignTruck>0">@{{ TotalForeignNightCharge }}</span>--}}
                    {{--</td>--}}


                {{--</tr>--}}

                {{--Documentation Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2">
                        Documentation Charge:
                    </th>
                    <td class="td-center">
                        <span ng-if="numberOfDocuments">@{{ numberOfDocuments }}</span>
                    </td>
                    <td>
                        <span style="padding-left:5%" ng-if="numberOfDocuments">@{{ documentCharges }}</span>
                    </td>

                    <th style="padding-left:6%" colspan="4">

                    </th>
                    <td class="amount-right">
                        <span ng-if="numberOfDocuments">@{{ totalDocumentCharges | number:2}}</span>
                    </td>
                </tr>


                {{--=====================Holiday Charge================--}}

                {{--<tr>--}}
                    {{--<td></td>--}}
                    {{--<td rowspan="2" style="vertical-align: middle">--}}
                        {{--<b>Holiday charge:</b>--}}
                    {{--</td>--}}
                    {{--<td>Unloading</td>--}}


                    {{--<td class="td-center">--}}
                        {{--<span ng-if="TotalForeignHolidayCharge>0">@{{ holidayTotalForeignTruck }}</span>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<span ng-if="TotalForeignHolidayCharge>0">@{{ foreign_holiday_charge }}</span>--}}
                    {{--</td>--}}
                    {{--<td colspan="4"></td>--}}
                    {{--<td class="amount-right">--}}
                        {{--<span ng-if="TotalForeignHolidayCharge>0">@{{ TotalForeignHolidayCharge }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}

                {{--<tr>--}}
                    {{--<td></td>--}}
                    {{--<td>Loading</td>--}}

                    {{--<td class="td-center">--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ holidayTotalLocalTruck }}</span>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ local_holiday_charge }}</span>--}}
                    {{--</td>--}}
                    {{--<td colspan="4"></td>--}}
                    {{--<td class="amount-right">--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ TotalLocalHolidayCharge }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}


                {{--outstanding Charge--}}

                {{--<tr>
                    <td></td>
                    <th>(viii). Outstanding Bill(if any):</th>
                    <td></td>
                    <td></td>
                    <td colspan="4"></td>

                    <td></td>
                </tr>--}}
            </table>
            <br>
        </div>

        {{--Equipment hired period distance rate--}}

        {{--  <div class="col-md-12 box-shadow" style="padding: 0;">

          -- <table class="table-bordered td-bordered" border="0" style="width: 100%">
                  <tr>
                      <td></td>
                      <th colspan="2" style="width: 50%"></th>
                      <td></td>

                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>

                  </tr>
             {{-- <tr>
                      <td><b>4.</b></td>
                      <th> Equipment Hire Period Distance Rate Charge:</th>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

                  <tr>
                      <td></td>
                      <td>(a) Mobile Crane</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

                  <tr>
                      <td></td>
                      <td>(b) Forklift</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>
                  <tr>
                      <td></td>
                      <td>(c) Tarpaulin</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

              </table>
            <br>
        </div>
        <div class="clearfix">&nbsp;</div>--}}

        <div class="col-md-12 box-shadow" style="padding:0">


            <table border="1" class="table table-bordered td-bordered" style="width: 100%">

                <tr>
                    <td></td>
                    <th style="width: 70%; vertical-align: middle">

                    </th>

                    <td class="amount-right">
                        <b>Sub Total Taka:</b> <span ng-if="TotalAmount > 0">@{{ TotalAmount |ceil|number:2 }}</span>
                    </td>


                </tr>

                <tr>
                    <td><b>4.</b></td>
                    <td></td>
                    <td class="amount-right">
                        <b>VAT
                            <span ng-if="consignee_vat_flag==0">(15%)</span>:
                        </b>
                        <span style="float:right" ng-if="consignee_vat_flag==0 && TotalAmount > 0">@{{  TotalAmount *15/100 |ceil|number:2}}</span>
                        <span style="float:right" ng-if="consignee_vat_flag==1">@{{ 'No VAT' }}</span>
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <b>Grand Total Taka:</b> <span ng-if="grand_total > 0">@{{ grand_total |number:2 }}</span>
                    </td>


                </tr>

                <tr>
                    <td></td>
                    <td colspan="2"><b>In Words (Taka)</b>

                        <span id="totalInWord" class="text-capitalize" style="text-decoration: underline"></span>
                        @{{inword}}
                    </td>


                </tr>

            </table>

        </div>

    </div>


    <div class="col-md-12 ForPdf" ng-show="ForPdf">

        <br><br><br><br><br><br>

        <div class="col-md-4">
            <span>Signature Of </span><br>
            <span>Warehouse Superintendent</span>
        </div>

        <div class="col-md-4">
            <span>Signature Of </span><br>
            <span>Warehouse Superintendent</span>
        </div>

        <div class="col-md-4">
            <span></span><br>
            <span>Checked & Verified By</span>
        </div>

    </div>


</div>
{{--END PDF Div--}}
{{--
<script type="text/javascript">
    $('#receive_datetime').datetimepicker({
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss'
    });
</script>--}}
<script>
    $(document).ready(function () {
        // create DateTimePicker from input HTML element

        /*   $("#receive_datetime").kendoDateTimePicker({
         //value: new Date(),
         format: "dd/MM/yyyy hh:mm tt",
         dateInput: true,
         animation: {
         close: {
         effects: "zoom:out",
         duration: 300
         }}
         });*/

        $('#receive_datetime').datetimepicker({
            /* formatTime:'H:i',
             formatDate:'d.m.Y'*/

            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'


        });
    });


</script>
