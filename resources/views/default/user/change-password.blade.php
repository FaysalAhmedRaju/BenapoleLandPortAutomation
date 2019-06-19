@extends('layouts.master')
@section('title','Change Password')
@section('style')
	<style type="text/css">
		[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
	</style>
@endsection
@section('script')
	{!!Html :: script('js/customizedAngular/changePassword.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="changePasswordApp" ng-controller="changePasswordCtrl">
		<div class="col-md-11 col-md-offset-1">
			<div class="col-md-4">
				<span><b>Name:</b></span>
				<span>{{ Auth::user()->name }}</span>
			</div>
			<div class="col-md-4">
				<span><b>Email:</b></span>
				<span>{{ Auth::user()->email }}</span>
			</div>
			<div class="col-md-4">
				<span><b>Role:</b></span>
				<span>{{ Auth::user()->role->name }}</span>
			</div>
		</div>
		<div class="col-md-12" style="background-color: #dbd3ff; border-radius: 20px;">
			<form name="changePasswordForm" id="changePasswordForm" novalidate>
				<h4 class="text-center ok">Change Password</h4>
				<div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="col-md-12">
                	<table>
						<tr>
							<th>Old Password:</th>
							<td>
								<input class="form-control" type="password" name="old_password" ng-model="old_password" required>
								<span class="error" ng-show="changePasswordForm.old_password.$invalid && submitted">Old Password is required.</span>
							</td>
							<th style="padding-left: 15px;">New Password:</th>
							<td>
								<input class="form-control" type="password" name="new_password" ng-model="new_password" required>
								<span class="error" ng-show="changePasswordForm.new_password.$invalid && submitted">New Password is required.</span>
							</td>
							<th style="padding-left: 15px;">Confirm Password:</th>
							<td>
								<input class="form-control" type="password" name="confirm_password" ng-model="confirm_password" required ng-pattern="new_password">
								<span class="error" ng-show="changePasswordForm.confirm_password.$error.required && submitted">Confirm Password is required.</span>
								<span class="error" ng-show="changePasswordForm.confirm_password.$error.pattern && submitted">Password Do not Match</span>
							</td>
						</tr>
						<tr>
							<td colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="6" class="text-center">
								<button type="button" ng-if="saveBtn" class="btn btn-success" ng-click="ChangePassword()">Save</button><br>
								<span ng-if="dataLoading">
									<img src="img/dataLoader.gif" width="250" height="15">
									<br> Please Wait !
								</span>
							</td>
						</tr>
					</table>
					<br>
				</div>
			</form>
		</div>
	</div>
	
@endsection