@extends('layouts.master')
@section('title', 'Accounts Calan')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
                display: none !important;
            }
        .tab th, .tab tr, .tab td{
            border: 1px solid black;
        }
        .tab th {
            text-align: center;
        }
        .tab tr td {
            text-align: left;
        }
    </style>
@endsection

@section('script')
    {!!Html :: script('js/customizedAngular/invoiceAccounts.js')!!}
@endsection

@section('content')
	<div class="col-md-12 text-center ng-cloak" ng-app="InvoiceApp" ng-controller="InvoiceCtrl">
		<div class="col-md-5 col-md-offset-3">
            <form name="form" class="form-inline" novalidate ng-submit="manifestSearch(searchText)">
                <div class="form-group">
                    <label for="searchText"> </label>
                    <input type="text" ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A|a)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/' required="required"  ng-model="searchText" name="searchText" class="form-control input-sm" id="searchText" placeholder="Enter Manifest No." ng-keydown="keyBoard($event)" ng-model-options="{allowInvalid: true}">
                    <br>
                    <span class="error" ng-show='form.searchText.$error.pattern'>
                            Input like: 256/12/2017 Or 256/A/2017
                    </span>
                    <span ng-if="MNotFound" class="error">Manifest Not Found!</span>
                </div>
                <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px;">
                    <img src="img/dataLoader.gif" width="250" height="15"/>
                    <br/> Please wait!
                </span>
            </form>
            <br>
        </div>
        <div class="col-md-12" style="text-align: left;">
        	<table border="0" class="table" style="box-shadow: 0px 0px 5px 1px darkgrey">
        		<tr>
        			<th>
        				Name of Consignee:
        			</th>
        			<td>
        				@{{ consignee }}
        			</td>
        			<td>
        				<b>A/C. Goods under BCP Entry/Manifest No.:</b>
        			</td>
        			<td>
        				@{{ manifest }}
        			</td>
        			<th>
        				Dated:
        			</th>
        			<td colspan="3">
        				@{{ manifestDate }}
        			</td>
        		</tr>
        		<tr>
        			<th>
        				Consigner:
        			</th>
        			<td>
        				@{{ consigner }}
        			</td>
        			<th>
        				B/E or E/A No:
        			</th>
        			<td>
        				@{{ billOfEntryNo }}
        			</td>
        			<th>
        				Dated:
        			</th>
        			<td>
        				@{{ billOfEntryDate }}
        			</td>
                    <th>
                        Challan No:
                    </th>
                    <td>
                        @{{ ChallanNO }}
                    </td>
        		</tr>
        		<tr>
        			<th>
        				Address:
        			</th>
        			<td>
        				@{{ consignerAddress }}
        			</td>
        			<th>
        				A/C. Shed/Yard No:
        			</th>
        			<td colspan="3">
        				@{{ postedYardShed }}
        			</td>
                    <th>
                        Date:
                    </th>
                    <td>
                        @{{ today | date : "y-MM-dd" }}
                    </td>
        		</tr>
        	</table>
        </div>
        <div class="col-md-12">
            <table class="table tab" style="box-shadow: 0px 0px 5px 1px darkgrey">
                <tr>
                    <th rowspan="2">
                        Name &amp; Address of C&amp;F<br>
                        Agent/Party making payment
                    </th>
                    <th rowspan="2">
                        SL<br>
                        No.
                    </th>
                    <th rowspan="2">
                        Particulars of Charges
                    </th>
                    <th rowspan="2">
                        Description of<br>
                        Goods/Services
                    </th>
                    <th rowspan="2">
                        Quantity<br>
                        Pkgs
                    </th>
                    <th rowspan="2">
                        Weight
                    </th>
                    <th colspan="3">
                        PERIOD
                    </th>
                    <th rowspan="2" width="120px;">
                        Basis of<br>
                        Charges
                    </th>
                    <th colspan="2">
                        RATE
                    </th>
                    <th colspan="2">
                        AMOUNT
                    </th>
                </tr>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Total<br>Days</th>
                    <th>Shed</th>
                    <th>Yard</th>
                    <th>Taka</th>
                    <th>Ps.</th>
                </tr>
                <tr>
                    <td rowspan="17" style="vertical-align: middle;">
                        MODE OF<br>
                        PAYMENT/<br>
                        Cash/Pay-order/<br>
                        Draft/Cheque/<br>
                        Credit Note
                    </td>
                    <td rowspan="3">
                        <b>1.</b>
                    </td>
                    <td rowspan="3">
                        Warehouse Rent<br>
                        Shed/Yard
                    </td>
                    <td rowspan="3">
                        @{{ goodsName }}
                    </td>
                    <td rowspan="3">
                        @{{ pkg }}
                    </td>
                    <td rowspan="3">
                        @{{ weight }}
                    </td>
                    {{-----------------PERIOD TO AMOUNT--------------------}}
                    <td>

                    </td>
                    <td>

                    </td>
                    {{--First Slab--}}
                    <td>
                        @{{ FirstSlabDay }}
                    </td>
                    <td>
                        @{{ FirstSlabCharge }}
                        @{{ FirstSlabCharge != null ? " X " : "" }}
                        @{{ FirstSlabCharge != null ? ReceiveWeight : "" }}
                    </td>
                    <td>
                        @{{ FirstSlabDay != null && (postedYardShed>=9 && postedYardShed<=24) ?
                         postedYardShed : ""}}
                    </td>
                    <td>
                        @{{ FirstSlabDay != null && (postedYardShed>=24 && postedYardShed<=30) ?
                         postedYardShed : ""}}
                    </td>
                    <td class="amount-right">
                        @{{ firstSlabTk|number:2 }}
                    </td>
                    <td>
                        @{{ firstSlabPs }}
                    </td>
                    {{--First Slab--}}
                </tr>
                <tr>
                    {{--Second Slab--}}
                    <td></td>
                    <td></td>
                    <td>
                        @{{ SecondSlabDay }}
                    </td>
                    <td>
                        @{{ SecondSlabCharge }}
                        @{{ SecondSlabCharge != null ? " X " : "" }}
                        @{{ SecondSlabCharge != null ? ReceiveWeight : "" }}
                    </td>
                    <td>
                        @{{ SecondSlabDay != null && (postedYardShed>=9 && postedYardShed<=24) ?
                         postedYardShed : ""}}
                    </td>
                    <td>
                        @{{ SecondSlabDay != null && (postedYardShed>=24 && postedYardShed<=30) ?
                         postedYardShed : ""}}
                    </td>
                    <td class="amount-right">
                        @{{ secondSlabTk|number:2 }}
                    </td>
                    <td>
                        @{{ secondSlabPs }}
                    </td>
                    {{--Second Slab--}}
                </tr>
                <tr>
                    {{--Third Slab--}}
                    <td></td>
                    <td></td>
                    <td>
                        @{{ thirdSlabDay }}
                    </td>
                    <td>
                        @{{ ThirdSlabCharge }}
                        @{{ ThirdSlabCharge != null ? " X " : "" }}
                        @{{ ThirdSlabCharge != null ? ReceiveWeight : "" }}
                    </td>
                    <td>
                        @{{ thirdSlabDay != null && (postedYardShed>=9 && postedYardShed<=24) ?
                         postedYardShed : ""}}
                    </td>
                    <td>
                        @{{ thirdSlabDay != null && (postedYardShed>=24 && postedYardShed<=30) ?
                         postedYardShed : ""}}
                    </td>
                    <td class="amount-right">
                        @{{ thirdSlabTk|number:2 }}
                    </td>
                    <td>
                        @{{ thirdSlabPs }}
                    </td>
                    {{--Third Slab--}}
                </tr>
                {{-----------------PERIOD TO AMOUNT--------------------}}
                {{--Offloading--}}
                <tr>
                    <td>
                        <b>2.</b>
                    </td>
                    <td>
                        <b>Off Loading</b><br>
                        <span ng-if="totaloffLoadingLabour != null" style="font-family: DejaVu Sans, sans-serif;">✓</span>
                        a) By Manual Labour<br>
                        <span ng-if="totaloffLoadingEquipment != null" style="font-family: DejaVu Sans, sans-serif;">✓</span>
                        b) By BLPA Equipment
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totaloffLoadingLabour }}
                        @{{ totaloffLoadingLabour != null ? " X " : "" }}
                        @{{ offLoadingLabourCharge }}
                        <span ng-if="totaloffLoadingLabour != null && totaloffLoadingEquipment != null">
                            <br>
                        </span>
                        @{{ totaloffLoadingEquipment }}
                        @{{ totaloffLoadingEquipment != null ? " X " : "" }}
                        @{{ offLoadingEquipmentCharge }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ offLoadingLabourTk|number:2 }}
                        <span ng-if="offLoadingLabourTk != 0 && offLoadingEquipmentTk != 0">
                            <br>
                        </span>
                        @{{ offLoadingEquipmentTk|number:2 }}
                    </td>
                    <td>
                        @{{ offLoadingLabourPs }}
                        <span ng-if="offLoadingLabourPs != 0 && offLoadingEquipmentPs != 0">
                            <br>
                        </span>
                        @{{ offLoadingEquipmentPs }}
                    </td>
                {{--Offloading--}}
                </tr>
                {{--Loading--}}
                <tr>
                    <td>
                        <b>3.</b>
                    </td>
                    <td>
                        <b>Loading</b><br>
                        <span ng-if="totalLoadingLabour != null" style="font-family: DejaVu Sans, sans-serif;">✓</span>
                        a) By Manual Labour<br>
                        <span ng-if="totalLoadingEquipment != null" style="font-family: DejaVu Sans, sans-serif;">✓</span>
                        b) By BLPA Equipment
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalLoadingLabour }}
                        @{{ totalLoadingLabour != null ? " X " : "" }}
                        @{{ loadingLabourCharge }}
                        <span ng-if="totalLoadingLabour != null && totalLoadingEquipment != null">
                            <br>
                        </span>
                        @{{ totalLoadingEquipment }}
                        @{{ totalLoadingEquipment != null ? " X " : "" }}
                        @{{ loadingEquipmentCharge }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ loadingLabourTk|number:2 }}
                        <span ng-if="loadingLabourTk != 0 && loadingEquipmentTk != 0">
                            <br>
                        </span>
                        @{{ loadingEquipmentTk|number:2 }}
                    </td>
                    <td>
                        @{{ loadingLabourPs }}
                        <span ng-if="loadingLabourPs != 0 && loadingEquipmentPs != 0">
                            <br>
                        </span>
                        @{{ loadingEquipmentPs }}
                    </td>
                    {{--Loading--}}
                </tr>
                <tr>
                    <td>
                        <b>4.</b>
                    </td>
                    <td>
                        <b>Re-stacking / Removal /<br>
                        Transhipment</b><br>
                        a) By Manual Labour<br>
                        b) By BLPA Equipment
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                {{--Foreign Truck--}}
                    <td>
                        <b>5.</b>
                    </td>
                    <td>
                        <b>Other</b><br>
                        a) FT
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalFT }}
                        @{{ totalFT != null ? " X " : "" }}
                        @{{ FTcharge }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ FTTaka|number:2 }}
                    </td>
                    <td>
                        @{{ FTPs }}
                    </td>
                    {{--Foreign Truck--}}
                </tr>
                <tr>
                    {{--Local Truck--}}
                    <td></td>
                    <td>
                        b) L/T
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalLT }}
                        @{{ totalLT != null ? " X " : "" }}
                        @{{ LTcharge }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ LTTaka|number:2 }}
                    </td>
                    <td>
                        @{{ LTPs }}
                    </td>
                    {{--Local Truck--}}
                </tr>
                <tr>
                    {{--Carpenter--}}
                    <td></td>
                    <td>
                        c) C/C
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalCCOpeningOrClosing }}
                        @{{ totalCCOpeningOrClosing != null ? " X " : "" }}
                        @{{ CCOpenningOrCLosingCharge }}
                        <span ng-if="totalCCOpeningOrClosing != null && totalCCRepair != null">
                            <br>
                        </span>
                        @{{ totalCCRepair }}
                        @{{ totalCCRepair != null ? " X " : "" }}
                        @{{ CCRepairCharge }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ CCOpenningOrClosingTk|number:2 }}
                        <span ng-if="CCOpenningOrClosingTk != 0 && CCRepairTk != 0">
                            <br>
                        </span>
                        @{{ CCRepairTk|number:2 }}
                    </td>
                    <td>
                        @{{ CCOpenningOrClosingPs }}
                        <span ng-if="CCOpenningOrClosingPs != 0 && CCRepairPs != 0">
                            <br>
                        </span>
                        @{{ CCRepairPs }}
                    </td>
                </tr>
                <tr>
                    {{--Holiday Charge--}}
                    <td></td>
                    <td>
                        d) Holiday Charge
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalHolidayFT }}
                        @{{ totalHolidayFT != null ? " X " : "" }}
                        @{{ holidayChargeFT }}
                        <span ng-if="totalHolidayFT != null && totalHolidayLT != null">
                            <br>
                        </span>
                        @{{ totalHolidayLT }}
                        @{{ totalHolidayLT != null ? " X " : "" }}
                        @{{ holidayChargeLT }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ holidayFTTk|number:2 }}
                        <span ng-if="holidayFTTk != 0 && holidayLTTk != 0">
                            <br>
                        </span>
                        @{{ holidayLTTk|number:2 }}
                    </td>
                    <td>
                         @{{ holidayFTPs }}
                        <span ng-if="holidayFTPs != 0 && holidayLTPs != 0">
                            <br>
                        </span>
                        @{{ holidayLTPs }}
                    </td>
                    {{--Holiday Charge--}}
                </tr>
                <tr>
                    {{--Night Charge--}}
                    <td></td>
                    <td>
                        e) Night Charge
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalNightFT }}
                        @{{ totalNightFT != null ? " X " : "" }}
                        @{{ nightChargeFT }}
                        <span ng-if="totalNightFT != null && totalNightLT != null">
                            <br>
                        </span>
                        @{{ totalNightLT }}
                        @{{ totalNightLT != null ? " X " : "" }}
                        @{{ nightChargeLT }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ nightFTTk|number:2 }}
                        <span ng-if="nightFTTk != 0 && nightLTTk != 0">
                            <br>
                        </span>
                        @{{ nightLTTk|number:2 }}
                    </td>
                    <td>
                        @{{ nightFTPs }}
                        <span ng-if="nightFTPs != 0 && nightLTPs != 0">
                            <br>
                        </span>
                        @{{ nightLTPs }}
                    </td>
                    {{--Night Charge--}}
                </tr>
                <tr>
                    {{--Holtage Charge--}}
                    <td></td>
                    <td>
                        f) Holtage Charge
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalHoltageFT }}
                        @{{ totalHoltageFT != null ? " -> " : "" }}
                        @{{ holtageOtherFT }}
                        @{{ totalHoltageFT != null ? " X " : "" }}
                        @{{ holtageChargeFT }}
                        <span ng-if="totalHoltageFT != null && totalHoltageLT != null">
                            <br>
                        </span>
                        @{{ totalHoltageLT }}
                        @{{ totalHoltageLT != null ? " -> " : "" }}
                        @{{ holtageOtherLT }}
                        @{{ totalHoltageLT != null ? " X " : "" }}
                        @{{ holtageChargeLT }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ holtageFTTk|number:2 }}
                        <span ng-if="holtageFTTk != 0 && holtageLTTk != 0">
                            <br>
                        </span>
                        @{{ holtageLTTk|number:2 }}
                    </td>
                    <td>
                        @{{ holtageFTPs }}
                        <span ng-if="holtageFTPs != 0 && holtageLTPs != 0">
                            <br>
                        </span>
                        @{{ holtageLTPs }}
                    </td>
                    {{--Holtage Charge--}}
                </tr>
                <tr>
                    <td></td>
                    <td>
                        g) Documentation Charge
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    {{--WEIGHTMENT CHARGE--}}
                    <td></td>
                    <td>
                        h) Weighment Charge
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @{{ totalWeightmentFT }}
                        @{{ totalWeightmentFT != null ? " X " : "" }}
                        @{{ weightmentChargeFT }}
                        <span ng-if="totalWeightmentFT != null && totalWeightmentLT != null">
                            <br>
                        </span>
                        @{{ totalWeightmentLT }}
                        @{{ totalWeightmentLT != null ? " X " : "" }}
                        @{{ weightmentChargeLT }}
                    </td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{ weighmentFTTk|number:2 }}
                        <span ng-if="weighmentFTTk != 0 && weighmentLTTk != 0">
                            <br>
                        </span>
                        @{{ weighmentLTTk|number:2 }}
                    </td>
                    <td>
                        @{{ weighmentFTPs }}
                        <span ng-if="weighmentFTPs != 0 && weighmentLTPs != 0">
                            <br>
                        </span>
                        @{{ weighmentLTPs }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>6.</b>
                    </td>
                    <td>
                        Outstanding (if any)
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <b>7.</b>
                    </td>
                    <td>
                        Sub-Total
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{totalAmmountFromDBTk|number:2}}
                    </td>
                    <td>
                        @{{totalAmmountFromDBPs}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>8.</b>
                    </td>
                    <td>
                        VAT
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-right">
                        @{{vatTk|number:2}}
                    </td>
                    <td>
                        @{{vatPs}}
                    </td>
                </tr>
            </table>
            <p style="text-align: right;">Received Taka: @{{totalAmmountWithVat|number:2}} </p>
            <p style="text-align: right;">Taka(In Words: @{{words}}) </p>
        </div>
        {{-- <div class="col-md-12 text-center">
         <span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                                        <img src="img/dataLoader.gif" width="250" height="15"/>
                 <button  class="btn btn-primary" type="button" ng-disabled="!manif_id" ng-click="saveChallan()">Save Challan</button>
        </div>
        <div class="col-md-12 text-center">
                                      <br/> Saving...!
             </span>
            <div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Challan Successfully Done!
            </div>
        </div> --}}
	</div>
@endsection
