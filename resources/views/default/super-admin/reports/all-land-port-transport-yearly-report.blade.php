<!DOCTYPE html>
<html>
<head>
    <title>Transport Report of All Land Ports</title>
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
            <p> <b>Transport Report of Land Ports Under Bangladesh Land Port Authority</b></p>
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
         <th>Foreign Truck</th>
         <th>Local Truck</th>
         <th>Total</th>
         <th>Foreign Truck</th>
         <th>Local Truck</th>
         <th>Total</th>
    </tr>

    </thead>
    <tbody>
    @php
        $total_foregn_truck1=0;
        $total_foregn_truck2=0;

       $total_local_truck1=0;
       $total_local_truck2=0;

       $total_truck1=0;
       $total_truck2=0;



    @endphp

        @if($data)
            @foreach($data as $key=>$value)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->port_name}}</td>
                    <td>{{$value->foreign_2017_18?$value->foreign_2017_18:''}}@php($total_foregn_truck1+=$value->foreign_2017_18)</td>
                    <td>{{$value->local_2017_18?$value->local_2017_18:''}} @php($total_local_truck1+=$value->local_2017_18)</td>
                    <td>{{$value->foreign_2017_18 + $value->local_2017_18 }}@php($total_truck1+=($value->foreign_2017_18 + $value->local_2017_18))</td>
                    <td>{{$value->foreign_2018_19?$value->foreign_2018_19:''}}@php($total_foregn_truck2+=$value->foreign_2018_19)</td>
                    <td>{{$value->local_2018_19}}@php($total_local_truck2+=$value->local_2018_19)</td>
                    <td>{{$value->foreign_2018_19 + $value->local_2018_19}}@php($total_truck2+=($value->foreign_2018_19 + $value->local_2018_19))</td>


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
        <th>{{$total_foregn_truck1}}</th>
        <th>{{$total_local_truck1}}</th>
        <th>{{$total_truck1}}</th>
        <th>{{$total_foregn_truck2?$total_foregn_truck2:''}}</th>
        <th>{{$total_local_truck2?$total_local_truck2:''}}</th>
        <th>{{$total_truck2?$total_truck2:''}}</th>

    </tr>
    </tfoot>
</table>






</body>
</html>

