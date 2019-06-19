@extends('layouts.master')
@section('title', 'Transshipment Posting Other Reports')
@section('script')
@endsection
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
            <h4 class="text-center ok">Other Reports</h4>
            <div class="col-md-12">
                {{-- <div class="col-md-5">
                   <div class="list-group text-center">
                        <a class="list-group-item" href="{{ url('truckEntryDoneButPostingBranchEntryNotDoneReport') }}" target="_blank">Truck Entry Done, But Posting Branch Entry Not Done Report</a>
                    </div> 
                </div> --}}
                <div class="col-md-3 reportFormStyle">
                    <h6 class="ok headingTxt"><b>Monthly Posting Entry:</b></h6>
                    <form action="{{ route('transshipment-posting-monthly-entry-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <td>
                                    <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="month_entry" id="month_entry">
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
                    <h6 class="ok headingTxt"><b>Yearly Posting Entry:</b></h6>
                    <form action="{{ route('transshipment-posting-yearly-entry-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <td>
                                    <select class="form-control datePicker f" name="year" id="year">
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option selected value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2019">2020</option>

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
            <script type="text/javascript">
            $(function() {
                $("#month_entry").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });

                $('#month_entry').datepicker( {
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