@extends('layouts.master')
@section('title','Grade Basic')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        var grade_list = {!! json_encode($grade_list) !!};
    </script>
    {!!Html::script('js/customizedAngular/payroll/grade-and-grade-basic.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="gradeApp" ng-controller="GradeController">



        <div class="col-md-5" id="head">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Grade</h4>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>

                <div class="alert alert-success" id="savingSuccessUpdate" ng-hide="!savingSuccessUpdate">@{{ savingSuccessUpdate }}</div>
                <div class="alert alert-danger" id="savingErrorUpdate" ng-hide="!savingErrorUpdate">@{{ savingErrorUpdate }}</div>
                <div class="col-md-10">
                    <form  name="GradeForm" id="GradeForm" novalidate>
                        <table>
                            <tr>
                                <th>
                                    Grade:
                                </th>
                                <td colspan="5">
                                    <input class="form-control" required type="text" name="grade_name" ng-model="grade_name" id="grade_name" placeholder="Type Grade Name">
                                    <span class="error" ng-show="GradeForm.grade_name.$invalid && submittedGrade">Grade Name is required</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" ng-click="saveUpdateGrade(grade_name)" ng-if="HeadAddBtn" ng-disabled="!grade_name">Save</button>
                                    <button type="button" class="btn btn-success" ng-click="saveUpdateGrade(grade_name)" ng-if="HeadEditBtn">Update</button>
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
                    <caption><h4 class="text-center ok">Grade List</h4></caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Grade Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="value in allDegsignationData | orderBy:'head.id' | itemsPerPage:7" pagination-id="head">
                        <td>@{{$index+1}}</td>
                        <td>@{{value.grade_name}}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" ng-click="editBtn(value)">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="deleteGrade(value)" >

                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">
                            <dir-pagination-controls max-size="7"
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
                <h4 class="text-center ok">Grade Basic</h4>
                <div class="alert alert-success" id="saveDegnationSuccess" ng-hide="!saveDegnationSuccess">@{{ saveDegnationSuccess }}</div>
                <div class="alert alert-danger" id="saveDegnationError" ng-hide="!saveDegnationError">@{{ saveDegnationError }}</div>

                <div class="alert alert-success" id="savingSuccessDegUpdate" ng-hide="!savingSuccessDegUpdate">@{{ savingSuccessDegUpdate }}</div>
                <div class="alert alert-danger" id="savingErrorDegUpdate" ng-hide="!savingErrorDegUpdate">@{{ savingErrorDegUpdate }}</div>


                <form  name="Employeeform" id="Employeeform" novalidate>
                    <table>
                        <tr>
                            <td colspan="4">&nbsp;
                            </td>
                        </tr>

                        <tr>
                            <th>Grade<span class="mandatory">*</span>:</th>
                            <td>
                                <select  class="form-control" required  name ="grade" ng-model="grade" id="grade" ng-options="grade.id as grade.grade_name for grade in grade_list">
                                    <option value="" selected="selected">Select Grade</option>
                                </select>

                                <span class="error" ng-show="Employeeform.grade.$invalid && submittedFixed">Grade is required</span>
                            </td>

                            <th>&nbsp;&nbsp;Basic<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" required ng-model="basic_salary" name="basic_salary" id="basic_salary"class="form-control input-sm" placeholder="Basic Salary">

                                <span class="error" ng-show="Employeeform.basic_salary.$invalid && submittedFixed">Salary is required</span>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <th>Level<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="number" required ng-model="basic_level" name="basic_level" id="basic_level"class="form-control input-sm" placeholder="Basic Salary Level">

                                <span class="error" ng-show="Employeeform.basic_level.$invalid && submittedFixed">Level is required</span>
                            </td>

                            <th >&nbsp;&nbsp;Scale Year<span class="mandatory">*</span>:</th>
                            <td>

                                <select class="form-control" id="scale_year" name="scale_year" ng-model="scale_year" ng-options="year.value as year.text for year in years" required >
                                    <option value="">Select Year</option>
                                </select>
                                <span class="error" ng-show="Employeeform.scale_year.$invalid && submittedFixed">Scale Year is Required</span>
                            </td>


                        </tr>

                        <tr>
                            <td colspan="4" class="text-center">
                                <br>
                                <button type="button" ng-click="saveUpdateBasicGrade()" ng-if="!updateBtn" class="btn btn-primary center-block">
                                    Save</button>
                                <button type="button" ng-click="saveUpdateBasicGrade()" ng-if="updateBtn" class="btn btn-primary center-block">
                                    Update</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;
                            </td>
                        </tr>
                    </table>
                </form>

            </div>

            <div class="col-md-12">
                <table class="table table-bordered">
                    <caption><h4 class="text-center ok">Grade Basic List</h4></caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Grade</th>
                        <th>Basic</th>
                        <th>Basic Level</th>
                        <th>Scale Year</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="DegEmployee in allDesignationEmployeeData | orderBy:'subHead.id' | itemsPerPage:5" pagination-id="subHead">
                        <td>@{{$index+1}}</td>
                        <td>@{{DegEmployee.grade_name}}</td>
                        <td>@{{DegEmployee.basic}}</td>
                        <td>@{{DegEmployee.level}}</td>
                        <td>@{{DegEmployee.scale_year}}</td>
                        <td style="height: 50px; width: 150px">
                            <button type="button" style="width: 40px; height: 25px" class="btn btn-success btn-sm" ng-click="editDegEmployee(DegEmployee)">
                                Edit
                            </button>
                            <button type="button" style="width: 50px; height: 25px"  class="btn btn-danger btn-sm" ng-click="deleteGradeBasic(DegEmployee)" >
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center">
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