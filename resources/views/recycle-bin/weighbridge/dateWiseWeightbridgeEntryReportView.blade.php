@extends('layouts.master')
@section('title', 'Date Wise Weightbridge Entry Report')
@section('script')
@endsection
@section('content')
        <div class="col-md-12">
            <div class="col-md-4 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                <form action="{{ url('getDateWiseWeightbridgeEntryReportPDF') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <table>
                        <br>
                            <tr>
                                <th>Date:</th>
                                <td>
                                    <input type="text" class="form-control datePicker" name="date" id="date">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </form>
            </div>
        </div>
@endsection