@extends('layouts.master')
@section('title', 'Perishable Items')
@section('style')

@endsection

@section('script')
    {!!Html :: script('/js/customizedAngular/transshipment/perishable-items.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="PerishableItemsApp" ng-controller="PerishableItemsCtrl">
        <div class="col-md-12 text-center">
            <h4 class="ok">Item List</h4>
            <span ng-if="dataLoading">
                <img src="/img/dataLoader.gif" width="400" height="20">
                <br> Please Wait !
            </span>
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{savingSuccess}}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{savingError}}</div>
        </div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered">
                <caption>
                    <label class="form-inline">Search:<input class="form-control" ng-model="searchText"> </label>
                </caption>
                <thead>
                    <tr>
                        <th>S/L No.</th>
                        <th>Item Name</th>
                        <th>Perishable</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="item in allItems | orderBy:'item.id'| filter : searchText | itemsPerPage : 20" pagination-id="item">
                        <td>@{{ $index+1 }}</td>
                        <td>@{{ item.Description }}</td>
                        <td>
                            <input type="checkbox" ng-model="item.perishable_flag" ng-true-value="1" ng-false-value="0" ng-change="SavePerishableItem(item)">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="item">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection