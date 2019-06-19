<!DOCTYPE html>
<html>
<head>
    <title>Item Wise Warehouse Lying Report</title>
    <link rel="shortcut icon" href="{{asset('/images/favicon.ico')}} " type="image/x-icon">
    <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">

    <style>
        html {
            margin: 5px 5px 0;
        }
        body{
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            opacity: .2;
        }
        table.dataTable {
            border-collapse: collapse;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 3px 0;
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
        <td style="width: 20%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 58%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">T.C.B. Babhan (5th Floor)</span> <br>
            <span>Karwan Bazar Dhaka, 1215</span>
            {{--{{$firstDate .'-'. $lastDate}} 's Import-Export Report--}}
            <p> <b>Warehouse Lying Report</b></p>
        </td>
        <td style="width: 22%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($date))}}
        </td>

    </tr>
</table>

<table width="100%" class="dataTable">
                <thead>
        			<tr>
                        <th>S/L</th>
                        <th>Item</th>
                        <th>Current Weight <br> M.Ton</th>
                        <th>Total Imported </th>
                        <th>Total Exported </th>

                    </tr>

        		</thead>
        		<tbody>
                @php
                    $total_current_weight=0;
                    $total_import=0;
                    $total_export=0;
                @endphp
                @if($data)

                    @foreach($data as $key=>$value)
        			<tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->Description}}</td>
                        <td>{{$value->current_weight>0 ? number_format(($value->current_weight/1000),2):''}} @php($total_current_weight+=$value->current_weight)</td>

                        <td>{{$value->weight>0 ? number_format(($value->weight/1000),2):''}}@php($total_import+=$value->weight)</td>
                        <td></td>

        			</tr>
                    @endforeach
                    @endif
        		</tbody>
        		<tfoot>
                    <tr>
                        <td></td>
                        <th>Total</th>
                        <th>{{$total_current_weight >0 ?number_format(($total_current_weight/1000),2):''}}</th>
                        <th>{{$total_import>0 ?number_format(($total_import/1000),2) :''}}</th>
                        <th></th>
                    </tr>
                </tfoot>
        	</table>


</body>
</html>