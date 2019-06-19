@extends('layouts.master')
@section('title', 'Income Report')
@section('script')
@endsection
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray; padding: 5px 0;
        }
        .headingTxt{
            color: #00dd00;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
    </style>

@endsection
@section('content')
        <div class="col-md-12 text-center">
            <h3 class="ok" style="font-weight: bold;">Income Reports</h3><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise Revenue:</b></h4>
                    <form action="{{ route('accounts-income-report-date-wise-revenue-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <th>Date:</th>
                                <td>
                                    <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date">
                                </td>
                                <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                    </form>
            </div>
            <div class="col-md-1">
            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Month Wise Revenue:</b></h4>
                    <form action="{{ route('accounts-income-reports-monthly-revenue-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <th>Month:</th>
                                <td>
                                    <input type="text" class="form-control datePicker f" name="month_year" id="month_year" placeholder="Select Month">
                                </td>
                                <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                    </form>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Subhead Wise Monthly Income:</b></h4>
                <form action="{{ route('accounts-income-report-sub-head-wise-monthly-income-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <th>Month:</th>
                            <td>
                                <input type="text" class="form-control datePicker f" name="month_year_income" id="month_year_income" placeholder="Select Month">
                            </td>
                            <td style="padding-left: 10px;">
                                <button type="submit" class="btn btn-primary center-block">Show</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-12">
                <br><br>
            </div>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Yearly Income:</b></h4>
                    <form action="{{ route('accounts-income-report-sub-head-wise-yearly-income-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <th>Fiscal Year:</th>
                                <td>
                                    <select class="form-control" name="year">
                                        @foreach($years as $item)
                                            <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                    </form>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Monthly Income Statement:</b></h4>
                    <form action="{{ route('accounts-income-report-monthly-income-statement-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <th>Month:</th>
                                <td>
                                    <input type="text" class="form-control datePicker f" name="month_year_income_statement" id="month_year_income_statement" placeholder="Select Month">
                                </td>
                                <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                    </form>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Monthly Receipts & Payment:</b></h4>
                    <form action="{{ route('accounts-income-report-monthly-receipts-and-payment-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <th>Month:</th>
                                <td>
                                    <input type="text" class="form-control datePicker f" name="month_year_receipts_and_payment" id="month_year_receipts_and_payment" placeholder="Select Month">
                                </td>
                                <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                    </form>
            </div>
            <div class="col-md-1">
            </div>

            <script type="text/javascript">
            $(function() {
                $("#month_year, #month_year_income, #month_year_income_statement, #month_year_receipts_and_payment").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });

                $('#month_year, #month_year_income, #month_year_income_statement, #month_year_receipts_and_payment').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function(dateText, inst) { 
                        function isDonePressed(){
                            return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                        }
                        if (isDonePressed()){

                            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('input');
                            //console.log(a);

                        }
                    }
                });
            });
        </script>
        </div>
@endsection