@extends('layouts.master')
@section('title', 'Super Admin Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray; padding: 5px 0;
        }
        .headingTxt{
            color: #00dd00;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12 text-center">
        <div class="col-md-3 reportFormStyle">
            <h4 class="ok headingTxt"><b>Import-Export Information</b></h4>
            <form class="form-inline" action="{{ route('super-admin-import-export-information-report') }}" target="_blank" method="post">
                {!! csrf_field() !!}
                <table>
                    <tr>
                        <th>Fiscal Year:</th>
                        <td>
                            <select class="form-control" name="year">
                                @foreach($year as $item)
                                    <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding-left: 10px;">
                            <button type="submit" class="btn btn-primary center-block">Show</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-1">
        </div>
        <script type="text/javascript">
            $(function() {
                $("#sub_head_only").on('focus blur click',function () {
                    $(".ui-datepicker-calendar").hide();

                });


                $('#sub_head_only, #from , #to').datepicker( {
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