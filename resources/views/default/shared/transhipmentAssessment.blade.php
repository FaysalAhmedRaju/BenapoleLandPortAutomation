






<div id="aa"  class="col-md-12 text-center" ng-animate="'animate'"{{-- ng-show="AssessmentFound"--}}>
    {{--START PDF Div--}}

    <div class="col-md-12 ForPdf"  ng-show="ForPdf">

        <img src="img/blpa.jpg" style="float: left">
        <p class="center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            <span style="font-size: 19px;">Assessment Report</span> <br>
        </p>
        <h5 style="text-align: right;padding-right: 35px;"> Date: <?php $timezone  = 6; //(GMT -6:00) EST (Dhaka)
            echo gmdate("F j, Y, g:i a", time() + 3600*($timezone+date("I")));
            ?>
        </h5>
    </div>





    <div class="col-md-12" style="padding: 0">

        <table  class="text-center-td-th" id="assessment" style="box-shadow: 0px 0px 5px 1px darkgrey; width: 100%">


            <tr>
                <td class="2">
                    <b> Manifest/Export Application No & Data:</b>
                </td>

                <td class="2">
                    <u> @{{ ManifestNo }}</u> <br> @{{Mani_date }}
                </td>



                <td class="2">
                    <b>Consignee:</b>
                </td>

                <td class="2">
                    @{{ Consignee }}
                </td>
            </tr>

            <tr>
                <td class="2">
                    <b>Bill Of Entry No & Date:</b>
                </td>

                <td class="2"> <span ng-show="m.be_date">C-</span>
                    <u> @{{ Bill_No }}</u> <br> @{{ Bill_date}}
                </td>



                <td class="2">
                    <b> Consignor:</b>
                </td>

                <td class="2">
                    @{{ Consignor }}
                </td>
            </tr>



            <tr>
                <td rowspan="2">
                    <b>Custom's Release Order No & Date:</b>
                </td>

                <td rowspan="2"> <span ng-show="m.be_date">C-</span>
                    <u> @{{ Custome_release_No}}</u> <br> @{{ Custome_release_Date}}
                </td>



                <td>
                    <b> C & F Agent:</b>
                </td>

                <td>
                    @{{ CnF_Agent }}
                </td>
            </tr>

            <tr>

                <td><b>Shed / Yard No.</b> </td>
                <td>@{{ Shed_Yard }}</td>
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
                    <th colspan="2" style="width: 50%"></th>
                    <td></td>

                    <th>M.Ton</th>
                    <th>Period</th>
                    <th>Rate</th>
                    <th>Amount</th>

                </tr>
                <tr>
                    <td><b>1.</b></td>
                    <th> Warehouse Rent</th>
                    <td></td>
                    <th style="width:400px">1.Warehouse Rent</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(i). Description of goods</td>
                    <td> <span ng-show="totalItems">Items= <b>@{{ totalItems }}</b>  <br> </span> @{{description_of_goods }} </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(ii). Quantity of goods</td>
                    <td><span ng-show="package_no">@{{ package_no }} Bags</span></td>
                    <td>(a) 1st slab</td>
                    <td><span ng-show="firstSlabDay">@{{ chargableTonForWarehouse }}</span></td>
                    <td><span ng-show="firstSlabDay">@{{ firstSlabDay }}</span></td>
                    <td><span ng-show="firstSlabDay">@{{ firstSlabCharge }}</span></td>
                    <td class="amount-right"><span ng-show="firstSlabDay">@{{ totalFirstSlabCharge|number:2 }}</span></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(iii). Chargable Ton</td>
                    <td><span ng-show="chargableTon">@{{ chargableTon |number:0 }} KGs</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    {{-- <td ng-click="changeReceivedayOption(receive_date)">
                         <span ng-show="receive_date">@{{ receive_date |dateShort:'medium'}}</span>

                     </td>--}}
                    <td>(iv). Date of Unloading</td>

                    <td>
                        <form ng-submit="changeReceivedayOption(receive_date)">
                            <input class="form-control datePicker" type="text" name="receive_datetime" id="receive_datetime" ng-model="receive_date" ng-disabled="show">
                        </form>
                        <span style="color: #3e8f3e;" id="changeReceivedaySucc" ng-show="changeReceivedaySucc">Date Changed Successfully!</span>
                    </td>


                    {{--<td data-toggle="modal" data-target="#myModal">


                    </td>--}}

                    <td>(b) 2nd slab</td>
                    <td><span ng-show="secondSlabDay">@{{ chargableTonForWarehouse }}</span></td>
                    <td><span ng-show="secondSlabDay">@{{ secondSlabDay }}</span></td>
                    <td><span ng-show="secondSlabDay">@{{ secondSlabCharge }}</span></td>
                    <td class="amount-right"><span ng-show="secondSlabDay">@{{ totalSecondSlabCharge|number:2 }}</span></td>
                </tr>


                <tr>
                    <td></td>
                    <td>(v). Free Period <br> (From/To)</td>
                    <td>
                                <span ng-show="receive_date">
                                    @{{ receive_date |dateShort:'mediumDate'}} - @{{freeEndDay|dateShort:'mediumDate'  }} = FT
                                </span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>




                <tr>
                    <td></td>
                    <td>(vi). Rent due Period <br> (From/To)</td>
                    <td><span ng-if="firstSlabDay<1" class="error">No Rent</span><span  ng-if="WareHouseRentDay>=1">@{{ WarehouseChargeStartDay |dateShort:'mediumDate'}} - @{{deliver_date|dateShort:'mediumDate' }}= @{{ WareHouseRentDay }}</span></td>
                    <td>(c) 3rd slab</td>
                    <td><span ng-show="thirdSlabDay">@{{ chargableTonForWarehouse }}</span></td>
                    <td><span ng-show="thirdSlabDay">@{{ thirdSlabDay }}</span></td>
                    <td> <span ng-show="thirdSlabDay">@{{ thirdSlabCharge }}</span></td>
                    <td class="amount-right"><span ng-show="thirdSlabDay">@{{ totalThirdSlabCharge|number:2 }}</span></td>
                </tr>


                <tr>
                    <td></td>
                    <td>(vii). Total Diem</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(viii). Shed/Yard</td>
                    <td><b>@{{ Shed_Yard }}</b> </td>
                    <td></td>
                    <td colspan="2">

                        {{-- <div class="checkbox">
                             <label>
                                 <input type="checkbox" ng-model="Percentage" ng-click="warehousePercentage(Percentage)"> 200%
                             </label>
                         </div>--}}

                    </td>
                    <td colspan="2" style="" class="amount-right"><b>Total:</b> <span  style="width: 200px;">@{{TotalWarehouseCharge|number:2  }}</span>
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
                    <th colspan="3" style="width: 50%"></th>

                    <td></td>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>

                <tr>
                    <td> <b>2.</b></td>
                    <th> Handling Operation</th>
                    <th> Quantity</th>
                    <th></th>
                    <th style="width:550px">2.Handling Charge</th>


                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <th>(i) Offloading:</th>
                    <td></td>
                    <td> </td>
                    <th>(i) Offloading Charges:</th>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) By manual labour:</td>
                    <td>@{{ OffloadLabour }} X @{{ OffloadLabourCharge }}</td>
                    <td> </td>
                    <td>(a)  By manual labour:</td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td  class="amount-right">@{{ TotalForOffloadLabour |number:2}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) By equipment:</td>
                    <td>@{{ OffLoadingEquip }} X @{{ OffLoadingEquipCharge }}</td>
                    <td> </td>
                    <td>(b) By equipment:</td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td  class="amount-right">@{{ TotalForOffloadEquip|number:2 }}</td>
                </tr>


                {{--Loading--}}


                <tr>
                    <td></td>
                    <th>(ii) Loading:</th>
                    <td></td>
                    <td> </td>
                    <th>(ii) Loading Charges:</th>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) By manual labour:</td>
                    <td>@{{ loadLabour }} X @{{ OffloadLabourCharge }}</td>
                    <td> </td>
                    <td>(a)  By manual labour:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td  class="amount-right">@{{ TotalForloadLabour|number:2 }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) By equipment:</td>
                    <td>@{{ loadingEquip }} X @{{ OffloadLabourCharge }}</td>
                    <td> </td>
                    <td>(b) By equipment:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">@{{ TotalForloadEquip|number:2 }}</td>
                </tr>


                {{--Restcking--}}


                <tr>
                    <td></td>
                    <th>(iii) Re-Stacking:</th>
                    <td></td>
                    <td> </td>
                    <th>(iii) Re-Stacking Charges:</th>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) By manual labour:</td>
                    <td></td>
                    <td> </td>
                    <td>(a)  By manual labour:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) By equipment:</td>
                    <td></td>
                    <td> </td>
                    <td>(b) By equipment:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>


                {{--Removal--}}


                <tr>
                    <td></td>
                    <th>(iv) Removal:</th>
                    <td></td>
                    <td> </td>
                    <th>(iv) Removal Charges:</th>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) By manual labour:</td>
                    <td></td>
                    <td> </td>
                    <td>(a)  By manual labour:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) By equipment:</td>
                    <td></td>
                    <td> </td>
                    <td>(b) By equipment:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>


                {{--Transhipment--}}


                <tr>
                    <td></td>
                    <th>(v) Transhipemnt:</th>
                    <td></td>
                    <td> </td>
                    <th>(v) Transhipemnt Charges:</th>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) By manual labour:</td>
                    <td></td>
                    <td> </td>
                    <td>(a)  By manual labour:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(b) By equipment:</td>
                    <td></td>
                    <td> </td>
                    <td>(b) By equipment:</td>

                    <td> </td>
                    <td></td>
                    <td></td>
                    <td class="textRight"></td>
                </tr>

            </table>
        </div>

        {{--==========================Handling Operation ====END=========================--}}

        {{--========================== Other Dues ====START=========================--}}


        <div class="col-md-12" style="padding: 0">

            <table class="table table-bordered" style="width: 100%">
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
                    <th> Other Dues:</th>
                    <th>Quantity</th>
                    <td></td>
                    <th style="width:400px">3.Other Charges:</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                {{--Truck Entrance fee--}}

                <tr>
                    <td></td>
                    <th>(i). Truck Entrance Fee: </th>
                    <td></td>
                    <th></th>
                    <th>(i). Truck Entrance Fee: </th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a). Foreign Truck </td>
                    <td><span ng-show="totalForeignTruck">@{{ totalForeignTruck }} X @{{ entranceFee }}</span></td>
                    <td></td>
                    <td>(a). Foreign Truck </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">@{{ totalForeignTruckEntranceFee|number:2 }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b). Local Truck </td>
                    <td>@{{ totalLocalTruck }} X @{{ entranceFee }}</td>
                    <td></td>
                    <td>(b). Local Truck </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">@{{ totalLocalTruckEntranceFee|number:2 }}</td>

                </tr>

                {{--Carpenter Charge--}}

                <tr>
                    <td></td>
                    <th>(ii). Carpenter charge: </th>
                    <td> No. of packs</td>
                    <td>Rate</td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td>(a). Opening/Closing: </td>
                    <td>@{{ carpenterPackages }}</td>
                    <td>@{{ carpenterChargesOpenClose }}</td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">@{{ totalcarpenterChargesOpenClose|number:2 }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b). Repair </td>
                    <td>@{{ carpenterRepairPackages }}</td>
                    <td>@{{ carpenterChargesRepair }}</td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">@{{ totalcarpenterChargesRepair |number:2}}</td>
                </tr>

                {{--=====================Holiday Charge================--}}

                <tr>
                    <td></td>
                    <th>(iii). Holiday charge: </th>
                    <td></td>
                    <td></td>
                    <th>(iii). Holiday charge: </th>
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
                                    rowspan="@{{holidayForeignTruck.length}}"><b></b>(a). Foreign Truck
                                </td>
                                <td>@{{f.truck_type }} -@{{ f.truck_no  }}</td>

                                <td> @{{ f.holiday | stringToDate:'d/M/yy h:mm a' }}</td>

                                <td>1 X @{{ f.holiday_Charge }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="amount-right" style="width:20px; ">@{{ f.holiday_Charge |number:2  }}</td>


                            </tr>

                            <tr ng-repeat="f in holidayLocalTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"
                                    rowspan="@{{holidayLocalTruck.length}}">(b). Local Truck
                                </td>
                                <td>@{{ f.truck_no  }}</td>

                                <td> @{{ f.holiday | stringToDate:'d/M/yy h:mm a' }}</td>


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
                    <th>(iv). Night Charge: </th>
                    <td></td>
                    <td></td>
                    <th>(iv). Night charge: </th>
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
                            <tr ng-repeat="f in nightForeignTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"  rowspan="@{{nightForeignTruck.length}}"><b></b>(a). Foreign Truck</td>
                                <td>@{{ f.truck_no }}</td>

                                <td> @{{ f.charges_time | stringToDate:'short' }}</td>

                                <td>1 X @{{  f.Night_charges }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="amount-right" style="width:20px; ">@{{ f.Night_charges |number:2  }}</td>




                            </tr>

                            <tr ng-repeat="f in nightLocalTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"  rowspan="@{{nightLocalTruck.length}}">(b). Local Truck</td>
                                <td>@{{ f.truck_no }}</td>

                                <td> @{{ f.charges_time | stringToDate:'short' }}</td>


                                <td>1 X @{{  f.Night_charges }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="amount-right" style="width:20px; " >@{{  f.Night_charges |number:2  }}</td>

                            </tr>

                            </tbody>
                        </table>



                    </td>



                </tr>
                {{--Haltage Charge--}}

                <tr>
                    <td></td>
                    <th>(v). Haltage Charge: </th>
                    <td>Day</td>
                    <td>Period</td>
                    <th>(v). Haltage charge: </th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td colspan="8">

                        <table class="table table-bordered">



                            {{-- <tr ng-repeat-start="(key, val) in haltagesForeignTruck">
                                 <td rowspan="@{{val.length}}">@{{key==0?'Foreign Truck':'Local Truck'}}</td>
                                 <td>@{{val[0].truck_no}}</td>
                                 <td>@{{val[0].haltage_days}}</td>
                                 <td>(@{{ val[0].truckentry_datetime |stringToDate}} <br> <b>To</b>  <br>@{{ val[0].receive_datetime|stringToDate }})</td>


                             </tr>
                             <tr ng-repeat-end ng-repeat="value in val.slice(1)">
                                 <td>@{{value.truck_no}}</td>
                                 <td>@{{value.haltage_days}}</td>
                                 <td>(@{{ value.delivery_req_dt |stringToDate }}<br> <b>To</b>  <br>@{{ value.delivery_dt|stringToDate }})</td>

                             </tr>
 --}}
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
                                <td>Name</td>
                                <td>Truck No. & Type</td>
                                <td> Days</td>
                                <td>Period</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                            <tr ng-repeat="f in haltagesForeignTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"  rowspan="@{{haltagesForeignTruck.length}}"><b></b>(a). Foreign Truck</td>
                                <td>@{{ f.truck_no }}</td>
                                <td>@{{ f.haltage_days }}</td>
                                <td><b>(</b>@{{ f.truckentry_datetime |stringToDate }} <br> <b>To</b>  <br>@{{ f.receive_datetime|stringToDate }} <b>)</b></td>


                                <td>@{{ f.haltage_days}} X @{{  f.rate_of_charges }}</td>
                                <td></td>
                                <td></td>
                                <td ></td>
                                <td  class="amount-right" style="width:20px; ">@{{ (f.haltage_days *  f.rate_of_charges) |number:2 }}</td>




                            </tr>

                            <tr ng-repeat="f in haltagesLocalTruck">
                                <td style="vertical-align: middle" ng-if="$index==0"  rowspan="@{{haltagesLocalTruck.length}}">(b). Local Truck</td>
                                <td>@{{ f.truck_no }}</td>
                                <td>@{{ f.haltage_days }}</td>
                                <td> <b>(</b> @{{ f.entry_dt |stringToDate }} <br> <b>To</b>  <br>@{{ f.delivery_dt|stringToDate }} <b>)</b></td>


                                <td>@{{ f.haltage_days}} X @{{  f.rate_of_charges }}</td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="amount-right" style="width:20px; ">@{{ f.haltage_days *  f.rate_of_charges |number:2  }}</td>

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
                    <th style="">(vi). Documentation Charge: </th>
                    <td></td>
                    <td></td>
                    <th  style="width: 690px !important;">(vi). Documentation charge: </th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                {{--Weighment Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="3">(vii). Weighment Charge: </th>
                    <td></td>
                    <td></td>
                    <td> <b>Truck</b>
                    </td>
                    <td>
                        <b>Total Truck</b>
                    </td>
                    <td> <b>Twice</b>  </td>
                    <td> <b>Charge</b></td>
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

                        @{{ totalForeignTruck }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X
                    </td>
                    <td><span ng-show="totalForeignTruck">2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span></td>
                    <td> @{{ weightment_measurement_charges }}</td>
                    <td class="amount-right">
                        <span ng-show="weightmentChargesForeign">=@{{ weightmentChargesForeign |number:2 }}</span>
                        {{--&nbsp;&nbsp;&nbsp;--}}
                    </td>
                </tr>

                <tr>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td>
                        (ii) Local
                    </td>
                    <td>

                        @{{ totalLocalTruck }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X
                    </td>
                    <td ><span ng-show="weightmentChargesLocal">2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; X </span></td>
                    <td>@{{ weightment_measurement_charges }}</td>
                    <td class="amount-right" >
                        <span ng-show="weightmentChargesLocal" >= @{{ weightmentChargesLocal|number:2 }}</span>
                        {{--&nbsp;&nbsp;&nbsp; &nbsp;--}}
                    </td>
                </tr>

                {{--outstanding Charge--}}

                <tr>
                    <td></td>
                    <th>(viii). Outstanding Bill(if any): </th>
                    <td></td>
                    <td></td>
                    <td>(viii). Outstanding Bill(if any): </td>
                    <td></td>
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
                    <th style="width:500px">4. Equipment Hire Charge:</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(a) Mobile Crane</td>
                    <td></td>
                    <td>(a) Mobile Crane</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>(b) Forklift</td>
                    <td></td>
                    <td>(b) Forklift</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>(c) Tarpaulin</td>
                    <td></td>
                    <td>(c) Tarpaulin</td>
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
                    <th rowspan="3"  style="width: 70%; vertical-align: middle"> VAT:</th>

                    <td class="amount-right">
                        <b>_______________________________________ <br>
                            Sub Total Taka:</b> @{{ TotalAmount|number:2 }}
                    </td>


                </tr>

                <tr>
                    <td><b>5.</b></td>

                    <td class="amount-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @{{ TotalAmount *15/100 |number:2}}
                    </td>

                </tr>
                <tr>
                    <td></td>

                    <td class="amount-right">
                        <b> _______________________________________ <br>
                            Grand Total Taka:</b> @{{ TotalAmount + (TotalAmount *15/100) |ceil|number:2 }}
                    </td>


                </tr>

                <tr>
                    <td></td>
                    <td colspan="2" > <b>In Words (Taka)</b>

                        <span id="totalInWord" class="text-capitalize" style="text-decoration: underline"></span>

                    </td>


                </tr>

            </table>

        </div>

    </div>






    <div class="col-md-12 ForPdf"  ng-show="ForPdf" >

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
<script type="text/javascript">
    $('#receive_datetime').datetimepicker({
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss'
    });
</script>