<!DOCTYPE html>
<html>
<head>
    <title>Month Wise WareHouse Local Transport Delivery Report</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
            text-align: center;
        }
        table.posting_data {
            border-collapse: collapse;
        }
        table.posting_data, .posting_data th,.posting_data td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 250;
        }
    </style>
</head>
<body>
<img src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    {{$monthYear}}'s WareHouse Local Transport Delivery Report (Month Wise)
</p>
<h5 style="text-align: right;padding-right: 35px;">
    <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime(date("l")))}}
</h5>

<h5 style="padding-left: 8px">Shed/Yard:
    @foreach($shedYardName as $key => $name)
        <span>{{$name}} {{$key == (count($shedYardName)-1) ? '' : ', ' }}</span>
    @endforeach
</h5>
<table class="posting_data">

    <thead>
    <tr class="font-style">
        <th style="">1</th>
        <th style="">2</th>
        <th style="">3</th>
        <th style="" colspan="2">4</th>
        <th style="" colspan="6">5</th>
        <th style=""colspan="2">6</th>
        {{--<th>6</th>--}}
    </tr>
    <tr class="font-style">
        <th style=" font-size: 12px" rowspan="2"><nobr>S/L No.</nobr></th>
        <th rowspan="2" style="width: 120px; font-size: 12px"><nobr>Manifest</nobr><br>No. & Date </th>
        <th style="font-size: 12px" rowspan="2"><nobr>C&F Agents</nobr><br>Name & Address</th>
        <th style=" font-size: 12px" colspan="2"><nobr>Transport Particulars</nobr></th>

        <th style=" font-size: 12px"  colspan="6" style="text-transform: uppercase;"><nobr>PARTICULARS OF DELIVERY</nobr></th>
        <th colspan="2"><nobr>BALANCE IF ANY</nobr></th>
    </tr>
    <tr class="font-style">
        <th style="font-size: 12px" colspan="2"><nobr>Type-No</nobr></th>
        <th style="width: 80px; font-size: 12px"><nobr>Date of <br> Delivery<br><span style="font-size: 9px"> (Requisition)</span></nobr></th>
        <th style="width: 80px; font-size: 12px "><nobr>B/E No.<br>& Date</nobr></th>
        <th style="font-size: 12px"><nobr>Transport <br> Number</nobr></th>
        <th style="font-size: 12px"><nobr>Quantity</nobr><br>(No. of Pkgs)</th>
        <th style="font-size: 12px"><nobr>Weight</nobr></th>
        <th style="width: 80px;font-size: 12px" ><nobr>Exit Pass<br> No & Date</nobr></th>

        <th><nobr>Quantity</nobr></th>
        <th><nobr>Weight</nobr></th>
    </tr>
    </thead>
    <tbody> @php($i=0)  @php($totalTransportNumber=0) @php($totalQuantaty=0) @php($totalWeight=0) @php($totalRemainingQuantity=0) @php($totalRemainingWeight=0)
    @foreach($data as $key => $getData)
        <tr>
            <td> {{ ++$i }}</td>
            <td><u>{{ $getData->manifest  }}</u><br>{{ $getData->manifest_date }}</td>
            <td>{{ $getData->cnf_name }}<br>  @if($getData->address)
                    ,{{ $getData->address}}
                @endif
            </td>
            <td colspan="2">{{ $getData->truck_type_no }}</td>


            <td>{{ $getData->delivery_date }}</td>
            <td><u>{{ $getData->be_no  }}</u><br>{{ $getData->be_date }}</td>
            <td>{{  $getData->transport_number }}</td>
            @php($totalTransportNumber+= $getData->transport_number)
            <td>{{ $getData->labor_equ_package }}</td>
            @php($totalQuantaty+= $getData->labor_equ_package)
            <td>{{  $getData->labor_equ_weight  }}</td>
            @php($totalWeight+= $getData->labor_equ_weight)
            <td><u>{{ $getData->gate_pass_no  }}</u><br>{{ $getData->delivery_date }}</td>
            <td>{{ (number_format($getData->receive_packages, 2, '.', '')   -   number_format($getData->labor_equ_package, 2, '.', '')) == 0 ? null : number_format(number_format($getData->receive_packages, 2, '.', '')   -   number_format($getData->labor_equ_package, 2, '.', ''), 2,'.','') }}</td>
            @php($totalRemainingQuantity+= number_format(number_format($getData->receive_packages, 2, '.', '')   -   number_format($getData->labor_equ_package, 2, '.', ''), 2,'.',''))
            <td>{{  (number_format($getData->receive_weights, 2, '.', '')  -  number_format($getData->labor_equ_weight, 2, '.', '')) == 0 ? null : number_format(number_format($getData->receive_weights, 2, '.', '')  -  number_format($getData->labor_equ_weight, 2, '.', ''),2,'.','') }}</td>
            @php($totalRemainingWeight+= number_format(number_format($getData->receive_weights, 2, '.', '')  -  number_format($getData->labor_equ_weight, 2, '.', ''),2,'.',''))
        </tr>
    @endforeach
    <tr class="font-style">
        <th style="" {{--colspan="13"--}}><b>Total:</b>{{-- {{ $i }} &nbsp;&nbsp;--}}
        {{--Total Transport Number:  &nbsp;&nbsp;--}}
        {{--Total Quantity:  &nbsp;&nbsp;--}}
        {{--Total Weight: {{ $totalWeight != 0 ? number_format($totalWeight,2) : '' }}</th>--}}
        {{--<th style=""></th>--}}
        {{--<th style=""></th>--}}
        {{--<th style="" colspan="2"></th>--}}
        <th style="" colspan="6"></th>
        <th style="" colspan=""><b>{{ $totalTransportNumber }} </b></th>
        <th style="" colspan=""><b>
                {{ $totalQuantaty }}</b></th>
        <th style="" colspan=""><b>
                {{ $totalWeight != 0 ? number_format($totalWeight,2) : '' }}</b></th>
        <th style="" colspan=""></th>
        <th style=""colspan=""><b>{{$totalRemainingQuantity }}</b></th>
        <th style=""colspan=""><b>{{$totalRemainingWeight}}</b></th>
        {{--<th>6</th>--}}
    </tr>
    </tbody>

</table>

{{--<p><b>Total: {{ $i }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</b> </p>--}}
{{--<p>--}}{{--<b>Total: {{ $i }}--}}
{{--Gross Weight: {{ number_format($sumLabourUnload + $sumEquipmentUnload,2) }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}

{{--</b>--}}
    {{--</b> </p>--}}
</body>
</html>