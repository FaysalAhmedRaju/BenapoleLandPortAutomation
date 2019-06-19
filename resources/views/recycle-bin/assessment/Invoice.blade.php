@extends('layouts.master')

@section('title', 'Assessment Calan (Invoice)')

@section('script')
    {!!Html :: script('js/customizedAngular/invoice.js')!!}
@endsection

@section('content')
	<div class="col-md-12 text-center" ng-app="InvoiceApp" ng-controller="InvoiceCtrl">
		<div class="col-md-5 col-md-offset-3">
            <form name="form" class="form-inline" novalidate ng-submit="manifestSearch(searchText)">
                <div class="form-group">
                    <label for="searchText"> </label>
                    <input type="text" ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/' required="required"  ng-model="searchText" name="searchText" class="form-control input-sm" id="searchText" placeholder="Enter Manifest No.">
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
        	<table border="0" class="table">
        		<tr>
        			<td>
        				<b>Name of Consigne:</b>
        			</td>
        			<td>
        				................
        			</td>
        			<td>
        				<b>A/C. Goods under BCI:</b>
        			</td>
        			<td>
        				................
        			</td>
        			<td>
        				<b>Other:</b>
        			</td>
        			<td>
        				................
        			</td>
        		</tr>
        		<tr>
        			<td>
        				<b>Consignar:</b>
        			</td>
        			<td>
        				...............
        			</td>
        			<td>
        				<b>B/E or E/A No:</b>
        			</td>
        			<td>
        				.............
        			</td>
        			<td>
        				<b>Other:</b>
        			</td>
        			<td>
        				.............
        			</td>
        		</tr>
        		<tr>
        			<td>
        				<b>Address:</b>
        			</td>
        			<td>
        				.............
        			</td>
        			<td>
        				<b>A/C. Shed/Yard No</b>
        			</td>
        			<td>
        				.............
        			</td>
        		</tr>
        	</table>
        </div>
	</div>
@endsection
