@extends('layouts.master')
@section('title', 'Welcome WareHouse User')

@section('script')
    {!!Html :: script('js/customizedAngular/graphView.js')!!}
    <script src="js/lodash.js"></script>

    <style>
        .modal{
            text-align: center;
            left: 0;
        }
        .cell_background{

           /* color: #FFF;
            font-weight: bolder;
            padding:10px;*/
           /* background: red; !* For browsers that do not support gradients *!
            background: -webkit-linear-gradient(left,rgba(255,0,0,0),rgba(255,0,0,1)); !*Safari 5.1-6*!
            background: -o-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); !*Opera 11.1-12*!
            background: -moz-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); !*Fx 3.6-15*!
            background: linear-gradient(to right, rgba(255,0,0,0), rgba(255,0,0,1)); !*Standard*!
    */
/*
            background: red; !* For browsers that do not support gradients *!
            background: -webkit-radial-gradient(red, yellow, green); !* Safari 5.1 to 6.0 *!
            background: -o-radial-gradient(red, yellow, green); !* For Opera 11.6 to 12.0 *!
            background: -moz-radial-gradient(red, yellow, green); !* For Firefox 3.6 to 15 *!
            background: radial-gradient(red, yellow, green); !* Standard syntax *!*/


            background-image: linear-gradient(to right, transparent, #FFEBEE);
            background-size: 100% 20%;
            background-position: left bottom;
            background-repeat: no-repeat;

            /* background: linear-gradient(90deg, pink 50%,cyan 50%); */

        }
        .normal_cell{
            padding:10px;

        }

        .head_style{
            background-color: #0000cc;
            color: #fff;
            padding:0 10px;
        }

    </style>
@endsection
@section('content')

    <div class="col-md-12 text-center table-responsive" ng-app="graphApp" ng-controller="graphCtrl">

        <h3 class="text-muted"><u>Graphical View Of Shed </u></h3>



        <table border="1" cellspacing="0">{{--getWeight(row, column)--}}
            <tbody ng-if="weights">
            <tr>
                <td></td>
                <td class="head_style" ng-repeat="row in rows"><span ng-hide="row==0">@{{row}}</span> </td>
            </tr>
            <tr ng-if="weights" ng-repeat="column in columns">
                <td class="head_style">@{{column}}</td>

                <td ng-repeat="row in rows" style="width: 100px; cursor: pointer"
                    ng-class="getWeight(column,row)?'cell_background':'normal_cell'"
                    ng-click="onCellselect(column,row,getWeight(column,row))">
                    @{{column}}@{{row}} <br>
                    @{{ getWeight(column,row) }}
                </td>
            </tr>
            </tbody>
        </table>
        {{--<div ng-click="dd()">add</div>--}}


    <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        Please enter weight for @{{ row }}@{{ column }}
                        <input type="text" class="form-control"
                               ng-model="weight" />
                        <button class="btn btn-primary"
                                ng-click="save()">Save</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>



    </div>


@endsection