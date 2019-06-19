<!DOCTYPE html>
<html>
<head>
    <title>Manifest Report</title>
    <link rel="shortcut icon" href="{{asset('/images/favicon.ico')}} " type="image/x-icon">
    <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">

    <style>

        body {
            margin: 20px 50px 150px;
            padding: 20px;
            box-shadow: 1px 20px 50px 15px grey;

            /*animation: mymove 7s infinite;*/
        }

        /* Chrome, Safari, Opera */
        @-webkit-keyframes mymove {
            20% {
                box-shadow: 1px 20px 50px 15px grey;
            }
        }

        @keyframes mymove {
            20% {
                box-shadow: 1px 20px 50px 15px grey;
            }
        }

        table.manifestTable {
            border: none !important;

        }

        table.manifestTable tr td {
            border: 0;

        }

        table.manifestTable tr td:nth-child(odd) {
            font-weight: bold;
            text-align: left !important;
        }

        table.manifestTable tr td:nth-child(even) {
            text-align: left !important;
        }

        table.dataTable {
            border-collapse: collapse;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            /*border: 1px solid black;*/
            padding: 5px;
            text-align: center;
            border: 0;
        }

        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }

        table {
            border-collapse: collapse;
            width: 100%;

        }

        table, th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
        }

        table.bd-truck-table tfoot tr td {
            text-align: left;
            font-weight: bold;
        }

        table.foreign-truck-table tfoot tr td {
            text-align: left;
            font-weight: bold;
        }
    </style>
</head>
<body>


<table width="100%;" class="dataTable">
    <tr>
        <td style="width: 15%">
            <img src="{{asset('img/blpa.jpg')}}" height="100">
        </td>
        <td style="width: 60%; text-align:center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            Manifest Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>
    </tr>
</table>
<br>


<table border="0" class="manifestTable">
    <caption style="padding-bottom: 10px;">
        <b><u>Manifest Details Of: {{$manifestNo}}</u></b>
    </caption>

    <tr>
        <td>
            Manifest No:
        </td>

        <td> {{$manifest}}</td>

        <td>
            Goods Name:
        </td>

        <td>
            {{$cargo_name}}
        </td>

        <td>
            Manifest Date:
        </td>

        <td>
            {{$manifest_date}}
        </td>

    </tr>

    <tr>

        <td>Gross Weight:</td>

        <td>{{$manifestGrossWeight}}</td>

        <td>Net Weight:</td>

        <td>{{$manifestNetWeight}}</td>

        <td>Package No:</td>

        <td>{{$package_no}}</td>

    </tr>
    <tr>

        <td>
            Importer Name:
        </td>

        <td>
            {{$importer_name_addr}}
        </td>

        <td>
            Exporter Name:
        </td>

        <td>
            {{$exporter_name_addr}}
        </td>


        <td>
            C&F Name:
        </td>

        <td>
            {{$cnf_name}}
        </td>
    </tr>


    <tr>
        <td>
            Bill Entry No:
        </td>

        <td>
            {{$be_no}}
        </td>


        <td>
            Realsed Oreder No:
        </td>

        <td>
            {{$custom_release_order_no}}
        </td>


        <td>
            Gate Pass NO:
        </td>

        <td>
            {{$gate_pass_no}}
        </td>
    </tr>

    <tr>
        <td>
            Yard/Shed NO:
        </td>


        <td>
            {{$posted_yard_shed}}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

</table>


<br><br>

<table class="foreign-truck-table">
    <caption style="padding-bottom: 10px;">
        <b>
            <u>
                Foreign Truck Details
            </u>
        </b>
    </caption>
    <thead>

    <tr>
        <th rowspan="2">S/L</th>
        <th rowspan="2">Truck No.</th>
        <th rowspan="2">Driver Name</th>
        <th colspan="3">Weighbridge Weight</th>

        <th rowspan="2">Receive Package</th>
        <th colspan="3">Date</th>


        <th>Labor</th>
        <th colspan="2">Equipment </th>

    </tr>
    <tr>
        <th>Gross</th>
        <th>Net</th>
        <th>Tare</th>

        <th>Entry</th>
        <th>Receive</th>
        <th>Exit</th>
        <th>Unload</th>
        <th>Name</th>
        <th>Load</th>

    </tr>
    </thead>
    <tbody>
            @php
                $i=0;
                $indTotalLabourUnload=0  ;
                $indTotalEquipmentUnload=0;
                $totalForeignTruckGrossWeight=0;
            @endphp

    @foreach($indianTruckData as $key => $indianTruck)
        <tr>
            <td> {{ ++$i }}</td>

            <td>{{ $indianTruck->truck_type.'-'.$indianTruck->truck_no }}</td>
            <td>{{ $indianTruck->driver_name }}</td>
            <td>
                {{$indianTruck->gweight_wbridge != null ? number_format($indianTruck->gweight_wbridge,2) : '' }}
                @php($totalForeignTruckGrossWeight+= $indianTruck->gweight_wbridge)
            </td>
            {{--<td>{{$indianTruck->tweight_wbridge}}</td>--}}
            <td>{{ (is_numeric($indianTruck->tweight_wbridge) &&  $indianTruck->tweight_wbridge != 0 )? number_format($indianTruck->tweight_wbridge,2) : '' }}</td>
            <td>{{$indianTruck->tr_weight != null ? number_format($indianTruck->tr_weight,2) : '' }}</td>

            <td>{{ $indianTruck->receive_package }}</td>
            <td>{{date('d-m-Y h:i:s A',strtotime($indianTruck->truckentry_datetime))}}</td>
            <td>{{date('d-m-Y',strtotime($indianTruck->unload_receive_datetime))}}</td>
            <td>
                @if($indianTruck->out_date)
                    {{date('d-m-Y',strtotime($indianTruck->out_date))}}
                @else
                    Not Exited
                @endif
            </td>
            <td>{{ $indianTruck->unload_labor_weight != 0 ? $indianTruck->unload_labor_weight : '' }}</td>@php($indTotalLabourUnload+= $indianTruck->unload_labor_weight)
            <td>{{ $indianTruck->unload_equip_name }}</td>
            <td>{{ $indianTruck->unload_equip_weight != 0 ? $indianTruck->unload_equip_weight : '' }}</td>@php($indTotalEquipmentUnload+= $indianTruck->unload_equip_weight)
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td colspan="2">
            Total Truck: {{ $i }}
        </td>

        <td colspan="4">
            Gross Weight:{{ $totalForeignTruckGrossWeight}}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2">
            Total Labour: {{ $indTotalLabourUnload }}
        </td>
        <td colspan="2">
            Total Equipment: {{ $indTotalEquipmentUnload }}
        </td>

    </tr>
    </tfoot>


</table>

<br><br>

<table class="bd-truck-table">
    <caption style="padding-bottom: 10px;"><b><u>Local Truck Details</u></b></caption>
    <thead>

    <tr>
        <th>S/L</th>

        <th>Truck No.</th>
        <th>Driver Name</th>
        <th>Package</th>
        <th>Entry Date</th>
        <th>Labor Load</th>
        <th>Equipment Name</th>
        <th>Equipment Load</th>
    </tr>
    </thead>
    <tbody> @php($i=0)  @php($bdTotalLabourUnload=0) @php($bdTotalEquipmentUnload=0)
    @foreach($bdTruckData as $key => $bdTruck)
        <tr>
            <td> {{ ++$i }}</td>

            <td>{{ $bdTruck->truck_no }}</td>
            <td>{{ $bdTruck->driver_name }}</td>
            <td>{{ $bdTruck->package }}</td>
            <td>{{$bdTruck->delivery_dt}}</td>
            <td>{{ $bdTruck->labor_load }}</td>@php($bdTotalLabourUnload+= $bdTruck->labor_load)
            <td>{{ $bdTruck->equip_name }}</td>
            <td>{{ $bdTruck->equip_load }}</td>@php($bdTotalEquipmentUnload+= $bdTruck->equip_load)
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td colspan="2">
            Total Truck: {{ $i }}
        </td>

        <td colspan="2">

        </td>
        <td colspan="2">
            Total Labour: {{ $bdTotalLabourUnload }}
        </td>
        <td colspan="2">
            Total Equipment: {{ $bdTotalEquipmentUnload }}
        </td>

    </tr>
    </tfoot>
</table>


</body>
</html>