@extends('layouts.master')
@section('title', 'Warehouse Others Report')
@section('script')
@endsection
@section('content')
        <div class="col-md-12">
            <h4 class="text-center ok">Other Reports</h4>
            <div class="col-md-12">
                {{-- <div class="col-md-4">
                    <br>
                   <div class="list-group text-center">
                        <a class="list-group-item" href="{{ url('postingBranchEntryDoneButWarehouseEntryNotDoneReport') }}" target="_blank">Posting Branch Entry Done, But WareHouse Entry Not Done Report</a>
                    </div> 
                </div> --}}
                <div class="col-md-3">
                <h4 class="ok headingTxt"><b>Receive Summary:</b></h4>
                <form action="{{ route('warehouse-monthly-entry-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="month_entry" id="month_entry">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary center-block">Get Report</button>
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
                <div class="col-md-5">
                <h4 class="ok headingTxt"><b>Warehouse Entry (Manifest Wise):</b></h4>
                <form action="{{ route('warehouse-receive-manifest-and-month-wise-entry-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <table>
                        <tr>
                            <td>
                                <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="manifest_wise_month_entry" id="manifest_wise_month_entry">
                            </td>
                            <td>
                                <select style="width: 150px"  name="vehile_type_flage_pdf"  name="vehile_type_flage_pdf"  class="form-control input-sm" >
                                    <optgroup label="(1). Truck">
                                        <option    value="1" selected >Goods</option>
                                        <option   value="2">Chassis(Chassis on Truck)</option>
                                        <option   value="3">Trucktor(Trucktor on Truck)</option>
                                    </optgroup>
                                    <optgroup label="(2). Self">
                                        <option  value="11">Chassis(Self)</option>
                                        <option  value="12">Trucktor(Self)</option>
                                        <option   value="13">Bus</option>
                                        <option   value="14">Three Wheller</option>
                                        <option   value="15">Rickshaw</option>
                                        <option value="16">Car(self)</option>
                                        <option value="17">Pick Up(self)</option>
                                    </optgroup>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary center-block">Get Report</button>
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
                <div class="col-md-4">
                    <h4 class="ok headingTxt"><b>Warehouse Delivery (Month Wise):</b></h4>
                    <form action="{{ route('warehouse-delivery-month-wise-local-transport-report') }}" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <table>
                            <tr>
                                <td>
                                    <input type="text" placeholder="Please Select Month" class="form-control datePicker f" name="month_wise_delivery_report" id="month_wise_delivery_report">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary center-block">Get Report</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <script type="text/javascript">
            $(function() {
                $("#month_entry, #manifest_wise_month_entry, #month_wise_delivery_report").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });

                $('#month_entry, #manifest_wise_month_entry, #month_wise_delivery_report').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function(dateText, inst) {
                        function isDonePressed(){
                            return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                        }
                        if (isDonePressed()){

                            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('input');
                        }
                    }
                });
            });
        </script>
        </div>
@endsection