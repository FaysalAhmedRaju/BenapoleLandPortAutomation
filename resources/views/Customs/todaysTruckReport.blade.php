
<!DOCTYPE html>
<html>
<head>
 <title>Todays Truck Entry Report</title>



    <style>

        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        .center{

            text-align: center;

        }
    </style>



</head>
<body>
<p class="center"><span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span>  <br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    Truck Entry Register</p>
  <h5 style="text-align: right;padding-right: 35px;"> Date: {{$date}}</h5>

  <table width="550">
  <thead>
  <tr>
   <th>Sl</th>
   <th>Truck No.</th>
   <th>Goods Name</th>
   <th>Manifest No.</th>
   <th>Driver Card</th>

  </tr>
  </thead>
  <tbody>
@foreach($manifestdata as $key => $u)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $u->truck_type }}-{{ $u->truck_no }}</td>
        <td>{{ $u->cargo_name }}</td>
        <td>{{ $u->manf_id }}</td>
        <td>{{ $u->driver_card }}</td>

    </tr>
    @endforeach

    </tbody>
    </table>
<p style="text-align: right"><b>Total Trucks: {{$todaysTotalCount}}</b> </p>
    </body>
    </html>

