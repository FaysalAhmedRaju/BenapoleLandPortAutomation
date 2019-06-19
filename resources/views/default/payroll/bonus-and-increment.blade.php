@extends('layouts.master')
@section('title','Bonus And Increment')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
@endsection
@section('script')
    {!!Html::script('js/customizedAngular/payroll/bonus-increment.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="BonusAndIncrementApp" ng-controller="BonusAndIncrementController">

        <div class="col-md-12" id="head">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Bonus</h4>
                <div class="alert alert-success" id="SuccessBonus" ng-hide="!SuccessBonus">@{{ SuccessBonus }}</div>
                <div class="alert alert-danger" id="bonusdError" ng-hide="!bonusdError">@{{ bonusdError }}</div>
                <div class="alert alert-success" id="SuccessIncreaseUpdate" ng-hide="!SuccessIncreaseUpdate">@{{ SuccessIncreaseUpdate }}</div>
                <div class="alert alert-danger" id="ErrorIncreaseUpdate" ng-hide="!ErrorIncreaseUpdate">@{{ ErrorIncreaseUpdate }}</div>
                <div class="col-md-8 col-md-offset-2">
                    <form  name="BonusForm" id="BonusForm" novalidate>
                        <table>
                            <tr>
                                <th>Employee<span class="mandatory">*</span>:</th>
                                <td>
                                    <select  class="form-control" required  name ="Employee_bonus" ng-model="Employee_bonus" id="Employee_bonus" ng-options="employ.id as employ.emp_id+'-'+employ.name for employ in getEmployeesInfoDataForBonus ">
                                        <option value="" selected="selected">Select Employee</option>
                                    </select>
                                    <span class="error" ng-show="BonusForm.Employee_bonus.$invalid && submittedFixedBonus">Employee is required</span>

                                </td>

                                <th>Type<span class="mandatory">*</span>:</th>
                                <td>
                                    <input class="form-control" required type="text" name="type_name" ng-model="type_name" id="type_name" placeholder="Type">
                                    <span class="error" ng-show="BonusForm.type_name.$invalid && submittedFixedBonus">Type is required</span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <th>Date<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="text" required class="form-control datePicker" name="bonus_data" id="bonus_data" ng-model="bonus_data">
                                    <span class="error" ng-show="BonusForm.bonus_data.$invalid && submittedFixedBonus">Date is required</span>
                                </td>

                                <th>Amount<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" required ng-model="Amount_salary" name="Amount_salary" id="Amount_salary" class="form-control input-sm" placeholder="Amount">
                                    <span class="error" ng-show="BonusForm.Amount_salary.$invalid && submittedFixedBonus">Amount is required</span>
                                </td>

                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>


                            <tr>
                                <td>
                                <td colspan="0"></td>
                                <td colspan="1" class="text-center">
                                    <br>
                                    <button type="button" ng-click="SaveBonus()" ng-if="SaveBonusBtn" class="btn btn-primary btn-block center-block">Save</button>

                                    <button type="button" ng-click="updateBonus(value)" ng-if="SaveUpdateBtn" class="btn btn-primary btn-block center-block">Update</button>

                                </td>
                                <td colspan="2"></td>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-bordered" ng-show="BonusTable">
                    <caption><h4 class="text-center ok">Bonus List</h4></caption>
                    <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="value in allBonusData | orderBy:'head.id' | itemsPerPage:5" pagination-id="head">
                        <td>@{{value.emp_id}}</td>
                        <td>@{{value.name}}</td>
                        <td>@{{value.type}}</td>
                        <td>@{{value.amount}}</td>
                        <td>@{{value.date | date : "MMMM yy"}}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" ng-click="editBonous(value)">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="deleteBonus(value)"  >
                                {{--data-target="#deleteDegnationOnlyModal"  data-toggle="modal"--}}
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="head">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>

        </div>


    </div>
    <script>
        $(function() {
            $('#bonus_data').datepicker( {
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
        $(function() {
            $('#increment_date').datepicker( {
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
