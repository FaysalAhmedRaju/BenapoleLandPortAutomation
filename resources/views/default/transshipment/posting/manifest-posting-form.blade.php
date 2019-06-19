@extends('layouts.master')
@section('title', 'Manifest Posting Form')

@section('script')
    {!!Html :: script('js/customizedAngular/transshipment/posting/posting.js')!!}
    <script type="text/javascript">
        
        var role_id = {!! json_encode(Auth::user()->role->id) !!};
    </script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
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

@section('content')
    <div class="col-md-12"  style="padding: 0;" ng-cloak=""  ng-app="manifestApp" ng-controller="manifestPostingController" ng-cloak>
        {{-- <div class="col-md-10 col-md-offset-1" style="padding-bottom: 30px;">
            <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#report">Report</button>
            <div id="report" class="collapse">
                <div class="col-md-4 col-md-offset-4" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                    <h4 class="text-center">Datewise Manifest Posting</h4>
                    <form action="{{ url('reportPostingPDF') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <table>
                                <br>
                                <tr>
                                    <th>Date:</th>
                                    <td>
                                        <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date">
                                    </td>
                                    <td style="padding-left: 10px;">
                                        <button type="submit" class="btn btn-primary center-block">GET REPORT</button>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                <br>
                    <div class="list-group text-center">
                        <a class="list-group-item" href="{{ url('truckEntryDoneButPostingBranchEntryNotDoneReport') }}" target="_blank">Truck Entry Done, But Posting Branch Entry Not Done Report</a>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-3 col-md-offset-4">
            <p style="color: red"  ><b>@{{ WeightEmty }}</b></p>
            <p style="color: green"><b>@{{ WeightDone }}</b></p>
            <p style="color: red" ><b>@{{ WeightTruckEmty }}</b></p>
            <p style="color: green" ><b>@{{ WeightTruckDone }}</b></p>
        </div>
        <div class="col-md-5" ng-show="cnf_posted_flag == 1">
            <span style="color: green;">Manifest Posted By @{{ org_name }}(CNF)</span>
        </div>
        <div class="col-md-5" ng-show="manifest_posted_done_flag == 1">
            <span style="color: green;">Manifest Posting updated by Posting Branch</span>
            
        </div>

        <div class="col-md-11 col-md-offset-1" style="padding-bottom: 30px;">
            <div class="col-md-3">
                {{--<br>--}}
                <form class="form-inline" ng-submit="search(ManifestNo)">
                    <div class="form-group">
                        {{--ng-click="search(ManifestNo)"--}}
                        <input type="text" name="ManifestNo" ng-model="ManifestNo" id="ManifestNo" class="form-control" placeholder="Search Manifest No" ng-keydown="keyBoard($event)">
                    </div>
                    <span class="error">@{{ searchNotFound }}</span>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('transshipment-posting-todays-manifest-posting-report') }}" target="_blank">
                    <button type="button" class="btn btn-primary">
                        <span class="fa fa-search"></span>Today's Manifest
                    </button>
                </a>
                <a href="/transshipment/posting/manifest-details-report/@{{ ManifestNo }}" target="_blank">
                    <button type="button" class="btn btn-primary" ng-disabled="reportByManifestBtn"><span
                                class="fa fa-search"></span>Manifest Details
                    </button>
                </a>
            </div>
            <div class="col-md-5">
                <form action="{{ route('transhipment-posting-date-wise-posting-report') }}" target="_blank" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text" class="form-control datePicker" name="from_date" id="from_date" placeholder="Select Date" ng-model="from_date">
                    </div>
                    <button type="submit" class="btn btn-primary" ng-disabled="!from_date">Get Report </button>
                    {{-- Add Bank Modal Button --}}
                            <button type="button" class="btn  btn-success pull-right" data-target="#addImporter" data-toggle="modal">+ Importer</button>

                            {{-- Add Bank Modal Button --}}
                </form>
            </div>
        </div>
        

        {{----------------------------------------------------------------------------------------------------------------------------------------}}
       {{-- col-md-offset-1--}}
        <div class="col-md-12 table-responsive {{--col-md-offset-1--}}" style="background-color: #f8f9f9; /*border-radius: 10px; padding: 5px 5px 5px 5px;*/">
            <h4 class="text-center ok">Manifest Posting (Transshipment)</h4>

            {{----------------------------------------Complete Form Inside The DIV----------------------------------------------------}}
            <form  name="postingform" id="postingform" novalidate>
            <table>
                {{--<tr>--}}
                    {{--<td colspan="6" class="text-center"  ng-if="selectedTruckNoShowDiv">--}}
                        {{--<p>Truck NO: @{{ truckNoEdit }}</p>--}}
                        {{--<span>@{{ truckNoEdit }} Is Selected.</span>--}}
                        {{--<p>WeighBridge Gross Weight : @{{ t_gweight_wbridge_truck }}</p>--}}
                        {{--<p>Posting Gross Weight : @{{ t_gweight }}</p>--}}
                        {{--<p>Difference: @{{ t_gweight_wbridge_truck - t_gweight }}</p>--}}
                    {{--</td>--}}
                {{--</tr>--}}

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" >Manifest NO:</th>
                    <td>

                        <input  ng-disabled="m_manifest"  style="width: 190px;" class="form-control" ng-model="m_manifest"  name="m_manifest" id="m_manifest" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Manifest NO">
                    </td>
                    {{--style="width: 190px;"--}}
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Manifest Date <span class="mandatory">*</span> :</th>
                    <td>

                        <input  style="width: 190px;"  type="text"  class="form-control datePicker" required ng-model="m_manifest_date" name="m_manifest_date" id="m_manifest_date"  placeholder="Manifest Date">
                        {{--class="form-control"--}}
                        {{--ng-hide="hidemanifestWhenUpdatebtnClick"--}}
                        {{--ng-disabled="m_manifest_date"--}}
                        <span class="error" ng-show="postingform.m_manifest_date.$invalid && submittedPostingForm">Manifest Date is required</span>

                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Marks & No:</th>
                    <td>
                        <input  style="width: 190px;" class="form-control"  ng-model="m_marks_no"  name="m_marks_no" id="m_marks_no" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Marks & No" >
                        {{--<span class="error" ng-show="postingform.m_marks_no.$invalid && submittedPostingForm">Marks & No is required</span>--}}
                       {{--<span class="error" ng-show="postingform.m_marks_no.$invalid && submittedPostingForm">Marks & No is required</span>--}}
                    </td>
                    {{--style="width: 120em;"--}}
                </tr>

                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
                {{--&nbsp;--}}
                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Description of Goods:</th>
                    <td>
                        {{--<input ng-disabled="m_good_id" style="width: 190px;"  class="form-control"  ng-model="m_good_id"
                               name="m_good_id" id="m_good_id" ng-hide="hidemanifestWhenUpdatebtnClick"
                               placeholder="Description of Goods">--}}

                        <tags-input ng-model="goods_id"
                                    max-tags="5" required="required"
                                    enable-editing-last-tag="true"
                                    display-property="cargo_name"
                                    placeholder="Type Goods Name"
                                    replace-spaces-with-dashes="false"
                                    add-from-autocomplete-only="false"
                                    on-tag-added="tagAdded($tag)"
                                    on-tag-removed="tagRemoved($tag)">

                            <auto-complete source="loadGoods($query)"
                                           min-length="0"
                                           debounce-delay="0"
                                           max-results-to-show="10">

                            </auto-complete>

                        </tags-input>
                        <span ng-show="submittedPostingForm && (!goods_id || goods_id.length==0)" class="error">Choose at least one goods!</span>
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Gross Weight <span class="mandatory">*</span> :</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control" required ng-model="m_gweight" name="m_gweight" id="m_gweight" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Gross Weight">
                        <span class="error" ng-show="postingform.m_gweight.$invalid && submittedPostingForm">Gross Weight is required</span>
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Net  Weight:</th>
                    <td>
                        <input type="text" style="width: 190px;"   ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="m_nweight" name="m_nweight" id="m_nweight" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Net  Weight">
                        {{--<span class="error" ng-show="postingform.m_nweight.$invalid && submittedPostingForm">Net Weight is required</span>--}}
                    </td>

                </tr>

                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">NO. of Packages <span class="mandatory">*</span> :</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control" required ng-model="m_package_no" name="m_package_no" id="m_package_no" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="NO. of Packages">

                        <span class="error" ng-show="postingform.m_package_no.$invalid && submittedPostingForm">NO. of Packages is required</span>
                    </td>



                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Package Type:<span class="mandatory">*</span></th>
                    <td>


                        <input required type="text" style="width: 190px;"   class="form-control"  ng-model="m_package_type" name="m_package_type" id="m_package_type" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Package Type">
                        {{--<button id="add" class="hidden btn btn-primary btn-xs">Add Package</button>--}}
                        <span class="error" ng-show="postingform.m_package_type.$invalid && submittedPostingForm">Package Type is required</span>
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">CNF Value <span class="mandatory">*</span> :</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control" required ng-model="m_cnf_value" name="m_cnf_value" id="m_cnf_value" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="CNF Value">

                        <span class="error" ng-show="postingform.m_cnf_value.$invalid && submittedPostingForm">CNF Value is required</span>
                    </td>
                </tr>



                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>

                <tr>
                    <th>Vat No.</th>
                    <td>
                       {{-- <label class="radio-inline">
                            <input ng-change="hideVatNOorImporterName()"  type="radio" ng-init="vat_no=1"  ng-model="vat_no" ng-checked="true"  value="1">VAT NO <span class="mandatory">*</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-change="hideVatNOorImporterName()" type="radio"  ng-model="vat_no"  value="0">Importer Name <span class="mandatory">*</span>
                        </label>--}}
                        <input style="width: 190px;" type="text" required  class="form-control"  ng-model="m_vat_id" name="m_vat_id" id="m_vat_id" {{--ng-blur="getVatsData()"--}}  placeholder="VAT No" >
                        <span class="error" ng-if="postingform.m_vat_id.$invalid && submittedPostingForm">Vat is required</span>
                        <p class="error" id="vat-not-found"></p>
                    </td>
                    <td style="padding-left: 30px">
                       <b>Importer Name:</b>
                    </td>

                    <td colspan="3">
                        <span id="importerNameLabel"></span>
                        <input type="text" ng-show="importerNameInput" name="importerNameLabelinput" id="importerNameLabelinput" class="form-control" ng-model="importerNameLabelinput">
                    </td>

                </tr>

                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>


              {{--  <tr>
                    --}}{{--<th ng-hide="hidemanifestWhenUpdatebtnClick">VAT No:</th>--}}{{--


                    --}}{{--<input type="text" ng-disabled="vatname" ng-disabled="manif_posted_btn_disable"  class="form-control" ng-model="m_vat_name" name="m_vat_name" id="m_vat_name" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Importer Name" >--}}{{--
                    <th --}}{{-- ng-hide="hidemanifestWhenUpdatebtnClick ||!m_vat_name"--}}{{-- ng-show="vat_no_after_Vat">Importer Name:</th>
                    <td colspan="3"  ng-hide="!m_vat_name" ng-show="vat_no_after_Vat">
                    <level>@{{ m_vat_name }}</level>
                    </td>



                        --}}{{--<input type="text"  class="form-control" ng-model="m_Vat_importer_NO" name="m_Vat_importer_NO" id="m_Vat_importer_NO"  placeholder="Importer Name" >--}}{{--

                    <th ng-show="imp_name_from_Importer">Importer Name:</th>
                    <td colspan="3" ng-show="imp_name_from_Importer" >
                    <level>@{{ ImorterName }}</level>
                    </td>
                </tr>--}}

                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Name & Address Exporter:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="m_exporter_name_addr" name="m_exporter_name_addr" id="m_exporter_name_addr" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Name & Address Exporter">

                        {{--<span class="error" ng-show="postingform.m_exporter_name_addr.$invalid && submittedPostingForm">Name & Address Exporter is required</span>--}}
                    </td>

                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">L.C No:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="m_lc_no" name="m_lc_no" id="m_lc_no" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="L.C No" >

                        {{--<span class="error" ng-show="postingform.m_lc_no.$invalid && submittedPostingForm">L.C No is required</span>--}}
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">L.C Date:</th>
                    <td>
                        <input type="text"  style="width: 190px;"  ng-disabled="manif_posted_btn_disable"  class="form-control datePicker" ng-model="m_lc_date" name="m_lc_date" id="m_lc_date" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="L.C Date">

                        {{--<span class="error" ng-show="postingform.m_lc_date.$invalid && submittedPostingForm">L.C Date is required</span>--}}
                    </td>
                </tr>

                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>

                <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Indian Bill NO:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="m_ind_be_no" name="m_ind_be_no" id="m_ind_be_no" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Indian Bill NO">

                        {{--<span class="error" ng-show="postingform.m_ind_be_no.$invalid && submittedPostingForm">Indian Bill NO is required</span>--}}
                    </td>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Indian Bill Date:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control datePicker"  ng-model="m_ind_be_date" name="m_ind_be_date" id="m_ind_be_date" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Indian Bill Date">

                        {{--<span class="error" ng-show="postingform.m_ind_be_date.$invalid && submittedPostingForm">Indian Bill Date is required</span>--}}
                    </td>
                    {{-- <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Posting Yard <span class="mandatory">*</span> :</th>
                    <td>
                        <select style="width: 190px;" class="selectpicker"  name="t_posted_yard_shed" ng-model="t_posted_yard_shed" required --}} {{-- ng-options="yard.id as yard.yard_shed_name for yard in allYardData" --}} {{-- ng-hide="hidemanifestWhenUpdatebtnClick" --}} {{-- ng-change="YardNOForLevelNO()" --}} {{-- multiple>
                            @foreach($yards as $k=>$v)
                                <option value="{{$v->id}}">{{$v->yard_shed_name}}</option>
                            @endforeach
                        </select>
                        <span class="error" ng-show="postingform.t_posted_yard_shed.$invalid && submittedPostingForm">Yard is required</span> --}}
                        {{-- &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<level><b style="color: green">@{{ message_1 }} @{{ message_2 }} @{{ yard_count_no }}</b></level> --}}
                    {{-- </td> --}}
                    <th ng-hide="hidemanifestWhenUpdatebtnClick" style="padding-left: 30px">Remark:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="remark" name="remark" id="remark" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Remark">
                    </td>
                </tr>

                {{-- <tr>
                    <td colspan="6">&nbsp;</td>
                </tr> --}}

                {{-- <tr>
                    <th ng-hide="hidemanifestWhenUpdatebtnClick">Remark:</th>
                    <td>
                        <input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control"  ng-model="remark" name="remark" id="remark" ng-hide="hidemanifestWhenUpdatebtnClick" placeholder="Remark">

                    </td>

                </tr> --}}




                {{--<tr>--}}

                    {{--<th ng-hide="showWhenUpdatebtnClick">Gross Weight</th >--}}
                    {{--<td>--}}
                        {{--<input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control" ng-model="t_gweight" name="t_gweight" id="t_gweight"  ng-hide="showWhenUpdatebtnClick" >--}}
                    {{--</td>--}}
                    {{--<th ng-hide="showWhenUpdatebtnClick">Net Weight</th>--}}
                    {{--<td>--}}
                        {{--<input type="text" style="width: 190px;"  ng-disabled="manif_posted_btn_disable" class="form-control" ng-model="t_nweight" name="t_nweight" id="t_nweight"  ng-hide="showWhenUpdatebtnClick" >--}}
                    {{--</td>--}}
                {{--</tr>--}}






                <tr>
                    <td colspan="6" class="text-center">
                        <br>
                        <button type="button" ng-click="save(postingform)" class="btn btn-primary"><span class="fa fa-file"></span> Save</button>

                        <p colspan="" class="ok" ng-show="saveSuccessManifiest"  >@{{ savingSuccess }}</p>
                        {{--<P ng-hide="showWhenUpdatebtnClick">@{{ success }} </P>--}}
                    </td>

                    {{--<td colspan="3" class="text-center">--}}
                        {{--<button  type="button" ng-click="selectItemsShow()" ng-hide="hidemanifestWhenUpdatebtnClick" data-toggle="modal"--}}
                                 {{--data-target="#MultipleItemsSelectModel" class="btn btn-primary" >Select Items--}}
                        {{--</button>--}}

                    {{--</td>--}}
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center" colspan="3">

                        <div id="error" class="col-md-12 alert alert-warning" ng-show="errorItemMsg">
                            @{{ ItemsChectMsg }}!
                        </div>
                    </td>
                    <td colspan="1"> </td>
                </tr>
                <tr>
                    <td colspan="0"></td>
                    <td class="text-center" colspan="3">

                        <div id="success" class="col-md-6 col-md-offset-4 alert alert-success" ng-show="successMsg">
                            Successfully @{{ successMsgTxt }}!
                        </div>

                        <div id="error" class="col-md-12 alert alert-warning" ng-show="errorMsg">
                            @{{ errorMsgTxt }}!
                        </div>

                    </td>
                    <td colspan="1"> </td>
                </tr>




            </table>
            </form>

        </div>
        {{-----------------------------------------------------------------------------------------------------------------------------------------------}}
        {{--</div>--}}

        <div class="clearfix"></div>
        <div class="col-md-12 ">
            <h4 class="text-center" style="color: black" ng-show="manifest_info"><b>Manifest Information: <i>@{{ m_manifest_show }}</i></b></h4>
        <table  class="table" {{--ng-repeat="truck in allManifestData"--}} {{--cellspacing="0" cellpadding="0"--}} {{--class="noBorder"--}} style="color: #8A6343;} " ng-show="manifest_info" >
            {{--<lavel>--}}
            <br>
                <tr  >
                    <div class="col-md-3" ng-show="manifest_info" >
                        <b>Manifest NO:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_manifest_show}}
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        <b>Manifest Date:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_manifest_date_show }}
                    </div>
                    {{--<th>Manifest NO:</th>--}}
                    {{--<td>@{{ m_manifest }}</td>--}}
                    {{--<th>Manifest Date:</th>--}}
                    {{--<td>@{{ m_manifest_date }}</td>--}}
                </tr>
            {{--</lavel>--}}
                {{--<br>--}}
{{--<lavel>--}}
            <tr>
                <br>
            </tr>
                <tr >
                    {{--<th>Gross Weight:</th>--}}
                    {{--<td>@{{ m_gweight }}</td>--}}
                    {{--<th>Packages No:</th>--}}
                    {{--<td>@{{ m_package_no }}</td>--}}
                    <div class="col-md-3" ng-show="manifest_info">
                        <b>   Gross Weight:</b>

                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_gweight_show }}
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        <b>Packages No:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_package_no_show }}
                    </div>

                </tr>
            <tr>
                <br>
            </tr>
{{--</lavel>--}}
                {{--<br>--}}
            {{--<lavel>--}}
                <tr >
                    {{--<th>CNF Value:</th>--}}
                    {{--<td>@{{ m_cnf_value }}</td>--}}
                    {{--<th>Importer Name:</th>--}}
                    {{--<td>@{{ m_vat_name }}</td>--}}
                    <div class="col-md-3" ng-show="manifest_info" >
                       <b>CNF Value:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_cnf_value_show }}
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        <b>Posting Yard:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info" >
                        @{{ t_posted_yard_shed_show }}
                    </div>
                </tr>
            <tr>
                <br>
            </tr>
            {{--</lavel>--}}

                {{--<br>--}}
            {{--<lavel>--}}
                <tr >

                    {{--<th>Posting Yard:</th>--}}
                    {{--<td>@{{ t_posted_yard_shed }}</td>--}}


                    <div class="col-md-3" ng-show="manifest_info">
                        <b>Importer Name:</b>
                    </div>
                    <div class="col-md-3" ng-show="manifest_info">
                        @{{ m_vat_name_show }}
                    </div>
                </tr>
            {{--</lavel>--}}






        </table>

        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 table-responsive">
            <h4 class="text-center" style="color: black" ng-show="textOfmanifest"><b>Truck Information</b></h4>


            <table class="table table-bordered" style="color: #8A6343" ng-show="table" >
                {{--<caption><h3><b>Please Insert other fields of Manifest Id:  @{{ManifestNo}}<b></h3></caption>--}}
                <thead >
                <tr>
                    {{--<th colspan="3" ></th>--}}
                    <td colspan="7" class="text-center" ng-if="dataLoading">
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="img/dataLoader.gif" width="300" height="20"/>
                            <br/>Loading Wait!
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Serial No</th>
                    <th>Truck NO</th>
                    <th>Driver Card No</th>
                    <th>Driver Name</th>
                    {{--<th>Posted Yard</th>--}}
                    {{--<th>Description of Goods</th>--}}
                    {{--<th>Action</th>--}}
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="truck in allManifestData"   ng-style="{'background-color':(truck.t_id == selectedStyle?'#dbd3ff':'')}">
                    <td>@{{$index+1}}</td>
                    <td>@{{truck.t_truck_type}}-@{{truck.t_truck_no}} </td>
                    <td>@{{truck.driver_card}}</td>
                    <td>@{{truck.driver_name | capitalize}}</td>
                    {{--<td>@{{truck.t_posted_yard_shed}}</td>--}}
                    {{--<td>@{{truck.cargo_name}}</td>--}}
                    {{--<td>--}}
                        {{--<a class="btn btn-primary" ng-click="edit(truck)" >Asign Yard</a>--}}
                    {{--</td>--}}
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>


        {{--=======================item select Modal===============================================================##########ITEMS#################=====--}}
        {{--<div class="modal fade text-center" style="left:0px; " id="MultipleItemsSelectModel" role="dialog">--}}

            {{--<div class="modal-dialog">--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<button class="close" data-dismiss="modal">&times;</button>--}}
                        {{--<h4 class="modal-title text-center">Add Multiple Items Against Manifest No. @{{ m_manifest }}</h4>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body col-md-12">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<th>Item Description:</th>--}}
                            {{--<td style="">--}}
                            {{--<select  style=""  class="form-control" name ="item_Code_id" ng-model="item_Code_id" id="--}}
                            {{--item_Code_id" ng-options="item.id as item.id+'-'+item.Description for item in allItemsGoodData">--}}
                                    {{--<option value="" type='checkbox'  >Select Goods Items</option>--}}
                                {{--</select>--}}
                                {{--<br>--}}
                            {{--</td>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-12">--}}
                            {{--<table style="width: 100%;">--}}
                                {{--<tr>--}}
                                    {{--<th>Weight:</th>--}}
                                    {{--<td><input type="text" ng-model="item_weight" class="form-control  input-sm" tabindex="2"></td>--}}
                                    {{--<th>Package:</th>--}}
                                    {{--<td><input type="text" ng-model="item_package" class="form-control  input-sm" tabindex="3"></td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                            {{--<br>--}}
                            {{--<button type="button" class="btn btn-primary center-block" ng-hide="updateBtnItems"  ng-click="addItems()">Add Item</button>--}}

                            {{--<p ng-show="saveSuccessItems" class="ok" >@{{ savingSuccessitems }}</p>--}}
                            {{--<button type="button" ng-click="updateitems(itemsData)" ng-show="updateBtnItems" class="btn btn-primary">Update Items</button>--}}
                            {{--<p class="ok" ng-show="updateSuccess">@{{ updateSuccessitems }}</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<div class="col-md-12"> --}}{{--data table--}}
                            {{--<table class="table table-bordered">--}}
                                {{--<tr>--}}
                                    {{--<th>S/L</th>--}}
                                    {{--<th>Item</th>--}}
                                    {{--<th>Weight</th>--}}
                                    {{--<th>Package</th>--}}
                                    {{--<th>Action</th>--}}
                                {{--</tr>--}}
                                {{--<tr ng-repeat="itemsData in allItemsData">--}}
                                    {{--<th>@{{ $index+1 }}</th>--}}
                                    {{--<td>@{{ itemsData.Description }}</td>--}}
                                    {{--<td>@{{ itemsData.item_package }}</td>--}}
                                    {{--<td>@{{ itemsData.item_weight }}</td>--}}
                                    {{--<td>@{{ itemsData. }}</td>--}}
                                    {{--<td>--}}
                                        {{--<button type="button" ng-click="editgoodsItems(itemsData)" class="btn btn-primary btn-xs">Edit</button>--}}
                                        {{--<button type="button" ng-click="deleteItems(itemsData)" class="btn btn-primary btn-xs">Delete</button>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                        {{--<div class="modal-footer">--}}
                            {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="modal fade text-center" style="left:0px; " id="MultipleItemsSelectModel" role="dialog">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Add Multiple Items Against Manifest No. @{{ ManifestNo }}</h4>
                    </div>

                    <div class="modal-body col-md-12">
                        <form action="" name="multiItemForm" novalidate>
                            <div class="col-md-6 col-md-offset-3">
                                <th><b>Item Description:</b></th>
                                <td>
                                    <select  style="" required="required" class="form-control" name ="item_Code_id" ng-model="item_Code_id" id="
                            item_Code_id" ng-options="item.id as item.id+'-'+item.Description for item in allItemsGoodData">
                                        <option value="" type='checkbox'>Select Goods Items</option>
                                    </select>
                                    <span class="error" ng-show="multiItemFormSubmit && !item_Code_id">
                                    Please Select Item!
                                </span>
                                    <br>
                                </td>
                            </div>
                            <div class="col-md-12">
                                <table style="width: 100%;">
                                    <tr>
                                        <th>Weight:</th>
                                        <td>
                                            <input type="number" required="required"  ng-model="item_weight" name="item_weight" class="form-control  input-sm" tabindex="2">
                                            <span class="error" ng-show="multiItemFormSubmit && !item_weight">
                                                Please Input Item Weight!
                                        </span>
                                        </td>
                                        <th>Package:</th>
                                        <td>
                                            <input type="number" required="required" ng-model="item_package" name="item_package" class="form-control  input-sm" tabindex="3">
                                            <span class="error" ng-show="multiItemFormSubmit && !item_package">
                                                Please Input Item Packages!
                                        </span>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <button type="button" class="btn btn-primary center-block" ng-hide="updateBtnItems"  ng-click="addItems(multiItemForm)">Add Item</button>

                                <button type="button" ng-click="updateitems(multiItemForm)" ng-show="updateBtnItems" class="btn btn-primary">Update Items</button>
                                <br>
                                <span ng-if="savingMultiItem" style="color:green; text-align:center; font-size:12px">
                                        <img src="img/dataLoader.gif" width="250" height="12"/>
                                        <br/> Saving...!
                            </span>

                                <div id="itemSuccess" class="col-md-12 alert alert-success text-center" ng-show="itemSuccessMsg">
                                    Successfully @{{itemSuccessMsgTxt}}!
                                </div>
                                <div id="itemError" class="col-md-12 alert alert-warning text-center" ng-show="itemErrorMsg">
                                    @{{itemErrorMsgTxt}}!
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12"> {{--data table--}}
                            <table class="table table-bordered  text-center-td-th" >
                                <tr>
                                    <td colspan="5" ng-if="data.dataLoading">
                                        <span style="color:green; text-align:center; font-size:20px">
                                            <img src="/Images/dataLoader.gif" width="350" height="20"/>
                                            <br/> Please wait! <br/>Data is loading...
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>S/L</th>
                                    <th>Item</th>
                                    <th>Weight</th>
                                    <th>Package</th>
                                    <th>Action</th>
                                </tr>
                                <tr ng-repeat="itemsData in allItemsData">
                                    <th>@{{ $index+1 }}</th>
                                    <td>@{{ itemsData.Description }}</td>
                                    <td>@{{ itemsData.item_weight}}</td>
                                    <td>@{{ itemsData.item_package  }}</td>
                                    {{--<td>@{{ itemsData. }}</td>--}}
                                    <td>
                                        <button type="button" ng-click="editgoodsItems(itemsData)" class="btn btn-primary btn-xs">Edit</button>
                                        <button type="button" ng-click="deleteItems(itemsData)" class="btn btn-primary btn-xs">Delete</button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

//        $(document).ready(function(){
//            $( "#m_Importer_Name").autocomplete({
//                source: "/api/GetVatNAMEDetails",
//                minLength: 3,
//                select: function(event, ui) {
//                    $('#m_Importer_Name').val(ui.item.id);
////                    $('#m_Vat_id').val(ui.item.id)
//                    console.log('ok')
//
//                    var vatId = ui.item.id;
//
//                    console.log(vatId);
//                }
//            });
//        });
    </script>

@endsection

{{--<th ng-show="importer_name_false">&nbsp;&nbsp;&nbsp;</th>--}}
{{--<td ng-show="importer_name_false">--}}
{{--<autocomplete options="AllVatsImpoerterNames" ng-model="searchTerm" required="required"--}}
{{--place-holder="Type Impoter Name"--}}
{{--on-select="onSelectSubHead"--}}
{{--display-property="NAME"--}}
{{--input-class="form-control input-sm"--}}
{{--clear-input="false">--}}
{{--</autocomplete>--}}
{{--</td>--}}
