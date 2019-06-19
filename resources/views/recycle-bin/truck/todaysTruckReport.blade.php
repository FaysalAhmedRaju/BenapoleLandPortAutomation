<!DOCTYPE html>
<html>
<head>
    <title>Today's Truck Entry Report</title>
    <style>
        html {
            margin: 5px 5px 0;
        }

        table.dataTable {
            border-collapse: collapse;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }
    </style>
</head>

<body>

<table width="100%;" border="0">
    <tr>
        <td style="width: 25%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            {{date('d-m-Y',strtotime($date))}} 's Truck Entry Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($date))}}
        </td>

    </tr>
</table>

<br>

<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th style="">S/L</th>
        <th style="">Manifest No.</th>
        <th style="">Description of Goods</th>
        <th style="">Truck No.</th>
        <th style="">Driver Card</th>
        <th style="width: 170px;font-size: 13px">Entry Date</th>
        <th style="width: 90px;font-size: 13px">Entry By</th>
    </tr>
    </thead>
    <tbody>

    @if(isset($manifestdata)  && count($manifestdata) > 0)
        @php($manifestNo=0)@php($countLess=0)@php($sl=1)
        @foreach($manifestdata as $key => $t)

            @if($countLess==0)
                @php($countLess=$t->total_truck_entered)
            @endif


            {{--for reducing cargo name show redundency--}}
           {{-- @php

                if(isset($manifestWiseGoodsName[$t->justManifest])){
                     $manifestWiseGoodsName[$t->justManifest]++;
                } else{
                    $manifestWiseGoodsName[$t->justManifest]=1;
                   }

            @endphp--}}

            <tr>
                <td>{{ $sl ++ }}</td>
                {{--<td>{{ $t->total_truck_entered }}</td>--}}
                <th>{{ $t->manifes_no }} </th>
               {{-- @if($manifestWiseGoodsName[$t->justManifest]==1)
                    <td --}}{{--rowspan="{{$t->total_truck_entered}}"--}}{{-->{{ $t->cargo_name }}</td>
                @else
                    <td></td>
                @endif--}}
                <td> {{$t->cargo_name }}</td>
                <td>{{ $t->truck_type }}-{{ $t->truck_no }}</td>
                <td>{{ $t->driver_card }}</td>
                <td>{{date('d-m-Y h:i:s A',strtotime($t->truckentry_datetime))}}</td>
                <td style="font-size: 14px;text-transform: capitalize">{{ $t->entryBy }}</td>
            </tr>
            @php($countLess--)
            @if($t->remaining_truck>0 && $countLess==0)
                @for ($i = 0; $i <$t->remaining_truck; $i++)
                    <tr>
                        <td>{{ $sl ++}}</td>
                        <th>{{$t->manifes_no}}</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                @endfor
            @endif
            @php($manifestNo=$t->manifes_no)
        @endforeach
    @endif

    </tbody>
</table>
<p style="text-align: right"><b>Total Trucks: {{ $key + 1}} </b></p>


</body>
</html>

