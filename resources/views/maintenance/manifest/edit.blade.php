@extends('layouts.master')

@section('title', $viewTitle)

@section('script')

    <script>

        var app = angular.module('maintananceMnifestEditApp', [ 'ngTagsInput', 'customServiceModule']);
        app.controller('maintananceMnifestEditController', function ($scope, $http, manifestService, $filter, enterKeyService) {

            $scope.goods_id = goods;
            $scope.loadGoods = function ($query) {
                console.log($query)
                return $http.get('/truck/api/get-goods-details/' + $query)
                        .then(function (response) {
                            // console.log(response);
                            var cargo_names = response.data;
                            return cargo_names.filter(function (v) {
                                return v.cargo_name
                                //return v.cargo_name.toLowerCase().indexOf($query.toLowerCase()) != -1;
                            });
                        }).catch(function (r) {
                            console.log(r)
                            if (r.status == 401) {
                                $.growl.error({message: r.data});
                            } else {
                                $.growl.error({message: "It has Some Error!"});
                            }
                        })

            };



            $scope.log = [];
            $scope.tagAdded = function (item) {
                console.log($scope.goods_id);
                var goods_array = [];
                var new_goods_array = [];
                angular.forEach($scope.goods_id, function (v, k) {
                    // console.log(v.id==undefined)
                    if (v.id == undefined) {
                        new_goods_array.push(v.cargo_name)
                    } else {
                        goods_array.push(v.id)
                    }
                });

                var all_goods_id = goods_array.join();
                var all_new_goods_name = new_goods_array;

                $("#goods-array").val(all_goods_id);
            };
            $scope.tagAdded();

            $scope.tagRemoved = function (item) {
                var goods_array = [];
                var new_goods_array = [];
                angular.forEach($scope.goods_id, function (v, k) {
                    // console.log(v.id==undefined)
                    if (v.id == undefined) {
                        new_goods_array.push(v.cargo_name)
                    } else {
                        goods_array.push(v.id)
                    }
                });
                var all_goods_id = goods_array.join();
                var all_new_goods_name = new_goods_array;
                $("#goods-array").val(all_goods_id);
            };

        })
        var manifest_id1 = {!! json_encode($theManifest->id) !!};
        var goods = {!! json_encode($goods) !!};

        $(document).ready(function(){
            $( "#vatreg_bin_no").autocomplete({
                source: "{{route('posting-get-vat-name-details-api')}}",
                minLength: 2,
                highlightItem: true,
                select: function(event, ui) {
                    console.log(ui.item.value);
                    console.log(ui.item.vat_id);
                    console.log(ui.item.desc);
                    $("#importerNameLabel").html(ui.item.desc);
                    $("#vatreg_id").val(ui.item.vat_id);
                }
            }).autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>")
                        .append("<div>" + item.label + "<br>" + item.desc + "</div>")
                        .appendTo(ul);
            };
        });
    </script>

@endsection
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}

    {{--{!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->--}}
@endsection
@section('content')
    <div class="col-md-12" ng-app="maintananceMnifestEditApp" ng-controller="maintananceMnifestEditController">
        @if (count($errors) > 0)
            <div class="alert alert-danger successOrErrorMsgDiv">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success successOrErrorMsgDiv">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="col-lg-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info"></i> Manifest Posting Edit
                    <a href="{{route('maintenance-manifest-manifest-details',[$theManifest->id])}}"
                       style="float: right;text-decoration: none">
                        <span><i class="fa fa-database"></i></span>
                        <span> Back To Manifest Details</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">
                            <form  name="manifestEdit" id="manifestEdit"  role="form" method="POST"
                                   action="{{route('maintenance-manifest-update',$theManifest->id)}}"
                                   enctype="multipart/form-data">

                                {{csrf_field()}}
                                <div class="form-group">


                                    <div class="col-sm-6 col-md-4">
                                        <label>Manifest No.:</label>
                                        <input type="text"  name="manifest" id="manifest"  value="{{$theManifest->manifest}}"
                                               title="Manifest No."
                                               class="form-control input-sm" placeholder="Type Manifest No."/>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Manifest Date: </label>
                                        <input type="text" title="manifest_date"  value="{{$theManifest->manifest_date}}"
                                               name="manifest_date" class="form-control input-sm datePicker"
                                               placeholder="Select manifest Date"/>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Marks & No: </label>
                                        <input type="text" value="{{$theManifest->marks_no}}"  name="marks_no"  class="form-control input-sm"
                                               placeholder="Marks & No.">
                                    </div>


                                    <div class="col-sm-6 col-md-4" >
                                        <label>Description of Goods: </label>

                                        <input id="goods-array" value="" name="goods_id"  type="hidden">

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
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Gross Weight:</label>
                                        <input type="text" value="{{$theManifest->gweight}}"   name="gweight"
                                               title="Gross Weight" required
                                               class="form-control input-sm" placeholder="Type Gross Weight"/>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Net Weight: </label>
                                        <input type="text" value="{{$theManifest->nweight}}"
                                               name="nweight"  class="form-control input-sm"
                                               placeholder="Net Weight">
                                    </div>


                                    <div class="clearfix"></div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Package No: </label>
                                        <input type="text" title="package_no" value="{{$theManifest->package_no}}"
                                               name="package_no" class="form-control input-sm" required
                                               placeholder="Type Package No"/>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Package Type: </label>
                                        <input type="text" value="{{$theManifest->package_type}}"
                                               name="package_type"  class="form-control input-sm"
                                               placeholder="Package Type">
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>CNF Value: </label>
                                        <input type="text" value="{{$theManifest->cnf_value}}"
                                               name="cnf_value"  class="form-control input-sm"
                                               placeholder="CNF Value">
                                    </div>




                                    <div class="col-sm-6 col-md-4">
                                        <label>Importer: </label>
                                        <input hidden  name="vatreg_id"  id="vatreg_id" >
                                        <input type="text" title="Vat No" value="@if($theManifest->importer){{$theManifest->importer->BIN}}@endif"
                                               name="vatreg_bin_no" id="vatreg_bin_no"  class="form-control input-sm"
                                               placeholder="Select Vat No"/>
                                        <span  name="importerNameLabel"  id="importerNameLabel"  style="font-size: 10px;color: green">
                                              @if($theManifest->importer)
                                                ( {{$theManifest->importer->NAME}})
                                            @endif
                                        </span>

                                    </div>


                                    <div class="col-sm-6 col-md-4">
                                        <label>Name & Address Exporter: </label>
                                        <input type="text" value="{{$theManifest->exporter_name_addr}}"
                                               name="m_exporter_name_addr"  class="form-control input-sm"
                                               placeholder="Importer Name">
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>L.C No: </label>
                                        <input type="text" value="{{$theManifest->lc_no}}"
                                               name="lc_no"  class="form-control input-sm"
                                               placeholder="L.C No:">
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>L.C Date: </label>
                                        <input type="text" value="{{$theManifest->lc_date}}"
                                               ng-model="lc_date"  name="lc_date"
                                               class="form-control datePicker input-sm" placeholder="L.C Date">
                                    </div>


                                    <div class="col-sm-6 col-md-4">
                                        <label>Indian Bill NO: </label>
                                        <input type="text" value="{{$theManifest->ind_be_no}}"
                                               name="ind_be_no"  class="form-control input-sm"
                                               placeholder="Indian Bill NO:">
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Indian Bill Date: </label>
                                        <input type="text" value="{{$theManifest->ind_be_date}}"
                                               name="ind_be_date"
                                               class="form-control datePicker input-sm" placeholder="Indian Bill Date" >
                                    </div>


                                    <div class="col-sm-6 col-md-4">
                                        <label>Posted Yard Shed: </label>
                                        <select title="" style="" class="form-control input-sm" name="posted_yard_shed" required>
                                            @foreach($yards as $k=>$v)
                                                <option @if($theManifest->posted_yard_shed==$v->id) selected
                                                        @endif value="{{$v->id}}">{{$v->shed_yard}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Remark: <span class="error">*</span></label>
                                        <input type="text" title="marks_no" name="posting_remark"
                                               value="{{$theManifest->posting_remark}}"
                                               class="form-control input-sm" placeholder="Remark"/>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <label>Created At: <span class="error">*</span></label>
                                        <input type="text" value="{{$theManifest->created_at}}" title="created_at"
                                               name="created_at"
                                               class="form-control input-sm datetime_picker"
                                               placeholder="Select Created At"/>
                                    </div>


                                </div>

                                <div class="clearfix"></div>


                                <div class="form-group">

                                    <div class="col-sm-6 col-sm-offset-2 col-md-4 col-md-offset-4 text-center">
                                        <br><br>
                                        <button type="submit" class="btn btn-info" >
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
@section('script')


   {{-- {!!Html :: script('js/bootstrap-tagsinput.min.js')!!}
    {!!Html :: script('js/typeahead.js')!!}
    --}}
    {{--    {!!Html :: script('js/bootstrap-select.min.js')!!} <!--3.3.7-->--}}

    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}

    <script type="text/javascript">

        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);
            $('.datetime_picker').datetimepicker({
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });

        });



    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection