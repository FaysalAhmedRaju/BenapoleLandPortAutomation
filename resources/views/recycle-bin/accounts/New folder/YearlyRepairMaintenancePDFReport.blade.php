<!DOCTYPE html>
<html>
<head>
    <title>Yearly Repair & Maintenance Report</title>
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
    <span style="font-size: 19px;">Yearly Repair & Maintenance Report</span> <br>

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
        <th>Building & Structure</th>
        <th>Furniture Office Equipment</th>
        <th>Weightment Equipment Repair & Maintenance </th>
        <th>Electricity Repairing</th>
        <th>Warehouse Repairing</th>
        <th>Godown Gate Repairing & Greasing</th>
        <th>Repairing</th>
        <th>Yards Repairing</th>
        <th>Security Wall Repairing</th>
        <th>Offices Repairing</th>
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


    @php ([$total=0,$totalBuildingandStructure=0,$totalFurnitureOfficeEquipment=0,$totalWeightmentEquipmentRepairMaintenance=0,$totalElectricityRepairing=0])
    @php ([$totalWarehouseRepairing=0,$totalGodownGateRepairingGreasing=0,$totalRepairing=0,$totalYardsRepairing=0,$totalSecurityWallRepairing=0])
    @php ([$totalOfficesRepairing=0,$totalResidentialBuildingRepairing=0,$totalFireExtinguisherEquipmentRepairingGasfilling=0,$totalInternalRoadRepairing=0,$totalGeneratorRepairing=0])
    @php ([$totalWarehouseElectricityRepairing=0,$totalWaterSupplyArrangementRepairing=0,$totalInternalSewerDrainRepairing=0,$totalSaftyTankRepairingCleaning=0,$totalCarRepairing=0])
    @php ([$totalobservationTowerRepairing=0,$totalShedscalescoloring=0,$totalPassengersBusTerminalRepairing=0,$totalFirehydrogenSystemRepairMaintenance=0,$totalMiscellaneousExpenses=0])


    @foreach($expenditure as $key => $ex)
        <tr>
            <td width="60">{{ ++$key }}</td>
            <td>{{$ex->CreateMonths}}-{{$ex->YearName}}</td>
            <td>{{ $ex->BuildingandStructure }}</td>
            <td>{{ $ex->FurnitureOfficeEquipment }}</td>
            <td>{{ $ex->WeightmentEquipmentRepairMaintenance }}</td>
            <td>{{ $ex->ElectricityRepairing }}</td>
            <td>{{ $ex->WarehouseRepairing }}</td>
            <td>{{ $ex->GodownGateRepairingGreasing }}</td>
            <td>{{ $ex->Repairing }}</td>
            <td>{{ $ex->YardsRepairing }}</td>
            <td>{{ $ex->SecurityWallRepairing }}</td>
            <td>{{ $ex->OfficesRepairing }}</td>
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
            @php ([$total+=$ex->total,$totalBuildingandStructure+=$ex->BuildingandStructure,$totalFurnitureOfficeEquipment+=$ex->FurnitureOfficeEquipment,$totalWeightmentEquipmentRepairMaintenance+=$ex->WeightmentEquipmentRepairMaintenance,$totalElectricityRepairing+=$ex->ElectricityRepairing])
            @php ([$totalWarehouseRepairing+=$ex->WarehouseRepairing,$totalGodownGateRepairingGreasing+=$ex->GodownGateRepairingGreasing,$totalRepairing+=$ex->Repairing,$totalYardsRepairing+=$ex->YardsRepairing,$totalSecurityWallRepairing+=$ex->SecurityWallRepairing])
            @php ([$totalOfficesRepairing+=$ex->OfficesRepairing,$totalResidentialBuildingRepairing+=$ex->ResidentialBuildingRepairing,$totalFireExtinguisherEquipmentRepairingGasfilling+=$ex->FireExtinguisherEquipmentRepairingGasfilling,$totalInternalRoadRepairing+=$ex->InternalRoadRepairing,$totalGeneratorRepairing+=$ex->GeneratorRepairing])

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

        <td>{{$totalBuildingandStructure}}</td>
        <td>{{$totalFurnitureOfficeEquipment}}</td>
        <td>{{$totalWeightmentEquipmentRepairMaintenance}}</td>
        <td>{{$totalElectricityRepairing}}</td>
        <td>{{$totalWarehouseRepairing}}</td>
        <td>{{$totalGodownGateRepairingGreasing}}</td>
        <td>{{$totalRepairing}}</td>
        <td>{{$totalYardsRepairing}}</td>
        <td>{{$totalSecurityWallRepairing}}</td>
        <td>{{$totalOfficesRepairing}}</td>
        <td>{{$totalResidentialBuildingRepairing}}</td>
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