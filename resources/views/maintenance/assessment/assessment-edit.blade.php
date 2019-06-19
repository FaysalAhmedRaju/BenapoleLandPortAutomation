@extends('layouts.master')

@section('title', $viewTitle)

@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}

    {{--{!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->--}}

    <style>
        .tbl-td-center tr td {
            text-align: center !important;

        }
    </style>

@endsection

@section('content')

    <div class="col-md-12">


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

        <div class="col-md-12" style="padding: 0">


            {{-- ============================================================= Assessment Created At BY  Start ==========================================  --}}

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info"></i> Assessment Edit
                        <a href="{{route('maintenance-manifest-manifest-details',[$theAssessment[0]->manif_id])}}"
                           style="float: right;text-decoration: none">
                            <span><i class="fa fa-database"></i></span>
                            <span> Manifest Details</span>

                        </a>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12" style="">

                            <div class="col-sm-3">
                                <label for="">Created By</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Created At</label>
                            </div>
                            {{--<div class="col-sm-3">--}}
                            {{--<label for="">Documnentation Charge</label>--}}
                            {{--</div>--}}


                            <div class="col-sm-2">
                                <label for="">Action</label>
                            </div>

                            <div class="clearfix"></div>

                            @if($assessmentOnly)

                                <form role="form" method="POST"
                                      action="{{route('maintenance-assessment-created-by-at-update',$assessmentOnly->id)}}"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}

                                    <div class="form-group">
                                        <input type="hidden" name="partial_status"
                                               value="{{$assessmentOnly->partial_status}}">
                                        <div class="col-sm-3">
                                            <select title="" style="" class="form-control input-sm" name="created_by">
                                                @foreach($userData as $k=>$user)
                                                    <option @if($user->id == $assessmentOnly->created_by) selected
                                                            @endif value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-3">
                                            <input class="form-control datePicker" type="text" name="created_at"
                                                   id="created_at" value="{{$assessmentOnly->created_at}}"
                                                   placeholder="Created At">
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-info btn-xs">
                                                <i class="fa fa-save"></i> Update
                                            </button>
                                        </div>
                                    </div>

                                </form>

                                <div class="clearfix"></div>

                            @endif


                        </div>

                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>


            {{-- ============================================================= Assessment Created At BY End ==========================================  --}}




            {{--===================================================================  Warehouse Charge START ===========================--}}

            <div class="col-md-12" style="padding: 0">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info"></i> Warehouse Charge
                        <a href="{{route('maintenance-manifest-manifest-details',[$theAssessment[0]->manif_id])}}"
                           style="float: right;text-decoration: none">
                            <span><i class="fa fa-database"></i></span>
                            <span> Manifest Details</span>
                            <div class="clearfix"></div>
                        </a>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>


                        <div class="col-md-12" style="">

                            @if(count($itemDetails)>0)
                                @foreach($itemDetails as $k=>$itemDetail)

                                    <form role="form" method="POST"
                                          action="{{route('maintenance-assessment-warehouse-charge-update',$itemDetail->id)}}"
                                          enctype="multipart/form-data">

                                        {{csrf_field()}}
                                        <div class="form-group">

                                            <input type="hidden" name="partial_status" value="{{$partial_status}}">

                                            <div class="col-sm-3">
                                                <label for="">Tariff</label>
                                                <select title="" style="" class="form-control input-sm" name="goods_id">
                                                    @foreach($goods as $k=>$v)
                                                        <option @if($v->id==$itemDetail->goods_id) selected
                                                                @endif value="{{$v->id}}">{{$v->id.'. '.$v->cargo_name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-sm-2">
                                                <label for="">Bassis</label>
                                                <select title="" style="" class="form-control input-sm" name="item_type"
                                                        tabindex="2">
                                                    <option value="1" @if($itemDetail->item_type==1) selected @endif >
                                                        Volumn
                                                    </option>
                                                    <option value="2" @if($itemDetail->item_type==2) selected @endif>
                                                        Unit
                                                    </option>
                                                    <option value="3" @if($itemDetail->item_type==3) selected @endif>
                                                        Package
                                                    </option>
                                                    <option value="4" @if($itemDetail->item_type==4) selected @endif>
                                                        Weight
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="">Item</label>
                                                {{-- <input title="" class="form-control input-sm" type="text" name="unit"
                                                        value="{{$itemDetail->}}">--}}
                                                <select title="" style="" class="form-control input-sm"
                                                        name="item_Code_id">
                                                    @foreach($itemCodes as $k=>$v)
                                                        <option @if($v->id==$itemDetail->item_Code_id) selected
                                                                @endif value="{{$v->id}}">{{$v->Description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="">Quantity</label>
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="item_quantity"
                                                       value="{{$itemDetail->item_quantity}}">
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="">Shed/Yard:</label>
                                                <select title="" class="form-control  input-sm" name="yard_shed">
                                                    <option value="0" @if($itemDetail->yard_shed==0) selected @endif>
                                                        Yard
                                                    </option>
                                                    <option value="1" @if($itemDetail->yard_shed==1) selected @endif>
                                                        Shed
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="">Dangerous:</label>
                                                <select title="" class="form-control  input-sm" name="dangerous">
                                                    <option value="0" @if($itemDetail->dangerous==0) selected @endif>
                                                        No
                                                    </option>
                                                    <option value="1" @if($itemDetail->dangerous==1) selected @endif>
                                                        Yes
                                                    </option>
                                                </select>
                                            </div>


                                            <div class="col-md-1">
                                                <label for=""></label>
                                                <button type="submit" class="btn btn-info btn-xs">
                                                    <i class="fa fa-save"></i> Update
                                                </button>
                                            </div>
                                            <div class="col-md-1">
                                                <label for=""></label>
                                                <a href="{{route('maintenance-assessment-warehouse-charge-delete',$itemDetail->id)}}"
                                                   onclick="return confirm('Do you really want to delete?');"
                                                   class="btn btn-outline btn-danger btn-xs">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="clearfix"></div>

                                @endforeach
                            @endif
                            <br>
                        </div>
                        {{--=============================   warehouse charge Add form Start==========================--}}
                        <div class="col-md-12" style="">
                            <form role="form" method="POST"
                                  action="{{route('maintenance-assessment-warehouse-charge-save')}}"
                                  enctype="multipart/form-data">

                                {{csrf_field()}}
                                <div class="form-group">

                                    <input type="hidden" name="manf_id" value="{{$theAssessment[0]->manif_id}}">
                                    <input type="hidden" name="partial_status" value="{{$partial_status}}">

                                    <div class="col-sm-3">
                                        <label for="">Tariff</label>
                                        <select title="" style="" class="form-control input-sm" name="goods_id">
                                            @foreach($goods as $k=>$v)
                                                <option value="{{$v->id}}">{{$v->id.'. '.$v->cargo_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Bassis</label>
                                        <select title="" style="" class="form-control input-sm" name="item_type"
                                                tabindex="2">
                                            <option value="1">
                                                Volumn
                                            </option>
                                            <option value="2">
                                                Unit
                                            </option>
                                            <option value="3">
                                                Package
                                            </option>
                                            <option value="4">
                                                Weight
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Item</label>

                                        <select title="" style="" class="form-control input-sm"
                                                name="item_Code_id">
                                            @foreach($itemCodes as $k=>$v)
                                                <option value="{{$v->id}}">{{$v->Description}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Quantity</label>
                                        <input title="" class="form-control input-sm" type="text"
                                               name="item_quantity" placeholder="Quantity"
                                               value="">
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Shed/Yard:</label>
                                        <select title="" class="form-control  input-sm" name="yard_shed">
                                            <option value="0">
                                                Yard
                                            </option>
                                            <option value="1">
                                                Shed
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Dangerous:</label>
                                        <select title="" class="form-control  input-sm" name="dangerous">
                                            <option value="0">
                                                No
                                            </option>
                                            <option value="1">
                                                Yes
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-md-1">
                                        <label for="">&nbsp;&nbsp; </label>
                                        <button type="submit" class="btn btn-info btn-xs">
                                            <i class="fa fa-save"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="clearfix"></div>

                        </div>
                        {{--=============================   warehouse charge Add form End ==========================--}}
                        {{--=========================Warehouse Charge================================================== --}}


                        <div class="col-md-12 table-responsive">

                            @if($warehouseCharge && !empty($warehouseCharge))

                                <table class="tbl-td-center" border="1" width="100%">
                                    <tr>
                                        <th colspan="7" class="text-center">Saved Assessment Warehouse Details</th>
                                    </tr>
                                    <tr>
                                        <td width="16%"><b>Item</b></td>
                                        <td width="20%"><b>Basis Of Charge</b></td>
                                        <td width="8%"><b>Slab</b></td>
                                        <td width="11%"><b>Quantity</b></td>
                                        <td width="12%" style="text-align: center"><b>Day</b></td>
                                        <td width="16%"><b>Rate</b></td>
                                        <td width="15%" class="amount-right"><b>Amount</b></td>


                                    </tr>

                                    {{--------------------------------------======================Shed START================------------------------------------}}
                                    <tbody>
                                    <tr>
                                        <th colspan="7" class="ok">Shed</th>
                                    </tr>
                                    @foreach($warehouseCharge['item_wise_shed_details_charge'] as $k=>$shed)
                                        <tr>

                                            <td>{{ $shed['Description'] }}
                                                @if($shed['dangerous']=='1')
                                                    <span><b>({{ $shed['dangerous'] ? '200%': ''}}</b>)</span>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;text-align: center">
                                                @if($shed['item_type'] ==1)
                                                    Volumn
                                                @elseif($shed['item_type']==2)
                                                    Unit
                                                @elseif($shed['item_type']==3)
                                                    Package
                                                @else
                                                    Weight
                                                @endif
                                            </td>
                                            <td colspan="5">
                                                <table class="tbl-td-center" border="1" width="100%">

                                                    @if($warehouseCharge['first_slab_day'] || $warehouseCharge['second_slab_day'] || $warehouseCharge['third_slab_day'])
                                                        <tr>
                                                            <td style="width: 13%; text-align: center"><b>1St</b></td>
                                                            <td style="width: 18%"> {{ $shed['item_quantity'] }}</td>
                                                            <td style="width: 19%"> {{ $warehouseCharge['first_slab_day'] }}</td>
                                                            <td style="width: 26%">
                                                                {{ $shed['first_slab'] }}
                                                                <span>
                                                                    @php
                                                                        $danger=0;
                                                                    @endphp
                                                                    X {{ $shed['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                </span>
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($shed['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $shed['first_slab']), 0)}}</b>
                                                            </td>
                                                        </tr>

                                                    @endif
                                                    @if($warehouseCharge['second_slab_day'] || $warehouseCharge['third_slab_day'])
                                                        <tr>
                                                            <td class="td-center"><b>2nd </b></td>
                                                            <td> {{ $shed['item_quantity'] }}</td>
                                                            <td> {{ $warehouseCharge['second_slab_day'] }}</td>
                                                            <td> {{ $shed['second_slab'] }}
                                                                @if($shed['dangerous']=='1')
                                                                    <span>
                                                                    X {{ $shed['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                </span>
                                                                @endif
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($shed['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $shed['second_slab']),0)}}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if($warehouseCharge['third_slab_day'])
                                                        <tr>

                                                            <td class="td-center"><b>3rd</b></td>
                                                            <td>{{ $shed['item_quantity'] }}</td>
                                                            <td>{{ $warehouseCharge['third_slab_day'] }}</td>
                                                            <td>{{$shed['third_slab']}}

                                                                @if($shed['dangerous']=='1')
                                                                    <span>
                                                                    X {{ $shed['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                </span>
                                                                @endif
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($shed['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $shed['third_slab']),0)}}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                    {{--------------------------------------======================Shed End================-------------------------------------}}


                                    {{---------------------------------===================YARD START====================-------------------------------------}}
                                    <tbody>
                                    <tr>
                                        <th colspan="7" class="ok">Yard</th>
                                    </tr>
                                    @foreach($warehouseCharge['item_wise_yard_details_charge'] as $k=>$yard)
                                        <tr>
                                            <td>{{ $yard['Description'] }}
                                                @if($yard['dangerous']=='1')
                                                    <span><b>({{ $yard['dangerous'] ? '200%': ''}}</b>)</span>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;text-align: center">
                                                @if($yard['item_type'] ==1)
                                                    Volumn
                                                @elseif($yard['item_type']==2)
                                                    Unit
                                                @elseif($yard['item_type']==3)
                                                    Package
                                                @else
                                                    Weight
                                                @endif
                                            </td>
                                            <td colspan="5">
                                                <table class="tbl-td-center" border="1" width="100%">

                                                    @if($warehouseCharge['first_slab_day'] || $warehouseCharge['second_slab_day'] || $warehouseCharge['third_slab_day'])
                                                        <tr>
                                                            <td style="width: 13%; text-align: center"><b>1St</b></td>
                                                            <td style="width: 18%"> {{ $yard['item_quantity'] }}</td>
                                                            <td style="width: 19%"> {{ $warehouseCharge['first_slab_day'] }}</td>
                                                            <td style="width: 26%">
                                                                {{ $yard['first_slab'] }}
                                                                <span>
                                                                    @php
                                                                        $danger=0;
                                                                    @endphp
                                                                    X {{ $yard['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                </span>
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($yard['item_quantity'] * $warehouseCharge['first_slab_day'] * $danger * $yard['first_slab']),0)}}</b>
                                                            </td>
                                                        </tr>

                                                    @endif
                                                    @if($warehouseCharge['second_slab_day'] || $warehouseCharge['third_slab_day'])
                                                        <tr>
                                                            <td class="td-center"><b>2nd </b></td>
                                                            <td> {{ $yard['item_quantity'] }}</td>
                                                            <td> {{ $warehouseCharge['second_slab_day'] }}</td>
                                                            <td> {{ $yard['second_slab'] }}
                                                                @if($yard['dangerous']=='1')
                                                                    <span>
                                                                        X {{ $yard['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($yard['item_quantity'] * $warehouseCharge['second_slab_day'] * $danger * $yard['second_slab']),0)}}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if($warehouseCharge['third_slab_day'])
                                                        <tr>

                                                            <td class="td-center"><b>3rd</b></td>
                                                            <td>{{ $yard['item_quantity'] }}</td>
                                                            <td>{{ $warehouseCharge['third_slab_day'] }}</td>
                                                            <td>{{$yard['third_slab']}}

                                                                @if($yard['dangerous']=='1')
                                                                    <span>
                                                                        X {{ $yard['dangerous']=='1' ? $danger=2 : $danger=1 }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="amount-right">
                                                                <b>={{number_format(ceil($yard['item_quantity'] * $warehouseCharge['third_slab_day'] * $danger * $yard['third_slab']),0)}}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                    {{---------------------------------===================YARD END====================-------------------------------------}}

                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <th colspan="5">
                                            Total:
                                        </th>
                                        <td>
                                            @if(count($totalWarehouseCharge)>0)
                                                {{ $totalWarehouseCharge[0]->tcharge }}
                                            @endif
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>

                            @endif

                        </div>


                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>

            {{--===================================================================  Warehouse Charge END ===========================--}}

            {{-- ============================================================= Documentation Charge Start ==========================================  --}}

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info"></i> Documentation Charge
                        <a href="{{route('maintenance-manifest-manifest-details',[$theAssessment[0]->manif_id])}}"
                           style="float: right;text-decoration: none">
                            <span><i class="fa fa-database"></i></span>
                            <span> Manifest Details</span>
                            <div class="clearfix"></div>
                        </a>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">

                            <div class="col-sm-3">
                                <label for="">Document Name</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Number of Document</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Documnentation Charge</label>
                            </div>


                            <div class="col-sm-2">
                                <label for="">Action</label>
                            </div>

                            <div class="clearfix"></div>

                            @if(count($assessmentDocumentCharge)>0)
                                @foreach($assessmentDocumentCharge as $k=>$charge)
                                    <form role="form" method="POST"
                                          action="{{route('maintenance-assessment-documentation-charge-update',$charge->id)}}"
                                          enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <input type="hidden" name="partial_status"
                                               value="{{$charge->partial_status}}">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="document_name"
                                                       value="{{$charge->document_name}}" placeholder="Document Name">

                                            </div>
                                            <div class="col-sm-3">
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="number_of_document"
                                                       value="{{$charge->number_of_document}}"
                                                       placeholder="Number of Document">
                                            </div>

                                            <div class="col-sm-3">
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="document_charge"
                                                       value=" {{$charge->document_charge}}"
                                                       placeholder="Documnentation Charge">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-info btn-xs">
                                                <i class="fa fa-save"></i> Update
                                            </button>
                                        </div>

                                        <div class="col-md-1">
                                            {{--<label for=""></label>--}}
                                            <a href="{{route('maintenance-assessment-documentation-charge-delete',$charge->id)}}"
                                               onclick="return confirm('Do you really want to delete?');"
                                               class="btn btn-outline btn-danger btn-xs">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                        </div>


                                    </form>

                                    <div class="clearfix"></div>
                                @endforeach
                            @endif


                        </div>

                        <div class="col-md-12" style="">

                            <div class="col-sm-3">
                                <label for="">Document Name</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Number of Document</label>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Documnentation Charge</label>
                            </div>


                            <div class="col-sm-2">
                                <label for="">Action</label>
                            </div>

                            <div class="clearfix"></div>

                            <form role="form" method="POST"
                                  action="{{route('maintenance-assessment-documentation-charge-save')}}"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="manifest_id" value="{{$theAssessment[0]->manif_id}}">
                                <input type="hidden" name="partial_status" value="{{$partial_status}}">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <input title="" class="form-control input-sm" type="text" name="document_name"
                                               value="" placeholder="Document Name">

                                    </div>
                                    <div class="col-sm-3">
                                        <input title="" class="form-control input-sm" type="text"
                                               name="number_of_document"
                                               value="" placeholder="Number of Document">
                                    </div>

                                    <div class="col-sm-3">
                                        <input title="" class="form-control input-sm" type="text"
                                               name="document_charge"
                                               value=" " placeholder="Documnentation Charge">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-info btn-xs">
                                        <i class="fa fa-save"></i> Add
                                    </button>
                                </div>


                            </form>

                            <div class="clearfix"></div>


                        </div>

                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>


            {{-- ============================================================= Documentation Charge End ==========================================  --}}


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info"></i> Assessment Edit Form
                        <a href="{{route('maintenance-manifest-manifest-details',[$theAssessment[0]->manif_id])}}"
                           style="float: right;text-decoration: none">
                            <span><i class="fa fa-database"></i></span>
                            <span> Manifest Details</span>
                            <div class="clearfix"></div>
                        </a>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-12">
                            <i class="fa fa-warning fa-fw"></i> <span class="error">*</span> Indicates Required Field!
                        </div>

                        <div class="col-md-12" style="">

                            <div class="col-sm-3">
                                <label for="">Sub Head</label>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Unit</label>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Other Unit</label>
                            </div>

                            <div class="col-sm-2">
                                <label for="">Charge</label>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Action</label>
                            </div>

                            <div class="clearfix"></div>

                            @if(count($theAssessment)>0)
                                @foreach($theAssessment as $k=>$assess)


                                    <form role="form" method="POST"
                                          action="{{route('maintenance-assessment-update',$assess->id)}}"
                                          enctype="multipart/form-data">

                                        {{csrf_field()}}
                                        <div class="form-group">

                                            <div class="col-sm-3">
                                                <input type="hidden" name="partial_status"
                                                       value="{{$assess->partial_status}}">

                                                <select title="" style="" class="form-control input-sm"
                                                        name="sub_head_id">
                                                    @foreach($subHeadList as $k=>$v)
                                                        <option @if($assess->sub_head_id==$v->id) selected
                                                                @endif value="{{$v->id}}">{{$v->acc_sub_head}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-sm-2">
                                                <input title="" class="form-control input-sm" type="text" name="unit"
                                                       value="{{$assess->unit}}">
                                            </div>

                                            <div class="col-sm-2">
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="other_unit"
                                                       value="{{$assess->other_unit}}">
                                            </div>
                                            <div class="col-sm-2">
                                                <input title="" class="form-control input-sm" type="text"
                                                       name="charge_per_unit"
                                                       value="{{$assess->charge_per_unit}}">
                                            </div>


                                        </div>


                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-info btn-xs">
                                                    <i class="fa fa-save"></i> Update
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="clearfix"></div>

                                @endforeach
                            @endif


                        </div>

                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>


        </div>
        <!-- /.col-lg-12 -->
    </div>





@endsection
@section('script')

    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#parent_id').select2();
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);


            $('.datetime_picker').datetimepicker({
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });

        });


    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        $('#created_at').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
    </script>
@endsection