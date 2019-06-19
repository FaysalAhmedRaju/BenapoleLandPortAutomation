<div id="aa" class="col-md-12 text-center" ng-animate="'animate'"{{-- ng-show="AssessmentFound"--}}>
    {{--START PDF Div--}}

    <div class="col-md-12 ForPdf" ng-show="ForPdf">

        <img src="{{ URL::asset('img/Logo_BSBK.gif') }}" style="float: left; width: 120px">
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
                    <b> Manifest/Export Application No & Data:</b>
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


    <div class="col-md-12 td-left" id="assessment" style="box-shadow: 0px 0px 5px 1px darkgrey">

        {{--==========================Warehouse Rent====START=========================--}}

        <div class="col-md-12" style="padding: 0">

            <table class="table table-bordered" style="width: 100%">
                <tr>
                    <td></td>
                    <th colspan="2" style="width: 43%"></th>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr>
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

                <tr>
                    <td></td>
                    <td>(i). Description of goods</td>
                    <td>
                        <span ng-show="totalItems">{{--Items= <b>@{{ totalItems }}</b> --}}
                            <br> </span> @{{description_of_goods }}
                    </td>
                    <td colspan="5" rowspan="5" ng-if="WareHouseRentDay>=1">

                        <table border="1">
                            <tr>
                                <td>Item</td>
                                <td width="120px"><b>Basis of Charge</b></td>
                                <td style="text-align: right"><b>Quantity X Day X Rate =Amount</b></td>
                            </tr>
                            <tr ng-repeat="item in item_wise_charge">

                                <td ng-class="{'error':item.dangerous=='1'}">@{{ item.Description }} <span
                                            ng-if="item.dangerous==1"><b>(@{{ item.dangerous|dangerous}}</b>)</span>
                                </td>
                                <td style="vertical-align: middle;text-align: center">@{{ item.item_type|item_type}}</td>
                                <td colspan="2">
                                    <table border="0">
                                        <tr ng-if="ShowFirstSlab ||ShowSecondSlab || ShowThirdSlab">
                                            <td style="width:90px;"><b>1St Slab</b></td>
                                            <td style="width: 250px;text-align: right">
                                                @{{ chargeable_weight }}X@{{ firstSlabDay }}X@{{ item.first_slab }}
                                                <span ng-show="item.dangerous=='1'"> X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                                <b>
                                                    =@{{Math.ceil(chargeable_weight * firstSlabDay * danger * item.first_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowSecondSlab || ShowThirdSlab">
                                            <td style="width:90px;"><b>2nd Slab</b></td>
                                            <td style="width: 200px;text-align: right">
                                                {{-- <span style="display: none"> @{{ item.dangerous=='1' ? danger=2 : 1 }}</span>--}}
                                                @{{ chargeable_weight }}X@{{ secondSlabDay }}X@{{ item.second_slab }}
                                                <span ng-show="item.dangerous=='1'">X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                                <b>=@{{Math.ceil(chargeable_weight* secondSlabDay * danger * item.second_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                        <tr ng-if="ShowThirdSlab">
                                            <td style="width:90px;"><b>3rd Slab</b></td>
                                            <td style="width: 200px;text-align: right">

                                                @{{ chargeable_weight }}X@{{ thirdSlabDay }}X@{{item.third_slab}} <span
                                                        ng-show="item.dangerous=='1'"> X @{{ item.dangerous=='1' ? danger=2 : danger=1 }}</span>
                                                <b>=@{{Math.ceil(chargeable_weight* thirdSlabDay * danger * item.third_slab)|number:2}}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>
                        </table>


                    </td>

                </tr>
                <tr>
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

                <tr>
                    <td></td>
                    <td>(iii). Weight</td>
                    <td>
                        <span ng-show="bassisOfCharge">@{{ bassisOfCharge}} KGs</span>


                        <form ng-submit="changeBassisOfCharge(bassisOfCharge)">
                            <input class="form-control" type="text" name="bassisOfCharge"
                                   id="bassisOfCharge" ng-model="bassisOfCharge"
                                   ng-show="role_id != 5">
                            <span ng-if="role_id == 5">@{{bassisOfCharge}}</span>

                        </form>


                        <span style="color: #b92c28;" id="changeBassisOfChargeError"
                              ng-show="changeBassisOfChargeError">@{{changebassisOfChargeErrorMsgTxt}}</span>

                        <span style="color: #3e8f3e;" id="changeBassisOfChargeSuccMsg"
                              ng-show="changeBassisOfChargeSuccMsg">@{{ bassisOfChargeSuccMsgTxt}}</span>
                    </td>
                </tr>


                <tr>
                    <td></td>
                    {{-- <td ng-click="changeReceivedayOption(receive_date)">
                         <span ng-show="receive_date">@{{ receive_date |dateShort:'medium'}}</span>

                     </td>--}}
                    <td>(iv). Date of Unloading</td>

                    <td>

                        <span>@{{receive_date |dateShort:'mediumDate' }}</span>
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


                <tr>
                    <td></td>
                    <td>(v). Free Period <br> (From/To)</td>
                    <td>
                                <span ng-show="freeEndDay">
                                    @{{ receive_date |dateShort:'mediumDate'}}
                                    - @{{freeEndDay|dateShort:'mediumDate'  }} = FT
                                </span>
                    </td>
                </tr>


                <tr>
                    <td></td>
                    <td>(vi). Rent due Period <br> (From/To)</td>
                    <td>
                        <span ng-if="firstSlabDay<1" class="error">No Rent</span>
                        <span ng-if="WareHouseRentDay>=1">
                                    @{{ WarehouseChargeStartDay |dateShort:'mediumDate'}}
                            - @{{deliver_date|dateShort:'mediumDate' }}= @{{ WareHouseRentDay }}
                                </span>
                    </td>
                    <td></td>


                    <td colspan="2">

                    </td>
                    <td colspan="2" style="" class="amount-right">
                        <b>W.R Total:</b> <span ng-if="TotalWarehouseCharge>0" style="width: 200px;">
                            @{{ TotalWarehouseCharge |number:2}}</span>
                        {{--text-align: center!important;--}}

                    </td>

                </tr>

            </table>


        </div>

        {{--==========================Warehouse Rent====END=========================--}}

        {{--==========================Handling Operation ====START=========================--}}

        <div class="col-md-12" style="padding: 0">
            <br>

            <table class="table table-bordered" style="width: 100%">


                <tr>
                    <td></td>
                    <th colspan="3"></th>

                    <td></td>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>

                <tr>
                    <td><b>2.</b></td>
                    <th style="width:590px"> Handling Operation</th>
                    <th>Handling Mode</th>
                    <th colspan="2">Ton X Rate</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th>Amount</th>
                </tr>

                <tr>
                    <td></td>
                    <th rowspan="2" style="vertical-align: middle">(i) Unloading:</th>
                    <td>(a) Manual</td>
                    <td  colspan="2">
                        <span ng-if="transshipment  && parishable==1">@{{ chargeable_weight }}
                            X @{{ OffloadLabourCharge }}</span>
                        <span ng-if="!transshipment && OffloadLabour">@{{ OffloadLabour }}
                            X @{{ OffloadLabourCharge }}</span>

                    </td>
                    <th>

                    </th>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <span ng-if="transshipment && parishable==1">@{{ TotalForOffloadLabour |number:2}}</span>
                        <span ng-if="!transshipment && OffloadLabour">@{{ TotalForOffloadLabour |number:2}}</span>


                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td>
                        (b) Equipment
                    </td>
                    <td colspan="2">
                         <span ng-if="OffLoadingEquip && !transshipment">
                            @{{ OffLoadingEquip }} X @{{ OffLoadingEquipCharge }}
                             <span ng-if="shifting_flag==1"> X 2</span>
                        </span>

                    </td>

                    <td></td>
                    <td></td>
                    <td></td>
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
                    <td>(a) Manual</td>
                    <td colspan="2">
                         <span ng-if="loadLabour>=0 && approximate_delivery_type==0 && !transshipment">
                            @{{ loadLabour }}X @{{ loadLabourCharge }}
                        </span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <span ng-if="TotalForloadLabour>=0 && approximate_delivery_type==0 && !transshipment">@{{ TotalForloadLabour|number:2 }}</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        (b) Equipment
                    </td>
                    <td colspan="2">
                        <span ng-if="approximate_delivery_type==1 && !transshipment">
                            @{{ loadingEquip }} X @{{ loadingEquipCharge }}
                        </span>
                    </td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right"><span
                                ng-if="approximate_delivery_type==1 && !transshipment">@{{ TotalForloadEquip|number:2 }}</span>
                    </td>
                </tr>

                {{--Restcking--}}


                <tr>
                    <td></td>
                    <th rowspan="2">(iii) Re-Stacking:</th>
                    <td>(a) Manual</td>
                    <td colspan="2">

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        (b) Equipment
                    </td>
                    <td>

                    </td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">

                    </td>
                </tr>

                {{--Removal--}}


                <tr>
                    <td></td>
                    <th rowspan="2">(iv) Removal:</th>
                    <td>(a) Manual</td>
                    <td colspan="2">

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        (b) Equipment
                    </td>
                    <td>

                    </td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">

                    </td>
                </tr>
                {{--Transhipment--}}

                <tr>
                    <td></td>
                    <th rowspan="2">(v) Transhipemnt:</th>
                    <td>
                      (a) Manual

                    </td>
                    <td colspan="2">
                         <span ng-if="loadLabour>0 && transshipment">
                             @{{ loadLabour }}X @{{ loadLabourCharge }}
                        </span>
                    </td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <span ng-if="TotalForloadLabour>=0 && transshipment">
                            @{{ TotalForloadLabour|number:2 }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>

                    <td>(b) By equipment:</td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>

                {{--==========================Handling Operation ====END=========================--}}

                {{--========================== Other Dues ====START=========================--}}


                <tr>
                    <td></td>
                    <th colspan="3" style="width: 50%"></th>

                    <td></td>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>


                <tr>
                    <td><b>3.</b></td>
                    <th style=""> Other Dues:</th>
                    <th>Quantity</th>
                    <th></th>
                    <th style="width:550px"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                {{--Truck Entrance fee--}}

                <tr>
                    <td></td>
                    <th>(i). Truck Entrance Fee:</th>
                    <td></td>
                    <th></th>
                    <th colspan="2"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a). Foreign Truck</td>
                    <td><span ng-if="totalForeignTruck">@{{ totalForeignTruck }} X @{{ entranceFee }}</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        <span ng-if="totalForeignTruckEntranceFee>0"> @{{ totalForeignTruckEntranceFee|number:2 }}</span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b). Local Truck</td>
                    <td>
                        <span ng-if="numberOfExtraTuck>0"> @{{ numberOfExtraTuck }} X @{{ transportEntranceCharges }}</span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right"><span
                                ng-if="totalTruckCharges>0"> @{{ totalTruckCharges|number:2 }}</span>
                    </td>

                </tr>

                {{--Carpenter Charge--}}

                <tr>
                    <td></td>
                    <th>(ii). Carpenter charge:</th>
                    <td> No. of packs</td>
                    <th>Rate</th>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td>(a). Opening/Closing:</td>
                    <td style="width: 25%;"><span ng-show="carpenterPackages">@{{ carpenterPackages }}</span></td>
                    <td>
                        <span ng-show="carpenterPackages">@{{ carpenterChargesOpenClose }}</span>
                    </td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right"><span
                                ng-if="totalcarpenterChargesOpenClose>0">@{{ totalcarpenterChargesOpenClose|number:2 }}</span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b). Repair</td>
                    <td><span ng-show="carpenterRepairPackages">@{{ carpenterRepairPackages }}</span></td>
                    <td><span ng-show="carpenterRepairPackages">@{{ carpenterChargesRepair }}</span></td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right"><span
                                ng-if="totalcarpenterChargesRepair>0"> @{{ totalcarpenterChargesRepair |number:2}}</span>
                    </td>
                </tr>

                {{--=====================Holiday Charge================--}}

                <tr>
                    <td></td>
                    <th>(iii). Holiday charge:</th>
                    <td></td>
                    <td></td>
                    <th colspan="2"></th>

                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="8">

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
                                <td> Time</td>
                                <td>Rate</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                            <tr ng-repeat="f in holidayForeignTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"
                                    rowspan="@{{holidayForeignTruck.length}}"><span>(a).</span> Foreign Truck
                                </td>
                                <td>@{{f.truck_type }} -@{{ f.truck_no  }}</td>

                                <td> @{{ f.holiday | stringToDate:'dd.MM.y' }}</td>

                                <td>1 X @{{ f.holiday_Charge }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
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


                </tr>


                {{--============================Night Charge--}}

                <tr>
                    <td></td>
                    <th>(iv). Night Charge:</th>
                    <td></td>
                    <td></td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        {{--<span ng-show="rate_of_night_charge">@{{ rate_of_night_charge }}</span>--}}
                    </td>
                    <td colspan="4">
                    </td>
                    <td class="amount-right">
                        <span ng-show="TotalForeignNightCharge">@{{ TotalForeignNightCharge }}</span>
                    </td>


                </tr>
                {{--Haltage Charge--}}

                <tr>
                    <td></td>
                    <th>(v). Haltage Charge:</th>
                    <td>Day</td>
                    <td>Period</td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td colspan="8">

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
                                <td style="">Name</td>
                                <td>Truck No. & Type</td>
                                <td> Days</td>
                                <td style="width: 200px;">Period</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                            <tr ng-repeat="f in haltagesForeignTruck">
                                <td style="vertical-align: middle;padding: 0" ng-if="$index==0"
                                    rowspan="@{{haltagesForeignTruck.length}}"><b></b>(a). Foreign Truck
                                </td>
                                <td>@{{ f.truck_no }}</td>
                                <td>@{{ f.haltage_days }}</td>
                                <td style="text-align: center">
                                    <b>(</b>@{{ f.truckentry_datetime |stringToDate:"dd.MM.y h:mm a" }}
                                    <br><b>To</b><br>
                                    @{{ f.receive_datetime|stringToDate:"dd.MM.y h:mm a" }} <b>)</b>
                                </td>


                                <td><span ng-show="f.haltage_days>0">@{{ f.haltage_days}}
                                        X @{{  f.rate_of_charges }}</span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="amount-right" style="width:20px; ">
                                    <span ng-show="f.haltage_days>0 && f.holtage_charge_flag==0">
                                    @{{ (f.haltage_days *  f.rate_of_charges) |number:2 }}
                                    </span>

                                    <span class="ok" ng-if="f.holtage_charge_flag==1 && f.haltage_days>0">
                                        Paid
                                    </span>
                                </td>


                            </tr>

                            <tr ng-repeat="f in haltagesLocalTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"
                                    rowspan="@{{haltagesLocalTruck.length}}">(b). Local Truck
                                </td>
                                <td>@{{ f.truck_no }}</td>
                                <td>@{{ f.haltage_day }}</td>
                                <td>
                                     <span ng-if="f.entry_dt">
                                     <b>(</b> @{{ f.entry_dt |stringToDate }} <br> <b>To</b>
                                     <br>@{{ f.delivery_dt|stringToDate }} <b>)</b>
                                     </span>

                                    <span ng-if="!f.entry_dt">No Date</span>
                                </td>


                                <td>@{{ f.haltage_day}} X @{{  f.rate_of_charges }}</td>

                                <td></td>
                                <td></td>
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
                {{--Documentation Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2">
                        (vi). Documentation Charge:


                    </th>

                    <td>
                        <span ng-if="numberOfDocuments">@{{ numberOfDocuments }} X @{{ documentCharges }}</span>
                    </td>
                    <th colspan="2"></th>

                    <td></td>
                    <td></td>
                    <td><span ng-if="numberOfDocuments">@{{ totalDocumentCharges | number:2}}</span></td>
                </tr>


                {{--Weighment Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="2">(vii). Weighment Charge:</th>
                    <td></td>
                    <td></td>
                    <td><b>Truck</b>
                    </td>
                    <td style="width: 220px;">
                        <b>Total Truck</b>
                    </td>
                    <td style="width: 220px;"><b>Twice</b></td>
                    <td style="width: 220px;"><b>Charge</b></td>
                    <td>

                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td>
                        (i) Foreign
                    </td>
                    <td>

                        <span ng-if="totalForeignTruck"> @{{ totalForeignTruck }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X</span>
                    </td>
                    <td><span ng-show="totalForeignTruck">2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span></td>
                    <td><span ng-show="totalForeignTruck">@{{ weightment_measurement_charges }}</span></td>
                    <td class="amount-right" style="width: 220px;">
                        <span ng-show="weightmentChargesForeign"> = @{{ weightmentChargesForeign | number:2 }}</span>
                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        (ii) Local
                    </td>
                    <td>

                        <span ng-if="local_truck_weighment">  @{{ local_truck_weighment }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span>
                    </td>
                    <td><span ng-show="weightmentChargesLocal">2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span></td>
                    <td><span ng-show="weightmentChargesLocal">@{{ weightment_measurement_charges }}</span></td>
                    <td class="amount-right">
                        <span ng-show="weightmentChargesLocal"> = @{{ weightmentChargesLocal|number:2 }}</span>
                    </td>
                </tr>

                {{--outstanding Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2">(viii). Outstanding Bill(if any):</th>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br>
        </div>

        {{--Equipment hired period distance rate--}}

        <div class="col-md-12" style="padding: 0">

            <table class="" style="width: 100%">
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
                    <th style="width:500px"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) Mobile Crane</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b) Forklift</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(c) Tarpaulin</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>
            <br>
        </div>


        <div class="col-md-12" style="padding:0">


            <table class="table table-bordered" style="width: 100%">

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
                            Grand Total Taka:</b> @{{ grand_total|number:2 }}
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
