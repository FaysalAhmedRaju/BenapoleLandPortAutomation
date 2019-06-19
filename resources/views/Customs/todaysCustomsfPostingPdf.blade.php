<!DOCTYPE html>
<html>
<head>
    <title>Manifest Report</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .center{

            position: absolute;
            text-align: center;
            top: 0;
            left: 320px;
        }
    </style>
</head>
<body>
<img src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    Today's Manifest Posting Report
</p>
<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
<table>
    <thead>
    <tr>

        <th>Marks & NO</th>
        <th>Manifest NO</th>
        <th width="5%">Description of Goods</th>
        <th>Quantity</th>
        <th>NO. of Packages</th>

        <th>C&F Value</th>
        <th>Name & Address of Expoter</th>
        <th>Name & Address of Importer</th>
        <th style="width: 90px;">L.C No. & Date</th>

        <th style="width: 90px;">Indian B/E No. and Date</th>

        <!-- <th>Actions</th> -->
    </tr>
    </thead>
    <tbody>	@php($i=0)
    @foreach($mainData as $key => $manifestEntry)
        <tr>
            {{--<td> {{ ++$i }} {{ $manifestEntry->m_marks_no }}</td>--}}

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

        </tr>
    @endforeach
    </tbody>
</table>
<p style="text-align: right"><b>Total Manifest NO: {{ $i }}</b> </p>
</body>
</html>