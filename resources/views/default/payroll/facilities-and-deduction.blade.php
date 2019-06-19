@extends('layouts.master')
@section('title','Employee Details')
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
    {!! Html :: script('js/customizedAngular/payroll/facilities-and-deduction.js') !!}
    {!! Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="FacilitiesAndDeductionApp" ng-controller="FacilitiesAndDeductionCtrl">
        <div class="col-md-12">
            <div class="col-md-5" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Fixed</h4>
                <div class="alert alert-success" id="savingSuccessFixed" ng-hide="!savingSuccessFixed">@{{ savingSuccessFixed }}</div>
                <div class="alert alert-danger" id="savingErrorFixed" ng-hide="!savingErrorFixed">@{{ savingErrorFixed }}</div>
                <form name="FixedFacilitiesAndDeduction" id="FixedFacilitiesAndDeduction" novalidate>
                    <div class="col-md-6">
                        <h4 class="text-center ok">Facilities</h4>
                        <table>
                            <tr>
                                <th>Education<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" class="form-control" name="education" ng-model="education" id="education" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.education.$invalid && submittedFixed">Education is Required</span></td>
                            </tr>
                            <tr>
                                <th>Medical<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" class="form-control" name="medical" ng-model="medical" id="medical" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.medical.$invalid && submittedFixed">Medical is Required</span></td>
                            </tr>
                            <tr>
                                <th>Tiffin<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" class="form-control" name="tiffin" ng-model="tiffin" id="tiffin" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.tiffin.$invalid && submittedFixed">Tiffin is Required</span></td>
                            </tr>
                            <tr>
                                <th>Washing:</th>
                                <td>
                                    <input type="number" class="form-control" name="washing" ng-model="washing" id="washing">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th>Transport:</th>
                                <td>
                                    <input type="number" class="form-control" name="transport" ng-model="transport" id="transport">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    &nbsp;
                                </th>

                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center ok">Deduction</h4>
                        <table>
                            <tr>
                                <th>GPF(%)<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" class="form-control" id="gpf" name="gpf" ng-model="gpf" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.gpf.$invalid && submittedFixed">GPF is Required</span></td>
                            </tr>
                            <tr>
                                <th>Revenue<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" class="form-control" name="revenue" id="revenue" ng-model="revenue" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.revenue.$invalid && submittedFixed">Revenue is Required</span></td>
                            </tr>
                            <tr>
                                <th>Scale Year<span class="mandatory">*</span>:</th>
                                <td>
                                    <select  class="form-control" required name ="scale_year" ng-model="scale_year" id="scale_year" ng-options="scale_year.scale_year as scale_year.scale_year  for scale_year in scale_yearData">
                                        <option value="" selected="selected">Select Year</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;<span class="error" ng-show="FixedFacilitiesAndDeduction.scale_year.$invalid && submittedFixed">Scale Year is Required</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <button type="button" class="btn btn-primary center-block" ng-click="saveFixed()" ng-if="btnSaveFixed" >Save</button>
                                    {{--ng-show="btnSaveFixed"--}}
                                    <button type="button" class="btn btn-success center-block" ng-click="updateFixed()" ng-if="btnUpdateFixed">Update</button>
                                    {{--ng-show="btnUpdateFixed"--}}
                                    <span ng-if="dataLoadingFixed">
                                        <img src="img/dataLoader.gif" width="250" height="15" />
                                    <br/>Please wait!
                                    </span>
                                <td>
                            </tr>
                        </table>
                    </div>

                </form>

            </div>
            <div class="col-md-7 table-responsive">
                <table class="table table-bordered" ng-show="fixedShow">
                    <caption><h4 class="text-center ok">Fixed Facilities & Deductions</h4></caption>
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Education</th>
                            <th>Medical</th>
                            <th>Tiffin</th>
                            <th>Washing</th>
                            <th>Transport</th>
                            <th>GPF(%)</th>
                            <th>Revenue</th>
                            <th>Scale Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-style="{'background-color':(fixed.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="fixed in fixedFacilitiesAndDeductions | orderBy:'fixed.id':true | itemsPerPage:3" pagination-id="fixed">
                            <td>@{{$index+1}}</td>
                            <td>@{{fixed.education}}</td>
                            <td>@{{fixed.medical}}</td>
                            <td>@{{fixed.tiffin}}</td>
                            <td>@{{fixed.washing}}</td>
                            <td>@{{fixed.transport}}</td>
                            <td>@{{fixed.gpf}}</td>
                            <td>@{{fixed.revenue}}</td>
                            <td>@{{fixed.scale_year}}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm " ng-click="pressUpdateBtnFixed(fixed)">Update</button>
                                <button type="button" class="btn btn-danger btn-sm" ng-click="pressDeleteBtnFixed(fixed)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" class="text-center">
                                <dir-pagination-controls max-size="3"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="fixed">
                                </dir-pagination-controls>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px; margin-top: 20px;">
            <h4 class="text-center ok">Monthly Deduction</h4>
            <div class="{{--col-md-8 col-md-offset-3--}} col-md-9 col-md-offset-3" >
                <form class="form-inline">
                    <div class="form-group" >
                        <div class="col-md-6 nopadding">
                        Search By:
                        <select class="form-control" name="select" ng-model="select" ng-change="clear()">
                            <option value="id">ID</option>
                            <option value="name">Name</option>
                        </select>
                        </div>
                        {{-- <input type="text" class="form-control" name="searchKey" ng-model="searchKey" ng-disabled="serachField" id="searchKey" placeholder="@{{ placeHolder }}"> --}}
                        <div class="col-md-6 nopadding">
                        <autocomplete ng-if="select=='id'" options="allValidEmployees" ng-model="searchTerm"
                                      place-holder="Type Employee ID"
                                      on-select="onSelect"
                                      display-property="emp_id"
                                      input-class="form-control"
                                      clear-input="false">
                        </autocomplete>
                        <autocomplete ng-if="select=='name'" options="allValidEmployees" ng-model="searchTerm"
                                      place-holder="Type Employee Name"
                                      on-select="onSelect"
                                      display-property="name"
                                      input-class="form-control"
                                      clear-input="false">
                        </autocomplete>
                        </div>

                    </div><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <img style="padding-left: 20px " ng-src="@{{ showEmployeeImg ? '/img/employees/'+showEmployeeImg : '/img/noImg.jpg'}}" height="80" width="100">
                </form>

            </div>
            <div class="col-md-10 col-md-offset-1" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-md-4">
                    <span ng-if="select=='id'"><b>Name:</b> @{{empName}}</span>
                    <span ng-if="select=='name'"><b>ID:</b> @{{empID}}</span>
                </div>
                <div class="col-md-4">
                    <b>Designation:</b> @{{empDesignation}}
                </div>
                <div class="col-md-4">
                    <b>Mobile Number:</b> @{{empMobileNumber}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-success" id="savingSuccessMonDeduction" ng-hide="!savingSuccessMonDeduction">@{{ savingSuccessMonDeduction }}</div>
                <div class="alert alert-danger" id="savingErrorMonDeduction" ng-hide="!savingErrorMonDeduction">@{{ savingErrorMonDeduction }}</div>
                <form name="MonthlyDeduction" id="MonthlyDedudction" novalidate>
                    <table>
                        <tr>

                            <th>Water<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="water" id="water" ng-model="water">
                                <span class="error" ng-show="MonthlyDeduction.water.$invalid && submittedMonDeduction">Water is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Generator<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="generator" id="generator" ng-model="generator">
                                <span class="error" ng-show="MonthlyDeduction.generator.$invalid && submittedMonDeduction">Generator is Required</span>
                            </td>

                            <th style="padding-left: 15px;">Electricity<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="electricity" id="electricity" ng-model="electricity">
                                <span class="error" ng-show="MonthlyDeduction.electricity.$invalid && submittedMonDeduction">Electricity is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>

                            <th>Due<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" class="form-control" name="previous_due" id="previous_due" ng-model="previous_due">
                                <span class="error" ng-show="MonthlyDeduction.previous_due.$invalid && submittedMonDeduction">Due is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Transport:</th>
                            <td>
                                <input type="number" class="form-control" name="transport_month" ng-model="transport_month" id="transport_month">
                                <span class="error" ng-show="MonthlyDeduction.transport_month.$invalid && submittedMonDeduction">Transport is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Month-Year<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="text" class="form-control datePicker" name="month_year" id="month_year" ng-model="month_year" required>
                                <span class="error" ng-show="MonthlyDeduction.month_year.$invalid && submittedMonDeduction">Month-Year is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="saveMonthlyDeduction()" ng-if="btnSaveMonthlyDeduction" >Save</button>
                                {{--ng-show="btnSaveMonthlyDeduction"--}}
                                <button type="button" class="btn btn-success center-block" ng-click="updateMonthlyDeduction()" ng-if="btnUpdateMonthlyDeduction">Update</button>
                                {{--ng-show="btnUpdateMonthlyDeduction"--}}
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
            <table class="table table-bordered" ng-show="monthlyDeductionTable">
                <caption><h4  class="text-center ok">Monthly Deduction</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Water</th>
                    <th>Generator</th>
                    <th>Electricity</th>
                    <th>Transport</th>
                    <th>Due</th>
                    <th>Month-Year</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr ng-style="{'background-color':(employee.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="employee in allVEmployeesMonthlyDeduction | orderBy:'employee.id':true | itemsPerPage:5" pagination-id="monthlyDeduction">
                        <td>@{{$index+1}}</td>
                        <td>@{{employee.water | numberFilter}}</td>
                        <td>@{{employee.generator | numberFilter}}</td>
                        <td>@{{employee.electricity | numberFilter}}</td>
                        <td>@{{employee.transport | numberFilter}}</td>
                        <td>@{{employee.previous_due | numberFilter}}</td>
                        <td>@{{employee.month_year | date : "MMMM yy"}}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm " ng-click="pressUpdateBtnMonthlyDeduction(employee)">Update</button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="pressDeleteBtnMonthlyDeduction(employee)">Delete</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="monthlyDeduction">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script type="text/javascript">
            $(function() {
                $('#month_year').datepicker( {
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