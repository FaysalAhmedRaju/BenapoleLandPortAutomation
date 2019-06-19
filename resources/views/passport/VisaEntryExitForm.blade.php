@extends('layouts.master')

@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

    {!!Html :: script('js/customizedAngular/passportEntry.js')!!}

@endsection

@section('content')
    <div class="col-md-12"  style="padding: 0;" ng-cloak=""  ng-app="passportEntryApp" ng-controller="passportEntryController">

        <div class="col-md-7 col-md-offset-5">
            <form class="form-inline" ng-submit="SearchPassNOEntryExit(PassportNo)">
                <div class="form-group">
                    <input type="text" name="PassportNo" ng-model="PassportNo" id="PassportNo" class="form-control" placeholder="Enter Passport No">
                </div>
            </form>
            <br>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px; padding: 10px 10px 10px 80px;">

            <form  name="visaform" id="visaform" novalidate>

                <table>
                <tr>
                    <td class="text-center" colspan="6" >
                        <h4 class="ok">Entry / Exit Form</h4>
                    </td>
                </tr>

                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Date :</th>
                    <td>

                        <input   ng-disabled="manif_posted_btn_disable" style="width: 190px;" type="text" class="form-control datePicker"  ng-model="date" name="date" id="date" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="visaform.date_of_expired.$touched && !date_of_expired">
                             Date Of Expiry is required
                            </span>

                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Reason :</th>
                    <td>
                        <input class="form-control" type="text" ng-model="entry_reasons" style="width: 190px;" name="entry_reasons" id="entry_reasons" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="visaform.place_of_issue.$touched && !place_of_issue">
                             Place Of Issue is required
                            </span>
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Comment :</th>
                    <td>
                        <input class="form-control" type="text" ng-model="comment" style="width: 190px;" name="comment" id="comment" ng-hide="hidemanifestWhenUpdatebtnClick">
                        <span class="error" ng-show="visaform.place_of_issue.$touched && !place_of_issue">
                             Place Of Issue is required
                            </span>
                    </td>
                </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>

                    <tr>

                        <th>Move Status : </th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" ng-checked="true"  ng-model="entry_exit_status" ng-init="entry_exit_status=Entry" value="Entry">Entry
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="entry_exit_status" value="Exit">Exit
                            </label>
                        </td>
                    </tr>
                <tr>
                <td colspan="4">&nbsp;</td>
                </tr>

                      <tr>
                    <td colspan="6" class="text-center">
                        <br>
                        <button type="button" ng-click="saveEntryExit()" ng-hide="updateBtn" class="btn btn-primary" ng-if="visaExit" >Save</button>
                    </td>
                </tr>
                <tr>
                    <td class="text-center ok" colspan="6">

                        @{{savingSuccessEntryExit }}
                        @{{ savingErrorEntryExit }}

                    </td>
                </tr>
            </table>
            </form>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <table class="table table-bordered" ng-show="showPassportDetails">
                <caption><h4 class="text-center ok">Entry Exit Information</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Passport No</th>
                    <th>Date</th>
                    <th>Move Status</th>
                    <th>Reason</th>
                    <th>Comment</th>

                    {{--<th>Action</th>--}}
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="passport in getAllExitEntryData">
                    <td>@{{ $index+1 }}</td>
                    <td>@{{passport.passport_no}}</td>
                    <td>@{{passport.date}}</td>
                    <td>@{{passport.entry_exit_status}}</td>
                    <td>@{{passport.entry_reasons}}</td>
                    <td>@{{passport.comment}}</td>


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



    </div>
    <script>
        $( function() {
            $( "#truckentry_datetime" ).datepicker(
                {

                    dateFormat: 'yy-mm-dd',


                }
            );

        } );
    </script>
@endsection


