<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<title>Weight Report</title>
	<style>
		.info {
            border-collapse: collapse;
        	width: 100%;
        }
        .info tr th, .info tr td {
            /*border: 1px solid black;*/
            padding: 2px;
            white-space: nowrap;
			font-size: 12px;
        }
        .weight {
        	/*border-collapse: collapse;*/
        	width: 100%;
        	text-align: center;
			font-size: 12px;
        }
        .weight tr th, .weight tr td{
        	/*border: 1px solid black;*/
        	padding: 8px;
        	white-space: nowrap;

        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 150;
        }

		th,td,p,div{
			margin:0;padding:0
		}
		html{
			margin:1px 10px 0;
		}

		/**{margin:0;padding:5px}*/
	</style>
</head>
<body>


<table width="100%">
	<tr>
		<td style="width:20%">
			<img src="../public/img/blpa.jpg" width="80">
		</td>
		<td style="width:70%; text-align: center">
			<p>
				<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
				<span style="font-size: 19px;">Benapole Land Port, Jessore </span><br>
				Weight Report
			</p>
		</td>
		<td style="width:15%">
			Scale No. 05
		</td>
	</tr>
</table>

		<hr>


	 	<table class="info" align="center">
	 		<tr>
	 			<th>Date</th>
	 			<th style="width: 1px;">:</th>
	 			<td>{{ $todayWithTime }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th style="width: 1px;">&nbsp;</th>
	 			<td>&nbsp;</td>
	 		</tr>
	 		<tr>
	 			<th>Weight id</th>
	 			<th>:</th>
	 			<td>{{ rand(1000,100000) }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Challan No.</th>
	 			<th>:</th>
	 			<td>{{ $weightData[0]->manifest }}</td>
	 		</tr>
	 		<tr>
	 			<th>Weight Type</th>
	 			<th>:</th>
	 			<td>IN WEIGHT</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Clist Code</th>
	 			<th>:</th>
	 			<td></td>
	 		</tr>
	 		<tr>
	 			<th>Matrial Description</th>
	 			<th>:</th>
	 			<td style="white-space: normal;">{{ $weightData[0]->goods }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Clist Type</th>
	 			<th>:</th>
	 			<td></td>
	 		</tr>
	 		<tr>
	 			<th>Quantity</th>
	 			<th>:</th>
	 			<td>{{ $weightData[0]->receive_package }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Name</th>
	 			<th>:</th>
	 			<td></td>
	 		</tr>
	 		<tr>
	 			<th>Operator Name</th>
	 			<th>:</th>
	 			<td>{{ $user }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Company</th>
	 			<th>:</th>
	 			<td></td>
	 		</tr>
	 		<tr>
	 			<th>Driver Name</th>
	 			<th>:</th>
	 			<td>{{ $weightData[0]->driver_name }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>Tel_Fax_Email</th>
	 			<th>:</th>
	 			<td></td>
	 		</tr>
	 		<tr>
	 			<th>Truck No</th>
	 			<th>:</th>
	 			<td>{{ $weightData[0]->truck_type."-".$weightData[0]->truck_no }}</td>
	 			<td>&nbsp;</td>
	 			<td>&nbsp;</td>
	 			<th>&nbsp;</th>
	 			<th>&nbsp;</th>
	 			<td>&nbsp;</td>
	 		</tr>
	 	</table>
	 	<hr>

	 	<table class="weight" align="center">
	 		<tr>
	 			<th>Date:</th>
	 			<th>{{ $weightData[0]->wbrdge_time1 }}</th>
	 			<th>Date:</th>
	 			<th>{{ $weightData[0]->wbrdge_time2 }}</th>
	 			<th>Date:</th>
	 			<th>{{ $weightData[0]->wbrdge_time2 }}</th>
	 		</tr>
	 		<tr>
	 			<th colspan="2">1st Weight</th>
	 			<th colspan="2">2nd Weight</th>
	 			<th colspan="2">Net Weight</th>
	 		</tr>
	 		<tr>
	 			<th colspan="2">{{  $weightData[0]->gweight_wbridge != null ? number_format($weightData[0]->gweight_wbridge,2,'.',',') : "" }}</th>

	 			<th colspan="2">{{ $weightData[0]->tr_weight != null ? number_format($weightData[0]->tr_weight,2,'.',',') : "" }}</th>

	 			<th colspan="2">{{ $weightData[0]->tweight_wbridge != null ? number_format($weightData[0]->tweight_wbridge,2,'.',',') : "" }}</th>
	 		</tr>
	 	</table>
	 	<hr>
	 	<p style="padding-top: 1px;">
	 		<span>_____________________</span>
	 		<span style="padding-left: 110px;">_____________________</span>
	 		<span style="padding-left: 110px;">________________</span><br>
	 		<span style="padding-left: 14px;">Customer's Signature</span>
	 		<span style="padding-left: 155px;">Custom's Signature</span>
	 		<span style="padding-left: 130px;">Operator Signature</span>
	 	</p>
</body>
</html>