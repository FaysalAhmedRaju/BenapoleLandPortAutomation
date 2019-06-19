@extends('layouts.master')
@section('title','Employee Designation')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        {{--var grade_list = {!! json_encode($grade_list) !!};--}}
    </script>
    {!!Html::script('js/customizedAngular/payroll/designation-employee.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="DesignationEmployeeApp" ng-controller="DesignationEmployeeController">



        <div class="col-md-5" id="head">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Designation</h4>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>

                <div class="alert alert-success" id="savingSuccessUpdate" ng-hide="!savingSuccessUpdate">@{{ savingSuccessUpdate }}</div>
                <div class="alert alert-danger" id="savingErrorUpdate" ng-hide="!savingErrorUpdate">@{{ savingErrorUpdate }}</div>
                <div class="col-md-10">
                    <form  name="DesignationForm" id="DesignationForm" novalidate>
                    <table>
                        <tr>
                            <th>
                                Name:
                            </th>
                            <td colspan="5">
                                <input class="form-control" required type="text" name="deg_name" ng-model="deg_name" id="deg_name" placeholder="Designation">
                                <span class="error" ng-show="DesignationForm.deg_name.$invalid && submittedFixedDeg">Designation is required</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" ng-click="saveDeg(deg_name)" ng-if="HeadAddBtn" ng-disabled="!deg_name">Save</button>
                                <button type="button" class="btn btn-success" ng-click="editDeg(deg_name)" ng-if="HeadEditBtn">Update</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                    </table>
                    </form>
                </div>

            </div>

            <div class="col-md-12">
                <table class="table table-bordered" ng-show="headTable">
                    <caption><h4 class="text-center ok">Designation List</h4></caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="value in allDegsignationData | orderBy:'head.id' | itemsPerPage:5" pagination-id="head">
                        <td>@{{$index+1}}</td>
                        <td>@{{value.designation}}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" ng-click="editBtn(value)">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="deleteDegnation(value)" >
                                {{--data-target="#deleteDegnationOnlyModal"  data-toggle="modal"--}}
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="head">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    </tfoot>
                </table>




                <div class="modal fade" id="deleteDegnationOnlyModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">Are you sure to delete?</h4>
                            </div>
                            <div class="modal-body">
                                <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteDegOnly()">Yes</a>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                            </div>
                            <div class="modal-footer">
                                <span ng-show="deleteFailMsg">Something wrong!</span>
                                <div id="deleteSuccessDeg" class="alert alert-warning text-center" ng-show="deleteSuccessMsgDEG">
                                    Successfully deleted!
                                </div>
                                <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="col-md-7" id="subHead">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Employee Designation</h4>
                <div class="alert alert-success" id="saveDegnationSuccess" ng-hide="!saveDegnationSuccess">@{{ saveDegnationSuccess }}</div>
                <div class="alert alert-danger" id="saveDegnationError" ng-hide="!saveDegnationError">@{{ saveDegnationError }}</div>

                <div class="alert alert-success" id="savingSuccessDegUpdate" ng-hide="!savingSuccessDegUpdate">@{{ savingSuccessDegUpdate }}</div>
                <div class="alert alert-danger" id="savingErrorDegUpdate" ng-hide="!savingErrorDegUpdate">@{{ savingErrorDegUpdate }}</div>
                {{-- <div class="col-md-10 col-md-offset-2"> --}}

                    <form  name="Employeeform" id="Employeeform" novalidate>
                    <table>
                        <tr >
                            <th>Designation<span class="mandatory">*</span>:
                                <br><span style="color: orange"><b>@{{ previouse_designation }}</b></span></th>
                            <td >
                                <select class="form-control" required {{-- style="width: 120px" --}} name="designation" ng-model="designation" ng-options="value.id as value.designation for value in allDegsignationData">
                                    <option value="" selected="selected">Select Designation</option>
                                </select>

                                <span class="error" ng-show="Employeeform.designation.$invalid && submittedFixed">Designation is required</span>
                                <span  style="color: goldenrod"><b>@{{ empCurrentDesignation }}</b></span>

                            </td>
                            <th>&nbsp;&nbsp;Employee<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required {{-- style="width: 120px" --}} name ="Employee" ng-model="Employee" id="Employee" ng-options="employ.id as employ.emp_id+'-'+employ.name for employ in getEmployeesInfoData " ng-change="getEmployeeDesignation(Employee)">
                                    <option value="" selected="selected">Select Employee</option>
                                </select>

                                <span class="error" ng-show="Employeeform.Employee.$invalid && submittedFixed">Employee is required</span>

                            </td>

                        </tr>
                        <tr>
                            <th>

                            </th>
                            <td colspan="4">

                            </td>
                        </tr>


                        <tr>
                            <th></th>
                            <td>

                            </td>
                            <th>
                                {{--<span style="color: black"><b>Previous Designation:</b></span>--}}
                            </th>
                            <td {{--colspan="4"--}}>&nbsp;
                                <br>
                                <img ng-src="@{{ show_employee_img ? '/img/employees/'+show_employee_img : '/img/noImg.jpg'}}" height="80" width="100">
                            </td>
                        </tr>

                        {{--<tr>--}}
                            {{--<th>Basic<span class="mandatory">*</span>:</th>--}}
                            {{--<td>--}}
                                {{--<input type="number" required ng-model="basic_salary" name="basic_salary" id="basic_salary"class="form-control input-sm" placeholder="Basic Salary">--}}

                                {{--<span class="error" ng-show="Employeeform.basic_salary.$invalid && submittedFixed">Salary is required</span>--}}
                            {{--</td>--}}
                            {{--<th>&nbsp;&nbsp;Grade<span class="mandatory">*</span>:</th>--}}
                            {{--<td>--}}
                                {{--<select  class="form-control" required --}}{{-- style="width: 120px" --}}{{-- name ="grade" ng-model="grade" id="grade" ng-options="grade.id as grade.grade_name for grade in grade_list">--}}
                                    {{--<option value="" selected="selected">Select Grade</option>--}}
                                {{--</select>--}}

                                {{--<span class="error" ng-show="Employeeform.grade.$invalid && submittedFixed">Grade is required</span>--}}
                            {{--</td>--}}
                        {{--</tr>--}}

                        {{--<tr>--}}
                            {{--<th>Scale Year:</th>--}}
                            {{--<td>--}}
                                {{--<input type="text" required ng-model="scale_year" ng-disabled="scaleYear" name="scale_year" id="scale_year" class="form-control input-sm" placeholder="scale_year Year">--}}

                                {{--<span class="error" ng-show="Employeeform.scale_year.$invalid && submittedFixed">Select Year is required</span>--}}
                            {{--</td>--}}
                        {{--</tr>--}}

                        <tr>
                            <td colspan="4" class="text-center">
                                <br>
                                <button type="button" ng-click="EmpDegSave()" ng-if="!updateBtn" class="btn btn-primary center-block">Save</button>


                                <button type="button" ng-click="updateDegEmployee()" ng-if="updateBtn" class="btn btn-primary center-block">Update</button>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;
                            </td>
                        </tr>
                    </table>
                    </form>
               {{--  </div> --}}
            </div>

            <div class="col-md-12">
                <table class="table table-bordered" ng-show="subHeadTable">
                    <caption><h4 class="text-center ok">Designation Employee List</h4></caption>
                    <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        {{--<th>Basic</th>--}}
                        {{--<th>Grade</th>--}}
                        {{--<th>Year</th>--}}
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="DegEmployee in allDesignationEmployeeData | orderBy:'subHead.id' | itemsPerPage:5" pagination-id="subHead">
                        {{--<td>@{{$index+1}}</td>--}}
                        <td>@{{DegEmployee.emp_id}}</td>
                        <td>@{{DegEmployee.name}}</td>
                        <td>@{{DegEmployee.designation}}</td>
                        {{--<td>@{{DegEmployee.basic}}</td>--}}
                        {{--<td>@{{DegEmployee.grade_name}}</td>--}}
                        {{--<td>@{{DegEmployee.scale_year}}</td>--}}
                        <td style="height: 50px; width: 150px">


                        <button type="button" style="width: 40px; height: 25px" class="btn btn-success btn-sm" ng-click="editDegEmployee(DegEmployee)">
                            Edit
                        </button>
                        <button type="button" style="width: 50px; height: 25px"  class="btn btn-danger btn-sm" ng-click="deleteDegEmployee(DegEmployee)" >
                            {{--data-target="#deleteDegnationModal" data-toggle="modal"--}}
                            Delete
                        </button>


                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="subHead">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    </tfoot>
                </table>



                <div class="modal fade" id="deleteDegnationModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">Are you sure to delete?</h4>
                            </div>
                            <div class="modal-body">



                                <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteDegEmp()">Yes</a>

                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>

                            </div>
                            <div class="modal-footer">
                                <span ng-show="deleteFailMsg">Something wrong!</span>
                                <div id="deleteSuccess" class="alert alert-warning text-center" ng-show="deleteSuccessMsg">
                                    Successfully deleted!
                                </div>
                                <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>
    </div>
    <script>
//        $( function() {
//            $( "#datePickerewew" ).datepicker(
//                {
//                    dateFormat: 'yy',  changeYear: true,  changeMonth: false
//                }
//            );
//
//        } );




    </script>
    {{--<script>--}}
        {{--$(function() {--}}
            {{--$( "#datepicker" ).datepicker({dateFormat: 'yy',  changeYear: true,  changeMonth: false});--}}
        {{--});--}}
    {{--</script>--}}
@endsection

{{--$(function() {--}}
{{--$( "#datepicker" ).datepicker({dateFormat: 'yy',  changeYear: true,  changeMonth: false});--}}
{{--});--}}