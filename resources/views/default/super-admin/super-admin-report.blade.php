@extends('layouts.master')
@section('title', 'Super Admin Reports')
@section('style')
    <style type="text/css">
        .reportFormStyle {
            box-shadow: 0 0 5px gray; padding: 5px 0;
        }
        .headingTxt{
            color: #000000;
            font-weight: bold;
            box-shadow: 0px 5px 37px #888888;
        }
    </style>
@endsection
@section('script')
    {!!Html::script('js/customizedAngular/superAdminReport.js')!!}
@endsection
@section('content')
    <div class="col-md-12 text-center" ng-app="superAdminReportApp" ng-controller="superAdminReportCtrl">
        <h5  style="font-weight: bold; color: #000000">Yearly Reports</h5><hr>

        <div class="col-md-3 reportFormStyle">
            <h6 class="ok headingTxt"><b>Yearly Transport:</b></h6>
            <form class="form-inline" action="{{route('super-admin-transport-yearly-report')}}" target="_blank" method="get">
                <table>
                    <tr>
                        <th></th>
                        <td>
                            <select class="form-control" name="year">
                                    <option value="2017">2017-2018</option>
                            </select>
                        </td>
                        <td style="text-align: center">
                            <button type="submit" class="btn btn-primary center-block">Get Report</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-1">

        </div>
        <div class="col-md-3 reportFormStyle text-center">
            <h6 class="ok headingTxt">
                <b>All Port's Transport:</b>
            </h6>
            <form class="form-inline" action="{{route('super-admin-all-land-port-transport-report')}}" target="_blank" method="get">


                            <button type="submit" class="btn btn-primary center-block">Get Report</button>

            </form>
        </div>
        <div class="col-md-1">

        </div>
        <div class="col-md-3 reportFormStyle text-center">
            <h6 class="ok headingTxt">
                <b>All Port's Export-Import:</b>
            </h6>
            <form class="form-inline" action="{{route('super-admin-all-land-port-export-import-report')}}" target="_blank" method="get">


                <button type="submit" class="btn btn-primary center-block">Get Report</button>

            </form>
        </div>








    </div>
@endsection