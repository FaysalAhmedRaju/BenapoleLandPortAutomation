@extends('layouts.master')

@section('title', $viewType)
@section('style')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content')

    <div class="container-fluid min_height_area">
        <div class="row">
            <div class="col-md-12">
                <div class="student-box-header">
                    <div class="col-md-6 col-xs-5">
                        <span class="glyphicon glyphicon-user "
                              aria-hidden="true">
                        </span>
                        Assign Permission
                    </div>
                    <div class="col-md-6 col-xs-7 snt">

                    </div>
                </div>
            </div>
        </div> <!--row-->
        <div class="inner-view">
            <div class="row">
                <div class="col-md-12">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger row">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <br>
                        <br>
                        <br>

                    <form class="form-inline">

                        <div class="form-group">
                            <label for="email">Role List:</label>
                            <select class="form-control" title="" name="role_id" id="role_id">
                                @if($role_list)
                                    <option value="0">Select Role To Assign</option>
                                    @foreach($role_list as $k=>$role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                @endif
                            </select>

                            <label for="email">Module List:</label>
                            <select class="form-control" title="" name="module_name" id="module_name">
                                @if($module_list)
                                    <option value="0">No Module Selected</option>
                                    <option value="assigned">Only Assigned Route</option>
                                    @foreach($module_list as $k=>$m)
                                        <option value="{{$m->module_name}}">{{$m->module_name}}</option>
                                    @endforeach
                                @endif
                            </select>

                            <button class="btn btn-info" type="button" id="access-btn">Assign </button>

                        </div>
                    </form>


                    <div id="group-access-table">

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!! Html::script('/js/select2.min.js') !!}

    <script>
        $(document).ready(function () {
            $('#role_id').select2();
            $('#module_name').select2();
            $('#access-btn').click(function (event) {
                event.preventDefault();
                var groupId = $('#role_id').val();
                var module_name = $('#module_name').val();

                console.log(groupId + ' - ' +module_name);
                if (groupId == 0) {
                    $.growl.warning({message: "No User Group Selected!"});
                    return false;
                }
                $.ajax({
                    url: "{{ route('group-access') }}",
                    type: "GET",
                    data: {
                        'groupId': groupId,
                        'module_name': module_name
                    },
                    success: function (data) {
                        //console.log(data)
                        $data = $(data); // the HTML content your controller has produced
                        $('#group-access-table').html($data);
                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status = 404) {
                            $.growl.error({message: "Permission Denied!!!!"});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                    }
                });
            });

            $('#role_id').change(function (event) {
                event.preventDefault();

                var groupId = $('#role_id').val();

                console.log(groupId);

                var content = $('#group-access-table').html();
                if (content != '') {
                    $('#group-access-table').html('');
                }


            });
            $("#group-access-table").on("click", ".all_view_class", function () {
                $(".check-common-viewclass").trigger('click');
                $(".check-common-class").prop('checked', $(this).prop("checked")); //change all ".checkb
            });
            $("#group-access-table").on("click", ".all_add_class", function () {
                $(".check-common-addclass").trigger('click');
            });
            $("#group-access-table").on("click", ".all_edit_class", function () {
                $(".check-common-editclass").trigger('click');
            });
            $("#group-access-table").on("click", ".all_delete_class", function () {
                $(".check-common-deleteclass").trigger('click');
            });
            $("#group-access-table").on("click", ".check-common-class", function () {
                var access_type = $(this).data("type");
                var access_role_id = $(this).data("role-id");
                var access_menu_id = $(this).data("menu-id");
                var access_checked = this.checked;

                console.log(access_menu_id+'-'+ access_checked);

                $.ajax({

                    type: 'get',
                    url: "{{ route('role-access') }}",
                    data: {
                        'access_type': access_type,
                        'access_role_id': access_role_id,
                        'access_menu_id': access_menu_id,
                        'access_checked': access_checked,

                    }, success: function (successData) {

                        console.log(successData)
                        $.growl.notice({ title: "Success Notice",message: successData['success']});

                    }, error: function (data) {
                        if (data.status = 401) {
                            $.growl.error({message: "Permission Denied!"});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                    }
                });
            });
        });
    </script>

@endsection


