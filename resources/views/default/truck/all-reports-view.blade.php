@extends('layouts.master')
@section('title', 'Truck Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray;
            margin: 10px 25px;
            padding: 5px 10px;
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
            height: 350px;
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
            height: 500px;
            padding: 10px;
        }

        #smallBoxBorder{
            width: 285px;
        }

    </style>
@endsection
@section('script')
    {!!Html::script('js/customizedAngular/truck/all-report.js')!!}
@endsection
@section('content')
    <div  class="col-md-12 text-center" ng-app="truckAllReportsApp" ng-controller="truckAllReportsCtrl">
        <h3 class="ok" style="font-weight: bold;">Truck Module Reports</h3><hr>



        <div id="boxShadowUpper" class="col-md-12" style="box-sizing: border-box">

            <h4 class="ok" style="font-weight: bold;">Details</h4> <hr>


            <div class="col-md-3 reportFormStyle" >
                <h4  class="ok headingTxt"><b >Date Wise Truck Entry:</b></h4>
                <form action="{{ route('truck-date-wise-truck-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr {{--style="background-color: red"--}}>

                            <td {{--colspan="3"--}}>
                                <select  ng-init="vehile_type_flage_pdf = '1'" {{--style="width: 150px"--}}  name="vehile_type_flage_pdf"  ng-model="vehile_type_flage_pdf"  class="form-control input-sm" >
                                    <optgroup label="(1). Truck">
                                        <option    value="1" selected >Goods</option>
                                        <option   value="2">Chassis(Chassis on Truck)</option>
                                        <option   value="3">Trucktor(Trucktor on Truck)</option>
                                    </optgroup>
                                    <optgroup label="(2). Self">
                                        <option  value="11">Chassis(Self)</option>
                                        <option  value="12">Trucktor(Self)</option>
                                        <option   value="13">Bus</option>
                                        <option   value="14">Three Wheller</option>
                                        <option   value="15">Rickshaw</option>
                                        <option value="16">Car(self)</option>
                                        <option value="17">Pick Up(self)</option>
                                    </optgroup>
                                </select></td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                             <td {{--colspan="3"--}}>
                                 <input type="text"  class="form-control datePicker" {{--style="width: 150px"--}}  ng-model="dateWiseReport"
                                        name="date" id="date" placeholder="Select Entry Date">
                             </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td colspan="1">
                                <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                    {{-- <span class="fa fa-calendar-o"></span>--}} Get
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-3 reportFormStyle">
                <h4  class="ok headingTxt"><b>Date Wise Truck Exit:</b></h4>
                <form action="{{ route('truck-date-wise-truck-exit-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text"  class="form-control datePicker" ng-model="exitDate"
                               name="date" id="exitDate" placeholder="Select Exit Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button ng-disabled="!exitDate" type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Get
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>



        {{--<div class="col-md-" style="">--}}
           {{--<br>--}}
        {{--</div>--}}

<br><br> <br><br> <br><br>

        <div id="boxShadow" class="col-sm-12" {{--style="background-color: yellow"--}} >

            <h4 class="ok" style="font-weight: bold; text-align: center!important;">Summary</h4><hr>

            <div class="col-sm-3 reportFormStyle"  id="smallBoxBorder" {{--style="background-color: red"--}}>
                <h4 class="ok headingTxt"><b >Monthly Truck Entry/Exit :</b></h4>
                <form action="{{ route('truck-monthly-truck-entry-exit-report') }}" target="_blank" method="POST">
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


            <div  class="col-sm-3 reportFormStyle"  id="smallBoxBorder"{{--style="background-color: red"--}}>
                <h4 class="ok headingTxt"><b >Yearly Truck Entry/Exit :</b></h4>
                <form action="{{ route('truck-yearly-truck-entry-exit-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <select title=""  class="form-control" name="year" id="year">
                                    <option value="2016">2016</option>
                                    <option  value="2017">2017</option>
                                    <option selected value="2018">2018</option>
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

            <div  class="col-sm-3 reportFormStyle" id="smallBoxBorder" >
                <h4 class="ok headingTxt"><b>Year Wise Truck Entry:</b></h4>
                <form class="form-inline" action="{{ route('truck-fiscal-year-wise-truck-entry-report') }}" target="_blank" method="get">
                    <table>
                        <tr>
                            <th>Fiscal Year:</th>
                            <td>
                                <select title="" class="form-control" name="year">
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



            <div class="col-sm-3 reportFormStyle" id="smallBoxBorder">

                <h4 class="ok headingTxt"><b>Year Wise Truck Exit:</b></h4>
                <form class="form-inline" action="{{ route('truck-fiscal-year-wise-truck-exit-report') }}" target="_blank" method="get">
                    <table>
                        <tr>
                            <th>Fiscal Year:</th>
                            <td>
                                <select title="" class="form-control" name="year">
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



            <div class="col-sm-3 reportFormStyle" id="smallBoxBorder">
                <h4 class="ok headingTxt"><b>Date-Range Wise Exit:</b></h4>
                <form action="{{ route('truck-date-range-wise-truck-exit-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group" style="background-color: yellow" >
                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_truck_Exit" id="from_date_truck_Exit">

                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_truck_Exit" id="to_date_truck_Exit">
                        <button style="width: 60px" type="submit" class="btn btn-primary center-block">
                            Get
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-sm-3 reportFormStyle" id="smallBoxBorder">
                <h4  class="ok headingTxt"><b>Date-Range Wise Truck Entry:</b></h4>
                <form action="{{ route('truck-date-range-wise-truck-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <th>From:</th>
                            <td colspan="2">
                                <input type="text" placeholder="From Date" class="form-control datePicker" name="from_date_v" id="from_date_v" ng-model="from_date_v">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>To:</th>
                            <td colspan="2">
                                <input type="text" class="form-control datePicker" placeholder="To Date" name="to_date_v" id="to_date_v" ng-model="to_date_v">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>S/L:</th>
                            <td>
                                <select title="" class="form-control" name="slValue" ng-model="slValue" ng-options="slValue.page as slValue.firstSl + '-' + slValue.lastSl for slValue in slValues" ng-change="getRange(slValue)">
                                    <option value="">Please Select</option>
                                </select>
                            </td>
                            <input type="hidden" name="range" id="range" ng-model="range">
                            <td>
                                <button type="submit" class="btn btn-primary center-block">
                                    Get
                                </button>
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