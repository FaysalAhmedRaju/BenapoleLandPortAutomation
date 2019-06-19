@extends('layouts.master')
@section('title','Home Rental Allowance Rates')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

        .ui-datepicker-calendar {
            display: none;
        }

        #searchTxt {
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .autocomplete-options-container {
            position: relative;
            box-sizing: border-box;
        }

        .autocomplete-options-dropdown {
            position: absolute;
            top: -1px;
            left: 0px;
            border: 1px solid #ccc;
            border-top-color: #d9d9d9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            -webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1001;
            background: white;
            box-sizing: border-box;
        }

        .autocomplete-options-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .autocomplete-option {
            padding: 4px 10px;
            line-height: 22px;
            overflow: hidden;
            font: normal normal normal 13.3333330154419px/normal Arial;
        }

        .autocomplete-option.selected {
            background-color: rgba(0,0,0,0.2);
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
        }

    </style>
@endsection
@section('script')
    {!! Html :: script('js/customizedAngular/payroll/home-rental-allowance-rates.js') !!}
    {!! Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="HomeRentalAllowanceApp" ng-controller="HomeRentalAllowanceCtrl">
        <div class="col-md-12">

        </div>
        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px; margin-top: 20px;">
            <h4 class="text-center ok">Home Rental Allowance Rates</h4>
            <div class="breakDiv" style="border: 10px;  margin-top: 50px; margin-bottom: 50px">

            </div>
            <div class="col-md-12">
                <div class="alert alert-success" id="savingSuccessHouseAllowance" ng-hide="!savingSuccessHouseAllowance">@{{ savingSuccessHouseAllowance }}</div>
                <div class="alert alert-danger" id="savingErrorHouseAllowance" ng-hide="!savingErrorHouseAllowance">@{{ savingErrorHouseAllowance }}</div>
                <form name="houseRentAllowance" id="houseRentAllowance" novalidate>
                    <table>
                        <tr>
                            <th>Salary First Range<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="salary_first_range" id="salary_first_range" ng-model="salary_first_range" placeholder="Minimum Amount" required>
                                <span class="error" ng-show="houseRentAllowance.salary_first_range.$invalid && submittedHouseRentalAllowance">First Range is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Salary Last Range<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="checkbox" name="maximum_salary" value="maximum_salary" ng-model="maximum_salary" ng-click="maximumSalaryCheckBox(maximum_salary)"> Highest Salary
                                <input type="number" class="form-control" name="salary_last_range" id="salary_last_range" ng-model="salary_last_range" PLACEHOLDER="Maximum Amount" required ng-disabled="ifMaximumSalaryDisable">
                                <span class="error" ng-show="houseRentAllowance.salary_last_range.$invalid && submittedHouseRentalAllowance">Last Range is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Scale Year<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required name ="scale_year" ng-model="scale_year" id="scale_year" ng-options="scale_year.scale_year as scale_year.scale_year  for scale_year in scale_yearData">
                                    <option value="" selected="selected">Select Year</option>
                                </select>

                                <span class="error" ng-show="houseRentAllowance.scale_year.$invalid && submittedHouseRentalAllowance">Scale Year is Required</span>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th >Dhaka Metro Politon Area(%)<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="dhaka_metro_politon_area_rate" id="dhaka_metro_politon_area_rate" ng-model="dhaka_metro_politon_area_rate" placeholder="Type Rate(%)" required>
                                <span class="error" ng-show="houseRentAllowance.dhaka_metro_politon_area_rate.$invalid && submittedHouseRentalAllowance">Dhaka Metro Politon Area Rate is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Expensive Area(%)<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="expensive_area_rate" id="expensive_area_rate" ng-model="expensive_area_rate" placeholder="Type Rate(%)" required>
                                <span class="error" ng-show="houseRentAllowance.expensive_area_rate.$invalid && submittedHouseRentalAllowance">Expensive Area Rate is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Other Area(%)<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="other_area_rate" id="other_area_rate" ng-model="other_area_rate" placeholder="Type Rate(%)" required>
                                <span class="error" ng-show="houseRentAllowance.other_area_rate.$invalid && submittedHouseRentalAllowance">Other Area Rate is Required</span>
                            </td>


                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Dhaka Metro Politon Area<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="dhaka_metro_politon_area_limit" id="dhaka_metro_politon_area_limit" ng-model="dhaka_metro_politon_area_limit" placeholder="Minimum Amount" required>
                                <span class="error" ng-show="houseRentAllowance.dhaka_metro_politon_area_limit.$invalid && submittedHouseRentalAllowance">Dhaka Metro Politon Area Limit is Required</span>
                            </td>

                            <th style="padding-left: 15px;">Expensive Area<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="expensive_area_limit" id="expensive_area_limit" ng-model="expensive_area_limit"  placeholder="Minimum Amount" required>
                                <span class="error" ng-show="houseRentAllowance.expensive_area_limit.$invalid && submittedHouseRentalAllowance">Expensive Area Limit is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Other Area<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="other_area_limit" id="other_area_limit" ng-model="other_area_limit" placeholder="Minimum Amount" required>
                                <span class="error" ng-show="houseRentAllowance.other_area_limit.$invalid && submittedHouseRentalAllowance">Other Area Limit is Required</span>
                            </td>


                        </tr>

                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="saveHomeRentalAllowance()" ng-if="btnSaveMonthlyDeduction" >Save</button>

                                <button type="button" class="btn btn-success center-block" ng-click="saveHomeRentalAllowance()" ng-if="btnUpdateMonthlyDeduction">Update</button>

                                <span ng-if="dataLoadingMonthlyDeduction">
                                    <img src="img/dataLoader.gif" width="250" height="15" />
                                    <br />Please wait!
                                </span>
                            <td>
                        </tr>
                    </table>
                    <br>
                </form>
            </div>
        </div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered" {{--ng-show="monthlyDeductionTable"--}}>
                <caption><h4  class="text-center ok">Home Rental Allowance Rates Details</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Salary First Range</th>
                    <th>Salary Last Range</th>
                    <th>Scale Year</th>
                    <th>Dhaka Metro Politon Area Rate(%)</th>
                    <th>Expensive Area Rate(%)</th>
                    <th>Other Area Rate(%)</th>
                    <th>Dhaka Metro Politon Area</th>
                    <th>Expensive Area</th>
                    <th>Other Area</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(allowance.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="allowance in homeAllowanceData | orderBy:'allowance.id':true | itemsPerPage:5" pagination-id="houseRentAllowance">
                    <td>@{{$index+1}}</td>
                    <td>@{{allowance.first_range | numberToText}}</td>
                    <td>@{{allowance.last_range | numberToText}}</td>
                    <td>@{{allowance.scale_year}}</td>
                    <td>@{{allowance.dhaka_metro_politon_area_rate | numberFilter}}</td>
                    <td>@{{allowance.expensive_area_rate | numberFilter}}</td>
                    <td>@{{allowance.other_area_rate | numberFilter}}</td>
                    <td>@{{allowance.dhaka_metro_politon_area_limit | numberFilter}}</td>
                    <td>@{{allowance.expensive_area_limit | numberFilter}}</td>
                    <td>@{{allowance.other_area_limit | numberFilter}}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm " ng-click="pressUpdateBtn(allowance)">Update</button>
                        <button type="button" class="btn btn-danger btn-sm" ng-click="pressDeleteBtn(allowance)">Delete</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="11" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="houseRentAllowance">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <script type="text/javascript">
            $(function() {
                $('#').datepicker( {
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

                // var min = new Date().getFullYear()-5,
                // max = min + 10,
                // select = document.getElementById('scale_year');
                // for (var i = min; i<=max; i++){
                //    var opt = document.createElement('option');
                //    opt.value = i;
                //    opt.innerHTML = i;
                //    select.appendChild(opt);
                // }
            });
        </script>
    </div>
@endsection