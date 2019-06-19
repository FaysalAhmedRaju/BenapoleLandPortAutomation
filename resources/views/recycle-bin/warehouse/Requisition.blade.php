@extends('layouts.master')
@section('title','Requisition')
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
	{!!Html :: script('js/customizedAngular/Requisition.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="RequisitionApp" ng-controller="RequisitionCtrl">
		<div class="col-md-12 text-center">
			<a href="{{url('getRequisitionPDF')}}" class="btn btn-success" target="_blank">PDF</a>
		</div>
		<div class="col-md-12 text-center">
			<p class="text-uppercase"><u>Requisition for Equipment to blpa machanical unit.............date.........time.........</u></p>
			<p class="text-uppercase">Please Arrange to load/offload/remove/shift the following</p>
		</div>
		<div class="col-md-12">
			<table class="table tab" style="box-shadow: 0px 0px 5px 1px darkgrey">
				<tr>
					<th>1</th>
					<th style="width: 240px;">2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th colspan="3">6</th>
					<th>7</th>
				</tr>
				<tr>
					<th rowspan="2">I.<br>o.</th>
					<th rowspan="2" style="text-align: left;">
						Nature of work to be performed,<br>
						Concerned Agent & Details of<br>
						Consignment
					</th>
					<th rowspan="2">Description of<br>Goods</th>
					<th rowspan="2">Weight in<br>Kgs.</th>
					<th rowspan="2">Place of<br>Operation</th>
					<th colspan="3">Period of Operation</th>
					<th rowspan="2">Remarks<br>(if any)</th>
				</tr>
				<tr>
					<th>From</th>
					<th>To</th>
					<th>Total Time</th>
				</tr>
				<tr>
					<td rowspan="2"></td> {{--I.O. row--}}
					<td rowspan="2">
						I. <u>Nature of work :</u><br>
						a) Load<br>
						b) Off Load<br>
						c) Removal<br>
						d) Shifting<br><br>
						<u>Note:</u> As Specified by Tick (&#10003;)<br>
						II. Name of C&F Agent with<br>
						Address:<br>
						M/s. ................<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.................<br>
						III. Manifest No. ...................<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date..................<br>
						IV. Customes Release<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Order No................<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date :..................<br>
					</td>
					<td rowspan="2"></td>
					<td rowspan="2"></td>
					<td rowspan="2" style="vertical-align: middle;">
						Shed/Yard<br>
						No..............
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td rowspan="2"></td>
					
				</tr>
				<tr>
					<td colspan="3" style="vertical-align: bottom; text-align: center;">
						Signature of Shed/Yard In-charge<br>
						&nbsp;&nbsp;&nbsp;(On completion of Work)
					</td>
				</tr>
			</table>
		</div>
	</div>
@endsection