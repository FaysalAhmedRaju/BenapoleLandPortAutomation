@extends('layouts.master')
@section('title', 'WeignhBridge Report')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray; padding: 5px 0;
        }
        .headingTxt{
            color: #000000;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
            text-align: center;
        }
    </style>
@endsection
@section('content')
        <div class="col-md-12">
            <h4 class="text-center"><u>WeignhBridge Reports</u></h4>
            <div class="col-md-12">
               {{-- <div class="col-md-5">
                   <div class="list-group text-center">
                        <a class="list-group-item" href="{{ url('truckEntryDoneButWeightbridgeEntryNotDoneReport') }}" target="_blank">Truck Entry Done, But Weightbridge Entry Not Done Report</a>
                    </div>
                </div>--}}

                <br><br>
                <div class="col-md-3 reportFormStyle">
                    <h6 class="ok headingTxt"><b>Monthly WeighBridge Entry/Exit:</b></h6>
                    <form action="{{ route('weighbridge-monthly-entry-exit-report')}}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <td>
                                    <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="month_entry_exit" id="month_entry_exit">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary center-block">Get Report</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="col-md-1">

                </div>


                <div class="col-md-3 reportFormStyle">
                    <h6 class="ok headingTxt"><b>Yearly WeignhBridge Entry/Exit:</b></h6>
                    <form action="{{ route('weighbridge-yearly-entry-exit-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <td>
                                    <select class="form-control datePicker f" name="year" id="year">
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>

                                    </select>

                                    {{--<input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="year" id="year">--}}
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary center-block">Get Report</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="col-md-1">

                </div>
                <div class="col-md-3 reportFormStyle text-center">
                    <h6 class="ok headingTxt"><b>Todays Truck Entry (Tong Ghor):</b></h6>
                    {{--<a class="btn btn-primary" href="{{ url('dailyTruckReportPdf') }}" target="_blank">--}}
                        {{--Get Report--}}
                    {{--</a>--}}
                    <form action="{{ route('truck-date-wise-truck-pdf-report') }}" class="form-inline" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text"  class="form-control datePicker" ng-model="dateWiseReport"
                                   name="date" id="date" placeholder="Select Entry Date">
                            <div class="input-group-btn">
                                {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                                <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                    {{-- <span class="fa fa-calendar-o"></span>--}} Enrty Report
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-md-1">

                </div>




            </div>
        </div>

        <script type="text/javascript">
            $(function() {
                $("#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });

                $('#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment').datepicker( {
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
@endsection