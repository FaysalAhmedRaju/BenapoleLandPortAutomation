@extends('layouts.master')
@section('title', 'Partial Assessment')
@section('script')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}

    {!! Html::script('js/bootbox.min.js')!!}
    {{-- {!!Html :: style('css/jquery.datetimepicker.css')!!}
     {!! Html::script('js/jquery.datetimepicker.js')!!}--}}
    {{--
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.common-material.min.css" />
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.material.min.css" />
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.material.mobile.min.css" />

      <script src="https://kendo.cdn.telerik.com/2017.3.913/js/jquery.min.js"></script>
        <script src="https://kendo.cdn.telerik.com/2017.3.913/js/kendo.all.min.js"></script>--}}

    {{--<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>--}}

    {{--  <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.common.min.css"/>
      <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.rtl.min.css"/>
      <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.silver.min.css"/>
      <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.3.913/styles/kendo.mobile.all.min.css"/>
      <script src="https://kendo.cdn.telerik.com/2017.3.913/js/kendo.all.min.js"></script>
   --}}
    {!!Html :: script('js/customizedAngular/assessment-partial.js')!!}

    <script type="text/javascript">

        /*    $(document).on('show.bs.modal', '.modal', function () {
         var zIndex = 1040 + (10 * $('.modal:visible').length);
         $(this).css('z-index', zIndex);
         setTimeout(function() {
         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
         }, 0);
         });*/
        $(document).ready(function () {
            {{--    $( "#searchText" ).autocomplete({
                   source: "/api/GetElevenCargoDetails",
                   minLength: 3,
                   select: function(event, ui) {
                       $('#searchText').val(ui.item.value);
                   }
               });--}}





        });

        //below two linse code is for multiple modal and fixing scrollbar issue
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
        });
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
        var role_id = {!! json_encode(Auth::user()->role->id) !!};


    </script>


@endsection

@section('content')
    <div class="col-md-12 text-center" ng-app="assessmentApp" ng-controller="assessmentCtrl" ng-cloak>

        <input title="" id="mani_no" type="hidden" value="{{$mani_no}}">
        <input title="" id="partial_status" type="hidden" value="{{$partial_status}}">


        <div class="col-md-12 alert alert-warning" ng-if="listOfWarning.length>0">
            <p style="font-size: 12px" class="error" ng-repeat="warning in listOfWarning">
                <i class="fa fa-warning"></i>
                @{{warning}}
            </p>

        </div>

        <div class="col-md-12 alert alert-warning" ng-if="errorDuringCheckingManifest">
            <p class="error">
                <i class="fa fa-warning"></i>
                @{{errorDuringCheckingManifestTxt}}
            </p>

        </div>

        <div class="col-md-3" style="padding: 0">

            <button ng-if="manifestFound" type="button" ng-click="DocumentShow()"
                    data-toggle="modal"
                    data-target="#DocumentModal" class="btn btn-success" data-backdrop="static"
                    data-keyboard="false"
                    {{--ng-if="role_name != 'C&F'"--}}>
                Add Document
            </button>
        </div>


        <div class="col-md-4" style="">

            {{--<button type="button" class="btn btn-primary" ng-click="generatePDF()"--}}
            {{--ng-disabled="MNotFound || !searchText">--}}
            {{--<span class="fa fa-search"></span> Get Assessment--}}
            {{--</button>--}}

            <a href="/transshipment/assessment/partial-assessment-report/@{{ manifes_no }}/@{{ partial_status }}" ng-disabled="!manifes_no" target="_blank"
               class="btn btn-primary">
                Assessment Sheet
            </a>
        </div>
        <div class="col-md-1">
            <form action="{{ url('getAssessmentInvoicePDF') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <input ng-show="ff" class="form-control" value="@{{ searchText }}" type="text" name="manifest"
                       id="manifest">
                <button type="submit" ng-disabled="!searchText" class="btn btn-primary center-block"> Challan</button>
            </form>
        </div>
        <div class="col-md-1">
            <form action="{{route('gateout-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <input  ng-show="ff" class="form-control" value="@{{ searchText }}" type="text" name="manifest"
                       id="manifest"/>
                <button type="submit" ng-disabled="!searchText" class="btn btn-primary center-block">Gate Pass
                </button>
            </form>
        </div>

        <div class="col-md-12">
            <span ng-if="permissionError" class="error">@{{ permissionError }}</span>
            <span ng-if="MNotFound" class="error">Manifest Not Found! </span>
            <p class="ok" ng-if="previouAssValue">Previous Assessment Value:
                <b>@{{Math.ceil( previousAssementValue)}}</b>
            </p>

        </div>

        <br>


        <div class="col-md-12">
            <div class="col-md-4 col-md-offset-4">

                <label for="">Partial Delivery Date :</label>

                    <input type="text" ng-change="getPartialData(partial_delivery_dt)"  ng-model="partial_delivery_dt" name="delivery_dt" id="delivery_dt" class="form-control input-sm datePicker" placeholder="Select Partial Date" required>


            </div>
        </div>



        @include('shared/partial/assessment')


        <div class="col-md-12 text-center" ng-show="Manifest_id">

                 <span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                        <img src="{{ URL::asset('img/dataLoader.gif') }}" width="250" height="15"/>
                        <br/> Saving...!
                 </span>
            <div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Assessment Successfully Done!
            </div>

            <div id="saveError" class="col-md-12 alert alert-warning" ng-show="saveAttemptWithoutManifest">
                Please Search By Manifest No!
            </div>


                <button ng-click="saveAssessment()" class="btn btn-primary center-block">Save</button>

        </div>

        {{-- Document Modal Start--}}
        <div class="modal fade text-center" style="" id="DocumentModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content largeModal">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">
                            Add Document Against Manifest No. @{{ ManifestNo }}
                        </h4>
                        <div class="alert alert-success" id="savingSuccessDocumentData"
                             ng-hide="!savingSuccessDocumentData">@{{savingSuccessDocumentData}}</div>
                        <div class="alert alert-danger" id="savingErrorDocumentData"
                             ng-hide="!savingErrorDocumentData">@{{savingErrorDocumentData}}</div>
                    </div>
                    <div class="modal-body">
                        <form action="" name="DocumentForm" novalidate>
                            <table>
                                <tr>
                                    <th>Document Name:</th>
                                    <td>
                                        <textarea class="form-control" ng-model="document_name"></textarea>
                                    </td>
                                    <th style="padding-left: 10px;">Number of Document:</th>
                                    <td>
                                        <input type="number" class="form-control" name="number_of_document"
                                               ng-model="number_of_document" required>
                                        <span class="error"
                                              ng-show="DocumentForm.number_of_document.$invalid && submitDocument">Number of Document is required.</span>
                                    </td>
                                    <th style="padding-left: 10px;">Documnentation Charge:</th>
                                    <td>
                                        <input type="number" class="form-control" name="total_documentation_charge"
                                               ng-model="total_documentation_charge" ng-disabled="true">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button type="button" class="btn btn-success"
                                                ng-click="SaveDocumentetaionDetails()">Save
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <span class="text-center" ng-if="DocumentDataLoading">
                                            <img src="{{ URL::asset('img/dataLoader.gif') }}"width="250" height="15"/>
                                            <br/>Please wait!
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                ng-click="manifestSearch(manifes_no)">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Document Modal End --}}


    </div>
    {{--main duv END------}}

@endsection
