<!DOCTYPE html>
<html>
<head>
    <title>Yearly Other Variable Expense Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;

        }
        table, th, td {
            border: 1px solid black;
            padding:10px  0;
            text-align: center;
            font-size: 9px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 480px;
        }

        .txt-right{
            text-align: right;
        }
    </style>
</head>
<body>


<img src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    <span style="font-size: 19px;">Other Variable Expense Report</span> <br>

</p>
<br><br><br>
<table style="border: none !important;">
    <tr>
        <td  style="border: none !important; text-align: left">

        </td>
        <td  style="border: none !important; text-align: right">Date : {{$todayWithTime}}</td>

    </tr>
</table>

<br>
<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/l</th>

        <th>Month/Year</th>
        <th>Cargo Handling Expenses</th>
        <th>Tarpaulin Demarage</th>
        <th>Cargo Handling Equipment Expenses</th>
        <th>MOrok Dues Payment</th>
        <th>Transportation Traveling Allowance</th>
        <th>Rent Income Tax</th>
        <th>Printing Writing Materials</th>
        <th>Medical Expenses Clinick</th>
        <th>Dresses</th>
        <th>Training</th>
        <th>Residential Building Repairing</th>
        <th>FireExtinguisher Equipment Repairing & Gasfilling</th>
        <th>Internal Road Repairing</th>
        <th>Generator Repairing</th>
        <th>Warehouse Electricity Repairing</th>
        <th>Water Supply Arrangement Repairing</th>
        <th>Internal Sewer Drain Repairing</th>
        <th>Safty Tank Repairing & Cleaning</th>
        <th>Car Repairing</th>
        <th>Observation Tower Repairing</th>
        <th>Sheds Scales Coloring</th>
        <th>Passengers Bus Terminal Repairing</th>
        <th>Firehydrogen System Repair Maintenance</th>
        <th>MiscellaneousExpenses</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>


    @php ([$total=0,$totalCargoHandlingExpenses=0,$totalTarpaulinDemarage=0,$totalCargoHandlingEquipmentExpenses=0,$totalMOrokDuesPayment=0])
    @php ([$totalTransportationTravelingAllowance=0,$totalRentIncomeTax=0,$totalPrintingWritingMaterials=0,$totalMedicalExpensesClinick=0,$totalDresses=0])
    @php ([$totalTraining=0,$totalResidentialBuildingRepairing=0,$totalFireExtinguisherEquipmentRepairingGasfilling=0,$totalInternalRoadRepairing=0,$totalGeneratorRepairing=0])
    @php ([$totalWarehouseElectricityRepairing=0,$totalWaterSupplyArrangementRepairing=0,$totalInternalSewerDrainRepairing=0,$totalSaftyTankRepairingCleaning=0,$totalCarRepairing=0])
    @php ([$totalobservationTowerRepairing=0,$totalShedscalescoloring=0,$totalPassengersBusTerminalRepairing=0,$totalFirehydrogenSystemRepairMaintenance=0,$totalMiscellaneousExpenses=0])


    @foreach($expenditure as $key => $ex)
        <tr>
            <td width="60">{{ ++$key }}</td>
            <td>{{$ex->CreateMonths}}-{{$ex->YearName}}</td>
            <td>{{ $ex->CargoHandlingExpenses }}</td>
            <td>{{ $ex->TarpaulinDemarage }}</td>
            <td>{{ $ex->CargoHandlingEquipmentExpenses }}</td>
            <td>{{ $ex->MOrokDuesPayment }}</td>
            <td>{{ $ex->TransportationTravelingAllowance }}</td>
            <td>{{ $ex->RentIncomeTax }}</td>
            <td>{{ $ex->PrintingWritingMaterials }}</td>
            <td>{{ $ex->MedicalExpensesClinick }}</td>
            <td>{{ $ex->Dresses }}</td>
            <td>{{ $ex->Training }}</td>
            <td>{{ $ex->ResidentialBuildingRepairing }}</td>
            <td>{{ $ex->FireExtinguisherEquipmentRepairingGasfilling }}</td>
            <td>{{ $ex->InternalRoadRepairing }}</td>
            <td>{{ $ex->GeneratorRepairing }}</td>
            <td>{{ $ex->WarehouseElectricityRepairing }}</td>
            <td>{{ $ex->WaterSupplyArrangementRepairing}}</td>
            <td>{{ $ex->InternalSewerDrainRepairing }}</td>
            <td>{{ $ex->SaftyTankRepairingCleaning }}</td>
            <td>{{ $ex->CarRepairing }}</td>
            <td>{{ $ex->observationTowerRepairing }}</td>
            <td>{{ $ex->Shedscalescoloring }}</td>
            <td>{{ $ex->PassengersBusTerminalRepairing }}</td>
            <td>{{ $ex->FirehydrogenSystemRepairMaintenance }}</td>
            <td>{{$ex->MiscellaneousExpenses}}</td>


            <td class="txt-right">{{ number_format($ex->total , 0, '.', ',')}}</td>
            @php ([$total+=$ex->total,$totalCargoHandlingExpenses+=$ex->CargoHandlingExpenses,$totalTarpaulinDemarage+=$ex->TarpaulinDemarage,$totalCargoHandlingEquipmentExpenses+=$ex->CargoHandlingEquipmentExpenses,$totalMOrokDuesPayment+=$ex->MOrokDuesPayment])
            @php ([$totalTransportationTravelingAllowance+=$ex->TransportationTravelingAllowance,$totalRentIncomeTax+=$ex->RentIncomeTax,$totalPrintingWritingMaterials+=$ex->PrintingWritingMaterials,$totalMedicalExpensesClinick+=$ex->MedicalExpensesClinick,$totalDresses+=$ex->Dresses])
            @php ([$totalTraining+=$ex->Training,$totalResidentialBuildingRepairing+=$ex->ResidentialBuildingRepairing,$totalFireExtinguisherEquipmentRepairingGasfilling+=$ex->FireExtinguisherEquipmentRepairingGasfilling,$totalInternalRoadRepairing+=$ex->InternalRoadRepairing,$totalGeneratorRepairing+=$ex->GeneratorRepairing])

            @php ([$totalWarehouseElectricityRepairing+=$ex->WarehouseElectricityRepairing,$totalWaterSupplyArrangementRepairing+=$ex->WaterSupplyArrangementRepairing,$totalInternalSewerDrainRepairing+=$ex->InternalSewerDrainRepairing,$totalSaftyTankRepairingCleaning+=$ex->SaftyTankRepairingCleaning,$totalCarRepairing+=$ex->CarRepairing])
            @php ([$totalobservationTowerRepairing+=$ex->observationTowerRepairing,$totalShedscalescoloring+=$ex->Shedscalescoloring,$totalPassengersBusTerminalRepairing+=$ex->PassengersBusTerminalRepairing,$totalFirehydrogenSystemRepairMaintenance+=$ex->FirehydrogenSystemRepairMaintenance,$totalMiscellaneousExpenses+=$ex->MiscellaneousExpenses])


        </tr>
    @endforeach
    </tbody>


    <tfoot>
    <tr>
        <td colspan="2">
            Total
        </td>

        <td>{{$totalCargoHandlingExpenses}}</td>
        <td>{{$totalTarpaulinDemarage}}</td>
        <td>{{$totalWeightmentEquipmentRepairMaintenance}}</td>
        <td>{{$totalMOrokDuesPayment}}</td>
        <td>{{$totalTransportationTravelingAllowance}}</td>
        <td>{{$totalRentIncomeTax}}</td>
        <td>{{$totalPrintingWritingMaterials}}</td>
        <td>{{$totalMedicalExpensesClinick}}</td>
        <td>{{$totalDresses}}</td>
        <td>{{$totalTraining}}</td>
        <td>{{$totalPostcableTelephone}}</td>
        <td>{{$totalFireExtinguisherEquipmentRepairingGasfilling}}</td>
        <td>{{$totalInternalRoadRepairing}}</td>
        <td>{{$totalGeneratorRepairing}}</td>

        <td>{{$totalWarehouseElectricityRepairing}}</td>
        <td>{{$totalWaterSupplyArrangementRepairing}}</td>
        <td>{{$totalInternalSewerDrainRepairing}}</td>
        <td>{{$totalSaftyTankRepairingCleaning}}</td>
        <td>{{$totalCarRepairing}}</td>
        <td>{{$totalobservationTowerRepairing}}</td>
        <td>{{$totalShedscalescoloring}}</td>
        <td>{{$totalPassengersBusTerminalRepairing}}</td>
        <td>{{$totalFirehydrogenSystemRepairMaintenance}}</td>

        <td>{{$totalMiscellaneousExpenses}}</td>


        <td>{{$total}}</td>
    </tr>
    </tfoot>

</table>


</body>
</html>