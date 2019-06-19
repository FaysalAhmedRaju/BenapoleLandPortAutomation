@extends('layouts.master')
<title>{{ $manifestNo }}</title>
@section('script')

    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!! Html::script('js/bootbox.min.js')!!}


    {!!Html :: script('js/customizedAngular/transshipment/assessment/admin/assessment-details.js')!!}
    {!!Html :: script('js/customizedAngular/transshipment/assessment/assessment.js')!!}

    <script>
        var manifest_id = {!! json_encode($manifest_id) !!};
        var assessment_id = {!! json_encode($assessment_id); !!};


        var role_name = {!! json_encode(Auth::user()->role->name) !!};
        var role_id = {!! json_encode(Auth::user()->role->id) !!};
        var partial_status = {!! json_encode($partial_status) !!}

    </script>

    <style type="text/css">


        @page { margin: 5px 30px; }
        /*body { margin: 0px; }*/

        .center {
            text-align: center;
        }

        /*.tble-warehouse{*/
        /*width: 100%;*/
        /*}*/

        /*.tble-warehouse tr th{*/
        /*}*/
        /*.tble-warehouse tr th, td{*/
        /*border:1px solid black;*/

        /*}*/
        .amount-right {
            text-align: right !important;

        }

        .tble-warehouse tr td {
            border: 1px solid black;
        }
    </style>
@endsection
@section('content')

<div class="col-md-12" style="padding:25px" ng-app="AssessmentDetailsApp">

    <h5 style="text-align: right;padding-right: 35px;"> Date:{{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}</h5>


    <br><br>
    {{-- <b>
        <u>Assessment Details For Manifest: {{$manifestNo}}</u>
    </b> --}}

{{--
    <div class="col-md-12" style="padding: 0">

        <table style="box-shadow: 0px 0px 5px 1px darkgrey; width: 100%">


            <tr>
                <td class="2">
                    <b> Manifest/Export Application No & Data:</b>
                </td>

                <td class="2">
                    <u> {{$manifestNo}}</u> <br> {{date('d-m-Y',strtotime($ManifestDate))}}
                </td>


                <td class="2">
                    <b>Consignee:</b>
                </td>

                <td class="2">
                    {{$importer}}
                </td>
            </tr>

            <tr>
                <td class="2">
                    <b>Bill Of Entry No & Date:</b>
                </td>

                <td class="2"> --}}{{--<span>C-</span>--}}{{--
                    <u> {{$bill_entry_no}}</u> <br> {{date('d-m-Y',strtotime($bill_entry_date))}}
                </td>


                <td class="2">
                    <b> Consignor:</b>
                </td>

                <td class="2">
                    {{$exporter}}
                </td>
            </tr>


            <tr>
                <td rowspan="2">
                    <b>Custom's Release Order No & Date:</b>
                </td>

                <td rowspan="2"><span>C-</span>
                    <u> {{$custom_realise_order_No}}</u> <br> {{date('d-m-Y',strtotime($custom_realise_order_date))}}
                </td>


                <td>
                    <b> C & F Agent:</b>
                </td>

                <td>
                    {{$cnf_name?$cnf_name:''}}
                </td>
            </tr>

            <tr>

                <td><b>Shed / Yard No.</b></td>
                <td>{{$posted_yard_shed}}</td>
            </tr>


        </table>

        <br> <br>
    </div>--}}


    <div class="col-md-12" style="padding: 0"  ng-controller="assessmentCtrl"  >

        <input type="hidden" id="dd"   ng-model="searchTextAssAdmin"  value="{{$manifestNo}}"
               name="searchTextAssAdmin" class="form-control input-sm">

        @include('default/transshipment/assessment/assessment')


        {{-- <table border="0">
             <tr>
                 <th colspan="5"></th>
                 <th></th>
                 <th></th>
                 <th></th>
                 <th></th>
                 <th></th>
                 <th></th>
                 <th></th>
             </tr>
             <tr>
                 <th colspan="5">PARTICULAR OF CHARGES DUE<br></th>
                 <th colspan="3">ASSESSMENT</th>
                 <th></th>
                 <th>Period</th>
                 <th></th>
                 <th class="amount-right">Amount<br></th>
             </tr>
             <tr>
                 <th>1</th>
                 <td></td>
                 <th style="width: 140px">Warehouse Rent<br></th>
                 <td></td>
                 <td><br></td>
                 <th>1.</th>
                 <td></td>
                 <th>Warehouse Rent</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(i)</td>
                 <td>Description of goods<br></td>
                 <td colspan="3"><span>Items= <b>{{ $totalItems }}</b> </span> <br>{{$description_of_goods}}</td>
                 <td></td>

                 <td colspan="5" rowspan="7" style="vertical-align: top">


                     <table border="0">
                         <tr>
                             <th style="width: 150px;">Item</th>
                             <td><b>Quantity</b></td>
                             <td><b>Slab</b></td>
                             <th style="width: 260px">Charge <br> Unit X Day X Rate (X 200%)</th>
                         </tr>
                         @if(isset($item_wise_charge) && !empty($item_wise_charge) && $WareHouseRentDay>0)

                             @foreach($item_wise_charge as $key => $item)
                                 <tr>

                                     <td><span style="text-transform: capitalize">{{ $item->Description }}</span>
                                         @if($item->dangerous=='1') <span><b>({{ $item->dangerous=='1' ? '200%':''}}</b>)</span>@endif
                                     </td>
                                     <td style="vertical-align: middle;text-align: center">{{ $item->item_quantity}}</td>
                                     <td colspan="2">
                                         <table border="0" width="100%">

                                             @php($danger=1)

                                             @if($firstSlabDay || $secondSlabDay || $thirdSlabDay )
                                                 <tr>
                                                     <td style="width:90px;"><b>1St Slab</b></td>
                                                     <td style="text-align: right">
                                                         {{$item->item_quantity }} X {{ $firstSlabDay }} X {{ $item->first_slab }} @if( $item->dangerous=='1')<span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif

                                                         <b> ={{number_format(ceil($item->item_quantity * $firstSlabDay * $danger * $item->first_slab),2)}}</b>
                                                     </td>
                                                 </tr>
                                             @endif

                                             @if( $secondSlabDay || $thirdSlabDay )
                                                 <tr>
                                                     <td style="width:90px;"><b>2nd Slab</b></td>
                                                     <td style="width: 200px;text-align: right">
                                                         --}}{{-- <span style="display: none"> @{{ item.dangerous=='1' ? danger=2 : 1 }}</span>--}}{{--
                                                         {{ $item->item_quantity }} X {{ $secondSlabDay }} X {{ $item->second_slab }} @if( $item->dangerous=='1') <span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif
                                                         <b>={{number_format(ceil($item->item_quantity * $secondSlabDay  *$danger* $item->second_slab),2)}}</b>
                                                     </td>
                                                 </tr>
                                             @endif

                                             @if($thirdSlabDay )
                                                 <tr>
                                                     <td style="width:90px;"><b>3rd Slab</b></td>
                                                     <td style="width: 200px;text-align: right">

                                                         {{ $item->item_quantity }} X {{ $thirdSlabDay }} X {{$item->third_slab}} @if( $item->dangerous=='1')<span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif
                                                         <b>={{number_format(ceil($item->item_quantity * $thirdSlabDay * $danger * $item->third_slab),2)}}</b>
                                                     </td>
                                                 </tr>
                                             @endif
                                         </table>
                                     </td>

                                 </tr>

                             @endforeach
                         @endif
                     </table>




                 </td>


             </tr>


             <tr>
                 <td></td>
                 <td>(ii)</td>
                 <td>Quantity of Goods<br></td>
                 <td colspan="3">{{$package_no ? $package_no:'' }} {{$package_type ? $package_type:''}}</td>
                 <td></td>

             </tr>
             <tr>
                 <td></td>
                 <td>(iii)</td>
                 <td> Chargeable Ton<br></td>
                 <td colspan="2">{{$chargeable_ton}} Kgs</td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(iv)</td>
                 <td> Date of Unloading<br></td>
                 <td colspan="3">{{date('d-m-Y h:i:s',strtotime($receive_date) )  }}</td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(v)</td>
                 <td> Free period<br>(From/To)<br></td>
                 <td colspan="3">{{date('d-m-Y',strtotime($receive_date) )  }} - {{date('d-m-Y',strtotime($FreeEndDate))}} =
                     FT
                 </td>
                 <td></td>

             </tr>
             <tr>
                 <td></td>
                 <td>(vi)</td>
                 <td> Rent Due date<br>(From/To)</td>
                 <td colspan="3">

                     @if ($WareHouseRentDay>0)
                         {{ date('d-m-Y',strtotime($ChargeStartDay )) . ' - ' .  date('d-m-Y',strtotime($deliver_date))
                         .' ='. $WareHouseRentDay
                         }}

                     @else 'No Rent'
                     @endif

                 </td>
                 <td></td>
             </tr>
             --}}{{-- <tr>
                  <td></td>
                  <td>(vii)</td>
                  <td> Rent Due date</td>
                  <td colspan="3">


                  </td>
                  <td></td>
              </tr>--}}{{--

             <tr>
                 <td></td>
                 <td>(vii)</td>
                 <td> Total Diem<br><br></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(viii)</td>
                 <td> Shed/Yard<br></td>
                 <td>{{$posted_yard_shed}}</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>-------<br>
                     <b>Total</b></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td class="amount-right">
                     ----------------------------------------------------------------<br>{{number_format($TotalSlabCharge,2)}}</td>
             </tr>
             <tr>
                 <td>2.</td>
                 <td></td>
                 <th  colspan="2">Handling Operation:<br></th>
                 <td>Quantity</td>
                 <td>2.</td>
                 <td></td>
                 <th>Handling Charges:<br></th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(i)</td>
                 <th>Offloading<br></th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(i)</td>
                 <th>Offloading Charges<br></th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(a) By Manual Labour<br></td>
                 <td colspan="2">
                     @if($role=='TransShipment Assessment Admin')

                         @if($parishableItem==1)
                             {{$chargeable_weight}} X {{$OffloadLabourCharge}}
                         @else

                         @endif

                     @else
                         @if($OffloadLabour){{$OffloadLabour}} X {{$OffloadLabourCharge}} @endif
                     @endif

                 </td>
                 <td></td>
                 <td colspan="2">(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalForOffloadLabour}}</td>--}}{{--
                 <td class="amount-right">

                     @if($role=='TransShipment Assessment Admin')
                         @if($parishableItem==1)
                             {{number_format($TotalForOffloadLabour,2)}}
                         @else

                         @endif
                     @else
                         @if($OffloadLabour){{number_format($TotalForOffloadLabour,2)}} @endif
                     @endif
                 </td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(b) By Equipment<br></td>
                 <td colspan="2">
                     @if($OffLoadingEquip)
                         {{$OffLoadingEquip}} X {{$OffLoadingEquipCharge}}
                         @if($shifting_flag)X <span>2</span>@endif
                     @endif
                 </td>

                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalForOffloadEquip}}</td>--}}{{--
                 <td class="amount-right">
                     @if($OffLoadingEquip){{ number_format($TotalForOffloadEquip,2)}} @endif
                 </td>
             </tr>
             <tr>
                 <td></td>
                 <th>(ii)</th>
                 <th> Loading</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <th>(ii)</th>
                 <th>Loading Charges</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(a) By Manual Labour</td>
                 <td colspan="2">
                     @if($role=='TransShipment Assessment Admin')
                         @if($parishableItem==1)
                             {{$chargeable_weight}} X {{$loadLabourCharge}}
                         @else
                             {{$chargeable_weight}} X {{$loadLabourCharge}}
                         @endif

                     @else--}}{{-- not transshipment--}}{{--
                     @if($approximate_delivery_type==0)
                         {{$chargeable_weight}} X {{$loadLabourCharge}}
                     @endif
                     @endif





                 </td>

                 <td></td>
                 <td colspan="2">(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td class="amount-right">

                     @if($role=='TransShipment Assessment Admin')
                         @if($parishableItem==1)
                             {{ number_format($TotalForloadLabour,2)}}
                         @else
                             {{ number_format($TotalForloadLabour,2)}}
                         @endif

                     @else--}}{{-- not transshipment--}}{{--

                     --}}{{-- @if($OffloadLabour)
                          {{$OffloadLabour}} X {{$OffloadLabourCharge}}
                      @endif--}}{{--

                     @if($approximate_delivery_type==0)
                         {{ number_format($TotalForloadLabour,2)}}
                     @endif
                     @endif


                 </td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(b) By Equipment</td>
                 <td colspan="2">
                     @if($approximate_delivery_type==1)
                         {{$chargeable_weight}} X {{$loadingEquipCharge}}
                     @endif
                 </td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalForloadEquip}}</td>--}}{{--
                 <td class="amount-right">@if($approximate_delivery_type==1){{ number_format($TotalForloadEquip,2)}} @endif</td>
             </tr>
             <tr>
                 <td></td>
                 <th>(iii)</th>
                 <th>Re-Stacking<br></th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <th>(iii)</th>
                 <th>Re-Stacking</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <th>(iv)</th>
                 <th> Removal<br></th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <th>(iv)</th>
                 <th>Removal</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <th>(v)</th>
                 <th>Transhipment</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <th>(v)</th>
                 <th>Transhipment</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(a) By Manual Labour</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(b) By Equipment</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <th>3.</th>
                 <td></td>
                 <th>Other Dues<br></th>
                 <th colspan="2">Quantity</th>
                 <td>3.</td>
                 <td></td>
                 <td>Other Dues</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td colspan="2">Number Over Night<br></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(i)</td>
                 <td colspan="2"> Truck Entrance Fee<br></td>
                 <td></td>
                 <td></td>
                 <td>(i)</td>
                 <td>Truck Entrance Fee</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(a) Foreign Truck<br></td>
                 <td>{{$entranceTotalForeignTruck}} X {{$entranceFeeForeign}}</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(a) Foreign Truck</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$totalLocalTruckEntranceFee}}</td>--}}{{--
                 <td class="amount-right">{{ number_format($totalForeignTruckEntranceFee,2)}}</td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>(b) Local Truck</td>
                 <td>
                     @if($entranceTotalLocalTruck>0)
                         {{$entranceTotalLocalTruck}} X {{$entranceFeeLocal}}
                     @endif
                 </td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(b) Local Truck</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$totalForeignTruckEntranceFee}}</td>--}}{{--
                 <td class="amount-right">
                     @if($totalLocalTruckEntranceFee>0)
                         {{number_format($totalLocalTruckEntranceFee,2)}}
                     @endif
                 </td>
             </tr>
             <tr>
                 <td></td>
                 <td>(ii)</td>
                 <td>Carpenter Charge<br></td>
                 <td colspan="2">Number of Packgs<br></td>

                 <td>Rate</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(a) Opening/Closing</td>
                 <td>@if($carpenterOPPackages) {{$carpenterOPPackages}}@endif</td>
                 <td>@if($carpenterOPPackages) {{$carpenterChargesOpenClose}}@endif</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$totalcarpenterChargesOpenClose}}</td>--}}{{--
                 <td class="amount-right">@if($carpenterOPPackages){{ number_format($totalcarpenterChargesOpenClose,2)}}@endif</td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td colspan="2">(b) Repair</td>
                 <td>@if($carpenterRepairPackages) {{$carpenterRepairPackages}}@endif</td>
                 <td>@if($carpenterRepairPackages){{$carpenterChargesRepair}}@endif</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$totalcarpenterChargesRepair}}</td>--}}{{--
                 <td class="amount-right">@if($carpenterRepairPackages){{ number_format($totalcarpenterChargesRepair,2)}}@endif</td>
             </tr>
             <tr>
                 <td></td>
                 <td>(iii)</td>
                 <td>Holiday Charge<br></td>
                 <td> @if($HolidayTotalTruck) {{$HolidayTotalTruck}}@endif</td>
                 --}}{{--<td>{{$HolidayCharge}}</td>--}}{{--
                 <td class="amount-right">@if($HolidayTotalTruck){{ number_format($HolidayCharge,2)}}@endif</td>
                 <td></td>
                 <td>(iii)</td>
                 <td>Holiday Charge</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalHolidayCharge}}</td>--}}{{--
                 <td class="amount-right">@if($HolidayTotalTruck){{ number_format($TotalHolidayCharge,2)}}@endif</td>
             </tr>
             <tr>
                 <td></td>
                 <td>(iv)</td>
                 <td>Night Charge<br></td>
                 <td>@if($NightTotalTruck){{$NightTotalTruck}}@endif</td>
                 --}}{{--<td>{{$NightCharge}}</td>--}}{{--
                 <td>@if($NightTotalTruck){{ number_format($NightCharge,2)}}@endif</td>
                 <td></td>
                 <td>(iv)</td>
                 <td>Night Charge</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalNightCharge}}</td>--}}{{--
                 <td class="amount-right">@if($NightTotalTruck){{ number_format($TotalNightCharge,2)}}@endif</td>
             </tr>
             <tr>
                 <td></td>
                 <td>(v)</td>
                 <td>Haltage Charge<br></td>
                 <td colspan="2">
                     <table>
                         <tr>
                             <td>Truck</td>
                             <td></td>
                             <td>Day</td>
                             <td></td>
                             <td>Charge</td>
                         </tr>
                         <tr>
                             <td style="text-align: center"> @if($HaltageTotalTruck) {{$HaltageTotalTruck}}@endif</td>
                             <td>@if($HaltageTotalTruck) X @endif</td>
                             <td style="text-align: center">@if($HaltageTotalTruck){{number_format($TotalHaltageDay,0) }}@endif</td>
                             <td>@if($HaltageTotalTruck) X @endif</td>
                             <td style="text-align: center">@if($HaltageTotalTruck){{$HaltageCharge}} @endif</td>
                         </tr>
                     </table>
                 </td>
                 <td></td>
                 <td>(v)</td>
                 <td>Haltage Charge</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 --}}{{--<td>{{$TotalHaltageCharge}}</td>--}}{{--
                 <td class="amount-right">@if($HaltageTotalTruck) {{ number_format($TotalHaltageCharge,2)}} @endif</td>
             </tr>
             <tr>
                 <td></td>
                 <td>(vi)</td>
                 <td colspan="2">Documentation Charge<br></td>

                 <td></td>
                 <td></td>
                 <td>(vi)</td>
                 <td colspan="2">Documentation Charge</td>

                 <td></td>
                 <td></td>
                 <td class="amount-right">
                     @if(isset($documentCharge) && !is_null($documentCharge))
                         {{$documentCharge}}
                     @endif
                 </td>
             </tr>
             <tr>
                 <td></td>
                 <td>(vii)</td>
                 <td rowspan="3" colspan="3">Weighment Charge<br>(if Weighment is carried out on Weighbridge)<br></td>
                 <td></td>
                 <td>(vii)</td>
                 <td>Truck</td>
                 <td>Total</td>
                 <td>Twice</td>
                 <td>Charge</td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>Foreign</td>
                 <td>{{$totalForeignTruckForWeighment}}</td>
                 <td>2</td>
                 <td>{{$weighmentChargeForeign}}</td>
                 --}}{{--<td>{{$TotalweightmentChargesForeign}}</td>--}}{{--
                 <td class="amount-right">
                     {{ number_format($totalweightmentChargesForeign,2)}}
                 </td>

             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>Local</td>
                 <td>@if($totalLocalTruckForWeighment) {{$totalLocalTruckForWeighment}}@endif</td>
                 <td>@if($totalLocalTruckForWeighment)2 @endif</td>
                 <td> @if($totalLocalTruckForWeighment){{$weighmentChargeLocal}} @endif</td>
                 <td class="amount-right">
                     @if($totalLocalTruckForWeighment){{ number_format($totalweightmentChargesLocal,2)}} @endif
                 </td>
             </tr>


             <tr>
                 <td></td>
                 <td>(viii)</td>
                 <td>Outstanding Bill<br></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(viii)</td>
                 <td>Outstanding Bill</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <th>4.<br></th>
                 <td></td>
                 <th colspan="3">Equipment Hire Period Distance Rate<br></th>

                 <td>4.</td>
                 <td></td>
                 <th colspan="2">Equipment Hire Charges</th>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td>Charge</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(a)</td>
                 <td>Mobile Crane<br></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(a)</td>
                 <td>Mobile Crane</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(b)</td>
                 <td>Forklift</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(b)</td>
                 <td>Forklift</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(c)</td>
                 <td>Tarpaulin</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>(c)</td>
                 <td>Tarpaulin</td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td></td>
                 <td>(ix)</td>
                 <td colspan="2">Miscellaneous Charge<br>(To be specified)<br></td>
                 <td></td>
                 <td></td>
                 <td>(ix)</td>
                 <td colspan="2">Miscellaneous Charge<br>(To be specified)</td>
                 <td></td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td><br></td>
                 <td></td>
                 <td><br></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>
                     <br><b>Sub Total Taka:</b></td>

                 <th colspan="4" class="amount-right">
                     -----------------------------------------------------------------------<br>{{ number_format($TotalAssessmentValue,2)}}
                 </th>
             </tr>
             <tr>
                 <th>5</th>
                 <td></td>
                 <th>VAT</th>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>

                 <td colspan="4" class="amount-right">{{ number_format($Vat,2)}}</td>
             </tr>
             <tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td><br><b>Grand Total Taka:</b></td>

                 <th colspan="4" class="amount-right">

                     -----------------------------------------------------------------------
                     <br>{{ number_format($TotalAssessmentWithVat,2)}}</th>
             </tr>

             <tr>
                 <td colspan="3">In word:<br></td>
                 <th colspan="9">&nbsp;<span
                             style="text-transform: capitalize">{{convert_number_to_words($TotalAssessmentWithVat)." Taka only"}}</span><br>
                 </th>
             </tr>
         </table>--}}

    </div>



    <div class="col-md-12 text-center" ng-controller="AssessmentDetailsCtrl">
        <button class="btn btn-primary" type="button" ng-click="Done()" ng-show="showDoneButton">Approve</button>
        <div class="col-md-12 text-center">
            <span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                <img src="/img/dataLoader.gif" width="250" height="15"/>
                <br/> Saving...!
             </span>
            <div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Successfully Done!
            </div>

            <div id="saveError" class="col-md-12 alert alert-warning error" ng-show="insertErrorMsg">
               @{{ insertErrorMsgTxt }}
            </div>
        </div>
        <div class="col-md-12 text-center alert alert-success" ng-show="showAlreadyDone">
            Already Done
        </div>
    </div>

</div>

@php
    function convert_number_to_words($number) {
        $hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
@endphp
@endsection