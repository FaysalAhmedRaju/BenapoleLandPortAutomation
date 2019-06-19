@extends('layouts.master')
@section('title', 'Weightbridge Reports')
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
        #boxShadowUpper{
            margin: 20px 25px 20px 20px;
            /*// padding: 5px;*!*!*/
            /*box-shadow: 5px 10px;*/
            box-shadow:0px 0px 1px 1px grey;

            box-sizing: border-box;
            width: 1050px;
            height: 250px;
            padding: 10px;
            /*border: 10px solid;*/
        }
        #boxShadow {
            /*border: 1px solid;*/
            /*padding: 10px;*/
            margin: 20px 25px 20px 20px;
            padding: 5px;
            /*box-shadow: 5px 10px;*/
            box-shadow: 0px 0px 1px 1px grey;

            box-sizing: border-box;
            width: 1050px;
            height: 500px;
            padding: 10px;
        }
    </style>
@endsection
{{-- @section('script')
    {!!Html::script('js/customizedAngular/weightBridgeMonthYearWiseReports.js')!!}
@endsection --}}
@section('content')
    <div  class="col-md-12 text-center" {{-- ng-app="weightBrightAllReportsApp" ng-controller="WeightBridgeAllReportsCtrl" --}}>
        <h3 class="ok" style="font-weight: bold;">Weighbridge Module Reports</h3><hr>
        <div id="boxShadowUpper" class="col-md-12">
            <h4 class="ok" style="font-weight: bold;">Details</h4><hr>
            <div class="col-md-3 reportFormStyle" {{--style="padding: 0"--}}>
                <h4 class="ok headingTxt"><b>Date Wise Entry:</b></h4>
                <form action="{{ route('weighbridge-get-date-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text"  class="form-control datePicker" ng-model="dateWiseReport"
                               name="date" id="date" placeholder="Select Entry Date">
                        <div class="input-group-btn">
                            <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div   class="col-md-1">

            </div>


            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise Exit:</b></h4>
                <form action="{{ route('weighbridge-get-date-wise-exit-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text"  class="form-control datePicker" ng-model="exitDate"
                               name="date" id="exitDate" placeholder="Select Exit Date">
                        <div class="input-group-btn">
                            <button ng-disabled="!exitDate" type="submit" class="btn btn-primary">
                                Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>




    <div   class="col-md-12">
        <br><br>
    </div>

        <div id="boxShadow"	 class="col-md-12">

            <h4 class="ok" style="font-weight: bold; text-align: center !important;">Summary</h4><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Fiscal Year Wise Entry:</b></h4>
                <form class="form-inline" action="{{ route('weighbridge-fiscal-year-wise-entry-report') }}" target="_blank" method="get">
                    <table>
                        <tr>
                            <th>Fiscal Year:</th>
                            <td>
                                <select class="form-control" name="year">
                                    @foreach($year as $item)
                                        <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding-left: 10px;">
                                <button type="submit" class="btn btn-primary center-block">Get</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-1">

            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date-Range Wise Entry:</b></h4>
                <form action="{{ route('weighbridge-date-range-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_v" id="from_date_v">

                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_v" id="to_date_v">

                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary center-block">Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-1">

            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date-Range Wise Exit:</b></h4>
                <form action="{{ route('weighbridge-date-range-wise-exit-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_exit" id="from_date_exit">

                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_Exit" id="to_date_Exit">

                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary center-block">Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>




            <div class="col-md-1">

            </div>
            <div class="col-md-12">
                <br><br>
            </div>



            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Fiscal Year Wise Exit:</b></h4>
                <form class="form-inline" action="{{ route('weighbridge-fiscal-year-wise-exit-report') }}" target="_blank" method="get">
                    <table>
                        <tr>
                            <th>Fiscal Year:</th>
                            <td>
                                <select class="form-control" name="year">
                                    @foreach($year as $item)
                                        <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding-left: 10px;">
                                <button type="submit" class="btn btn-primary center-block">Get</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-1">

            </div>

            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Monthly Entry/Exit:</b></h4>
                <form action="{{ route('weighbridge-monthly-entry-exit-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="month_entry_exit" id="month_entry_exit">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary center-block">Get</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="col-md-1">

            </div>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Yearly Entry/Exit:</b></h4>
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
                                <button type="submit" class="btn btn-primary center-block">Get</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>



        </div>



        <script type="text/javascript">
            $(function() {
                $("#sub_head_only,#month_entry_exit").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });


                $('#sub_head_only, #from , #to,#month_entry_exit').datepicker( {
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


                        }
                    }
                });
            });

        </script>
    </div>
@endsection