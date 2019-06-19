<style type="text/css">
    #one td {
        border: 1px solid white;
        border-color: black;
    }

    #one th {
        border: 1px solid white;
        border-color: black;
    }

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



</style>
<div id="aa" class="col-md-12 text-center" ng-animate="'animate'"{{-- ng-show="AssessmentFound"--}}>
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
                    <b> Manifest/Export Application No & Date:</b>
                </td>

                <td>
                    <u> @{{ ManifestNo  +' & '+ Mani_date }}</u>
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
                    <u> @{{ Bill_No +' & '+ Bill_date}}</u>
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

            <tr>
                <td></td>
                <td></td>

                <td><b>Shed / Yard No.</b></td>
                <td>@{{ posted_yard_shed }}</td>
            </tr>


        </table>

        <br> <br>
    </div>


    <div class="col-md-12" style=" text-align: left;padding: 0">

        <b><p style="padding: 3px ;box-shadow: 1px 0px 5px 2px #969696; width: 250px">PARTICULARS OF CHARGES DUE</p></b>

    </div>


    <div class="col-md-12 td-left" id="assessment" style="padding: 0;">

        {{--==========================Warehouse Rent====START=========================--}}

        <div class="col-md-12" style="padding: 0;">

            <table border="1" class="table table-bordered td-bordered"
                   style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">
                <tr>

                    <td></td>
                    <th colspan="2" style="width: 43%"></th>
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
                    <td>(i). Description of goods</td>
                    <td>
                        <span ng-show="totalItems">{{--Items= <b>@{{ totalItems }}</b> --}}
                        </span> @{{description_of_goods }}
                    </td>
                    <td colspan="5" rowspan="5" ng-if="WareHouseRentDay>=1">

                        <table class="tbl-td-center" border="1" ng-if="WareHouseRentDay>=1" width="100%">

                            <tr>
                                <td width="16%"><b>Item</b></td>
                                <td width="20%"><b>Basis Of Charge</b></td>
                                <td width="8%"><b>Slab</b></td>
                                <td width="11%"><b>Quantity</b></td>
                                <td width="12%" style="text-align: center"><b>Day</b></td>
                                <td width="16%"><b>Rate</b></td>
                                <td width="15%" class="amount-right"><b>Amount</b></td>


                            </tr>
                            {{--YARD--}}

                            <tbody ng-if="item_wise_shed_charge">
                            <tr>
                                <th colspan="7" class="ok">Shed</th>
                            </tr>

                            <tr ng-repeat="item in item_wise_shed_charge">

                                <td ng-class="{'error':item.dangerous=='1'}">@{{ item.Description }} <span
                                            ng-if="item.dangerous==1"><b>(@{{ item.dangerous|dangerous}}</b>)</span>
                                </td>
                                <td style="vertical-align: middle;text-align: center">@{{ item.item_type|item_type}}</td>
                                <td colspan="5">
                                    <table class="tbl-td-center" border="0" width="100%">


                                        <tr ng-if="ShowFirstSlab ||ShowSecondSlab || ShowThirdSlab">
                                            <td style="width: 13%; text-align: center"><b>1St</b></td>
                                            <td style="width: 18%"> @{{ item.item_quantity }}</td>
                                            <td style="width: 19%"> @{{ firstSlabDay }}</td>
                                            <td style="width: 26%">
                                                @{{ item.first_slab }}
                                                <span ng-show="item.dangerous=='1'">
                                                    X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}
                                                </span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity * firstSlabDay * danger * item.first_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowSecondSlab || ShowThirdSlab">
                                            <td class="td-center"><b>2nd </b></td>
                                            <td> @{{ item.item_quantity }}</td>
                                            <td> @{{ secondSlabDay }}</td>
                                            <td> @{{ item.second_slab }}
                                                <span ng-show="item.dangerous=='1'">X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity* secondSlabDay * danger * item.second_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowThirdSlab">
                                            <td class="td-center"><b>3rd</b></td>
                                            <td>@{{ item.item_quantity }}</td>
                                            <td>@{{ thirdSlabDay }}</td>
                                            <td>@{{item.third_slab}}
                                                <span ng-show="item.dangerous=='1'"> X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity* thirdSlabDay * danger * item.third_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>

                            </tbody>

                            {{--SHED--}}
                            <tbody ng-if="item_wise_yard_charge">
                            <tr>
                                <th class="ok" colspan="7">Yard</th>
                            </tr>

                            <tr ng-repeat="item in item_wise_yard_charge">

                                <td ng-class="{'error':item.dangerous=='1'}">@{{ item.Description }} <span
                                            ng-if="item.dangerous==1"><b>(@{{ item.dangerous|dangerous}}</b>)</span>
                                </td>
                                <td style="vertical-align: middle;text-align: center">@{{ item.item_type|item_type}}</td>
                                <td colspan="5">
                                    <table class="tbl-td-center" border="0" width="100%">


                                        <tr ng-if="ShowFirstSlab ||ShowSecondSlab || ShowThirdSlab">
                                            <td style="width: 13%; text-align: center"><b>1St</b></td>
                                            <td style="width: 18%"> @{{ item.item_quantity }}</td>
                                            <td style="width: 19%"> @{{ firstSlabDay }}</td>
                                            <td style="width: 26%">
                                                @{{ item.first_slab }}
                                                <span ng-show="item.dangerous=='1'">
                                                    X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}
                                                </span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity * firstSlabDay * danger * item.first_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowSecondSlab || ShowThirdSlab">
                                            <td class="td-center"><b>2nd </b></td>
                                            <td> @{{ item.item_quantity }}</td>
                                            <td> @{{ secondSlabDay }}</td>
                                            <td> @{{ item.second_slab }}
                                                <span ng-show="item.dangerous=='1'">X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity* secondSlabDay * danger * item.second_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowThirdSlab">
                                            <td class="td-center"><b>3rd</b></td>
                                            <td>@{{ item.item_quantity }}</td>
                                            <td>@{{ thirdSlabDay }}</td>
                                            <td>@{{item.third_slab}}
                                                <span ng-show="item.dangerous=='1'"> X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                            </td>
                                            <td class="amount-right">
                                                <b>=@{{Math.ceil(item.item_quantity* thirdSlabDay * danger * item.third_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>

                            </tbody>
                        </table>

                        <h3 style=" transform: rotate(-35deg);vertical-align: middle; text-align: center"
                            ng-if="firstSlabDay<1 && WareHouseRentDay<=0"> FT </h3>


                    </td>

                </tr>
                <tr id="one">
                    <td></td>
                    <td>(ii). No Of Package</td>
                    <td><span ng-show="package_no">@{{ package_no }}</span> <span
                                ng-if="package_type">@{{ package_type }}</span></td>
                    {{-- <td>(a) 1st slab</td>
                     <td><span ng-show="firstSlabDay">@{{ chargableTonForWarehouse }}</span></td>
                     <td><span ng-show="firstSlabDay">@{{ firstSlabDay }}</span></td>
                     <td><span ng-show="firstSlabDay">@{{ firstSlabCharge }}</span></td>
                     <td class="amount-right"><span ng-show="firstSlabDay">@{{ totalFirstSlabCharge|number:2 }}</span></td>--}}
                </tr>

                <tr id="one">
                    <td></td>
                    <td>(iii). Weight</td>
                    <td>

                        <form class="form-inline" ng-submit="changeBassisOfCharge(bassisOfCharge)" role="form">
                            <div class="form-group">
                                <label ng-show="bassisOfCharge">@{{ bassisOfCharge}} KGs</label>
                                <input style="width: 80px;" title="" class="form-control input-xs" type="text"
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
                    <td>(iv). Date of Unloading</td>

                    <td>

                        <span ng-if="receive_date">@{{receive_date |dateShort:"dd-MM-yyyy hh:mm:ss a" }}</span>
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
                    <td>(v). Free Period</td>
                    <td ng-model="freePeriod">
                                <span ng-show="freeEndDay">
                                    @{{ receive_date |dateShort:"dd-MM-yyyy"}}
                                    - @{{freeEndDay|dateShort:"dd-MM-yyyy"  }} = FT
                                </span>
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td>(vi). Rent due Period</td>
                    <td ng-model="rentDuePeriod">
                        <span ng-if="firstSlabDay<1" class="error">No Rent</span>
                        <span ng-if="WareHouseRentDay>=1">
                                    @{{ WarehouseChargeStartDay |dateShort:"dd-MM-yyyy"}}
                            - @{{deliver_date|dateShort:"dd-MM-yyyy" }}= @{{ WareHouseRentDay }}
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
            <br>

            <table class="table table-bordered" style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">


                <tr>
                    <td></td>
                    <th colspan="3" style="width: 50%"></th>
                    <th colspan="4" style="width: 50%"></th>
                    <td></td>


                </tr>

                <tr>
                    <td><b>2.</b></td>
                    <th style="width:590px"> Handling Operation</th>
                    <th colspan="2">Handling Mode</th>
                    <th colspan="4">Ton X Rate</th>
                    <th class="amount-right">Amount</th>
                </tr>

                <tr>
                    <td></td>
                    <th rowspan="2" style="vertical-align: middle">(i) Unloading:</th>

                    <td colspan="2">(a) Manual</td>
                    <td colspan="4">
                        <span ng-if="transshipment  && parishable==1">@{{ chargeable_weight }}
                            X @{{ OffloadLabourCharge }}</span>
                        <span ng-if="!transshipment && OffloadLabour">@{{ OffloadLabour }}
                            X @{{ OffloadLabourCharge }}</span>

                    </td>
                    <td class="amount-right">
                        <span ng-if="transshipment && parishable==1">@{{ TotalForOffloadLabour |number:2}}</span>
                        <span ng-if="!transshipment && OffloadLabour">@{{ TotalForOffloadLabour |number:2}}</span>


                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td colspan="2">
                        (b) Equipment
                    </td>
                    <td colspan="4">
                         <span ng-if="OffLoadingEquip && !transshipment">
                            @{{ OffLoadingEquip }} X @{{ OffLoadingEquipCharge }}
                             <span ng-if="shifting_flag==1"> X 2</span>
                        </span>

                    </td>
                    <td class="amount-right">
                        <span ng-if="OffLoadingEquip && !transshipment">
                            @{{ TotalForOffloadEquip|number:2 }}
                        </span>
                    </td>
                </tr>

                {{--Loading--}}


                <tr>
                    <td></td>
                    <th rowspan="2">(ii) Loading:</th>
                    <td colspan="2">(a) Manual</td>
                    <td colspan="4">
                         <span ng-if="loadLabour>=0 && approximate_delivery_type==0 && (parishable || !transshipment)">
                            @{{ loadLabour }}X @{{ loadLabourCharge }}
                        </span>
                    </td>
                    <td class="amount-right">
                        <span ng-if="TotalForloadLabour>=0 && approximate_delivery_type==0 && (parishable || !transshipment)">@{{ TotalForloadLabour|number:2 }}</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        (b) Equipment
                    </td>
                    <td colspan="4">
                        <span ng-if="loadingEquip && approximate_delivery_type==1 && (parishable || !transshipment)">
                            @{{ loadingEquip }} X @{{ loadingEquipCharge }}
                        </span>
                    </td>
                    <td class="amount-right"><span
                                ng-if="TotalForloadEquip && approximate_delivery_type==1 && (parishable || !transshipment)">@{{ TotalForloadEquip|number:2 }}</span>
                    </td>
                </tr>

                {{--Restcking--}}


                <tr>
                    <td></td>
                    <th rowspan="2">(iii) Re-Stacking:</th>
                    <td colspan="2">(a) Manual</td>
                    <td colspan="4">

                    </td>
                    <td class="amount-right">

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        (b) Equipment
                    </td>
                    <td colspan="4">
                    </td>

                    <td class="amount-right">

                    </td>
                </tr>

                {{--Removal--}}


                <tr>
                    <td></td>
                    <th rowspan="2">(iv) Removal:</th>
                    <td colspan="2">(a) Manual</td>
                    <td colspan="4">

                    </td>
                    <td class="amount-right">

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        (b) Equipment
                    </td>
                    <td colspan="4">

                    </td>
                    <td class="amount-right">

                    </td>
                </tr>
                {{--Transhipment--}}

                <tr>
                    <td></td>
                    <th rowspan="2">(v) Transhipemnt:</th>

                    <td colspan="2">
                        (a) Manual

                    </td>
                    <td colspan="4">
                         <span ng-if="loadLabour>0 && transshipment && !parishable">
                             @{{ loadLabour }}X @{{ loadLabourCharge }}
                        </span>
                    </td>
                    <td class="amount-right">
                        <span ng-if="TotalForloadLabour>=0 && transshipment && !parishable">
                            @{{ TotalForloadLabour|number:2 }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>

                    <td colspan="2">(b) Equipment</td>
                    <td colspan="4"></td>
                    <td class="textRight"></td>
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
                    <th colspan="3" style="width: 50%"></th>

                    <td colspan="4" style="width: 50%"></td>
                    <td></td>

                </tr>


                <tr>
                    <td><b>3.</b></td>

                    <th style="width:590px"> Other Dues:</th>
                    <th colspan="2"></th>
                    <th colspan="4">
                        Day X Rate
                    </th>
                    <td></td>
                </tr>
                {{--Truck Entrance fee--}}

                <tr>
                    <td></td>
                    <th rowspan="2">(i). Entrance Fee:</th>
                    <td colspan="2">(a). Foreign Truck</td>
                    <td colspan="4">
                        <span style="padding-left: 3%;" ng-if="totalForeignTruck">@{{ totalForeignTruck }}</span>
                        <span style="padding-left: 3%;" ng-if="totalForeignTruck"> X </span>
                        <span style="padding-left: 3%;" ng-if="totalForeignTruck">@{{ entrance_fee_foreign }}</span>
                    </td>

                    <td class="amount-right">
                        <span ng-if="totalForeignTruckEntranceFee>0"> @{{ totalForeignTruckEntranceFee|number:2 }}</span>
                    </td>
                </tr>

            <!--tr>
                    <td></td>
                    <td>(a). Foreign Truck</td>
                    <td><span ng-if="totalForeignTruck">@{{ totalForeignTruck }} X @{{ transportEntranceCharges }}</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <span ng-if="totalForeignTruckEntranceFee>0"> @{{ totalForeignTruckEntranceFee|number:2 }}</span>
                    </td>
                </tr-->

                <tr>
                    <td></td>
                    <td colspan="2">(b). Local Transport @{{  local_transport  }}</td>
                    <td colspan="4">
                        <span style="padding-left: 3%;" ng-if="totalLocalTruck>0">@{{ totalLocalTruck }}</span>
                        <span style="padding-left: 3%;" ng-if="totalLocalTruck>0"> X </span>
                        <span style="padding-left: 3%;" ng-if="totalLocalTruck>0">@{{ entrance_fee_local }}</span>
                    </td>
                    <td class="amount-right">
                        <span ng-if="totalLocalTruckEntranceFee>0">
                            @{{ totalLocalTruckEntranceFee|number:2 }}
                        </span>
                    </td>

                </tr>

                {{--Carpenter Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="3">(ii). Carpenter charge:</th>
                    <td colspan="2"></td>
                    <th colspan="4">No. of packs X Rate</th>

                    <td></td>

                </tr>


                <tr>
                    <td></td>
                    <td colspan="2">
                        <nobr>(a). Opening/Closing</nobr>
                    </td>
                    <td colspan="4" style="">
                        <span style="padding-left: 10%;" ng-if="carpenterPackages">@{{ carpenterPackages }}</span>
                        <span style="padding-left: 6%;" ng-if="carpenterPackages"> X </span>
                        <span style="padding-left: 3%;"
                              ng-if="carpenterPackages">@{{ carpenterChargesOpenClose }}</span>
                    </td>


                    <td class="amount-right"><span
                                ng-if="totalcarpenterChargesOpenClose>0">@{{ totalcarpenterChargesOpenClose|number:2 }}</span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="2">(b). Repair</td>
                    <td colspan="4">
                        <span style="padding-left: 10%;"
                              ng-if="carpenterRepairPackages">@{{ carpenterRepairPackages }}</span><span
                                style="padding-left: 6%;" ng-if="carpenterRepairPackages"> X </span> </span><span
                                style="padding-left: 3%;"
                                ng-if="carpenterRepairPackages">@{{ carpenterChargesRepair }}</span>
                    </td>
                    <td class="amount-right"><span
                                ng-if="totalcarpenterChargesRepair>0"> @{{ totalcarpenterChargesRepair |number:2}}</span>
                    </td>
                </tr>
                {{--Haltage Charge--}}

                <tr>
                    <td></td>
                    <th>(iii). Haltage Charge:</th>
                    <td colspan="6"></td>

                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td colspan="8" style="padding: 0;">

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td style="width: 51%" colspan="4"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="">Truck</th>
                                <th>Truck No.</th>
                                <!--td> Days</td-->
                                <th colspan="2">Entry Date</th>
                                <th colspan="2">Unloading Date</th>
                                <th colspan="2">Day X Rate</th>
                                <td></td>

                            </tr>
                            <tr ng-repeat="f in haltagesForeignTruck">
                                <td style="vertical-align: middle;padding: 0" ng-if="$index==0"
                                    rowspan="@{{haltagesForeignTruck.length}}"><b></b>(a). Foreign Truck
                                </td>
                                <td>@{{ f.truck_no }}</td>
                                <td colspan="2"><b>@{{ f.truckentry_datetime |stringToDate:"dd.MM.y h:mm a" }}</b></td>
                                <td colspan="2"><b>@{{ f.receive_datetime|stringToDate:"dd.MM.y h:mm a" }}</b></td>
                            <!--td colspan="2">@{{ f.haltage_days }}</td-->
                            <!--td colspan="2" style="text-align: center">
                                    <b>(</b>@{{ f.truckentry_datetime |stringToDate:"dd.MM.y h:mm a" }}
                                    <br><b>To</b><br>
                                    @{{ f.receive_datetime|stringToDate:"dd.MM.y h:mm a" }} <b>)</b>
                                </td-->


                                <td colspan="2">
                                    <span style="padding-left: 3%;"
                                          ng-show="f.haltage_days>0">@{{ f.haltage_days}}</span>
                                    <span style="padding-left: 3%;" ng-show="f.haltage_days>0"> X </span>
                                    <span style="padding-left: 3%;"
                                          ng-show="f.haltage_days>0">@{{  f.rate_of_charges }}</span>
                                </td>
                                <td>
                                    <span ng-show="f.haltage_days>0 && f.holtage_charge_flag==0">
                                    @{{ (f.haltage_days *  f.rate_of_charges) |number:2 }}
                                    </span>

                                    <span class="ok" ng-if="f.holtage_charge_flag==1 && f.haltage_days>0">
                                        Paid
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="amount-right" colspan="8"></td>
                                <td class="amount-right" style="width:20px;">
                                    <span ng-if="TotalHaltageForeignCharge">@{{ TotalHaltageForeignCharge }}</span>
                                </td>
                            </tr>

                            <tr ng-repeat="f in haltagesLocalTruck">
                                <td style="vertical-align: middle" {{--ng-if="$index==0"--}}
                                rowspan="@{{haltagesLocalTruck.length}}">(b). Local Truck
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
                                <td class="amount-right"
                                    style="width:20px; ">@{{ f.haltage_day *  f.rate_of_charges |number:2  }}</td>

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


                {{--Weighment Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="2">(iv). Weighment Charge:</th>
                    <th><b>Truck</b>
                    </th>
                    <td>
                        <b>
                            <nobr>Total Truck</nobr>
                        </b>
                    </td>
                    <td colspan="4">
                        <b>Twice X Charge</b>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        (i) Foreign
                    </td>
                    <td>

                        <span ng-if="totalForeignTruck">&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; @{{ totalForeignTruck }}
                            X</span>
                    </td>
                    <td colspan="4">
                        <span ng-show="totalForeignTruck">2 &nbsp; X </span>
                        <span ng-show="totalForeignTruck">@{{ weightment_measurement_charges }}</span>
                    </td>
                    <td class="amount-right">
                        <span ng-show="weightmentChargesForeign"> @{{ weightmentChargesForeign | number:2 }}</span>
                    </td>

                </tr>

                <tr>
                    <td></td>

                    <td></td>
                    <td>
                        (ii) Local
                    </td>
                    <td>

                        <span ng-if="local_truck_weighment">  @{{ local_truck_weighment }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span>
                    </td>
                    <td colspan="4">
                        <span ng-show="weightmentChargesLocal">2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span>
                        <span ng-show="weightmentChargesLocal">
                            @{{ weightment_measurement_charges }}
                        </span>
                    </td>

                    <td class="amount-right">
                        <span ng-show="weightmentChargesLocal"> = @{{ weightmentChargesLocal|number:2 }}</span>
                    </td>
                </tr>


                {{--============================Night Charge--}}

                <tr>
                    <td></td>
                    <th>Charge:</th>
                    <td><b>Number</b></td>
                    <td><b>Rate</b></td>
                    <th colspan="4"></th>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <th>(v). Night Charge:</th>
                    <td class="td-center">
                        <span ng-if="nightTotalForeignTruck>0">@{{ nightTotalForeignTruck }}</span>
                    </td>
                    <td>
                        <span ng-show="nightTotalForeignTruck">@{{ rate_of_night_charge }}</span>
                    </td>
                    <td colspan="4">
                    </td>
                    <td class="amount-right">
                        <span ng-show="nightTotalForeignTruck">@{{ TotalForeignNightCharge }}</span>
                    </td>


                </tr>

                {{--Documentation Charge--}}

                <tr>
                    <td></td>
                    <th>
                        (vi). Documentation Charge:
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

                <tr>
                    <td></td>
                    <th>(vii). Holiday charge:</th>
                    <td>

                    </td>
                    <td>

                    </td>

                    <td colspan="4"></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) Foreign:</td>
                    <td class="td-center">
                        <span ng-if="TotalForeignHolidayCharge>0">@{{ holidayTotalForeignTruck }}</span>
                    </td>
                    <td>
                        <span ng-if="TotalForeignHolidayCharge>0">@{{ foreign_holiday_charge }}</span>
                    </td>

                    <td colspan="4"></td>
                    <td class="amount-right" style="width:20px; ">
                        <span ng-if="TotalForeignHolidayCharge>0">@{{ TotalForeignHolidayCharge }}</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) Local:</td>
                    <td>
                        <span ng-if="TotalLocalHolidayCharge>0">@{{ holidayTotalLocalTruck }}</span>
                    </td>
                    <td>
                        <span ng-if="TotalLocalHolidayCharge>0">@{{ local_holiday_charge }}</span>
                    </td>

                    <td colspan="4"></td>
                    <td class="amount-right" style="width:20px; ">
                        <span ng-if="TotalLocalHolidayCharge>0">@{{ TotalLocalHolidayCharge }}</span>
                    </td>
                </tr>

                {{-- <tr>
                     <td></td>
                     <td colspan="8" style="padding: 0;">

                         <table class="table table-bordered">
                             <tbody>
                             <tr>
                                 <td style="width: 51%" colspan="4"></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                             </tr>
                             <tr>
                                 <td>Name</td>
                                 <td>Truck No.</td>
                                 <td> Date</td>
                                 <td colspan="4"> No OF Truck X Rate</td>
                                 <td></td>
                             </tr>
                             <tr ng-repeat="f in holidayForeignTruck">
                                 <td style="vertical-align: middle" ng-if="$index==0"
                                     rowspan="@{{holidayForeignTruck.length}}"><span>(a).</span> Foreign Truck
                                 </td>
                                 <td>@{{f.truck_type }} -@{{ f.truck_no  }}</td>

                                 <td> @{{ f.holiday | stringToDate:'dd.MM.y' }}</td>

                                 <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp; 1 X @{{ f.holiday_Charge }}</td>
                                 <td class="amount-right" style="width:20px; ">@{{ f.holiday_Charge |number:2  }}</td>


                             </tr>

                             <tr ng-repeat="f in holidayLocalTruck">
                                 <td style="vertical-align: middle" ng-if="$index==0"
                                     rowspan="@{{holidayLocalTruck.length}}"><span>(a).</span> Local Truck
                                 </td>
                                 <td>@{{ f.truck_no  }}</td>

                                 <td> @{{ f.holiday | stringToDate:'dd.MM.y' }}</td>


                                 <td>1 X @{{  f.holiday_Charge }}</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td class="amount-right" style="width:20px; ">@{{  f.holiday_Charge |number:2  }}</td>

                             </tr>

                             </tbody>
                         </table>

                     </td>


                 </tr>--}}


                {{--outstanding Charge--}}

                <tr>
                    <td></td>
                    <th>(viii). Outstanding Bill(if any):</th>
                    <td></td>
                    <td></td>
                    <td colspan="4"></td>

                    <td></td>
                </tr>
            </table>
            <br>
        </div>

        {{--Equipment hired period distance rate--}}

        <div class="col-md-12 box-shadow" style="padding: 0;">

            <table class="table-bordered td-bordered" border="0" style="width: 100%">
                <tr>
                    <td></td>
                    <th colspan="2" style="width: 50%"></th>
                    <td></td>

                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>
                <tr>
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
        <div class="clearfix">&nbsp;</div>

        <div class="col-md-12 box-shadow" style="padding:0">


            <table border="1" class="table table-bordered td-bordered" style="width: 100%">

                <tr>
                    <td></td>
                    <th rowspan="3" style="width: 70%; vertical-align: middle"> VAT:</th>

                    <td class="amount-right">
                        <b>_______________________________________ <br>
                            Sub Total Taka:</b> @{{ TotalAmount |ceil|number:2 }}
                    </td>


                </tr>

                <tr>
                    <td><b>5.</b></td>

                    <td class="amount-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @{{ TotalAmount *15/100 |ceil|number:2}}
                    </td>

                </tr>
                <tr>
                    <td></td>

                    <td class="amount-right">
                        <b> _______________________________________ <br>
                            Grand Total Taka:</b> @{{ grand_total |number:2 }}
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
