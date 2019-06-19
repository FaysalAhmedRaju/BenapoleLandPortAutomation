@extends('layouts.master')
@section('title','Employee Details')
@section('style')


	<style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')

	{!! Html :: script('js/customizedAngular/payroll/employee-details.js') !!}
    {!! Html :: script('js/bootbox.min.js')!!}

	<script type="text/javascript">
		$(function() {
	        $('#date_join').datepicker( {
	            changeMonth: true,
	            changeYear: true,
	            yearRange: "-100:+10",
	            dateFormat: 'yy-mm-dd'
	        });
            $('#date_transfer').datepicker( {
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
	        $('#date_of_birth').datepicker( {
	            changeMonth: true,
	            changeYear: true,
	            yearRange: "-100:+10",
	            dateFormat: 'yy-mm-dd'
	        });
    	});

	</script>
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="EmployeeDetailsApp" ng-controller="EmployeeDetailsController">   
		<div class="col-md-11" style="margin-left: 50px; background-color: #f8f9f9; border-radius: 20px;">	<span class="label label-warning" style="font-size: 12px;">You are inserting employee for {{ Session::get('PORT_NAME') }}</span><h4 class="text-center ok">Employee Entry</h4>
			<div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <form name="EmployeeEntry" id="EmployeeEntry" novalidate>
                <table>
            		<tr>
                        <th>Name<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="name" id="name" ng-model="name" required placeholder="Enter Name">
                            <span class="error" ng-show="EmployeeEntry.name.$invalid && submitted">Name is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Father Name<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="father_name" id="father_name" ng-model="father_name" required placeholder="Enter Father Name">
                            <span class="error" ng-show="EmployeeEntry.father_name.$invalid && submitted">Father Name is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Mother Name<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="mother_name" id="mother_name" ng-model="mother_name" required placeholder="Enter Mother Name">
                            <span class="error" ng-show="EmployeeEntry.mother_name.$invalid && submitted">Mother Name is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Spouse Name:</th>
                        <td>
                            <input class="form-control" type="text" name="spouse_name" id="spouse_name" ng-model="spouse_name" placeholder="Enter Spouse Name">
                            {{-- <span class="error" ng-show="EmployeeEntry.mobile.$error.required && submitted">Mobile Number is Required</span>
                            <span class="error" ng-show="EmployeeEntry.mobile.$error.pattern && submitted">Mobile Number is invalid</span> --}}
                        </td>
                        <th style="padding-left: 15px;">Mobile<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="mobile" id="mobile" ng-model="mobile" required ng-pattern="/^\d{11}$/" placeholder="Must be 11 digit">
                            <span class="error" ng-show="EmployeeEntry.mobile.$error.required && submitted">Mobile Number is Required</span>
                            <span class="error" ng-show="EmployeeEntry.mobile.$error.pattern && submitted">Mobile Number is invalid</span>
                        </td>
                        <th style="padding-left: 15px;">Telephone:</th>
                        <td>
                            <input class="form-control" type="text" name="telephone" id="telephone" ng-model="telephone" ng-pattern="/^\d{9,10}$/" placeholder="Must be 9 or 10 digit">
                            <span class="error" ng-show="EmployeeEntry.telephone.$error.pattern && submitted">Telephone Number is Invalid</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Email<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="email" id="email" ng-model="email"  required  ng-pattern="/\S+@\S+\.\S+/" placeholder="Ex: name@domain.com">
                             <span class="error" ng-show="EmployeeEntry.email.$error.required && submitted">Email is Required</span>
                            <span class="error" ng-show="EmployeeEntry.email.$error.pattern && submitted">Invalid Email</span>
                        </td>
                        <th style="padding-left: 15px;">National ID<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="national_id" id="national_id" ng-model="national_id" required ng-pattern="/^\d{10,20}$/" placeholder="Must be 10 to 20 digits">
                            <span class="error" ng-show="EmployeeEntry.national_id.$error.required && submitted">National ID is Required</span>
                            <span class="error" ng-show="EmployeeEntry.national_id.$error.pattern && submitted">National ID is Invalid</span>
                        </td>
                        <th style="padding-left: 15px;">Date of Birth<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control datePicker" type="text" name="date_of_birth" id="date_of_birth" ng-model="date_of_birth" required placeholder="Choose Date of Birth">
                            <span class="error" ng-show="EmployeeEntry.date_of_birth.$invalid && submitted">Date of Birth is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Joining Date<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control datePicker" type="text" name="date_join" id="date_join" ng-model="date_join" required placeholder="Choose Join Date">
                            <span class="error" ng-show="EmployeeEntry.date_join.$invalid && submitted">Joining Date is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Present Address<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="present_address" id="present_address" ng-model="present_address" required placeholder="Enter Present Address">
                            <span class="error" ng-show="EmployeeEntry.present_address.$invalid && submitted">Present Address is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Permanent Address<span class="mandatory">*</span>:</th>
                        <td>
                            <input class="form-control" type="text" name="permanent_address" id="permanent_address" ng-model="permanent_address" required placeholder="Enter Permanent Address">
                            <span class="error" ng-show="EmployeeEntry.permanent_address.$invalid && submitted">Permanent Address is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Children:</th>
                        <td>
                            <select class="form-control" ng-model="children" ng-options="children.value as children.text for children in childrenOptions">
                            </select>
                            {{-- <span class="error" ng-show="mobile_required">@{{mobile_required}}</span> --}}
                        </td>
                        <th style="padding-left: 15px;">Home Allocation<span class="mandatory">*</span>:</th>
                        <td style="width: 200px;">
                            <label class="radio-inline">
                                <input type="radio"  ng-model="allocate_home"
                                       value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-init="allocate_home = 0" ng-model="allocate_home" ng-checked="true" value="0">No
                            </label>

                            <span class="error" ng-show="EmployeeEntry.allocate_home.$invalid && submitted">Home Allocation is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Home Area<span class="mandatory">*</span>:</th>
                        <td>
                            <select class="form-control" ng-init="house_area_flag = '3'" name ="house_area_flag" ng-model="house_area_flag" id="house_area_flag"  required>
                                <option value="">Select Home Area</option>
                                <option value="1">Dhaka Metro Area</option>
                                <option value="2">Expensive Area</option>
                                <option value="3">Other Area</option>
                            </select>
                            <span class="error" ng-show="EmployeeEntry.house_area_flag.$invalid && submitted">Home Area is Required</span>
                        </td>


                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th style="padding-left: 15px;">National ID Photo<span class="mandatory">*</span>:</th>
                        <td style="width: 200px;">
                            <input type="file" id="national_id_photo" class="form-control" file-model="national_id_photo" accept="image/*"/>
                            <span class="error" ng-show="national_id_photo_error">@{{national_id_photo_error}}</span>
                        </td>
                        <th style="padding-left: 15px;">Photo:</th>
                        <td style="width: 200px;">
                            <input type="file" id="photo" class="form-control" file-model="photo" accept="image/*"/>
                            <span class="error" ng-show="photo_error">@{{photo_error}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-center">
                            <button type="button" class="btn btn-primary center-block" ng-click="save()"  ng-if="!btnUpdate">Save</button>
                            {{--ng-show="btnSave"--}}
                            <button type="button" class="btn btn-success center-block" ng-click="update()" ng-if="btnUpdate">Update</button>
                            {{--ng-show="btnUpdate"--}}
                            <span ng-if="dataLoading">
                                <img src="/img/dataLoader.gif" width="250" height="15" />
                                <br />Please wait!
                            </span>
                        <td>
                    </tr>
                </table>
                </form>
                <br>
            </div>
		</div>

        <div  class="col-md-12 table-responsive" >
            <table class="table table-bordered" ng-show="employeeDetails">
                <caption><h4 class="text-center ok">Employee Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
                <thead>
                    <tr>
                    	<th>Employee ID</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>
                        <th>Spouse Name</th>
                        <th>Mobile</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>National ID</th>
                        <th>Date of Birth</th>
                        <th>Joining Date</th>
                        <th>Present Address</th>
                        <th>Permanent Address</th>
                        <th>Children</th>
                        <th>Home Area</th>
                        <th>Home Allocation</th>
                        <th>NID Photo</th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-style="{'background-color':(employee.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="employee in allEmployees | orderBy:'employee.id' | filter:searchText | itemsPerPage:3000" pagination-id="current">
                        <td>@{{employee.emp_id}}</td>
                        <td>@{{employee.name}}</td>
                        <td>@{{employee.father_name}}</td>
                        <td>@{{employee.mother_name}}</td>
                        <td>@{{employee.spouse_name}}</td>
                        <td>@{{employee.mobile}}</td>
                        <td>@{{employee.telephone}}</td>
                        <td>@{{employee.email}}</td>
                        <td>@{{employee.national_id}}</td>
                        <td>@{{employee.date_of_birth}}</td>
                        <td>@{{employee.date_join}}</td>
                        <td>@{{employee.present_address}}</td>
                        <td>@{{employee.permanent_address}}</td>
                        <td>@{{employee.children | childrenFilter}}</td>
                        <td>@{{employee.house_area_flag | houseAreaFilter}}</td>
                        <td>@{{employee.allocate_home | houseAllocateFilter}}</td>
                        <td>
                            <img ng-src="@{{ employee.national_id_photo ? '/img/employees/national_id/'+employee.national_id_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                        </td>
                        <td>
                            <img ng-src="@{{ employee.photo ? '/img/employees/'+employee.photo : '/img/noImg.jpg'}}" height="100" width="100">
                        </td>
                        <td>
                            <button style="width: 80px;" type="button" class="btn btn-success" ng-click="pressUpdateBtn(employee)">Update</button>
                            <button style="width: 80px;" type="button" class="btn btn-danger" ng-click="pressSuspendBtn(employee)">Suspend</button>
                            <button style="width: 80px;" data-toggle="modal"
                                    data-target="#employeeTransferModal"
                                    type="button" class="btn btn-info" ng-click="employeeTransferButton(employee)">
                                Transfer

                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    {{--<tr>--}}
                        {{--<td colspan="19" class="text-center">--}}
                            {{--<dir-pagination-controls max-size="5"--}}
                                                 {{--direction-links="true"--}}
                                                 {{--boundary-links="true"--}}
                                                 {{--pagination-id="current">--}}
                            {{--</dir-pagination-controls>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </tfoot>
            </table>
        </div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered" ng-show="suspendedEmployeeDetails">
                <caption><h4 class="text-center ok">Suspended Employee Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchTextSuspended"></label></caption>

                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>
                        <th>Spouse Name</th>
                        <th>Mobile</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>National ID</th>
                        <th>Date of Birth</th>
                        <th>Joining Date</th>
                        <th>Present Address</th>
                        <th>Permanent Address</th>
                        <th>Children</th>
                        <th>NID Photo</th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
             <tbody>
                    <tr ng-style="{'background-color':(employee.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="employee in allSuspendedEmployees | orderBy:'employee.id' | filter:searchTextSuspended | itemsPerPage:5" {{--pagination-id="suspend"--}}>
                        <td>@{{employee.emp_id}}</td>
                        <td>@{{employee.name}}</td>
                        <td>@{{employee.father_name}}</td>
                        <td>@{{employee.mother_name}}</td>
                        <td>@{{employee.spouse_name}}</td>
                        <td>@{{employee.mobile}}</td>
                        <td>@{{employee.telephone}}</td>
                        <td>@{{employee.email}}</td>
                        <td>@{{employee.national_id}}</td>
                        <td>@{{employee.date_of_birth}}</td>
                        <td>@{{employee.date_join}}</td>
                        <td>@{{employee.present_address}}</td>
                        <td>@{{employee.permanent_address}}</td>
                        <td>@{{employee.children | childrenFilter}}</td>
                        <td>
                            <img ng-src="@{{ employee.national_id_photo ? '/img/employees/national_id/'+employee.national_id_photo : '/img/imgNotAvailable.jpg'}}" height="100" width="100">
                        </td>
                        <td>
                            <img ng-src="@{{ employee.photo ? '/img/employees/'+employee.photo : '/img/noImg.jpg'}}" height="100" width="100">
                        </td>
                        <td>
                            <button style="width: 80px;" type="button" class="btn btn-primary" ng-click="pressReassignBtn(employee)">Reassign</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    {{--<tr>--}}
                        {{--<td colspan="13" class="text-center">--}}
                            {{--<dir-pagination-controls max-size="5"--}}
                                                 {{--direction-links="true"--}}
                                                 {{--boundary-links="true"--}}
                                                 {{--pagination-id="suspend">--}}
                            {{--</dir-pagination-controls>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </tfoot>
            </table>
        </div>


        {{--==============================Transfer  modal  Start=======================================--}}

        <div class="modal fade text-center" style="" id="employeeTransferModal" role="dialog">

            <div class="modal-dialog">
                <div class="modal-content largeModal">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal"
                                ng-click="{{--manifestSearch(searchText)--}}">&times;</button>
                        <h4 class="modal-title text-center">
                            Employee Details
                        </h4>
                        <div class="col-md-12" >
                            <div class="col-md-4">
                                <b>Employee ID :</b> @{{ emp_id_show}}
                            </div>
                            <div class="col-md-4">
                                <b>Employee Name :</b> @{{ name_show }}
                            </div>
                            <div class="col-md-4">
                                <b>Mobile :</b> @{{ mobile_show }}
                            </div>
                        </div >
                    </div>

                    <div class="modal-body" style="margin-left: 100px; border-radius: 20px;">
                                <h4 class="text-center ok">Employee Transfer Entry</h4>
                                <form name="employeeTransferForm" id="employeeTransferForm" novalidate>
                                <table>
                                    <tr>
                                        <th>Transfer Date<span class="mandatory">*</span>:</th>
                                        <td>
                                            <input class="form-control datePicker" type="text" name="date_transfer" id="date_transfer" ng-model="date_transfer" required placeholder="Choose Transfer Date">
                                            <span class="error" ng-show="employeeTransferForm.date_transfer.$invalid && submittedTransfer">Transfer Date is Required</span>
                                        </td>
                                        <th style="padding-left: 15px;">Port Name<span class="mandatory">*</span>:
                                        </th>
                                        <td>
                                            <select class="form-control" name="port_id" ng-model="port_id" ng-options="port.id as port.port_name for port in PortDetailsData" required>
                                                <option value="" selected="selected">Please Select</option>
                                            </select>
                                            <span class="error" ng-show="employeeTransferForm.port_id.$invalid && submittedTransfer">Port is Required</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <button type="button" class="btn btn-primary center-block" ng-click="saveTransferData(employeeTransferForm)"  ng-if="!btnUpdaTetransfer">Save</button>
                                            {{--ng-show="btnSave"--}}
                                            <button type="button" class="btn btn-success center-block" ng-click="updateTransferData(employeeTransferForm)" ng-if="btnUpdaTetransfer">Update</button>
                                            {{--ng-show="btnUpdate"--}}
                                            <span ng-if="dataLoading">
                                                <img src="/img/dataLoader.gif" width="250" height="15" />
                                             <br/>Please wait!
                                             </span>
                                            <div class="alert alert-success" id="savingTransferSuccess" ng-hide="!savingTransferSuccess">@{{ savingTransferSuccess }}</div>
                                            <div class="alert alert-danger" id="savingErrorTransfer" ng-hide="!savingErrorTransfer">@{{ savingErrorTransfer }}</div>
                                        <td>
                                    </tr>
                                </table>
                            </form>
                       
                    </div>
                    {{--modal-body--}}

                    <div class="modal-footer">
                        {{--data table--}}
                        <table class="table table-bordered text-center-td-th">
                            <thead>
                            <tr>
                                <td colspan="5" ng-if="data.dataLoading">
                                        <span style="color:green; text-align:center; font-size:20px">
                                            <img src="images/dataLoader.gif" width="350" height="20"/>
                                            <br/> Please wait! <br/>Data is loading...
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <th>S/L</th>
                                <th>Port</th>
                                <th>Transfer Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="transferData in employeesTransferData | orderBy:'employee.id' | filter:searchText | itemsPerPage:5" pagination-id="transfer">
                                <th>@{{ $index+1 }}</th>
                                <td>@{{ transferData.port_name }}</td>
                                <td>@{{ transferData.transfer_date  }}</td>
                                <td style="width: 120px;">
                                    <div class="btn-group">
                                        <button type="button" ng-click="ediTransferData(transferData)"
                                                class="btn btn-primary btn-xs">Edit
                                        </button>
                                        {{--<button type="button" ng-click="ediTransferData(transferData)"--}}
                                                {{--class="btn btn-danger btn-xs">Delete--}}
                                        {{--</button>--}}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="13" class="text-center">
                                    <dir-pagination-controls max-size="5"
                                                             direction-links="true"
                                                             boundary-links="true"
                                                             pagination-id="transfer">
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                ng-click="manifestSearch(searchText)">Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
        {{--==============================Transfer  modal  Start=======================================--}}



    </div>
    <script>

//        $(document).ready( function() {
//            $("#load_home").on("click", function() {
//                $("#content").load("content.html");
//            });
//        });
    </script>
@endsection