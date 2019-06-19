@extends('layouts.master')
@section('title', 'Importer Wise Report')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

    {!!Html :: script('js/customizedAngular/cnfEntryForm.js')!!}

@endsection
@section('content')
        <div class="col-md-12 ng-cloak" ng-app="cnfApp" ng-controller="CnfPanelController">
            <div class="col-md-5 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                <form action="{{ route('c&f-reports-get-cargo-wise-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <table>
                        <br>
                            <tr>
                                <th>Select Cargo:</th>
                                <td>
                                    <select style="width: 190px;" class="form-control" name="goods_id" ng-model="goods_id" id="goods_id" ng-options="good.id as good.cargo_name for good in allGoodsDataCnf">
                                        <option value="" selected="selected">Select Goods Name</option>
                                    </select>
                                </td>
                                 <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </form>
            </div>
        </div>
@endsection