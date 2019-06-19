@extends('layouts.master')

@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

    {!!Html :: script('js/customizedAngular/cnfExpoter.js')!!}

@endsection



@section('content')
    <div class="col-md-12"  style="padding: 0;" ng-cloak=""  ng-app="cnfExpoterApp" ng-controller="CnfExpoterPanelController">

        <div class="col-md-12 col-md-offset-4">
            <form class="form-inline" ng-submit="search(ManifestNo)">
                <div class="form-group">
                    <label>Expoter Name : </label>
                    {{--<input type="text" name="ManifestNo" ng-model="ManifestNo"  id="ManifestNo" class="form-control" placeholder="Search By Expoter Name">--}}
                    <select  style="width: 190px;" class="form-control" name ="exporter_name_addr" ng-model="exporter_name_addr" id="exporter_name_addr"  ng-options="good.id as good.cargo_name for good in allGoodsData ">
                        <option value="" selected="selected">Select Expoter Name</option>
                    </select>
                </div>
                <a href="manifestReportForCnf/@{{ ManifestNo }}" target="_blank"><button type="button" class="btn btn-primary">
                        <span class="fa fa-search"></span>Show</button>
                </a>
                {{--<div class="col-md-12 col-md-offset-1">--}}
                    {{--<span class="ok">@{{ searchFound }}</span>--}}
                    {{--<span class="error">@{{ searchNotFound }}</span>--}}
                {{--</div>--}}
            </form>

            <label>Expoter Name : </label>
            {{--<input type="text" name="ManifestNo" ng-model="ManifestNo"  id="ManifestNo" class="form-control" placeholder="Search By Expoter Name">--}}
            <select  style="width: 190px;" class="form-control" name ="exporter_name_addr" ng-model="exporter_name_addr" id="exporter_name_addr"  ng-options="good.id as good.cargo_name for good in allGoodsData ">
                <option value="" selected="selected">Select Expoter Name</option>
            </select>



        </div>

    </div>

@endsection


