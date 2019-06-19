@extends('layouts.master')
@section('title','Create Custom Employee')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

    </style>
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/custom-employee/create-custom-employee.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}

    <script type="text/javascript">
        $(function() {
            $('.datePicker').datepicker( {
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="customEmployeeApp" ng-controller="customEmployeeCtrl">
        <div class="col-md-11" style="margin-left: 50px; background-color: #dbd3ff; border-radius: 20px;">
            <h4 class="text-center ok">Custom Employee Entry</h4>
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <form name="customEmployeeForm" id="customEmployeeForm" novalidate>
                    <table>

                        <tr>

                            <th>Employee Type<span class="mandatory">*</span>:</th>
                            <td>
                                <select class="form-control" ng-init="employee_type = 'head_office'" name="employee_type" ng-model="employee_type" id="employee_type" {{--ng-change="cngUserType(user_type)" --}}required>
                                    <option value="head_office">head_office</option>
                                    <option value="support">support</option>
                                    <option value="others">others</option>
                                </select>
                                <span class="error" ng-show="customEmployeeForm.employee_type.$invalid && submitted">Employee Type is Required</span>
                            </td>

                            <th style="padding-left: 15px;">Organization Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="organization_name" id="organization_name" ng-model="organization_name" placeholder="Organization Name" required>
                                <span class="error" ng-show="customEmployeeForm.organization_name.$invalid && submitted">Organization Name is Required</span>
                            </td>

                            <th style="padding-left: 15px;">Employee Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="name" id="name" ng-model="name" placeholder="Employee Name" required>
                                <span class="error" ng-show="customEmployeeForm.name.$invalid && submitted">Employee Name is Required</span>
                            </td>

                        </tr>

                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th >Designation<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="designation" id="designation" ng-model="designation"  placeholder="Designation" required="required">
                                <span class="error" ng-show="customEmployeeForm.designation.$invalid && submitted">Designation is Required</span>
                            </td>

                            <th style="padding-left: 15px;">Date of Birth<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control datePicker" type="text" name="date_of_birth" id="date_of_birth" ng-model="date_of_birth" placeholder="Date of Birth" required>
                                <span class="error" ng-show="customEmployeeForm.date_of_birth.$invalid && submitted">Date of Birth is Required</span>
                            </td>

                            <th style="padding-left: 15px;">National ID<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="national_id"  placeholder="Must be 10 to 20 digits" id="national_id" ng-model="national_id" required ng-pattern="/^\d{10,20}$/" >
                                <span class="error" ng-show="customEmployeeForm.national_id.$error.required && submitted">National ID is Required</span>
                                <span class="error" ng-show="customEmployeeForm.national_id.$error.pattern && submitted">National ID is Invalid</span>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>

                            <th>Mobile<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="mobile" id="mobile" ng-model="mobile"   required ng-pattern="/^\d{11}$/" placeholder="Must be 11 digit">
                                <span class="error" ng-show="customEmployeeForm.mobile.$error.required && submitted">Mobile Number is Required</span>
                                <span class="error" ng-show="customEmployeeForm.mobile.$error.pattern && submitted">Mobile Number is invalid</span>
                            </td>

                            <th style="padding-left: 15px;">Phone No:</th>
                            <td>
                                <input class="form-control" type="text" name="phone_no" id="phone_no" ng-model="phone_no"  placeholder="Phone No"  {{--required--}} ng-pattern="/^\d{9,10}$/" placeholder="Must be 9 or 10 digit">
                                {{-- <span class="error" ng-show="phone_no_required">@{{phone_no_required}}</span> --}}
                                <span class="error" ng-show="customEmployeeForm.phone_no.$error.pattern && submitted">Phone Number is Invalid</span>
                            </td>
                            <th style="padding-left: 15px;">Email<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="email" id="email" ng-model="email"   required ng-pattern="/\S+@\S+\.\S+/" placeholder="Ex: name@domain.com">
                                <span class="error" ng-show="customEmployeeForm.email.$error.required && submitted">Email is Required</span>
                                <span class="error" ng-show="customEmployeeForm.email.$error.pattern && submitted">Invalid Email</span>
                            </td>


                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>

                            <th >Address<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="address" id="address" ng-model="address" placeholder="Address" required>
                                <span class="error" ng-show="customEmployeeForm.address.$invalid && submitted">Address is Required</span>
                            </td>

                            <th  style="padding-left: 15px;">Photo:</th>
                            <td style="width: 220px;">
                                <input type="file" id="photo" class="form-control" file-model="photo" accept="image/*"/>
                                <span class="error" ng-show="photo_validation">@{{photo_validation}}</span>
                            </td>



                            <th style="padding-left: 15px;">National ID Photo<span class="mandatory">*</span>:</th>
                            <td style="width: 220px;">
                                <input type="file" id="national_id_photo" class="form-control" file-model="national_id_photo" accept="image/*"/>
                                <span class="error" ng-show="national_id_photo_error">@{{national_id_photo_error}}</span>

                            </td>


                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-if="btnSave"><span class="fa fa-file"></span> Save</button>
                                <button type="button" class="btn btn-success center-block" ng-click="update()" ng-if="btnUpdate"><span class="fa fa-download"></span> Update</button>
                                <span ng-if="dataLoading">
                                <img src="img/dataLoader.gif" width="250" height="15" />
                                <br />Please wait!
                            </span>
                            <td>
                        </tr>

                    </table>
                </form>
                <br>
            </div>
        </div>
        <div class="col-md-12  table-responsive">
            <table class="table table-bordered table-responsive" {{--ng-show="employeeByOrgShow"--}}>
                <caption><h4 class="text-center ok">Custom Employee Details:</h4>
                    <div class="col-md-6 col-sm-6 col-xs-3 form-inline">
                        <div class="form-group">
                            <label for="user_type_search">
                                Employee Type:
                            </label>
                            <select class="form-control" ng-init="emp_type_search = 'head_office'" name="emp_type_search" ng-model="emp_type_search" id="emp_type_search" ng-change="allCustomEmployeeList(emp_type_search)">
                                <option value="head_office">head_office</option>
                                <option value="support">support</option>
                                <option value="others">others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="searchText">Search:</label>
                            <input class="form-control" ng-model="searchText">
                        </div>
                    </div>

                </caption>

                <thead>
                <tr>
                    {{--<th>Organization Name</th>--}}
                    <th>Employee Type</th>
                    <th>Organization Name</th>
                    <th>Employee Name</th>
                    <th>Designation</th>
                    <th>Date of Birth</th>
                    <th>National ID</th>
                    <th>Mobile</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Photo</th>
                    <th>NID Photo</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(customEmployeeData.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="customEmployeeData in allCustomEmployee | filter:searchText | orderBy:'customEmployeeData.id' | itemsPerPage:5">
                    {{--<td>@{{employeeByOrg.org_name}}</td>--}}
                    <td>@{{customEmployeeData.employee_type}}</td>
                    <td>@{{customEmployeeData.organization}}</td>
                    <td>@{{customEmployeeData.name}}</td>
                    <td>@{{customEmployeeData.designation}}</td>
                    <td>@{{customEmployeeData.date_of_birth}}</td>
                    <td>@{{customEmployeeData.national_id}}</td>
                    <td>@{{customEmployeeData.mobile}}</td>
                    <td>@{{customEmployeeData.phone_no}}</td>
                    <td>@{{customEmployeeData.email}}</td>
                    <td>@{{customEmployeeData.address}}</td>
                    <td>
                        <img id="photo" ng-src="@{{ customEmployeeData.photo ? '/img/custom-employees/'+customEmployeeData.photo : '/img/noImg.jpg'}}" height="100" width="100">

                    </td>
                    <td>
                        <img id="nid_photo" ng-src="@{{ customEmployeeData.nid_photo ? '/img/custom-employees/nid/'+customEmployeeData.nid_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                    </td>
                    <td>
                        <button style="width: 80px;" type="button" class="btn btn-success" ng-click="pressUpdateBtn(customEmployeeData)">Update</button>
                        <button style="width: 80px;" type="button" class="btn btn-danger" ng-click="pressDeleteBtn(customEmployeeData)">Delete</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="13" class="text-center">
                        <dir-pagination-controls max-size="3"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection