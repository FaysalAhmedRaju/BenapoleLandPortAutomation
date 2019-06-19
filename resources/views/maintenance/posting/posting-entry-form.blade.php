@extends('layouts.master')
@section('title', 'Manifest Posting Form')
@section('script')
    {{--{!!Html :: script('js/customizedAngular/posting.js')!!}--}}
    <script type="text/javascript">

        {{--var role_id = {!! json_encode(Auth::user()->role->id) !!};--}}
    </script>
    {!!Html :: script('js/bootstrap-select.min.js')!!}
@endsection
@section('style')
    {{--{!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}--}}
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

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">

            </div>



            <div class="panel-body">
                <div class="row">

                 <div class="col-md-12" style="">
                        <form role="form" method="POST"{{-- action="{{route('maintenance-warehouse-delivery-delivery-request-update',$theManifest->id)}}"--}} enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="col-sm-4">
                                <label>Manifest NO. </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="manifest_no"  class="form-control input-sm"
                                       placeholder="Manifest NO." required>
                            </div>

                            <div class="col-sm-4">
                                <label>Manifest Date: </label>
                                <input type="text" {{--value="{{$theManifest->be_date}}"--}} ng-model="manifest_date"  name="manifest_date"
                                       class="form-control datePicker input-sm" placeholder="Manifest Date" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Marks & No: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="marks_no"  class="form-control input-sm"
                                       placeholder="Marks & No." required>
                            </div>

                            <div class="col-sm-4">
                                <label>Description of Goods:</label>
                                <input type="text"  {{--value="{{$theManifest->ain_no}}" --}}name="goods_id" class="form-control input-sm" placeholder="Type Goods Name" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Gross Weight: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="gross_weight"  class="form-control input-sm"
                                       placeholder="Gross Weight" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Net Weight: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="net_weight"  class="form-control input-sm"
                                       placeholder="Net Weight" required>
                            </div>



                            <div class="col-sm-4">
                                <label>NO. of Packages: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_package_no"  class="form-control input-sm"
                                       placeholder="NO. of Packages" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Package Type: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_package_type"  class="form-control input-sm"
                                       placeholder="Package Type" required>
                            </div>

                            <div class="col-sm-4">
                                <label>CNF Value: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_cnf_value"  class="form-control input-sm"
                                       placeholder="CNF Value" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Vat No: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_vat_id"  class="form-control input-sm"
                                       placeholder="VAT No" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Importer Name: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="importerNameLabelinput"  class="form-control input-sm"
                                       placeholder="Importer Name" required>
                            </div>


                            <div class="col-sm-4">
                                <label>Name & Address Exporter: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_exporter_name_addr"  class="form-control input-sm"
                                       placeholder="Importer Name" required>
                            </div>


                            <div class="col-sm-4">
                                <label>L.C No: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_lc_no"  class="form-control input-sm"
                                       placeholder="L.C No:" required>
                            </div>


                            <div class="col-sm-4">
                                <label>L.C Date: </label>
                                <input type="text" {{--value="{{$theManifest->be_date}}"--}} ng-model="m_lc_date"  name="m_lc_date"
                                       class="form-control datePicker input-sm" placeholder="L.C Date" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Indian Bill NO: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="m_ind_be_no"  class="form-control input-sm"
                                       placeholder="Indian Bill NO:" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Indian Bill Date: </label>
                                <input type="text" {{--value="{{$theManifest->be_date}}"--}} ng-model="m_ind_be_date"  name="m_ind_be_date"
                                       class="form-control datePicker input-sm" placeholder="Indian Bill Date" required>
                            </div>

                            <div class="col-sm-4">
                                <label>Posting Yard: </label>

                                <select title="" style="" class="form-control input-sm" name="posted_yard_shed">
                                    {{--@foreach($yards as $k=>$v)--}}
                                        <option {{--@if($theManifest->posted_yard_shed==$v->id)--}} selected
                                               {{-- @endif--}} value="{{--{{$v->id}}--}}">{{--{{$v->shed_yard}}--}}</option>
                                    {{--@endforeach--}}
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label>Remark: </label>
                                <input type="text" {{--value="{{$theManifest->be_no}}" --}} name="remark"  class="form-control input-sm"
                                       placeholder="Remark:" required>
                            </div>

                            <div class="clearfix"></div>


                            <div class="form-group">

                                <div class="col-sm-6 col-sm-offset-2 col-md-4 col-md-offset-4 text-center">
                                    <br><br>
                                    <button type="submit" class="btn btn-info">
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




    <script type="text/javascript">
        $( function() {
            $( "#truckentry_datetime" ).datepicker(
                {

                    dateFormat: 'yy-mm-dd',
                }
            );

        } );
    </script>

@endsection

{{--@section('script')--}}

    {{--{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}--}}

    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function () {--}}
            {{--$('#parent_id').select2();--}}
            {{--$(".successOrErrorMsgDiv").delay(3500).slideUp(4000);--}}


            {{--$('.datetime_picker').datetimepicker({--}}
                {{--showButtonPanel: true,--}}
                {{--dateFormat: 'yy-mm-dd',--}}
                {{--timeFormat: 'HH:mm:ss'--}}
            {{--});--}}

        {{--});--}}


    {{--</script>--}}

    {{--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>--}}
{{--@endsection--}}