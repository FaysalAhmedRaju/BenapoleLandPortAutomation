<!DOCTYPE html>
<html>
<head>
    <title>Manifest Report</title>
    <style>




        html {
            margin: 5px 12px 0;
        }

        table.dataTable {
            border-collapse: collapse;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            /*border: 1px solid black;*/
            padding: 5px;
            text-align: center;
            border: 0px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }

        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 2px;
        }
        /*.center{*/
            /*position: absolute;*/
            /*text-align: center;*/
            /*top: 0;*/
            /*left: 350px;*/
        /*}*/
    </style>
</head>
<body>

<table width="100%;"  class="dataTable">
    <tr>
        <td style="width: 15%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 60%; text-align:center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            Today's Manifest Posting Report


        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
            {{--Print Date : {{$todayWithTime}}--}}
        </td>
    </tr>
</table>
<br>


<table>
    <thead>
    <tr>

        <th>S/L</th>
        <th>Marks & NO</th>
        <th>Manifest NO</th>
        <th>Description of Goods</th>
        <th>Quantity</th>
        <th>NO. of Packages</th>

        <th>C&F Value</th>
        <th>Name & Address of Expoter</th>
        <th>Name & Address of Importer</th>
        <th>L.C No. & Date</th>

        <th>Indian B/E No. and Date</th>
        <th>Posted Yard or Shed</th>
        <th>Yard or Shed Serial No</th>



        <!-- <th>Actions</th> -->
    </tr>
    </thead>
    <tbody>	@php($i=0)
            {{-- @php
               $yardSerialNo = array();
            @endphp --}}
    @foreach($mainData as $key => $manifestEntry)
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

            <td>{{ $manifestEntry->m_lc_no }} <br> {{$manifestEntry->m_lc_date}}</td>

            <td>{{ $manifestEntry->m_ind_be_no }} <br>{{$manifestEntry->m_ind_be_date}}</td>
            <td>{{ $manifestEntry->posted_yard_shed }}</td>
            <td>
            {{-- @php
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