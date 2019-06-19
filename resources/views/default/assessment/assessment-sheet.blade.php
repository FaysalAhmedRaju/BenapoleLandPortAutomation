@extends('layouts.master')
@section('title', 'Assessment')
@section('script')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}

    {!! Html::script('js/bootbox.min.js')!!}
    {!!Html :: script('js/customizedAngular/assessment/assessment.js')!!}

    {!! Html::style('/css/jquery.growl.css') !!}
    {!! Html::script('js/jquery.growl.js') !!}

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
    <style type="text/css">
        .largeModalForAssementPage {
            width: 800px;
        }

        .largeModalForHandlingWeight {
            width: 900px;
        }

        .largeModalForDeliverRequtions {
            width: 1100px;
        }
        .largeModalForGoodsModal {
            width: 1100px;
        }

        .selectedRow {
            background-color: #dbd3ff !important;
        }

        .ui-autocomplete {
            z-index: 2147483647;
        }
    </style>


@endsection

@section('content')
    <div class="col-md-12" ng-app="assessmentApp" ng-controller="assessmentCtrl" ng-cloak>
        @if(Auth::user()->role->id != 12)
            <div class="col-md-12 alert alert-warning" ng-if="assessmentApproved">
                <p class="error">This Assessment is already Approved!</p>
            </div>
        @endif

        <div class="col-md-12 alert alert-warning" ng-if="listOfWarning.length>0">
            <p style="font-size: 12px" class="error" ng-repeat="warning in listOfWarning">
                <i class="fa fa-warning"></i>
                @{{warning}}
            </p>

        </div>


        <div class="col-md-12 alert alert-warning" ng-if="errorDuringCheckingManifest">
            <p class="error">
                <i class="fa fa-warning"></i> @{{errorDuringCheckingManifestTxt}}
            </p>

        </div>


        <div class="col-md-4" style="padding: 0">
            <button ng-if="manifestFound" type="button" ng-click="selectItemsShow()" id="warehouseModal"
                    data-toggle="modal"
                    data-target="#MultipleItemsSelectModal" class="btn btn-primary btn-sm" data-backdrop="static"
                    data-keyboard="false"
                    {{--ng-if="role_name != 'C&F'"--}}>
                Warehouse Charge
            </button>
            <button ng-if="manifestFound" type="button" ng-click="DocumentShow()"
                    data-toggle="modal"
                    data-target="#DocumentModal" class="btn btn-success btn-sm" data-backdrop="static"
                    data-keyboard="false"
                    {{--ng-if="role_name != 'C&F'"--}}>
                Document Charge
            </button>
            @if(Auth::user()->role->id != 12)

                <button {{-- ng-if="manifestFound" --}} ng-disabled="!searchText" type="button"
                        ng-click="warehouseDeliveryModal()"
                        data-toggle="modal"
                        data-target="#WarehouseDelivery" class="btn btn-success btn-sm" data-backdrop="static"
                        data-keyboard="false">
                    Delivery
                </button>

            @endif

        </div>

        <div class="col-md-2" style="padding: 0">
            <form name="form" class="form-inline" novalidate ng-submit="manifestSearch(searchText, partial_status)">
                <div class="form-group">
                    <label for="searchText"> </label>

                    <input type="text"
                           ng-pattern="/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/"
                           required="required" ng-model="searchText" name="searchText" class="form-control input-sm"
                           ng-change="cleanPage()"
                           id="searchText" placeholder="Enter Manifest No." ng-keydown="keyBoard($event)"
                           ng-model-options="{allowInvalid : true}">

                    <br>
                    <span class="error" ng-show='form.searchText.$error.pattern'>
                        Input like: 256/12/2017 Or 256/A/2017
                    </span>
                    <span ng-if="customError" class="error">@{{ customError }}</span>
                    <p class="ok" ng-if="previouAssValue">Previous Assessment Value:
                        <b>@{{Math.ceil(previousAssementValue)}}</b>
                    </p>
                </div>
                <br>
                <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                            <img src="{{asset('/img/dataLoader.gif') }}" width="250" height="15"/>
                            <br/> Please wait!
                        </span>

            </form>
            <br>

        </div>
        <div class="col-md-2" style="">
            <select {{--  ng-if="partial_number_list.length>0" --}} title="e" style="width: 130px;" required="required"
                    class="form-control"
                    ng-change="get_partial(searchText,partial_status)"
                    name="partial_status"
                    ng-model="partial_status"
                    ng-options="item as item for item in partial_number_list">
                <option value="">Select Partial</option>
            </select>
        </div>

        <div class="col-md-2" style="">

            {{--<button type="button" class="btn btn-primary" ng-click="generatePDF()"--}}
            {{--ng-disabled="MNotFound || !searchText">--}}
            {{--<span class="fa fa-search"></span> Get Assessment--}}
            {{--</button>--}}

            <a href="/assessment/get-assessment-report/@{{ searchText }}/@{{ partial_status }}"
               ng-disabled="!searchText" target="_blank"
               class="btn btn-primary btn-sm">
                Assessment Sheet
            </a>
        </div>


        <div class="col-md-1">
            <form action="{{ route('assessment-get-assessment-invoice-report') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <input ng-show="ff" class="form-control" value="@{{ searchText }}" type="text" name="manifest"
                       id="manifest">
                <input type="hidden" name="partial_status_for_challan" value="@{{ partial_status }}">
                <button type="submit" ng-disabled="!searchText" class="btn btn-primary btn-sm"> Challan</button>
            </form>
        </div>
        <div class="col-md-1">
            <form action="{{ route('gateout-get-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <input ng-show="ff" class="form-control" value="@{{ searchText }}" type="text" name="manifest"
                       id="manifest"/>
                <input type="hidden" name="partial_status_for_gatepass" value="@{{ partial_status }}">
                <button type="submit" ng-disabled="!searchText" class="btn btn-primary btn-sm">Gate Pass
                </button>
            </form>
        </div>

        <br>

        @include('default/shared/assessment')


        <div class="col-md-12 text-center">

                 <span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                        <img src="img/dataLoader.gif" width="250" height="15"/>
                        <br/> Saving...!
                 </span>
            <br>

            <div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Assessment Successfully Done!
            </div>

            <div id="saveError" class="col-md-12 alert alert-warning" ng-show="saveAttemptWithoutManifest">
                Please Search By Manifest No!
            </div>

            <div id="assSaveError" ng-show="assSaveError"
                 class="col-md-8 col-md-offset-2 alert alert-danger text-center">
                @{{assSaveErrorMsgTxt}}
            </div>

            <div class="col-md-8 col-md-offset-2 text-center">

                @if(Auth::user()->role->id != 12)
                    <button ng-disabled="savingData" ng-if="manifestFound" ng-click="saveAssessment()"
                            class="btn btn-primary center-block">Save
                    </button>
                @else
                    <button ng-disabled="savingData" ng-click="saveAssessment()" class="btn btn-primary center-block">
                        Save
                    </button>
                @endif

            </div>

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
                                            <img src="img/dataLoader.gif" width="250" height="15"/>
                                            <br/>Please wait!
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                ng-click="manifestSearch(searchText)">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Document Modal End --}}
        {{--==============================Item wise selecttin modal=======================================--}}

        <div class="modal fade" style="" id="MultipleItemsSelectModal" role="dialog">

            <div class="modal-dialog">

                <div class="modal-content largeModal">

                    <div class="modal-header">
                        <button class="close" data-dismiss="modal"
                                ng-click="manifestSearch(searchText)">&times;</button>
                        <h4 class="modal-title text-center">
                            Add Multiple Items Against Manifest No. @{{ ManifestNo }}
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <b>Declare Weight :</b> @{{ grossWeight | number:2}}
                            </div>
                            <div class="col-md-3">
                                <b>Scale Weight :</b> @{{ netWeight | number:2 }}
                            </div>
                            <div class="col-md-3">
                                <b>Shed Weight :</b> @{{ shedWeight | number:2 }}
                            </div>
                            <div class="col-md-3">
                                <b>Yard Weight :</b> @{{ yardWeight | number:2 }}
                            </div>
                        </div>


                        <div class="col-md-10 col-md-offset-1" style="">
                            <button type="button" class="btn btn-info btn-sm center-block" data-toggle="modal"
                                    ng-click="showTariffData(yard_shed)"
                                    data-target="#GoodsSecondModal" {{--data-backdrop="static" data-keyboard="false"--}} >
                                See Tariff
                            </button>

                            <form name="multiItemForm" id="multiItemForm" role="form">

                                <div class="form-group">


                                    <div class="col-sm-6 col-md-4">
                                        <label for="item_search_id" class="control-label text-left">Goods:</label>
                                        <tags-input ng-model="item_search_id"
                                                    required="required"
                                                    max-tags="1"
                                                    enable-editing-last-tag="true"
                                                    display-property="Description"
                                                    placeholder="Type Goods Name"
                                                    replace-spaces-with-dashes="false"
                                                    add-from-autocomplete-only="false"
                                                    on-tag-added="tagAdded($tag)"
                                                    on-tag-removed="tagRemoved($tag)">

                                            <auto-complete source="loadItems($query)"
                                                           min-length="0"
                                                           debounce-delay="0"
                                                           max-results-to-show="10">

                                            </auto-complete>

                                        </tags-input>
                                        <span ng-show="multiItemFormSubmit && !item_search_id || item_search_id.length==0"
                                              class="error">Choose  Goods!</span>

                                        <span ng-show="multiItemFormSubmit && item_search_id.length>=2"
                                              class="error">Can't add more than one!</span>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label for="tarrif" class="control-label">Tarrif:</label>
                                        <select style=" " required="required" class="form-control input-sm"
                                                name="tariff_good_id"
                                                ng-model="tariff_good_id" id="tariff_good_id"
                                                ng-change="getGoodsCharge(tariff_good_id)"
                                                ng-options="item.tariff_id as item.tariff_good_name for item in tariff_goods_details">
                                            <option value="">Select Tarrif</option>
                                        </select>
                                        {{--  <input style="" type="number" required="required"
                                                 ng-model="item_quantity"
                                                 name="item_quantity" class="form-control  input-sm" tabindex="3"
                                                 placeholder="Quantity">--}}
                                        <span class="error" ng-show="multiItemFormSubmit && !goods_id">
                                            Please Select Tarrif!
                                    </span>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label for="item_type" class="control-label">Basis of Charges:</label>

                                        <select title="" style="" required="required" class="form-control  input-sm"
                                                name="item_type"
                                                tabindex="2" ng-model="item_type" ng-change="getPackageNumber(item_quantity)">
                                            <option value="1" selected="selected">Volumn</option>
                                            <option value="2">Unit</option>
                                            <option value="3">Package</option>
                                            <option value="4">Weight</option>
                                        </select>

                                        <span class="error" ng-show="multiItemFormSubmit && !item_type">
                                                Please Input Item Weight!
                                        </span>
                                    </div>


                                    <div class="clearfix"></div>


                                    <div class="col-sm-6 col-md-4">
                                        <label for="inputLabel3" class="control-label">Dangerous:</label>
                                        <br>
                                        <label class="radio-inline">
                                            <input type="radio" ng-init="dangerous=0" ng-model="dangerous"
                                                   value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="dangerous" ng-checked="true" value="0">No
                                        </label>

                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label for="inputLabel3" class="control-label">Quantity:</label>

                                        <input style="" type="number" required="required"
                                               ng-model="item_quantity"
                                               name="item_quantity" class="form-control  input-sm" tabindex="3"
                                               placeholder="Quantity">
                                        <span class="error" ng-show="multiItemFormSubmit && !item_quantity">
                                                Please Input Item Quantity!
                                        </span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <button type="button" class="btn btn-primary center-block" ng-hide="updateBtnItems"
                                        ng-click="addItems(multiItemForm)">Add Goods
                                </button>

                                <button type="button" ng-click="updateitems(multiItemForm)" ng-show="updateBtnItems"
                                        class="btn btn-primary">Update Goods
                                </button>
                            </form>

                        </div>


                        <div class="clearfix"></div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="center-block" ng-if="goods_charge_div"
                                 style="background-color: #58bdff;text-align: left; padding: 10px 20px; width: 380px; border-radius: 10px;">
                                <h4 style="text-align: center"><u>Tariff Details | @{{ yard_shed==0 ? 'Yard' : 'Shed' }}</u></h4>
                                <b>Duration And Charge:</b><br>
                                <span ng-repeat="slab in charge.split('|')">
                                        <span>@{{slab}}<br></span>
                                </span>
                                <b>Free Time:</b>
                                <span ng-if="tariff_good.free_flag==0" class="fa fa-close fa-lg"></span>
                                <span ng-if="tariff_good.free_flag==1" class="fa fa-check fa-lg"></span>
                                <br>
                            </div>
                            <br>

                            <div class="clearfix"></div>


                            <br>
                            <span ng-if="savingMultiItem" style="color:green; text-align:center; font-size:12px">
                                        <img src="img/dataLoader.gif" width="250" height="12"/>
                                        <br/> Saving...!
                         </span>

                            <div id="itemSuccess" class="col-md-12 alert alert-success text-center"
                                 ng-show="itemSuccessMsg">
                                Successfully @{{itemSuccessMsgTxt}}!
                            </div>
                            <div id="itemError" class="col-md-12 alert alert-warning text-center"
                                 ng-show="itemErrorMsg">
                                @{{itemErrorMsgTxt}}!
                            </div>
                            {{--</div>--}}
                        </div>
                    </div>
                    {{--modal-body--}}

                    <div class="modal-footer">
                        {{--data table--}}
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered  text-center-td-th">
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
                                    <th>Goods</th>
                                    <th>Shed/Yard</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Tarrif</th>
                                    <th>Action</th>
                                </tr>
                                <tr ng-repeat="itemsData in allItemsData">

                                    <th>@{{ $index+1 }}</th>
                                    <td>@{{ itemsData.Description }}</td>
                                    <td>@{{ itemsData.yard_shed==0 ? 'Yard' :'Shed' }}</td>
                                    <td>@{{ itemsData.item_type | item_type}}</td>
                                    <td>@{{ itemsData.item_quantity  }}</td>
                                    <td>@{{ itemsData.tariff_good }}</td>
                                    <td style="width: 120px;">
                                        <div class="btn-group">
                                            <button type="button" ng-click="ediItem(itemsData)"
                                                    class="btn btn-primary btn-xs">Edit
                                            </button>
                                            <button type="button" ng-click="deleteItems(itemsData)"
                                                    class="btn btn-danger btn-xs">Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    ng-click="manifestSearch(searchText)">Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{--another modal--}}

        <div id="GoodsSecondModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content largeModalForGoodsModal">

                    <div class="modal-body">
                        <span class="label label-primary">
                            @{{ yard_shed == 1 ? 'Shed Charge' : 'Yard Charge' }}
                        </span>

                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    {{--<label class="radio-inline">
                                        <input type="radio"  ng-model="dangerous" value="@{{ i.id }}"> @{{ i.Description }}
                                    </label>--}}

                                    <div style="text-align: left" class="radio"
                                         ng-repeat="i in tariff_goods_details">
                                        <label>
                                            <input type="radio" name="i" ng-change="getGoodsId(i.tariff_id)"
                                                   ng-model="$parent.model"
                                                   ng-value="i">
                                            <b> @{{i.tariff_good_name}}</b>
                                            <i> @{{'Free Time:'}}</i>
                                            <i style="color: lightgreen;" ng-if="i.free_flag == 1" class="fa fa-check fa-lg" aria-hidden="true"></i>
                                            <i style="color: lightblue;" ng-if="i.free_flag == 0" class="fa fa-close fa-lg" aria-hidden="true"></i>

                                        </label>
                                        <br>
                                        <span ng-if="yard_shed==0">
                                            @{{ i.yard_charge }} y
                                        </span>
                                        <span ng-if="yard_shed==1">
                                            @{{ i.shed_charge }} s
                                        </span>


                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" ng-click="closeGoodChargeModal()"
                                data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--another modal END --}}
        {{--another modal START --}}


        <div id="WarehouseDelivery" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content largeModalForDeliverRequtions">
                    <div class="modal-header">
                        <button type="button" class="close" ng-click="manifestSearch(searchText)"
                                data-dismiss="modal">
                            &times;
                        </button>
                        <span ng-show="deliveryDataLoading" style="color:green; text-align:center; font-size:15px">
                            <img src="{{URL::asset('/img/dataLoader.gif')}}" width="200" height="10"/>
                            <br/> Please wait!
                        </span>
                        <br>

                        <button type="button"
                                ng-click="warehouseDeliveryModal()"
                                class="btn btn-primary btn-xs">Reload
                        </button>

                        <span style="color:red; text-align:center; font-size:15px" ng-if="permissionError"
                              id="permissionError">
                            <h5>@{{ permissionError}}</h5>
                        </span>


                        <div class="col-md-12" style="box-shadow: 0 0 5px 1px darkgrey;"
                             ng-show="showManifestInfoDiv">
                            <div class="col-md-6 text-left">
                                <span><b>Manifest No.:</b><span> @{{ GetManiNo }}</span></span>
                            </div>
                            <div class="col-md-6  text-left">
                                <span><b>Importer Name: </b> @{{ ImporterName }}</span>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-6 text-left">
                                <span><b>Manifest G. Weight: </b> @{{ GetManiGWeight }}</span>
                            </div>
                            <div class="col-md-6 text-left">
                                <span><b>WeighBridge Weight: </b> @{{ weigh_bridge_net_weight }}</span>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-6 text-left">
                                <span><b>Receive Weight: </b> @{{ receive_weight }}</span>
                            </div>

                            <div class="col-md-6 text-left">
                                <span><b>Poseted Yard/Shed: </b> @{{ posted_yard_shed }}</span>
                            </div>
                        </div>
                    </div>


                    <div class="modal-body">
                        <form name="dRForm" id="dRForm" novalidate>
                            <table style="width: 100%;">

                                <tr>
                                    <td class="text-center" colspan="6">
                                        <h4 class="ok">Warehouse Delivery Request </h4>
                                        <br>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="6">
                                        &nbsp;
                                    </td>
                                </tr>

                                <tr>
                                    <th>B/E No<span class="mandatory">*</span> :</th>
                                    <td>
                                        <input type="text" ng-model="be_no" name="be_no" id="be_no"
                                               class="form-control input-sm"
                                               placeholder="B/E No." required>
                                        <span class="error" ng-show="dRForm.be_no.$invalid && submitted">
                                          B/E No is required
                                    </span>
                                    </td>
                                    <th>&nbsp; B/E Date<span class="mandatory">*</span> :</th>
                                    <td>
                                        <input type="text" ng-model="be_date" required="required" name="be_date"
                                               id="be_date"
                                               class="form-control datePicker input-sm" placeholder="B/E date"
                                               required>
                                        <span ng-show="dRForm.be_date.$invalid && submitted"
                                              class="error">Select a date</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">&nbsp;
                                    </td>
                                </tr>


                                <tr>

                                    <th>Custom Release Order No<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" ng-model="custom_release_order_no"
                                               name="custom_release_order_no"
                                               id="custom_release_order_no" class="form-control input-sm"
                                               placeholder="Custom Release Order No" required>
                                        <span class="error"
                                              ng-show="dRForm.custom_release_order_no.$invalid && submitted">
                                          Custom Release Order No is required
                                    </span>
                                    </td>
                                    <th>&nbsp; Custom Release Order Date<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" ng-model="custom_release_order_date"
                                               name="custom_release_order_date"
                                               id="custom_release_order_date"
                                               class="form-control input-sm datePicker"
                                               placeholder="Custom Release Order Date"
                                               required>
                                        <span class="error"
                                              ng-show="dRForm.custom_release_order_date.$invalid && submitted">
                                     Custom Release Order Date is required
                                    </span>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>
                                <tr>
                                    {{-- <th>Paid Tax : </th> --}}
                                    <th>AIN No<span class="mandatory">*</span> :</th>
                                    <td>
                                        <input type="text" ng-model="ain_no" name="ain_no" id="m_Importer_Name"
                                               class="form-control input-sm" placeholder="AIN No" required>
                                        <span class="error" ng-show="dRForm.ain_no.$invalid && submitted">
                                      AIN No is required
                                    </span>
                                    </td>
                                    <th>&nbsp; C&F Name<span class="mandatory">*</span> :</th>
                                    <td>
                                        {{-- <input type="text"  ng-model="paid_date"  name="paid_date" id="paid_date" class="form-control datePicker input-sm" placeholder="Paid Date"> --}}
                                        <input type="text" ng-model="cnf_name" ng-disabled="cnfNameDisable"
                                               name="cnf_name"
                                               id="cnf_name" class="form-control input-sm" placeholder="C&F Name"
                                               required>

                                        {{-- <span class="error" ng-show="dRForm.paid_date.$touched && !paid_date">
                                              Paid Date is required
                                            </span> --}}
                                        <span class="error" ng-show="dRForm.cnf_name.$invalid && submitted">
                                          C&F Name is required
                                        </span>
                                    </td>


                                </tr>

                                <tr>
                                    <td colspan="4">
                                        <hr style="border-width: 2px;">
                                    </td>
                                </tr>


                                <tr>
                                    <th>Carpenter Packages :</th>

                                    <td>
                                        <input type="number" ng-model="carpenter_packages" name="carpenter_packages"
                                               id="carpenter_packages"
                                               class="form-control input-sm" placeholder="Packages No">

                                    </td>
                                    <th>&nbsp; Repair Packages :</th>
                                    <td>
                                        <input type="number" ng-model="carpenter_repair_packages"
                                               name="carpenter_repair_packages"
                                               id="carpenter_repair_packages" class="form-control input-sm"
                                               placeholder="Carpenter Repair Packages No">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;
                                    </td>
                                </tr>

                                <tr>

                                    <th>Delivery Date<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" ng-model="approximate_delivery_date"
                                               name="approximate_delivery_date"
                                               id="approximate_delivery_date"
                                               class="form-control input-sm datePicker" placeholder="Delivery Date"
                                               required ng-disabled="delivery_date">
                                        <span class="error"
                                              ng-show="dRForm.approximate_delivery_date.$invalid && submitted">
                                            Delivery Date is required
                                        </span>
                                    </td>
                                    <th>&nbsp; Loading Type<span class="mandatory">*</span>:</th>
                                    <td>

                                        <select title="" class="form-control input-sm"
                                                ng-change="changeAapproximateDeliveryType(approximate_delivery_type)"
                                                ng-model="approximate_delivery_type"
                                                ng-init="approximate_delivery_type='0'">

                                            <option value="0">Labour</option>
                                            <option value="1">Equipment</option>
                                            <option value="2">Both</option>
                                            <option value="3">Self</option>
                                            <option value="4">None</option>

                                        </select>
                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>

                                <tr>
                                    <th>Labour Weight:</th>
                                    <td>
                                        <input type="number" ng-model="approximate_labour_load"
                                               name="approximate_labour_load"
                                               class="form-control input-sm" placeholder="Type Appx. Labour Weight"
                                               ng-required="labourWeightMust"
                                               ng-disabled="equipmentWeightMust && !(labourWeightMust && equipmentWeightMust) || (!labourWeightMust && !equipmentWeightMust)">
                                        <span class="error"
                                              ng-show="dRForm.approximate_labour_load.$invalid && submitted">
                                            Approximate Labour Weight
                                        </span>
                                    </td>
                                    <th>&nbsp;Equipment Weight:</th>
                                    <td>
                                        <input type="number" ng-model="approximate_equipment_load"
                                               name="approximate_equipment_load"
                                               class="form-control input-sm" placeholder="Type Appx. Equipment Load"
                                               ng-required="equipmentWeightMust"
                                               ng-disabled="labourWeightMust && !(labourWeightMust && equipmentWeightMust) || (!labourWeightMust && !equipmentWeightMust)"/>
                                        <span class="error"
                                              ng-show="dRForm.approximate_equipment_load.$invalid && submitted">
                                            Approximate Equipment Weight
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>

                                <tr>
                                    <th>Transport Type<span class="mandatory">*</span>:</th>
                                    <td style="text-align: left">
                                        <select class="form-control input-sm"
                                                ng-change="changeApprxTransportFlag(local_transport_type)"
                                                ng-model="local_transport_type" ng-init="local_transport_type='0'">
                                            <option value="0">Truck</option>
                                            <option value="1">VAN</option>
                                            <option value="2">Self</option>
                                            <option value="3">Both</option>
                                        </select>
                                    </td>

                                    <th>&nbsp;Gate Pass No{{--<span class="mandatory">*</span> --}}:</th>
                                    <td>
                                        <input type="text" ng-model="gate_pass_no" ng-disabled="gate_pass"
                                               name="gate_pass_no"
                                               id="gate_pass_no" class="form-control input-sm"
                                               placeholder="Gate Pass No" {{--required--}}>
                                        {{--<span class="error" ng-show="dRForm.gate_pass_no.$invalid && submitted">--}}
                                        {{--Gate Pass No is required--}}
                                        {{--</span>--}}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>

                                <tr>
                                    <th>Transport Truck:</th>
                                    <td>
                                        <input type="number" ng-model="transport_truck" name="transport_truck"
                                               id="transport_truck" class="form-control input-sm"
                                               placeholder="Transport Truck" ng-required="!transportTruckMust"
                                               ng-disabled="transportTruckMust"/>
                                        <span class="error" ng-show="dRForm.transport_truck.$invalid && submitted">
                                          Transport Truck is required
                                        </span>
                                    </td>
                                    <th>&nbsp;Transport VAN:</th>
                                    <td>
                                        <input type="number" ng-model="transport_van" name="transport_van"
                                               id="transport_van" class="form-control input-sm"
                                               placeholder="Transport VAN" ng-required="!transportVanMust"
                                               ng-disabled="transportVanMust"/>
                                        <span class="error" ng-show="dRForm.transport_van.$invalid && submitted">
                                            Transport VAN is required
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>


                                <tr>

                                    <th>BD Weighment:</th>
                                    <td>
                                        <input type="number" ng-model="bd_weighment" name="bd_weighment"
                                               class="form-control input-sm"
                                               placeholder="BD Weighment" {{--ng-required="!chassis_transport"--}}/>
                                        {{--<span class="error"--}}
                                        {{--ng-show="dRForm.bd_weighment.$invalid && submitted">--}}
                                        {{--BD Weighment is required--}}
                                        {{--</span>--}}
                                    </td>
                                    <th {{--style="padding-left: 15px;"--}}>&nbsp;Shifting:</th>
                                    <td>
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="shifting_flag" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" ng-model="shifting_flag" ng-init="shifting_flag='0'"
                                                   ng-checked="true" value="0">No
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>

                                <tr>

                                    <th>BD Haltage:</th>
                                    <td>
                                        <input type="number" ng-model="bd_haltage" name="bd_haltage"
                                               class="form-control input-sm"
                                               placeholder="BD Haltage"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;

                                    </td>
                                </tr>

                                <tr>
                                    <td></td>

                                    <td style="/*background-color: yellow;*/ padding-left: 100px">
                                        <br>

                                        <button style="" type="button" ng-click="saveDeliveryData(dRForm)"
                                                ng-if="!updateBtn"
                                                {{-- ng-disabled="!be_no ||!be_date||!ain_no||!cnf_name||!no_del_truck" --}}
                                                class="btn btn-primary center-block">{{--<span class="fa fa-file"></span>--}}
                                            <span
                                                    {{--id="saveManifestDataBtn"--}}>Save Request</span>
                                        </button>
                                        <button type="button" ng-click="updateDeliveryData(dRForm)"
                                                ng-if="updateBtn"
                                                class="btn btn-primary center-block">
                                            Update Request
                                        </button>
                                        <br>

                                    </td>

                                    {{-- <td></td>

                                    <td>
                                        <a class="btn btn-success btn-sm text-right" href="{{url('/warehouse/delivery/delivery-local-transport-delivery-form')}}/@{{GetManiNo }}">
                                            <i class="fa fa-road"></i>Delivery
                                        </a>
                                    </td> --}}

                                </tr>

                                <tr>
                                    <td class="text-center" colspan="6">

                                        <div id="maniBEsuccessmsg" class="col-md-12 alert alert-success"
                                             ng-show="maniBEsuccessmsg">
                                            Successfully @{{ SuccessMessage }}
                                        </div>
                                        <div id="maniBEerrormsg" class="col-md-12 alert alert-danger"
                                             ng-show="maniBEerrormsg">
                                            @{{message}}
                                        </div>
                                        <div id="errormsgdiv" class="col-md-12 alert alert-danger"
                                             ng-show="errormsgdiv">
                                            @{{errormsg}}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <div id="manifestDetails" ng-hide="todaysEntryDiv">
                            <h4 class="text-center ok">Delivery Request Details:</h4>


                            <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                                <thead>

                                <tr>
                                    <td colspan="14" class="text-center" ng-if="manifestDataLoading">
                                    <span style="color:green; text-align:center; font-size:15px">
                                        <img src="{{URL::asset('/img/dataLoader.gif')}}" width="250" height="15"/>
                                        <br/> Please wait!
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="14" class="text-center" ng-if="manifestDataLoadingError">
                                    <span style="color:red; text-align:center; font-size:15px">
                                        <p>@{{ searchTextNotFoundTxt }} Couldn't found</p>
                                    </span>
                                    </td>
                                    <td colspan="14" class="text-center" ng-if="permissionError"
                                        id="permissionError">
                                    <span style="color:red; text-align:center; font-size:15px">
                                        <p>@{{ permissionError}}</p>
                                    </span>
                                    </td>
                                </tr>
                                <tr>

                                    <th>S/L</th>
                                    <th>Carpenter Packages</th>
                                    <th>Delivery Date</th>
                                    <th>Loading Type</th>
                                    <th>Labour Weight</th>
                                    <th>Equipment Weight</th>
                                    <th>Transport Type</th>
                                    <th>Gate Pass No</th>
                                    <th>Transport Truck</th>
                                    <th>Transport VAN</th>
                                    <th>BD Weighment</th>
                                    <th>Shifting</th>
                                    <th>BD Haltage</th>
                                    <th>Action</th>

                                </tr>
                                </thead>

                                <tbody>

                                <tr dir-paginate="data in allData|itemsPerPage:10">
                                    <td>@{{$index+1}}</td>
                                    <td>@{{data.carpenter_packages}}</td>
                                    <td>@{{data.approximate_delivery_date}}</td>
                                    <td>@{{data.approximate_delivery_type | loading}}</td>
                                    <td>@{{data.approximate_labour_load}}</td>
                                    <td>@{{data.approximate_equipment_load}}</td>
                                    <td>@{{data.local_transport_type |transportTypeFilter }}</td>
                                    <td>@{{data.gate_pass_no}}</td>
                                    <td>@{{data.transport_truck}}</td>
                                    <td>@{{data.transport_van}}</td>
                                    <td>@{{ data.bd_weighment }}</td>
                                    <td>@{{ data.m_shifting_flag | shifting_flag }}</td>
                                    <td>@{{ data.bd_haltage }}</td>
                                    <td style="text-align: center">
                                        <a class="btn btn-primary btn-md" ng-click="edit(data)"
                                           data-toggle="modal">Edit</a>
                                    </td>

                                </tr>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <td colspan="14" class="text-center">

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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" ng-click="manifestSearch(searchText)"
                                data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--another modal END --}}


        {{--Haltage charge change sTART haltage-charge--}}
        <div class="modal fade text-center" style="" id="haltageChargeChangeModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content largeModalForAssementPage">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">
                            Change Haltage Charge Against Manifest No. @{{ ManifestNo }}
                        </h4>
                        {{--<div class="alert alert-success" id="savingSuccessDocumentData"
                             ng-hide="!savingSuccessDocumentData">@{{savingSuccessDocumentData}}</div>
                        <div class="alert alert-danger" id="savingErrorDocumentData"
                             ng-hide="!savingErrorDocumentData">@{{savingErrorDocumentData}}</div>--}}
                    </div>
                    <div class="modal-body">

                        <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                            <thead>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <span ng-if="changing_haltage_charge_flag"
                                          style="color:green; text-align:center; font-size:15px">
                                        <img src="img/dataLoader.gif" width="160" height="10"/>
                                        <br/> Please wait!
                                    </span>

                                    <span ng-if="trucks_loading_for_changes_haltage"
                                          style="color:green; text-align:center; font-size:15px">
                                        <img src="img/dataLoader.gif" width="160" height="10"/>
                                        <br/> Please wait!
                                    </span>

                                    <div id="haltage-charge-success" class="col-md-12 alert alert-success"
                                         ng-show="haltage_charge_success_div">
                                        @{{haltage_charge_changes_success}}
                                    </div>
                                    <div id="haltage-charge-error" class="col-md-12 alert alert-danger"
                                         ng-show="haltage_charge_error_div">
                                        @{{haltage_charge_changes_error_txt}}
                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <th>S/L</th>
                                <th>Truck No.</th>
                                <th>Driver Card</th>
                                <th>Entry Date</th>
                                <th>Receive Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>

                            <tr ng-repeat="truck in allTrucksData">

                                <td>@{{$index+1}}</td>
                                <td>@{{truck.truck_type}}-@{{truck.truck_no}} </td>
                                <td>@{{truck.driver_card}}</td>
                                <td>@{{ truck.truckentry_datetime | stringToDate:'dd.MM.y HH:mm:ss' }}</td>
                                <td>@{{ truck.unload_receive_datetime != null ? (truck.unload_receive_datetime | stringToDate:'dd.MM.y HH:mm:ss') : 'Truck To Truck' }}</td>
                                <td style="width: 150px; text-align: center">
                                    <div class="btn-group">
                                        <button ng-click="changeHaltageFlagStatus(truck.id,0)" type="button"
                                                ng-disabled="!truck.holtage_charge_flag"
                                                class="btn btn-success btn-xs">
                                            Unpaid
                                        </button>
                                        <button ng-click="changeHaltageFlagStatus(truck.id,1)" type="button"
                                                ng-disabled="truck.holtage_charge_flag" class="btn btn-info btn-xs">
                                            Paid
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            </tbody>

                            <tfoot>

                            <tr>
                                <td colspan="6">
                                    <div ng-if="trucks_loading_for_changes_haltage_error"
                                         class="alert alert-danger">

                                       <span class="alter">
                                           Error occured while data loading!
                                       </span>
                                        <button ng-click="getTrucksForHaltageChargeChange()" type="button"
                                                class="btn btn-info btn-xs">
                                            Reload
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tfoot>


                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                ng-click="manifestSearch(searchText)">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Haltage charge change Modal End --}}

        {{--Handling Weight Modal--}}
        <div class="modal fade text-center" style="" id="handlingWeightChangeModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content largeModalForHandlingWeight">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">
                            Change Handling Weights Against Manifest No. @{{ ManifestNo }}
                        </h4>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <ul class="list-group text-left text-bold">
                                    <li class="list-group-item active text-center">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 text-bold">Weights</h5>
                                            <small>(In Ton)</small>
                                        </div>
                                    </li>
                                    <li class="list-group-item list-group-item-warning">
                                        Maximum: @{{chargeable_weight}}</li>
                                    <li class="list-group-item list-group-item-info">Gross: @{{gross_weight}}</li>
                                    <li class="list-group-item list-group-item-success">
                                        Weighbridge: @{{weighbridge_weight}}</li>
                                    <li class="list-group-item list-group-item-danger">Unload</li>
                                    <li class="list-group-item">
                                        &nbsp;&nbsp;&nbsp;-Labour: @{{handling.labor_unload}}</li>
                                    <li class="list-group-item">
                                        &nbsp;&nbsp;&nbsp;-Equipment: @{{handling.equip_unload}}</li>
                                    <li class="list-group-item list-group-item-danger">Load</li>
                                    <li class="list-group-item">
                                        &nbsp;&nbsp;&nbsp;-Labour: @{{handling.labor_load}}</li>
                                    <li class="list-group-item">
                                        &nbsp;&nbsp;&nbsp;-Equipment: @{{handling.equip_load}}</li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="text-right">
                                    <button type="button" class="btn btn-success" ng-click="getHandlingWeight()">
                                        Reload
                                    </button>
                                </div>
                                <br/>
                                <ul class="list-group text-left text-bold">
                                    <li class="list-group-item list-group-item-info text-center">Unload</li>
                                    <li class="list-group-item list-group-item-warning">
                                        <div class="input-group">
                                            <span class="input-group-addon">Manual&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <input type="number" class="form-control" name="OffloadLabourModal"
                                                   ng-model="OffloadLabourModal" ng-change="getChangedWeight()">
                                            <span class="input-group-addon"{{--  ng-show="OffloadLabourModal" --}}>&nbsp;&nbsp;X&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="OffloadLabourModal" --}}>&nbsp;&nbsp;@{{OffloadLabourChargeModal}}
                                                &nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="OffloadLabourModal" --}}>&nbsp;&nbsp;=&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="OffloadLabourModal" --}}>&nbsp;&nbsp;@{{TotalForOffloadLabourModal}}
                                                &nbsp;&nbsp;</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon">Equipment&nbsp;&nbsp;</span>
                                            <input type="number" class="form-control" name="OffLoadingEquipModal"
                                                   ng-model="OffLoadingEquipModal" ng-change="getChangedWeight()">
                                            <span class="input-group-addon">
                                                <label><input type="checkbox" ng-model="unloadingShiftingModal"
                                                              value="unloadingShiftingModal" aria-label=""
                                                              ng-disabled="!OffLoadingEquipModal"
                                                              ng-change="getChangedWeight()">Shift</label>
                                            </span>
                                            <span class="input-group-addon"{{--  ng-show="OffLoadingEquipModal" --}}>&nbsp;&nbsp;X&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="OffLoadingEquipModal" --}}>&nbsp;&nbsp;@{{OffLoadingEquipChargeModal}}
                                                <span ng-if="unloadingShiftingModal" ng-show="OffLoadingEquipModal">&nbsp;X&nbsp;2</span>
                                                &nbsp;&nbsp;
                                            </span>
                                            <span class="input-group-addon"{{--  ng-show="OffLoadingEquipModal" --}}>&nbsp;&nbsp;=&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="OffLoadingEquipModal" --}}>&nbsp;&nbsp;@{{TotalForOffloadEquipModal}}
                                                &nbsp;&nbsp;</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">&nbsp;</li>
                                    <li class="list-group-item list-group-item-success text-center">Load</li>
                                    <li class="list-group-item list-group-item-warning">
                                        {{-- <div class="input-group">
                                            <span class="input-group-addon">Mode&nbsp;&nbsp;</span>
                                           <span class="input-group-addon">&nbsp;&nbsp;Ton&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                           <span class="input-group-addon">&nbsp;&nbsp;Rate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                           <span class="input-group-addon">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </div> --}}
                                        <div class="input-group">
                                            <span class="input-group-addon">Manual&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <input type="number" class="form-control" name="loadLabourModal"
                                                   ng-model="loadLabourModal" ng-change="getChangedWeight()">
                                            <span class="input-group-addon"{{--  ng-show="loadLabourModal" --}}>&nbsp;&nbsp;X&nbsp;&nbsp;</span>
                                            <span class="input-group-addon">&nbsp;&nbsp;@{{loadLabourChargeModal}}
                                                &nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="loadLabourModal" --}}>&nbsp;&nbsp;=&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="loadLabourModal" --}}>&nbsp;&nbsp;@{{TotalForloadLabourModal}}
                                                &nbsp;&nbsp;</span>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">Equipment&nbsp;&nbsp;</span>
                                            <input type="number" class="form-control" name="loadingEquipModal"
                                                   ng-model="loadingEquipModal" ng-change="getChangedWeight()">
                                            <span class="input-group-addon">
                                                <label><input type="checkbox" ng-model="loadShiftingModal"
                                                              value="loadShiftingModal" aria-label=""
                                                              ng-disabled="!loadingEquipModal"
                                                              ng-change="getChangedWeight()">Shift</label>
                                            </span>
                                            <span class="input-group-addon"{{--  ng-show="loadingEquipModal" --}}>&nbsp;&nbsp;X&nbsp;&nbsp;</span>
                                            <span class="input-group-addon" {{-- ng-show="loadingEquipModal" --}}>&nbsp;&nbsp;@{{loadingEquipChargeModal}}
                                                <span ng-if="loadShiftingModal" ng-show="loadingEquipModal">&nbsp;X&nbsp;2</span>
                                                &nbsp;&nbsp;
                                            </span>

                                            <span class="input-group-addon"{{--  ng-show="loadingEquipModal" --}}>&nbsp;&nbsp;=&nbsp;&nbsp;</span>
                                            <span class="input-group-addon"{{--  ng-show="loadingEquipModal" --}}>&nbsp;&nbsp;@{{TotalForloadEquipModal}}
                                                &nbsp;&nbsp;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                ng-click="assigntoHandlingCharge()">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--Handling Weight Modal End--}}


    </div>
    {{--main duv END------}}
    <script>

        /*
         $('#openBtn').click(function(){
         $('#myModal').modal({show:true})
         });*/


        /*   $(document).on('show.bs.modal', '.modal', function (event) {
         var zIndex = 1040 + (10 * $('.modal:visible').length);
         $(this).css('z-index', zIndex);
         setTimeout(function() {
         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
         }, 0);
         });*/
    </script>


@endsection
