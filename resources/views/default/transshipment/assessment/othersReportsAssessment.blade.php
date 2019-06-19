@extends('layouts.master')
@section('title', 'Assessment Report')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray;
            padding: 5px 0;
        }

        .headingTxt {
            color: #000000;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12">
        <h4 class="text-center"><u>Assessment Reports</u></h4>
        <div class="col-md-12">
            <br><br>
            <div class="col-md-3 reportFormStyle">
                <h6 class="ok headingTxt"><b>Assessment Entry Report</b></h6>
                <form action="{{ route('assessment-reports-user-and-date-wise') }}" target="_blank" method="POST">
                    {{ csrf_field() }}

                    <select name="reportType" id="" class="form-control">
                        <option value="">All Users</option>

                        {{Auth::user()->role_id}}--}}
                        @if(Auth::user()->role_id==1)
                            @foreach($assessment_users as $k=>$v)
                                <option value="{{$v->id}}">
                                    {{$v->name}}
                                </option>
                            @endforeach

                            @else
                            <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                        @endif


                    </select>
                    <br>
                    <div class="input-group">
                        <input type="text" style="" class="form-control datePicker"
                               name="entryDate" id="date" placeholder="Select Ass. Entry Date">
                        <div class="input-group-btn">

                            <button style="" type="submit" class="btn btn-primary">
                                Get Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-1">
            </div>


            <div class="col-md-3 reportFormStyle">
                <h6 class="ok headingTxt"><b>Monthly Assessment Entry:</b></h6>
                <form action="{{ url('monthlyAssessmentEntry') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <input type="text" placeholder="Please Select Month" class="form-control datePicker f"
                                       name="month_entry_exit" id="month_entry_exit">
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
                <h6 class="ok headingTxt"><b>Yearly Assessment Entry:</b></h6>
                <form action="{{ route('Assessment-yearly-entry-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <select class="form-control datePicker f" name="year" id="year">
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option selected value="2018">2018</option>
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
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment").on('focus blur click', function () {
                $(".ui-datepicker-calendar").hide();

            });

            $('#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM yy',
                onClose: function (dateText, inst) {
                    function isDonePressed() {
                        return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                    }

                    if (isDonePressed()) {

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