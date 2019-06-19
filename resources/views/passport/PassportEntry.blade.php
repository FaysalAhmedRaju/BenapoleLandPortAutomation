@extends('layouts.master')
@section('title', 'Passport Entry Form')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/passportEntry.js')!!}

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
        <div class="col-md-12  ng-cloak" ng-app="passportEntryApp" ng-controller="passportEntryController">
            <div class="col-md-7 col-md-offset-5">
                <form class="form-inline" ng-submit="passportInfo(PassportNo)">
                    <div class="form-group">
                        <input type="text" name="PassportNo" ng-model="PassportNo" id="PassportNo" class="form-control" placeholder="Enter Passport No">
                    </div>
                </form>
                <br>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-11" style="left:50px; background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Passport Entry Form</h4>
                <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="col-md-12">
                    <form name="passportEntry" id="passportEntry" novalidate>
                    <table>
                        <tr>
                            <th>Passport No<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="passport_no" id="passport_no" ng-model="passport_no">
                                <span class="error" ng-show="passport_no_required">Passport No is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Country Code<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="country_code" id="country_code" ng-model="country_code">
                                <span class="error" ng-show="country_code_required">Country Code is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Sur Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="sur_name" id="sur_name" ng-model="sur_name">
                                <span class="error" ng-show="sur_name_required">Sur Name is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Given Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="given_name" id="given_name" ng-model="given_name">
                                <span class="error" ng-show="given_name_required">Given Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Nationality<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="nationality" id="nationality" ng-model="nationality">
                                <span class="error" ng-show="nationality_required">Nationality is Required</span>
                            </td>




                            <th style="padding-left: 15px;">Sex:</th>
                            <td>
                               <label class="radio-inline">
                                    <input type="radio" ng-checked="true"   ng-init="sex=1" ng-model="sex"  value="1">Male
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"   ng-model="sex"  value="0">Female
                                </label>
                            </td>





                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Date of Birth<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control datePicker" type="text" name="date_of_birth" id="date_of_birth" ng-model="date_of_birth">
                                <span class="error" ng-show="date_of_birth_required">Date of Birth is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Place of Birth<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="place_of_birth" id="place_of_birth" ng-model="place_of_birth">
                                <span class="error" ng-show="place_of_birth_required">Place of Birth is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Place of Issue<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control" type="text" name="place_of_issue" id="place_of_issue" ng-model="place_of_issue">
                                <span class="error" ng-show="place_of_issue_required">Place of Issue is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Date of Issue<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control datePicker" type="text" name="date_of_issue" id="date_of_issue" ng-model="date_of_issue">
                                <span class="error" ng-show="date_of_issue_required">Date of Issue is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Date of Expired<span class="mandatory">*</span>:</th>
                            <td>
                                <input class="form-control datePicker" type="text" name="date_of_expired" id="date_of_expired" ng-model="date_of_expired">
                                <span class="error" ng-show="date_of_expired_required">Date of Expired is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="6" class="text-center">
                                <button type="button" style="width:110px"  class="btn btn-primary center-block" ng-click="save()" ng-if="buttonpassport">Save</button>
                                {{--<button type="button" class="btn btn-primary center-block" ng-click="update()" ng-show="btnUpdate">Update</button>--}}


                            <td>
                            <td>
                                <button type="button" style="width:110px"  class="btn btn-primary center-block" ng-click="visaDetails(PassportNo)" data-target="#addVisaFormModal" data-toggle="modal">Add Visa</button>

                            </td>
                            <td></td>
                        </tr>
                    </table>
                    </form>
                    <br>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-md-12">
                <table class="table table-bordered" ng-show="showPassportDetailsSearch">
                    <caption><h4 class="text-center ok">Passport Information</h4></caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Passport No</th>
                        <th>Place Of Issue</th>
                        <th>Date of Issue</th>
                        <th>Date of Expired</th>
                        {{--<th>Type</th>--}}
                        <th>Numbers Of Entries</th>
                        <th>Duration Of Stay</th>
                        <th>Sur Name</th>
                        <th>Date Of Birth</th>
                        <th>Sex</th>
                        <th>Nationality</th>
                        {{--<th>Remarks</th>--}}
                        {{--<th>Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="passport in passportInfoData">
                        <td>@{{ $index+1 }}</td>
                        <td>@{{passport.passport_no}}</td>
                        <td>@{{passport.place_of_issue}}</td>
                        <td>@{{passport.date_of_issue}}</td>
                        <td>@{{passport.date_of_expired}}</td>
                        {{--<td>@{{passport.type}}</td>--}}
                        <td>@{{passport.numbers_of_entries}}</td>
                        <td>@{{passport.duration_of_stay}}</td>
                        <td>@{{passport.sur_name}}</td>
                        <td>@{{passport.date_of_birth}}</td>
                        <td>@{{passport.sex | sexFilter}}</td>
                        <td>@{{passport.nationality}}</td>
                        {{--<td>@{{passport.remarks}}</td>--}}

                        <td>
                        <a class="btn btn-success"  target="_blank">Edit</a>
                        {{--href="addVisa/@{{ passport.id }}"--}}
                        {{--ng-click="visaDetails(passport.passport_no)"--}}
                        {{--<button type="button" class="btn btn-primary" ng-click="details(passport.VisaDetailsForm)">Details</button>--}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="clearfix"></div>
            <div class="col-md-12">
               <table class="table table-bordered" ng-show="showPassportDetails">
                <caption><h4 class="text-center ok">Visa Details Information</h4></caption>
                    <thead>
                        <tr>
                            <th>Passport No</th>
                            <th>Place Of Issue</th>
                            <th>Date of Issue</th>
                            <th>Date of Expired</th>
                            <th>Type</th>
                            <th>Numbers Of Entries</th>
                            <th>Duration Of Stay</th>
                            <th>Sur Name</th>
                            <th>Date Of Birth</th>
                            <th>Sex</th>
                            <th>Nationality</th>
                            <th>Remarks</th>
                            {{--<th>Action</th>--}}
                        </tr>
                    </thead>
                 <tbody>
                        <tr ng-repeat="passport in AllVisaInfoDataForShow">
                            <td>@{{passport.passport_no}}</td>
                            <td>@{{passport.place_of_issue}}</td>
                            <td>@{{passport.date_of_issue}}</td>
                            <td>@{{passport.date_of_expired}}</td>
                            <td>@{{passport.type}}</td>
                            <td>@{{passport.numbers_of_entries}}</td>
                            <td>@{{passport.duration_of_stay}}</td>
                            <td>@{{passport.sur_name}}</td>
                            <td>@{{passport.date_of_birth}}</td>
                            <td>@{{passport.sex | sexFilter}}</td>
                            <td>@{{passport.nationality}}</td>
                            <td>@{{passport.remarks}}</td>

                            {{--<td>--}}
                                {{--<a class="btn btn-success"  target="_blank">Add Visa</a>--}}
                                {{--href="addVisa/@{{ passport.id }}"--}}
                                {{--ng-click="visaDetails(passport.passport_no)"--}}
                                {{--<button type="button" class="btn btn-primary" ng-click="details(passport.VisaDetailsForm)">Details</button>--}}
                            {{--</td>--}}
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- addVisaFormModal Modal START===================================================================MODAL========== -->


            <div id="addVisaFormModal" class="modal fade" role="dialog">


                <div class="modal-dialog" >

                    <!-- Modal content-->
                    <div class="modal-content largeModal" id="">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center" style="color: #000;">Visa Information</h4>
                            {{--<h5>You are inserting data against Manifest No: <b>@{{GetManiNo}}</b> </h5>--}}

                        </div>
                        <div class="modal-body">
                            {{--style="padding: 10px 100px; margin: 0 auto;width: 711px;padding: 10px;"--}}
                            <form class="formBgColor"  name="bdTruckForm" id="passportForm" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                                {{--style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;"--}}
                                <table style="width: 100%;">


                                    <tbody>

                                    <tr>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Passport NO:</th>
                                        <td>

                                            <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;"  class="form-control" required="required" ng-model="v_passport_no"  name="v_passport_no" id="v_passport_no" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.passport_no.$touched && !passport_no">--}}
                             {{--Passport NO is required--}}
                            {{--</span>--}}

                                        </td>

                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Place Of Issue:</th>
                                        <td>
                                            <input class="form-control" type="text" ng-model="v_place_of_issue"  style="width: 190px;"  name="v_place_of_issue" id="v_place_of_issue" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.place_of_issue.$touched && !place_of_issue">--}}
                             {{--Place Of Issue is required--}}
                            {{--</span>--}}
                                        </td>

                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Date Of Issue:</th>
                                        <td>

                                            <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;"   type="text" class="form-control datePicker"  ng-model="v_date_of_issue" name="v_date_of_issue" id="v_date_of_issue" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.date_of_issue.$touched && !date_of_issue">--}}
                             {{--Date Of Issue is required--}}
                            {{--</span>--}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">&nbsp;

                                        </td>
                                    </tr>

                                    <tr>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Date Of Expired:</th>
                                        <td>

                                            <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" type="text" class="form-control datePicker"  ng-model="v_date_of_expired" name="v_date_of_expired" id="v_date_of_expired" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.date_of_expired.$touched && !date_of_expired">--}}
                             {{--Date Of Expiry is required--}}
                            {{--</span>--}}

                                        </td>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Type:</th>
                                        <td>
                                            <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="type" name="type" id="type" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.type.$touched && !type">--}}
                             {{--Type is required--}}
                            {{--</span>--}}
                                        </td>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Number of Entries:</th>
                                        <td>
                                            <input type="text"  ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="numbers_of_entries" name="numbers_of_entries" id="numbers_of_entries" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.numbers_of_entries.$touched && !type">--}}
                             {{--Number of Entries is required--}}
                            {{--</span>--}}
                                        </td>
                                    </tr>





                                    <tr>
                                        <td colspan="4">&nbsp;

                                        </td>
                                    </tr>

                                    <tr>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Duration Of Each Stay:</th>
                                        <td>
                                            <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="duration_of_stay" name="duration_of_stay" id="duration_of_stay" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.duration_of_stay.$touched && !type">--}}
                            {{--Duration Of Each Stay is required--}}
                            {{--</span>--}}
                                        </td>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Sur Name:</th>
                                        <td>
                                            <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="v_sur_name" name="v_sur_name" id="v_sur_name" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.sur_name.$touched && !type">--}}
                            {{--Sur Name is required--}}
                            {{--</span>--}}
                                        </td>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Date Of Birth:</th>
                                        <td>
                                            <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" type="text" class="form-control datePicker"  ng-model="v_date_of_birth" name="v_date_of_birth" id="v_date_of_birth" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.date_of_birth.$touched && !type">--}}
                            {{--Date Of Birth is required--}}
                            {{--</span>--}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">&nbsp;

                                        </td>
                                    </tr>

                                    <tr>



                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Sex:</th>
                                        <td>
                                            {{--radio-inline--}}
                                            <label class="radio-inline">
                                                <input type="radio" ng-checked="true" ng-init="v_sex=1" ng-model="v_sex" name="v_sex"  value="1">Male
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  ng-model="v_sex" name="v_sex"  value="0" >Female
                                            </label>
                                            {{--<span class="error" ng-show="visaform.sex.$touched && !type">--}}
                            {{--Sex is required--}}
                            {{--</span>--}}
                                        </td>




                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Nationality:</th>
                                        <td>
                                            <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="v_nationality" name="v_nationality" id="v_nationality" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.nationlity.$touched && !type">--}}
                            {{--Nationality is required--}}
                            {{--</span>--}}
                                        </td>
                                        <th ng-hide="hidemanifestWhenUpdatebtnClick">Remarks:</th>
                                        <td>
                                            <input type="text" ng-disabled="manif_posted_btn_disable" style="width: 190px;" class="form-control" ng-model="remarks" name="remarks" id="remarks" ng-hide="hidemanifestWhenUpdatebtnClick">
                                            {{--<span class="error" ng-show="visaform.remarks.$touched && !type">--}}
                            {{--Remarks is required--}}
                            {{--</span>--}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">&nbsp;

                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <br>
                                            <button type="button" style="width:110px" ng-click="saveVisa()" ng-hide="updateBtn" class="btn btn-primary" ng-if="visaButton">Save</button>
                                            {{--<button type="button" ng-click="update(truckForm)" ng-show="updateBtn" class="btn btn-primary">Update</button>--}}

                                        </td>
                                    <tr>
                                        <td class="text-center ok" colspan="6">

                                            @{{savingSuccessVisa }}
                                            @{{ savingErroVisa }}
                                            {{--@{{ updateSuccessMsg }}--}}
                                        </td>
                                    </tr>

                                    </tr>


                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center ok" colspan="6">

                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>

            </div>

            <!-- addVisaFormModal Modal END -->


        </div>
@endsection