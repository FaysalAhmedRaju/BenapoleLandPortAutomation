@extends('layouts.master')
@section('title', 'Date Wise WareHouse Entry Report')
@section('script')
@endsection
@section('content')
    <div class="col-md-12">
        <div class="col-md-4 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">


            <h4 class="center-block">Date Wise WareHouse Entry Report</h4>
            <form action="{{ url('DateWiseWarehouseReceiveReportPdf') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <div class="col-md-12">
                    <table>
                        <br>
                        <tr>
                            <th>Date:</th>
                            <td>
                                <input type="text" class="form-control datePicker" name="date" id="date"  placeholder="Select Date"/>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary center-block">Get Report</button>
                            </td>
                        </tr>
                    </table>
                    <br>
                </div>
            </form>
        </div>
    </div>
@endsection