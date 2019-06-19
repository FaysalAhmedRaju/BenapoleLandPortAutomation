@extends('layouts.master')
@section('title','Heading/Sub-Heading')
@section('style')
	<style type="text/css">
		[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
	</style>
@endsection
@section('script')
	{!!Html::script('js/customizedAngular/HeadOrSubHead.js')!!}
	{!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="HeadOrSubHeadApp" ng-controller="HeadOrSubHeadController">
		<div class="col-md-6" id="head">
			<div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
				<h4 class="text-center ok">Head Entry</h4>
				<div class="alert alert-success" id="savingSuccessHead" ng-hide="!savingSuccessHead">@{{ savingSuccessHead }}</div>
                <div class="alert alert-danger" id="savingErrorHead" ng-hide="!savingErrorHead">@{{ savingErrorHead }}</div>

                <div class="col-md-8 col-md-offset-2">
					{{--<form name="Account_Form" id="Account_Form" novalidate>--}}
                	<table>
							<tr>
								<th>Type:</th>
								<td>
									<select {{--name="singleSelect"--}}ng-init="type='0'" {{--ng-model="data.singleSelect"--}} id="type" name="type" ng-model="type" class="form-control">
										<option value="0" selected>Income</option>
										<option value="1">Expenditure</option>
										<option value="2">Income(Others)</option>
									</select>
								</td>
							</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>

                		<tr>
                			<th>
                				Head:
                			</th>
                			<td>
                				<input class="form-control" type="text" name="acc_head" ng-model="acc_head" id="acc_head">
                			</td>
                			<td>
                				<button type="button" class="btn btn-primary" ng-click="saveHead()" ng-if="HeadAddBtn" ng-disabled="!acc_head || !type">
                					Add
                				</button>
                				<button type="button" class="btn btn-success" ng-click="editHead()" ng-if="HeadEditBtn">
                					Edit
                				</button>
                			</td>
                		</tr>
                		<tr>
                			<td colspan="3">&nbsp;</td>
                		</tr>
                	</table>
					{{--</form>--}}
                </div>
            </div>
            <div class="col-md-12">
            	<table class="table table-bordered" ng-show="headTable">
            		<caption><h4 class="text-center ok">Head List</h4></caption>
            		<thead>
            			<tr>
            				<th>S/L</th>
            				<th>Name</th>
							<th>Type</th>
            				<th>Action</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr dir-paginate="head in allHeadData | orderBy:'head.id' | itemsPerPage:headPerPage" pagination-id="head">
            				<td>@{{$index + headSerial}}</td>
            				<td>@{{head.acc_head}}</td>
							<td>@{{head.in_ex_status | accountTypeFilter}}</td>
            				<td>
            					<button type="button" class="btn btn-success btn-sm" ng-click="editHeadBtn(head)">
            						Edit
            					</button>
            					<button type="button" class="btn btn-danger btn-sm" ng-click="deleteHeadBtn(head)">
            						Delete
            					</button>
            				</td>
            			</tr>
            		</tbody>
            		<tfoot>
                        <tr>
                            <td colspan="4" class="text-center">
                                <dir-pagination-controls max-size="5" on-page-change="getPageCount(newPageNumber)"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="head">
                                </dir-pagination-controls>
                            </td>
                        </tr>
                    </tfoot>
            	</table>
			</div>
		</div>
		<div class="col-md-6" id="subHead">
			<div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
				<h4 class="text-center ok">Sub-Head Entry</h4>
				<div class="alert alert-success" id="savingSuccessSubHead" ng-hide="!savingSuccessSubHead">@{{ savingSuccessSubHead }}</div>
                <div class="alert alert-danger" id="savingErrorSubHead" ng-hide="!savingErrorSubHead">@{{ savingErrorSubHead }}</div>
                <div class="col-md-10 col-md-offset-2">
                	<table>
                		<tr>
                			<th>
                				Head:
                			</th>
                			<td>
                				<select class="form-control" name="head_id" ng-model="head_id" ng-options="head.id as head.acc_head for head in allHeadData" ng-change="getSubHead(head_id)">
                                </select>
                			</td>
                		</tr>
                		<tr>
                			<td colspan="3">&nbsp;</td>
                		</tr>
                		<tr>
                			<th>
                				Sub-Head:
                			</th>
                			<td>
                				<input class="form-control" type="text" name="acc_sub_head" ng-model="acc_sub_head" id="acc_sub_head">
                			</td>
                			<td>
                				<button type="button" class="btn btn-primary" ng-click="postSubHead()" ng-if="subHeadAddBtn" ng-disabled="!acc_sub_head">
                					Add
                				</button>
                				<button type="button" class="btn btn-success" ng-click="editSubHead()" ng-if="subHeadEditBtn">
                					Edit
                				</button>
                			</td>
                		</tr>
                		<tr>
                			<td colspan="3">&nbsp;</td>
                		</tr>
                	</table>
                </div>
			</div>
            <div class="col-md-12">
            	<table class="table table-bordered" ng-show="subHeadTable">
            		<caption><h4 class="text-center ok">Sub-Head List</h4></caption>
            		<thead>
            			<tr>
            				<th>S/L</th>
            				<th>Name</th>
            				<th>Action</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr dir-paginate="subHead in allSubHeadData | orderBy:'subHead.id' | itemsPerPage:subHeadPerPage" pagination-id="subHead">
            				<td>@{{$index+subHeadSerial}}</td>
            				<td>@{{subHead.acc_sub_head}}</td>
            				<td>
            					<button type="button" class="btn btn-success btn-sm" ng-click="editSubHeadBtn(subHead)">
            						Edit
            					</button>
            					<button type="button" class="btn btn-danger btn-sm" ng-click="deleteSubHeadBtn(subHead)">
            						Delete
            					</button>
            				</td>
            			</tr>
            		</tbody>
            		<tfoot>
                        <tr>
                            <td colspan="3" class="text-center">
                                <dir-pagination-controls max-size="5" on-page-change="getSubHeadPageCount(newPageNumber)"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="subHead">
                                </dir-pagination-controls>
                            </td>
                        </tr>
                    </tfoot>
            	</table>
			</div>
		</div>
	</div>
@endsection
