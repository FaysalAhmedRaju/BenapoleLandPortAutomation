@extends('layouts.master')
@section('title', 'Organization Entry Form')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/organization.js')!!}
@endsection
@section('content')
        <div class="col-md-12  ng-cloak" ng-app="OrganizationEntryApp" ng-controller="OrganizationEntryController">
            <div class="col-md-12" style="background-color: #dbd3ff; border-radius: 20px;">
                <h4 class="text-center ok">Organization Entry Form</h4>
                <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="col-md-12">
                    <form name="OrganizationEntryForm" id="OrganizationEntryForm" novalidate>
                    <table>
                        <tr>
                            <th>Organization Type<span class="mandatory">*</span>:</th>
                            <td>
                                <select class="form-control" name="org_type_id" ng-model="org_type_id" ng-options="orgType.id as orgType.org_type for orgType in allOrgTypeData">
                                    <option value="" selected="selected">Please Select</option>
                                </select>
                                <span class="error" ng-show="org_type_id_required">Organization Type is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Organization Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="org_name" id="org_name" ng-model="org_name">
                                <span class="error" ng-show="org_name_required">Organization Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Port Name<span class="mandatory">*</span>:</th>
                            <td>
                                <select class="form-control" name="port_id" ng-model="port_id" ng-options="port.id as port.port_name for port in allPortData">
                                    <option value="" selected="selected">Please Select</option>
                                </select>
                                <span class="error" ng-show="port_id_required">Port is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Propriter Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="propriter_name" id="propriter_name" ng-model="propriter_name">
                                <span class="error" ng-show="propriter_name_required">Propriter Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Address1<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="add1" id="add1" ng-model="add1">
                                <span class="error" ng-show="add1_required">Address1 is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Address2<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="add2" id="add2" ng-model="add2">
                                <span class="error" ng-show="add2_required">Addreess2 is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Phone<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="phone" id="phone" ng-model="phone">
                                <span class="error" ng-show="phone_required">Phone is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Mobile<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="mobile" id="mobile" ng-model="mobile">
                                <span class="error" ng-show="mobile_required">Mobile is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Email<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="email" id="email" ng-model="email">
                                <span class="error" ng-show="email_required">Email is Required</span>
                            </td>
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
                            <td>
                        </tr>
                    </table>
                    </form>
                    <br>
                </div>
            </div>

            <div class="clearfix"></div>
                <div class="col-md-12" style="padding: 10px;">
                    {{--<div class="alert alert-danger" ng-hide="!errorType">@{{ errorType }}</div> --}}
                    <table class="table table-bordered">
                    <caption><h4 class="text-center ok">Organization Details:</h4></caption>
                        <thead>
                            <tr>
                                <th>Organization Type</th>
                                <th>Organization Name</th>
                                <th>Port Name</th>
                                <th>Propriter Name</th>
                                <th>Address1</th>
                                <th>Address2</th>
                                <th>Phone</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                     <tbody>
                            <tr ng-style="{'background-color':(organization.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="organization in allOrganization | orderBy:'organization.id' | itemsPerPage:5">
                                <td>@{{organization.org_type}}</td>
                                <td>@{{organization.org_name}}</td>
                                <td>@{{organization.port_name}}</td>
                                <td>@{{organization.propriter_name}}</td>
                                <td>@{{organization.add1}}</td>
                                <td>@{{organization.add2}}</td>
                                <td>@{{organization.phone}}</td>
                                <td>@{{organization.mobile}}</td>
                                <td>@{{organization.email}}</td>
                                <td>
                                    <button style="width: 80px;" type="button" class="btn btn-success" ng-click="pressUpdateBtn(organization)">Update</button>
                                    <button style="width: 80px;" type="button" class="btn btn-danger" ng-click="pressDeleteBtn(organization)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="text-center">
                                    <dir-pagination-controls max-size="5"
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