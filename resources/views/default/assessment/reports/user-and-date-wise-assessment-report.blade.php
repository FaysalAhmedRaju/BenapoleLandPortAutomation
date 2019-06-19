<!DOCTYPE html>
<html>
<head>
    <title>Assessment Report</title>
    <style>
        html {
            margin: 15px 5px;
        }

        body {
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 250px 180px;
            opacity: .2;
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

            Delivery Date {{date('d-m-Y',strtotime($requestedDate))}} 's Assessment Report

        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>

<br>

<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th>S/L</th>
        <th>
            Manifest No <br>
            & Date
        </th>
        <th>B/E No</th>
        <th>Customs Release Order No</th>
        <th>CNF Name</th>
        <th>Importer Name</th>
        <th>Goods Description</th>
        <th>Assessment Value<br>(With VAT)</th>
        <th>Status</th>
        <th>Creator</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @if(count($data)>0 && !empty($data) )
        @foreach($data as $k=>$v)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>
                    {{ $v->manifest }} <br>
                    {{ date('d-m-Y',strtotime($v->manifest_date)) }}
                </td>
                <td>{{ $v->be_no }}</td>
                <td>{{ $v->custom_release_order_no }}</td>
                <td>{{ $v->cnf_name }}</td>
                <td>{{ $v->importerName }}</td>
                @php
                    $vat = 0;
                    $goods = null;
                    $values = json_decode($v->assessment_values);
                    $goods = $values->good_description;
                    if($values->vat == 1) {
                        $vat = ceil(($v->totalAssessmentValue/100)*15);
                    } else {
                        $vat = 0;
                    }
                @endphp
                <td>{{ $goods }}</td>
                <td>{{ number_format(ceil($vat + $v->totalAssessmentValue),2) }}</td>
                <td style="font-weight: bold;">
                    @if($v->done)<span class="text-success">Done</span>@endif
                    @if(!$v->done)<span class="text-danger">Created</span>@endif
                </td>
                <td>{{ $v->created_by }}</td>
                <td>
                    <nobr>{{date('d-m-Y h:i:s A',strtotime($v->created_at))}}</nobr>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr>


        @if(count($data)>0 && !empty($data) )
            <td colspan="5" class="text-center">
                Total Assessments: {{ $k+1 }}
            </td>
            <td colspan="6" class="text-center">

            </td>
        @else
            <td colspan="11" class="text-center">
                <p style="color:red;">Data Not Found For the Date :{{date('d-m-Y',strtotime($requestedDate))}} </p>
            </td>
        @endif


    </tr>
    </tfoot>
</table>

</body>
</html>

