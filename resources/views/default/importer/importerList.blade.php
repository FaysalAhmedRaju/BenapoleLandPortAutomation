@extends('layouts.master')
@section('title', 'Importer List')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
	{!! Html :: script('js/customizedAngular/Importer.js') !!}
	{!! Html :: script('js/bootbox.min.js')!!}
	
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="ImporterApp" ng-controller="ImporterCtrl">
		<div class="col-md-4 col-md-offset-4">
	        <form class="form-inline" ng-submit="GetSingleImporter(bin_id)">
				<div class="form-group">
	              <input type="text" name="BinNo" id="bin_id"  ng-model="bin_id" class="form-control" placeholder="Search By BIN No">
				</div>
	        </form>
			<br>
	    </div>
	    <div class="col-md-5 col-md-offset-3 text-center">
			<div id="binNotFound" class="alert alert-danger" ng-show="binNotFound">BIN Number Not Found</div>
	    </div>
		<div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
			<h4 class="text-center ok">Importer Details Entry</h4>
			<div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
	        <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
			<form  name="importerForm" id="importerForm" novalidate>
				<table>
					<tr>
						<th>BIN No<span class="mandatory">*</span>:</th>
						<td>
							<input type="text" name="BIN" id="BIN" class="form-control" ng-model="BIN" required ng-pattern="/^\d{7,11}$/" {{-- unique --}} {{-- ng-disabled="diableBINNUmber" --}}>
							<span class="error" ng-show="importerForm.BIN.$error.required && submitted">BIN No is required.</span>
							<span class="error" ng-show="importerForm.BIN.$error.pattern && submitted">BIN No must be 7 to 11 character.</span>
							<span class="error" ng-show="exist && submitted">BIN No already exist.</span>
						</td>
						<th  style="padding-left: 25px;">Vat:</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio"  ng-model="vat" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="vat" ng-init="vat=0" value="0" ng-checked="true">No
                            </label>
                        </td>
						<th style="padding-left: 25px;">Name<span class="mandatory">*</span>:</th>
						<td>
							<input type="text"  name="NAME" id="Name" class="form-control" ng-model="NAME" required>
							<span class="error" ng-show="importerForm.NAME.$invalid && submitted">Name Is required.</span>
						</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<th>Address1<span class="mandatory">*</span>:</th>
						<td>
							<textarea class="form-control textarea" name="ADD1" id="ADD1" ng-model="ADD1" required>
							</textarea>
							<span class="error" ng-show="importerForm.ADD1.$invalid && submitted">Address1 Is required.</span>
						</td>
						<th style="padding-left: 25px;">Address2<span class="mandatory">*</span>:</th>
						<td>
							<textarea class="form-control textarea" name="ADD2" id="ADD2" ng-model="ADD2" required>
							</textarea>
							<span class="error" ng-show="importerForm.ADD2.$invalid && submitted">Address2 Is required.</span>
						</td>
						<th style="padding-left: 25px;">Address3:</th>
						<td>
							<textarea class="form-control textarea" name="ADD3" id="ADD3" ng-model="ADD3">
							</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<th>Address4:</th>
						<td>
							<textarea class="form-control textarea" name="ADD4" id="ADD4" ng-model="ADD4">
							</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6" class="text-center">
                            <button type="button" class="btn btn-primary center-block" ng-click="Save()" ng-if="btnSave"><span class="fa fa-file"></span> Save</button>
                            <button type="button" class="btn btn-success center-block" ng-click="Update()" ng-if="btnUpdate"><span class="fa fa-download"></span> Update</button>
                            <span ng-if="dataLoading">
                                <img src="img/dataLoader.gif" width="250" height="15"/>
                                <br/>Please wait!
                            </span>
                        <td>
					</tr>
				</table>
			</form>
		</div>
	    <div class="col-md-12 text-center">
		<table class="table table-bordered">
			<caption><h4 class="text-center ok">@{{ tableHeading }}</h4>
				<button type="button" ng-if="whenSingleImporter" class="btn btn-primary pull-right" ng-click="GetData(1)">All Importers</button>
			</caption>
			<thead>
                <tr>
                	<th>BIN</th>
                	<th>Vat</th>
                    <th>Name</th>
                    <th>ADD1</th>
                    <th>ADD2</th>
                    <th>ADD3</th>
                    <th>ADD4</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-style="{'background-color':(importer.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="importer in importers | itemsPerPage:10 | orderBy: importer.id" total-items="total_count" current-page="currentPage">
                    <td>@{{importer.BIN}}</td>
                    <td>@{{importer.vat | vatFilter }}</td>
                    <td>@{{importer.NAME}}</td>
                    <td>@{{importer.ADD1}}</td>
                    <td>@{{importer.ADD2}}</td>
                    <td>@{{importer.ADD3}}</td>
                    <td>@{{importer.ADD4}}</td>
                    <td>
                    	<button style="width: 80px;" type="button" class="btn btn-success" ng-click="PressUpdateBtn(importer)">Update</button>
                        <button style="width: 80px;" type="button" class="btn btn-danger" ng-click="PressDeleteBtn(importer)">Delete</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
            	<tr ng-if="listLoading">
            		<td colspan="7" style="text-align:center;">
            			<img src="img/dataLoader.gif" width="250" height="15" />
            			<br />Please wait!
            		</td>
            	</tr>
                <tr>
                    <td colspan="8" class="text-center">
                        <dir-pagination-controls
					        max-size="8"
					        direction-links="true"
					        boundary-links="true" 
					        on-page-change="GetData(newPageNumber)"
					        >
					    </dir-pagination-controls>
                    </td>
                </tr>
            </tfoot>
	    </table>
		<hr>
	    </div>
    </div>
@endsection