@extends('layouts.master')

@section('title', 'Assessment Calan (Invoice)')

@section('style')
    <style type="text/css">
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
    {!!Html :: script('js/customizedAngular/invoice.js')!!}
@endsection

@section('content')
	<div class="col-md-12 text-center" ng-app="InvoiceApp" ng-controller="InvoiceCtrl">
		<div class="col-md-5 col-md-offset-3">
            <form name="form" class="form-inline" novalidate ng-submit="manifestSearch(searchText)">
                <div class="form-group">
                    <label for="searchText"> </label>
                    <input type="text" ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A|a)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/' required="required"  ng-model="searchText" name="searchText" class="form-control input-sm" id="searchText" placeholder="Enter Manifest No.">
                    <br>
                    <span class="error" ng-show='form.searchText.$error.pattern'>
                            Input like: 256/12 Or 256/A
                    </span>
                    <span ng-if="MNotFound" class="error">Manifest Not Found! </span>
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
        				................
        			</td>
        			<td>
        				<b>A/C. Goods under BCP Entry/Manifest No.:</b>
        			</td>
        			<td>
        				................
        			</td>
        			<th>
        				Dated:
        			</th>
        			<td colspan="3">
        				................
        			</td>
        		</tr>
        		<tr>
        			<th>
        				Consigner:
        			</th>
        			<td>
        				...............
        			</td>
        			<th>
        				B/E or E/A No:
        			</th>
        			<td>
        				.............
        			</td>
        			<th>
        				Dated:
        			</th>
        			<td>
        				.............
        			</td>
                    <th>
                        Challan No:
                    </th>
                    <td>
                        .............
                    </td>
        		</tr>
        		<tr>
        			<th>
        				Address:
        			</th>
        			<td>
        				.............
        			</td>
        			<th>
        				A/C. Shed/Yard No:
        			</th>
        			<td colspan="3">
        				.............
        			</td>
                    <th>
                        Date:
                    </th>
                    <td>
                        .............
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
                    <th rowspan="2">
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
                    <td rowspan="17" style="padding-top: 780px; padding-left: 50px;">
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
                        .....
                    </td>
                    <td rowspan="3">
                        .....
                    </td>
                    <td rowspan="3">
                        .....
                    </td>
                    {{-----------------PERIOD TO AMOUNT--------------------}}
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                    <td>
                        .....
                    </td>
                </tr>
                <tr>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                </tr>
                <tr>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                </tr>
                {{-----------------PERIOD TO AMOUNT--------------------}}
                <tr>
                    <td>
                        <b>2.</b>
                    </td>
                    <td>
                        <b>Off Loading</b><br>
                        a) By Manual Labour<br>
                        b) By BLPA Equipment
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>
                        <b>3.</b>
                    </td>
                    <td>
                        <b>Loading</b><br>
                        a) By Manual Labour<br>
                        b) By BLPA Equipment
                    </td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
                    <td>......</td>
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
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                </tr>
                <tr>
                    <td>
                        <b>5.</b>
                    </td>
                    <td>
                        <b>Other</b><br>
                        a) FT
                    </td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        b) L/T
                    </td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                    <td>.....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        c) C/C
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        d) Holiday Charge
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        e) Night Charge
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        f) Holtage Charge
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        g) Documentation Charge
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        h) Weighment Charge
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>
                        <b>6.</b>
                    </td>
                    <td>
                        Outstanding (if any)
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>
                        <b>7.</b>
                    </td>
                    <td>
                        Sub-Total
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>
                        <b>8.</b>
                    </td>
                    <td>
                        VAT
                    </td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                    <td>....</td>
                </tr>
            </table>
        </div>
	</div>
@endsection
