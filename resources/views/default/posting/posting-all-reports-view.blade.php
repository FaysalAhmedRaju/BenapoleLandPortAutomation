@extends('layouts.master')
@section('title', 'Posting Module Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray;
            padding: 5px 0;
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
            box-shadow: 0px 0px 1px 1px grey;

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
            box-shadow:0px 0px 1px 1px grey;

            box-sizing: border-box;
            width: 1050px;
            height: 250px;
            padding: 10px;
        }
    </style>
@endsection
@section('script')
    {{-- {!!Html::script('js/customizedAngular/posting-monthly-yearly-reports.js')!!} --}}
@endsection
@section('content')
    <div class="col-md-12 text-center"{{--  ng-app="postingAllReportsApp" ng-controller="postingAllReportsCtrl" --}}>
        <h3 class="ok" style="font-weight: bold;">Posting Module Reports</h3><hr>
        <div id="boxShadowUpper"	 class="col-md-12">
            <h4 class="ok" style="font-weight: bold;">Details</h4><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Date Wise Posting:</b></h4>
                <form action="{{ route('posting-date-wise-manifest-report') }}" target="_blank" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date" ng-model="from_date">
                    </div>
                    <button type="submit" class="btn btn-primary" ng-disabled="!from_date">Get</button>

                </form>
            </div>
        </div>



        <div class="col-md-9">
                <br>
        </div>
        {{--<div class="col-md-1">--}}
                {{--<br><br>--}}
        {{--</div>--}}

        {{--<div class="col-md-12">--}}
            {{--<br><br><br>--}}
        {{--</div>--}}


        <div id="boxShadow"	 class="col-md-12">
            <h4 class="ok" style="font-weight: bold; ">Summary</h4><hr>
            <div class="col-md-3 reportFormStyle">
                <h4 class="ok headingTxt"><b>Year Wise Posting:</b></h4>
                <form class="form-inline" action="{{ route('posting-year-wise-posting-report') }}" target="_blank" method="get">
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
                <h4 class="ok headingTxt"><b>Month Wise Entry Report:</b></h4>
                <form action="{{ route('posting-month-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_posting" id="from_date_posting">

                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_posting" id="to_date_posting">

                        <div class="input-group-btn">

                            <button type="submit" class="btn btn-primary center-block">Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-1">

            </div>

        </div>





        <script type="text/javascript">
            $(function() {
                $("#sub_head_only").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });


                $('#sub_head_only, #from , #to').datepicker( {
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