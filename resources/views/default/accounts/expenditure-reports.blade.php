@extends('layouts.master')
@section('title', 'Expenditure Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray; padding: 5px 0;
        }
        .headingTxt{
            color: #000000;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
    </style>
@endsection
@section('script')
    {!!Html::script('js/customizedAngular/accounts/expenditure-entry.js')!!}
@endsection
@section('content')
    <div class="col-md-12 text-center" ng-app="expenditureApp" ng-controller="expenditureCtrl">
        <h5  style="font-weight: bold; color: #000000">Expenditure Reports</h5><hr>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt"><b>Fixed Maintenance:</b></h6>
            <form class="form-inline" action="{{ route('accounts-expenditure-report-yearly-fixed-maintenance-expenditure-report') }}" target="_blank" method="get">
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
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-1">
            
        </div>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt">Fuel & Energy Sector Expenses:</h6>
            <form action="{{ route('accounts-expenditure-report-yearly-expenditure-fuel-energy-report') }}" target="_blank" method="get">
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
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="col-md-1">
            
        </div>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt">Repair & Maintenance Expenses:</h6>
            <form action="{{ route('accounts-expenditure-report-repair-maintenance-sector-report') }}" target="_blank" method="get">
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
            <h6 class="ok headingTxt">Others Variable Expense:</h6>
            <form action="{{ route('accounts-expenditure-report-others-variable-expense-report') }}" target="_blank" method="get">
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
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="col-md-1">
            
        </div>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt">Sub-Head Wise Expenditure:</h6>
            <form action="{{ route('accounts-expenditure-report-sub-head-wise-yearly-report') }}" target="_blank" method="get">
                <table>
                    <tr>
                        <th>Sub-Head:</th>
                        <td>
                            <select style="width: 120px" class="form-control" name="sub_h_id" ng-model="sub_h_id" ng-options="head.sh_id as head.acc_sub_head for head in DataSubHeadWise" {{--ng-change="getSubHead(head_id)"--}}>
                                <option value="" disabled selected>Select Sub-Head</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
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
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-1">
            
        </div>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt">Sub-Head Wise Monthly Expenditure:</h6>
            <form action="{{ route('accounts-expenditure-report-sub-head-wise-monthly-report') }}" target="_blank" method="get">

                <table>
                    <tr>
                        <th>Sub-Head:</th>
                        <td style="width: 110px">
                            <select class="form-control" name="sub_h_id_only_monthly" ng-model="sub_h_id_only_monthly" ng-options="head.sh_id as head.acc_sub_head for head in monthlydata"  {{--ng-change="getSubHead(head_id)"--}}>
                                <option value="" disabled selected>Select Sub-Head</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Month-Year:</th>
                        <td>

                            <input type="text" class="form-control datePicker" name="sub_head_only" id="sub_head_only" ng-model="sub_head_only" placeholder="Select Month-Year">
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

        <div class="col-md-3 reportFormStyle" style="top: -50px;">
            <h6 class="ok headingTxt">Date Range Wise Sub-Head Expenditure:</h6>
            <form action="{{ route('accounts-expenditure-report-date-range-wise-sub-head-expenditure-report') }}" target="_blank" method="get">
                <table>
                    <tr>
                        <th>Sub-Head:</th>
                        <td style="width: 130px;">
                            <select class="form-control" name="sub_h_id_monthly" ng-model="sub_h_id_monthly" ng-options="head.sh_id as head.acc_sub_head for head in monthlySubHeadWisedata" >
                                <option value="" disabled selected>Select Sub-Head</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>From:</th>
                        <td>
                            <input type="text" class="form-control datePicker" name="from_date_sub" id="from_date_sub" placeholder="Select Date">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th style="/*padding-left: 40px;*/">To:</th>
                        <td>
                            <div class="form-horizontal">
                                
                            </div>
                            <input type="text" class="form-control datePicker" name="to_date_sub" id="to_date_sub" placeholder="Select Date">
                        </td>
                        <td>
                            <button type="submit" style="padding-left: 10px;" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="col-md-1">

        </div>

        {{--<div class="col-md-12">--}}
            {{--<br><br>--}}
        {{--</div>--}}


        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt">Head Wise Expenditure:</h6>
            <form action="{{ route('accounts-expenditure-report-yearly-head-wise-expenditure-report') }}" target="_blank" method="get">
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
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
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