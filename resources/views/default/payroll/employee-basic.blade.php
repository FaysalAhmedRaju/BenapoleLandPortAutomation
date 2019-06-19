@extends('layouts.master')
@section('title','Employee Basic')
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
    <script type="text/javascript">
        var grade_list = {!! json_encode($grade_list) !!};
    </script>
    {!! Html :: script('js/customizedAngular/payroll/employee-basic.js') !!}
    {!! Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="HomeRentalAllowanceApp" ng-controller="HomeRentalAllowanceCtrl">
        <div class="col-md-12">

        </div>
        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px; margin-top: 20px;">
            <h4 class="text-center ok">Employee Basic</h4>
            <div class="breakDiv" style="border: 10px;  margin-top: 50px; margin-bottom: 50px">

            </div>

            <div class="col-md-12">
                <div class="alert alert-success" id="savingSuccessHouseAllowance" ng-hide="!savingSuccessHouseAllowance">@{{ savingSuccessHouseAllowance }}</div>
                <div class="alert alert-danger" id="savingErrorHouseAllowance" ng-hide="!savingErrorHouseAllowance">@{{ savingErrorHouseAllowance }}</div>
                <form name="houseRentAllowance" id="houseRentAllowance" novalidate>
                    <table>
                        <tr>
                            <th>Grade<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required name ="grade" ng-model="grade" id="grade" ng-options="grade.id as grade.grade_name for grade in grade_list" ng-change="getGradeBasic(grade)">
                                    <option value="" selected="selected">Select Grade</option>
                                </select>

                                <span class="error" ng-show="houseRentAllowance.grade.$invalid && submittedHouseRentalAllowance">Grade is required</span>

                            </td>
                            <th style="padding-left: 15px;">Scale Year<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required name ="scale_year" ng-model="scale_year" id="scale_year" ng-options="scale_year.scale_year as scale_year.scale_year  for scale_year in scale_yearData" ng-change="getGradeBasic(grade)" {{--ng-change="getHomeRent(grade_basic)"--}}>
                                    <option value="" selected="selected">Select Year</option>
                                </select>
                                <span class="error" ng-show="houseRentAllowance.scale_year.$invalid && submittedHouseRentalAllowance">Scale Year is Required</span>

                            </td>

                            <th style="padding-left: 15px;">Grade Basic<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required name ="grade_basic" ng-model="grade_basic" id="grade_basic" ng-options="grade_basic.id as grade_basic.basic for grade_basic in gradeBasicData" ng-change="getHomeRent(grade_basic)">
                                    <option value="" selected="selected">Select Grade</option>
                                </select>

                                <span class="error" ng-show="houseRentAllowance.grade_basic.$invalid && submittedHouseRentalAllowance">Grade is required</span>
                            </td>


                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th >Employee<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" style="width: 200px" required  name ="Employee" ng-model="Employee" id="Employee" ng-options="employ.id as employ.emp_id+'-'+employ.name for employ in getEmployeesInfoData" ng-change="getHomeArea(Employee)">
                                    <option value="" selected="selected">Select Employee</option>
                                </select>

                                <span class="error" ng-show="houseRentAllowance.Employee.$invalid && submittedHouseRentalAllowance">Employee is required</span>

                            </td>


                            {{--<th style="padding-left: 15px;">Home Area<span class="mandatory">*</span>:</th>--}}
                            {{--<td>--}}

                                {{--<select class="form-control" ng-init="home_rent = '0'" name ="home_rent" ng-model="home_rent" id="home_rent"  required>--}}
                                    {{--<option value="0">Select Home Area</option>--}}
                                    {{--<option value="1">Dhaka Metro Area</option>--}}
                                    {{--<option value="2">Expensive Area</option>--}}
                                    {{--<option value="3">Other Area</option>--}}
                                {{--</select>--}}
                                {{--<span class="error" ng-show="houseRentAllowance.home_rent.$invalid && submittedHouseRentalAllowance">Home Rent is Required</span>--}}

                            {{--</td>--}}

                            <th style="padding-left: 15px;" ng-show="home_rent_show">Home Rent:</th>
                            <td ng-show="home_rent_show">
                                <input type="text" class="form-control" ng-model="final_home_rent"  name="final_home_rent" id="final_home_rent" disabled>
                            </td>

                            <th style="padding-left: 15px;"></th>
                            <td>
                                <img ng-src="@{{ employee_photo ? '/img/employees/'+employee_photo : '/img/noImg.jpg'}}" height="80" width="100">
                            </td>



                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>

                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="saveEmployeeBasic()" ng-if="btnSaveMonthlyDeduction" >Save</button>

                                <button type="button" class="btn btn-success center-block" ng-click="saveEmployeeBasic()" ng-if="btnUpdateMonthlyDeduction">Update</button>

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
            <table class="table table-bordered" ng-show="employeeBasicTable">
                <caption><h4  class="text-center ok">Employee Basic Details</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Grade</th>
                    <th>Grade Basic</th>
                    <th>Scale Year</th>
                    <th>Employee</th>
                    <th>Home Rent</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(allowance.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="allowance in homeAllowanceData | orderBy:'allowance.id':true | itemsPerPage:5" pagination-id="houseRentAllowance">
                    <td>@{{$index+1}}</td>
                    <td>@{{allowance.grade_name}}</td>
                    <td>@{{allowance.basic}}</td>
                    <td>@{{allowance.scale_year}}</td>
                    <td>@{{allowance.emp_id}} - @{{ allowance.name }}</td>
                    <td>@{{allowance.house_rent}}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm " ng-click="pressUpdateBtn(allowance)">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" ng-click="pressDeleteBtn(allowance)">Delete</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7" class="text-center">
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