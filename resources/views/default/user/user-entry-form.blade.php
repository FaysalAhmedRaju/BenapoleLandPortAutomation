@extends('layouts.master')
@section('title', 'User Entry Form')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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
            background-color: rgba(0, 0, 0, 0.2);
        }


        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }
        /* IE 6 doesn't support max-height
         * we use height instead, but this forces the menu to always be this tall
         */
        * html .ui-autocomplete {
            height: 100px;
        }
        
    </style>
    <!-- Latest compiled and minified CSS -->
    {!!Html :: style('css/bootstrap-select.min.css')!!}
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/user.js')!!}
    

    <!-- Latest compiled and minified JavaScript -->
    {!!Html :: script('js/bootstrap-select.min.js')!!}
    <script type="text/javascript">
        $(function () {
            $('#join_date').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
            $('#date_of_birth').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="UserEntryApp" ng-controller="UserEntryController">
        <div class="col-md-10 col-md-offset-1" style="background-color: #dbd3ff; border-radius: 20px;">
            <h4 class="text-center ok">User Entry Form</h4>
            <div class="col-md-12" style="box-shadow:0 0 12px #3b5998; border-radius: 5px">
                <h5 class="text-center"><b>Employee Information</b></h5>
                <div class="col-md-4">
                    <h6><b>Name: </b>@{{name}}</h6>
                    <h6><b>Organization: </b>@{{organization}}</h6>
                    <h6><b>Designation: </b>@{{designation}}</h6>
                    <h6><b>Father Name: </b>@{{father_name}}</h6>
                    <h6><b>Mother Name: </b>@{{mother_name}}</h6>
                </div>
                <div class="col-md-4">
                    <h6><b>Mobile: </b>@{{mobile}}</h6>
                    <h6><b>Email: </b>@{{email}}</h6>
                    <h6><b>Date of Birth:</b> @{{date_of_birth}}</h6>
                    <h6><b>NID:</b> @{{national_id}}</h6>
                    <h6 ng-show="id != null"><b>User id:</b>@{{ id }}</h6>
                </div>

                <div class="col-md-4">
                    <img id="photo" src="#" height="100" width="100">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12">
                <hr>
                <form name="userEntryForm" id="userEntryForm" novalidate>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4">
                            <label for="user_type">
                                User Type:<span class="error">*</span>
                            </label>
                            <select class="form-control" ng-init="user_type = 'port'" name="user_type" ng-model="user_type" id="user_type" ng-change="cngUserType(user_type)" required>
                                <option value="port">Port</option>
                                <option value="c&f">C&F</option>
                                {{-- <option value="exporter">Exporter</option>
                                <option value="importer">Importer</option> --}}
                                <option value="custom">Custom</option>
                            </select>
                            <span class="error" ng-show="userEntryForm.user_type.$invalid && submitted">User Type is required</span>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="employee_id">
                                Employee Name/ID:<span class="error">*</span>
                            </label>
                            <input type="text" class="form-control" name="employee_name_or_id" id="employee_name_or_id" ng-model="employee_name_or_id" placeholder="Type Employee Name" required>
                            <span class="error" ng-show="userEntryForm.employee_name_or_id.$invalid && submitted">Employee Name/ID is required</span>
                            <span class="error" ng-show="empNotFoundError">No Employee Found</span>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="role_id">
                                Role:<span class="error">*</span>
                            </label>
                            <select class="form-control" name="role_id" ng-model="role_id" ng-options="role.id as role.name for role in allRoleData{{--  | filter:skipValues(org_type_id) --}}" required>
                                <option value="" selected="selected">Please Select</option>
                            </select>
                            <span class="error" ng-show="userEntryForm.role_id.$invalid && submitted">Role is required</span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4">
                            <label for="username">
                                Username:<span class="error">*</span>
                            </label>
                            <input class="form-control" type="text" name="user_name" id="user_name" ng-model="user_name" ng-blur="checkDuplicateUsername(user_name)" placeholder="Enter unique Username" required>
                            <span class="error" ng-show="userEntryForm.user_name.$invalid && submitted">Username is required</span>
                            <span class="error" ng-show="userNameExist">Username exists</span>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="password">
                                Password:<span class="error">*</span>
                            </label>
                            <input class="form-control" type="pass_word" name="pass_word" id="pass_word"ng-model="pass_word" {{-- ng-disabled="passwordShow" --}} ng-required="passwordValidation" placeholder="Enter Password">
                            <span class="error" ng-show="userEntryForm.pass_word.$invalid && submitted">Password is required</span>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="user_status">
                                Status:<span class="error">*</span>
                            </label>
                            <select class="form-control" ng-init="user_status = '1'" name="user_status"  ng-model="user_status" id="user_status">
                                <option value="1">Active</option>
                                <option value="0">Pending</option>
                                <option value="2" ng-show="user_type == 'custom'">Test User</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4" ng-show="showScaleOption">
                            <label for="scale">
                                Weighbridge Scale:<span class="error">*</span>
                            </label>
                            <select title="" class="form-control" name="scale" ng-init="scale = ''" ng-model="scale" ng-required="showScaleOption">
                                <option value="">Please Select</option>
                                @foreach($weighbridgeList as $j=>$w)
                                    <option value="{{$w->id}}">{{$w->scale_name}}</option>
                                @endforeach
                            </select>
                            <span class="error" ng-show="userEntryForm.scale.$invalid && submitted">Weighbridge Scale is required</span>
                        </div>
                        <div class="col-md-4 col-sm-4" ng-show="roleWarehouse">
                            <label for="shedYards">
                                Shed Yard:<span class="error">*</span>
                            </label>
                            <select title="No Shed Yard Selected" name="shedYards" class="form-control selectpicker" multiple ng-model="shedYards" ng-required="roleWarehouse">
                                    @foreach($yards as $k=>$v)
                                        <option value="{{$v->id}}">{{$v->shed_yard}}</option>
                                    @endforeach
                            </select>
                            <span class="error" ng-show="userEntryForm.shedYards.$invalid && submitted">Shed Yard is required</span>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="port_id">
                                Office Order:<span class="error">*</span>
                            </label>
                            <input class="form-control" type="text" name="office_order" ng-model="office_order" placeholder="Enter Office Order No" required>

                            <span class="error" ng-show="!office_order  && submitted">Office Order is required</span>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <label for="port_id">
                                Port:<span class="error">*</span>
                            </label>
                            <select title="No Port Selected" name="port_id" class="form-control selectpicker" multiple ng-model="port_id" required>
                                @foreach($portList as $i=>$d)
                                    <option value="{{$d->id}}">{{$d->port_name}}</option>
                                @endforeach
                            </select>
                            <span class="error" ng-show="userEntryForm.port_id.$invalid && submitted">Port is required</span>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <br/>
                        <button type="button" class="btn btn-primary center-block" ng-click="save()"
                                ng-if="btnSave" ng-disabled="uploadingTym"><span class="fa fa-file"></span> Save
                        </button>
                        {{--ng-show="btnSave"--}}
                        <button type="button" class="btn btn-success center-block" ng-click="update()"
                                ng-if="btnUpdate"><span class="fa fa-download"></span> Update
                        </button>
                        {{--ng-show="btnUpdate"--}}
                        <span ng-if="dataLoading">
                            <br/>Please wait!<br/>
                            {{ Html::image('img/dataLoader.gif','alt',['height'=>15,'width'=>250]) }}
                        </span>
                        <br/>
                    </div>
                </form>
            </div>
        </div>
        @if(Auth::user()->role->id == 11)
            <div class="col-md-6 form-inline">
                <br/>
                <label>User ID:</label>
                <input class="form-control" type="number" name="user_id" ng-model="user_id">
                <a class="btn btn-primary" target="_blank" href="{{url('user/edit/')}}/@{{user_id}}">Assign Port</a>
            </div>
        @endif
        <div class="clearfix"></div>
        <div class="col-md-12 table-responsive" style="padding: 10px;">
            {{--<div class="alert alert-danger" ng-hide="!errorType">@{{ errorType }}</div> --}}
            <table class="table table-bordered text-center">
                <caption><h4 class="text-center ok">User Details:</h4>
                    <div class="col-md-6 col-sm-6 col-xs-3 form-inline">
                        <div class="form-group">
                            <label for="user_type_search">
                                User Type:
                            </label>
                            <select class="form-control" ng-init="user_type_search = 'port'" name="user_type_search" ng-model="user_type_search" id="user_type_search" ng-change="allUserList(user_type_search)">
                                <option value="port">Port</option>
                                <option value="c&f">C&F</option>
                                {{-- <option value="exporter">Exporter</option>
                                <option value="importer">Importer</option> --}}
                                <option value="custom">Custom</option>
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
                    <th>S/L</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Photo</th>
                    <th style="width: 100px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(user.id == selectedStyle?'#dbd3ff':'')}"
                    dir-paginate="user in allUser | filter:searchText | itemsPerPage:itemPerpage " pagination-id="branch">
                    <td>@{{ $index + serial }}</td>
                    <td>
                        {{-- <a target="_blank" href="{{url('user/edit/')}}/@{{user.id}}"> --}}@{{user.name}}{{-- </a> --}}
                    </td>
                    <td>@{{user.mobile}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.username}}</td>
                    <td>@{{user.rolename}}</td>
                    <td>
                        <img ng-src="@{{ user.photo != null ? '/'+user.photo : '/img/noImg.jpg'}}" height="100" width="100">
                    </td>
                    <td>
                        <button style="width: 70px;" type="button" class="btn btn-success btn-sm"
                                ng-click="editUser(user)">Edit
                        </button>
                        <button style="width: 70px;" type="button" class="btn btn-danger btn-sm"
                                ng-click="pressDeleteBtn(user)">Delete
                        </button>
                        <button style="width: 70px;" data-target="#DetailsModal" data-toggle="modal" type="button" class="btn btn-primary btn-sm" ng-click="pressDetailsBtn(user)">Details</button>
                        {{-- <button style="width: 145px;" type="button" class="btn btn-primary" ng-click="">Change Password</button> --}}
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="20" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="branch">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        {{-- Details Modal Start --}}
        <div class="modal fade text-center" style="left: 0;" id="DetailsModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-auto formBgColor">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">User Name: <span class="ok">@{{ userFullName }}</span></h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Organization:</th>
                                <td style="padding-left: 25px;">@{{userorg_name}}</td>
                            </tr>
                            <tr>
                                <th>Designation:</th>
                                <td style="padding-left: 25px;">@{{ userdesignation }}</td>
                            </tr>
                            <tr>
                                <th>Father Name:</th>
                                <td style="padding-left: 25px;">@{{ userfather_name }}</td>
                            </tr>
                            <tr>
                                <th>Mother Name:</th>
                                <td style="padding-left: 25px;">@{{ usermother_name }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td style="padding-left: 25px;">@{{ userdate_of_birth }}</td>
                            </tr>
                            <tr>
                                <th>National ID:</th>
                                <td style="padding-left: 25px;">@{{ usernid_no }}</td>
                            </tr>
                            <tr>
                                <th>Office Orders:</th>
                                <td style="padding-left: 25px;">
                                    <label class="label label-primary" for="" ng-repeat=" order in office_orders.split(',')">
                                        @{{ order }}
                                    </label>  &nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Details Modal End --}}
    </div>


@endsection