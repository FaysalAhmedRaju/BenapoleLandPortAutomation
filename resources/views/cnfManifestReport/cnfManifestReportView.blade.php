@extends('layouts.master')

@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection

@section('script')

   {{--  {!!Html :: script('js/customizedAngular/cnfManifestReport.js')!!} --}}

@endsection



@section('content')
    <div class="col-md-12"  style="padding: 0;" {{-- ng-cloak=""  ng-app="cnfReportApp" ng-controller="CnfReportPanelController" --}}>
        <div class="col-md-12 col-md-offset-4">
            <form class="form-inline" action="{{ route('c&f-reports-cnf-manifest-report') }}" target="_blank" method="POST" {{-- ng-submit="search(ManifestNo)" --}}>
            {{ csrf_field() }}
                <div class="form-group">
                    <label>Manifest NO: </label>
                    <input type="text" name="ManifestNo" {{-- ng-model="ManifestNo" --}}  id="ManifestNo" class="form-control" placeholder="Search Manifest No">
                </div>
                <button type="submit" class="btn btn-primary">
                        <span class="fa fa-search"></span>Show</button>
               {{--  <div class="col-md-12 col-md-offset-1">
                    <span class="ok">@{{ searchFound }}</span>
                    <span class="error">@{{ searchNotFound }}</span>

                </div> --}}
            </form>
        </div>
    </div>
@endsection


