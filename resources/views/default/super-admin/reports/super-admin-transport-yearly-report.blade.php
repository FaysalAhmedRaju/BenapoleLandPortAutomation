<!DOCTYPE html>
<html>
<head>
    <title>Date Wise ruck Entry Report</title>
    <style>
        html {
            margin: 5px 5px 0;
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
            <span style="font-size: 19px;">T.C.B. Babhan (5th Floor)</span> <br>
            <span>Karwan Bazar Dhaka, 1215</span><br>
            {{$firstDate .'-'. $lastDate}} 's Import-Export Report
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
        <th rowspan="2">Month Name</th>
        <th colspan="3">Num. Of Truck</th>
        <th colspan="2">Import</th>
        <th colspan="2">Export</th>

    </tr>
    <tr>
        <th>Foreign</th>
        <th>Local</th>
        <th>Total</th>
        <th>
            Measurement <br>
            (M.Ton)
        </th>
        <th>Tk.</th>
        <th>Measurement<br>
            (M.Ton)
        </th>
        <th>Tk.</th>
    </tr>
    <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>


    </tr>
    </thead>
    <tbody>
    @php
        $total_foregn_truck=0;
       $total_local_truck=0;
       $total_truck=0;
       $total_import_weight=0;
       $total_export_weight=0;

    @endphp

    @if($data)
        @foreach($data as $key=>$value)
            <tr>

                <th>{{$value->Month_name .', '.$value->year_name}} </th>
                <td>{{$value->total_foreign ? $value->total_foreign:''}} @php($total_foregn_truck+=$value->total_foreign) </td>
                <td>{{$value->total_local ? $value->total_local:''}}@php($total_local_truck+=$value->total_local)</td>
                <td>{{$value->total_truck ?$value->total_truck:''}}@php($total_truck+=$value->total_truck)</td>
                <td>{{number_format($value->foreign_weight,2)}}@php($total_import_weight+=$value->foreign_weight)</td>
                <td></td>
                <td></td>
                <td>{{$value->total_export_amount>0 ? $value->total_export_amount :''}}</td>


            </tr>
        @endforeach
    @endif
    </tbody>

    <tfoot>
    <tr>
        <th>Total:</th>
        <th>{{$total_foregn_truck}}</th>
        <th>{{$total_local_truck}}</th>
        <th>{{$total_truck}}</th>
        <th>{{number_format($total_import_weight,2)}}</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
</table>

<br><br><br>

<div>

    <table width="100%">
        <tr>
            <td style="width: 20%;"><b>Major Imported Goods:</b></td>
            <td style="width: 80%">

                @if($major_goods)
                    @foreach($major_goods as $key=>$value)
                        <span>
                        {{$value->Description}},
                        </span>
                    @endforeach
                @endif

            </td>
        </tr>

        <tr>
            <td colspan="2">
                &nbsp;

            </td>
        </tr>
        <tr>
            <td><b>Major Exported Goods:</b></td>
            <td>
                @if($major_goods)
                    @foreach($major_goods as $key=>$value)
                        <span>
                        {{$value->Description}},
                        </span>
                    @endforeach
                @endif
            </td>
        </tr>

    </table>

</div>


</body>
</html>

