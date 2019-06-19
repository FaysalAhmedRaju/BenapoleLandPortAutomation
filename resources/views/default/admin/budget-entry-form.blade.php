@extends('layouts.master')
@section('title','Budget Entry Form')
@section('style')
    {{-- <style type="text/css">
         [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
             display: none !important;
         }

         .ui-datepicker-calendar {
             display: none;
         }
     </style>--}}
@endsection
@section('script')
    {!!Html::script('js/customizedAngular/budgetEntry.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="budgetAddApp" ng-controller="budgetAddController">

        <div class="col-md-8 col-md-offset-2" style="background-color: #f8f9f9; border-radius: 20px;">
            <h4 class="text-center ok">Budget Entry</h4>

            <div class="alert alert-success" id="success" ng-show="success">@{{ success }}</div>
            <div class="alert alert-danger" id="error" ng-show="error">@{{ error }}</div>


            <form name="budgetAddForm" id="budgetAddForm" novalidate>
                <br>
                <table>
                    <tr>
                        <th> Budget Type<span class="mandatory">*</span>:</th>
                        <td>
                            <select class="form-control" ng-init="monthly_yearly_flag='0'" required="required" id="year" name="year"
                                       ng-model="monthly_yearly_flag">
                                <option value="0">Monthly</option>
                                <option value="1">Yearly</option>
                            </select>
                            <span class="error" ng-show="!monthly_yearly_flag && submitted">Monthly/Yearly is required</span>

                        </td>

                        <th>Fiscal Year:<span class="mandatory"> *</span>:</th>
                        <td>
                            <select class="form-control" ng-init="fiscal_year='2017-2018'" required="required" id="year" name="year"
                                      ng-model="fiscal_year">
                                <option value="2016-2017">2016-17</option>
                                <option value="2017-2018">2017-18</option>
                                <option value="2018-2019">2018-19</option>
                                <option value="2019-2020">2019-20</option>
                            </select>
                            <span class="error" ng-show="!fiscal_year && submitted">
                                        Select Budget Type
                             </span>
                           </td>


                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>

                    <tr>

                        <th>SubHead:</th>
                        <td>
                            <input id="subhead_name" type="text" class="form-control" ng-model="subhead_name" placeholder="Type With Subhead">
                            <span class="error" ng-if="!subhead_name && submitted">SubHead is required</span>
                            <p class="error" id="subhead-not-found"></p>
                        </td>



                        <th style="padding-left: 20px;">Amount<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="number" required="required" class="form-control"
                                   placeholder="Type Budget Amount" ng-model="amount">
                            <span class="error" ng-show="!amount && submitted">Amount is required</span>

                        </td>
                    </tr>



                    <tr>
                        <td ></td>
                        <td colspan="3" class="text-center">
                            <br>
                            <button type="button" ng-click="saveBudget(budgetAddForm)" ng-if="!updateBtn"
                                    class="btn btn-primary center-block"><span class="fa fa-file"></span> Save
                            </button>

                            <button type="button" ng-click="saveBudget(budgetAddForm)" ng-if="updateBtn"
                                    class="btn btn-success center-block"><span class="fa fa-download"></span> Update
                            </button>

                        </td>

                    </tr>


                </table>
                <br>
            </form>

        </div>


        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center" colspan="5">Budget List</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Fiscal</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="item in allBudgetData | itemsPerPage:100" pagination-id="budgetentry">
                    <td class="text-capitalize">@{{item.sub_head_name}}</td>
                    <td>@{{item.monthly_yearly_flag==1?'Yearly':'Monthly'}}</td>
                    <td>@{{item.amount}}</td>
                    <td>@{{ item.fiscal_year }}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" ng-click="editBudgetData(item)">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" ng-click="deleteBudgetData(item)">
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="budgetentry">
                        </dir-pagination-controls>
                    </td>
                </tr>
                 <tr>
                    <td colspan="5" class="text-center">

                    </td>
                </tr>
                </tfoot>
            </table>

        </div>


    </div>
    <script>
        $(function () {
            $('#tariff_year').datepicker({
                changeMonth: false,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                onClose: function (dateText, inst) {
                    function isDonePressed() {
                        return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                    }

                    if (isDonePressed()) {
//                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, 1)).trigger('input');
                        //console.log(a);
                    }
                }
            });
        });
    </script>
@endsection