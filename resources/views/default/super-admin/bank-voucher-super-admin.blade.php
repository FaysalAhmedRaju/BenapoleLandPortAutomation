@extends('layouts.master')
@section('title', 'Welcome Super Admin User')

@section('script')

    {!!Html::script('js/customizedAngular/bankVoucher.js')!!}

@endsection

@section('content')

    <div class="col-md-12 text-center" ng-app="bankVoucherApp" ng-controller="bankVoucherCtrl" ng-cloak>

        <div class="col-md-12" style="padding-bottom: 30px;">

            <div class="col-md-6">


                {{--<form action="{{ url('DateWiseVoucherPDFReport') }}" target="_blank" method="POST" class="form-inline">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control datePicker" name="from_date" id="from_date"--}}
                               {{--placeholder="Select Date" ng-model="from_date">--}}
                    {{--</div>--}}
                    {{--<button type="submit" class="btn btn-primary" ng-disabled="!from_date">Date Wise Voucher Report--}}
                    {{--</button>--}}
                {{--</form>--}}

            </div>




            <div class="col-md-6">

                <a href="/super-admin/bank-voucher/report/voucher-report/@{{ voucherNo }}" target="_blank">
                    <button ng-disabled="!voucherNo" type="button" class="btn btn-primary">
                        <span class="fa fa-calendar-o"></span> Voucher Report
                    </button>
                </a>



            </div>

        </div>




        <div class="col-md-12 ">

            <form name="vSearchForm" class="form-inline" ng-submit="getVoucherDetails(voucherNo)">
                <div class="form-group">
                    <input type="text" name="voucherNo" ng-model="voucherNo" class="form-control"
                           ng-keydown="keyBoard($event)" ng-model-options="{allowInvalid: true}"
                           ng-pattern='/^([0-9]{1,10})[\/]{1}[0-9]{2}$/'
                           placeholder="Search By Voucher No.">

                    <br>
                    <span class="error" ng-show='vSearchForm.voucherNo.$error.pattern'>
                                Input like: 26/17
                            </span>
                    <span class="ok">@{{ searchFound }}</span>
                    <span class="error">@{{ searchNotFound }}</span>


                    <br>
                    <span ng-if="voucherSearching" style="color:green; text-align:center; font-size:15px">
                                        <img src="img/dataLoader.gif" width="200" height="14"/>
                                        <br/> Please wait!
                </span>
                </div>


            </form>
        </div>






        <div class="col-md-12" ng-cloak> {{--Expenditure entry and Data  Div--}}

            <div class="col-md-8 col-md-offset-2" style="padding: 0 ;background-color: #f8f9f9; border-radius: 20px;">

                <form name="expenEntryForm" id="expenEntryForm" novalidate>
                    <table id="" style="width: 100%">
                        <tr>
                            <td class="text-center" colspan="6">
                                <h4 class="ok"> Bank Voucher(Credit) Entry Form</h4>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Voucher No<span class="mandatory">*</span>:</th>
                            <td>
                                <input  type="text" {{--ng-disabled="updateBtnExpn"--}} ng-model="vouchar_no"
                                        ng-pattern='/^([0-9]{1,10})[\/]{1}[0-9]{2}$/'
                                        required="required" class="form-control input-sm" name="vouchar_no"
                                        placeholder="Voucher No"/>
                                <span ng-show="submittedExpen && !vouchar_no" class="error">Please Input Voucher No</span>

                                <span class="error" ng-show='expenEntryForm.vouchar_no.$error.pattern'>
                                Input like: 256/17
                            </span>
                            </td>
                            <th>&nbsp;Voucher Date<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="text" ng-model="vouchar_date" required="required" name="vouchar_date"
                                       id="manifest_date" class="form-control datePicker input-sm"
                                       placeholder="Voucher date">

                                <span ng-show="submittedExpen && !vouchar_date" class="error">Select a date</span>
                            </td>

                        </tr>

                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>

                        <tr>
                            <th>Income:<span class="mandatory">*</span>:</th>
                            <td>
                                <autocomplete options="allExpenditureSubHeadData" ng-model="subHeadSearch"
                                              required="required"
                                              place-holder="Type Income"
                                              on-select="onSelectSubHead"
                                              display-property="acc_sub_head"
                                              input-class="form-control input-sm"
                                              clear-input="false">
                                </autocomplete>

                                <span ng-show="submittedExpen && !subHeadSearch"
                                      class="error">Please Search First</span>
                            </td>
                            <th>&nbsp;Amount<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" required="required" class="form-control input-sm" ng-model="amount"
                                       placeholder="Type Amount">

                                <span ng-show="submittedExpen && !amount" class="error">Please Input Amount</span>
                            </td>

                        </tr>


                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>

                    <tr>
                        <th>Organization Name:<span class="mandatory">*</span>:</th>
                        <td>
                            <input  type="text"  ng-model="organization_name"

                                    required="required" class="form-control input-sm" name="organization_name"
                                    placeholder="Organization Name"/>
                            <span ng-show="submittedExpen && !organization_name" class="error">Please Input Organization Name</span>


                        </td>

                        <th>&nbsp;Date:</th>
                        <td>
                            <input type="text" ng-model="in_ex_date" required="required" name="in_ex_date"
                                   id="in_ex_date" class="form-control datePicker input-sm"
                                   placeholder="Date">
                        </td>

                    </tr>
                   <tr>
                            <td colspan="4">&nbsp;</td>
                   </tr>
                        <tr>
                            <th>Cheque No:<span class="mandatory">*</span>:</th>
                            <td>
                                <input  type="text"  ng-model="cheque_no"

                                        required="required" class="form-control input-sm" name="cheque_no"
                                        placeholder="Cheque No"/>
                                <span ng-show="submittedExpen && !cheque_no" class="error">Please Input Cheque No</span>


                            </td>
                            <th>Comment:</th>
                            <td>
                                <input  type="text"  ng-model="comment"

                                         class="form-control input-sm" name="comment"
                                        placeholder="Comment"/>
                            </td>

                        </tr>
                        <tr>

                            <td colspan="4" class="text-center">
                                <div class="col-md-4 col-md-offset-4">
                                    <br>
                                    <button type="button" ng-click="saveExpenditure(expenEntryForm)"
                                            ng-if="!updateBtnExpn"
                                            class="btn btn-primary center-block">Save
                                    </button>
                                    {{--ng-hide="updateBtnExpn"--}}
                                    <button type="button" ng-click="updateExpense(expenEntryForm)" ng-if="updateBtnExpn"
                                            class="btn btn-primary center-block">Update
                                    </button>
                                    {{--ng-show="updateBtnExpn"--}}
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>


                        <tr>

                            <td colspan="4" class="text-center">

                                <div class="text-center" ng-if="expenSaving">
                                    <span style="color:green; text-align:center; font-size:15px">
                                        <img src="img/dataLoader.gif" width="200" height="14"/>
                                        <br/> Please wait!
                                    </span>
                                </div>

                                <div id="expenSuccess" class="col-md-6 col-md-offset-3 alert alert-success"
                                     ng-show="expenSuccessMsg">
                                    Successfully @{{ expenSuccessMsgTxt }}!
                                </div>

                                <div id="expenError" class="col-md-6 col-md-offset-3 alert alert-warning"
                                     ng-show="expenErrorMsg">
                                    @{{ expenErrorMsgTxt }}!
                                </div>
                            </td>

                        </tr>

                    </table>
                </form>


            </div>

            <div class="col-md-12 table-responsive">
                <br><br>
                <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                    <thead>

                    <tr ng-if="expenDataLoading">
                        <td colspan="6" class="text-center">
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="img/dataLoader.gif" width="200" height="14"/>
                            <br/> Loading...!
                        </span>
                        </td>
                    </tr>

                    <tr>

                        <th class="text-center">S/L</th>
                        <th class="text-center">Voucher No.</th>
                        <th class="text-center">Expenditure Name</th>
                        <th class="text-center">Amount (TK.)</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>

                    </tr>
                    </thead>

                    <tbody>

                    <tr ng-show="noExpenditureDataFound">
                        <td colspan="6">
                            <p class="error"> @{{ noExpenditureDataFoundTxt }}</p>
                        </td>
                    </tr>

                    <tr ng-class="{selectedRow : item.ex_id === idSelectedRow}"
                        dir-paginate="item in allExpendituresData|orderBy:'id':true| itemsPerPage:10"
                        pagination-id="expense">

                        <td>@{{$index+1}}</td>
                        <td>@{{item.bank_vouchar_no}}</td>
                        <td>@{{item.acc_sub_head}}</td>
                        <td>@{{item.amount |number:2}}</td>

                        <td>@{{item.created_at|dateShort:'mediumDate'}}</td>

                        <td>

                            <a class="btn btn-primary btn-xs" ng-click="editExpense(item)">Edit</a>
                            <a class="btn btn-danger btn-xs" ng-click="deleteExpenseConfirm(item)"
                               data-target="#deleteExpenseConfirm" data-toggle="modal">Delete</a>

                        </td>

                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center">

                            <dir-pagination-controls max-size="5" pagination-id="expense"
                                                     direction-links="true"
                                                     boundary-links="true">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    <tr ng-if="loadingerror">
                        <td colspan="6">
                            <div class="alert alert-danger">
                                <p id="errorLoadData" style="color:green; text-align:center; font-size:20px"></p>
                                Error! The leave data was not loaded. <a href="" ng-click></a>
                            </div>
                        </td>
                    </tr>
                    </tfoot>


                </table>
            </div>


        </div>

        {{--=============deleteVoucherConfirmModal--=============================================--}}

        <div class="modal fade text-center" style="left:0px; " id="deleteExpenseConfirm" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="text-muted"><b class="fa fa-check-circle-o"> Remember: </b> Once Delete, can't be
                            regained again!</h4>

                    </div>
                    <div class="modal-body">

                        <h4 class="modal-title text-center">Sure to Delete <b>@{{ acc_sub_head_delete }}</b> From
                            Voucher No <b> @{{ vouchar_no_delete }} </b>?</h4>

                        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">No</button>
                        <a href="" class="btn btn-danger center-block pull-left" ng-click="deleteExpenditure()">Yes</a>

                        <br><br>

                    </div>
                    <div class="modal-footer">
                        <div class="text-center" ng-if="expenDeleting">
                     <span style="color:green; text-align:center; font-size:15px">
                                            <img src="img/dataLoader.gif" width="200" height="14"/>
                                            <br/> Wait...!
                                        </span>
                        </div>

                        <div id="deleteSuccessExpense" class="alert alert-success text-center"
                             ng-show="deleteSuccessExpenseMsg">
                            Successfully @{{ deleteSuccessExpenseMsgTxt }}!
                        </div>

                        <div id="deleteErrorExpense" class="alert alert-warning text-center"
                             ng-show="deleteErrorExpenseMsg">
                            <span>Something Went Wrong!</span>
                        </div>

                        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        {{--=============deleteVoucherConfirmModal-- END=============================================--}}
    </div>
@endsection