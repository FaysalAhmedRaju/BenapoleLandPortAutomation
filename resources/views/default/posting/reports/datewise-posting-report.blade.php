<!DOCTYPE html>
<html>
<head>
    <title>Manifest Report</title>
    <style>

        html {
            margin: 0px 10px 0;
        }
        body{
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            opacity: .2;
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
            left: 350px;
        }
    </style>
</head>
<body>


<table style="width: 100%; border: 0">
    <tr>


        <td>
            <img src="../public/img/blpa.jpg">
        </td>

        <td style="text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            {{$from_date}} Manifest Posting Report
        </td>

        <td style="text-align:right; vertical-align: bottom">
            <span> Time: {{date('d-m-Y h:i:sa',strtotime($todayWithTime))}}</span>
        </td>
    </tr>
</table>

<br>

<table class="posting_data">
    <thead>
    <tr>

        <th  style=" font-size: 12px;">S/L</th>
        <th  style=" font-size: 12px;">Marks & NO</th>
        <th  style="font-size: 12px;">Manifest NO</th>
        <th  style=" font-size: 12px;">Description Goods</th>
        <th  style="font-size: 12px;">Quantity</th>
        <th  style="  font-size: 12px;">NO. of Packages</th>

        <th  style=" font-size: 12px;">C&F Value</th>
        <th  style="  font-size: 12px;">Name & Address Expoter</th>
        <th  style=" font-size: 12px;">Name & Address Importer</th>
        <th  style=" font-size: 12px;">L.C No. & Date</th>

        <th  style=" font-size: 12px;">Indian B/E No. & Date</th>
        <th  style=" font-size: 12px;">Yard/Shed</th>
        {{-- <th  style=" font-size: 12px;">Yard/Shed No</th> --}}



        <!-- <th>Actions</th> -->
    </tr>
    </thead>
    <tbody>	@php($i=0)
    @php
        $yardSerialNo = array();
    @endphp
    @foreach($dateWisePostingData as $key => $manifestEntry)
        <tr>
            {{--<td> {{ ++$i }} {{ $manifestEntry->m_marks_no }}</td>--}}
            <td> {{ ++$i }}</td>
            <td>{{ $manifestEntry->m_marks_no }}</td>
            <th>{{ $manifestEntry->m_manifest }}</th>
            <td>{{ $manifestEntry->cargo_name }}</td>
            <td>Gr.Wt-{{ $manifestEntry->m_gweight }} Nt.Wt-{{ $manifestEntry->m_nweight }}</td>
            {{--<td>{{ $manifestEntry->m_nweight }}</td>--}}
            <td>{{ $manifestEntry->m_package_no }}-{{ $manifestEntry->m_package_type }}</td>

            <td>{{ $manifestEntry->m_cnf_value }}</td>

            <td>{{ $manifestEntry->m_exporter_name_addr }}</td>
            <td>{{ $manifestEntry->NAME." ".$manifestEntry->ADD1 }}</td>

            <td>{{ $manifestEntry->m_lc_no }} <br> {{ $manifestEntry->m_lc_date }}</td>

            <td>{{ $manifestEntry->m_ind_be_no }} <br>{{ $manifestEntry->m_ind_be_date }}</td>
            <td>{{ $manifestEntry->posted_yard_shed }}</td>
            {{-- <td>
                @php
                    if(isset($yardSerialNo[$manifestEntry->posted_yard_shed])) {
                        $yardSerialNo[$manifestEntry->posted_yard_shed]++;
                    }else {
                       $yardSerialNo[$manifestEntry->posted_yard_shed] = 1;
                    }
                @endphp
                {{ $yardSerialNo[$manifestEntry->posted_yard_shed] }}
            </td> --}}
        </tr>
    @endforeach
    </tbody>
</table>
<p style="text-align: right"><b>Total: {{ $i }}</b> </p>


</body>
</html>















{{--
<!DOCTYPE html>
<html>
<head>
    <title>Date Wiese Posting Branch Entry Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;

        }
        table, th, td {
            border: 1px solid black;
            padding: 1px;
            text-align: center;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 450px;
        }
    </style>
</head>
<body>
<img src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    <span style="font-size: 19px;">Date Wise Posting Branch Entry Report</span> <br>
    Date : {{$from_date}}
</p>

<br><br><br>
<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u><b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        --}}
{{--<th>Date</th>--}}{{--

        <th>Manifest</th>
        <th>Truck NO</th>
        <th>Gross Weight</th>

        --}}
{{--<th>Truck Type</th>--}}{{--

        <th>Truck Entry Time</th>
        <th>Created Time</th>
        --}}
{{--<th>Night Charges</th>--}}{{--

        --}}
{{--<th>Holiday Charges</th>--}}{{--

        --}}
{{--<th>Weighment Charge</th>--}}{{--

        --}}
{{--<th>Removal Charges</th>--}}{{--

        --}}
{{--<th>Truck Terminal Entry Fee</th>--}}{{--

        --}}
{{--<th>Truck Terminal Haltage Fee</th>--}}{{--

        --}}
{{--<th>Import Terminal Entry Fee</th>--}}{{--

        --}}
{{--<th>Import Terminal Haltage Fee</th>--}}{{--

        --}}
{{--<th>Miscellaneous Charges</th>--}}{{--

        --}}
{{--<th>Total Taka</th>--}}{{--

        --}}
{{--<th>VAT</th>--}}{{--

    </tr>
    </thead>
    <tbody>

    @foreach($DWRDate as $key => $u)
        <tr>
            <td>{{ $key+1}}</td>
            <td>{{ $u->manifest }}</td>
            <td>{{ $u->truck_no }}-{{ $u->truck_type }}</td>
            <td>{{ $u->gweight}}</td>

            --}}
{{--<td>{{ $u->truck_type }}</td>--}}{{--

            <td>{{ $u->truckentry_datetime }}</td>

            <td>{{ $u->manifest_created_time }}</td>
            --}}
{{--<td>{{ $u->Night_Charge }}</td>--}}{{--

            --}}
{{--<td>{{ $u->Holiday_Charge }}</td>--}}{{--

            --}}
{{--<td>{{ $u->Weighment_Charge }}</td>--}}{{--

            --}}
{{--<td>{{ $u->Removal_Charge }}</td>--}}{{--

            --}}
{{--<td></td>--}}{{--

            --}}
{{--<td></td>--}}{{--

            --}}
{{--<td></td>--}}{{--

            --}}
{{--<td></td>--}}{{--

            --}}
{{--<td></td>--}}{{--

            --}}
{{--<td>{{ number_format( $u->total,2) }}</td>--}}{{--

            --}}
{{--<td class="amount-right">{{ number_format($u->total_Vat,2)}}</td>--}}{{--

            --}}
{{--<td>{{ number_format($u->total_Vat,2) }}</td>--}}{{--

        </tr>
    @endforeach
    </tbody>
</table>
<p style="text-align: right"><b>Total Trucks: {{ $key +1}} </b> </p>

</body>
</html>--}}
