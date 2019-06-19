<!DOCTYPE html>
<html>
<head>
    <title>Export-Import Report of All Land Ports Report</title>
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
        <td style="width: 20%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 58%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">T.C.B. Babhan (5th Floor)</span> <br>
            <span>Karwan Bazar Dhaka, 1215</span>
            {{--{{$firstDate .'-'. $lastDate}} 's Import-Export Report--}}
            <p> <b>Export-Import Report of Land Ports Under Bangladesh Land Port Authority</b></p>
        </td>
        <td style="width: 22%; font-size: 14px; text-align: right; vertical-align: bottom;">
           <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($date))}}
        </td>

    </tr>
</table>

<br>

<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th colspan="3">2017-2018</th>
        <th colspan="3">2018-2019</th>



    </tr>
     <tr>
         <th>S/L</th>
         <th>Land Port</th>
         <th>Import <br> (M.Ton)</th>
         <th>Export  <br> (M.Ton)</th>
         <th>Total <br> (M.Ton)</th>
         <th>Import <br> (M.Ton)</th>
         <th>Export <br> (M.Ton)</th>
         <th>Total <br> (M.Ton)</th>
    </tr>

    </thead>
    <tbody>
    @php
        $total_import1=0;
        $total_import2=0;

       $total_export1=0;
       $total_export2=0;

       $total1=0;
       $total2=0;



    @endphp

        @if($data)
            @foreach($data as $key=>$value)
                <tr>
                    <th>{{$key+1}}</th>
                    <th>{{$value->port_name}}</th>
                    <th>{{number_format($value->import_2017_18,2)}}@php($total_import1+=$value->import_2017_18)</th>
                    <th></th>
                     <th>{{number_format($value->import_2017_18,2) }}@php($total1+=($value->import_2017_18))</th>
                     <th>{{--{{$value->export_2018_19}}@php($total_export2+=$value->export_2018_19)--}}</th>
                    <th>{{--{{$value->import_2018_19 + $value->export_2018_19}}@php($total2+=($value->import_2018_19 + $value->export_2018_19))--}}</th>
                <td></td>
                </tr>
       @endforeach
    @endif
<tr>

    <td>2</td>
    <td>SonaMosjid Land Port</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>


</tr>
    <tr>

        <td>3</td>
        <td>Hili Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>4</td>
        <td>BuriMari Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>5</td>
        <td>Akhaora Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>6</td>
        <td>Bibi Bazar Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>7</td>
        <td>BanglaBanda Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>8</td>
        <td>Tacnaf Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>9</td>
        <td>Bomra Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    <tr>

        <td>10</td>
        <td>Thakorgao Land Port</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>


    </tr>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>Total:</th>
        <td>{{number_format($total_import1,2)}}</td>
        <td>{{$total_export1 ?  number_format($total_export1,2) :''}}</td>
        <td>{{number_format($total1,2)}}</td>
        <td>{{$total_import2 ? $total_import2:''}}</td>
        <td>{{$total_export2?$total_export2:''}}</td>
        <td>{{$total2?$total2:''}}</td>

    </tr>
    </tfoot>
</table>






</body>
</html>

