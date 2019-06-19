@extends('layouts.master')
@section('title', 'Date Wise Report')
@section('script')



@endsection
@section('content')
    <div class="col-md-12">
        <div class="col-md-4 col-md-offset-4" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
            <form action="{{ url('reportPostingPDF') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <div class="col-md-12">
                    <table>
                        <br>
                        <tr>
                            <th>Date:</th>
                            <td>
                                <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date">
                            </td>
                            <td style="padding-left: 10px;">
                                <button type="submit" class="btn btn-primary center-block">GET REPORT</button>
                            </td>
                        </tr>
                    </table>
                    <br>
                </div>
            </form>
        </div>
    </div>
@endsection