<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>Requisition</title>
	<style>
		.tab {
			border-collapse: collapse;
			width: 100%;
		}
        .tab tr, .tab th, .tab td{
            border: 1px solid black;
            padding: 1px;
        }
        .tab th {
            text-align: center;
        }
        .tab tr td {
            text-align: left;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 220;
        }
        .head {
            text-align: center;
        }
        .info {
            position: absolute;
            top: 0;
            left:670px;
        }
    </style>
</head>
<body>
	<img src="../public/img/blpa.jpg">
	<span class="center">
		<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
		<span style="font-size: 19px;">Benapole Land Port, Jessore </span>
	</span>
    <p class="head">
        <span style="text-transform: uppercase;"><u>Requisition for Equipment to blpa machanical unit...............date.............time..............</u></span><br>
        <span style="text-transform: uppercase;">Please Arrange to load/offload/remove/shift the following:</span>
    </p>
    <span class="info">
        <span>(In Duplicate)</span><br>
        <span>1. Orginal</span><br>
        <span>2. Duplicate</span>
    </span>
 	<table class="tab">
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
                <u>Note:</u> As Specified by Tick (<span style="font-family: DejaVu Sans, sans-serif;">✓</span>)<br>
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
                No.........
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
    <p>
        <br><br>
    	<span style="padding-left: 580px;">Signature of Operator</span><br>
    	<span style="padding-left: 100px;">Signature of</span>
    	<span style="padding-left: 180px;">Signature of</span>
    	<span style="padding-left: 180px;">Nature of</span><br>
    	<span style="padding-left: 70px;">Shed/Yard-In-Charge</span>
    	<span style="padding-left: 155px;">In-Charge</span>
    	<span style="padding-left: 140px;">Equipment..................</span>
        <span style="padding-left: 180px;">Confirmed by</span><br>
        <span style="padding-left: 110px;">with seal</span>
        <span style="padding-left: 160px;">BLPA Unit, Benapole</span>
        <span style="padding-left: 100px;">No..................</span>
        <span style="padding-left: 212px;">Authorised Officer</span>
    </p>
</body>
</html>