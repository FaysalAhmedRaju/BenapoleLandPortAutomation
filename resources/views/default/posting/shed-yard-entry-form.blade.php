@extends('layouts.master')
@section('title', 'Posting Shed Yard Entry Form')
@section('script')
    {!!Html :: script('js/customizedAngular/posting/posting-shed-yard-entry.js')!!}
    <script type="text/javascript">

        var role_id = {!! json_encode(Auth::user()->role->id) !!};
    </script>
    {!!Html :: script('js/bootstrap-select.min.js')!!}
@endsection
@section('style')
    {!!Html :: style('css/bootstrap-select.min.css')!!}

    <style>
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
@endsection

@section('script')
    {!!Html :: script('js/customizedAngular/posting.js')!!}
    <script type="text/javascript">

        var role_id = {!! json_encode(Auth::user()->role->id) !!};
    </script>
    {!!Html :: script('js/bootstrap-select.min.js')!!}
@endsection

@section('content')
    <div class="col-md-12"  {{--style="padding: 0;"--}} ng-cloak=""  ng-app="shedYardApp" ng-controller="shedYardController" ng-cloak>
        {{--<div class="col-md-3 col-md-offset-4">--}}
            {{--<p style="color: red"  ><b>@{{ WeightEmty }}</b></p>--}}
            {{--<p style="color: green"><b>@{{ WeightDone }}</b></p>--}}
            {{--<p style="color: red" ><b>@{{ WeightTruckEmty }}</b></p>--}}
            {{--<p style="color: green" ><b>@{{ WeightTruckDone }}</b></p>--}}
        {{--</div>--}}
        {{--<div class="col-md-5" ng-show="cnf_posted_flag == 1">--}}
            {{--<span style="color: green;">Manifest Posted By @{{ org_name }}(CNF)</span>--}}
        {{--</div>--}}
        {{--<div class="col-md-5" ng-show="manifest_posted_done_flag == 1">--}}
            {{--<span style="color: green;">Manifest Posting updated by Posting Branch</span>--}}

        {{--</div>--}}

        <div class="col-md-11 col-md-offset-1" style="padding-bottom: 30px;">
            <div class="col-md-3">

            </div>
            <div class="col-md-4">
                <form class="form-inline" ng-submit="search(ManifestNo)">
                    <div class="form-group">
                        <input type="text" name="ManifestNo" ng-model="ManifestNo" id="ManifestNo" class="form-control" placeholder="Search Manifest No" ng-keydown="keyBoard($event)">
                    </div><br>
                    <span class="error">@{{ searchNotFound }}</span>
                </form>
            </div>
            <div class="col-md-5">

                 {{-- <button type="button" class="btn  btn-success pull-right" data-target="#addImporter" data-toggle="modal">+ Importer</button>--}}

            </div>
        </div>
        <div id="success" class="col-md-6 col-md-offset-4 alert alert-success" ng-show="successMsg">
        Successfully @{{ successMsgTxt }}!
        </div>
        <div id="error" class="col-md-12 alert alert-warning" ng-show="errorItemMsg">
        @{{ ItemsChectMsg }}!
        </div>
        <div id="errorMsg" class="col-md-12 alert alert-warning" ng-show="errorMsg">
        @{{ errorMsgTxt }}!
        </div>
        {{--<div class="col-md-12 table-responsive" style="background-color: #f8f9f9;">--}}
        <form  name="postingform" id="postingform" novalidate>
            <table   class="table table-bordered">
                <thead>
                <tr>
                    <th style=" width: 400px; text-align: center"  >Truck Information</th>
                    <th style=" width: 400px; text-align: center" >Posting Shed/Yard</th>


                    <th style=" padding: 20px; text-align: center"  >Action</th>
                </tr>
                </thead>
                <tbody >
                <tr>

                    <td style=" /*padding: 0px;*/" >

                        @{{ truck_type_no }}

                    </td>
                    <td style="text-align: center " >
                        <select title="Select Shed/Yard" style="width: 190px;" class="selectpicker" name="t_posted_yard_shed"
                        ng-model="t_posted_yard_shed" required
                        multiple>
                        @foreach($yards as $k=>$v)
                        <option value="{{$v->id}}">{{$v->shed_yard}}</option>
                        @endforeach
                        </select>
                    </td>

                    <td  style="text-align: center " >
                        {{--<form action="{{ route('gateout-get-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">--}}
                            {{--{{ csrf_field() }}--}}
                            <button type="button" id="saveBtn" name="saveBtn" ng-click="save(postingform)" class="btn btn-primary"><span class="fa fa-file"></span> Save</button>

                          {{----}}
                            {{--<input ng-show="ff" class="form-control" value="@{{ getData.manifest }}" type="text" name="manifest"--}}
                                   {{--id="manifest"/>--}}
                            {{--<input type="hidden" name="partial_status_for_gatepass" value="@{{ getData.partial_status }}">--}}
                            {{--<button type="submit" --}}{{--ng-disabled="!searchText"--}}{{-- class="btn btn-info">Report--}}
                            {{--</button>--}}
                        {{--</form>--}}


                    </td>
                </tr>
                </tbody>

            </table>
        </form>

            {{--<form  name="postingform" id="postingform" novalidate>--}}
                {{--<table>--}}
                    {{--<tr>--}}
                        {{--<th ng-hide="hidemanifestWhenUpdatebtnClick" >Manifest NO:</th>--}}
                        {{--<td>--}}

                            {{--<input  ng-disabled="m_manifest"  style="width: 190px;" class="form-control" ng-model="m_manifest"  name="m_manifest" id="m_manifest" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Manifest NO">--}}
                        {{--</td>--}}
                        {{--<th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Manifest Date <span class="mandatory">*</span> :</th>--}}
                        {{--<td>--}}

                            {{--<input  style="width: 190px;"  type="text"  class="form-control datePicker" required ng-model="m_manifest_date" name="m_manifest_date" id="m_manifest_date"  placeholder="Manifest Date">--}}
                            {{--<span class="error" ng-show="postingform.m_manifest_date.$invalid && submittedPostingForm">Manifest Date is required</span>--}}

                        {{--</td>--}}

                        {{--<th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Marks & No:</th>--}}

                        {{--<td>--}}
                            {{--<select title="" style="width: 190px;" class="selectpicker" name="t_posted_yard_shed"--}}
                                    {{--ng-model="t_posted_yard_shed" required--}}
                                    {{--ng-hide="hidemanifestWhenUpdatebtnClick" multiple>--}}
                                {{--@foreach($yards as $k=>$v)--}}
                                    {{--<option value="{{$v->id}}">{{$v->shed_yard}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                            {{--<span class="error" ng-show="postingform.t_posted_yard_shed.$invalid && submittedPostingForm">Yard is required</span>--}}
                        {{--</td>--}}
                        {{--<td colspan="6" class="text-center">--}}
                            {{--<br>--}}
                            {{--<button type="button" id="saveBtn" name="saveBtn" ng-click="save(postingform)" class="btn btn-primary"><span class="fa fa-file"></span> Save</button>--}}

                            {{--<p colspan="" class="ok" ng-show="saveSuccessManifiest"  >@{{ savingSuccess }}</p>--}}
                        {{--</td>--}}
                    {{--</tr>--}}

                    {{--<tr>--}}
                        {{--<td colspan="6">&nbsp;</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td colspan="2"></td>--}}
                        {{--<td class="text-center" colspan="3">--}}


                        {{--</td>--}}
                        {{--<td colspan="1"> </td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td colspan="0"></td>--}}
                        {{--<td class="text-center" colspan="3">--}}





                        {{--</td>--}}
                        {{--<td colspan="1"> </td>--}}
                    {{--</tr>--}}
                {{--</table>--}}
            {{--</form>--}}


        {{--</div>--}}



    {{--    <div class="clearfix"></div>--}}


        {{-- Add Importer Model --}}
        <div class="modal fade text-center" style="left: 0;" id="addImporter" role="dialog">
            <div class="modal-dialog">
                <div class="modal-lg formBgColor">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center ok">Add Importer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="savingSuccessBin" ng-hide="!savingSuccessBin">@{{ savingSuccessBin }}</div>
                        <div class="alert alert-danger" id="savingErrorBin" ng-hide="!savingErrorBin">@{{ savingErrorBin }}</div>
                        <form  name="importerForm" id="importerForm" novalidate>
                            <table>
                                <tr>
                                    <th>BIN No<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" name="BINNO" id="BINNO" class="form-control" ng-model="BINNO" required ng-pattern="/^\d{5,15}$/" {{-- unique --}} {{-- ng-disabled="diableBINNUmber" --}}>
                                        <span class="error" ng-show="importerForm.BINNO.$error.required && submittedBin">BIN No is required.</span>
                                        <span class="error" ng-show="importerForm.BINNO.$error.pattern && submittedBin">BIN No must be 5 to 15 character.</span>
                                        {{-- <span class="error" ng-show="exist && submittedBin">BIN No already exist.</span> --}}
                                    </td>
                                    <th style="padding-left: 25px;">Name<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text"  name="BINNAME" id="BINNAME" class="form-control" ng-model="BINNAME" required>
                                        <span class="error" ng-show="importerForm.BINNAME.$invalid && submittedBin">Name Is required.</span>
                                    </td>
                                    <th style="padding-left: 25px;">Address1<span class="mandatory">*</span>:</th>
                                    <td>
                                        <textarea class="form-control textarea" name="ADD1" id="ADD1" ng-model="ADD1" required>
                                        </textarea>
                                        <span class="error" ng-show="importerForm.ADD1.$invalid && submittedBin">Address1 Is required.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th>Address2<span class="mandatory">*</span>:</th>
                                    <td>
                                        <textarea class="form-control textarea" name="ADD2" id="ADD2" ng-model="ADD2" required>
                                        </textarea>
                                        <span class="error" ng-show="importerForm.ADD2.$invalid && submittedBin">Address2 Is required.</span>
                                    </td>
                                    <th style="padding-left: 25px;">Address3:</th>
                                    <td>
                                        <textarea class="form-control textarea" name="ADD3" id="ADD3" ng-model="ADD3">
                                        </textarea>
                                    </td>
                                    <th style="padding-left: 25px;">Address4:</th>
                                    <td>
                                        <textarea class="form-control textarea" name="ADD4" id="ADD4" ng-model="ADD4">
                                        </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button type="button" class="btn btn-primary center-block" ng-click="SaveBin()"><span class="fa fa-file"></span> Save</button>
                                        <span ng-if="dataLoadingBin">
                                            <img src="img/dataLoader.gif" width="250" height="15"/>
                                            <br/>Please wait!
                                        </span>
                                    <td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Add Importer Model --}}
    </div>

    <script type="text/javascript">
        $( function() {
            $( "#truckentry_datetime" ).datepicker(
                {

                    dateFormat: 'yy-mm-dd',
                }
            );

        } );

        $(document).on('keyup keydown', 'select', function (e) {
            shifted = e.shiftKey;
        });

        function EnterToTab(next_input){
            console.log('asdasd');
            if (event.keyCode==13) {
                event.preventDefault();
                var nextInput = next_input;
                if (nextInput) {
                    nextInput.focus();
                }
                console.log(nextInput);
                if(nextInput == saveBtn) {
                    document.getElementById("saveBtn").click();
                }
            }

        }


    </script>

@endsection


