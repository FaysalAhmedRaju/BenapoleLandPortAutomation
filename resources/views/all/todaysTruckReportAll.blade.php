
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
<img src="../public/img/blpa.jpg">
<p class="center"><span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span>  <br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    Truck Entry Register</p>
  <h5 style="text-align: right;padding-right: 35px;"> Date: {{$date}}</h5>

  <table width="550">
  <thead>
  <tr>
   <th>S/L</th>
   <th>Truck No.</th>
   <th>Description of Goods</th>
   <th>Manifest No.</th>
   <th>Driver Card</th>
      <th>Entry Date</th>
      <th>Created By</th>

  </tr>
  </thead>
  <tbody>
@foreach($manifestdata as $key => $u)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $u->truck_type }}-{{ $u->truck_no }}</td>
        <td>{{ $u->cargo_name }}</td>
        <td>{{ $u->manifest }}</td>
        <td>{{ $u->driver_card }}</td>
        <td>{{ $u->truckentry_datetime }}</td>
        <td>{{ $u->created_by }}</td>


    </tr>
    @endforeach

    </tbody>
    </table>
<p style="text-align: right"><b>Total Trucks: {{$todaysTotalCount}}</b> </p>
    </body>
    </html>

