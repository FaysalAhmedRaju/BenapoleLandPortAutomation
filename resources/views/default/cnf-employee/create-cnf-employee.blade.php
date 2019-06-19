@extends('layouts.master')
@section('title','Create C&F Employee')
@section('style')
	<style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

    </style>
@endsection
@section('script')
	{!!Html :: script('js/customizedAngular/cnf-employee/cnf-employee.js')!!}
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
	<div class="col-md-12 ng-cloak" ng-app="cnfEmployeeApp" ng-controller="cnfEmployeeCtrl">
		<div class="col-md-11" style="margin-left: 50px; background-color: #dbd3ff; border-radius: 20px;">
			<h4 class="text-center ok">C&F Employee Entry</h4>
			<div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <form name="cnfEmployeeForm" id="cnfEmployeeForm" novalidate>
            	<table>

            		<tr>
                        <th>C&F Name<span class="mandatory">*</span>:</th>
                        <td>

                            <input type="text" ng-model="m_vat_id" name="m_vat_id" id="m_vat_id"
                                   class="form-control"  placeholder="C&F Name" required>
                            <span class="error" ng-show="cnfEmployeeForm.m_vat_id.$invalid && submitted">C&F Name is Required</span>
                        </td>

                        <th style="padding-left: 15px;">Employee Name<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="name" id="name" ng-model="name" placeholder="Employee Name" required>
                            <span class="error" ng-show="cnfEmployeeForm.name.$invalid && submitted">Employee Name is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Address<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="address" id="address" ng-model="address" placeholder="Address" required>
                            <span class="error" ng-show="cnfEmployeeForm.address.$invalid && submitted">Address is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>

                        <th >National ID<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="national_id"  placeholder="Must be 10 to 20 digits" id="national_id" ng-model="national_id" required ng-pattern="/^\d{10,20}$/" >
                            {{--<span class="error" ng-show="national_id_required"> @{{ national_id_required }}</span>--}}
                            <span class="error" ng-show="cnfEmployeeForm.national_id.$error.required && submitted">National ID is Required</span>
                            <span class="error" ng-show="cnfEmployeeForm.national_id.$error.pattern && submitted">National ID is Invalid</span>
                        </td>
                        <th style="padding-left: 15px;">Date of Birth<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control datePicker" type="text" name="date_of_birth" id="date_of_birth" ng-model="date_of_birth" placeholder="Date of Birth" required>
                            <span class="error" ng-show="cnfEmployeeForm.date_of_birth.$invalid && submitted">Date of Birth is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Designation<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="designation" id="designation" ng-model="designation"  placeholder="Designation" required="required">
                            {{--<span class="error" ng-show="designation_required">Designation is Required</span>--}}
                            <span class="error" ng-show="cnfEmployeeForm.designation.$invalid && submitted">Designation is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>

                        <th >Phone No:</th>
                        <td>
                            <input class="form-control" type="text" name="phone_no" id="phone_no" ng-model="phone_no"  placeholder="Phone No"  {{--required--}} ng-pattern="/^\d{9,10}$/" placeholder="Must be 9 or 10 digit">
                            {{-- <span class="error" ng-show="phone_no_required">@{{phone_no_required}}</span> --}}
                            <span class="error" ng-show="cnfEmployeeForm.phone_no.$error.pattern && submitted">Phone Number is Invalid</span>
                        </td>
                        <th style="padding-left: 15px;">Email<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="email" id="email" ng-model="email"   required ng-pattern="/\S+@\S+\.\S+/" placeholder="Ex: name@domain.com">
                            {{--<span class="error" ng-show="email_required">@{{email_required}}</span>--}}
                            <span class="error" ng-show="cnfEmployeeForm.email.$error.required && submitted">Email is Required</span>
                            <span class="error" ng-show="cnfEmployeeForm.email.$error.pattern && submitted">Invalid Email</span>
                        </td>
                        <th style="padding-left: 15px;">Mobile<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="mobile" id="mobile" ng-model="mobile"   required ng-pattern="/^\d{11}$/" placeholder="Must be 11 digit">
                            {{--<span class="error" ng-show="mobile_required">@{{mobile_required}}</span>--}}
                            <span class="error" ng-show="cnfEmployeeForm.mobile.$error.required && submitted">Mobile Number is Required</span>
                            <span class="error" ng-show="cnfEmployeeForm.mobile.$error.pattern && submitted">Mobile Number is invalid</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>

                        <th >Photo:</th>
                        <td style="width: 220px;">
                            {{-- <input class="form-control" type="file" ng-disabled="!username" name="file" id="file" onchange="angular.element(this).scope().uploadUserPicture(this.files)"/>
                            <span class="error" ng-show="uploading">@{{$scope.uploading}}</span> --}}
                            <input type="file" id="photo" class="form-control" file-model="photo" accept="image/*"/>
                            <span class="error" ng-show="photo_validation">@{{photo_validation}}</span>
                        </td>



                        <th style="padding-left: 15px;">National ID Photo<span class="mandatory">*</span>:</th>
                        <td style="width: 220px;">
                            <input type="file" id="national_id_photo" class="form-control" file-model="national_id_photo" accept="image/*"/>
                            {{--<span class="error" ng-show="national_id_photo_validation">NID is Required</span>--}}
                            <span class="error" ng-show="national_id_photo_error">@{{national_id_photo_error}}</span>
                            {{--<span class="error" ng-show="national_id_photo_validation">@{{national_id_photo_error}}</span>--}}
                        </td>

                        {{-- <td>
                            <img ng-src="@{{filepreview}}" class="img-responsive" ng-show="filepreview"/>
                        </td> --}}
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-center">
                            <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-if="btnSave"><span class="fa fa-file"></span> Save</button>
                            {{--ng-show="btnSave"--}}
                            <button type="button" class="btn btn-success center-block" ng-click="update()" ng-if="btnUpdate"><span class="fa fa-download"></span> Update</button>
                            {{--ng-show="btnUpdate"--}}
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
            <table class="table table-bordered table-responsive" ng-show="employeeByOrgShow">
                <caption><h4 class="text-center ok">C&F Employee Details: @{{orgNameForShow}}</h4></caption>
                <thead>
                    <tr>
                        {{--<th>Organization Name</th>--}} 
                        <th>C&F Name</th>
                        <th>Employee Name</th>
                        <th>Address</th>
                        <th>National ID</th>
                        <th>Date of Birth</th>
                        <th>Designation</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Photo</th>
                        <th>NID Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
             <tbody>
                    <tr ng-style="{'background-color':(employeeByOrg.emp_id == selectedStyle?'#dbd3ff':'')}" dir-paginate="employeeByOrg in allEmployeeByOrg | orderBy:'employeeByOrg.emp_id' | itemsPerPage:5">
                        {{--<td>@{{employeeByOrg.org_name}}</td>--}}
                        <td>@{{employeeByOrg.cnf_name}}</td>
                        <td>@{{employeeByOrg.name}}</td>
                        <td>@{{employeeByOrg.cnf_address}}</td>
                        <td>@{{employeeByOrg.national_id}}</td>
                        <td>@{{employeeByOrg.date_of_birth}}</td>
                        <td>@{{employeeByOrg.designation}}</td>
                        <td>@{{employeeByOrg.phone_no}}</td>
                        <td>@{{employeeByOrg.cnf_email}}</td>
                        <td>@{{employeeByOrg.cnf_mobile}}</td>
                        <td>
                            <img id="photo" ng-src="@{{ employeeByOrg.photo ? '/img/cnf-employees/'+employeeByOrg.photo : '/img/noImg.jpg'}}" height="100" width="100">

                        </td>
                        <td>
                            <img id="nid_photo" ng-src="@{{ employeeByOrg.nid_photo ? '/img/cnf-employees/nid/'+employeeByOrg.nid_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                        </td>
                        <td>
                            <button style="width: 80px;" type="button" class="btn btn-success" ng-click="pressUpdateBtn(employeeByOrg)">Update</button>
                            <button style="width: 80px;" type="button" class="btn btn-danger" ng-click="pressDeleteBtn(employeeByOrg)">Delete</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12" class="text-center">
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
