@extends('layouts.master')
@section('title', 'CNF Entry Form')
@section('style')
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/cnf/cnf-create.js')!!}
    {!!Html :: script('js/bootstrap-select.min.js')!!}

    {!!Html :: style('css/bootstrap-select.min.css')!!}
    <script type="text/javascript">

       $(function() {
            $('#register_date').datepicker( {
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
            $('#expired_date').datepicker( {
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
            $('#licence_date').datepicker( {
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
@endsection
@section('content')
        <div class="col-md-12 ng-cloak" ng-app="CnfCreateApp" ng-controller="CnfCreateController">
            <div class="col-md-10 col-md-offset-1" style="background-color: #dbd3ff; border-radius: 20px;">
                <h4 class="text-center ok">CNF Entry Form</h4>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="col-md-12">
                <form name="cnfCreateForm" id="cnfCreateForm" novalidate>
                    <table>
                        <tr>
                            <th>C&F Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="cnf_name" id="cnf_name" ng-model="cnf_name" required placeholder="Enter C&F Name">
                                <span class="error" ng-show="cnfCreateForm.cnf_name.$invalid && submitted">C&F Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Reg/Licence NO<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="ain_no" id="ain_no" ng-model="ain_no" required placeholder="Enter REG No">
                                <span class="error" ng-show="cnfCreateForm.ain_no.$invalid && submitted">Reg/Licence No is required</span>
                            </td>
                            <th style="padding-left: 15px;">Licence Date<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="licence_date" id="licence_date" ng-model="licence_date" required placeholder="Enter Licence Name">
                                <span class="error" ng-show="cnfCreateForm.licence_date.$invalid && submitted">Licence Date is required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Address<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="address" id="address" ng-model="address" required  placeholder="Enter Address No">
                                <span class="error" ng-show="cnfCreateForm.address.$error.required && submitted">Address is required</span>
                            </td>
                            <th style="padding-left: 15px;">Mobile:<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="mobile" id="mobile" ng-model="mobile" ng-pattern="/^\d{11}$/" placeholder="must be 11 digit" required>
                                <span class="error" ng-show="cnfCreateForm.mobile.$error.required && submitted">Mobile number is required</span>
                                <span class="error" ng-show="cnfCreateForm.mobile.$error.pattern && submitted">Invalid Mobile number</span>
                            </td>
                            <th style="padding-left: 15px;">Email<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="email" id="email" ng-model="email" required ng-pattern="/\S+@\S+\.\S+/" placeholder="Ex: name@domain.com">
                                <span class="error" ng-show="cnfCreateForm.email.$error.required && submitted">Email address is required</span>
                                <span class="error" ng-show="cnfCreateForm.email.$error.pattern && submitted">Invalid Email address</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Register Date<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="register_date" id="register_date" ng-model="register_date" required placeholder="Choose Register Date" ng-change="getExpireDate()">
                                <span class="error" ng-show="cnfCreateForm.register_date.$invalid && submitted">Register Date is required</span>
                            </td>
                            <th style="padding-left: 15px;">Validity<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="validity" id="validity" ng-model="validity" required placeholder="Enter Year" ng-change="getExpireDate()">
                                <span class="error" ng-show="cnfCreateForm.validity.$invalid && submitted">Validity is required</span>
                            </td>
                            <th style="padding-left: 15px;">Expired date<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="expired_date" id="expired_date" ng-model="expired_date" required placeholder="Enter Expire Date" ng-disabled="disableExpireDate">
                                <span class="error" ng-show="cnfCreateForm.expired_date.$invalid && submitted">Expired date is required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Licence Photo:</th>
                            <td style="width: 180px;">
                                <input type="file" id="licence_photo" class="form-control" file-model="licence_photo" accept="image/*" />
                                <span class="error" ng-show="licence_photo && submitted">@{{licence_photo_validation}}</span>
                            </td>
                            <th style="padding-left: 15px;">Owner Photo:</th>
                            <td style="width: 180px;">
                                <input type="file" id="owner_photo" class="form-control" file-model="owner_photo" accept="image/*" />
                                <span class="error" ng-show="owner_photo && submitted">@{{owner_photo_validation}}</span>
                            </td>
                            <th style="padding-left: 15px;">Owner NID:</th>
                            <td style="width: 180px;">
                                <input type="file" id="owner_nid_photo" name="owner_nid_photo" class="form-control" file-model="owner_nid_photo" accept="image/*"/>
                                <span class="error" ng-show="owner_nid_photo_validation && submitted">@{{owner_nid_photo_validation}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Bank Voucher Photo:</th>
                            <td style="width: 180px;">
                                <input type="file" id="bank_voucher_photo" class="form-control" file-model="bank_voucher_photo" accept="image/*" />
                                <span class="error" ng-show="bank_voucher_photo && submitted">@{{bank_voucher_photo_validation}}</span>
                            </td>
                            <th style="padding-left: 15px;">Shonchoypatro Photo:</th>
                            <td style="width: 180px;">
                                <input type="file" id="shonchoypatro_photo" class="form-control" file-model="shonchoypatro_photo" accept="image/*" />
                                <span class="error" ng-show="shonchoypatro_photo && submitted">@{{shonchoypatro_photo_validation}}</span>
                            </td>
                            <th style="padding-left: 15px;">Agreement Photo:</th>
                            <td style="width: 180px;">
                                <input type="file" id="agreement_photo" name="agreement_photo" class="form-control" file-model="agreement_photo" accept="image/*"/>
                                <span class="error" ng-show="agreement_photo_validation && submitted">@{{agreement_photo_validation}}</span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Assign Port<span class="mandatory">*</span>:</th>
                            <td style="width: 180px;">

                                <select title="No Port Selected"  style="width: 180px;" class="selectpicker"  name="port_id" ng-model="port_id"  required  multiple>
                                    @foreach($portList as $k=>$v)
                                        <option value="{{$v->id}}">{{$v->port_name}}</option>
                                    @endforeach
                                </select>
                                <span class="error" ng-show="cnfCreateForm.port_id.$invalid && submitted">Port is required</span>

                            </td>

                        </tr>

                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-if="btnSave" ng-disabled="uploadingTym"><span class="fa fa-file"></span>Save</button>
                                {{--ng-show="btnSave"--}}
                                <button type="button" class="btn btn-success center-block" ng-click="update()" ng-if="btnUpdate"><span class="fa fa-download"></span>Update</button>
                                {{--ng-show="btnUpdate"--}}
                                <span ng-if="dataLoading">
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

            <div class="clearfix"></div>
                <div class="col-md-12 table-responsive" style="padding: 10px;">
                    {{--<div class="alert alert-danger" ng-hide="!errorType">@{{ errorType }}</div> --}}
                    <table class="table table-bordered">
                    <caption><h4 class="text-center ok">CNF Details:</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
                        <thead>
                            <tr>
                                <th>S/L</th>
                                <th>CNF Name</th>
                                <th>AIN No</th>
                                <th>Licence Date</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Register Date</th>
                                <th>Validity(Year)</th>
                                <th>Duration</th>
                                <th>Expired date</th>
                                <th>Assign Port</th>
                                <th>Licence Photo</th>
                                <th>Owner Photo</th>
                                <th>Owner NID Photo</th>
                                <th>Bank Voucher Photo</th>
                                <th>ShonchoyParto Photo</th>
                                <th>Agreement Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                     <tbody>
                            <tr ng-style="{'background-color':(CNF.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="CNF in allCNF | orderBy:'CNF.id':true | itemsPerPage:5 | filter:searchText" total-items="total_count" current-page="currentPage">
                                <td>@{{ $index + serial }}</td>
                                <td>@{{CNF.cnf_name}}</td>
                                <td>@{{CNF.ain_no}}</td>
                                <td>@{{CNF.licence_date}}</td>
                                <td>@{{CNF.address}}</td>
                                <td>@{{CNF.mobile}}</td>
                                <td>@{{CNF.email}}</td>
                                <td>@{{CNF.register_date}}</td>
                                <td>@{{CNF.validity}}</td>
                                <td>
                                <div class="progress">
                                <div id="@{{ $index + serial }}" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:@{{ getProgressbarValue(CNF.diff_from_today,CNF.total_day_difference, 3, $index + serial)}} "{{-- getProgressbarValue(CNF.diff_from_today,CNF.total_day_difference, 4, $index + serial)" --}}>
                                  @{{getProgressbarValue(CNF.diff_from_today,CNF.total_day_difference, 5, $index + serial)}}
                                </div>
                                </div>
                                </td>
                                <td>@{{CNF.expired_date}}</td>
                                <td>@{{ CNF.port_name }}</td>
                                <td>
                                    <img ng-src="@{{ CNF.licence_photo ? '/img/cnf/'+CNF.licence_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <img ng-src="@{{ CNF.owner_photo ? '/img/cnf/owner_photo/'+CNF.owner_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <img ng-src="@{{ CNF.owner_nid_photo ? '/img/cnf/owner_nid_photo/'+CNF.owner_nid_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <img ng-src="@{{ CNF.bank_voucher_photo ? '/img/cnf/bank_voucher_photo/'+CNF.bank_voucher_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <img ng-src="@{{ CNF.shonchoypatro_photo ? '/img/cnf/shonchoypatro_photo/'+CNF.shonchoypatro_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <img ng-src="@{{ CNF.agreement_photo ? '/img/cnf/agreement_photo/'+CNF.agreement_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                                </td>
                                <td>
                                    <button style="width: 70px;" type="button" class="btn btn-success" ng-click="pressUpdateBtn(CNF)">Update</button>
                                    <button style="width: 70px;" type="button" class="btn btn-danger" ng-click="pressDeleteBtn(CNF)">Delete</button>
                                    {{-- <button style="width: 145px;" type="button" class="btn btn-primary" ng-click="">Change Password</button> --}}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="20" class="text-center">
                                    <dir-pagination-controls
                                            max-size="5"
                                            direction-links="true"
                                            boundary-links="true"
                                            on-page-change="getPageCount(newPageNumber)"

                                    >
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
@endsection