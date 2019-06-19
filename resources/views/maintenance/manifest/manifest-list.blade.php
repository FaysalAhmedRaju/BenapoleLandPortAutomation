@extends('layouts.master')

@section('title', $viewType)
@section('script')
    <script type="text/javascript">

        $('#manifest_no').on('input',function(e){
//            if($(this).data("lastval")!= $(this).val()){
//                $(this).data("lastval",$(this).val());
//                //change action
//                alert($(this).val());
//            };
            var input = $(this);
            console.log( $(this));
            console.log(input.val());
            var val = input.val();
          //  console.log(val);

            var today = new Date();
            var pattern = /^([0-9]{1,10}|[0-9(P|p)]{2,6}|[0-9(ch|CH)]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}$/;
            if (pattern.test(input.val())) {
                val =  input.val() + today.getFullYear();
            }

            console.log(val)
          //  input.val() = val;
//            $("#manifest_no").html(val);

            $("#manifest_no").val(val);
//            addYearWithManifest: function (manifest) {
//                key = (key || 'manifestno').toLowerCase();
//                if (keyboardStatus == true && key == 'manifestno') {
//
//                }
//                return manifest;
//            }

//            if (input.data("lastval") != val) {
//                input.data("lastval", val);
//
//                //your change action goes here
//                console.log(val);
//            }
        });

//                $(document).ready(function(){
//                    $('#manifest_no').change(function(){
//
//
//                    });
//                });

    </script>
@endsection
@section('style')

    {!! Html::style('css/styles.css') !!}
    {!! Html::style('css/select2.min.css') !!}
    {!! Html::style('/css/jquery.growl.css') !!}

@endsection


@section('content')

    <div class="col-md-12">
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
    </div>

    <div class="col-md-12 table-responsive"  ng-app="maintananceMnifestEditApp" ng-controller="maintananceMnifestEditController">

        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th colspan="3">
                    <form class="form-inline" method="POST"
                          action="{{route('maintenance-manifest-search-by-manifest-no')}}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="">Manifest No:</label>
                            <input class="form-control" placeholder="Search" name="manifest_no" type="text" id="manifest_no">
                            {{--<input type="number" id="n" value="5" step=".5" />--}}
                            {{--<input id="input" name="email" type="text"/>--}}
                        </div>
                        <input class="btn btn-primary" type="submit" value="Search">
                    </form>
                </th>
                <th colspan="7">

                </th>

            </tr>
            <tr>
                {{--<th>manifest id</th>--}}
                <th>Manifest No</th>
                <th>Port</th>
                <th>Shed/Yard</th>
                <th>Scale</th>
                <th>Received At</th>
                <th>Assessment</th>
                <th>Delivery</th>
                <th class="text-center">action</th>

            </tr>
            </thead>
            @if(count($manifests)>0)
                <tbody>

                @foreach($manifests as $k=> $manifest)

                    <tr>
                        {{--<th>{{$manifest_id}}</th>--}}
                        <td>{{$manifest->manifest}}({{ $manifest->transshipment_flag}})</td>
                        <td>
                            @if($manifest->port)
                                {{ $manifest->port->port_alias}}
                            @else
                                <span class="error">No Port Found</span>
                            @endif
                        </td>
                        <td>
                            @if($manifest->shedYard)
                                {{$manifest->shedYard->shed_yard}}
                            @else
                                <span class="error"> No Posting</span>
                            @endif
                        </td>
                        <td>
                            @if($manifest->trucks && count($manifest->trucks)>0)
                                @php($truckWeighted=0)
                                @foreach($manifest->trucks as $k=>$v)
                                    @if($v->entry_scale)
                                        @if($k==0)
                                            <span class="fa fa-arrow-circle-right"></span>
                                        @else
                                            ,
                                        @endif
                                        {{$v->entry_scale}}
                                        @php($truckWeighted++)
                                    @endif
                                @endforeach
                                @if($truckWeighted>0)
                                    <br>------<br>
                                @endif
                                @foreach($manifest->trucks as $k=>$v)
                                    @if($v->exit_scale)

                                        @if($k==0)
                                            <span class="fa fa-arrow-circle-left"></span>
                                        @else
                                            ,
                                        @endif
                                        {{$v->exit_scale}}
                                    @endif
                                @endforeach
                                @if($truckWeighted==0)
                                    <span class="error">No Weighbridge</span>
                                @endif
                            @else

                                <span class="error">No Truck Found</span>
                            @endif
                        </td>
                        <td style="width: 146px">
                            @if($manifest->trucks && count($manifest->trucks)>0 )
                                @php($truckReceived=0)
                                @foreach($manifest->trucks as $k=>$truck)
                                    @if(count($truck->shedYardWeights)>0)
                                        @foreach($truck->shedYardWeights->sortByDesc('unload_receive_datetime') as $k=>$shedYardWeight)
                                            {{date("d/m/Y H:i:s",strtotime($shedYardWeight->unload_receive_datetime))}}
                                            <br>
                                            <span style="color: red; font-size: 9px">
                                           @if($shedYardWeight->yardDetail && $shedYardWeight->yardDetail->yard_shed_name)
                                                    ({{$shedYardWeight->yardDetail->yard_shed_name}})
                                                @else
                                             (No ShedYard Found )
                                                @endif
                                                </span>
                                            <br>
                                        @endforeach
                                        @php($truckReceived++)
                                    @else

                                    @endif
                                @endforeach
                                @if($truckReceived==0)
                                    <span class="error">Not Received Yet</span>
                                @endif
                            @else
                                <span class="error">No Truck Found</span>
                            @endif
                        </td>
                        <td style="width: 146px">
                            @if($manifest->assessments && count($manifest->assessments)>0)
                                @foreach($manifest->assessments->sortByDesc('id') as $k=>$assessment)
                                    {{--{{$assessment->partial_status}}<br>--}}
                                    @if($k==0)
                                        {{date("d/m/Y H:i:s",strtotime($assessment->created_at))}}<br>
                                    @endif
                                @endforeach
                            @else
                                <span class="error">No Assess.</span>
                            @endif
                        </td>
                        <td style="width: 146px">

                            {{--{{ $manifest->port->port_alias}}--}}


                            @if($manifest->deliveryRequisitions)
                                @foreach($manifest->deliveryRequisitions as $k=>$requisitionData)
                                    {{date("d/m/Y",strtotime($requisitionData->approximate_delivery_date))}}<br>
                                 @endforeach
                                {{--{{date("d/m/Y H:i:s",strtotime($manifest->deliveryRequisitions->approximate_delivery_date))}}<br>--}}

                            @else
                                <span class="error">No Delivery</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-info btn-xs mrg" data-original-title="" data-toggle="tooltip"
                               href="{{ route('maintenance-manifest-manifest-details',[$manifest->id]) }}">
                                <i class="fa fa-edit"></i>
                                Details
                            </a>

                            <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"  ng-click="getManifestEdit({{$manifest->id}})"
                               href="{!! route('maintenance-manifest-edit-form',[$manifest->id]) !!}">
                                <i class="fa fa-edit"></i>
                                Edit
                            </a>


                            <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                               onclick="return confirm('Are you sure?')"
                               href="#">
                                <i class="fa fa-trash-o"></i>
                                Delete
                            </a>
                        </td>

                    </tr>

                @endforeach
                </tbody>
            @else
                <tfoot>
                <tr>
                    <td colspan="6">
                        <p style="color: red; text-align: center">No Data Found!</p>

                    </td>
                </tr>
                </tfoot>
            @endif


        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$manifests->render() ) !!}
        </div>

    </div>



@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);

        });

//         var keyBoard = function (event) {
//          var  keyboardFlag = manifest_no.getKeyboardStatus(event);
//        };
//
//        var $watchGroup(['manifest_no'/*,'m_manifest'*/], function () {
//            $scope.ManifestNo = manifestService.addYearWithManifest($scope.ManifestNo, $scope.keyboardFlag);
//            //$scope.m_manifest = manifestService.addYearWithManifest($scope.m_manifest, $scope.keyboardFlag);
//        });


//        function myFunction() {
//            var x = document.getElementById("manifest_no").value;
//          //  console.log(x);
//
//            alert("The text has been changed.");
//           // document.getElementById("demo").innerHTML = "You selected: " + x;
//        }
//        $(":input").bind('keyup mouseup', function () {
//            alert("changed");
//        });

//        $(document).ready(function(){
//            $('#manifest_no').change(function(){
//
////                addYearWithManifest: function (manifest, keyboardStatus, key) {
////                    key = (key || 'manifestno').toLowerCase();
////                    if (keyboardStatus == true && key == 'manifestno') {
////                        var today = new Date();
////                        var pattern = /^([0-9]{1,10}|[0-9(P|p)]{2,6}|[0-9(ch|CH)]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}$/;
////                        if (pattern.test(manifest)) {
////                            manifest = manifest + today.getFullYear();
////                        }
////                    }
////                    return manifest;
////                }
//
//
//                alert("The text has been changed.");
//
//            });
//        });
    </script>


@endsection